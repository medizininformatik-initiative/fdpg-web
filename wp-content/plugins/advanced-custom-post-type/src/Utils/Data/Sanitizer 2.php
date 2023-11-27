<?php

namespace ACPT\Utils\Data;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;
use ACPT\Core\Models\Taxonomy\TaxonomyMetaBoxFieldModel;
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
    public static function sanitizePostTypeRawData($type, $rawData)
    {
        switch ($type){

	        case CustomPostTypeMetaBoxFieldModel::ICON_TYPE:
	        	return Sanitizer::sanitizeSVG($rawData);

            case CustomPostTypeMetaBoxFieldModel::EMAIL_TYPE:
                return sanitize_email($rawData);

            case CustomPostTypeMetaBoxFieldModel::URL_TYPE:
                return esc_url_raw($rawData);

            case CustomPostTypeMetaBoxFieldModel::TEXTAREA_TYPE:
                return sanitize_textarea_field($rawData);

            case CustomPostTypeMetaBoxFieldModel::EDITOR_TYPE:
            case CustomPostTypeMetaBoxFieldModel::HTML_TYPE:
                return wp_kses_post($rawData);

            case is_array($rawData):
            case CustomPostTypeMetaBoxFieldModel::GALLERY_TYPE:
            case CustomPostTypeMetaBoxFieldModel::CHECKBOX_TYPE:
            case CustomPostTypeMetaBoxFieldModel::SELECT_MULTI_TYPE:
            case CustomPostTypeMetaBoxFieldModel::LIST_TYPE:
                return Sanitizer::recursiveSanitizeRawData($rawData);
                break;

            default:
                return sanitize_text_field($rawData);
        }
    }

	/**
	 * Sanitize option page data before saving
	 *
	 * @param $type
	 * @param $rawData
	 *
	 * @return mixed
	 */
	public static function sanitizeOptionPageRawData($type, $rawData)
	{
		switch ($type){

			case OptionPageMetaBoxFieldModel::ICON_TYPE:
				return Sanitizer::sanitizeSVG($rawData);

			case OptionPageMetaBoxFieldModel::EMAIL_TYPE:
				return sanitize_email($rawData);

			case OptionPageMetaBoxFieldModel::URL_TYPE:
				return esc_url_raw($rawData);

			case OptionPageMetaBoxFieldModel::TEXTAREA_TYPE:
				return sanitize_textarea_field($rawData);

			case OptionPageMetaBoxFieldModel::EDITOR_TYPE:
			case OptionPageMetaBoxFieldModel::HTML_TYPE:
				return wp_kses_post($rawData);

			case is_array($rawData):
			case OptionPageMetaBoxFieldModel::GALLERY_TYPE:
			case OptionPageMetaBoxFieldModel::CHECKBOX_TYPE:
			case OptionPageMetaBoxFieldModel::SELECT_MULTI_TYPE:
			case OptionPageMetaBoxFieldModel::LIST_TYPE:
				return Sanitizer::recursiveSanitizeRawData($rawData);
				break;

			default:
				return sanitize_text_field($rawData);
		}
	}

    /**
     * Sanitize taxonomy data before saving
     *
     * @param $type
     * @param $rawData
     *
     * @return mixed
     */
    public static function sanitizeTaxonomyRawData($type, $rawData)
    {
        switch ($type){

	        case TaxonomyMetaBoxFieldModel::ICON_TYPE:
		        return Sanitizer::sanitizeSVG($rawData);

            case TaxonomyMetaBoxFieldModel::EMAIL_TYPE:
                return sanitize_email($rawData);

            case TaxonomyMetaBoxFieldModel::URL_TYPE:
                return esc_url_raw($rawData);

            case TaxonomyMetaBoxFieldModel::TEXTAREA_TYPE:
                return sanitize_textarea_field($rawData);

            case TaxonomyMetaBoxFieldModel::EDITOR_TYPE:
            case TaxonomyMetaBoxFieldModel::HTML_TYPE:
                return wp_kses_post($rawData);

            case is_array($rawData):
            case TaxonomyMetaBoxFieldModel::GALLERY_TYPE:
            case TaxonomyMetaBoxFieldModel::CHECKBOX_TYPE:
            case TaxonomyMetaBoxFieldModel::SELECT_MULTI_TYPE:
            case TaxonomyMetaBoxFieldModel::LIST_TYPE:
                return Sanitizer::recursiveSanitizeRawData($rawData);
                break;

            default:
                return sanitize_text_field($rawData);
        }
    }

    /**
     * Sanitize user meta data before saving
     *
     * @param $type
     * @param $rawData
     *
     * @return mixed
     */
    public static function sanitizeUserMetaFieldRawData($type, $rawData)
    {
        switch ($type){
            case UserMetaBoxFieldModel::USER_TYPE;
                return (int)($rawData);

	        case UserMetaBoxFieldModel::ICON_TYPE:
		        return Sanitizer::sanitizeSVG($rawData);

            case UserMetaBoxFieldModel::EMAIL_TYPE:
                return sanitize_email($rawData);

            case UserMetaBoxFieldModel::URL_TYPE:
                return esc_url_raw($rawData);

            case UserMetaBoxFieldModel::TEXTAREA_TYPE:
                return sanitize_textarea_field($rawData);

            case UserMetaBoxFieldModel::EDITOR_TYPE:
            case UserMetaBoxFieldModel::HTML_TYPE:
                return wp_kses_post($rawData);

            case is_array($rawData):
            case UserMetaBoxFieldModel::GALLERY_TYPE:
            case UserMetaBoxFieldModel::CHECKBOX_TYPE:
            case UserMetaBoxFieldModel::SELECT_MULTI_TYPE:
            case UserMetaBoxFieldModel::LIST_TYPE:
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
            'label' => [
                'for' => [],
                'style' => [],
                'type' => [],
                'name' => [],
                'class' => [],
                'id' => []
            ],
            'input' => [
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