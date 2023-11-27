<?php

namespace ACPT\Core\Generators\OptionPageFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;

class PhoneField extends AbstractOptionPageField implements MetaFieldInterface
{
    public function render()
    {
        $icon = 'bx:bx-phone';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.OptionPageMetaBoxFieldModel::PHONE_TYPE.'">';
        $field .= '<input '.$this->required().' id="'.esc_attr($this->getIdName()).'" name="'. esc_attr($this->getIdName()).'" type="tel" class="acpt-form-control" value="'.esc_attr($this->getDefaultValue()).'"';

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

        return $this->renderField($icon, $field);
    }
}