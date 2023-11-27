<?php

namespace ACPT\Core\Shortcodes\Loop;

class RepeaterFieldShortcode extends AbstractLoopShortcode
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

		if(count($loop) > 0){
			$return .= '<div id="'. $atts['id']. '" class="acpt-loop grid-'. $atts['perRow'].'">';

			foreach ($loop as $index => $datum){
				$return .= $this->injectShortocdes($content, $index);
			}

			$return .= '</div>';

		} else {
			$return .= '<div>'.$atts['noRecords'].'</div>';
		}

		return $return;
	}
}