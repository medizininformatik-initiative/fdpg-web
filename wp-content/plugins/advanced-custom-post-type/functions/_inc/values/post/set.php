<?php

use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Core\Validators\ArgumentsArrayValidator;
use ACPT\Costants\MetaTypes;
use ACPT\Includes\ACPT_DB;
use ACPT\Utils\Data\NestedValues;
use ACPT\Utils\Data\Sanitizer;
use ACPT\Utils\PHP\GeoLocation;
use ACPT\Utils\Wordpress\WPAttachment;

if( !function_exists('add_acpt_meta_field_row_value') ){

	/**
	 * Add meta field row value (only for Repeater and List fields).
	 *
	 * @param array $args
	 *
	 * @return bool
	 */
	function add_acpt_meta_field_row_value(array $args = [])
	{
		try {
			// validate array
			$mandatory_keys = [
				'post_id' => [
					'required' => true,
					'type' => 'integer',
				],
				'box_name' => [
					'required' => true,
					'type' => 'string',
				],
				'field_name' => [
					'required' => true,
					'type' => 'string',
				],
				'value' => [
					'required' => true,
					'type' => 'string|integer|array|object',
				],
			];

			$validator = new ArgumentsArrayValidator();

			if(!$validator->validate($mandatory_keys, $args)){
				return false;
			}

			$post_type = get_post_type($args['post_id']);

			$meta_field_model = MetaRepository::getMetaFieldByName([
				'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
				'find' => $post_type,
				'boxName' => $args['box_name'],
				'fieldName' => $args['field_name']
			]);

			if($meta_field_model === null){
				return false;
			}

			$meta_field_model_type = $meta_field_model->getType();

			if($meta_field_model_type !== CustomPostTypeMetaBoxFieldModel::LIST_TYPE and $meta_field_model_type !== CustomPostTypeMetaBoxFieldModel::REPEATER_TYPE){
				return false;
			}

			$value = $args['value'];

			$saved_field_type = get_post_meta($args['post_id'], $meta_field_model->getDbName().'_type', true);
			$saved_field_value = get_post_meta($args['post_id'], $meta_field_model->getDbName(), true);

			if($saved_field_type !== $meta_field_model_type){
				if(!update_post_meta($args['post_id'], $meta_field_model->getDbName().'_type', $meta_field_model_type)){
					return false;
				}
			}

			switch ($meta_field_model_type){

				// LIST_TYPE
				case CustomPostTypeMetaBoxFieldModel::LIST_TYPE:

					if(empty($saved_field_value)){
						$values = [$value];
					} else {
						array_push($saved_field_value, $value);
						$values = $saved_field_value;
					}

					if(!update_post_meta($args['post_id'], $meta_field_model->getDbName(), Sanitizer::sanitizeRawData(CustomPostTypeMetaBoxFieldModel::LIST_TYPE, $values))){
						return false;
					}

					break;

				// REPEATER_TYPE
				case CustomPostTypeMetaBoxFieldModel::REPEATER_TYPE:

					$values = NestedValues::addOrUpdateRawValue(
						$meta_field_model,
						(empty($saved_field_value)) ? [] : $saved_field_value,
						$value,
						null);

					if(!update_post_meta($args['post_id'], $meta_field_model->getDbName(), Sanitizer::sanitizeRawData(CustomPostTypeMetaBoxFieldModel::REPEATER_TYPE, $values))){
						return false;
					}

					break;
			}

			return true;

		} catch (\Exception $exception){
			return false;
		}
	}
}

if( !function_exists('add_acpt_meta_field_value') ){

	/**
	 * Add meta field value.
	 *
	 * @param array $args
	 *
	 * @return bool
	 */
	function add_acpt_meta_field_value(array $args = [])
	{
		try {
			// validate array
			$mandatory_keys = [
				'post_id' => [
					'required' => true,
					'type' => 'integer',
				],
				'box_name' => [
					'required' => true,
					'type' => 'string',
				],
				'field_name' => [
					'required' => true,
					'type' => 'string',
				],
				'value' => [
					'required' => true,
					'type' => 'double|float|boolean|string|integer|array|object',
				],
			];

			$validator = new ArgumentsArrayValidator();

			if(!$validator->validate($mandatory_keys, $args)){
				return false;
			}

			$post_type = get_post_type($args['post_id']);

			$meta_field_model = MetaRepository::getMetaFieldByName([
				'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
				'find' => $post_type,
				'boxName' => $args['box_name'],
				'fieldName' => $args['field_name']
			]);

			if($meta_field_model === null){
				return false;
			}

			$meta_field_model_type = $meta_field_model->getType();
			$value = $args['value'];

			$saved_field_type = get_post_meta($args['post_id'], $meta_field_model->getDbName().'_type', true);
			if($saved_field_type !== $meta_field_model_type){
				if(!update_post_meta($args['post_id'], $meta_field_model->getDbName().'_type', $meta_field_model_type)){
					return false;
				}
			}

			switch ($meta_field_model_type){

				// ADDRESS_TYPE
				case CustomPostTypeMetaBoxFieldModel::ADDRESS_TYPE:

					$saved_field_value = get_post_meta($args['post_id'], $meta_field_model->getDbName(), true);

					if($saved_field_value !== $value){

						if(!update_post_meta($args['post_id'], $meta_field_model->getDbName(), Sanitizer::sanitizeRawData(CustomPostTypeMetaBoxFieldModel::ADDRESS_TYPE, $value))){
							return false;
						}

						$coordinates = GeoLocation::getCoordinates($value);

						if($coordinates){
							if(!update_post_meta($args['post_id'], $meta_field_model->getDbName().'_lat', Sanitizer::sanitizeRawData(CustomPostTypeMetaBoxFieldModel::TEXT_TYPE, $coordinates['lat']))){
								return false;
							}

							if(!update_post_meta($args['post_id'], $meta_field_model->getDbName().'_lng', Sanitizer::sanitizeRawData(CustomPostTypeMetaBoxFieldModel::TEXT_TYPE, $coordinates['lng']))){
								return false;
							}
						}
					}

					break;

				// CURRENCY_TYPE
				case CustomPostTypeMetaBoxFieldModel::CURRENCY_TYPE:

					$mandatory_keys = [
						'amount' => [
							'required' => true,
							'type' => 'integer',
						],
						'unit' => [
							'required' => true,
							'type' => 'string',
						],
					];

					$validator = new ArgumentsArrayValidator();

					if(!$validator->validate($mandatory_keys, $value)){
						return false;
					}

					$amount = $value['amount'];
					$unit = $value['unit'];

					$saved_field_value = get_post_meta($args['post_id'], $meta_field_model->getDbName(), true);
					$saved_field_unit = get_post_meta($args['post_id'], $meta_field_model->getDbName().'_currency', true);

					if($saved_field_value !== $amount){
						if(!update_post_meta($args['post_id'], $meta_field_model->getDbName(), Sanitizer::sanitizeRawData(CustomPostTypeMetaBoxFieldModel::CURRENCY_TYPE, $amount))){
							return false;
						}
					}

					if($saved_field_unit !== $unit){
						if(!update_post_meta($args['post_id'], $meta_field_model->getDbName().'_currency', Sanitizer::sanitizeRawData(CustomPostTypeMetaBoxFieldModel::TEXT_TYPE, $unit))){
							return false;
						}
					}

					break;

				// DATE_RANGE_TYPE
				case CustomPostTypeMetaBoxFieldModel::DATE_RANGE_TYPE:

					if(!is_array($value)){
						return false;
					}

					$value = implode(" - ", $value);
					if(!update_post_meta($args['post_id'], $meta_field_model->getDbName(), Sanitizer::sanitizeRawData($meta_field_model_type, $value))){
						return false;
					}

					break;

				// EMBED_TYPE
				case CustomPostTypeMetaBoxFieldModel::EMBED_TYPE:

					$embed = (new \WP_Embed())->shortcode([
						'width' => 180,
						'height' => 135,
					], $value);

					if(!Strings::contains('<iframe', $embed)){
						return false;
					}

					$saved_field_value = get_post_meta($args['post_id'], $meta_field_model->getDbName(), true);
					if($saved_field_value !== $value){
						if(!update_post_meta($args['post_id'], $meta_field_model->getDbName(), Sanitizer::sanitizeRawData($meta_field_model_type, $value))){
							return false;
						}
					}

					break;

				// FILE_TYPE
				case CustomPostTypeMetaBoxFieldModel::FILE_TYPE:

					$mandatory_keys = [
						'url' => [
							'required' => true,
							'type' => 'string',
						],
						'label' => [
							'required' => true,
							'type' => 'string',
						],
					];

					$validator = new ArgumentsArrayValidator();

					if(!$validator->validate($mandatory_keys, $value)){
						return false;
					}

					$url = $value['url'];
					$label = $value['label'];

					$wpAttachment = WPAttachment::fromUrl($url);
					$idFile = $wpAttachment->getId();
					$isImage = $wpAttachment->isImage();
					$isVideo = $wpAttachment->isVideo();
					$fileData = $wpAttachment;

					if($fileData->isEmpty()){
						return false;
					}

					if($isImage or $isVideo){
						return false;
					}

					$saved_field_value = get_post_meta($args['post_id'], $meta_field_model->getDbName(), true);
					$saved_field_label = get_post_meta($args['post_id'], $meta_field_model->getDbName().'_label', true);
					$saved_file_id = get_post_meta($args['post_id'], $meta_field_model->getDbName().'_id', true);

					if($idFile !== $saved_file_id){
						if(!update_post_meta($args['post_id'], $meta_field_model->getDbName().'_id', $idFile)){
							return false;
						}
					}

					if($saved_field_value !== $url){
						if(!update_post_meta($args['post_id'], $meta_field_model->getDbName(), Sanitizer::sanitizeRawData($meta_field_model_type, $url))){
							return false;
						}
					}

					if($saved_field_label !== $label){
						if(!update_post_meta($args['post_id'], $meta_field_model->getDbName().'_label', Sanitizer::sanitizeRawData(CustomPostTypeMetaBoxFieldModel::TEXT_TYPE, $label))){
							return false;
						}
					}

					break;

				// GALLERY_TYPE
				case CustomPostTypeMetaBoxFieldModel::GALLERY_TYPE:

					if(!is_array($value)){
						return false;
					}

					$idFiles = [];

					foreach ($value as $image){
						$wpAttachment = WPAttachment::fromUrl($image);
						$isImage = $wpAttachment->isImage();
						$idFile = $wpAttachment->getId();

						if(!$isImage){
							return false;
						}

						$idFiles[] = $idFile;
					}

					$idFiles = implode(",", $idFiles);
					$saved_file_ids = get_post_meta($args['post_id'], $meta_field_model->getDbName().'_id', true);

					if($saved_file_ids !== $idFiles){
						if(!update_post_meta($args['post_id'], $meta_field_model->getDbName().'_id', $idFiles)){
							return false;
						}
					}

					$saved_field_value = get_post_meta($args['post_id'], $meta_field_model->getDbName(), true);

					if($saved_field_value !== $value){
						if(!update_post_meta($args['post_id'], $meta_field_model->getDbName(), Sanitizer::sanitizeRawData($meta_field_model_type, $value))){
							return false;
						}
					}

					break;

				// IMAGE_TYPE
				case CustomPostTypeMetaBoxFieldModel::IMAGE_TYPE:

					$wpAttachment = WPAttachment::fromUrl($value);
					$isImage = $wpAttachment->isImage();
					$idFile = $wpAttachment->getId();

					if(!$isImage){
						return false;
					}

					$saved_field_value = get_post_meta($args['post_id'], $meta_field_model->getDbName(), true);
					$saved_file_id = get_post_meta($args['post_id'], $meta_field_model->getDbName().'_id', true);

					if($saved_file_id !== $idFile){
						if(!update_post_meta($args['post_id'], $meta_field_model->getDbName().'_id', $idFile)){
							return false;
						}
					}

					if($saved_field_value !== $value){
						if(!update_post_meta($args['post_id'], $meta_field_model->getDbName(), Sanitizer::sanitizeRawData($meta_field_model_type, $value))){
							return false;
						}
					}

					break;

				// LENGTH_TYPE
				case CustomPostTypeMetaBoxFieldModel::LENGTH_TYPE:

					$mandatory_keys = [
						'length' => [
							'required' => true,
							'type' => 'integer',
						],
						'unit' => [
							'required' => true,
							'type' => 'string',
						],
					];

					$validator = new ArgumentsArrayValidator();

					if(!$validator->validate($mandatory_keys, $value)){
						return false;
					}

					$length = $value['length'];
					$unit = $value['unit'];

					$saved_field_value = get_post_meta($args['post_id'], $meta_field_model->getDbName(), true);
					$saved_field_unit = get_post_meta($args['post_id'], $meta_field_model->getDbName().'_length', true);

					if($saved_field_value !== $length){
						if(!update_post_meta($args['post_id'], $meta_field_model->getDbName(), Sanitizer::sanitizeRawData(CustomPostTypeMetaBoxFieldModel::WEIGHT_TYPE, $length))){
							return false;
						}
					}

					if($saved_field_unit !== $unit){
						if(!update_post_meta($args['post_id'], $meta_field_model->getDbName().'_length', Sanitizer::sanitizeRawData(CustomPostTypeMetaBoxFieldModel::TEXT_TYPE, $unit))){
							return false;
						}
					}

					break;

				case CustomPostTypeMetaBoxFieldModel::NUMBER_TYPE:
				case CustomPostTypeMetaBoxFieldModel::RANGE_TYPE:

					$min = $meta_field_model->getAdvancedOption('min');
					$max = $meta_field_model->getAdvancedOption('max');

					if($min !== null and (int)$value < (int)$min){
						return false;
					}

					if($max !== null and (int)$value > (int)$max){
						return false;
					}

					$saved_field_value = get_post_meta($args['post_id'], $meta_field_model->getDbName(), true);
					if($saved_field_value !== $value){
						if(!update_post_meta($args['post_id'], $meta_field_model->getDbName(), Sanitizer::sanitizeRawData($meta_field_model_type, $value))){
							return false;
						}
					}

					break;

				// LIST_TYPE
				// REPEATER_TYPE
				case CustomPostTypeMetaBoxFieldModel::LIST_TYPE:
				case CustomPostTypeMetaBoxFieldModel::REPEATER_TYPE:

					if(!is_array($value)){
						return false;
					}

					foreach ($value as $item){
						if(!add_acpt_meta_field_row_value([
							'post_id' => $args['post_id'],
							'box_name' => $args['box_name'],
							'field_name' => $args['field_name'],
							'value' => $item,
						])){
							return false;
						}
					}

					break;

				// FLEXIBLE_CONTENT_TYPE
				case CustomPostTypeMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE:

					if(!is_array($value)){
						return false;
					}

					if(!isset($value['blocks'])){
						return false;
					}

					foreach ($value['blocks'] as $block_index => $block){
						if(is_array($block)){
							foreach ($block as $block_name => $block_values){
								if(is_array($block_values)){
									foreach ($block_values as $nested_field_values){
										if(!add_acpt_meta_block_field_row_value([
											'post_id' => $args['post_id'],
											'box_name' => $args['box_name'],
											'field_name' => $args['field_name'],
											'block_name' => $block_name,
											'block_index' => $block_index,
											'value' => $nested_field_values,
										])){
											return false;
										}
									}
								}
							}
						}
					}

					break;

				// POST_TYPE
				case CustomPostTypeMetaBoxFieldModel::POST_TYPE:

					$relations = $meta_field_model->getRelations();

					if(empty($relations)){
						return false;
					}

					$relation = $relations[0];

					if($relation->isBidirectional() and $value !== null){

						$inversedIds = (!is_array($value)) ? [$value] : $value;

						foreach ($inversedIds as $inversedId){
							$inversed_field_name = $relation->getInversedBy()->getDbName();

							$saved_field_type = get_post_meta($inversedId, $inversed_field_name.'_type', true);
							if($saved_field_type !== $meta_field_model_type){
								if(!update_post_meta($inversedId, $inversed_field_name.'_type', $meta_field_model_type)){
									return false;
								}
							}

							$saved_field_value = get_post_meta($inversedId, $inversed_field_name, true);
							if($saved_field_value != $args['post_id']){
								if(!update_post_meta($inversedId, $inversed_field_name, Sanitizer::sanitizeRawData($meta_field_model_type, $args['post_id']))){
									return false;
								}
							}
						}
					}

					$saved_field_value = get_post_meta($args['post_id'], $meta_field_model->getDbName(), true);

					if($saved_field_value != $value){
						if(!update_post_meta($args['post_id'], $meta_field_model->getDbName(), Sanitizer::sanitizeRawData($meta_field_model_type, $value))){
							return false;
						}
					}

					break;

				// SELECT_TYPE
				case CustomPostTypeMetaBoxFieldModel::RADIO_TYPE:
				case CustomPostTypeMetaBoxFieldModel::SELECT_TYPE:

					if(in_array($value, $meta_field_model->getOptionValues())){
						$saved_field_value = get_post_meta($args['post_id'], $meta_field_model->getDbName(), true);
						if($saved_field_value !== $value){
							if(!update_post_meta($args['post_id'], $meta_field_model->getDbName(), Sanitizer::sanitizeRawData($meta_field_model_type, $value))){
								return false;
							}
						}

						return true;
					}

					return false;

					break;

				// SELECT_MULTI_TYPE
				case CustomPostTypeMetaBoxFieldModel::CHECKBOX_TYPE:
				case CustomPostTypeMetaBoxFieldModel::SELECT_MULTI_TYPE:

					if(!is_array($value)){
						return false;
					}

					$optionValues = $meta_field_model->getOptionValues();

					foreach ($value as $item){
						if(!in_array($item, $optionValues)){
							return false;
						}
					}

					$saved_field_value = get_post_meta($args['post_id'], $meta_field_model->getDbName(), true);
					if($saved_field_value !== $value){
						if(!update_post_meta($args['post_id'], $meta_field_model->getDbName(), Sanitizer::sanitizeRawData($meta_field_model_type, $value))){
							return false;
						}
					}

					break;

				// VIDEO_TYPE
				case CustomPostTypeMetaBoxFieldModel::VIDEO_TYPE:

					$wpAttachment = WPAttachment::fromUrl($value);
					$isVideo = $wpAttachment->isVideo();
					$idFile = $wpAttachment->getId();

					if(!$isVideo){
						return false;
					}

					$saved_field_value = get_post_meta($args['post_id'], $meta_field_model->getDbName(), true);
					$saved_file_id = get_post_meta($args['post_id'], $meta_field_model->getDbName().'_id', true);

					if($saved_file_id !== $idFile){
						if(!update_post_meta($args['post_id'], $meta_field_model->getDbName().'_id', $idFile)){
							return false;
						}
					}

					if($saved_field_value !== $value){
						if(!update_post_meta($args['post_id'], $meta_field_model->getDbName(), Sanitizer::sanitizeRawData($meta_field_model_type, $value))){
							return false;
						}
					}

					break;

				// WEIGHT_TYPE
				case CustomPostTypeMetaBoxFieldModel::WEIGHT_TYPE:

					$mandatory_keys = [
						'weight' => [
							'required' => true,
							'type' => 'integer',
						],
						'unit' => [
							'required' => true,
							'type' => 'string',
						],
					];

					$validator = new ArgumentsArrayValidator();

					if(!$validator->validate($mandatory_keys, $value)){
						return false;
					}

					$weight = $value['weight'];
					$unit = $value['unit'];

					$saved_field_value = get_post_meta($args['post_id'], $meta_field_model->getDbName(), true);
					$saved_field_unit = get_post_meta($args['post_id'], $meta_field_model->getDbName().'_weight', true);

					if($saved_field_value !== $weight){
						if(!update_post_meta($args['post_id'], $meta_field_model->getDbName(), Sanitizer::sanitizeRawData(CustomPostTypeMetaBoxFieldModel::WEIGHT_TYPE, $weight))){
							return false;
						}
					}

					if($saved_field_unit !== $unit){
						if(!update_post_meta($args['post_id'], $meta_field_model->getDbName().'_weight', Sanitizer::sanitizeRawData(CustomPostTypeMetaBoxFieldModel::TEXT_TYPE, $unit))){
							return false;
						}
					}

					break;

				// URL_TYPE
				case CustomPostTypeMetaBoxFieldModel::URL_TYPE:

					$mandatory_keys = [
						'url' => [
							'required' => true,
							'type' => 'string',
						],
						'label' => [
							'required' => true,
							'type' => 'string',
						],
					];

					$validator = new ArgumentsArrayValidator();

					if(!$validator->validate($mandatory_keys, $value)){
						return false;
					}

					$url = $value['url'];
					$label = $value['label'];

					$saved_field_value = get_post_meta($args['post_id'], $meta_field_model->getDbName(), true);
					$saved_field_label = get_post_meta($args['post_id'], $meta_field_model->getDbName().'_label', true);

					if($saved_field_value !== $url){
						if(!update_post_meta($args['post_id'], $meta_field_model->getDbName(), Sanitizer::sanitizeRawData($meta_field_model_type, $url))){
							return false;
						}
					}

					if($saved_field_label !== $label){
						if(!update_post_meta($args['post_id'], $meta_field_model->getDbName().'_label', Sanitizer::sanitizeRawData(CustomPostTypeMetaBoxFieldModel::TEXT_TYPE, $label))){
							return false;
						}
					}

					break;

				// RATING_TYPE
				case CustomPostTypeMetaBoxFieldModel::RATING_TYPE:
					if(!is_numeric($value)){
						return false;
					}

					if($value < 1){
						return false;
					}

					if($value > 10){
						return false;
					}

					$saved_field_value = get_post_meta($args['post_id'], $meta_field_model->getDbName(), true);
					if($saved_field_value !== $value){
						if(!update_post_meta($args['post_id'], $meta_field_model->getDbName(), Sanitizer::sanitizeRawData($meta_field_model_type, $value))){
							return false;
						}
					}
					break;

				// Default behaviour
				default:
					$saved_field_value = get_post_meta($args['post_id'], $meta_field_model->getDbName(), true);
					if($saved_field_value !== $value){
						if(!update_post_meta($args['post_id'], $meta_field_model->getDbName(), Sanitizer::sanitizeRawData($meta_field_model_type, $value))){
							return false;
						}
					}
			}

			return true;

		} catch (\Exception $exception){
			return false;
		}
	}
}

if( !function_exists('add_acpt_meta_block_field_row_value') ){

	/**
	 * Add meta field row value inside a block (only for Flexible field).
	 *
	 * @param array $args
	 *
	 * @return bool
	 */
	function add_acpt_meta_block_field_row_value(array $args = [])
	{
		try {
			// validate array
			$mandatory_keys = [
				'post_id' => [
					'required' => true,
					'type' => 'integer',
				],
				'box_name' => [
					'required' => true,
					'type' => 'string',
				],
				'field_name' => [
					'required' => true,
					'type' => 'string',
				],
				'block_name' => [
					'required' => true,
					'type' => 'string',
				],
				'block_index' => [
					'required' => true,
					'type' => 'string|integer',
				],
				'value' => [
					'required' => true,
					'type' => 'string|integer|array|object',
				],
			];

			$validator = new ArgumentsArrayValidator();

			if(!$validator->validate($mandatory_keys, $args)){
				return false;
			}

			$post_type = get_post_type($args['post_id']);

			$meta_field_model = MetaRepository::getMetaFieldByName([
				'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
				'find' => $post_type,
				'boxName' => $args['box_name'],
				'fieldName' => $args['field_name']
			]);

			if($meta_field_model === null){
				return false;
			}

			$meta_field_model_type = $meta_field_model->getType();

			if($meta_field_model_type !== CustomPostTypeMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE){
				return false;
			}

			$blockName = $args['block_name'];
			$blockIndex = $args['block_index'];
			$value = $args['value'];

			$saved_field_type = get_post_meta($args['post_id'], $meta_field_model->getDbName().'_type', true);
			$saved_field_value = get_post_meta($args['post_id'], $meta_field_model->getDbName(), true);

			if($saved_field_type !== $meta_field_model_type){
				if(!update_post_meta($args['post_id'], $meta_field_model->getDbName().'_type', $meta_field_model_type)){
					return false;
				}
			}

			$values = NestedValues::addOrUpdateBlockRawValue(
				$meta_field_model,
				$blockName,
				$blockIndex,
				(empty($saved_field_value)) ? [] : $saved_field_value,
				$value,
				null);

			if(!update_post_meta($args['post_id'], $meta_field_model->getDbName(), Sanitizer::sanitizeRawData(CustomPostTypeMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE, $values))){
				return false;
			}

			return true;

		} catch (\Exception $exception){
			return false;
		}
	}
}

if( !function_exists('delete_acpt_meta_field_value') ){

	/**
	 * Delete meta field value.
	 *
	 * @param array $args
	 *
	 * @return bool
	 */
	function delete_acpt_meta_field_value(array $args = [])
	{
		try {
			// validate array
			$mandatory_keys = [
				'post_id' => [
					'required' => true,
					'type' => 'integer',
				],
				'box_name' => [
					'required' => true,
					'type' => 'string',
				],
				'field_name' => [
					'required' => true,
					'type' => 'string',
				],
			];

			$validator = new ArgumentsArrayValidator();

			if(!$validator->validate($mandatory_keys, $args)){
				return false;
			}

			$post_type = get_post_type($args['post_id']);

			$meta_field_model = MetaRepository::getMetaFieldByName([
				'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
				'find' => $post_type,
				'boxName' => $args['box_name'],
				'fieldName' => $args['field_name']
			]);

			if($meta_field_model === null){
				return false;
			}

			global $wpdb;

			$sql = "
                DELETE FROM `{$wpdb->prefix}postmeta`
                WHERE meta_key LIKE %s AND post_id = %d
            ";

			ACPT_DB::executeQueryOrThrowException($sql, [
				$meta_field_model->getDbName().'%',
				$args['post_id']
			]);

			return true;

		} catch (\Exception $exception){
			return false;
		}
	}
}

if( !function_exists('delete_acpt_meta_field_row_value') ){

	/**
	 * Delete meta field row value (only for Repeater and List fields).
	 * Use array index [0, 1, 2, 3 etc.....]
	 *
	 * @param array $args
	 *
	 * @return bool
	 */
	function delete_acpt_meta_field_row_value(array $args = [])
	{
		try {
			// validate array
			$mandatory_keys = [
				'post_id' => [
					'required' => true,
					'type' => 'integer',
				],
				'box_name' => [
					'required' => true,
					'type' => 'string',
				],
				'field_name' => [
					'required' => true,
					'type' => 'string',
				],
				'index' => [
					'required' => true,
					'type' => 'integer',
				],
			];

			$validator = new ArgumentsArrayValidator();

			if(!$validator->validate($mandatory_keys, $args)){
				return false;
			}

			$post_type = get_post_type($args['post_id']);

			$meta_field_model = MetaRepository::getMetaFieldByName([
				'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
				'find' => $post_type,
				'boxName' => $args['box_name'],
				'fieldName' => $args['field_name']
			]);

			if($meta_field_model === null){
				return false;
			}

			$saved_field_value = get_post_meta($args['post_id'], $meta_field_model->getDbName(), true);
			$index = $args['index'];

			unset($saved_field_value[$index]);

			if(!update_post_meta($args['post_id'], $meta_field_model->getDbName(), Sanitizer::sanitizeRawData(CustomPostTypeMetaBoxFieldModel::LIST_TYPE, array_values($saved_field_value)))){
				return false;
			}

			$acpt_field = get_acpt_field([
				'post_id' => $args['post_id'],
				'box_name' => $args['box_name'],
				'field_name' => $args['field_name'],
			]);

			// delete the meta field value (not the meta field settings!)
			if(empty($acpt_field)){

				global $wpdb;

				$sql = "
	                DELETE FROM `{$wpdb->prefix}postmeta`
	                WHERE meta_key LIKE %s AND post_id = %d
	            ";

				ACPT_DB::executeQueryOrThrowException($sql, [
					$meta_field_model->getDbName().'%',
					$args['post_id']
				]);
			}

			return true;

		} catch (\Exception $exception){
			return false;
		}
	}
}

if( !function_exists('delete_acpt_meta_block_field_row_value') ){

	/**
	 * Delete meta field row value (only for Flexible fields).
	 * Use array index [0, 1, 2, 3 etc.....]
	 *
	 * @param array $args
	 *
	 * @return bool
	 */
	function delete_acpt_meta_block_field_row_value(array $args = [])
	{
		try {
			// validate array
			$mandatory_keys = [
				'post_id' => [
					'required' => true,
					'type' => 'integer',
				],
				'box_name' => [
					'required' => true,
					'type' => 'string',
				],
				'field_name' => [
					'required' => true,
					'type' => 'string',
				],
				'block_name' => [
					'required' => true,
					'type' => 'string',
				],
				'block_index' => [
					'required' => true,
					'type' => 'integer',
				],
				'index' => [
					'required' => true,
					'type' => 'integer',
				],
			];

			$validator = new ArgumentsArrayValidator();

			if(!$validator->validate($mandatory_keys, $args)){
				return false;
			}

			$post_type = get_post_type($args['post_id']);

			$meta_field_model = MetaRepository::getMetaFieldByName([
				'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
				'find' => $post_type,
				'boxName' => $args['box_name'],
				'fieldName' => $args['field_name']
			]);

			if($meta_field_model === null){
				return false;
			}

			$saved_field_value = get_post_meta($args['post_id'], $meta_field_model->getDbName(), true);
			$index = $args['index'];
			$block_index = $args['block_index'];
			$block_name = $args['block_name'];

			if(
				isset($saved_field_value['blocks']) and
				isset($saved_field_value['blocks'][$block_index]) and
				isset($saved_field_value['blocks'][$block_index][$block_name])
			){
				foreach ($saved_field_value['blocks'][$block_index][$block_name] as $block_element => $block_values){
					unset($saved_field_value['blocks'][$block_index][$block_name][$block_element][$index]);
				}
			}

			if(!update_post_meta($args['post_id'], $meta_field_model->getDbName(), Sanitizer::sanitizeRawData(CustomPostTypeMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE, $saved_field_value))){
				return false;
			}

			return true;

		} catch (\Exception $exception){
			return false;
		}
	}
}

if( !function_exists('edit_acpt_meta_field_value') ){

    /**
     * Edit meta field value.
     *
     * @param array $args
     *
     * @return bool
     */
    function edit_acpt_meta_field_value(array $args = [])
    {
        return add_acpt_meta_field_value($args);
    }
}

if( !function_exists('edit_acpt_meta_field_row_value') ){

    /**
     * Edit meta field row value (only for Repeater and List fields).
     * Use array index [0, 1, 2, 3 etc.....]
     *
     * @param array $args
     *
     * @return bool
     */
    function edit_acpt_meta_field_row_value(array $args = [])
    {
        try {
            // validate array
            $mandatory_keys = [
                'post_id' => [
                    'required' => true,
                    'type' => 'integer',
                ],
                'box_name' => [
                    'required' => true,
                    'type' => 'string',
                ],
                'field_name' => [
                    'required' => true,
                    'type' => 'string',
                ],
                'value' => [
                    'required' => true,
                    'type' => 'string|integer|array|object',
                ],
                'index' => [
                    'required' => true,
                    'type' => 'integer',
                ],
            ];

            $validator = new ArgumentsArrayValidator();

            if(!$validator->validate($mandatory_keys, $args)){
                return false;
            }

            $post_type = get_post_type($args['post_id']);

            $meta_field_model = MetaRepository::getMetaFieldByName([
                'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
                'find' => $post_type,
                'boxName' => $args['box_name'],
                'fieldName' => $args['field_name']
            ]);
            if($meta_field_model === null){
                return false;
            }

            $meta_field_model_type = $meta_field_model->getType();

            if($meta_field_model_type !== CustomPostTypeMetaBoxFieldModel::LIST_TYPE and $meta_field_model_type !== CustomPostTypeMetaBoxFieldModel::REPEATER_TYPE){
                return false;
            }

            $value = $args['value'];
            $index = $args['index'];

            $saved_field_type = get_post_meta($args['post_id'], $meta_field_model->getDbName().'_type', true);
            $saved_field_value = get_post_meta($args['post_id'], $meta_field_model->getDbName(), true);

            if($saved_field_type !== $meta_field_model_type){
                if(!update_post_meta($args['post_id'], $meta_field_model->getDbName().'_type', $meta_field_model_type)){
                    return false;
                }
            }

            switch ($meta_field_model_type){

                case CustomPostTypeMetaBoxFieldModel::LIST_TYPE:

                    if($saved_field_value[$index] !== $value){
                        $saved_field_value[$index] = $value;

                        if(!update_post_meta($args['post_id'], $meta_field_model->getDbName(), Sanitizer::sanitizeRawData(CustomPostTypeMetaBoxFieldModel::LIST_TYPE, $saved_field_value))){
                            return false;
                        }
                    }

                    break;

                case CustomPostTypeMetaBoxFieldModel::REPEATER_TYPE:

                    $values = NestedValues::addOrUpdateRawValue(
                            $meta_field_model,
                            $saved_field_value,
                            $value,
                            $index
                    );

                    if(!update_post_meta($args['post_id'], $meta_field_model->getDbName(), Sanitizer::sanitizeRawData(CustomPostTypeMetaBoxFieldModel::REPEATER_TYPE, $values))){
                        return false;
                    }

                    break;
            }

            return true;

        } catch (\Exception $exception){
            return false;
        }
    }
}

if( !function_exists('edit_acpt_meta_block_field_row_value') ){

	/**
	 * Edit meta field row value (only for Flexible fields).
	 * Use array index [0, 1, 2, 3 etc.....]
	 *
	 * @param array $args
	 *
	 * @return bool
	 */
	function edit_acpt_meta_block_field_row_value(array $args = [])
	{
		try {
			// validate array
			$mandatory_keys = [
				'post_id' => [
					'required' => true,
					'type' => 'integer',
				],
				'box_name' => [
					'required' => true,
					'type' => 'string',
				],
				'field_name' => [
					'required' => true,
					'type' => 'string',
				],
				'value' => [
					'required' => true,
					'type' => 'string|integer|array|object',
				],
				'block_name' => [
					'required' => true,
					'type' => 'string',
				],
				'block_index' => [
					'required' => true,
					'type' => 'integer',
				],
				'index' => [
					'required' => true,
					'type' => 'integer',
				],
			];

			$validator = new ArgumentsArrayValidator();

			if(!$validator->validate($mandatory_keys, $args)){
				return false;
			}

			$post_type = get_post_type($args['post_id']);

			$meta_field_model = MetaRepository::getMetaFieldByName([
				'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
				'find' => $post_type,
				'boxName' => $args['box_name'],
				'fieldName' => $args['field_name']
			]);

			if($meta_field_model === null){
				return false;
			}

			$meta_field_model_type = $meta_field_model->getType();

			if($meta_field_model_type !== CustomPostTypeMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE){
				return false;
			}

			$value = $args['value'];
			$index = $args['index'];
			$block_name = $args['block_name'];
			$block_index = $args['block_index'];

			$saved_field_type = get_post_meta($args['post_id'], $meta_field_model->getDbName().'_type', true);
			$saved_field_value = get_post_meta($args['post_id'], $meta_field_model->getDbName(), true);

			if($saved_field_type !== $meta_field_model_type){
				if(!update_post_meta($args['post_id'], $meta_field_model->getDbName().'_type', $meta_field_model_type)){
					return false;
				}
			}

			$values = NestedValues::addOrUpdateBlockRawValue(
				$meta_field_model,
				$block_name,
				$block_index,
				$saved_field_value,
				$value,
				$index
			);

			if(!update_post_meta($args['post_id'], $meta_field_model->getDbName(), Sanitizer::sanitizeRawData(CustomPostTypeMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE, $values))){
				return false;
			}

			return true;

		} catch (\Exception $exception){
			return false;
		}
	}
}
