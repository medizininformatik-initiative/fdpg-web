<?php

namespace ACPT\Core\Generators\OptionPageFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Helper\Currencies;
use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;

class CurrencyField extends AbstractOptionPageField implements MetaFieldInterface
{
    public function render()
    {
	    $min = $this->getAdvancedOption('min') ? $this->getAdvancedOption('min') : 0.01;
	    $max = $this->getAdvancedOption('max') ? $this->getAdvancedOption('max') : 999999999999999999999;
	    $step = $this->getAdvancedOption('step') ? $this->getAdvancedOption('step') : 0.01;

        $icon = 'bx:bx-euro';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.OptionPageMetaBoxFieldModel::CURRENCY_TYPE.'">';
        $field .= '<div class="currency-group">
                    <div class="currency-symbol">'.Currencies::getList()[$this->getDefaultCurrencyValue()]['symbol'].'</div>
                    <input '.$this->required().' id="'.esc_attr($this->getIdName()).'" name="'. esc_attr($this->getIdName()).'" type="number" min="'.$min.'" max="'.$max.'" step="'.$step.'" class="currency-amount" value="'.esc_attr($this->getDefaultValue()).'">
                      <div class="currency-addon">
                        <select name="'. esc_attr($this->getIdName()).'_currency" class="currency-selector">';

        foreach (Currencies::getList() as $currency => $data){
            $selected = ($currency === $this->getDefaultCurrencyValue()) ? 'selected' : '';
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
    private function getDefaultCurrencyValue()
    {
    	$savedValue = get_option(esc_attr($this->getIdName()).'_currency');

        return ($savedValue !== null and $savedValue !== '' and $savedValue !== false) ? $savedValue : 'USD';
    }
}