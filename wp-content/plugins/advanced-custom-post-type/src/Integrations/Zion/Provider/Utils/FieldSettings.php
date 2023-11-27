<?php

namespace ACPT\Integrations\Zion\Provider\Utils;

use ACPT\Core\Repository\MetaRepository;
use ACPT\Integrations\Zion\Provider\Constants\ZionConstants;

class FieldSettings
{
	/**
	 * @param $fieldKey
	 *
	 * @return array|bool
	 * @throws \Exception
	 */
	public static function get($fieldKey)
	{
		$field = explode(ZionConstants::FIELD_KEY_SEPARATOR, $fieldKey);

		if(empty($field)){
			return false;
		}

		$belongsTo = $field[0];
		$fieldId = $field[1];

		$metaFieldSettings = MetaRepository::getMetaField([
			'id' => $fieldId,
			'belongsTo' => $belongsTo,
		]);

		if ($metaFieldSettings === null){
			return false;
		}

		return [
			'id' => $fieldId,
			'belongsTo' => $belongsTo,
			'model' => $metaFieldSettings,
		];
	}
}