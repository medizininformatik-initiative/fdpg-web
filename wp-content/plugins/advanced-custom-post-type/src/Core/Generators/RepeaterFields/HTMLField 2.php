<?php

namespace ACPT\Core\Generators\RepeaterFields;

use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;

class HTMLField extends AbstractRepeaterField implements RepeaterFieldInterface
{
    public function render()
    {
        $id = $this->generateRandomId();
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'[type]" value="'.CustomPostTypeMetaBoxFieldModel::HTML_TYPE.'">';
        $field .= '<input type="hidden" name="'. esc_attr($this->getIdName()).'[original_name]" value="'.$this->fieldModel->getName().'">';
        $field .= '<textarea '.$this->required().' id="'.$id.'" name="'. esc_attr($this->getIdName()).'[value]" class="acpt-admin-meta-field-input code" rows="8">'.esc_attr($this->getDefaultValue())
                .'</textarea>';

        return $this->renderField($field);
    }
}
