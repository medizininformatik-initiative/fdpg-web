<?php

namespace ACPT\Core\Generators\FlexibleFields;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Helper\Weights;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;

class WeightField extends AbstractFlexibleField implements FlexibleFieldInterface
{
    public function render()
    {
	    $min = $this->getAdvancedOption('min') ? $this->getAdvancedOption('min') : 1;
	    $max = $this->getAdvancedOption('max') ? $this->getAdvancedOption('max') : 999999999999999999999;
	    $step = $this->getAdvancedOption('step') ? $this->getAdvancedOption('step') : 1;

        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'[type]" value="'.AbstractMetaBoxFieldModel::WEIGHT_TYPE.'">';
        $field .= '<input type="hidden" name="'. esc_attr($this->getIdName()).'[original_name]" value="'.$this->fieldModel->getName().'">';
        $field .= '<input type="hidden" name="meta_fields[]" value="'.esc_attr($this->getIdName()).'[weight]">';
        $field .= '<div class="currency-group w-full">
                    <div class="currency-symbol">'.Weights::getList()[$this->getDefaultWeightValue()]['symbol'].'</div>
                    <input 
                        '.$this->required().' 
                        id="'.esc_attr($this->getIdName()).'[value]" 
                        name="'. esc_attr($this->getIdName()).'[value]" 
                        type="number" 
                        min="'.$min.'" 
                        max="'.$max.'" 
                        step="'.$step.'"
                        class="currency-amount" value="'.esc_attr($this->getDefaultValue()).'"
                    >
                    <div class="currency-addon">
                        <select name="'. esc_attr($this->getIdName()).'[weight]" class="currency-selector">';

        foreach (Weights::getList() as $currency => $data){
            $selected = ($currency === $this->getDefaultWeightValue()) ? 'selected' : '';
            $field .= '<option value="'.esc_attr($currency).'" data-symbol="'.esc_attr($data['symbol']).'" data-placeholder="0.00" '.$selected.'>'.esc_html($currency).'</option>';
        }

        $field .=' </select>
                          </div>
                        </div>';

        return $this->renderField($field);
    }

    /**
     * @return string
     */
    private function getDefaultWeightValue()
    {
        if(!isset($this->id)){
            return 'KILOGRAM';
        }

	    $data = $this->getParentData();
        $key = Strings::toDBFormat($this->fieldModel->getName());

        return (isset($data[$key]) and isset($data[$key][$this->elementIndex]) and isset($data[$key][$this->elementIndex]['weight']) ) ? $data[$key][$this->elementIndex]['weight'] : 'KILOGRAM';
    }
}