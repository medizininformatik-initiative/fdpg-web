<?php

namespace ACPT\Core\Data\Export\DTO;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxModel;

class MetadataExportItemDto
{
	/**
	 * @var int
	 */
	public $id;

	/**
	 * @var string
	 */
	public $belongsTo;

	/**
	 * @var string
	 */
	public $find;

	/**
	 * @var AbstractMetaBoxModel[]
	 */
	public $metaBoxes = [];
}