<?php

namespace ACPT\Core\Data\Export\Generator;

use ACPT\Core\Data\Export\DTO\MetadataExportItemDto;
use ACPT\Core\Data\Export\Formatter\MetadataExportFormatterInterface;
use ACPT\Core\Data\Export\Formatter\MetadataExportArrayFormatter;
use Symfony\Component\Yaml\Yaml;

class MetadataExportYamlGenerator implements MetadataExportGeneratorInterface
{
	/**
	 * @param MetadataExportItemDto[] $items
	 *
	 * @return string
	 */
	public function generate($items = [])
	{
		$meta = [];

		foreach ($items as $item){
			$meta[] = $this->getFormatter()->format($item);
		}

		return Yaml::dump([
			'acpt_meta' => $meta
		]);
	}

	/**
	 * @return MetadataExportFormatterInterface
	 */
	public function getFormatter()
	{
		return new MetadataExportArrayFormatter();
	}
}