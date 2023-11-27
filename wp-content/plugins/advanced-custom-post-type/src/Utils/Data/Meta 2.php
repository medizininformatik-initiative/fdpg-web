<?php

namespace ACPT\Utils\Data;

use ACPT\Costants\MetaTypes;

class Meta
{
	/**
	 * @param string $elementId
	 * @param string $belongsTo
	 * @param string $key
	 * @param bool $single
	 *
	 * @return mixed|void|null
	 */
	public static function fetch($elementId, $belongsTo, $key, $single = true)
	{
		$fetched = null;

		switch ($belongsTo){
			case MetaTypes::CUSTOM_POST_TYPE:
				$fetched = get_post_meta($elementId, $key, $single);
				break;

			case MetaTypes::TAXONOMY:
				$fetched = get_term_meta($elementId, $key, $single);
				break;

			case MetaTypes::OPTION_PAGE:
				$fetched = get_option($key);
				break;

			case MetaTypes::USER:
				$fetched = get_user_meta($elementId, $key, $single);
				break;
		}

		return $fetched;
	}
}