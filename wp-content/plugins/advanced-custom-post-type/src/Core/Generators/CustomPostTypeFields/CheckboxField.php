<?php

namespace ACPT\Core\Generators\CustomPostTypeFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;

class CheckboxField extends AbstractCustomPostTypeField implements MetaFieldInterface
{
	public function render()
	{
		$icon = 'bx:checkbox-checked';
		$field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.CustomPostTypeMetaBoxFieldModel::CHECKBOX_TYPE.'">';
		$field .= '<div class="acpt_checkboxes">';

		foreach ($this->options as $index => $option){
			$id = esc_attr($this->getIdName()).'_'.$index;

			if($this->getDefaultValue() === ''){
				$selected = '';
			} elseif(is_array($option['value'])){
				$selected = '';
			} else {
				$selected = (in_array($option['value'], $this->getDefaultValue())) ? 'checked="checked"' : '';
			}

			$field .= '<div class="item"><input name="'.esc_attr($this->getIdName()).'[]" id="'.$id.'" type="checkbox" '.$selected.' value="'.esc_attr($option['value']).'" /><label for="'.$id.'">'. esc_html($option['label']) . '</label></div>';
		}

		$field .= '</div>';

		echo $this->renderField($icon, $field);
	}
}