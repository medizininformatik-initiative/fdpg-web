<?php

namespace ACPT\Core\Generators\TaxonomyFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\Taxonomy\TaxonomyMetaBoxFieldModel;

class CheckboxField extends AbstractTaxonomyField implements MetaFieldInterface
{
	public function render()
	{
		$icon = 'bx:checkbox-checked';
		$field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.TaxonomyMetaBoxFieldModel::CHECKBOX_TYPE.'">';
		$field .= '<div class="acpt_checkboxes">';

		foreach ($this->metaBoxFieldModel->getOptions() as $index => $option){
			$id = esc_attr($this->getIdName()).'_'.$index;

			if($this->getDefaultValue() === ''){
				$selected = '';
			} elseif(is_array($option->getValue())){
				$selected = '';
			} else {
				$selected = (in_array($option->getValue(), $this->getDefaultValue())) ? 'checked="checked"' : '';
			}

			$field .= '<div class="item"><input name="'.esc_attr($this->getIdName()).'[]" id="'.$id.'" type="checkbox" '.$selected.' value="'.esc_attr($option->getValue()).'" /><label for="'.$id.'">'. esc_html($option->getLabel()) . '</label></div>';
		}

		$field .= '</div>';

		echo $this->renderField($icon, $field);
	}
}