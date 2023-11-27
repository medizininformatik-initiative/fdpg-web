<?php

namespace ACPT\Utils\MetaSync;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxModel;
use ACPT\Costants\MetaTypes;

class MetaSync
{
	/**
	 * @param $metaType
	 * @param AbstractMetaBoxModel $metaBoxModel
	 *
	 * @throws \Exception
	 */
	public static function syncBox($metaType, AbstractMetaBoxModel $metaBoxModel)
	{
		switch ($metaType){
			case MetaTypes::CUSTOM_POST_TYPE:
				PostMetaSync::syncBox($metaBoxModel);
				break;

			case MetaTypes::TAXONOMY:
				TaxonomyMetaSync::syncBox($metaBoxModel);
				break;

			case MetaTypes::OPTION_PAGE:
				OptionPageMetaSync::syncBox($metaBoxModel);
				break;

			case MetaTypes::USER:
				UserMetaSync::syncBox($metaBoxModel);
				break;
		}
	}

	/**
	 * @param $metaType
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 *
	 * @throws \Exception
	 */
	public static function syncField($metaType, AbstractMetaBoxFieldModel $fieldModel )
	{
		switch ($metaType){
			case MetaTypes::CUSTOM_POST_TYPE:
				PostMetaSync::syncField($fieldModel);
				break;

			case MetaTypes::TAXONOMY:
				TaxonomyMetaSync::syncField($fieldModel);
				break;

			case MetaTypes::OPTION_PAGE:
				OptionPageMetaSync::syncField($fieldModel);
				break;

			case MetaTypes::USER:
				UserMetaSync::syncField($fieldModel);
				break;
		}
	}
}