<?php

namespace ACPT\Integrations\Breakdance\Provider\Fields;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Utils\Wordpress\WPAttachment;
use Breakdance\DynamicData\ImageField;
use Breakdance\DynamicData\ImageData;

class ACPTImageField extends ImageField implements ACPTFieldInterface
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
	 * @return ImageData
	 * @throws \Exception
	 */
	public function handler($attributes): ImageData
	{
		$value = ACPTField::getValue($this->fieldModel, $attributes);

		if(empty($value)){
			return ImageData::emptyImage();
		}

		if(!$value instanceof WPAttachment){
			return ImageData::emptyImage();
		}

		return ImageData::fromAttachmentId($value->getId());
	}
}