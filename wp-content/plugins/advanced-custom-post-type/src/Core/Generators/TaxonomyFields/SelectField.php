<?php

namespace ACPT\Core\Generators\TaxonomyFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\Taxonomy\TaxonomyMetaBoxFieldModel;
use ACPT\Utils\Wordpress\Translator;

class SelectField extends AbstractTaxonomyField implements MetaFieldInterface
{
    public function render()
    {
        $icon = 'bx:bx-select-multiple';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.TaxonomyMetaBoxFieldModel::SELECT_TYPE.'">';
        $field .= '<select '.$this->required().' id="'.esc_attr($this->getIdName()).'" name="'. esc_attr($this->getIdName()).'" class="acpt-select2 acpt-admin-meta-field-input">';
        $field .= '<option value="">'.Translator::translate("--Select--").'</option>';

        foreach ($this->metaBoxFieldModel->getOptions() as $option){
            $selected = ($option->getValue() === $this->getDefaultValue()) ? 'selected="selected"' : '';
            $field .= '<option '.$selected.' value="'.esc_attr($option->getValue()).'">'.esc_html($option->getLabel()).'</option>';
        }

        $field .= '</select>';

        echo $this->renderField($icon, $field);
    }
}