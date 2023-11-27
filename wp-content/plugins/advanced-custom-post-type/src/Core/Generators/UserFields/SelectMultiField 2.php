<?php

namespace ACPT\Core\Generators\UserFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\User\UserMetaBoxFieldModel;

class SelectMultiField extends AbstractUserField implements MetaFieldInterface
{
    public function render()
    {
        $icon = 'bx:bxs-select-multiple';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.UserMetaBoxFieldModel::SELECT_MULTI_TYPE.'">';
        $field .= '<select '.$this->required().' multiple id="'.esc_attr($this->getIdName()).'" name="'. esc_attr($this->getIdName()).'[]" class="acpt-select2 regular-text">';

        foreach ($this->options as $option){
            if($this->getDefaultValue() === ''){
                $selected = '';
            } else {
                $selected = (in_array($option->getValue(), $this->getDefaultValue())) ? 'selected="selected"' : '';
            }

            $field .= '<option '.$selected.' value="'.esc_attr($option->getValue()).'">'.esc_html($option->getLabel()).'</option>';
        }

        $field .= '</select>';

        echo $this->renderField($icon, $field);
    }
}