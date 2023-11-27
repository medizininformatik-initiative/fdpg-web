<?php

namespace ACPT\Core\Shortcodes\ACPT\Fields;

use ACPT\Core\Helper\Strings;
use ACPT\Utils\Wordpress\WPAttachment;

class FileField extends AbstractField
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
			    $fileSrc = $data['value'];
			    $wpAttachment = WPAttachment::fromUrl($fileSrc); // @TODO ID
			    $label = (isset($data['label']) and $data['label'] !== '') ? $data['label'] : $wpAttachment->getTitle();

			    return $this->addBeforeAndAfter('<a href="'.$fileSrc.'" target="_blank">'.$label.'</a>');
		    }

		    return null;
	    }

        if($this->isAChildElement()){
            @$groupRawValue = $this->fetchMeta($this->getKey());
            $field = Strings::toDBFormat($this->payload->field);

            if(isset($groupRawValue[$field]) and isset($groupRawValue[$field][$this->payload->index])){
                $data = $groupRawValue[$field][$this->payload->index];
                $fileSrc = $data['value'];
                $wpAttachment = WPAttachment::fromUrl($fileSrc);
	            $label = (isset($data['label']) and $data['label'] !== '') ? $data['label'] : $wpAttachment->getTitle();

                return $this->addBeforeAndAfter('<a href="'.$fileSrc.'" target="_blank">'.$label.'</a>');
            }

            return null;
        }

        $fileSrc = $this->fetchMeta($this->getKey());
        $wpAttachment = WPAttachment::fromUrl($fileSrc);
	    $label = $this->fetchMeta($this->getKey().'_label') !== '' ? $this->fetchMeta($this->getKey().'_label') : $wpAttachment->getTitle();

        return $this->addBeforeAndAfter('<a href="'.$fileSrc.'" target="_blank">'.$label.'</a>');
    }
}