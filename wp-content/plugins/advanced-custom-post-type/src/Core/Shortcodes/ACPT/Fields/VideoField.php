<?php

namespace ACPT\Core\Shortcodes\ACPT\Fields;

use ACPT\Core\Helper\Strings;

class VideoField extends AbstractField
{
    public function render()
    {
        if(!$this->isFieldVisible()){
            return null;
        }

        $width = ($this->payload->width !== null) ? $this->payload->width : '100%';
        $height = ($this->payload->height !== null) ? $this->payload->height : null;

	    if($this->isABlockElement()){
		    @$groupRawValue = $this->fetchMeta($this->getKey());
		    $field = Strings::toDBFormat($this->payload->field);
		    $data = $this->getBlockElementValue($groupRawValue, $field);

		    if($data !== null and isset($data['value'])){
			    return $this->addBeforeAndAfter($this->renderVideo($data['value'], $width, $height));
		    }

		    return null;
	    }

        if($this->isAChildElement()){
            @$groupRawValue = $this->fetchMeta($this->getKey());
            $field = Strings::toDBFormat($this->payload->field);

            if(isset($groupRawValue[$field]) and isset($groupRawValue[$field][$this->payload->index])){
                $data = $groupRawValue[$field][$this->payload->index];

                return $this->addBeforeAndAfter($this->renderVideo($data['value'], $width, $height));
            }

            return null;
        }

	    return $this->addBeforeAndAfter($this->renderVideo($this->fetchMeta($this->getKey()), $width, $height));
    }

	/**
	 * @param $src
	 * @param $width
	 * @param $height
	 *
	 * @return string
	 */
    private function renderVideo($src, $width, $height)
    {
    	return '<video width="'.$width.'" height="'.$height.'" controls>
                    <source src="'.$src.'" type="video/mp4">
                    Your browser does not support the video tag.
                </video>';
    }
}