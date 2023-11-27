<?php

namespace ACPT\Core\Shortcodes\WP;

class PermalinkShortcode
{
	public function render($atts)
	{
		$pid = isset($atts['pid']) ? $atts['pid'] : get_the_ID();
		$text = isset($atts['anchor']) ? $atts['anchor'] : __("Read more");
		$target = isset($atts['target']) ? $atts['target'] : '';

		return '<a href="'.get_the_permalink($pid).'" target="'.$target.'">'.$text.'</a>';
	}
}