<?php

namespace ACPT\Integrations\Breakdance\Provider\Helper;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Utils\Wordpress\WPAttachment;

class RawValueConverter
{
	/**
	 * @param $rawValue
	 * @param $fieldType
	 * @param $attributes
	 *
	 * @return array|string|null
	 */
	public static function convert($rawValue, $fieldType, $attributes)
	{
		switch ($fieldType){

			case AbstractMetaBoxFieldModel::RATING_TYPE:

				if(empty($rawValue)){
					return null;
				}

				$size = isset($attributes['size']) ? $attributes['size'] : null;
				$rawValue = Strings::renderStars($rawValue, $size);
				break;

			case AbstractMetaBoxFieldModel::GALLERY_TYPE:

				if(empty($rawValue)){
					return null;
				}

				if(!is_array($rawValue)){
					return null;
				}

				$attachmentIds = [];

				foreach ($rawValue as $image){
					if($image instanceof WPAttachment){
						$attachmentIds[] = $image->getId();
					}
				}

				$rawValue = $attachmentIds;
				break;

			case AbstractMetaBoxFieldModel::LIST_TYPE:

				if(!is_array($rawValue)){
					return null;
				}

				$list = '<ul>';

				foreach ($rawValue as $item){
					$list .= '<li>'.$item.'</li>';
				}

				$list .= '</ul>';

				$rawValue = $list;

				break;
		}

		return $rawValue;
	}
}