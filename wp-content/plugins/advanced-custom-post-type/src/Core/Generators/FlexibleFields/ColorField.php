<?php

namespace ACPT\Core\Generators\FlexibleFields;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;

class ColorField extends AbstractFlexibleField implements FlexibleFieldInterface
{
    public function render()
    {
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'[type]" value="'.AbstractMetaBoxFieldModel::COLOR_TYPE.'">';
        $field .= '<input type="hidden" name="'. esc_attr($this->getIdName()).'[original_name]" value="'.$this->fieldModel->getName().'">';
        $field .= '<input '.$this->required().' id="'.esc_attr($this->getIdName()).'[value]" name="'. esc_attr($this->getIdName()).'[value]" type="text" class="acpt-admin-meta-field-input acpt-color-picker" value="'
                .esc_attr($this->getDefaultValue()).'">';

        return $this->renderField($field);
    }
}
