<?php

namespace ACPT\Integrations\Zion\Provider\Fields;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Integrations\Zion\Provider\Utils\FieldSettings;
use ACPT\Integrations\Zion\Provider\Utils\FieldValue;
use ACPT\Utils\Wordpress\Translator;

class AcptFieldTypeText extends AcptFieldBase
{
	/**
	 * Retrieve the list of all supported field types
	 * @return array
	 */
	public static function getSupportedFieldTypes()
	{
		return [
			AbstractMetaBoxFieldModel::ADDRESS_TYPE,
			AbstractMetaBoxFieldModel::COLOR_TYPE,
			AbstractMetaBoxFieldModel::EDITOR_TYPE,
			AbstractMetaBoxFieldModel::EMAIL_TYPE,
			AbstractMetaBoxFieldModel::EMBED_TYPE,
			AbstractMetaBoxFieldModel::HTML_TYPE,
			AbstractMetaBoxFieldModel::NUMBER_TYPE,
			AbstractMetaBoxFieldModel::PHONE_TYPE,
			AbstractMetaBoxFieldModel::RANGE_TYPE,
			AbstractMetaBoxFieldModel::TEXT_TYPE,
			AbstractMetaBoxFieldModel::TEXTAREA_TYPE,
			AbstractMetaBoxFieldModel::TOGGLE_TYPE,
		];
	}

	/**
	 * @return string
	 */
	public function get_category()
	{
		return self::CATEGORY_TEXT;
	}

	/**
	 * @return string
	 */
	public function get_id()
	{
		return 'acpt-field-text';
	}

	/**
	 * @return string
	 */
	public function get_name()
	{
		return Translator::translate( 'ACPT Textual field');
	}

	/**
	 * @param mixed $fieldObject
	 *
	 * @throws \Exception
	 */
	public function render($fieldObject)
	{
		//#! Invalid entry, nothing to do here
		if ( empty( $fieldObject[ 'field_name' ] ) ) {
			return;
		}

		$fieldSettings = FieldSettings::get($fieldObject[ 'field_name' ]);

		if($fieldSettings === false or empty($fieldSettings)){
			return;
		}

		/** @var AbstractMetaBoxFieldModel $metaFieldModel */
		$metaFieldModel = $fieldSettings['model'];
		$belongsTo = $fieldSettings['belongsTo'];

		if(!$this->isSupportedFieldType($metaFieldModel->getType())){
			return;
		}

		$rawValue = FieldValue::raw($belongsTo, $metaFieldModel);

		if(empty($rawValue)){
			return;
		}

		switch ($metaFieldModel->getType()){
			case AbstractMetaBoxFieldModel::CHECKBOX_TYPE:
			case AbstractMetaBoxFieldModel::SELECT_MULTI_TYPE:
				if(!is_array($rawValue)){
					return;
				}

				echo implode(',', $rawValue);
				break;

			case AbstractMetaBoxFieldModel::TOGGLE_TYPE:

				echo ($rawValue == 1) ? '1' : '0';
				break;

			default:
			echo $rawValue;
		}
	}
}