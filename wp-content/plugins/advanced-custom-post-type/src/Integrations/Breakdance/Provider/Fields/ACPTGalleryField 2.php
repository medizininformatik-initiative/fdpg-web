<?php

namespace ACPT\Integrations\Breakdance\Provider\Fields;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use Breakdance\DynamicData\GalleryField;
use Breakdance\DynamicData\GalleryData;
use Breakdance\DynamicData\ImageData;

class ACPTGalleryField extends GalleryField implements ACPTFieldInterface
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
	 * @return GalleryData
	 * @throws \Exception
	 */
	public function handler($attributes): GalleryData
	{
		$attachmentIds = ACPTField::getValue($this->fieldModel, $attributes);
		$gallery = new GalleryData();

		if(empty($attachmentIds)){
			return $gallery;
		}

		$images = [];

		foreach ($attachmentIds as $attachmentId){
			$images[] = ImageData::fromAttachmentId($attachmentId);
		}

		$gallery->images = $images;

		return $gallery;
	}
}