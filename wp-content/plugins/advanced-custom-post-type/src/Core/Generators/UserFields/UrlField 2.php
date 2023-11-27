<?php

namespace ACPT\Core\Generators\UserFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\User\UserMetaBoxFieldModel;
use ACPT\Utils\Wordpress\Translator;

class UrlField extends AbstractUserField implements MetaFieldInterface
{
    public function render()
    {
        $defaultLabel = (get_user_meta($this->userId, esc_attr($this->getIdName()).'_label', true) !== null and get_user_meta($this->userId, esc_attr($this->getIdName()).'_label', true) !== '' ) ? get_user_meta($this->userId, esc_attr($this->getIdName()).'_label', true) : 'Enter text link';

        $icon = 'bx:bx-link';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.UserMetaBoxFieldModel::URL_TYPE.'">';
        $field .= '<input '.$this->required().' id="'.esc_attr($this->getIdName()).'" name="'. esc_attr($this->getIdName()).'" value="'.esc_attr($this->getDefaultValue()).'" type="url" class="regular-text" placeholder="'.Translator::translate('Enter the URL').'"';

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
		    $field .= '<input id="'.esc_attr($this->getIdName()).'_label" name="'. esc_attr($this->getIdName()).'_label" value="'.esc_attr($defaultLabel).'" type="text" class="regular-text" placeholder="'.Translator::translate('Enter text link').'" />';
	    }

        echo $this->renderField($icon, $field);
    }
}