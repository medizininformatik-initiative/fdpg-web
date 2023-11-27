<?php

namespace ACPT\Core\Generators\UserFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\User\UserMetaBoxFieldModel;

class ColorField extends AbstractUserField implements MetaFieldInterface
{
    public function render()
    {
        $icon = 'bx:bx-color-fill';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.UserMetaBoxFieldModel::COLOR_TYPE.'">';
        $field .= '<input '.$this->required().' id="'.esc_attr($this->getIdName()).'" name="'. esc_attr($this->getIdName()).'" value="'.esc_attr($this->getDefaultValue()).'" type="text" class="regular-text acpt-color-picker" />';

        echo $this->renderField($icon, $field);
    }
}