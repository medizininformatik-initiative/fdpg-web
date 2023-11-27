<?php

namespace ACPT\Core\Data\Export;

use ACPT\Core\Data\Export\DTO\MetadataExportItemDto;
use ACPT\Core\Data\Export\Generator\MetadataExportGeneratorInterface;
use ACPT\Core\Data\Export\Generator\MetadataExportJsonGenerator;
use ACPT\Core\Data\Export\Generator\MetadataExportXMLGenerator;
use ACPT\Core\Data\Export\Generator\MetadataExportYamlGenerator;
use ACPT\Costants\MetadataFormats;

class MetadataExport
{
	/**
	 * This function exports the metadata items into a file
	 *
	 * @param string $format
	 * @param MetadataExportItemDto[] $items $items
	 *
	 * @return string
	 * @throws \Exception
	 */
	public static function export($format, $items = [])
	{
		if(!in_array($format, MetadataFormats::ALLOWED_FORMATS)){
			throw new \Exception($format . ' is not supported format');
		}

		$generator = self::getGeneratorInstance($format);

		return $generator->generate($items);
	}

	/**
	 * @param $format
	 *
	 * @return MetadataExportGeneratorInterface
	 */
	private static function getGeneratorInstance($format)
	{
		switch ($format){
			case MetadataFormats::JSON_FORMAT:
				return new MetadataExportJsonGenerator();

			case MetadataFormats::XML_FORMAT;
				return new MetadataExportXMLGenerator();

			case MetadataFormats::YAML_FORMAT:
				return new MetadataExportYamlGenerator();
		}
	}
}