<?php

namespace ACPT\Core\Data\Import\Importer;

use ACPT\Costants\MetadataFormats;
use ACPT\Utils\Wordpress\Translator;

class MetadataXMLImporter extends AbstractImporter implements MetadataImporterInterface
{
	/**
	 * @param $newItemId
	 * @param $data
	 *
	 * @return mixed|void
	 * @throws \Exception
	 */
	public function importItem($newItemId, $data)
	{
		$xml = simplexml_load_string($data);

		if(!$xml){
			throw new \Exception(Translator::translate('Wrong XML provided, exit'));
		}

		if(!$xml instanceof \SimpleXMLElement){
			throw new \Exception(Translator::translate('Wrong XML provided, exit'));
		}

		if($xml->getName() !== 'acpt_meta'){
			throw new \Exception(Translator::translate('Malformed XML provided, no <acpt_meta> tag found'));
		}

		foreach ($xml as $acpt){
			$find = (isset($acpt->attributes()['find'])) ? $acpt->attributes()['find'][0]->__toString() : null;
			$belongsTo = $acpt->attributes()['belongsTo'][0]->__toString();

			foreach($acpt->boxes[0] as $box){
				$boxName = $box->attributes()['name'][0]->__toString();
				$boxLabel = $box->attributes()['label'][0]->__toString();

				$this->importBoxSettings($belongsTo, $boxName, $boxLabel, $find );

				foreach ($box->fields[0] as $field){
					$fieldName = $field->attributes()['name'][0]->__toString();
					$fieldType = $field->attributes()['type'][0]->__toString();

					$props = $field->props[0];
					$blocks = $field->blocks[0];
					$children = $field->children[0];
					$advancedOptions = $field->advanced_options[0];
					$visibilityConditions = $field->visibility_conditions[0];
					$relations = $field->relations[0];
					$options = $field->options[0];
					$values = $field->values[0];

					$this->importFieldSettings(MetadataFormats::XML_FORMAT, $belongsTo, $boxName, $fieldName, $fieldType, $props, $advancedOptions, $visibilityConditions, $relations, $options, $children, $blocks, $find);
					$this->importFieldMetadata(MetadataFormats::XML_FORMAT, $belongsTo, $newItemId, $boxName, $fieldName, $values);
				}
			}
		}
	}
}