<?php

namespace ACPT\Core\Shortcodes\WP;

class TermDescriptionShortcode
{
	public function render($atts)
	{
		$tid = isset($atts['tid']) ? $atts['tid'] : null;

		if($tid === null){
			$queriedObject = get_queried_object();

			if(!$queriedObject instanceof \WP_Term){
				return null;
			}

			$tid = $queriedObject->term_id;
		}

		$taxonomyObject = get_term($tid);

		return $taxonomyObject->description;
	}
}