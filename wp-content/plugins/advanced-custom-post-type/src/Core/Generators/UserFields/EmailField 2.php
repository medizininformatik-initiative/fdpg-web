<?php

namespace ACPT\Core\Generators\UserFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\User\UserMetaBoxFieldModel;

class EmailField extends AbstractUserField implements MetaFieldInterface
{
    public function render()
    {
        $icon = 'bx:bx-envelope';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.UserMetaBoxFieldModel::EMAIL_TYPE.'">';
        $field .= '<input '.$this->required().' id="'.esc_attr($this->getIdName()).'" name="'. esc_attr($this->getIdName()).'" value="'.esc_attr($this->getDefaultValue()).'" type="email" class="regular-text"';

	    $min = $this->getAdvancedOption('min');
	    $max = $this->getAdvancedOption('max');

	    if($min){
		    $field .= ' minlength="'.$min.'"';
	    }

	    if($max){
		    $field .= ' maxlength="'.$max.'"';
	    }

	    $field .= '>';

        echo $this->renderField($icon, $field);
    }
}