<?php

namespace ACPT\Core\Generators\TaxonomyFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\Taxonomy\TaxonomyMetaBoxFieldModel;

class EmailField extends AbstractTaxonomyField implements MetaFieldInterface
{
    public function render()
    {
        $icon = 'bx:bx-envelope';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.TaxonomyMetaBoxFieldModel::EMAIL_TYPE.'">';
        $field .= '<input '.$this->required().' id="'.esc_attr($this->getIdName()).'" name="'. esc_attr($this->getIdName()).'" type="email" class="acpt-admin-meta-field-input" value="'.esc_attr($this->getDefaultValue()).'"';

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