<?php

namespace ACPT\Core\Shortcodes\Loop;

use ACPT\Admin\ACPT_Hooks;

abstract class AbstractLoopShortcode
{
	/**
	 * @param array $atts
	 *
	 * @return array
	 */
	protected function parseAttrs($atts = [])
	{
		return [
			'belongsTo' => isset($atts['belongs_to']) ? $atts['belongs_to'] : null,
			'find' => isset($atts['find']) ? $atts['find'] : null,
			'pagination' => isset($atts['pagination']) ? $atts['pagination'] : null,
			'perPage' => isset($atts['per_page']) ? $atts['per_page'] : 12,
			'perRow' => isset($atts['per_row']) ? $atts['per_row'] : 3,
			'orderBy' => isset($atts['order_by']) ? $atts['order_by'] : null,
			'sortBy' => isset($atts['sort_by']) ? $atts['sort_by'] : null,
			'noRecords' => isset($atts['no_records']) ? $atts['no_records'] : __('Sorry, no posts matched your criteria.'),
			'id' => isset($atts['id']) ? $atts['id'] : '',
		];
	}

	/**
	 * @param array $atts
	 *
	 * @return array|\WP_Query|\WP_Term_Query|null
	 * @throws \Exception
	 */
	protected function loop($atts = [])
	{
		return (new ACPT_Hooks())->loop([
			'belongsTo' => $atts['belongsTo'],
			'find' => $atts['find'],
			'pagination' => $atts['pagination'] == 1,
			'perPage' => $atts['perPage'],
			'perRow' => $atts['perRow'],
			'orderBy' => $atts['orderBy'],
			'sortBy' => $atts['sortBy'],
		]);
	}

	/**
	 * @param $belongsTo
	 * @param $find
	 * @param $perPage
	 * @param string $format
	 *
	 * @return string
	 */
	protected function pagination($belongsTo, $find, $perPage, $format = '/page/%#%')
	{
		return (new ACPT_Hooks())->pagination($belongsTo, $find, $perPage, $format);
	}

	/**
	 * @param array $atts
	 * @param null $content
	 *
	 * @return string
	 */
	abstract public function render($atts = [], $content = null);

	/**
	 * @param $content
	 * @param $fieldIndex
	 * @param null $blockIndex
	 *
	 * @return string
	 */
	protected function injectShortocdes($content, $fieldIndex, $blockIndex = null)
	{
		$tags = [
			'acpt',
			'acpt_option',
		];

		foreach ($tags as $tag){
			$content = preg_replace('/'.$tag.' index="\d+"/', 'acpt', $content);
			$replacedTag = ($blockIndex !== null) ? '['.$tag.' block_index="'.$blockIndex.'" index="'.$fieldIndex.'"' : '['.$tag.' index="'.$fieldIndex.'"';
			$content = str_replace('['.$tag, $replacedTag, $content);
		}

		return do_shortcode($content);
	}
}