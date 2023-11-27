<?php

namespace ACPT\Core\Generators\OptionPageFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;

class TextField extends AbstractOptionPageField implements MetaFieldInterface
{
	/**
	 * @inheritDoc
	 */
	public function render()
	{
		$icon = 'bx:bx-text';
		$field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.OptionPageMetaBoxFieldModel::TEXT_TYPE.'">';
		$field .= '<input '.$this->required().' id="'.esc_attr($this->getIdName()).'" name="'. esc_attr($this->getIdName()).'" type="text" class="acpt-form-control" value="'.esc_attr($this->getDefaultValue()).'"';

		$min = $this->getAdvancedOption('min');
		$max = $this->getAdvancedOption('max');
		$pattern = $this->getAdvancedOption('pattern');

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

		return $this->renderField($icon, $field);
	}
}