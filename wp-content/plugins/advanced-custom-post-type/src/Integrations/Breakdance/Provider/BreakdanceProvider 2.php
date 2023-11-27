<?php

namespace ACPT\Integrations\Breakdance\Provider;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Core\Models\MetaField\MetaBoxFieldBlockModel;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Costants\MetaTypes;
use ACPT\Integrations\Breakdance\Provider\Blocks\ACPTBlock;
use ACPT\Integrations\Breakdance\Provider\Fields\ACPTFieldInterface;

class BreakdanceProvider
{
	const STRING_FIELD = 'String';
	const GALLERY_FIELD = 'Gallery';
	const IMAGE_FIELD = 'Image';
	const OEMBED_FIELD = 'Oembed';
	const REPEATER_FIELD = 'Repeater';

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

			if($breakdanceField !== null){
				\Breakdance\DynamicData\registerField($breakdanceField);
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

			case AbstractMetaBoxFieldModel::IMAGE_TYPE:
				$fieldType = self::IMAGE_FIELD;
				break;

			case AbstractMetaBoxFieldModel::GALLERY_TYPE:
				$fieldType = self::GALLERY_FIELD;
				break;

			case AbstractMetaBoxFieldModel::EMBED_TYPE:
				$fieldType = self::OEMBED_FIELD;
				break;

			case AbstractMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE:
			case AbstractMetaBoxFieldModel::REPEATER_TYPE:
				$fieldType = self::REPEATER_FIELD;
				break;

			default:
				$fieldType = self::STRING_FIELD;
				break;
		}

		$className = 'ACPT\\Integrations\\Breakdance\\Provider\\Fields\\ACPT'.$fieldType.'Field';

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