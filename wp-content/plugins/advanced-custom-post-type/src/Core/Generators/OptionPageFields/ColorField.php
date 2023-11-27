<?php

namespace ACPT\Core\Generators\OptionPageFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;

class ColorField extends AbstractOptionPageField implements MetaFieldInterface
{
    public function render()
    {
	    $icon = 'bx:bx-color-fill';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.OptionPageMetaBoxFieldModel::COLOR_TYPE.'">';
        $field .= '<input '.$this->required().' id="'.esc_attr($this->getIdName()).'" name="'. esc_attr($this->getIdName()).'" type="text" class="acpt-form-control acpt-color-picker" value="' .esc_attr($this->getDefaultValue()).'">';

        return $this->renderField($icon, $field);
    }
}
