<?php

namespace ACPT\Core\Generators\OptionPageFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;
use ACPT\Utils\Wordpress\Translator;

class RadioField extends AbstractOptionPageField implements MetaFieldInterface
{
	public function render()
	{
		$icon = 'bx:radio-circle-marked';
		$field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.OptionPageMetaBoxFieldModel::RADIO_TYPE.'">';
		$field .= '<div class="acpt_checkboxes">';
		$selected = ('' === $this->getDefaultValue()) ? 'checked="checked"' : '';

		if(empty($this->getAdvancedOption('hide_blank_radio'))){
			$field .= '<div class="item"><input name="'.esc_attr($this->getIdName()).'" id="'.esc_attr($this->getIdName()).'_blank" type="radio" '.$selected.' value="" /><label for="'.esc_attr($this->getIdName()).'_blank">'.Translator::translate('No choice').'</label></div>';
		}

		foreach ($this->fieldModel->getOptions() as $index => $option){
			$id = esc_attr($this->getIdName()).'_'.$index;
			$selected = ($option->getValue() === $this->getDefaultValue()) ? 'checked="checked"' : '';
			$field .= '<div class="item"><input name="'.esc_attr($this->getIdName()).'" id="'.$id.'" type="radio" '.$selected.' value="'.esc_attr($option->getValue()).'" /><label for="'.$id.'">'. esc_html($option->getLabel()) . '</label></div>';
		}

		$field .= '</div>';

		return $this->renderField($icon, $field);
	}
}
