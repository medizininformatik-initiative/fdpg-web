<?php

namespace ACPT\Integrations\Breakdance\Provider\Fields;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;

interface ACPTFieldInterface
{
	/**
	 * ACPTFieldInterface constructor.
	 *
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 */
	public function __construct(AbstractMetaBoxFieldModel $fieldModel);
}