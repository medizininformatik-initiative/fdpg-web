<?php

namespace ACPT\Core\Shortcodes\ACPT\Fields;

use ACPT\Core\Helper\Strings;

class ToggleField extends AbstractField
{
    public function render()
    {
        if(!$this->isFieldVisible()){
            return null;
        }

        $width = ($this->payload->width !== null) ? str_replace('px', '', $this->payload->width) : 36;

        return $this->addBeforeAndAfter('<span class="acpt-toggle-placeholder" style="font-size: '.$width.'px;">'.$this->getIcon().'</span>');
    }

	/**
	 * @return string|null
	 */
    private function getIcon()
    {
	    if($this->isABlockElement()){
		    @$groupRawValue = $this->fetchMeta($this->getKey());
		    $field = Strings::toDBFormat($this->payload->field);
		    $data = $this->getBlockElementValue($groupRawValue, $field);

		    if($data !== null and isset($data['value'])){
			    return $this->renderIcon($data['value']);
		    }

		    return null;
	    }

        if($this->isAChildElement()){
            @$groupRawValue = $this->fetchMeta($this->getKey());
            $field = Strings::toDBFormat($this->payload->field);

            if(isset($groupRawValue[$field]) and isset($groupRawValue[$field][$this->payload->index])){
                $data = $groupRawValue[$field][$this->payload->index];

	            return $this->renderIcon($data['value']);
            }

            return null;
        }

        return $this->renderIcon($this->fetchMeta($this->getKey()));
    }

	/**
	 * @param $value
	 *
	 * @return string
	 */
    private function renderIcon($value)
    {
	    if($value === "1") {
		    return '<span class="iconify" data-icon="bx:bx-check-circle" style="color: #02c39a;"></span>';
	    }

	    return '<span class="iconify" data-icon="bx:bx-x-circle" style="color: #f94144;"></span>';
    }
}