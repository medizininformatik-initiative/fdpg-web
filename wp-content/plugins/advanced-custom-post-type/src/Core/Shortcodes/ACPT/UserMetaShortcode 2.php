<?php

namespace ACPT\Core\Shortcodes\ACPT;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Shortcodes\ACPT\DTO\ShortcodePayload;
use ACPT\Costants\MetaTypes;

class UserMetaShortcode extends AbstractACPTShortcode
{
    public function render($atts)
    {
        $uid = $atts['uid'];
        $box = $atts['box'];
        $field = $atts['field'];

        if(!$uid or !$box or !$field){
            return '';
        }

	    $uid = isset($atts['uid']) ? $atts['uid'] : get_current_user_id();

	    if(!$uid){
		    return '';
	    }

        $key = Strings::toDBFormat($box).'_'.Strings::toDBFormat($field);
        $type = get_user_meta($uid, $key.'_type', true);

        if(!$type){
            return '';
        }

        $width = isset ($atts['width'] ) ? $atts['width'] : null;
        $height = isset ($atts['height'] ) ? $atts['height'] : null;
        $target = isset ($atts['target'] ) ? $atts['target'] : null;
        $dateFormat = isset ($atts['date-format'] ) ? $atts['date-format'] : null;
        $elements = isset ($atts['elements'] ) ? $atts['elements'] : null;
        $preview = (isset($atts['preview']) and $atts['preview'] === 'true') ? true : false;
        $parent = (isset($atts['parent'])) ? $atts['parent'] : null;
        $index = (isset($atts['index'])) ? $atts['index'] : null;

        if(!empty($type)){

            $payload = new ShortcodePayload();
            $payload->id = $uid;
            $payload->box = $box;
            $payload->field = $field;
            $payload->belongsTo = MetaTypes::USER;
            $payload->width = $width;
            $payload->height = $height;
            $payload->target = $target;
            $payload->dateFormat = $dateFormat;
            $payload->elements = $elements;
            $payload->preview = $preview;
            $payload->parent = $parent;
            $payload->index = $index;

            $field = self::getField($type, $payload);

	        if($field){
		        return $field->render();
	        }

	        return null;
        }

        return null;
    }
}