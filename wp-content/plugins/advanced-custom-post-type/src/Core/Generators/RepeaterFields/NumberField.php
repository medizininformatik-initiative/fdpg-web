<?php

namespace ACPT\Core\Generators\RepeaterFields;

use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;

class NumberField extends AbstractRepeaterField implements RepeaterFieldInterface
{
    public function render()
    {
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'[type]" value="'.CustomPostTypeMetaBoxFieldModel::NUMBER_TYPE.'">';
        $field .= '<input type="hidden" name="'. esc_attr($this->getIdName()).'[original_name]" value="'.$this->fieldModel->getName().'">';
        $field .= '<input '.$this->required().' id="'.esc_attr($this->getIdName()).'[value]" name="'. esc_attr($this->getIdName()).'[value]" type="number" class="acpt-admin-meta-field-input" value="'. esc_attr($this->getDefaultValue()).'"';

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

        return $this->renderField($field);
    }
}
