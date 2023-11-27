<?php

namespace ACPT\Core\Generators\RepeaterFields;

use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;

class TextareaField extends AbstractRepeaterField implements RepeaterFieldInterface
{
    public function render()
    {
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'[type]" value="'.CustomPostTypeMetaBoxFieldModel::TEXTAREA_TYPE.'">';
        $field .= '<input type="hidden" name="'. esc_attr($this->getIdName()).'[original_name]" value="'.$this->fieldModel->getName().'">';
        $field .= '<textarea '.$this->required().' id="'.esc_attr($this->getIdName()).'[value]" name="'. esc_attr($this->getIdName()).'[value]" class="acpt-admin-meta-field-input" rows="8" cols="50"';

	    $min = $this->getAdvancedOption('min');
	    $max = $this->getAdvancedOption('max');

	    if($min){
		    $field .= ' minlength="'.$min.'"';
	    }

	    if($max){
		    $field .= ' maxlength="'.$max.'"';
	    }

	    $field .= '>';

	    $field .= esc_attr($this->getDefaultValue()).'</textarea>';

        return $this->renderField($field);
    }
}
