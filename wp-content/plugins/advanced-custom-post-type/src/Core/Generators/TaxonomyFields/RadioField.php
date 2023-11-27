<?php

namespace ACPT\Core\Generators\TaxonomyFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\Taxonomy\TaxonomyMetaBoxFieldModel;
use ACPT\Utils\Wordpress\Translator;

class RadioField extends AbstractTaxonomyField implements MetaFieldInterface
{
	public function render()
	{
		$icon = 'bx:radio-circle-marked';
		$field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.TaxonomyMetaBoxFieldModel::RADIO_TYPE.'">';
		$field .= '<div class="acpt_checkboxes">';
		$selected = ('' === $this->getDefaultValue()) ? 'checked="checked"' : '';

		if(empty($this->getAdvancedOption('hide_blank_radio'))){
			$field .= '<div class="item"><input name="'.esc_attr($this->getIdName()).'" id="'.esc_attr($this->getIdName()).'_blank" type="radio" '.$selected.' value="" /><label for="'.esc_attr($this->getIdName()).'_blank">'.Translator::translate('No choice').'</label></div>';
		}

		foreach ($this->metaBoxFieldModel->getOptions() as $index => $option){
			$id = esc_attr($this->getIdName()).'_'.$index;
			$selected = ($option['value'] === $this->getDefaultValue()) ? 'checked="checked"' : '';
			$field .= '<div class="item"><input name="'.esc_attr($this->getIdName()).'" id="'.$id.'" type="radio" '.$selected.' value="'.esc_attr($option['value']).'" /><label for="'.$id.'">'. esc_html($option['label']) . '</label></div>';
		}

		$field .= '</div>';

		echo $this->renderField($icon, $field);
	}
}
