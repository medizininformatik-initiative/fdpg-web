<?php

namespace ACPT\Core\Generators\OptionPageFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;

class AddressField extends AbstractOptionPageField implements MetaFieldInterface
{
    public function render()
    {
        $defaultLat = (get_option($this->getIdName().'_lat') !== null and get_option($this->getIdName().'_lat') !== '' ) ? get_option($this->getIdName().'_lat') : '';
        $defaultLng = (get_option($this->getIdName().'_lng') !== null and get_option($this->getIdName().'_lng') !== '' ) ? get_option($this->getIdName().'_lng') : '';

        $icon = 'bx:bx-map';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.OptionPageMetaBoxFieldModel::ADDRESS_TYPE.'">';
        $field .= '<input type="hidden" id="'. esc_attr($this->getIdName()).'_lat" name="'. esc_attr($this->getIdName()).'_lat" value="'.esc_attr($defaultLat).'">';
        $field .= '<input type="hidden" id="'. esc_attr($this->getIdName()).'_lng" name="'. esc_attr($this->getIdName()).'_lng" value="'.esc_attr($defaultLng).'">';
        $field .= '<input '.$this->required().' id="'.esc_attr($this->getIdName()).'" name="'. esc_attr($this->getIdName()).'" type="text" class="acpt-form-control input-map" value="' .esc_attr($this->getDefaultValue()) .'">';
        $field .= '<div class="map_preview" id="'. esc_attr($this->getIdName()).'_map"></div>';

        return $this->renderField($icon, $field);
    }
}