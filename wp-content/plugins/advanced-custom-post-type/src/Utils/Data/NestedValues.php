<?php

namespace ACPT\Utils\Data;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Utils\PHP\GeoLocation;

/**
 * Class DataMorphing
 * @package ACPT\Utils
 */
class NestedValues
{
	/**
	 * @param AbstractMetaBoxFieldModel $meta_field_model
	 * @param $block_name
	 * @param $block_index
	 * @param $values
	 * @param $value
	 * @param null $index
	 *
	 * @return array
	 * @throws \Exception
	 */
	public static function addOrUpdateBlockRawValue(AbstractMetaBoxFieldModel $meta_field_model, $block_name, $block_index, $values, $value, $index = null)
	{
		if(empty($values)){
			$values = [
				'blocks' => []
			];
		}

		foreach ($meta_field_model->getBlocks() as $block_model){
			if($block_model->getName() === $block_name){
				foreach ($block_model->getFields() as $nested_field_model){
					foreach ($value as $rawIndex => $raw){
						if($nested_field_model->getName() === $rawIndex){
							$type = $nested_field_model->getType();
							$rawValue = self::getRawValue($type, $raw, $nested_field_model->getName());

							// finally append $rawValue to $values
							if($index !== null){
								$values['blocks'][$block_index][$block_model->getNormalizedName()][$nested_field_model->getNormalizedName()][$index] = $rawValue;
							} else {
								$values['blocks'][$block_index][$block_model->getNormalizedName()][$nested_field_model->getNormalizedName()][] = $rawValue;
							}
						}
					}
				}
			}
		}

		return $values;
	}

    /**
     * This function adds or update a raw value ($value) in the $values array (representation of DB)
     * Used both by add_acpt_meta_field_row_value and edit_acpt_meta_field_row_value functions.
     * It returns the updated $values array
     *
     * @param AbstractMetaBoxFieldModel $meta_field_model
     * @param array             $values
     * @param array             $value
     * @param int               $index
     *
     * @return array
     * @throws \Exception
     */
    public static function addOrUpdateRawValue(AbstractMetaBoxFieldModel $meta_field_model, $values, $value, $index = null)
    {
        /** @var  $child AbstractMetaBoxFieldModel */
        foreach ($meta_field_model->getChildren() as $child){

            $type = $child->getType();
            $raw = $value[$child->getName()];
	        $rawValue = self::getRawValue($type, $raw, $child->getName());

            // finally append $rawValue to $values
            if($index !== null){
                $values[$child->getNormalizedName()][$index] = $rawValue;
            } else {
                $values[$child->getNormalizedName()][] = $rawValue;
            }
        }

        return $values;
    }

	/**
	 * @param $type
	 * @param $raw
	 * @param $original_name
	 *
	 * @return array
	 * @throws \Exception
	 */
    private static function getRawValue($type, $raw, $original_name)
    {
	    switch ($type){

		    // ADDRESS_TYPE
		    case CustomPostTypeMetaBoxFieldModel::ADDRESS_TYPE:

			    $coordinates = GeoLocation::getCoordinates($raw);

			    return [
				    'value' => $raw,
				    'lat' => $coordinates['lat'],
				    'lng' => $coordinates['lng'],
				    'original_name' => $original_name,
				    'type' => $type,
			    ];

		    // CURRENCY_TYPE
		    case CustomPostTypeMetaBoxFieldModel::CURRENCY_TYPE:
			    return [
				    'currency' => $raw['unit'],
				    'value' => $raw['amount'],
				    'original_name' => $original_name,
				    'type' => $type,
			    ];

		    // LENGTH_TYPE
		    case CustomPostTypeMetaBoxFieldModel::LENGTH_TYPE:
			    return [
				    'length' => $raw['unit'],
				    'value' => $raw['length'],
				    'original_name' => $original_name,
				    'type' => $type,
			    ];

		    // WEIGHT_TYPE
		    case CustomPostTypeMetaBoxFieldModel::WEIGHT_TYPE:
			    return [
				    'weight' => $raw['unit'],
				    'value' => $raw['weight'],
				    'original_name' => $original_name,
				    'type' => $type,
			    ];

		    // URL_TYPE
		    case CustomPostTypeMetaBoxFieldModel::URL_TYPE:
			    return [
				    'label' => $raw['label'],
				    'value' => $raw['url'],
				    'original_name' => $original_name,
				    'type' => $type,
			    ];

		    default:
			    return [
				    'value' => $raw,
				    'original_name' => $original_name,
				    'type' => $type,
			    ];
	    }
    }
}