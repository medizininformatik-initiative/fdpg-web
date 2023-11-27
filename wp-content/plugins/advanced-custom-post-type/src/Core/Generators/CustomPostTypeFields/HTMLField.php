<?php

namespace ACPT\Core\Generators\CustomPostTypeFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;

class HTMLField extends AbstractCustomPostTypeField implements MetaFieldInterface
{
    public function render()
    {
        $icon = 'bx:bx-code-alt';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.CustomPostTypeMetaBoxFieldModel::HTML_TYPE.'">';
        $field .= '<textarea '.$this->required().' id="'.esc_attr($this->getIdName()).'" name="'. esc_attr($this->getIdName()).'" class="acpt-admin-meta-field-input acpt-codemirror" rows="8">'.esc_attr($this->getDefaultValue()).'</textarea>';

        echo $this->renderField($icon, $field);
    }
}