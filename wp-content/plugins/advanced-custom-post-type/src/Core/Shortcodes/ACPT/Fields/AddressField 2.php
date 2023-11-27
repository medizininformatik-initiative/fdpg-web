<?php

namespace ACPT\Core\Shortcodes\ACPT\Fields;

use ACPT\Core\Helper\Strings;

class AddressField extends AbstractField
{
    public function render()
    {
        if(!$this->isFieldVisible()){
            return null;
        }

        $width = ($this->payload->width !== null) ? $this->payload->width : '100%';
        $height = ($this->payload->height !== null) ? $this->payload->height : 500;
        $z = 16;

	    if($this->isABlockElement()){
		    @$groupRawValue = $this->fetchMeta($this->getKey());
		    $field = Strings::toDBFormat($this->payload->field);
		    $data = $this->getBlockElementValue($groupRawValue, $field);

		    if($data !== null and isset($data['value'])){
			    return $this->addBeforeAndAfter($this->renderMap($width, $height, $data['value'], $z));
		    }

		    return null;
	    }

        if($this->isAChildElement()){
            @$groupRawValue = $this->fetchMeta($this->getKey());
            $field = Strings::toDBFormat($this->payload->field);

            if(isset($groupRawValue[$field]) and isset($groupRawValue[$field][$this->payload->index])){
                $data = $groupRawValue[$field][$this->payload->index];

                return $this->addBeforeAndAfter($this->renderMap($width, $height, $data['value'], $z));
            }

            return null;
        }

        $address = $this->fetchMeta($this->getKey());

        return $this->addBeforeAndAfter($this->renderMap($width, $height, $address, $z));
    }

	/**
	 * @param $width
	 * @param $height
	 * @param $value
	 * @param $z
	 *
	 * @return string
	 */
    private function renderMap($width, $height, $value, $z)
    {
    	return '<iframe class="maps" width="'.esc_attr($width).'" height="'.esc_attr($height).'" src="https://maps.google.com/maps?q='.$value.'&z='.$z.'&ie=UTF8&output=embed" frameborder="0" allowfullscreen></iframe>';
    }
}