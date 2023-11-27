<?php

namespace ACPT\Core\Generators\FlexibleFields;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;

class HTMLField extends AbstractFlexibleField implements FlexibleFieldInterface
{
    public function render()
    {
        $id = $this->generateRandomId();
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'[type]" value="'.AbstractMetaBoxFieldModel::HTML_TYPE.'">';
        $field .= '<input type="hidden" name="'. esc_attr($this->getIdName()).'[original_name]" value="'.$this->fieldModel->getName().'">';
        $field .= '<textarea '.$this->required().' id="'.$id.'" name="'. esc_attr($this->getIdName()).'[value]" class="acpt-admin-meta-field-input acpt-codemirror" rows="8">'.esc_attr($this->getDefaultValue())
                .'</textarea>';

        return $this->renderField($field);
    }
}
