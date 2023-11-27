<?php

namespace ACPT\Core\Generators\CustomPostTypeFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;

class DateField extends AbstractCustomPostTypeField implements MetaFieldInterface
{
    public function render()
    {
        $icon = 'bx:bx-calendar';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.CustomPostTypeMetaBoxFieldModel::DATE_TYPE.'">';
        $field .= '<input '.$this->required().' id="'.esc_attr($this->getIdName()).'" name="'. esc_attr($this->getIdName()).'" type="date" class="acpt-admin-meta-field-input" value="'.$this->getDefaultValue().'"';

	    $min = $this->getAdvancedOption('min');
	    $max = $this->getAdvancedOption('max');

	    if($min){
		    $field .= ' min="'.$min.'"';
	    }

	    if($max){
		    $field .= ' max="'.$max.'"';
	    }

	    $field .= '>';

        echo $this->renderField($icon, $field);
    }
}