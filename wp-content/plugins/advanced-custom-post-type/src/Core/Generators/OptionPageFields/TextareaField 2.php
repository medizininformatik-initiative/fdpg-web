<?php

namespace ACPT\Core\Generators\OptionPageFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;

class TextareaField extends AbstractOptionPageField implements MetaFieldInterface
{
	/**
	 * @return mixed|void
	 */
    public function render()
    {
        $icon = 'bx:bx-pen';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.OptionPageMetaBoxFieldModel::TEXTAREA_TYPE.'">';
        $field .= '<textarea '.$this->required().' id="'.esc_attr($this->getIdName()).'" name="'. esc_attr($this->getIdName()).'" class="acpt-form-control" rows="8" cols="50"';

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

        return $this->renderField($icon, $field);
    }
}