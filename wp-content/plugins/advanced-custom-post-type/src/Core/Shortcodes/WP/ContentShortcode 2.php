<?php

namespace ACPT\Core\Shortcodes\WP;

class ContentShortcode
{
	public function render($atts)
	{
		$pid = isset($atts['pid']) ? $atts['pid'] : get_the_ID();
		$content = get_the_content(null, false, $pid);

		return do_shortcode($content);
	}
}