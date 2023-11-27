<?php

namespace ACPT\Core\Generators\TaxonomyFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\Taxonomy\TaxonomyMetaBoxFieldModel;

class AddressField extends AbstractTaxonomyField implements MetaFieldInterface
{
    public function render()
    {
        $defaultLat = (get_term_meta($this->termId, $this->getIdName().'_lat', true) !== null and get_term_meta($this->termId, $this->getIdName().'_lat', true) !== '' ) ? get_term_meta($this->termId, $this->getIdName().'_lat', true) : '';
        $defaultLng = (get_term_meta($this->termId, $this->getIdName().'_lng', true) !== null and get_term_meta($this->termId, $this->getIdName().'_lng', true) !== '' ) ? get_term_meta($this->termId, $this->getIdName().'_lng', true) : '';

        $icon = 'bx:bx-map';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.TaxonomyMetaBoxFieldModel::ADDRESS_TYPE.'">';
        $field .= '<input type="hidden" id="'. esc_attr($this->getIdName()).'_lat" name="'. esc_attr($this->getIdName()).'_lat" value="'.esc_attr($defaultLat).'">';
        $field .= '<input type="hidden" id="'. esc_attr($this->getIdName()).'_lng" name="'. esc_attr($this->getIdName()).'_lng" value="'.esc_attr($defaultLng).'">';
        $field .= '<input '.$this->required().' id="'.esc_attr($this->getIdName()).'" name="'. esc_attr($this->getIdName()).'" type="text" class="acpt-admin-meta-field-input input-map" value="' .esc_attr($this->getDefaultValue()) .'">';
        $field .= '<div class="map_preview" id="'. esc_attr($this->getIdName()).'_map"></div>';

        echo $this->renderField($icon, $field);
    }
}