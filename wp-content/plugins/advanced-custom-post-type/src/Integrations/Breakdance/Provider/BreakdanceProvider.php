<?php

namespace ACPT\Integrations\Breakdance\Provider;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Core\Models\MetaField\MetaBoxFieldBlockModel;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Costants\MetaTypes;
use ACPT\Integrations\Breakdance\Provider\Blocks\ACPTBlock;
use ACPT\Integrations\Breakdance\Constants\BreakdanceField;
use ACPT\Integrations\Breakdance\Provider\Fields\ACPTFieldInterface;

class BreakdanceProvider
{
	/**
	 * Register ACPT fields
	 *
	 * @see https://github.com/soflyy/breakdance-sample-dynamic-data/tree/master
	 *
	 * @throws \Exception
	 */
	public static function init()
	{
		// fetch settings
		$settings = self::fetchSettings();
		$fields = $settings['fields'];
 		$blocks = $settings['blocks'];

		foreach ($fields as $field){
			$breakdanceField = self::getBreakdanceField($field);
			$breakdanceFieldAsUrl = self::getBreakdanceFieldAsUrl($field);

			if($breakdanceField !== null){
				\Breakdance\DynamicData\registerField($breakdanceField);
			}

			if($breakdanceFieldAsUrl !== null){
				\Breakdance\DynamicData\registerField($breakdanceFieldAsUrl);
			}
		}

		foreach ($blocks as $block){
			$breakdanceField = self::getBreakdanceBlock($block);

			if($breakdanceField !== null){
				\Breakdance\DynamicData\registerField($breakdanceField);
			}
		}
	}

	/**
	 * @return AbstractMetaBoxFieldModel[]
	 * @throws \Exception
	 */
	private static function fetchSettings()
	{
		$fields = [];
		$blocks = [];

		// CPT fields
		$cptBoxes = MetaRepository::get([
			'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
		]);

		foreach ( $cptBoxes as $cptBox ) {
			foreach ($cptBox->getFields() as $field){

				// Exclude the Flexible fields, allow only the blocks
				if($field->getType() !== AbstractMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE){
					$fields[] = $field;
				}

				// REPEATER
				if($field->getType() === AbstractMetaBoxFieldModel::REPEATER_TYPE and $field->hasChildren()){
					foreach ($field->getChildren() as $childField){
						$fields[] = $childField;
					}
				}

				// FLEXIBLE
				if($field->getType() === AbstractMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE and $field->hasBlocks()){
					foreach ($field->getBlocks() as $blockModel){
						foreach ($blockModel->getFields() as $nestedField){
							$fields[] = $nestedField;
						}

						$blocks[] = $blockModel;
					}
				}
			}
		}

		// TAX fields
		$taxBoxes = MetaRepository::get([
			'belongsTo' => MetaTypes::TAXONOMY,
		]);

		foreach ( $taxBoxes as $taxBox ) {
			foreach ($taxBox->getFields() as $field){
				$fields[] = $field;
			}
		}

		// OP fields
		$opBoxes = MetaRepository::get([
			'belongsTo' => MetaTypes::OPTION_PAGE,
		]);

		foreach ( $opBoxes as $opBox ) {
			foreach ($opBox->getFields() as $field){

				// Exclude the Flexible fields, allow only the blocks
				if($field->getType() !== AbstractMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE){
					$fields[] = $field;
				}

				// REPEATER
				if($field->getType() === AbstractMetaBoxFieldModel::REPEATER_TYPE and $field->hasChildren()){
					foreach ($field->getChildren() as $childField){
						$fields[] = $childField;
					}
				}

				// FLEXIBLE
				if($field->getType() === AbstractMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE and $field->hasBlocks()){
					foreach ($field->getBlocks() as $blockModel){
						foreach ($blockModel->getFields() as $nestedField){
							$fields[] = $nestedField;
						}

						$blocks[] = $blockModel;
					}
				}
			}
		}

		return [
			'blocks' => $blocks,
			'fields' => $fields,
		];
	}

	/**
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 *
	 * @return null|ACPTFieldInterface
	 */
	private static function getBreakdanceField(AbstractMetaBoxFieldModel $fieldModel)
	{
		$fieldType = null;

		switch ($fieldModel->getType()){

			case AbstractMetaBoxFieldModel::CURRENCY_TYPE:
				$fieldType = BreakdanceField::CURRENCY;
				break;

			case AbstractMetaBoxFieldModel::DATE_TYPE:
				$fieldType = BreakdanceField::DATE;
				break;

			case AbstractMetaBoxFieldModel::DATE_RANGE_TYPE:
				$fieldType = BreakdanceField::DATE_RANGE;
				break;

			case AbstractMetaBoxFieldModel::EMAIL_TYPE:
				$fieldType = BreakdanceField::EMAIL;
				break;

			case AbstractMetaBoxFieldModel::EMBED_TYPE:
				$fieldType = BreakdanceField::OEMBED;
				break;

			case AbstractMetaBoxFieldModel::FILE_TYPE:
				$fieldType = BreakdanceField::FILE;
				break;

			case AbstractMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE:
			case AbstractMetaBoxFieldModel::REPEATER_TYPE:
				$fieldType = BreakdanceField::REPEATER;
				break;

			case AbstractMetaBoxFieldModel::GALLERY_TYPE:
				$fieldType = BreakdanceField::GALLERY;
				break;

			case AbstractMetaBoxFieldModel::ICON_TYPE:
				$fieldType = BreakdanceField::ICON;
				break;

			case AbstractMetaBoxFieldModel::IMAGE_TYPE:
				$fieldType = BreakdanceField::IMAGE;
				break;

			case AbstractMetaBoxFieldModel::LENGTH_TYPE:
				$fieldType = BreakdanceField::LENGTH;
				break;

			case AbstractMetaBoxFieldModel::NUMBER_TYPE:
			case AbstractMetaBoxFieldModel::RANGE_TYPE:
				$fieldType = BreakdanceField::NUMBER;
				break;

			case AbstractMetaBoxFieldModel::PHONE_TYPE:
				$fieldType = BreakdanceField::PHONE;
				break;

			case AbstractMetaBoxFieldModel::RATING_TYPE:
				$fieldType = BreakdanceField::RATING;
				break;

			case AbstractMetaBoxFieldModel::TIME_TYPE:
				$fieldType = BreakdanceField::TIME;
				break;

			case AbstractMetaBoxFieldModel::URL_TYPE:
				$fieldType = BreakdanceField::URL;
				break;

			case AbstractMetaBoxFieldModel::VIDEO_TYPE:
				$fieldType = BreakdanceField::VIDEO;
				break;

			case AbstractMetaBoxFieldModel::WEIGHT_TYPE:
				$fieldType = BreakdanceField::WEIGHT;
				break;

			case AbstractMetaBoxFieldModel::CHECKBOX_TYPE:
			case AbstractMetaBoxFieldModel::RADIO_TYPE:
			case AbstractMetaBoxFieldModel::SELECT_TYPE:
			case AbstractMetaBoxFieldModel::SELECT_MULTI_TYPE:
				$fieldType = BreakdanceField::LABEL_VALUE;
				break;

			default:
				$fieldType = BreakdanceField::STRING;
				break;
		}

		$className = 'ACPT\\Integrations\\Breakdance\\Provider\\Fields\\ACPT'.$fieldType.'Field';

		if(class_exists($className)){
			return new $className($fieldModel);
		}

		return null;
	}

	/**
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 *
	 * @return null|ACPTFieldInterface
	 */
	private static function getBreakdanceFieldAsUrl(AbstractMetaBoxFieldModel $fieldModel)
	{
		$fieldType = null;

		switch ($fieldModel->getType()){

			case AbstractMetaBoxFieldModel::EMAIL_TYPE:
				$fieldType = BreakdanceField::EMAIL;
				break;

			case AbstractMetaBoxFieldModel::FILE_TYPE:
				$fieldType = BreakdanceField::FILE;
				break;

			case AbstractMetaBoxFieldModel::IMAGE_TYPE:
				$fieldType = BreakdanceField::IMAGE;
				break;

			case AbstractMetaBoxFieldModel::PHONE_TYPE:
				$fieldType = BreakdanceField::PHONE;
				break;

			case AbstractMetaBoxFieldModel::URL_TYPE:
				$fieldType = BreakdanceField::URL;
				break;

			case AbstractMetaBoxFieldModel::VIDEO_TYPE:
				$fieldType = BreakdanceField::VIDEO;
				break;
		}

		$className = 'ACPT\\Integrations\\Breakdance\\Provider\\Fields\\ACPT'.$fieldType.'AsUrlField';

		if(class_exists($className)){
			return new $className($fieldModel);
		}

		return null;
	}

	/**
	 * @param MetaBoxFieldBlockModel $blockModel
	 *
	 * @return ACPTBlock
	 */
	private static function getBreakdanceBlock(MetaBoxFieldBlockModel $blockModel)
	{
		return new ACPTBlock($blockModel);
	}
}