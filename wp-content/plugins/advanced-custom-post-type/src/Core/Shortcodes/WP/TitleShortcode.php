<?php

namespace ACPT\Core\Shortcodes\WP;

class TitleShortcode
{
	public function render($atts)
	{
		$pid = isset($atts['pid']) ? $atts['pid'] : get_the_ID();

		return get_the_title($pid);
	}
}