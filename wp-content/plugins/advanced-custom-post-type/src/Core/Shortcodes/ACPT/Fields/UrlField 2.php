<?php

namespace ACPT\Core\Shortcodes\ACPT\Fields;

use ACPT\Core\Helper\Strings;

class UrlField extends AbstractField
{
    public function render()
    {
        if(!$this->isFieldVisible()){
            return null;
        }

        $target = ($this->payload->target !== null) ? $this->payload->target : '_blank';

	    if($this->isABlockElement()){
		    @$groupRawValue = $this->fetchMeta($this->getKey());
		    $field = Strings::toDBFormat($this->payload->field);
		    $data = $this->getBlockElementValue($groupRawValue, $field);

		    if($data !== null and isset($data['value'])){
			    return $this->addBeforeAndAfter('<a href="'.$data['value'].'" target="'.$target.'">'.$data['label'].'</a>');
		    }

		    return null;
	    }

        if($this->isAChildElement()){
            @$groupRawValue = $this->fetchMeta($this->getKey());
            $field = Strings::toDBFormat($this->payload->field);

            if(isset($groupRawValue[$field]) and isset($groupRawValue[$field][$this->payload->index])){
                $data = $groupRawValue[$field][$this->payload->index];

                return $this->addBeforeAndAfter('<a href="'.$data['value'].'" target="'.$target.'">'.$data['label'].'</a>');
            }

            return null;
        }

        $label = $this->fetchMeta($this->getKey().'_label') !== '' ? $this->fetchMeta($this->getKey().'_label') : $this->fetchMeta($this->getKey());

        return $this->addBeforeAndAfter('<a href="'.$this->fetchMeta($this->getKey()).'" target="'.$target.'">'.$label.'</a>');
    }
}