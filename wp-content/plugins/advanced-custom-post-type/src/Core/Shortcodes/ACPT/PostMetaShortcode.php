<?php

namespace ACPT\Core\Shortcodes\ACPT;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Shortcodes\ACPT\DTO\ShortcodePayload;
use ACPT\Costants\MetaTypes;

class PostMetaShortcode extends AbstractACPTShortcode
{
    /**
     * @param array $atts
     *
     * @return string
     * @throws \Exception
     */
    public function render($atts)
    {
        global $post;

        if(!isset($atts['box']) or !isset($atts['field'])){
            return '';
        }

        if(!isset($atts['pid']) and $post === null){
            return '';
        }

        $pid = isset($atts['pid']) ? $atts['pid'] : $post->ID;

        if($post === null){
            $post = get_post((int)$pid);
        }

        if($post === null){
            return '';
        }

        $postType = $post->post_type;
        $box = $atts['box'];
        $field = $atts['field'];
        $blockName = isset ($atts['block_name'] ) ? $atts['block_name'] : null;
        $blockIndex = isset ($atts['block_index'] ) ? $atts['block_index'] : null;
        $width = isset ($atts['width'] ) ? $atts['width'] : null;
        $height = isset ($atts['height'] ) ? $atts['height'] : null;
        $target = isset ($atts['target'] ) ? $atts['target'] : null;
        $dateFormat = isset ($atts['date-format'] ) ? $atts['date-format'] : null;
        $elements = isset ($atts['elements'] ) ? $atts['elements'] : null;
        $preview = (isset($atts['preview']) and $atts['preview'] === 'true') ? true : false;
        $parent = (isset($atts['parent'])) ? $atts['parent'] : null;
        $index = (isset($atts['index'])) ? $atts['index'] : null;
        $render = (isset($atts['render'])) ? $atts['render'] : null;

        if($parent){

            $key = Strings::toDBFormat($box).'_'.Strings::toDBFormat($parent);
            @$groupRawValue = get_post_meta($pid, $key, true);

            if($groupRawValue === null or $groupRawValue === ''){
                return '';
            }

            if($index !== null and isset($groupRawValue[Strings::toDBFormat($field)][$index])){
	            $data = $groupRawValue[Strings::toDBFormat($field)][$index];
	            $type = $data['type'];
            }

            if($blockName !== null and $blockIndex !== null){
            	if(isset($groupRawValue['blocks']) and
	               isset($groupRawValue['blocks'][$blockIndex]) and
	               isset($groupRawValue['blocks'][$blockIndex][$blockName]) and
	               isset($groupRawValue['blocks'][$blockIndex][$blockName][Strings::toDBFormat($field)]) and
	               isset($groupRawValue['blocks'][$blockIndex][$blockName][Strings::toDBFormat($field)][$index])
	            ){
		            $data = $groupRawValue['blocks'][$blockIndex][$blockName][Strings::toDBFormat($field)][$index];
		            $type = $data['type'];
	            }
            }

        } else {
            $key = Strings::toDBFormat($box).'_'.Strings::toDBFormat($field);
            $type = get_post_meta($pid, $key.'_type', true);
            $data = get_post_meta($pid, $key, true);

            if($data === null or $data === ''){
                return '';
            }
        }

        if(!empty($type)){

            $payload = new ShortcodePayload();
            $payload->id = $pid;
            $payload->box = $box;
            $payload->field = $field;
            $payload->belongsTo = MetaTypes::CUSTOM_POST_TYPE;
            $payload->find = $postType;
            $payload->width = $width;
            $payload->height = $height;
            $payload->target = $target;
            $payload->dateFormat = $dateFormat;
            $payload->elements = $elements;
            $payload->preview = $preview;
            $payload->parent = $parent;
            $payload->index = $index;
            $payload->blockIndex = $blockIndex;
            $payload->blockName = $blockName;
            $payload->render = $render;

            $field = self::getField($type, $payload);

	        if($field){
		        return $field->render();
	        }

	        return null;
        }

        return null;
    }
}
