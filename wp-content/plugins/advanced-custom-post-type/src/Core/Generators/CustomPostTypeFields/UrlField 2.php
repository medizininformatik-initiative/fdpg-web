<?php

namespace ACPT\Core\Generators\CustomPostTypeFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Utils\Wordpress\Translator;

class UrlField extends AbstractCustomPostTypeField implements MetaFieldInterface
{
    public function render()
    {
        $defaultLabel = (get_post_meta($this->postId, esc_attr($this->getIdName()).'_label', true) !== null and get_post_meta($this->postId, esc_attr($this->getIdName()).'_label', true) !== '' ) ?
                get_post_meta($this->postId, esc_attr($this->getIdName()).'_label', true) : 'Enter text link';

        $icon = 'bx:bx-link';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.CustomPostTypeMetaBoxFieldModel::URL_TYPE.'">';
        $field .= '<input type="hidden" name="meta_fields[]" value="'. esc_html($this->getIdName()).'_label">';

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
		    $field .= '<input id="'.esc_attr($this->getIdName()).'_label" name="'. esc_attr($this->getIdName()).'_label" type="text" class="acpt-admin-meta-field-input mb-4" value="'.esc_attr($defaultLabel) .'" placeholder="'.Translator::translate('Enter text link').'">';
	    }

        echo $this->renderField($icon, $field);
    }

}