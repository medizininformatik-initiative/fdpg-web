<?php

namespace ACPT\Core\Shortcodes\ACPT;

use ACPT\Core\Shortcodes\ACPT\DTO\ShortcodePayload;
use ACPT\Core\Shortcodes\ACPT\Fields\AbstractField;

abstract class AbstractACPTShortcode
{
    /**
     * @param string $type
     * @param ShortcodePayload $payload
     * @return null|AbstractField
     */
    protected function getField($type, ShortcodePayload $payload)
    {
        $className = 'ACPT\\Core\\Shortcodes\\ACPT\\Fields\\'.$type.'Field';

        if(class_exists($className)){
	        return new $className($payload);
        }

        return null;
    }

	/**
	 * Render the shortcode
	 *
	 * @param $atts
	 *
	 * @return mixed
	 */
	public abstract function render($atts);
}