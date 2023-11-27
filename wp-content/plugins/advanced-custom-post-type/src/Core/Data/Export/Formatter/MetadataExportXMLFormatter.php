<?php

namespace ACPT\Core\Data\Export\Formatter;

use ACPT\Core\Data\Export\DTO\MetadataExportItemDto;
use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Costants\MetaTypes;
use ACPT\Utils\Wordpress\WPAttachment;

class MetadataExportXMLFormatter implements MetadataExportFormatterInterface
{
	/**
	 * This function format a single unit (for single post type for example)
	 *
	 * @param MetadataExportItemDto $dto
	 *
	 * @return mixed
	 */
	public function format(MetadataExportItemDto $dto)
	{
		$find = ($dto->find !== null) ? 'find="'.$dto->find.'"' : '';
		$xml = '<acpt belongsTo="'.$dto->belongsTo.'" '.$find.' itemId="'.$dto->id.'">';
		$xml .= '<boxes>';

		foreach ($dto->metaBoxes as $metaBoxModel){
			$xml .= '<box name="'.$metaBoxModel->getName().'" label="'.$metaBoxModel->getLabel().'">';
			$xml .= '<fields>';

			foreach ($metaBoxModel->getFields() as $fieldModel){
				$xml .= self::formatField($dto->id, $dto->belongsTo, $fieldModel);
			}

			$xml .= '</fields>';
			$xml .= '</box>';
		}

		$xml .= '</boxes>';
		$xml .= '</acpt>';

		return $xml;
	}

	/**
	 * @param $itemId
	 * @param $belongsTo
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 * @param null $fieldIndex
	 * @param null $blockIndex
	 *
	 * @return string
	 */
	private static function formatField($itemId, $belongsTo, AbstractMetaBoxFieldModel $fieldModel, $fieldIndex = null, $blockIndex = null)
	{
		$xml = '<field name="'.$fieldModel->getName().'" type="'.$fieldModel->getType().'">';
		$xml .= self::formatFieldProps($fieldModel);
		$xml .= self::formatAdvancedOptions($fieldModel);
		$xml .= self::formatVisibilityConditions($fieldModel);
		$xml .= self::formatOptions($fieldModel);
		$xml .= self::formatRelations($fieldModel);
		$xml .= self::formatChildren($fieldModel);
		$xml .= self::formatBlocks($fieldModel);
		$xml .= self::formatFieldValue($itemId, $belongsTo, $fieldModel, $fieldIndex, $blockIndex);
		$xml .= '</field>';

		return $xml;
	}

	/**
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 *
	 * @return string
	 */
	private static function formatFieldProps(AbstractMetaBoxFieldModel $fieldModel)
	{
		$xml = '<props>';
		$xml .= '<default_value>'.$fieldModel->getDefaultValue().'</default_value>';
		$xml .= '<description>'.$fieldModel->getDescription().'</description>';
		$xml .= '<show_in_archive>'.self::renderBoolean($fieldModel->isShowInArchive()).'</show_in_archive>';
		$xml .= '<required>'.self::renderBoolean($fieldModel->isRequired()).'</required>';
		$xml .= '<quick_edit>'.self::renderBoolean($fieldModel->isForQuickEdit()).'</quick_edit>';
		$xml .= '<is_filterable>'.self::renderBoolean($fieldModel->isFilterableInAdmin()).'</is_filterable>';
		$xml .= '</props>';

		return $xml;
	}

	/**
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 *
	 * @return string
	 */
	private static function formatRelations(AbstractMetaBoxFieldModel $fieldModel)
	{
		$xml = '<relations>';

		foreach ($fieldModel->getRelations() as $relationModel){
			$xml .= '<relation>';
			$xml .= '<relationship>'.Strings::toSnakeCase($relationModel->getRelationship()).'</relationship>';
			$xml .= '<related_to>';
			$xml .= '<post_type>'.$relationModel->getRelatedEntity()->getValue()->getName().'</post_type>';

			$xml .= '<box_name>';
			if($relationModel->getInversedBy()){ $xml .= $relationModel->getInversedBy()->getMetaBox()->getName(); }
			$xml .= '</box_name>';

			$xml .= '<field_name>';
			if($relationModel->getInversedBy()){ $xml .= $relationModel->getInversedBy()->getName(); }
			$xml .= '</field_name>';

			$xml .= '</related_to>';
			$xml .= '</relation>';
		}

		$xml .= '</relations>';

		return $xml;
	}

	/**
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 *
	 * @return string
	 */
	private static function formatBlocks(AbstractMetaBoxFieldModel $fieldModel)
	{
		$xml = '<blocks>';

		foreach ($fieldModel->getBlocks() as $blockField){
			$xml .= '<block name="'.$blockField->getName().'" label="'.$blockField->getLabel().'">';

			foreach ($blockField->getFields() as $nestedFieldModel){
				$xml .= self::formatNestedField($nestedFieldModel);
			}

			$xml .= '</block>';
		}

		$xml .= '</blocks>';

		return $xml;
	}

	/**
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 *
	 * @return string
	 */
	private static function formatChildren(AbstractMetaBoxFieldModel $fieldModel)
	{
		$xml = '<children>';

		foreach ($fieldModel->getChildren() as $childFieldModel){
			$xml .= self::formatNestedField($childFieldModel);
		}

		$xml .= '</children>';

		return $xml;
	}

	/**
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 *
	 * @return string
	 */
	private static function formatNestedField(AbstractMetaBoxFieldModel $fieldModel)
	{
		$xml = '<field name="'.$fieldModel->getName().'" type="'.$fieldModel->getType().'">';
		$xml .= self::formatFieldProps($fieldModel);
		$xml .= self::formatAdvancedOptions($fieldModel);
		$xml .= self::formatVisibilityConditions($fieldModel);
		$xml .= self::formatOptions($fieldModel);
		$xml .= '</field>';

		return $xml;
	}

	/**
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 *
	 * @return string
	 */
	private static function formatAdvancedOptions(AbstractMetaBoxFieldModel $fieldModel)
	{
		$xml = '<advanced_options>';

		foreach ($fieldModel->getAdvancedOptions() as $advancedOptionModel){
			$xml .= '<option>';
			$xml .= '<key>'.$advancedOptionModel->getKey().'</key>';
			$xml .= '<value>'.$advancedOptionModel->getValue().'</value>';
			$xml .= '</option>';
		}

		$xml .= '</advanced_options>';

		return $xml;
	}

	/**
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 *
	 * @return string
	 */
	private static function formatVisibilityConditions(AbstractMetaBoxFieldModel $fieldModel)
	{
		$xml = '<visibility_conditions>';

		foreach ($fieldModel->getVisibilityConditions() as $visibilityConditionModel){
			$xml .= '<condition>';
			$xml .= '<operator>'.$visibilityConditionModel->getOperator().'</operator>';
			$xml .= '<logic>'.$visibilityConditionModel->getLogic().'</logic>';
			$xml .= '<type>';
			$xml .= '<type>'.$visibilityConditionModel->getType()['type'].'</type>';
			$xml .= '<value>'.$visibilityConditionModel->getType()['value'].'</value>';
			$xml .= '</type>';
			$xml .= '<value>'.$visibilityConditionModel->getValue().'</value>';
			$xml .= '</condition>';
		}

		$xml .= '</visibility_conditions>';

		return $xml;
	}

	/**
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 *
	 * @return string
	 */
	private static function formatOptions(AbstractMetaBoxFieldModel $fieldModel)
	{
		$xml = '<options>';

		foreach ($fieldModel->getOptions() as $optionModel){
			$xml .= '<option>';
			$xml .= '<label>'.$optionModel->getLabel().'</label>';
			$xml .= '<value>'.$optionModel->getValue().'</value>';
			$xml .= '</option>';
		}

		$xml .= '</options>';

		return $xml;
	}

	/**
	 * @param $itemId
	 * @param $belongsTo
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 * @param null $fieldIndex
	 * @param null $blockIndex
	 *
	 * @return string
	 */
	private static function formatFieldValue($itemId, $belongsTo, AbstractMetaBoxFieldModel $fieldModel, $fieldIndex = null, $blockIndex = null)
	{
		$rawValue = null;

		switch ($belongsTo){
			case MetaTypes::CUSTOM_POST_TYPE:

				if($fieldModel->isNestedInABlock()){
					$rawValue = get_acpt_block_child_field([
						'post_id' => (int)$itemId,
						'box_name' => $fieldModel->getMetaBox()->getName(),
						'field_name' => $fieldModel->getName(),
						'parent_field_name' => $fieldModel->getParentBlock()->getMetaBoxField()->getName(),
						'index' => $fieldIndex,
						'block_name' => $fieldModel->getParentBlock()->getName(),
						'block_index' => $blockIndex,
					]);
				} elseif($fieldModel->hasParent()){
					$rawValue = get_acpt_child_field([
						'post_id' => (int)$itemId,
						'box_name' => $fieldModel->getMetaBox()->getName(),
						'field_name' => $fieldModel->getName(),
						'parent_field_name' => $fieldModel->getParentField()->getName(),
						'index' => $fieldIndex,
					]);
				} else {
					$rawValue = get_acpt_field([
						'post_id' => (int)$itemId,
						'box_name' => $fieldModel->getMetaBox()->getName(),
						'field_name' => $fieldModel->getName(),
					]);
				}
				break;

			case MetaTypes::TAXONOMY:
				$rawValue = get_acpt_tax_field([
					'term_id' => (int)$itemId,
					'box_name' => $fieldModel->getMetaBox()->getName(),
					'field_name' => $fieldModel->getName(),
				]);
				break;

			case MetaTypes::USER:
				$rawValue = get_acpt_user_field([
					'user_id' => (int)$itemId,
					'box_name' => $fieldModel->getMetaBox()->getName(),
					'field_name' => $fieldModel->getName(),
				]);
				break;
		}

		$xml = '<values name="'.$fieldModel->getName().'" type="'.$fieldModel->getType().'">';

		if($rawValue !== null){
			$xml .= self::formatRawValue($itemId, $belongsTo, $rawValue, $fieldModel);
		}

		$xml .= '</values>';

		return $xml;
	}

	/**
	 * @param $itemId
	 * @param $belongsTo
	 * @param mixed $rawValue
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 *
	 * @return string
	 */
	private static function formatRawValue($itemId, $belongsTo, $rawValue, AbstractMetaBoxFieldModel $fieldModel)
	{
		$fieldType = $fieldModel->getType();

		switch ($fieldType){

			case AbstractMetaBoxFieldModel::ADDRESS_TYPE:

				if(!is_array($rawValue)){
					return null;
				}

				$value = '<value>';
				$value .= '<address>'.$rawValue['address'].'</address>';
				$value .= '<lat>'.$rawValue['lat'].'</lat>';
				$value .= '<lng>'.$rawValue['lng'].'</lng>';
				$value .= '</value>';

				return $value;

			case AbstractMetaBoxFieldModel::CURRENCY_TYPE:

				$value = '<value>';
				$value .= '<amount>'.$rawValue['amount'].'</amount>';
				$value .= '<unit>'.$rawValue['unit'].'</unit>';
				$value .= '</value>';

				return $value;

			case AbstractMetaBoxFieldModel::DATE_RANGE_TYPE:

				if(!is_array($rawValue)){
					return null;
				}

				$value = '<value>';
				$value .= '<from>'.$rawValue[0].'</from>';
				$value .= '<to>'.$rawValue[1].'</to>';
				$value .= '</value>';

				return $value;

			case AbstractMetaBoxFieldModel::FILE_TYPE:

				$file = $rawValue['file'];
				$label = $rawValue['label'];

				if(!$file instanceof WPAttachment){
					return null;
				}

				$value = '<value>';
				$value .= '<id>'.$file->getId().'</id>';
				$value .= '<src>'.$file->getSrc().'</src>';
				$value .= '<description>'.$file->getDescription().'</description>';
				$value .= '<label>'.$label.'</label>';
				$value .= '</value>';

				return $value;

			case AbstractMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE:

				if(!is_array($rawValue)){
					return null;
				}

				$value = '<blocks>';
				$blockIndex = 0;

				foreach ($rawValue['blocks'] as $block){
					foreach ($block as $blockName => $blockFields){
						$nestedBlockModel = $fieldModel->getBlock($blockName);
						$value .= '<block name="'.$blockName.'" label="'.$nestedBlockModel->getLabel().'">';

						$arrayValues = [];

						foreach ($blockFields as $childName => $childRawValues){
							$nestedFieldModel = $nestedBlockModel->getField($childName);

							if($nestedFieldModel !== null){
								for($i = 0; $i < count($childRawValues); $i++){
									$arrayValues[$childName][$i] = self::formatFieldValue($itemId, $belongsTo, $nestedFieldModel, $i, $blockIndex);
								}
							}
						}

						if(!empty($arrayValues)){
							for($i = 0; $i < count($arrayValues); $i++){
								foreach ($arrayValues as $metaFieldName => $arrayValue){
									for($k = 0; $k < count($arrayValue); $k++){
										$value .= $arrayValues[$metaFieldName][$k];
									}
								}
							}
						}

						$value .= '</block>';
					}

					$blockIndex++;
				}

				$value .= '</blocks>';

				return $value;

			case AbstractMetaBoxFieldModel::GALLERY_TYPE:

				if(!is_array($rawValue)){
					return null;
				}

				$value = '';

				foreach ($rawValue as $item){
					if($item instanceof WPAttachment){
						$value .= '<value>';
						$value .= '<id>'.$item->getId().'</id>';
						$value .= '<src>'.$item->getSrc().'</src>';
						$value .= '<description>'.$item->getDescription().'</description>';
						$value .= '</value>';
					}
				}

				return $value;

			case AbstractMetaBoxFieldModel::IMAGE_TYPE:
			case AbstractMetaBoxFieldModel::VIDEO_TYPE:

				if(!$rawValue instanceof WPAttachment){
					return null;
				}

				$value = '<value>';
				$value .= '<id>'.$rawValue->getId().'</id>';
				$value .= '<src>'.$rawValue->getSrc().'</src>';
				$value .= '<description>'.$rawValue->getDescription().'</description>';
				$value .= '</value>';

				return $value;

			case AbstractMetaBoxFieldModel::LENGTH_TYPE:

				$value = '<value>';
				$value .= '<length>'.$rawValue['length'].'</length>';
				$value .= '<unit>'.$rawValue['unit'].'</unit>';
				$value .= '</value>';

				return $value;

			case AbstractMetaBoxFieldModel::LIST_TYPE:
			case AbstractMetaBoxFieldModel::SELECT_MULTI_TYPE:
			case AbstractMetaBoxFieldModel::CHECKBOX_TYPE:

				if(!is_array($rawValue)){
					return null;
				}

				$value = '';

				foreach ($rawValue as $item){
					$value .= '<value>'.$item.'</value>';
				}

				return $value;

			case AbstractMetaBoxFieldModel::POST_TYPE:

				if(!is_array($rawValue)){
					return null;
				}

				$value = '';

				/** @var \WP_Post $item */
				foreach ($rawValue as $item){
					$value .= '<value>'.$item->ID.'</value>';
				}

				return $value;

			case AbstractMetaBoxFieldModel::REPEATER_TYPE:

				if(!is_array($rawValue)){
					return null;
				}

				$value = '';
				$fieldIndex = 0;

				foreach ($rawValue as $children){

					$value .= '<value>';

					foreach ($children as $childName => $childRawValue){
						$nestedFieldModel = $fieldModel->getChild($childName);

						if($nestedFieldModel !== null){
							$value .= self::formatFieldValue($itemId, $belongsTo, $nestedFieldModel, $fieldIndex);
						}
					}

					$fieldIndex++;
					$value .= '</value>';
				}

				return $value;

			case AbstractMetaBoxFieldModel::WEIGHT_TYPE:

				$value = '<value>';
				$value .= '<weight>'.$rawValue['weight'].'</weight>';
				$value .= '<unit>'.$rawValue['unit'].'</unit>';
				$value .= '</value>';

				return $value;

			case AbstractMetaBoxFieldModel::URL_TYPE:

				$value = '<value>';
				$value .= '<url>'.$rawValue['url'].'</url>';
				$value .= '<label>'.$rawValue['label'].'</label>';
				$value .= '</value>';

				return $value;

			default:
				return '<value>'.$rawValue.'</value>';
		}
	}

	/**
	 * @param $boolean
	 *
	 * @return string
	 */
	private static function renderBoolean($boolean = null)
	{
		if($boolean == 1 or $boolean == true){
			return "1";
		}

		return "0";
	}
}