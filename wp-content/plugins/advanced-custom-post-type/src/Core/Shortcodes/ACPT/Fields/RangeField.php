<?php

namespace ACPT\Core\Shortcodes\ACPT\Fields;

use ACPT\Core\Helper\Strings;

class RangeField extends AbstractField
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
			    $value = Strings::convertStringToNumber($data['value']);

			    return $this->addBeforeAndAfter($value);
		    }

		    return null;
	    }

        if($this->isAChildElement()){
            @$groupRawValue = $this->fetchMeta($this->getKey());
            $field = Strings::toDBFormat($this->payload->field);

            if(isset($groupRawValue[$field]) and isset($groupRawValue[$field][$this->payload->index])){
                $data = $groupRawValue[$field][$this->payload->index];
	            $value = Strings::convertStringToNumber($data['value']);

	            return $this->addBeforeAndAfter($value);
            }

            return null;
        }

	    $value = $this->fetchMeta($this->getKey());
	    $value = Strings::convertStringToNumber($value);

        return $this->addBeforeAndAfter($value);
    }
}