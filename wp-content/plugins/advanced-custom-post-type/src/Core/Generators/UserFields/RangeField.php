<?php

namespace ACPT\Core\Generators\UserFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\User\UserMetaBoxFieldModel;

class RangeField extends AbstractUserField implements MetaFieldInterface
{
	public function render()
	{
		$icon = 'bx:slider-alt';
		$min = ($this->getAdvancedOption('min') and is_numeric($this->getAdvancedOption('min'))) ? (int)$this->getAdvancedOption('min') : 0;
		$max = ($this->getAdvancedOption('max') and is_numeric($this->getAdvancedOption('max'))) ? (int)$this->getAdvancedOption('max') : 100;
		$step = ($this->getAdvancedOption('step') and is_numeric($this->getAdvancedOption('step'))) ? (int)$this->getAdvancedOption('step') : 1;
		$delta = $max - $min;
		$oneFourth = ceil((int)$delta*0.25);
		$oneHalf = ceil((int)$delta*0.5);
		$threeFourths = ceil((int)$delta*0.75);
		$defaultValue = (!empty($this->getDefaultValue())) ? $this->getDefaultValue() : $oneHalf;

		$field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.UserMetaBoxFieldModel::RANGE_TYPE.'">';
		$field .= '<div class="acpt-range-wrapper">';
		$field .= '<div class="">';
		$field .= '<input '.$this->required().' id="'.esc_attr($this->getIdName()).'" list="'.esc_attr($this->getIdName()).'_markers" name="'. esc_attr($this->getIdName()).'" type="range" class="regular-text acpt-range" value="'. esc_attr($defaultValue).'"';

		if($min){
			$field .= ' min="'.$min.'"';
		}

		if($max){
			$field .= ' max="'.$max.'"';
		}

		if($step){
			$field .= ' step="'.$step.'"';
		}

		$field .= '>';

		$field .= '<datalist class="acpt-datalist" id="'.esc_attr($this->getIdName()).'_markers">';
		$field .= '<option value="'.$min.'" label="'.$min.'">'.$min.'</option>';
		$field .= '<option value="'.$oneFourth.'" label="'.$oneFourth.'">'.$oneFourth.'</option>';
		$field .= '<option value="'.$oneHalf.'" label="'.$oneHalf.'">'.$oneHalf.'</option>';
		$field .= '<option value="'.$threeFourths.'" label="'.$threeFourths.'">'.$threeFourths.'</option>';
		$field .= '<option value="'.$max.'" label="'.$max.'">'.$max.'</option>';
		$field .= '</datalist>';
		$field .= '</div>';

		$field .= '<span id="'.esc_attr($this->getIdName()).'_value" class="acpt-range-value">'.esc_attr($defaultValue).'</span>';
		$field .= '</div>';

		echo $this->renderField($icon, $field);
	}
}