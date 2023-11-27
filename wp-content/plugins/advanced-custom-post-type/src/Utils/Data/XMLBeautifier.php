<?php

namespace ACPT\Utils\Data;

class XMLBeautifier
{
	/**
	 * @param $xml
	 *
	 * @return mixed
	 */
	public static function beautify($xml)
	{
		$dom = new \DOMDocument;
		$dom->preserveWhiteSpace = false;
		$dom->loadXML($xml);
		$dom->formatOutput = true;

		return $dom->saveXML();
	}
}



