<?php

namespace ACPT\Core\Generators\OptionPageFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;

class SelectMultiField extends AbstractOptionPageField implements MetaFieldInterface
{
    public function render()
    {
        $icon = 'bx:bxs-select-multiple';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.OptionPageMetaBoxFieldModel::SELECT_MULTI_TYPE.'">';
        $field .= '<select '.$this->required().' multiple id="'.esc_attr($this->getIdName()).'" name="'. esc_attr($this->getIdName()).'[]" class="acpt-select2 acpt-form-control">';

        foreach ($this->fieldModel->getOptions() as $option){
            if($this->getDefaultValue() === '' or !is_array($this->getDefaultValue())){
                $selected = '';
            } else {
                $selected = (in_array($option->getValue(), $this->getDefaultValue())) ? 'selected="selected"' : '';
            }

            $field .= '<option '.$selected.' value="'.esc_attr($option->getValue()).'">'.esc_html($option->getLabel()).'</option>';
        }

        $field .= '</select>';

        return $this->renderField($icon, $field);
    }
}