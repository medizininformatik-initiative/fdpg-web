<?php

namespace ACPT\Utils\Checker;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Core\Models\MetaField\MetaBoxFieldVisibilityModel;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Utils\Data\Meta;

class FieldVisibilityChecker
{
	/**
	 * @param $elementId
	 * @param AbstractMetaBoxFieldModel $metaBoxFieldModel
	 * @param null $fieldIndex
	 * @param null $blockName
	 * @param null $blockIndex
	 *
	 * @return bool
	 */
	public static function isFieldVisible($elementId, AbstractMetaBoxFieldModel $metaBoxFieldModel, $fieldIndex = null, $blockName = null, $blockIndex = null)
	{
		try {
			if($metaBoxFieldModel === null or !$metaBoxFieldModel->hasVisibilityConditions()){
				return true;
			}

			$logicBlocks = [];
			$storedLogicBlocks = [];
			$visibilityConditions = $metaBoxFieldModel->getVisibilityConditions();

			foreach ($visibilityConditions as $index => $visibilityCondition){

				$isLast = $index === (count($visibilityConditions)-1);
				$logic = $visibilityCondition->getLogic();

				// AND
				if($logic === 'AND' and !$isLast){
					if(!empty($storedLogicBlocks)){
						$storedLogicBlocks[] = $visibilityCondition;
						$logicBlocks[] = $storedLogicBlocks;
						$storedLogicBlocks = [];
					} else {
						$logicBlocks[] = [$visibilityCondition];
					}
				}

				// OR
				if($logic === 'OR' and !$isLast){
					$storedLogicBlocks[] = $visibilityCondition;
				}

				// Last element
				if($isLast){
					if(!empty($storedLogicBlocks)){
						$storedLogicBlocks[] = $visibilityCondition;
						$logicBlocks[] = $storedLogicBlocks;
						$storedLogicBlocks = [];
					} else {
						$logicBlocks[] = [$visibilityCondition];
					}
				}
			}

			$logics = [];

			foreach ($logicBlocks as $logicBlocksConditions){
				$logics[] = self::returnTrueOrFalseForALogicBlock($elementId, $metaBoxFieldModel, $logicBlocksConditions, $fieldIndex, $blockName, $blockIndex);
			}

			return !in_array(false, $logics );
		} catch (\Exception $exception){
			return true;
		}
	}

	/**
	 * @param $elementId
	 * @param AbstractMetaBoxFieldModel $metaBoxFieldModel
	 * @param array $conditions
	 * @param null $fieldIndex
	 *
	 * @return bool
	 * @throws \Exception
	 */
	private static function returnTrueOrFalseForALogicBlock($elementId, AbstractMetaBoxFieldModel $metaBoxFieldModel, array $conditions, $fieldIndex = null, $blockName = null, $blockIndex = null)
	{
		foreach ($conditions as $condition){

			$belongsTo = $metaBoxFieldModel->getMetaBox()->metaType();
			$typeEnum = $condition->getType()['type'];
			$typeValue = $condition->getType()['value'];
			$operator = $condition->getOperator();
			$value = $condition->getValue();

			$rawData = Meta::fetch($elementId, $belongsTo, self::getKey($metaBoxFieldModel));

			// field value of a nested field in a Repeater
			if($metaBoxFieldModel->getParentId() !== null){
				if(isset( $rawData[$metaBoxFieldModel->getName()]) and isset( $rawData[$metaBoxFieldModel->getName()][$fieldIndex])){
					$rawData = $rawData[$metaBoxFieldModel->getName()][$fieldIndex]['value'];
				}
			}

			// field value of a nested field in a Flexible content
			if($metaBoxFieldModel->getBlockId() !== null){
				if(
					isset($rawData['blocks']) and
					isset($rawData['blocks'][$blockIndex]) and
					isset($rawData['blocks'][$blockIndex][$blockName]) and
					isset($rawData['blocks'][$blockIndex][$blockName][$metaBoxFieldModel->getName()]) and
					isset($rawData['blocks'][$blockIndex][$blockName][$metaBoxFieldModel->getName()][$fieldIndex])
				){
					$rawData = $rawData['blocks'][$blockIndex][$blockName][$metaBoxFieldModel->getName()][$fieldIndex]['value'];
				}
			}

			if($typeEnum === 'VALUE'){
				switch ($operator) {
					case "=":
						return $rawData == $value;

					case "!=":
						return $rawData != $value;

					case "<":
						return $rawData < $value;

					case ">":
						return $rawData > $value;

					case "<=":
						return $rawData <= $value;

					case ">=":
						return $rawData >= $value;

					case "LIKE":
						return Strings::likeMatch('%'.$value.'%',$rawData);

					case "NOT_LIKE":
						return false === Strings::likeMatch('%'.$value.'%',$rawData);

					case "NULL":
						return $rawData === null;

					case "NOT_NULL":
						return $rawData !== null;

					case "BLANK":
						return $rawData === '';

					case "NOT_BLANK":
						return $rawData !== '';

					case "CHECKED":
						return $rawData == 1;

					case "NOT_CHECKED":
						return $rawData == 0;

					case 'IN':
						$value = trim($value);
						$value = explode(',', $value);

						$check = array_intersect($rawData, $value);

						return count($check) > 0;

					case 'NOT_IN':
						$value = trim($value);
						$value = explode(',', $value);

						$check = array_intersect($rawData, $value);

						return empty($check);
				}
			}

			if($typeEnum === 'POST_ID' or $typeEnum === 'TERM_ID'){
				switch ($operator) {
					case "=":
						return $value == $elementId;

					case "!=":
						return $value !== $elementId;

					case 'IN':
						$value = trim($value);
						$value = explode(',', $value);

						return in_array($elementId, $value);

					case 'NOT_IN':
						$value = trim($value);
						$value = explode(',', $value);

						return !in_array($elementId, $value);
				}
			}

			if($typeEnum === 'TAXONOMY'){

				$categories = wp_get_post_categories($elementId);
				$taxonomies = wp_get_post_terms($elementId, $typeValue);
				$allTerms = array_merge($categories, $taxonomies);
				$termIds = [];

				foreach ($allTerms as $term){
					if(isset($term->term_id)){
						$termIds[] = $term->term_id;
					}
				}

				switch ($operator) {

					case 'IN':
						$value = trim($value);
						$value = explode(',', $value);

						$check = array_intersect($termIds, $value);

						return count($check) > 0;

					case 'NOT_IN':
						$value = trim($value);
						$value = explode(',', $value);

						$check = array_intersect($termIds, $value);

						return empty($check);

					case "BLANK":
						return empty($termIds);

					case "NOT_BLANK":
						return !empty($termIds);
				}
			}

			if($typeEnum === 'OTHER_FIELDS' and $typeValue instanceof CustomPostTypeMetaBoxFieldModel){

				$fieldRawData = Meta::fetch($elementId, $belongsTo, $typeValue->getDbName());

				switch ($operator) {
					case "=":
						return $fieldRawData == $value;

					case "!=":
						return $fieldRawData != $value;

					case "<":
						return $fieldRawData < $value;

					case ">":
						return $fieldRawData > $value;

					case "<=":
						return $fieldRawData <= $value;

					case ">=":
						return $fieldRawData >= $value;

					case "LIKE":
						return Strings::likeMatch('%'.$value.'%',$fieldRawData);

					case "NOT_LIKE":
						return false === Strings::likeMatch('%'.$value.'%',$fieldRawData);

					case "NULL":
						return $fieldRawData === null;

					case "NOT_NULL":
						return $fieldRawData !== null;

					case "BLANK":
						return $fieldRawData === '';

					case "NOT_BLANK":
						return $fieldRawData !== '';

					case "CHECKED":
						return $fieldRawData == 1;

					case "NOT_CHECKED":
						return $fieldRawData == 0;

					case 'IN':
						$value = trim($value);
						$value = explode(',', $value);

						$check = array_intersect($fieldRawData, $value);

						return count($check) > 0;

					case 'NOT_IN':
						$value = trim($value);
						$value = explode(',', $value);

						$check = array_intersect($fieldRawData, $value);

						return empty($check);
				}
			}
		}

		return false;
	}

	/**
	 * @param AbstractMetaBoxFieldModel $metaBoxFieldModel
	 *
	 * @return string
	 * @throws \Exception
	 */
	private static function getKey(AbstractMetaBoxFieldModel $metaBoxFieldModel)
	{
		if($metaBoxFieldModel->getParentId() !== null){

			$metaBoxParentFieldModel = MetaRepository::getMetaField([
				'belongsTo' => $metaBoxFieldModel->getMetaBox()->metaType(),
				'id' => $metaBoxFieldModel->getParentId()
			]);

			return Strings::toDBFormat($metaBoxParentFieldModel->getMetaBox()->getName()).'_'.Strings::toDBFormat($metaBoxParentFieldModel->getName());
		}

		if($metaBoxFieldModel->getBlockId() !== null){

			$metaBoxParentBlockModel = MetaRepository::getMetaBlockById([
				'belongsTo' => $metaBoxFieldModel->getMetaBox()->metaType(),
				'id' => $metaBoxFieldModel->getBlockId()
			]);

			$metaBoxParentFieldModel = $metaBoxParentBlockModel->getMetaBoxField();

			return Strings::toDBFormat($metaBoxParentFieldModel->getMetaBox()->getName()).'_'.Strings::toDBFormat($metaBoxParentFieldModel->getName());
		}

		return Strings::toDBFormat($metaBoxFieldModel->getMetaBox()->getName()).'_'.Strings::toDBFormat($metaBoxFieldModel->getName());
	}
}