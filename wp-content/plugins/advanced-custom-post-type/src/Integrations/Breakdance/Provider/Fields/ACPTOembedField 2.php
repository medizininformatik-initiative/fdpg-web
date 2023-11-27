<?php

namespace ACPT\Integrations\Breakdance\Provider\Fields;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use Breakdance\DynamicData\OembedField;
use Breakdance\DynamicData\OembedData;

class ACPTOembedField extends OembedField implements ACPTFieldInterface
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
	 * @param mixed $attributes
	 *
	 * @return OembedData
	 * @throws \Exception
	 */
	public function handler($attributes): OembedData
	{
		$resource = ACPTField::getValue($this->fieldModel, $attributes);

		if(!is_string($resource) or $resource === null){
			return new OembedData();
		}

		return OembedData::fromOembedUrl($resource);
	}
}