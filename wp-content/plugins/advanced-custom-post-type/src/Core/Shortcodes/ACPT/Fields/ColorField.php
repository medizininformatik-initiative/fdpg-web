<?php

namespace ACPT\Core\Shortcodes\ACPT\Fields;

use ACPT\Core\Helper\Strings;

class ColorField extends AbstractField
{
    public function render()
    {
        if(!$this->isFieldVisible()){
            return null;
        }

        $width = ($this->payload->width !== null) ? str_replace('px', '', $this->payload->width) : 36;

        if($this->isAChildElement()){
            @$groupRawValue = $this->fetchMeta($this->getKey());
            $field = Strings::toDBFormat($this->payload->field);

            if(isset($groupRawValue[$field]) and isset($groupRawValue[$field][$this->payload->index])){
                $data = $groupRawValue[$field][$this->payload->index];

                return $this->addBeforeAndAfter('<span class="acpt-color-placeholder" style="width: '.$width.'px; height: '.$width.'px; background-color: '.$data['value'].'"></span>');
            }

            return null;
        }

        return $this->addBeforeAndAfter('<span class="acpt-color-placeholder" style="width: '.$width.'px; height: '.$width.'px; background-color: '.$this->fetchMeta($this->getKey()).'"></span>');
    }
}