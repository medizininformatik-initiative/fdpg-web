<?php

namespace ACPT\Core\Generators\CustomPostTypeFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;

class AddressField extends AbstractCustomPostTypeField implements MetaFieldInterface
{
    public function render()
    {
        $defaultLat = (get_post_meta($this->postId, $this->getIdName().'_lat', true) !== null and get_post_meta($this->postId, $this->getIdName().'_lat', true) !== '' ) ? get_post_meta($this->postId, $this->getIdName().'_lat', true) : '';
        $defaultLng = (get_post_meta($this->postId, $this->getIdName().'_lng', true) !== null and get_post_meta($this->postId, $this->getIdName().'_lng', true) !== '' ) ? get_post_meta($this->postId, $this->getIdName().'_lng', true) : '';

        $icon = 'bx:bx-map';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.CustomPostTypeMetaBoxFieldModel::ADDRESS_TYPE.'">';
        $field .= '<input type="hidden" name="meta_fields[]" value="'. esc_attr($this->getIdName()).'_lat">';
        $field .= '<input type="hidden" name="meta_fields[]" value="'. esc_attr($this->getIdName()).'_lng">';
        $field .= '<input type="hidden" id="'. esc_attr($this->getIdName()).'_lat" name="'. esc_attr($this->getIdName()).'_lat" value="'.esc_attr($defaultLat).'">';
        $field .= '<input type="hidden" id="'. esc_attr($this->getIdName()).'_lng" name="'. esc_attr($this->getIdName()).'_lng" value="'.esc_attr($defaultLng).'">';
        $field .= '<input '.$this->required().' id="'.esc_attr($this->getIdName()).'" name="'. esc_attr($this->getIdName()).'" type="text" class="acpt-admin-meta-field-input input-map" value="' .esc_attr($this->getDefaultValue()) .'">';
        $field .= '<div class="map_preview" id="'. esc_attr($this->getIdName()).'_map"></div>';

        echo $this->renderField($icon, $field);
    }
}