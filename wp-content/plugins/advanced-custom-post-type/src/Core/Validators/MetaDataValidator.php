<?php

namespace ACPT\Core\Validators;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Utils\PHP\Assert;

class MetaDataValidator
{
	/**
	 * @param string $type
	 * @param mixed $rawData
	 * @param bool $isRequired
	 */
	public static function validate($type, $rawData, $isRequired = false)
	{
		if($isRequired){
			Assert::notEmpty($rawData);
		}

		if(!$isRequired and empty($rawData)){
			return;
		}

		switch ($type){
			case AbstractMetaBoxFieldModel::COLOR_TYPE:
				Assert::color($rawData);
				break;

			case AbstractMetaBoxFieldModel::CHECKBOX_TYPE:
			case AbstractMetaBoxFieldModel::LIST_TYPE:
			case AbstractMetaBoxFieldModel::SELECT_MULTI_TYPE:
				foreach ($rawData as $item){
					Assert::string($item);
				}
				break;

			case AbstractMetaBoxFieldModel::CURRENCY_TYPE:
			case AbstractMetaBoxFieldModel::LENGTH_TYPE:
			case AbstractMetaBoxFieldModel::NUMBER_TYPE:
			case AbstractMetaBoxFieldModel::USER_TYPE:
			case AbstractMetaBoxFieldModel::WEIGHT_TYPE:
				Assert::numeric($rawData);
				break;

			case AbstractMetaBoxFieldModel::DATE_TYPE:
				Assert::date($rawData);
				break;

			case AbstractMetaBoxFieldModel::EMAIL_TYPE:
				Assert::email($rawData);
				break;

			case AbstractMetaBoxFieldModel::IMAGE_TYPE:
			case AbstractMetaBoxFieldModel::URL_TYPE:
				Assert::url($rawData);
				break;

			case AbstractMetaBoxFieldModel::GALLERY_TYPE:
				foreach ($rawData as $image){
					Assert::url($image);
				}
				break;

			case AbstractMetaBoxFieldModel::POST_TYPE:
				if(is_array($rawData)){
					foreach ($rawData as $item){
						Assert::numeric($item);
					}
				} else {
					Assert::numeric($rawData);
				}
				break;

			case AbstractMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE:
				foreach ($rawData as $blockRawData){
					foreach ($blockRawData as $nestedFieldsRawData){
						foreach ($nestedFieldsRawData as $nestedRawData){
							if(is_string($nestedRawData)){
								self::validate(AbstractMetaBoxFieldModel::TEXT_TYPE, $nestedRawData);
							} elseif(is_array($nestedRawData) and isset($nestedRawData['type']) and is_array($nestedRawData['type'])){
								foreach ($nestedRawData['type'] as $nestedIndex => $nestedType){
									$nestedValue = @$nestedRawData['value'][$nestedIndex];

									if(isset($nestedValue) and !empty($nestedValue)){
										self::validate($nestedType, $nestedValue);
									}
								}
							}
						}
					}
				}
				break;

			case AbstractMetaBoxFieldModel::REPEATER_TYPE:
				foreach ($rawData as $fieldRawData){
					foreach ($fieldRawData as $nestedRawData){
						if(is_array($nestedRawData['type'])){
							foreach ($nestedRawData['type'] as $nestedIndex => $nestedType){
								$nestedValue = @$nestedRawData['value'][$nestedIndex];

								if(isset($nestedValue) and !empty($nestedValue)){
									self::validate($nestedType, $nestedValue);
								}
							}
						}
					}
				}
				break;

			case AbstractMetaBoxFieldModel::USER_MULTI_TYPE:
				foreach ($rawData as $userId){
					Assert::numeric($userId);
				}
				break;

			default:
				Assert::string($rawData);
		}
	}
}