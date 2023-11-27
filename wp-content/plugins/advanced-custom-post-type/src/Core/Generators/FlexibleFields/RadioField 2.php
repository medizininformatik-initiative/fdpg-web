<?php

namespace ACPT\Core\Generators\FlexibleFields;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Utils\Wordpress\Translator;

class RadioField extends AbstractFlexibleField implements FlexibleFieldInterface
{
	public function render()
	{
		$field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'[type]" value="'.AbstractMetaBoxFieldModel::RADIO_TYPE.'">';
		$field .= '<input type="hidden" name="'. esc_attr($this->getIdName()).'[original_name]" value="'.$this->fieldModel->getName().'">';

		$field .= '<div class="acpt_checkboxes">';
		$selected = ('' === $this->getDefaultValue()) ? 'checked="checked"' : '';

		if(empty($this->getAdvancedOption('hide_blank_radio'))){
			$field .= '<div class="item"><input name="'.esc_attr($this->getIdName()).'[value]" id="'.esc_attr($this->getIdName()).'_blank" type="radio" '.$selected.' value="" /><label for="'.esc_attr($this->getIdName()).'_blank">'.Translator::translate('No choice').'</label></div>';
		}

		foreach ($this->fieldModel->getOptions() as $index => $option){
			$id = esc_attr($this->getIdName()).'_'.$index;
			$selected = ($option->getValue() === $this->getDefaultValue()) ? 'checked="checked"' : '';
			$field .= '<div class="item"><input name="'.esc_attr($this->getIdName()).'[value]" id="'.$id.'" type="radio" '.$selected.' value="'.esc_attr($option->getValue()).'" /><label for="'.$id.'">'. esc_html($option->getLabel()) . '</label></div>';
		}

		$field .= '</div>';

		return $this->renderField($field);
	}
}