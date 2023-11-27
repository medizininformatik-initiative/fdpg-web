<?php

namespace ACPT\Core\Shortcodes\WP;

class AuthorShortcode
{
	public function render($atts)
	{
		$pid = isset($atts['pid']) ? $atts['pid'] : get_the_ID();

		return get_the_author();
	}
}