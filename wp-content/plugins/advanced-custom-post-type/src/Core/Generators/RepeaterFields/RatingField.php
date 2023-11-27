<?php

namespace ACPT\Core\Generators\RepeaterFields;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Utils\Wordpress\Translator;

class RatingField extends AbstractRepeaterField implements RepeaterFieldInterface
{
	public function render()
	{
		$field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'[type]" value="'.AbstractMetaBoxFieldModel::RATING_TYPE.'">';
		$field .= '<input type="hidden" name="'. esc_attr($this->getIdName()).'[original_name]" value="'.$this->fieldModel->getName().'">';

		$field .= '<div class="">';
		$field .= '<fieldset class="acpt-rating">';
		$field .= $this->renderRating();
		$field .= '</fieldset>';
		$field .= '</div>';

		return $this->renderField($field);
	}

	/**
	 * @return string
	 */
	private function renderRating()
	{
		$id = esc_attr($this->getIdName());
		$field = '';

		$ratings = [
			10 => '5/5',
			9 => '4.5/5',
			8 => '4/5',
			7 => '3.5/5',
			6 => '3/5',
			5 => '2.5/5',
			4 => '2/5',
			3 => '1.5/5',
			2 => '1/5',
			1 => '0.5/5',
		];

		foreach ($ratings as $value => $label){
			$checked = ($this->getDefaultValue() == $value) ? 'checked' : '';
			$class = ($value % 2 == 0) ? '' : 'half';

			$field .= '<input class="rate-input" '.$checked.' type="radio" id="'.$id.'_rating'.$value.'" name="'.esc_attr($this->getIdName()).'[value]" value="'.$value.'" /><label class="rate-label '.$class.'" for="'.$id.'_rating'.$value.'" title="'.$label.'"></label>';
		}

		return $field;
	}
}