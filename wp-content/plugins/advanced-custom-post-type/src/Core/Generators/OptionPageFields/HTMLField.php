<?php

namespace ACPT\Core\Generators\OptionPageFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;

class HTMLField extends AbstractOptionPageField implements MetaFieldInterface
{
    public function render()
    {
        $icon = 'bx:bx-code-alt';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.OptionPageMetaBoxFieldModel::HTML_TYPE.'">';
        $field .= '<textarea '.$this->required().' id="'.esc_attr($this->getIdName()).'" name="'. esc_attr($this->getIdName()).'" class="acpt-form-control acpt-codemirror" rows="8">'.esc_attr($this->getDefaultValue()).'</textarea>';

        return $this->renderField($icon, $field);
    }
}