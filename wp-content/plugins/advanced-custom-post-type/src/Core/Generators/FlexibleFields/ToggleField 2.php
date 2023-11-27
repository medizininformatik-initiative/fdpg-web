<?php

namespace ACPT\Core\Generators\FlexibleFields;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;

class ToggleField extends AbstractFlexibleField implements FlexibleFieldInterface
{
    public function render()
    {
        $id = $this->generateRandomId();
        $checked = ($this->getToggleValue() == 1) ? 'checked="checked"' : '';

        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'[type]" value="'.AbstractMetaBoxFieldModel::TOGGLE_TYPE.'">';
        $field .= '<input type="hidden" name="'. esc_attr($this->getIdName()).'[original_name]" value="'.$this->fieldModel->getName().'">';
        $field .= '<input type="hidden" id="'.$id.'" name="'.esc_html($this->getIdName()).'[value]" value="'.esc_attr($this->getToggleValue()).'">';
        $field .= '<input id="'.$id.'" type="checkbox" class="wppd-ui-toggle" '.$checked.'>';

        return $this->renderField($field);
    }

    /**
     * @return int
     */
    private function getToggleValue()
    {
        if($this->getDefaultValue()){
            return $this->getDefaultValue();
        }

        return 0;
    }
}
