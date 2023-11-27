<?php

namespace ACPT\Core\Generators\FlexibleFields;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;

class DateRangeField extends AbstractFlexibleField implements FlexibleFieldInterface
{
	public function render()
	{
		$field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'[type]" value="'.AbstractMetaBoxFieldModel::DATE_RANGE_TYPE.'">';
		$field .= '<input type="hidden" name="'. esc_attr($this->getIdName()).'[original_name]" value="'.$this->fieldModel->getName().'">';
		$field .= '<input '.$this->required().' id="'.esc_attr($this->getIdName()).'[value]" name="'. esc_attr($this->getIdName()).'[value]" value="'.esc_attr($this->getDefaultValue()).'" type="text" class="acpt-daterangepicker regular-text acpt-admin-meta-field-input"';

		$min = $this->getAdvancedOption('min');
		$max = $this->getAdvancedOption('max');

		if($min){
			$field .= ' data-min-date="'.$min.'"';
		}

		if($max){
			$field .= ' data-max-date="'.$max.'"';
		}

		$field .= '>';

		return $this->renderField($field);
	}
}