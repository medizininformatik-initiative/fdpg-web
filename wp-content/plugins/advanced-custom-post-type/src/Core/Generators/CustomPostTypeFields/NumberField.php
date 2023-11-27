<?php

namespace ACPT\Core\Generators\CustomPostTypeFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;

class NumberField extends AbstractCustomPostTypeField implements MetaFieldInterface
{
    public function render()
    {
        $icon = 'bx:bx-list-ol';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.CustomPostTypeMetaBoxFieldModel::NUMBER_TYPE.'">';
        $field .= '<input '.$this->required().' id="'.esc_attr($this->getIdName()).'" name="'. esc_attr($this->getIdName()).'" type="number" class="acpt-admin-meta-field-input" value="'. esc_attr($this->getDefaultValue()).'"';

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