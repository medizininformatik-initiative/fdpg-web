<?php

namespace ACPT\Integrations\Zion\Provider\Fields;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Integrations\Zion\Provider\Utils\FieldSettings;
use ACPT\Integrations\Zion\Provider\Utils\FieldValue;
use ACPT\Utils\Wordpress\Translator;

class AcptFieldTypeList extends AcptFieldBase
{
	/**
	 * Retrieve the list of all supported field types
	 * @return array
	 */
	public static function getSupportedFieldTypes()
	{
		return [
			AbstractMetaBoxFieldModel::LIST_TYPE
		];
	}

	/**
	 * @return string
	 */
	public function get_category()
	{
		return self::CATEGORY_TEXT;
	}

	/**
	 * @return string
	 */
	public function get_id()
	{
		return 'acpt-field-list';
	}

	/**
	 * @return string
	 */
	public function get_name()
	{
		return Translator::translate( 'ACPT List field');
	}

	/**
	 * @return array
	 * @throws \Exception
	 */
	public function get_options()
	{
		return array_merge(
			parent::get_options(),
			[
				'format' => [
					'type'        => 'select',
					'title'       => Translator::translate('List format'),
					'description' => Translator::translate('Select the list format'),
					'placeholder' => Translator::translate('--Select--'),
					'default'     => 'list',
					'options'     => [
						['name' => 'list', 'id' => 'list'],
						['name' => 'string', 'id' => 'string'],
					]
				],
				'separator' => [
					'type'        => 'text',
					'title'       => Translator::translate('String separator'),
					'description' => Translator::translate('Select the string separator'),
					'default'     => ',',
				]
			]
		);
	}

	/**
	 * @param mixed $fieldObject
	 *
	 * @throws \Exception
	 */
	public function render($fieldObject)
	{
		//#! Invalid entry, nothing to do here
		if (empty($fieldObject[ 'field_name' ])) {
			return;
		}

		$fieldSettings = FieldSettings::get($fieldObject[ 'field_name' ]);

		if($fieldSettings === false or empty($fieldSettings)){
			return;
		}

		/** @var AbstractMetaBoxFieldModel $metaFieldModel */
		$metaFieldModel = $fieldSettings['model'];
		$belongsTo = $fieldSettings['belongsTo'];

		if(!$this->isSupportedFieldType($metaFieldModel->getType())){
			return;
		}

		$rawValue = FieldValue::raw($belongsTo, $metaFieldModel);

		if(empty($rawValue)){
			return;
		}

		$format = $fieldObject['format'] ?? 'list';
		$separator = $fieldObject['separator'] ?? ',';

		if(empty($rawValue)){
			return;
		}

		if(!is_array($rawValue)){
			return;
		}

		echo ($format === 'list') ? $this->renderList($rawValue) : $this->renderString($rawValue, $separator);
	}

	/**
	 * @param array $rawValue
	 *
	 * @return string
	 */
	private function renderList($rawValue = [])
	{
		$list = '<ul>';

		foreach ($rawValue as $item) {
			$list .= '<li>'.$item.'</li>';
		}

		$list .= '</ul>';

		return $list;
	}

	/**
	 * @param array $rawValue
	 * @param string $separator
	 *
	 * @return string
	 */
	private function renderString($rawValue = [], $separator = ',')
	{
		return implode($separator, $rawValue);
	}
}