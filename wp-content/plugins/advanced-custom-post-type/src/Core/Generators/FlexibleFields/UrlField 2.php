<?php

namespace ACPT\Core\Generators\FlexibleFields;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Utils\Wordpress\Translator;

class UrlField extends AbstractFlexibleField implements FlexibleFieldInterface
{
    public function render()
    {
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'[type]" value="'.AbstractMetaBoxFieldModel::URL_TYPE.'">';
        $field .= '<input type="hidden" name="'. esc_attr($this->getIdName()).'[original_name]" value="'.$this->fieldModel->getName().'">';
        $field .= '<input type="hidden" name="meta_fields[]" value="'.esc_attr($this->getIdName()).'[label]">';
        $field .= '<input '.$this->required().' id="'.esc_attr($this->getIdName()).'[value]" name="'. esc_attr($this->getIdName()).'[value]" type="url" class="acpt-admin-meta-field-input mb-4" value="'. esc_attr($this->getDefaultValue()) .'" placeholder="'.Translator::translate('Enter the URL').'"';

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
		    $field .= '<input id="'.esc_attr($this->getIdName()).'[label]'.'" name="'. esc_attr($this->getIdName()).'[label]' .'" type="text" class="acpt-admin-meta-field-input mb-4" value="'.esc_attr($this->getDefaultLabel()) .'" placeholder="'.Translator::translate('Enter text link').'">';
	    }

        return $this->renderField($field);
    }

    /**
     * @return string
     */
    private function getDefaultLabel()
    {
        if(!isset($this->id)){
            return 'Enter text link';
        }

	    $data = $this->getParentData();
        $key = Strings::toDBFormat($this->fieldModel->getName());

        return (isset($data[$key]) and isset($data[$key][$this->elementIndex]) and isset($data[$key][$this->elementIndex]['label'])) ? $data[$key][$this->elementIndex]['label'] : 'Enter text link';
    }
}
