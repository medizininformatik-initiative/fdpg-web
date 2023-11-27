<?php

namespace ACPT\Core\Generators\OptionPageFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;

class CheckboxField extends AbstractOptionPageField implements MetaFieldInterface
{
	public function render()
	{
		$icon = 'bx:checkbox-checked';
		$field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.OptionPageMetaBoxFieldModel::CHECKBOX_TYPE.'">';
		$field .= '<div class="acpt_checkboxes">';

		foreach ($this->fieldModel->getOptions() as $index => $option){
			$id = esc_attr($this->getIdName()).'_'.$index;

			if($this->getDefaultValue() === '' or !is_array($this->getDefaultValue())){
				$selected = '';
			} elseif(is_array($option->getValue())){
				$selected = '';
			} else {
				$selected = (in_array($option->getValue(), $this->getDefaultValue())) ? 'checked="checked"' : '';
			}

			$field .= '<div class="item"><input name="'.esc_attr($this->getIdName()).'[]" id="'.$id.'" type="checkbox" '.$selected.' value="'.esc_attr($option->getValue()).'" /><label for="'.$id.'">'. esc_html($option->getLabel()) . '</label></div>';
		}

		$field .= '</div>';

		return $this->renderField($icon, $field);
	}
}