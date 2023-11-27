<?php

namespace ACPT\Core\Generators\CustomPostTypeFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Utils\Wordpress\Translator;

class SelectField extends AbstractCustomPostTypeField implements MetaFieldInterface
{
    public function render()
    {
        $icon = 'bx:bx-select-multiple';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.CustomPostTypeMetaBoxFieldModel::SELECT_TYPE.'">';
        $field .= '<select '.$this->required().' id="'.esc_attr($this->getIdName()).'" name="'. esc_attr($this->getIdName()).'" class="acpt-select2 acpt-admin-meta-field-input">';
        $field .= '<option value="">'.Translator::translate("--Select--").'</option>';

        foreach ($this->options as $option){
            $selected = ($option['value'] === $this->getDefaultValue()) ? 'selected="selected"' : '';
            $field .= '<option '.$selected.' value="'.esc_attr($option['value']).'">'.esc_html($option['label']).'</option>';
        }

        $field .= '</select>';

        echo $this->renderField($icon, $field);
    }
}