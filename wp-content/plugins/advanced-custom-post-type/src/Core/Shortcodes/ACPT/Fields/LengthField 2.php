<?php

namespace ACPT\Core\Shortcodes\ACPT\Fields;

use ACPT\Core\Helper\Lengths;
use ACPT\Core\Helper\Strings;

class LengthField extends AbstractField
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
			    return $this->addBeforeAndAfter($this->renderLength($data['value'], $data['length']));
		    }

		    return null;
	    }

        if($this->isAChildElement()){
            @$groupRawValue = $this->fetchMeta($this->getKey());
            $field = Strings::toDBFormat($this->payload->field);

            if(isset($groupRawValue[$field]) and isset($groupRawValue[$field][$this->payload->index])){
                $data = $groupRawValue[$field][$this->payload->index];

                return $this->addBeforeAndAfter($this->renderLength($data['value'], $data['length']));
            }

            return null;
        }

        $length = $this->fetchMeta($this->getKey().'_length', true);

	    return $this->addBeforeAndAfter($this->renderLength($this->fetchMeta($this->getKey()), $length));
    }

	/**
	 * @param $value
	 * @param $length
	 *
	 * @return string
	 */
    private function renderLength($value, $length)
    {
    	return '<span class="amount">'.$value.'<span class="currency">'.Lengths::getList()[$length]['symbol'].'</span></span>';
    }
}