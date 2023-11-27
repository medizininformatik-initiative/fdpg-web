<?php

namespace ACPT\Utils\Data;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Core\Models\User\UserMetaBoxFieldModel;

class Sanitizer
{
	/**
	 * Sanitize post type data before saving
	 *
	 * @param $type
	 * @param $rawData
	 *
	 * @return mixed
	 */
	public static function sanitizeRawData($type, $rawData)
	{
		switch ($type){

			case AbstractMetaBoxFieldModel::ICON_TYPE:
				return Sanitizer::sanitizeSVG($rawData);

			case AbstractMetaBoxFieldModel::EMAIL_TYPE:
				return sanitize_email($rawData);

			case AbstractMetaBoxFieldModel::URL_TYPE:
				return esc_url_raw($rawData);

			case AbstractMetaBoxFieldModel::TEXTAREA_TYPE:
				return sanitize_textarea_field($rawData);

			case AbstractMetaBoxFieldModel::EDITOR_TYPE:
			case AbstractMetaBoxFieldModel::HTML_TYPE:
				return wp_kses_post($rawData);

			case is_array($rawData):
			case AbstractMetaBoxFieldModel::GALLERY_TYPE:
			case AbstractMetaBoxFieldModel::CHECKBOX_TYPE:
			case AbstractMetaBoxFieldModel::SELECT_MULTI_TYPE:
			case AbstractMetaBoxFieldModel::LIST_TYPE:
			case UserMetaBoxFieldModel::USER_MULTI_TYPE:
				return Sanitizer::recursiveSanitizeRawData($rawData);
				break;

			default:
				return sanitize_text_field($rawData);
		}
	}

    /**
     * @param $array
     *
     * @return mixed
     */
    public static function recursiveSanitizeRawData($array)
    {
    	if(is_string($array)){
    		return sanitize_text_field($array);
	    }

        foreach ( $array as $key => &$value ) {
            if ( is_array( $value ) ) {
                $value = self::recursiveSanitizeRawData($value);
            } elseif(Strings::contains('</svg>', $value) or Strings::contains('&lt;/svg&gt;', $value)){
	            $value = self::sanitizeSVG($value);
            } elseif(\is_string($value)){
                $value = self::sanitizeHTML($value);
                $value = self::rebuildPHP($value);
            } elseif(\is_bool($value)) {
                $value = (bool)( $value );
            } elseif (\is_null($value)){
                $value = null;
            }
        }

        return $array;
    }

	/**
	 * @param $svg
	 *
	 * @return mixed
	 */
    private static function sanitizeSVG($svg)
    {
    	return self::escapeField($svg);
    }

    /**
     * @param $data
     *
     * @return string
     */
    private static function sanitizeHTML($data)
    {
        // Fix &entity\n;
        $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

        do {
            // Remove really unwanted tags
            $old_data = $data;
            $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        }

        while ($old_data !== $data);

        return $data;
    }

    /**
     * @param $value
     *
     * @return string|string[]
     */
    private static function rebuildPHP($value)
    {
        preg_match_all('/&lt;\?php(.*?)\?&gt;/iu', $value, $phpMatches);

        if(empty($phpMatches[0])){
            return $value;
        }

        foreach ($phpMatches[0] as $match){
            $value = str_replace($match, str_replace(['&lt;','&gt;'], ['<','>'], $match), $value);
        }

        return $value;
    }

    /**
     * @param $field
     *
     * @return mixed
     */
    public static function escapeField($field)
    {
        $allowedTags = [
        	'h1' => [],
        	'h2' => [],
        	'h3' => [],
        	'h4' => [],
        	'h5' => [],
        	'h6' => [],
            'a' => [
                'class' => [],
                'id' => [],
                'href' => [],
                'title' => [],
                'style' => [],
                'data-index' => [],
                'data-group-id' => [],
                'data-target-id' => [],
                'data-media-type' => [],
	            'data-element' => [],
	            'data-elements' => [],
	            'data-block-id' => [],
            ],
            'br' => [],
            'img' => [
                'class' => [],
                'id' => [],
                'src' => [],
                'alt' => [],
                'title' => [],
            ],
            'iframe' => [
                'title' => [],
                'width' => [],
                'height' => [],
                'src' => [],
                'frameborder' => [],
                'allow' => [],
                'allowfullscreen' => [],
            ],
            'strong' => [],
            'div' => [
                'data-prefix' => [],
                'data-value' => [],
                'data-target' => [],
                'data-target-copy' => [],
                'data-parent-id' => [],
                'data-target-id' => [],
                'data-index' => [],
                'style' => [],
                'class' => [],
                'id' => [],
                'draggable' => [],
            ],
	        'datalist' => [
		        'class' => [],
		        'id' => [],
	        ],
	        'fieldset' => [
		        'class' => [],
		        'id' => [],
	        ],
            'label' => [
                'for' => [],
                'title' => [],
                'style' => [],
                'type' => [],
                'name' => [],
                'class' => [],
                'id' => []
            ],
            'input' => [
            	'list' => [],
	            'data-target-id' => [],
	            'data-min-date' => [],
	            'data-max-date' => [],
	            'data-index' => [],
                'checked' => [],
                'required' => [],
                'value' => [],
                'readonly' => [],
                'style' => [],
                'type' => [],
                'name' => [],
                'class' => [],
                'id' => [],
	            'minlength' => [],
	            'maxlength' => [],
	            'min' => [],
	            'max' => [],
	            'step' => [],
	            'pattern' => [],
	            'placeholder' => [],
            ],
            'select' => [
                'required' => [],
                'readonly' => [],
                'style' => [],
                'multiple' => [],
                'type' => [],
                'name' => [],
                'class' => [],
                'id' => []
            ],
            'textarea' => [
                'required' => [],
                'rows' => [],
                'cols' => [],
                'readonly' => [],
                'style' => [],
                'multiple' => [],
                'type' => [],
                'name' => [],
                'class' => [],
                'id' => []
            ],
            'option' => [
                'selected' => [],
                'value' => [],
                'label' => [],
                'data-symbol' => [],
                'data-placeholder' => [],
            ],
            'button' => [
                'disabled' => [],
                'value' => [],
                'data-target-id' => [],
                'data-min-blocks' => [],
                'data-max-blocks' => [],
                'readonly' => [],
                'style' => [],
                'multiple' => [],
                'type' => [],
                'name' => [],
                'class' => [],
                'id' => []
            ],
            'video' => [
                'controls' => [],
                'width' => [],
                'height' => [],
                'muted' => [],
            ],
            'source' => [
                'src' => [],
                'type' => [],
            ],
            'ul' => [
                'style' => [],
                'class' => [],
                'id' => [],
                'data-min-blocks' => [],
                'data-max-blocks' => [],
            ],
            'li' => [
                'style' => [],
                'class' => [],
                'id' => [],
	            'draggable' => [],
	            'data-value' => [],
                'data-field-id' => [],
                'data-media-type' => [],
            ],
	        'p' => [
		        'class' => [],
		        'id' => [],
	        	'data-message-id' => [],
	        ],
            'span' => [
                    'style' => [],
                    'class' => [],
                    'id' => []
            ],
            'svg' => [
               'xmlns' => [],
               'aria-hidden' => [],
               'role' => [],
               'class' => [],
               'width' => [],
               'height' => [],
               'preserveAspectRatio' => [],
               'viewbox' => [],
               'data-width' => [],
               'data-height' => [],
               'data-icon' => [],
               'style' => [],
               'clip-rule' => [],
            ],
	        'g' => [
		        'fill' => [],
	        ],
            'path' => [
                'd' => [],
                'fill' => [],
                'opacity' => [],
                'fill-rule' => [],
                'clip-rule' => [],
            ],
	        'circle' => [
	            'cx' => [],
	            'cy' => [],
	            'r' => [],
	            'fill' => [],
	        ],
	        'ellipse' => [
		        'cx' => [],
		        'cy' => [],
		        'rx' => [],
		        'ry' => [],
		        'fill' => [],
	        ],
        ];

        return wp_kses($field, $allowedTags);
    }
}