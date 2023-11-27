<?php

namespace ACPT\Core\Shortcodes\Loop;

class CustomPostTypeLoopShortcode extends AbstractLoopShortcode
{
	/**
	 * @param array $atts
	 * @param null $content
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function render( $atts = [], $content = null )
	{
		$atts = $this->parseAttrs($atts);
		$loop = $this->loop($atts);

		$return = '';

		if($loop !== null and $loop->post_count > 0){
			$return .= '<div id="'. $atts['id']. '" class="acpt-loop grid-'. $atts['perRow'].'">';

			while ($loop->have_posts() ) : $loop->the_post();
				$return .= do_shortcode($content);
			endwhile;
			wp_reset_query();

			$return .= '</div>';

			if($atts['pagination'] == 1){
				$return .= $this->pagination($atts['belongsTo'], $atts['find'], $atts['perPage']);
			}

		} else {
			$return .= '<div>'.$atts['noRecords'].'</div>';
		}

		return $return;
	}
}