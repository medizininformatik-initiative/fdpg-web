<?php

namespace ACPT\Core\Shortcodes\Loop;

class BlockLoopShortcode extends AbstractLoopShortcode
{
	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function render( $atts = [], $content = null )
	{
		$atts = $this->parseAttrs($atts);
		$loop = $this->loop($atts);

		$return = '';

		if(count($loop) > 0){
			$return .= '<div id="'. $atts['id']. '" class="acpt-loop grid-'. $atts['perRow'].'">';

			foreach ($loop as $blockIndex => $data){
				foreach ($data as $fieldIndex => $datum){
					$ffdsfds = 545454;
					$ffdsfds = 545454;
					$ffdsfds = 545454;

					$return .= $this->injectShortocdes($content, $fieldIndex, $blockIndex);
				}
			}

			$return .= '</div>';
		} else {
			$return .= '<div>'.$atts['noRecords'].'</div>';
		}

		return $return;
	}
}