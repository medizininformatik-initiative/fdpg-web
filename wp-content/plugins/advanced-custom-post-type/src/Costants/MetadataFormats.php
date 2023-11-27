<?php

namespace ACPT\Costants;

class MetadataFormats
{
	const JSON_FORMAT = 'json';
	const XML_FORMAT = 'xml';
	const YAML_FORMAT = 'yaml';

	const ALLOWED_FORMATS = [
		self::JSON_FORMAT,
		self::XML_FORMAT,
		self::YAML_FORMAT,
	];
}