<?php

namespace ACPT\Core\Data\Import\Importer;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Costants\MetadataFormats;
use ACPT\Costants\MetaTypes;
use ACPT\Utils\Wordpress\Files;

abstract class AbstractImporter
{
	/**
	 * @param $belongsTo
	 * @param $boxName
	 * @param null $boxLabel
	 * @param null $find
	 */
	protected function importBoxSettings($belongsTo, $boxName, $boxLabel = null, $find = null)
	{
		switch ($belongsTo){
			case MetaTypes::CUSTOM_POST_TYPE:
				if(null === get_acpt_box_object($find, $boxName)){
					add_acpt_meta_box($find, $boxName, $boxLabel);
				}
				break;

			case MetaTypes::TAXONOMY:
				if(null === get_acpt_tax_box_object($find, $boxName)){
					add_acpt_tax_meta_box($find, $boxName, $boxLabel);
				}
				break;

			case MetaTypes::USER:
				if(null === get_acpt_user_box_object($boxName)){
					add_acpt_user_meta_box($boxName, $boxLabel);
				}
				break;
		}
	}

	/**
	 * @param $format
	 * @param $belongsTo
	 * @param $boxName
	 * @param $fieldName
	 * @param $fieldType
	 * @param $props
	 * @param $advancedOptions
	 * @param $visibilityConditions
	 * @param $relations
	 * @param $options
	 * @param $children
	 * @param $blocks
	 * @param null $find
	 */
	protected function importFieldSettings(
		$format,
		$belongsTo,
		$boxName,
		$fieldName,
		$fieldType,
		$props,
		$advancedOptions,
		$visibilityConditions,
		$relations,
		$options,
		$children,
		$blocks,
		$find = null
	)
	{
		$payload = [
			'box_name' => $boxName,
			'field_name' => $fieldName,
			'field_type' => $fieldType,
			'show_in_archive' => (($format === MetadataFormats::XML_FORMAT) ? $props[0]->show_in_archive[0]->__toString() == 1 : $props['show_in_archive'] == 1),
			'required' => (($format === MetadataFormats::XML_FORMAT) ? $props[0]->required[0]->__toString() == 1 : $props['required'] == 1),
			'default_value' => ($format === MetadataFormats::XML_FORMAT) ? $props[0]->default_value[0]->__toString() : $props['default_value'],
			'description' => ($format === MetadataFormats::XML_FORMAT) ? $props[0]->description[0]->__toString() : $props['description'],
			'advanced_options' => [],
			'options' => [],
			'visibility_conditions' => [],
		];

		if(!empty($options)){
			foreach($options as $option){
				$payload['options'][] = [
					'value' => ($format === MetadataFormats::XML_FORMAT) ? $option->value[0]->__toString() : $option['value'],
					'label' => ($format === MetadataFormats::XML_FORMAT) ? $option->label[0]->__toString() : $option['label'],
				];
			}
		}

		if(!empty($advancedOptions)){
			foreach($advancedOptions as $option){
				$payload['advanced_options'][] = [
					'value' => ($format === MetadataFormats::XML_FORMAT) ? $option->value[0]->__toString() : $option['value'],
					'label' => ($format === MetadataFormats::XML_FORMAT) ? $option->label[0]->__toString() : $option['label'],
				];
			}
		}

		if(!empty($visibilityConditions)){
			foreach($visibilityConditions as $condition){
				$payload['visibility_conditions'][] = [
					'type' => ($format === MetadataFormats::XML_FORMAT ? $condition->type[0]->type[0]->__toString() : $condition['type']['type']),
					'value' => ($format === MetadataFormats::XML_FORMAT ? $condition->value[0]->__toString() : $condition['value']),
					'operator' => ($format === MetadataFormats::XML_FORMAT ? $condition->operator[0]->__toString() : $condition['operator']),
					'logic' => ($format === MetadataFormats::XML_FORMAT ? $condition->logic[0]->__toString() : $condition['logic']),
				];
			}
		}

		switch ($belongsTo){
			case MetaTypes::CUSTOM_POST_TYPE:
				$payload['post_type'] = $find;
				$payload['quick_edit'] = (($format === MetadataFormats::XML_FORMAT) ? $props[0]->quick_edit[0]->__toString() == 1 : $props['quick_edit'] == 1);
				$payload['is_filterable'] = (($format === MetadataFormats::XML_FORMAT) ? $props[0]->is_filterable[0]->__toString() == 1 : $props['is_filterable'] == 1);
				$payload['children'] = [];
				$payload['relations'] = [];
				$payload['blocks'] = [];

				if(!empty($relations)){
					foreach($relations as $relation){
						$payload['relations'][] = [
							'related_to' => [
								'post_type' => ($format === MetadataFormats::XML_FORMAT ? $relation[0]->related_to[0]->post_type[0]->__toString() : $relation['related_to']['post_type']),
								'box_name' => ($format === MetadataFormats::XML_FORMAT ? $relation[0]->related_to[0]->box_name[0]->__toString() : $relation['related_to']['box_name']),
								'field_name' => ($format === MetadataFormats::XML_FORMAT ? $relation[0]->related_to[0]->field_name[0]->__toString() : $relation['related_to']['field_name']),
							],
							'relation' => ($format === MetadataFormats::XML_FORMAT ? $relation[0]->relationship[0]->__toString() : $relation['relationship']),
						];
					}
				}

				if(!empty($children)){
					$payload['children'] = $this->formatChildrenFieldsSettings($format, $children);
				}

				if(!empty($blocks)){
					foreach($blocks as $block) {
						$blockName = ($format === MetadataFormats::XML_FORMAT ? $block->attributes()['name'][0]->__toString() : $block['name']);
						$blockLabel = ($format === MetadataFormats::XML_FORMAT ? $block->attributes()['label'][0]->__toString() : $block['label']);

						$payload['blocks'][] = [
							'block_name' => $blockName,
							'block_label' => $blockLabel,
							'fields' => $this->formatChildrenFieldsSettings($format, $block)
						];
					}
				}

				if(null === get_acpt_field_object($find, $boxName, $fieldName)){
					add_acpt_meta_field($payload);
				}

				break;

			case MetaTypes::TAXONOMY:
				$payload['taxonomy'] = $find;

				if(null === get_acpt_tax_field_object($find, $boxName, $fieldName)){
					add_acpt_tax_meta_field($payload);
				}

				break;

			case MetaTypes::USER:
				if(null === get_acpt_user_field_object($boxName, $fieldName)){
					add_acpt_user_meta_field($payload);
				}

				break;
		}
	}

	/**
	 * @param $format
	 * @param $children
	 *
	 * @return array
	 */
	private function formatChildrenFieldsSettings($format, $children)
	{
		$arrayOfChildrenFields = [];

		foreach($children as $child){

			$childFieldName = ($format === MetadataFormats::XML_FORMAT) ? $child->attributes()['name'][0]->__toString() : $child['name'];
			$childFieldType = ($format === MetadataFormats::XML_FORMAT) ? $child->attributes()['type'][0]->__toString() : $child['type'];

			$childFieldProps = ($format === MetadataFormats::XML_FORMAT) ? $child->props[0] : $child['props'];
			$childFieldAdvancedOptions = ($format === MetadataFormats::XML_FORMAT) ? $child->advanced_options[0] : $child['advanced_options'];
			$childFieldVisibilityConditions = ($format === MetadataFormats::XML_FORMAT) ? $child->visibility_conditions[0] : $child['visibility_conditions'];
			$childFieldOptions = ($format === MetadataFormats::XML_FORMAT) ? $child->options[0] : $child['options'];

			$payloadOptions = [];
			$payloadAdvancedOptions = [];
			$payloadVisibilityConditions = [];

			if(!empty($childFieldOptions)){
				foreach($childFieldOptions as $option){
					$payloadOptions[] = [
						'value' => ($format === MetadataFormats::XML_FORMAT ? $option->value[0]->__toString() : $option['value']),
						'label' => ($format === MetadataFormats::XML_FORMAT ? $option->label[0]->__toString() : $option['label']),
					];
				}
			}

			if(!empty($childFieldAdvancedOptions)){
				foreach($childFieldAdvancedOptions as $option){
					$payloadAdvancedOptions[] = [
						'value' => ($format === MetadataFormats::XML_FORMAT ? $option->value[0]->__toString() : $option['value']),
						'key' => ($format === MetadataFormats::XML_FORMAT ? $option->key[0]->__toString() : $option['key']),
					];
				}
			}

			if(!empty($childFieldVisibilityConditions)){
				foreach($childFieldVisibilityConditions as $condition){
					$payloadVisibilityConditions[] = [
						'type' => ($format === MetadataFormats::XML_FORMAT ? $condition->type[0]->type[0]->__toString() : $condition['type']['type']),
						'value' => ($format === MetadataFormats::XML_FORMAT ? $condition->value[0]->__toString() : $condition['value']),
						'operator' => ($format === MetadataFormats::XML_FORMAT ? $condition->operator[0]->__toString() : $condition['operator']),
						'logic' => ($format === MetadataFormats::XML_FORMAT ? $condition->logic[0]->__toString() : $condition['logic']),
					];
				}
			}

			$arrayOfChildrenFields[] = [
				'field_name' => $childFieldName,
				'field_type' => $childFieldType,
				'required' => (($format === MetadataFormats::XML_FORMAT) ? $childFieldProps[0]->required[0]->__toString() == 1 : $childFieldProps['quick_edit'] == 1),
				'default_value' => ($format === MetadataFormats::XML_FORMAT ? $childFieldProps[0]->default_value[0]->__toString() : $childFieldProps['default_value']),
				'description' => ($format === MetadataFormats::XML_FORMAT ? $childFieldProps[0]->description[0]->__toString() : $childFieldProps['description']),
				'options' => $payloadOptions,
				'advanced_options' => $payloadAdvancedOptions,
				'visibility_conditions' => $payloadVisibilityConditions,
			];
		}

		return $arrayOfChildrenFields;
	}

	/**
	 * @param $format
	 * @param $belongsTo
	 * @param $newItemId
	 * @param $boxName
	 * @param $fieldName
	 * @param $values
	 */
	protected function importFieldMetadata($format, $belongsTo, $newItemId, $boxName, $fieldName, $values)
	{
		$value = $this->extractValues($format, $values);

		switch ($belongsTo) {
			case MetaTypes::CUSTOM_POST_TYPE:
				add_acpt_meta_field_value([
					'post_id' => $newItemId,
					'box_name' => $boxName,
					'field_name' => $fieldName,
					'value' => $value,
				]);

				break;

			case MetaTypes::TAXONOMY:
				add_acpt_tax_meta_field_value([
					'term_id' => $newItemId,
					'box_name' => $boxName,
					'field_name' => $fieldName,
					'value' => $value,
				]);

				break;

			case MetaTypes::USER:
				add_acpt_user_meta_field_value([
					'term_id' => $newItemId,
					'box_name' => $boxName,
					'field_name' => $fieldName,
					'value' => $value,
				]);
				break;
		}
	}

	/**
	 * @param $format
	 * @param $values
	 *
	 * @return mixed
	 */
	private function extractValues($format, $values)
	{
		$fieldType = ($format === MetadataFormats::XML_FORMAT ? $values->attributes()['type'][0]->__toString() : $values['type']);

		switch ($fieldType){

			case AbstractMetaBoxFieldModel::ADDRESS_TYPE:
				return ($format === MetadataFormats::XML_FORMAT ? $values->value->address[0]->__toString() : $values['value']['address']);

			case AbstractMetaBoxFieldModel::CHECKBOX_TYPE:
			case AbstractMetaBoxFieldModel::LIST_TYPE:
			case AbstractMetaBoxFieldModel::SELECT_MULTI_TYPE:

				$arrayOfValues = [];

				foreach ($values as $value){
					$arrayOfValues[] = ($format === MetadataFormats::XML_FORMAT) ? $value[0]->__toString() : $value;
				}

				return $arrayOfValues;

			case AbstractMetaBoxFieldModel::CURRENCY_TYPE:

				$amount = ($format === MetadataFormats::XML_FORMAT) ? $values->value->amount[0]->__toString() : $values['value']['amount'];
				$unit = ($format === MetadataFormats::XML_FORMAT) ? $values->value->unit[0]->__toString() : $values['value']['unit'];

				return [
					'amount' => (int)$amount,
					'unit' => $unit,
				];

			case AbstractMetaBoxFieldModel::DATE_RANGE_TYPE:

				$from = ($format === MetadataFormats::XML_FORMAT) ? $values->value->from[0]->__toString() : $values['value']['from'];
				$to = ($format === MetadataFormats::XML_FORMAT) ? $values->value->to[0]->__toString() : $values['value']['to'];

				return [
					$from,
					$to
				];

			case AbstractMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE:

				$blocks = $values->blocks[0];
				$blocksValues = [];
				$blockIndex = 0;

				foreach ($blocks as $block){

					$blockName = ($format === MetadataFormats::XML_FORMAT) ? $block->attributes()['name'][0]->__toString() : $block['name'];
					$fieldIndex = 0;

					foreach($block->values as $nestedValue){
						$nestedFieldName = ($format === MetadataFormats::XML_FORMAT) ? $nestedValue->attributes()['name'][0]->__toString() : $nestedValue['name'];
						$blocksValues[$blockIndex][$blockName][$fieldIndex][$nestedFieldName] = self::extractValues($format, $nestedValue);
						$fieldIndex++;
					}

					$blockIndex++;
				}

				return [
					'blocks' => $blocksValues
				];

			case AbstractMetaBoxFieldModel::FILE_TYPE:
				$src = ($format === MetadataFormats::XML_FORMAT) ? $values->value->src[0]->__toString() : $values['value']['src'];
				$label = ($format === MetadataFormats::XML_FORMAT) ? $values->value->label[0]->__toString() : $values['value']['label'];
				$file = Files::downloadFromUrl($src);

				if($file){
					return [
						'label' => $label,
						'url' => $file['url'],
					];
				}

				return null;

			case AbstractMetaBoxFieldModel::POST_TYPE:

				$arrayOfValues = [];

				foreach ($values as $postId){
					$arrayOfValues[] = $postId;
				}

				return $arrayOfValues;

			case AbstractMetaBoxFieldModel::GALLERY_TYPE:

				$arrayOfValues = [];

				foreach ($values as $value){
					$src = ($format === MetadataFormats::XML_FORMAT) ? $value[0]->src[0]->__toString() : $value['src'];
					$file = Files::downloadFromUrl($src);

					if($file){
						$arrayOfValues[] = $file['url'];
					} else {
						$arrayOfValues[] = $src;
					}
				}

				return $arrayOfValues;

			case AbstractMetaBoxFieldModel::IMAGE_TYPE:
			case AbstractMetaBoxFieldModel::VIDEO_TYPE:

				$src = ($format === MetadataFormats::XML_FORMAT) ? $values->value->src[0]->__toString() : $values['value']['src'];
				$file = Files::downloadFromUrl($src);

				if($file){
					return $file['url'];
				}

				return $src;

			case AbstractMetaBoxFieldModel::LENGTH_TYPE:

				$length = ($format === MetadataFormats::XML_FORMAT) ? $values->value->length[0]->__toString() : $values['value']['length'];
				$unit = ($format === MetadataFormats::XML_FORMAT) ? $values->value->unit[0]->__toString() : $values['value']['unit'];

				return [
					'length' => (int)$length,
					'unit' => $unit,
				];

			case AbstractMetaBoxFieldModel::REPEATER_TYPE:

				$childrenValues = [];

				foreach ($values as $value){

					$arrayOfNestedValues = [];

					foreach($value as $nestedValue){
						$nestedFieldName = ($format === MetadataFormats::XML_FORMAT) ? $nestedValue->attributes()['name'][0]->__toString() : $nestedValue['name'];
						$arrayOfNestedValues[$nestedFieldName] = self::extractValues($format, $nestedValue);
					}

					$childrenValues[] = $arrayOfNestedValues;
				}

				return $childrenValues;

			case AbstractMetaBoxFieldModel::WEIGHT_TYPE:

				$weight = ($format === MetadataFormats::XML_FORMAT) ? $values->value->weight[0]->__toString() : $values['value']['weight'];
				$unit = ($format === MetadataFormats::XML_FORMAT) ? $values->value->unit[0]->__toString() : $values['value']['unit'];

				return [
					'weight' => (int)$weight,
					'unit' => $unit,
				];

			case AbstractMetaBoxFieldModel::URL_TYPE:

				$url = ($format === MetadataFormats::XML_FORMAT) ? $values->value->url[0]->__toString() : $values['value']['url'];
				$label = ($format === MetadataFormats::XML_FORMAT) ? $values->value->label[0]->__toString() : $values['value']['label'];

				return [
					'url' => $url,
					'label' => $label,
				];

			default:
				return ($format === MetadataFormats::XML_FORMAT) ? $values->value[0]->__toString() : $values['value'][0];
		}
	}
}