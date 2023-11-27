<?php

namespace ACPT\Core\Generators\RepeaterFields;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;

class AddressField extends AbstractRepeaterField implements RepeaterFieldInterface
{
    public function render()
    {
        $id = $this->generateRandomId();
        $lat = $this->getDefaultLatAndLngValues()['lat'];
        $lng = $this->getDefaultLatAndLngValues()['ltn'];
        
        $defaultLat = ($lat !== null and $lat !== '' ) ? $lat : '';
        $defaultLng = ($lng !== null and $lng !== '' ) ? $lng : '';

        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'[type]" value="'.CustomPostTypeMetaBoxFieldModel::ADDRESS_TYPE.'">';
        $field .= '<input type="hidden" name="'. esc_attr($this->getIdName()).'[original_name]" value="'.$this->fieldModel->getName().'">';
        $field .= '<input type="hidden" name="meta_fields[]" value="'. esc_attr($this->getIdName()).'[lat]">';
        $field .= '<input type="hidden" name="meta_fields[]" value="'. esc_attr($this->getIdName()).'[lng]">';
        $field .= '<input type="hidden" id="'. $id . '_lat" name="'. esc_attr($this->getIdName()).'[lat]" value="'.esc_attr($defaultLat).'">';
        $field .= '<input type="hidden" id="'. $id . '_lng" name="'. esc_attr($this->getIdName()).'[lng]" value="'.esc_attr($defaultLng).'">';
        $field .= '<input '.$this->required().' id="'.$id.'" name="'. esc_attr($this->getIdName()).'[value]" type="text" class="acpt-admin-meta-field-input input-map" value="' .esc_attr($this->getDefaultValue()) .'">';

        if(empty($this->value)){
            $field .= '<div style="margin-top: 10px">Please refresh the page to see the map.</div>';
        } else {
            $field .= '<div class="map_preview" id="'. $id.'_map"></div>';
        }

        return $this->renderField($field);
    }

    /**
     * @return array
     */
    private function getDefaultLatAndLngValues()
    {
        if(!isset($this->id)){
            return [
                'lat' => null,
                'ltn' => null,
            ];
        }

        $data = $this->getParentData();
        $key = Strings::toDBFormat($this->fieldModel->getName());

        $lat = (isset($data[$key]) and isset($data[$key][$this->index]) and isset($data[$key][$this->index]['lat']) ) ? $data[$key][$this->index]['lat'] : null;
        $lng = (isset($data[$key]) and isset($data[$key][$this->index]) and isset($data[$key][$this->index]['lng']) ) ? $data[$key][$this->index]['lng'] : null;

        return [
                'lat' => $lat,
                'ltn' => $lng,
        ];
    }
}
