<?php

namespace ACPT\Core\Data\Import;

use ACPT\Core\Data\Import\Importer\MetadataImporterInterface;
use ACPT\Core\Data\Import\Importer\MetadataJsonImporter;
use ACPT\Core\Data\Import\Importer\MetadataXMLImporter;
use ACPT\Core\Data\Import\Importer\MetadataYamlImporter;
use ACPT\Costants\MetadataFormats;

class MetadataImport
{
	/**
	 * @param $newItemId
	 * @param $format
	 * @param $data
	 *
	 * @throws \Exception
	 */
	public static function import($newItemId, $format, $data)
	{
		if(!in_array($format, MetadataFormats::ALLOWED_FORMATS)){
			throw new \Exception($format . ' is not supported format');
		}

		$importer = self::getImporterInstance($format);
		$importer->importItem($newItemId, $data);
	}

	/**
	 * @param $format
	 *
	 * @return MetadataImporterInterface
	 */
	private static function getImporterInstance($format)
	{
		switch ($format){
			case MetadataFormats::JSON_FORMAT:
				return new MetadataJsonImporter();

			case MetadataFormats::XML_FORMAT;
				return new MetadataXMLImporter();

			case MetadataFormats::YAML_FORMAT:
				return new MetadataYamlImporter();
		}
	}
}