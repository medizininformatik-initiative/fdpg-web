<?php

namespace ACPT\Core\Generators\TaxonomyFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\Taxonomy\TaxonomyMetaBoxFieldModel;

class DateRangeField extends AbstractTaxonomyField implements MetaFieldInterface
{
	public function render()
	{
		$this->enqueueAssets();

		$icon = 'bx:bx-calendar-check';
		$field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.TaxonomyMetaBoxFieldModel::DATE_RANGE_TYPE.'">';
		$field .= '<input '.$this->required().' id="'.esc_attr($this->getIdName()).'" name="'. esc_attr($this->getIdName()).'" type="text" class="acpt-daterangepicker acpt-admin-meta-field-input" value="'.$this->getDefaultValue().'"';

		$min = $this->getAdvancedOption('min');
		$max = $this->getAdvancedOption('max');

		if($min){
			$field .= ' data-min-date="'.$min.'"';
		}

		if($max){
			$field .= ' data-max-date="'.$max.'"';
		}

		$field .= '>';

		echo $this->renderField($icon, $field);
	}

	private function enqueueAssets()
	{
		wp_enqueue_script( 'momentjs', plugin_dir_url( dirname( __FILE__ ) ) . '../../../assets/vendor/moment/moment.min.js', [], '2.18.1', true);
		wp_enqueue_script( 'daterangepicker-js', plugin_dir_url( dirname( __FILE__ ) ) . '../../../assets/vendor/daterangepicker/js/daterangepicker.min.js', [], '3.1.0', true);
		wp_enqueue_style( 'daterangepicker-css', plugin_dir_url( dirname( __FILE__ ) ) . '../../../assets/vendor/daterangepicker/css/daterangepicker.min.css', [], '3.1.0', 'all');
		wp_enqueue_script( 'custom-daterangepicker-js', plugin_dir_url( dirname( __FILE__ ) ) . '../../../assets/static/js/daterangepicker.js', [], '1.0.0', true);
	}
}