<?php

namespace ACPT\Core\Generators\TaxonomyFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\Taxonomy\TaxonomyMetaBoxFieldModel;

class ColorField extends AbstractTaxonomyField implements MetaFieldInterface
{
    public function render()
    {
        $icon = 'bx:bx-color-fill';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.TaxonomyMetaBoxFieldModel::COLOR_TYPE.'">';
        $field .= '<input '.$this->required().' id="'.esc_attr($this->getIdName()).'" name="'. esc_attr($this->getIdName()).'" type="text" class="acpt-admin-meta-field-input acpt-color-picker" value="'.esc_attr($this->getDefaultValue()).'">';

        echo $this->renderField($icon, $field);
    }
}