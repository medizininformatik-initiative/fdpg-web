<?php

namespace ACPT\Core\Shortcodes\ACPT\Fields;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Helper\Weights;

class WeightField extends AbstractField
{
    public function render()
    {
        if(!$this->isFieldVisible()){
            return null;
        }

        if($this->isAChildElement()){
            @$groupRawValue = $this->fetchMeta($this->getKey());
            $field = Strings::toDBFormat($this->payload->field);

            if(isset($groupRawValue[$field]) and isset($groupRawValue[$field][$this->payload->index])){
                $data = $groupRawValue[$field][$this->payload->index];

	            return $this->addBeforeAndAfter($this->renderWeight($data['value'], $data['weight']));
            }

            return null;
        }

        $weight = $this->fetchMeta( $this->getKey().'_weight');

        return $this->addBeforeAndAfter($this->renderWeight($this->fetchMeta($this->getKey()), $weight));
    }

	/**
	 * @param $value
	 * @param $weight
	 *
	 * @return string
	 */
	private function renderWeight($value, $weight)
	{
		return '<span class="amount">'.$value.'<span class="currency">'.Weights::getList()[$weight]['symbol'].'</span></span>';
	}
}