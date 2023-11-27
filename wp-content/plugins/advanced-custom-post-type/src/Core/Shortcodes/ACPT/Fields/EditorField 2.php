<?php

namespace ACPT\Core\Shortcodes\ACPT\Fields;

use ACPT\Core\Helper\Strings;

class EditorField extends AbstractField
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
			    return $this->renderContent($data['value']);
		    }

		    return null;
	    }

        if($this->isAChildElement()){
            @$groupRawValue = $this->fetchMeta($this->getKey());
            $field = Strings::toDBFormat($this->payload->field);

            if(isset($groupRawValue[$field]) and isset($groupRawValue[$field][$this->payload->index])){
                $data = $groupRawValue[$field][$this->payload->index];

                return $this->renderContent($data['value']);
            }

            return null;
        }

        return $this->renderContent($this->fetchMeta($this->getKey()));
    }

	/**
	 * @param $content
	 *
	 * @return string
	 */
    private function renderContent($content)
    {
	    $content = do_shortcode($content);

	    $replacementMap = [
		    '<p>['    => '[',
		    ']</p>'   => ']',
		    ']<br />' => ']'
	    ];

	    $content = strtr( $content, $replacementMap );

	    return $this->addBeforeAndAfter(wpautop($content));
    }
}