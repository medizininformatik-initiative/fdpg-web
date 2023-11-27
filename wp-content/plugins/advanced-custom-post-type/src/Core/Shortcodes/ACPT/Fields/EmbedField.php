<?php

namespace ACPT\Core\Shortcodes\ACPT\Fields;

use ACPT\Core\Helper\Strings;

class EmbedField extends AbstractField
{
    public function render()
    {
        if(!$this->isFieldVisible()){
            return null;
        }

        $width = ($this->payload->width !== null) ? $this->payload->width : '100%';
        $height = ($this->payload->height !== null) ? $this->payload->height : 500;

	    if($this->isABlockElement()){
		    @$groupRawValue = $this->fetchMeta($this->getKey());
		    $field = Strings::toDBFormat($this->payload->field);
		    $data = $this->getBlockElementValue($groupRawValue, $field);

		    if($data !== null and isset($data['value'])){
			    return $this->addBeforeAndAfter($this->renderEmbed($width, $height, $data['value']));
		    }

		    return null;
	    }

        if($this->isAChildElement()){
            @$groupRawValue = $this->fetchMeta($this->getKey());
            $field = Strings::toDBFormat($this->payload->field);

            if(isset($groupRawValue[$field]) and isset($groupRawValue[$field][$this->payload->index])){
                $data = $groupRawValue[$field][$this->payload->index];

	            return $this->addBeforeAndAfter($this->renderEmbed($width, $height, $data['value']));
            }

            return null;
        }

	    return $this->addBeforeAndAfter($this->renderEmbed($width, $height, $this->fetchMeta($this->getKey())));
    }

	/**
	 * @param $width
	 * @param $height
	 * @param $data
	 *
	 * @return false|string
	 */
    private function renderEmbed($width, $height, $data)
    {
    	return (new \WP_Embed())->shortcode([
		    'width' => $width,
		    'height' => $height,
	    ], $data);
    }
}