<?php

namespace ACPT\Core\Generators\RepeaterFields;

use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;

class SelectMultiField extends AbstractRepeaterField implements RepeaterFieldInterface
{
    public function render()
    {
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'[type]" value="'.CustomPostTypeMetaBoxFieldModel::SELECT_MULTI_TYPE.'">';
        $field .= '<input type="hidden" name="'. esc_attr($this->getIdName()).'[original_name]" value="'.$this->fieldModel->getName().'">';
        $field .= '<select '.$this->required().' multiple id="'.esc_attr($this->getIdName()).'[value]" name="'. esc_attr($this->getIdName()).'[value][]" class="acpt-select2 acpt-admin-meta-field-input">';

        foreach ($this->fieldModel->getOptions() as $option){
            if($this->getDefaultValue() === ''){
                $selected = '';
            } else {
                $selected = (in_array($option->getValue(), $this->getDefaultValue())) ? 'selected="selected"' : '';
            }

            $field .= '<option '.$selected.' value="'.esc_attr($option->getValue()).'">'.esc_html($option->getLabel()).'</option>';
        }

        $field .= '</select>';

        return $this->renderField($field);
    }
}
