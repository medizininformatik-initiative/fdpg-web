<?php

namespace ACPT\Core\Generators\FlexibleFields;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;

class AddressField extends AbstractFlexibleField implements FlexibleFieldInterface
{
    public function render()
    {
        $id = $this->generateRandomId();
        $lat = $this->getDefaultLatAndLngValues()['lat'];
        $lng = $this->getDefaultLatAndLngValues()['ltn'];
        
        $defaultLat = ($lat !== null and $lat !== '' ) ? $lat : '';
        $defaultLng = ($lng !== null and $lng !== '' ) ? $lng : '';

        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'[type]" value="'.AbstractMetaBoxFieldModel::ADDRESS_TYPE.'">';
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

        $lat = (isset($data[$key]) and isset($data[$key][$this->elementIndex]) and isset($data[$key][$this->elementIndex]['lat']) ) ? $data[$key][$this->elementIndex]['lat'] : null;
        $lng = (isset($data[$key]) and isset($data[$key][$this->elementIndex]) and isset($data[$key][$this->elementIndex]['lng']) ) ? $data[$key][$this->elementIndex]['lng'] : null;

        return [
                'lat' => $lat,
                'ltn' => $lng,
        ];
    }
}
