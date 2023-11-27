<?php

namespace ACPT\Core\Generators\UserFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\User\UserMetaBoxFieldModel;

class AddressField extends AbstractUserField implements MetaFieldInterface
{
    public function render()
    {
        $defaultLat = (get_user_meta($this->userId, $this->getIdName().'_lat', true) !== null and get_user_meta($this->userId, $this->getIdName().'_lat', true) !== '' ) ? get_user_meta($this->userId,
                $this->getIdName().'_lat', true) : '';
        $defaultLng = (get_user_meta($this->userId, $this->getIdName().'_lng', true) !== null and get_user_meta($this->userId, $this->getIdName().'_lng', true) !== '' ) ? get_user_meta($this->userId, $this->getIdName().'_lng', true) : '';

        $icon = 'bx:bx-map';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.UserMetaBoxFieldModel::ADDRESS_TYPE.'">';
        $field .= '<input type="hidden" id="'. esc_attr($this->getIdName()).'_lat" name="'. esc_attr($this->getIdName()).'_lat" value="'.esc_attr($defaultLat).'">';
        $field .= '<input type="hidden" id="'. esc_attr($this->getIdName()).'_lng" name="'. esc_attr($this->getIdName()).'_lng" value="'.esc_attr($defaultLng).'">';
        $field .= '<input '.$this->required().' id="'.esc_attr($this->getIdName()).'" name="'. esc_attr($this->getIdName()).'" type="text" class="regular-text input-map" value="' .esc_attr($this->getDefaultValue()) .'">';
        $field .= '<div class="map_preview" id="'. esc_attr($this->getIdName()).'_map"></div>';

        echo $this->renderField($icon, $field);
    }
}