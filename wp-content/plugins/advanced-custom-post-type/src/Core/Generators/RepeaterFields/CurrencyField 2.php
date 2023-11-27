<?php

namespace ACPT\Core\Generators\RepeaterFields;

use ACPT\Core\Helper\Currencies;
use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;

class CurrencyField extends AbstractRepeaterField implements RepeaterFieldInterface
{
    public function render()
    {
	    $min = $this->getAdvancedOption('min') ? $this->getAdvancedOption('min') : 0.01;
	    $max = $this->getAdvancedOption('max') ? $this->getAdvancedOption('max') : 999999999999999999999;
	    $step = $this->getAdvancedOption('step') ? $this->getAdvancedOption('step') : 0.01;

        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'[type]" value="'.CustomPostTypeMetaBoxFieldModel::CURRENCY_TYPE.'">';
        $field .= '<input type="hidden" name="'. esc_attr($this->getIdName()).'[original_name]" value="'.$this->fieldModel->getName().'">';
        $field .= '<input type="hidden" name="meta_fields[]" value="'.esc_attr($this->getIdName()).'[currency]">';
        $field .= '<div class="currency-group w-full">
                    <div class="currency-symbol">'.Currencies::getList()[$this->getDefaultCurrencyValue()]['symbol'].'</div>
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
                        <select name="'. esc_attr($this->getIdName()).'[currency]" class="currency-selector">';

                        foreach (Currencies::getList() as $currency => $data){
                            $selected = ($currency === $this->getDefaultCurrencyValue()) ? 'selected' : '';
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
    private function getDefaultCurrencyValue()
    {
        if(!isset($this->id)){
            return 'USD';
        }

	    $data = $this->getParentData();
        $key = Strings::toDBFormat($this->fieldModel->getName());

        return (isset($data[$key]) and isset($data[$key][$this->index]) and isset($data[$key][$this->index]['currency']) ) ? $data[$key][$this->index]['currency'] : 'USD';
    }
}
