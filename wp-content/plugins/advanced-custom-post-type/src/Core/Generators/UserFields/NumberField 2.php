<?php

namespace ACPT\Core\Generators\UserFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\User\UserMetaBoxFieldModel;

class NumberField extends AbstractUserField implements MetaFieldInterface
{
    public function render()
    {
        $icon = 'bx:bx-list-ol';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.UserMetaBoxFieldModel::NUMBER_TYPE.'">';
        $field .= '<input '.$this->required().' id="'.esc_attr($this->getIdName()).'" name="'. esc_attr($this->getIdName()).'" value="'.esc_attr($this->getDefaultValue()).'" type="number" class="regular-text"';

	    $min = $this->getAdvancedOption('min');
	    $max = $this->getAdvancedOption('max');
	    $step = $this->getAdvancedOption('step');

	    if($min){
		    $field .= ' min="'.$min.'"';
	    }

	    if($max){
		    $field .= ' max="'.$max.'"';
	    }

	    if($step){
		    $field .= ' step="'.$step.'"';
	    }

	    $field .= '>';

        echo $this->renderField($icon, $field);
    }
}