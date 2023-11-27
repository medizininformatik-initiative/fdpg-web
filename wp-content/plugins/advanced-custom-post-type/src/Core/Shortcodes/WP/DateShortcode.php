<?php

namespace ACPT\Core\Shortcodes\WP;

class DateShortcode
{
	public function render($atts)
	{
		$pid = isset($atts['pid']) ? $atts['pid'] : get_the_ID();
		$format  = isset($atts['format']) ? $atts['format'] : '';

		return get_the_date($format, $pid);
	}
}