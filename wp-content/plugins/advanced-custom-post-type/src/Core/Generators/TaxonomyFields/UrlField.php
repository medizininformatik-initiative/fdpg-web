<?php

namespace ACPT\Core\Generators\TaxonomyFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\Taxonomy\TaxonomyMetaBoxFieldModel;
use ACPT\Utils\Wordpress\Translator;

class UrlField extends AbstractTaxonomyField implements MetaFieldInterface
{
    public function render()
    {
        $icon = 'bx:bx-link';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.TaxonomyMetaBoxFieldModel::URL_TYPE.'">';
        $field .= '<input '.$this->required().' id="'.esc_attr($this->getIdName()).'" name="'. esc_attr($this->getIdName()).'" type="url" class="acpt-admin-meta-field-input mb-4" value="'.esc_attr($this->getDefaultValue()) .'" placeholder="'.Translator::translate('Enter the URL').'"';

	    $min = $this->getAdvancedOption('min');
	    $max = $this->getAdvancedOption('max');
	    $pattern = $this->getAdvancedOption('pattern');
	    $hideLabel = $this->getAdvancedOption('hide_url_label');

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

	    if(empty($hideLabel)){
		    $field .= '<input id="'.esc_attr($this->getIdName()).'_label" name="'. esc_attr($this->getIdName()).'_label" type="text" class="acpt-admin-meta-field-input mb-4" value="'.esc_attr($this->getDefaultLabel()) .'" placeholder="'.Translator::translate('Enter text link').'">';
	    }

	    echo $this->renderField($icon, $field);
    }

    /**
     * @return string
     */
    private function getDefaultLabel()
    {
        return (get_term_meta($this->termId, esc_attr($this->getIdName()).'_label', true) !== null and get_term_meta($this->termId, esc_attr($this->getIdName()).'_label', true) !== '' ) ?
            get_term_meta($this->termId, esc_attr($this->getIdName()).'_label', true) : Translator::translate('Enter text link');
    }
}