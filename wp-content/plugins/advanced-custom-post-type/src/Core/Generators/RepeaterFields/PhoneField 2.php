<?php

namespace ACPT\Core\Generators\RepeaterFields;

use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;

class PhoneField extends AbstractRepeaterField implements RepeaterFieldInterface
{
    public function render()
    {
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'[type]" value="'.CustomPostTypeMetaBoxFieldModel::PHONE_TYPE.'">';
        $field .= '<input type="hidden" name="'. esc_attr($this->getIdName()).'[original_name]" value="'.$this->fieldModel->getName().'">';
        $field .= '<input '.$this->required().' id="'.esc_attr($this->getIdName()).'[value]" name="'. esc_attr($this->getIdName()).'[value]" value="'.esc_attr($this->getDefaultValue()).'" type="tel" class="regular-text acpt-admin-meta-field-input"';

	    $min = $this->getAdvancedOption('min');
	    $max = $this->getAdvancedOption('max');
	    $pattern = $this->getAdvancedOption('pattern');

	    if($min){
		    $field .= ' minlength="'.$min.'"';
	    }

	    if($max){
		    $field .= ' maxlength="'.$max.'"';
	    }

	    if($pattern){
		    $field .= ' pattern="'.$pattern.'"';
	    }

	    $field .= '>';

        return $this->renderField($field);
    }
}
