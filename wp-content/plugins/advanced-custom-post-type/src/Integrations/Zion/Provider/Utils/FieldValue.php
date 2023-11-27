<?php

namespace ACPT\Integrations\Zion\Provider\Utils;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Costants\MetaTypes;
use ACPT\Utils\Wordpress\WPAttachment;

class FieldValue
{
	/**
	 * @param $belongsTo
	 * @param AbstractMetaBoxFieldModel $metaFieldModel
	 *
	 * @return mixed|null
	 */
	public static function raw($belongsTo, AbstractMetaBoxFieldModel $metaFieldModel)
	{
		switch ($belongsTo){
			case MetaTypes::CUSTOM_POST_TYPE:

				global $post;

				if(empty($post)){
					return null;
				}

				if($metaFieldModel->getType() === AbstractMetaBoxFieldModel::REPEATER_TYPE){
					return self::repeaterToTextualValues(get_acpt_field([
						'post_id' => $post->ID,
						'box_name' => $metaFieldModel->getMetaBox()->getName(),
						'field_name' => $metaFieldModel->getName(),
					]));
				}

				return get_acpt_field([
					'post_id' => $post->ID,
					'box_name' => $metaFieldModel->getMetaBox()->getName(),
					'field_name' => $metaFieldModel->getName(),
				]);

				break;

			case MetaTypes::OPTION_PAGE:

				if($metaFieldModel->getType() === AbstractMetaBoxFieldModel::REPEATER_TYPE){
					return self::repeaterToTextualValues(get_acpt_field([
						'post_id' => $post->ID,
						'box_name' => $metaFieldModel->getMetaBox()->getName(),
						'field_name' => $metaFieldModel->getName(),
					]));
				}

				return get_acpt_option_page_field([
					'option_page' => $metaFieldModel->getMetaBox()->getOptionPage(),
					'box_name' => $metaFieldModel->getMetaBox()->getName(),
					'field_name' => $metaFieldModel->getName(),
				]);

				break;

			case MetaTypes::TAXONOMY:

				$queriedObject = get_queried_object();

				if(!$queriedObject instanceof \WP_Term){
					return null;
				}

				$termId = $queriedObject->term_id;

				return get_acpt_tax_field([
					'term_id' => $termId,
					'box_name' => $metaFieldModel->getMetaBox()->getName(),
					'field_name' => $metaFieldModel->getName(),
				]);

				break;
		}

		return null;
	}

	/**
	 * @param array $values
	 *
	 * @return array
	 */
	private static function repeaterToTextualValues($values = [])
	{
		foreach ($values as $index => $group){
			foreach ($group as $key => $value){

				if($value instanceof WPAttachment){
					$values[$index][$key] = $value->getSrc();
				}

				if(is_array($value)){
					if(isset($value['file']) and $value['file'] instanceof WPAttachment){
						$values[$index][$key] = $value['file']->getSrc();
					} elseif(isset($value['url'])){
						$values[$index][$key] = $value['url'];
					} elseif(isset($value['amount']) and isset($value['unit'])){
						$values[$index][$key] = $value['amount'] . ' ' . $value['unit'];
					} elseif(isset($value['length']) and isset($value['unit'])){
						$values[$index][$key] = $value['length'] . ' ' . $value['unit'];
					} elseif(isset($value['weight']) and isset($value['unit'])){
						$values[$index][$key] = $value['weight'] . ' ' . $value['unit'];
					} else {
						$values[$index][$key] = implode(', ', $value);
					}
				}
			}
		}

		return $values;
	}
}



