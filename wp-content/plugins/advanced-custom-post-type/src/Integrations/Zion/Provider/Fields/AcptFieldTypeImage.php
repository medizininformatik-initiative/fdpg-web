<?php

namespace ACPT\Integrations\Zion\Provider\Fields;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Integrations\Zion\Provider\Utils\FieldSettings;
use ACPT\Integrations\Zion\Provider\Utils\FieldValue;
use ACPT\Utils\Wordpress\Translator;
use ACPT\Utils\Wordpress\WPAttachment;

class AcptFieldTypeImage extends AcptFieldBase
{
	/**
	 * Retrieve the list of all supported field types
	 * @return array
	 */
	public static function getSupportedFieldTypes()
	{
		return [
			AbstractMetaBoxFieldModel::IMAGE_TYPE,
		];
	}

	/**
	 * @return string
	 */
	public function get_category()
	{
		return self::CATEGORY_IMAGE;
	}

	/**
	 * @return string
	 */
	public function get_id()
	{
		return 'acpt-field-image';
	}

	/**
	 * @return string
	 */
	public function get_name()
	{
		return Translator::translate( 'ACPT Image field');
	}

	/**
	 * @param mixed $fieldObject
	 *
	 * @throws \Exception
	 */
	public function render($fieldObject)
	{
		//#! Invalid entry, nothing to do here
		if(empty( $fieldObject['field_name'])) {
			return;
		}

		$fieldSettings = FieldSettings::get($fieldObject['field_name']);

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

		if(!$rawValue instanceof WPAttachment){
			return;
		}

		echo $rawValue->getSrc();
	}
}