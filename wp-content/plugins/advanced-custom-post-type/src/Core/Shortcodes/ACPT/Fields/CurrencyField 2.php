<?php

namespace ACPT\Core\Shortcodes\ACPT\Fields;

use ACPT\Core\Helper\Currencies;
use ACPT\Core\Helper\Strings;

class CurrencyField extends AbstractField
{
    public function render()
    {
        if(!$this->isFieldVisible()){
            return null;
        }

	    if($this->isABlockElement()){
		    @$groupRawValue = $this->fetchMeta($this->getKey());
		    $field = Strings::toDBFormat($this->payload->field);
		    $data = $this->getBlockElementValue($groupRawValue, $field);

		    if($data !== null and isset($data['value'])){
			    return $this->addBeforeAndAfter('<span class="amount">'. $data['value'].'<span class="currency">'.Currencies::getList()[ $data['currency']]['symbol'].'</span></span>');
		    }

		    return null;
	    }

        if($this->isAChildElement()){
            @$groupRawValue = $this->fetchMeta($this->getKey());

            $field = Strings::toDBFormat($this->payload->field);

            if(isset($groupRawValue[$field]) and isset($groupRawValue[$field][$this->payload->index])){
                $data = $groupRawValue[$field][$this->payload->index];

                return $this->addBeforeAndAfter('<span class="amount">'. $data['value'].'<span class="currency">'.Currencies::getList()[ $data['currency']]['symbol'].'</span></span>');
            }

            return null;
        }

        $currency = $this->fetchMeta($this->getKey().'_currency');

        return $this->addBeforeAndAfter('<span class="amount">'.$this->fetchMeta($this->getKey()).'<span class="currency">'.Currencies::getList()[$currency]['symbol'].'</span></span>');

    }
}