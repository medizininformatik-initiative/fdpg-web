<?php

namespace ACPT\Integrations\Breakdance\Provider\Fields;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use Breakdance\DynamicData\StringField;
use Breakdance\DynamicData\StringData;

class ACPTStringField extends StringField implements ACPTFieldInterface
{
	/**
	 * @var AbstractMetaBoxFieldModel
	 */
	protected AbstractMetaBoxFieldModel $fieldModel;

	/**
	 * AbstractACPTField constructor.
	 *
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 */
	public function __construct(AbstractMetaBoxFieldModel $fieldModel)
	{
		$this->fieldModel = $fieldModel;
	}

	/**
	 * @return string
	 */
	public function label()
	{
		return ACPTField::label($this->fieldModel);
	}

	/**
	 * @return string
	 */
	public function category()
	{
		return ACPTField::category();
	}

	/**
	 *@return string
	 */
	public function subcategory()
	{
		return ACPTField::subcategory($this->fieldModel);
	}

	/**
	 * @return string
	 */
	public function slug()
	{
		return ACPTField::slug($this->fieldModel);
	}

	/**
	 * @inheritDoc
	 */
	public function returnTypes()
	{
		return ['string'];
	}

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

		return StringData::fromString($value);
	}
}