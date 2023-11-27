<?php

namespace ACPT\Core\Generators\FlexibleFields;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;

class CheckboxField extends AbstractFlexibleField implements FlexibleFieldInterface
{
	public function render()
	{
		$field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'[type]" value="'.AbstractMetaBoxFieldModel::CHECKBOX_TYPE.'">';
		$field .= '<input type="hidden" name="'. esc_attr($this->getIdName()).'[original_name]" value="'.$this->fieldModel->getName().'">';

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

			$field .= '<div class="item"><input name="'.esc_attr($this->getIdName()).'[value][]" id="'.$id.'" type="checkbox" '.$selected.' value="'.esc_attr($option->getValue()).'" /><label for="'.$id.'">'. esc_html($option->getLabel()) . '</label></div>';
		}

		$field .= '</div>';

		return $this->renderField($field);
	}
}