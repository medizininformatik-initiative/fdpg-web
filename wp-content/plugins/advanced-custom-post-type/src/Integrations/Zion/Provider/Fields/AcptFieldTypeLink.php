<?php

namespace ACPT\Integrations\Zion\Provider\Fields;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Integrations\Zion\Provider\Utils\FieldSettings;
use ACPT\Integrations\Zion\Provider\Utils\FieldValue;
use ACPT\Utils\Wordpress\Translator;
use ACPT\Utils\Wordpress\WPAttachment;

class AcptFieldTypeLink extends AcptFieldBase
{
	/**
	 * Retrieve the list of all supported field types
	 * @return array
	 */
	public static function getSupportedFieldTypes()
	{
		return [
			AbstractMetaBoxFieldModel::EMAIL_TYPE,
			AbstractMetaBoxFieldModel::FILE_TYPE,
			AbstractMetaBoxFieldModel::IMAGE_TYPE,
			AbstractMetaBoxFieldModel::PHONE_TYPE,
			AbstractMetaBoxFieldModel::URL_TYPE,
			AbstractMetaBoxFieldModel::VIDEO_TYPE,
		];
	}

	/**
	 * @return string
	 */
	public function get_category()
	{
		return self::CATEGORY_LINK;
	}

	/**
	 * @return string
	 */
	public function get_id()
	{
		return 'acpt-field-link';
	}

	/**
	 * @return string
	 */
	public function get_name()
	{
		return Translator::translate( 'ACPT Link field');
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

		switch ($metaFieldModel->getType()){
			case AbstractMetaBoxFieldModel::EMAIL_TYPE:
				echo esc_url('mailto:'.$rawValue);
				break;

			case AbstractMetaBoxFieldModel::IMAGE_TYPE:
			case AbstractMetaBoxFieldModel::VIDEO_TYPE:

				if(!$rawValue instanceof WPAttachment){
					return;
				}

				echo $rawValue->getSrc();
				break;

			case AbstractMetaBoxFieldModel::FILE_TYPE:

				if(empty($rawValue)){
					return;
				}

				if(!isset($rawValue['file'])){
					return;
				}

				if(!$rawValue['file'] instanceof WPAttachment){
					return;
				}

				/** @var WPAttachment $file */
				$file = $rawValue['file'];

				echo $file->getSrc();
				break;

			case AbstractMetaBoxFieldModel::PHONE_TYPE:
				echo esc_url('tel:'.$rawValue);
				break;

			case AbstractMetaBoxFieldModel::URL_TYPE:

				if(empty($rawValue)){
					return;
				}

				if(!isset($rawValue['url'])){
					return;
				}

				echo esc_url($rawValue['url']);
				break;

			default:
				echo $rawValue;
		}
	}
}