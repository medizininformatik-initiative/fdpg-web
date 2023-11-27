<?php

namespace ACPT\Core\Shortcodes\Loop;

class TaxonomyLoopShortcode extends AbstractLoopShortcode
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
		$terms = $loop->get_terms();

		$return = '';

		if(count($terms) > 0){
			$return .= '<div id="'. $atts['id']. '" class="acpt-loop grid-'. $atts['perRow'].'">';

			foreach ($terms as $term){

				// inject tid into shortcodes
				$shortcodes = [
					'acpt_tax',
					'wp_term_name',
					'wp_term_description',
				];

				foreach ($shortcodes as $shortcode){
					$content = preg_replace('/'.$shortcode.' tid="\d+"/', $shortcode, $content);
					$content = str_replace('['.$shortcode, '['.$shortcode.' tid="'.$term->term_id.'"', $content);
				}

				$return .= do_shortcode($content);
			}

			$return .= '</div>';

			if($atts['pagination'] == 1){
				$return .= $this->pagination($atts['belongsTo'], $atts['find'], $atts['perPage'], '?pag=%#%');
			}

		} else {
			$return .= '<div>'.$atts['noRecords'].'</div>';
		}

		return $return;
	}
}