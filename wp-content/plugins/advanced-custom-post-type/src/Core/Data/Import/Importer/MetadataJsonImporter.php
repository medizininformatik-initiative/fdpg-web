<?php

namespace ACPT\Core\Data\Import\Importer;

use ACPT\Costants\MetadataFormats;
use ACPT\Utils\Wordpress\Translator;

class MetadataJsonImporter extends AbstractImporter implements MetadataImporterInterface
{
	/**
	 * @param $newItemId
	 * @param $data
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function importItem($newItemId, $data)
	{
		$parsed = json_decode($data, true);

		$this->importParserItem($newItemId, $parsed);
	}

	/**
	 * @param $newItemId
	 * @param $parsed
	 *
	 * @throws \Exception
	 */
	protected function importParserItem($newItemId, $parsed)
	{
		if(!isset($parsed['acpt_meta'])){
			throw new \Exception(Translator::translate('Malformed data provided, no `acpt_meta` node found'));
		}

		foreach ($parsed['acpt_meta'] as $acpt){

			$find = (isset($acpt['find'])) ? $acpt['find'] : null;
			$belongsTo = $acpt['belongsTo'];

			foreach($acpt['boxes'] as $box){
				$boxName = $box['name'];
				$boxLabel = $box['label'];

				$this->importBoxSettings($belongsTo, $boxName, $boxLabel, $find );

				foreach ($box['fields'] as $field){
					$fieldName = $field['name'];
					$fieldType = $field['type'];

					$props = $field['props'];
					$blocks = $field['blocks'];
					$children = $field['children'];
					$advancedOptions = $field['advanced_options'];
					$visibilityConditions = $field['visibility_conditions'];
					$relations = $field['relations'];
					$options = $field['options'];
					$values = $field['values'];

					$this->importFieldSettings(MetadataFormats::JSON_FORMAT, $belongsTo, $boxName, $fieldName, $fieldType, $props, $advancedOptions, $visibilityConditions, $relations, $options, $children, $blocks, $find);
					$this->importFieldMetadata(MetadataFormats::JSON_FORMAT, $belongsTo, $newItemId, $boxName, $fieldName, $values);
				}
			}
		}
	}
}