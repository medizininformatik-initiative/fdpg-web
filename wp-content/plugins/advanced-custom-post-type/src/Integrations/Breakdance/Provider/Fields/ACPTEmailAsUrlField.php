<?php

namespace ACPT\Integrations\Breakdance\Provider\Fields;

use Breakdance\DynamicData\StringData;

class ACPTEmailAsUrlField extends ACPTStringAsUrlField
{
	/**
	 * @param mixed $attributes
	 *
	 * @return StringData
	 * @throws \Exception
	 */
	public function handler($attributes): StringData
	{
		$value = ACPTField::getValue($this->fieldModel, $attributes);

		if(!is_string($value) or $value === null){
			return StringData::emptyString();
		}

		$value = "mailto:".$value;

		return StringData::fromString($value);
	}
}
