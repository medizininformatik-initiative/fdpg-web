<?php

namespace ACPT\Core\Generators\RepeaterFields;

use ACPT\Core\Helper\Lengths;
use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;

class LengthField extends AbstractRepeaterField implements RepeaterFieldInterface
{
    public function render()
    {
	    $min = $this->getAdvancedOption('min') ? $this->getAdvancedOption('min') : 1;
	    $max = $this->getAdvancedOption('max') ? $this->getAdvancedOption('max') : 999999999999999999999;
	    $step = $this->getAdvancedOption('step') ? $this->getAdvancedOption('step') : 1;

        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'[type]" value="'.CustomPostTypeMetaBoxFieldModel::LENGTH_TYPE.'">';
        $field .= '<input type="hidden" name="'. esc_attr($this->getIdName()).'[original_name]" value="'.$this->fieldModel->getName().'">';
        $field .= '<input type="hidden" name="meta_fields[]" value="'.esc_attr($this->getIdName()).'[length]">';
        $field .= '<div class="currency-group w-full">
                    <div class="currency-symbol">'.Lengths::getList()[$this->getDefaultLengthValue()]['symbol'].'</div>
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
                        <select name="'. esc_attr($this->getIdName()).'[length]" class="currency-selector">';

        foreach (Lengths::getList() as $currency => $data){
            $selected = ($currency === $this->getDefaultLengthValue()) ? 'selected' : '';
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
    private function getDefaultLengthValue()
    {
        if(!isset($this->id)){
            return 'METER';
        }

	    $data = $this->getParentData();
        $key = Strings::toDBFormat($this->fieldModel->getName());

        return (isset($data[$key]) and isset($data[$key][$this->index]) and isset($data[$key][$this->index]['length']) ) ? $data[$key][$this->index]['length'] : 'METER';
    }
}