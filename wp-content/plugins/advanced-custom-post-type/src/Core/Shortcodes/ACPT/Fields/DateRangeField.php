<?php

namespace ACPT\Core\Shortcodes\ACPT\Fields;

use ACPT\Core\Helper\Strings;

class DateRangeField extends AbstractField
{
    public function render()
    {
        if(!$this->isFieldVisible()){
            return null;
        }

        $dateFormat = $this->payload->dateFormat ? $this->payload->dateFormat : 'd/m/Y';
        $rawValue = $this->fetchMeta($this->getKey());
        $rawValue = explode(" - ", $rawValue);
        $dateStart = new \DateTime($rawValue[0]);
        $dateEnd = new \DateTime($rawValue[1]);

	    if($this->isABlockElement()){
		    @$groupRawValue = $this->fetchMeta($this->getKey());
		    $field = Strings::toDBFormat($this->payload->field);
		    $data = $this->getBlockElementValue($groupRawValue, $field);

		    if($data !== null and isset($data['value'])){
			    $data = explode(" - ", $data['value']);

			    return $this->addBeforeAndAfter($dateStart->format($data[0])) . ' - '. $this->addBeforeAndAfter($dateEnd->format($data[0]));
		    }

		    return null;
	    }

        if($this->isAChildElement()){
            @$groupRawValue = $this->fetchMeta($this->getKey());
            $field = Strings::toDBFormat($this->payload->field);

            if(isset($groupRawValue[$field]) and isset($groupRawValue[$field][$this->payload->index])){
                $data = $groupRawValue[$field][$this->payload->index];
	            $data = explode(" - ", $data['value']);

                return $this->addBeforeAndAfter($dateStart->format($data[0])) . ' - '. $this->addBeforeAndAfter($dateEnd->format($data[0]));
            }

            return null;
        }

        return $this->addBeforeAndAfter($dateStart->format($dateFormat)) . ' - '. $this->addBeforeAndAfter($dateEnd->format($dateFormat));
    }
}