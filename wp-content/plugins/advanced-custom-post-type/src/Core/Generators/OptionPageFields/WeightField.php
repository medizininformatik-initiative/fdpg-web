<?php

namespace ACPT\Core\Generators\OptionPageFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Helper\Weights;
use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;

class WeightField extends AbstractOptionPageField implements MetaFieldInterface
{
    public function render()
    {
        $icon = 'bx:bx-tachometer';
        $list = Weights::getList();

	    $min = $this->getAdvancedOption('min') ? $this->getAdvancedOption('min') : 1;
	    $max = $this->getAdvancedOption('max') ? $this->getAdvancedOption('max') : 999999999999999999999;
	    $step = $this->getAdvancedOption('step') ? $this->getAdvancedOption('step') : 1;

        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.OptionPageMetaBoxFieldModel::WEIGHT_TYPE.'">';
        $field .= '<div class="currency-group">
                    <div class="currency-symbol">'.$list[$this->getDefaultWeightValue()]['symbol'].'</div>
                    <input '.$this->required().' id="'.esc_attr($this->getIdName()).'" name="'. esc_attr($this->getIdName()).'" type="number" min="'.$min.'" max="'.$max.'" step="'.$step.'" class="currency-amount" value="'
                .esc_attr($this->getDefaultValue()).'">
                      <div class="currency-addon">
                        <select name="'. esc_attr($this->getIdName()).'_weight" class="currency-selector">';

        foreach ($list as $currency => $data){
            $selected = ($currency === $this->getDefaultWeightValue()) ? 'selected' : '';
            $field .= '<option value="'.esc_attr($currency).'" data-symbol="'.esc_attr($data['symbol']).'" data-placeholder="0.00" '.$selected.'>'.esc_html($currency).'</option>';
        }

        $field .=' </select>
              </div>
            </div>';

        return $this->renderField($icon, $field);
    }

    /**
     * @return string
     */
    private function getDefaultWeightValue()
    {
	    $savedValue = get_option(esc_attr($this->getIdName()).'_weight');

	    return ($savedValue !== null and $savedValue !== '' and $savedValue !== false) ? $savedValue : 'KILOGRAM';
    }
}