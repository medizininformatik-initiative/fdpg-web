<?php

namespace ACPT\Core\Shortcodes\ACPT\Fields;

use ACPT\Core\Helper\Strings;
use ACPT\Utils\Wordpress\WPAttachment;

class ImageField extends AbstractField
{
    public function render()
    {
        if(!$this->isFieldVisible()){
            return null;
        }

        $width = $this->calculateImgWidth();
        $height = ($this->payload->height !== null) ? $this->payload->height : null;

	    if($this->isABlockElement()){
		    @$groupRawValue = $this->fetchMeta($this->getKey());
		    $field = Strings::toDBFormat($this->payload->field);
		    $data = $this->getBlockElementValue($groupRawValue, $field);

		    if($data !== null and isset($data['value'])){
			    return $this->addBeforeAndAfter($this->renderImage($data['value'], $width, $height));
		    }

		    return null;
	    }

        if($this->isAChildElement()){
            @$groupRawValue = $this->fetchMeta($this->getKey());
            $field = Strings::toDBFormat($this->payload->field);

            if(isset($groupRawValue[$field]) and isset($groupRawValue[$field][$this->payload->index])){
                $data = $groupRawValue[$field][$this->payload->index];

                return $this->addBeforeAndAfter($this->renderImage($data['value'], $width, $height));
            }

            return null;
        }

        $imageSrc = $this->fetchMeta($this->getKey());

        return $this->addBeforeAndAfter($this->renderImage($imageSrc,  $width, $height));
    }

	/**
	 * @return int|string|null
	 */
    private function calculateImgWidth()
    {
		if($this->payload->preview){
			return 80;
		}

	    return ($this->payload->width !== null) ? $this->payload->width : '100%';
    }

	/**
	 * @param $src
	 * @param $width
	 * @param $height
	 *
	 * @return string
	 */
    private function renderImage($src, $width, $height)
    {
	    $wpAttachment = WPAttachment::fromUrl($src);

	    return $this->addBeforeAndAfter('<img src="'.$src.'" width="'.esc_attr($width).'" height="'.esc_attr($height).'" title="'.$wpAttachment->getTitle().'" alt="'.$wpAttachment->getAlt().'" />');
    }
}