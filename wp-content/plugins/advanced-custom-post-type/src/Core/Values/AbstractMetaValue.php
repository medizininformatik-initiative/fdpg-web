<?php

namespace ACPT\Core\Values;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Core\Models\CustomPostType\CustomPostTypeModel;
use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;
use ACPT\Utils\Wordpress\WPAttachment;

abstract class AbstractMetaValue
{
	/**
	 * @var OptionPageMetaBoxFieldModel
	 */
	protected $metaFieldModel;

	/**
	 * OptionPageMetaValue constructor.
	 *
	 * @param AbstractMetaBoxFieldModel $metaFieldModel
	 */
	public function __construct(AbstractMetaBoxFieldModel $metaFieldModel)
	{
		$this->metaFieldModel = $metaFieldModel;
	}

	/**
	 * Retrieve save data by key
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	abstract protected function getData($key = '');

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		$saved_field_type = $this->getData('_type');
		$saved_field_value = $this->getData();

		$before = null;
		$after  = null;

		$advanced_options = $this->metaFieldModel->getAdvancedOptions();

		if(is_array($advanced_options)){
			foreach ($advanced_options as $advanced_option){
				if($advanced_option->getKey() === 'after'){
					$after = $advanced_option->getValue();
				}

				if($advanced_option->getKey() === 'before'){
					$before = $advanced_option->getValue();
				}
			}
		}

		switch ($saved_field_type){

			// CURRENCY_TYPE
			case AbstractMetaBoxFieldModel::CURRENCY_TYPE:

				$unit = $this->getData('_currency');

				return [
					'amount' => $before.$saved_field_value.$after,
					'unit' => $unit
				];

			// DATE_RANGE_TYPE
			case AbstractMetaBoxFieldModel::DATE_RANGE_TYPE:

				$saved_field_value = explode(" - ", $saved_field_value);

				return [
					$before.$saved_field_value[0].$after,
					$before.$saved_field_value[1].$after,
				];

			// GALLERY_TYPE
			case AbstractMetaBoxFieldModel::GALLERY_TYPE:

				$id = $this->getData('_id');

				if(!empty($id)){
					$ids = explode(",", $id);
					$gallery = [];

					foreach ($ids as $_id){
						$wpAttachment = WPAttachment::fromId($_id);
						$gallery[] = $wpAttachment;
					}

					return $gallery;
				}

				if(is_array($saved_field_value)){

					$gallery = [];

					foreach ($saved_field_value as $image){
						$wpAttachment = WPAttachment::fromUrl($image);
						$gallery[] = $wpAttachment;
					}

					return $gallery;
				}

				$wpAttachment = WPAttachment::fromUrl($saved_field_value);

				return $wpAttachment;

			// FILE_TYPE
			case AbstractMetaBoxFieldModel::FILE_TYPE:

				$label = $this->getData('_label');
				$id = $this->getData('_id');

				if(!empty($id)){
					$wpAttachment = WPAttachment::fromId($id);
				} else {
					$wpAttachment = WPAttachment::fromUrl($saved_field_value);
				}

				return [
					'file' => (!$wpAttachment->isEmpty() ? $wpAttachment : null),
					'label' => $label
				];

			// IMAGE_TYPE
			// VIDEO_TYPE
			case AbstractMetaBoxFieldModel::IMAGE_TYPE:
			case AbstractMetaBoxFieldModel::VIDEO_TYPE:

				$id = $this->getData('_id');

				if(!empty($id)){
					return WPAttachment::fromId($id);
				}

				return  WPAttachment::fromUrl($saved_field_value);

			// LENGTH_TYPE
			case AbstractMetaBoxFieldModel::LENGTH_TYPE:

				$unit = $this->getData('_length');

				return [
					'length' => $before.$saved_field_value.$after,
					'unit' => $unit
				];

			// EDITOR_TYPE
			case AbstractMetaBoxFieldModel::EDITOR_TYPE:
				return wpautop($before.$saved_field_value.$after);

			// LIST_TYPE
			case AbstractMetaBoxFieldModel::CHECKBOX_TYPE:
			case AbstractMetaBoxFieldModel::SELECT_MULTI_TYPE:
			case AbstractMetaBoxFieldModel::LIST_TYPE:

				$return = [];
				if(is_array($saved_field_value)){
					foreach ($saved_field_value as $value){
						$return[] = $before.$value.$after;
					}
				}

				return $return;

			// NUMBER_TYPE
			case AbstractMetaBoxFieldModel::NUMBER_TYPE:
			case AbstractMetaBoxFieldModel::RANGE_TYPE:
				$saved_field_value = Strings::convertStringToNumber($saved_field_value);

				if(empty($before) and empty($after)){
					return $saved_field_value;
				}

				return $before.$saved_field_value.$after;

			// POST_TYPE
			case AbstractMetaBoxFieldModel::POST_TYPE:

				$relations = $this->metaFieldModel->getRelations();

				if(empty($relations)){
					return [];
				}

				$relation = $relations[0];
				$array_of_post_ids = (!is_array($saved_field_value)) ? [$saved_field_value] : $saved_field_value;

				/** @var CustomPostTypeModel $related_post */
				$related_post = $relation->getRelatedEntity()->getValue();

				return get_posts([
					'post__in' => array_map('intval', $array_of_post_ids),
					'post_type' => $related_post->getName(),
				]);

			// FLEXIBLE TYPE
			case AbstractMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE:
				return $this->getNestedBlockValues($saved_field_value, $before, $after);

			// REPEATER_TYPE
			case AbstractMetaBoxFieldModel::REPEATER_TYPE:
				return $this->getNestedValues($saved_field_value, $before, $after);

			// WEIGHT_TYPE
			case AbstractMetaBoxFieldModel::WEIGHT_TYPE:

				$unit = $this->getData('_weight');

				return [
					'weight' => $before.$saved_field_value.$after,
					'unit' => $unit
				];

			// URL
			case AbstractMetaBoxFieldModel::URL_TYPE:
				$label = $this->getData('_label');

				return [
					'url' => $before.$saved_field_value.$after,
					'label' => $label
				];

			default:
				return $before.$saved_field_value.$after;
		}
	}

	/**
	 * @param $saved_field_value
	 * @param null $before
	 * @param null $after
	 *
	 * @return array|bool
	 */
	private function getNestedBlockValues($saved_field_value, $before = null, $after = null)
	{
		if(!is_array($saved_field_value) or !isset($saved_field_value['blocks'])) {
			return false;
		}

		$values = [];

		foreach ($saved_field_value['blocks'] as $block_index => $block){

			if(!is_array($block)) {
				return false;
			}

			foreach ($block as $block_name => $block_fields){
				foreach ($block_fields as $block_field){
					foreach ($block_field as $block_field_index => $block_field_value){
						$values['blocks'][$block_index][$block_name][$block_field_value['original_name']][$block_field_index] = $this->getNestedFieldValue($block_field_value, $before, $after);
					}
				}
			}
		}

		return $values;
	}

	/**
	 * @param $saved_field_value
	 * @param null $before
	 * @param null $after
	 *
	 * @return array|bool
	 */
	private function getNestedValues($saved_field_value, $before = null, $after = null)
	{
		if(!is_array($saved_field_value)) {
			return false;
		}

		$values = [];

		$keys = array_keys($saved_field_value);
		$firstKey = $keys[0];
		$firstElement = $saved_field_value[$firstKey];

		for ($i=0;$i<count($firstElement);$i++){
			$element = [];
			foreach (array_keys($saved_field_value) as $index => $key){
				if(isset($saved_field_value[$key]) and isset($saved_field_value[$key][$i])){
					$rawData = $saved_field_value[$key][$i];
					$element[$rawData['original_name']] =  $this->getNestedFieldValue($rawData, $before, $after);
				}
			}

			$values[] = $element;
		}

		return $values;
	}

	/**
	 * @param array $rawData
	 * @param null $before
	 * @param null $after
	 *
	 * @return WPAttachment|array|mixed|null
	 */
	private function getNestedFieldValue(array $rawData = [], $before = null, $after = null)
	{
		$type = is_array($rawData['type']) ? $rawData['type'][0] : $rawData['type'];
		$value = $rawData['value'];
		$id = (isset($rawData['id'])) ? $rawData['id'] : null;

		switch ($type){

			case AbstractMetaBoxFieldModel::ADDRESS_TYPE:
				return [
					'address' => $before.$value.$after,
					'lat' => $rawData['lat'],
					'lng' => $rawData['lng'],
				];

			case AbstractMetaBoxFieldModel::CURRENCY_TYPE:
				return [
					'amount' => $before.$value.$after,
					'unit' => $rawData['currency'],
				];

			case AbstractMetaBoxFieldModel::GALLERY_TYPE:

				if(!empty($id)){
					$ids = explode(',', $id);

					$gallery = [];

					foreach ($ids as $_id){
						$wpAttachment = WPAttachment::fromId($_id);
						if(!$wpAttachment->isEmpty()){
							$gallery[] = $wpAttachment;
						}
					}

					return $gallery;
				}

				if(is_array($value)){
					$gallery = [];

					foreach ($value as $image){
						$wpAttachment = WPAttachment::fromUrl($image);
						if(!$wpAttachment->isEmpty()){
							$gallery[] = $wpAttachment;
						}
					}

					return $gallery;
				}

				$wpAttachment = WPAttachment::fromUrl($value);

				if($wpAttachment->isEmpty()){
					return null;
				}

				return $wpAttachment;

			case AbstractMetaBoxFieldModel::FILE_TYPE:

				$label = (isset($rawData['label']) and !empty($rawData['label'])) ? $rawData['label'] : null;

				if(!empty($id)){
					$wpAttachment = WPAttachment::fromId($id);
				} else {
					$wpAttachment = WPAttachment::fromUrl($value);
				}

				return [
					'file' => (!$wpAttachment->isEmpty() ? $wpAttachment : null),
					'label' => $label
				];

			case AbstractMetaBoxFieldModel::IMAGE_TYPE:
			case AbstractMetaBoxFieldModel::VIDEO_TYPE:

				if(!empty($id)){
					return WPAttachment::fromId($id);
				}

				$wpAttachment = WPAttachment::fromUrl($value);

				if($wpAttachment->isEmpty()){
					return null;
				}

				return $wpAttachment;

			case AbstractMetaBoxFieldModel::LENGTH_TYPE:
				return [
					'length' => $before.$value.$after,
					'unit' => $rawData['length'],
				];

			case AbstractMetaBoxFieldModel::NUMBER_TYPE:
				return Strings::convertStringToNumber($value);

			case AbstractMetaBoxFieldModel::WEIGHT_TYPE:
				return [
					'weight' => $before.$value.$after,
					'unit' => $rawData['weight'],
				];

			case AbstractMetaBoxFieldModel::URL_TYPE:
				return [
					'url' => $before.$value.$after,
					'label' => (isset($rawData['label']) and !empty($rawData['label'])) ? $rawData['label'] : null,
				];

			default:
				return $value;
		}
	}
}