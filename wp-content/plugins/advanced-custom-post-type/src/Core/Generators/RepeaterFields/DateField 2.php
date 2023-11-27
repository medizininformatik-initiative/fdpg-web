<?php

namespace ACPT\Core\Generators\RepeaterFields;

use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;

class DateField extends AbstractRepeaterField implements RepeaterFieldInterface
{
    public function render()
    {
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'[type]" value="'.CustomPostTypeMetaBoxFieldModel::DATE_TYPE.'">';
        $field .= '<input type="hidden" name="'. esc_attr($this->getIdName()).'[original_name]" value="'.$this->fieldModel->getName().'">';
        $field .= '<input '.$this->required().' id="'.esc_attr($this->getIdName()).'[value]" name="'. esc_attr($this->getIdName()).'[value]" value="'.esc_attr($this->getDefaultValue()).'" type="date" class="regular-text acpt-admin-meta-field-input"';

	    $min = $this->getAdvancedOption('min');
	    $max = $this->getAdvancedOption('max');

	    if($min){
		    $field .= ' min="'.$min.'"';
	    }

	    if($max){
		    $field .= ' max="'.$max.'"';
	    }

	    $field .= '>';

        return $this->renderField($field);
    }
}
