<?php

namespace ACPT\Core\Generators\OptionPageFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;
use ACPT\Utils\Wordpress\Translator;

class UrlField extends AbstractOptionPageField implements MetaFieldInterface
{
    public function render()
    {
        $icon = 'bx:bx-link';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.OptionPageMetaBoxFieldModel::URL_TYPE.'">';
        $field .= '<input '.$this->required().' id="'.esc_attr($this->getIdName()).'" name="'. esc_attr($this->getIdName()).'" type="url" class="acpt-form-control mb-4" value="'.esc_attr($this->getDefaultValue()) .'" placeholder="'.Translator::translate('Enter the URL').'"';

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
		    $field .= '<input id="'.esc_attr($this->getIdName()).'_label" name="'. esc_attr($this->getIdName()).'_label" type="text" class="acpt-form-control mb-4" value="'.esc_attr($this->getDefaultLabel()) .'" placeholder="'.Translator::translate('Enter text link').'">';
	    }

        return $this->renderField($icon, $field);
    }

    /**
     * @return string
     */
    private function getDefaultLabel()
    {
        return (get_option(esc_attr($this->getIdName()).'_label') !== null and get_option(esc_attr($this->getIdName()).'_label') !== '' ) ?
	        get_option(esc_attr($this->getIdName()).'_label') : Translator::translate('Enter text link');
    }
}