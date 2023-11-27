<?php

namespace ACPT\Integrations\Zion\Provider\Fields;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Integrations\Zion\Provider\Utils\FieldSettings;
use ACPT\Integrations\Zion\Provider\Utils\FieldValue;
use ACPT\Utils\Wordpress\Translator;

class AcptFieldTypeLabelValue extends AcptFieldBase
{
	/**
	 * Retrieve the list of all supported field types
	 * @return array
	 */
	public static function getSupportedFieldTypes()
	{
		return [
			AbstractMetaBoxFieldModel::CHECKBOX_TYPE,
			AbstractMetaBoxFieldModel::RADIO_TYPE,
			AbstractMetaBoxFieldModel::SELECT_TYPE,
			AbstractMetaBoxFieldModel::SELECT_MULTI_TYPE,
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
		return 'acpt-field-label-value';
	}

	/**
	 * @return string
	 */
	public function get_name()
	{
		return Translator::translate( 'ACPT Label-value field');
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
					'title'       => Translator::translate('What you want to display?'),
					'description' => Translator::translate('What you want to display?'),
					'placeholder' => Translator::translate('--Select--'),
					'default'     => 'value',
					'options'     => [
						['name' => Translator::translate('Value'), 'id' => 'value'],
						['name' => Translator::translate('Label'), 'id' => 'label'],
					]
				],
				'separator' => [
					'type'        => 'text',
					'title'       => Translator::translate('Separator'),
					'description' => Translator::translate('Select the separator'),
					'default'     => ',',
				],
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

		$format = $fieldObject['format'] ?? 'value';
		$separator = $fieldObject['separator'] ?? ',';

		switch ($metaFieldModel->getType()){
			case AbstractMetaBoxFieldModel::SELECT_MULTI_TYPE:
			case AbstractMetaBoxFieldModel::CHECKBOX_TYPE:
				echo $this->renderList($metaFieldModel, $rawValue, $separator, $format);
				break;

			case AbstractMetaBoxFieldModel::SELECT_TYPE:
			case AbstractMetaBoxFieldModel::RADIO_TYPE:
				echo $this->renderItem($metaFieldModel, $rawValue, $format);
				break;
		}
	}

	/**
	 * @param AbstractMetaBoxFieldModel $metaFieldModel
	 * @param array $list
	 * @param string $separator
	 * @param null $render
	 *
	 * @return string
	 */
	private function renderList(AbstractMetaBoxFieldModel $metaFieldModel, array $list, $separator = ',', $render = null)
	{
		$renderedList = [];

		foreach ($list as $item){
			$item = ($render === 'label') ? $metaFieldModel->getOptionLabel($item) : $item;
			$renderedList[] = $item;
		}

		return implode($separator, $renderedList);
	}

	/**
	 * @param AbstractMetaBoxFieldModel $metaFieldModel
	 * @param $value
	 * @param null $render
	 *
	 * @return string|null
	 */
	private function renderItem(AbstractMetaBoxFieldModel $metaFieldModel, $value, $render = null)
	{
		if($render === 'label'){
			return $metaFieldModel->getOptionLabel($value);
		}

		return $value;
	}
}