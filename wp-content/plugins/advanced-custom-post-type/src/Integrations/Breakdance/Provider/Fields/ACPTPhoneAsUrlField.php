<?php

namespace ACPT\Integrations\Breakdance\Provider\Fields;

use Breakdance\DynamicData\StringData;

class ACPTPhoneAsUrlField extends ACPTStringAsUrlField
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

		$value = "tel:".$value;

		return StringData::fromString($value);
	}
}
