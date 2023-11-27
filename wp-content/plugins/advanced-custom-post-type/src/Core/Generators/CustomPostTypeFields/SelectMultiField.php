<?php

namespace ACPT\Core\Generators\CustomPostTypeFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;

class SelectMultiField extends AbstractCustomPostTypeField implements MetaFieldInterface
{
    public function render()
    {
        $icon = 'bx:bxs-select-multiple';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.CustomPostTypeMetaBoxFieldModel::SELECT_MULTI_TYPE.'">';
        $field .= '<select '.$this->required().' multiple id="'.esc_attr($this->getIdName()).'" name="'. esc_attr($this->getIdName()).'[]" class="acpt-select2 acpt-admin-meta-field-input">';

        foreach ($this->options as $option){
            if($this->getDefaultValue() === ''){
                $selected = '';
            } else {
                $selected = (in_array($option['value'], $this->getDefaultValue())) ? 'selected="selected"' : '';
            }

            $field .= '<option '.$selected.' value="'.esc_attr($option['value']).'">'.esc_html($option['label']).'</option>';
        }

        $field .= '</select>';

        echo $this->renderField($icon, $field);
    }
}