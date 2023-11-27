<?php

namespace ACPT\Core\Shortcodes\ACPT\Fields;

use ACPT\Core\Helper\Strings;

class ListField extends AbstractField
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
			    $list = $data['value'];
		    } else {
			    $list = [];
		    }

	    } elseif($this->isAChildElement()){
            @$groupRawValue = $this->fetchMeta($this->getKey());
            $field = Strings::toDBFormat($this->payload->field);

            if(isset($groupRawValue[$field]) and isset($groupRawValue[$field][$this->payload->index])){
                $data = $groupRawValue[$field][$this->payload->index];

                $list = $data['value'];
            } else {
                $list = [];
            }

        } else {
            $list = $this->fetchMeta($this->getKey());
        }

        if(empty($list)){
        	return '';
        }

        if(empty($list[0])){
        	return '';
        }

        $return = '<ul>';

        foreach ($list as $element){
            $return .= '<li>'.$this->addBeforeAndAfter(esc_html($element)).'</li>';
        }

        $return .= '</ul>';

        return $return;
    }
}