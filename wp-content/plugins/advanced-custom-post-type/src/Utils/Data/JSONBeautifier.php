<?php

namespace ACPT\Utils\Data;

class JSONBeautifier
{
	/**
	 * @param array $json
	 *
	 * @return false|string
	 */
	public static function beautify(array $json = [])
	{
		return json_encode($json, JSON_PRETTY_PRINT);
	}
}



