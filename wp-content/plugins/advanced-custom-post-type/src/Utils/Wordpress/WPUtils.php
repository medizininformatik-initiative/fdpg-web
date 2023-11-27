<?php

namespace ACPT\Utils\Wordpress;

class WPUtils
{
    /**
     * This function replaces locate_template function
     * in order to fix undefined constants issue
     *
     * @see https://developer.wordpress.org/reference/functions/locate_template/
     *
     * @param $template_names
     * @param bool $load
     * @param bool $require_once
     * @param array $args
     * @return string
     */
    public static function locateTemplate( $template_names, $load = false, $require_once = true, $args = array() )
    {
        $located = '';
        foreach ( (array) $template_names as $template_name ) {
            if ( ! $template_name ) {
                continue;
            }
            if ( file_exists( get_stylesheet_directory() . '/' . $template_name ) ) {
                $located = get_stylesheet_directory() . '/' . $template_name;
                break;
            } elseif ( file_exists( get_template_directory() . '/' . $template_name ) ) {
                $located = get_template_directory() . '/' . $template_name;
                break;
            } elseif ( file_exists( ABSPATH . WPINC . '/theme-compat/' . $template_name ) ) {
                $located = ABSPATH . WPINC . '/theme-compat/' . $template_name;
                break;
            }
        }

        if ( $load && '' !== $located ) {
            load_template( $located, $require_once, $args );
        }

        return $located;
    }

	/**
	 * @param string $content
	 * @param int    $id
	 * @param array  $options
	 *
	 * @return false|string
	 */
	public static function renderWpEditor( $content, $id, array $options = array() )
	{
		// @TODO fix tinyMCE on generated content. possible fix:
		// https://wordpress.stackexchange.com/questions/70548/load-tinymce-wp-editor-via-ajax?lq=1
		// https://wordpress.stackexchange.com/questions/51776/how-to-load-wp-editor-through-ajax-jquery

		ob_start();
		wp_editor(stripslashes($content), $id, $options);

		return ob_get_clean();
	}
}