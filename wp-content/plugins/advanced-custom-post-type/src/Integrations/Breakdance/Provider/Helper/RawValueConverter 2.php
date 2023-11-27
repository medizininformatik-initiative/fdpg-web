<?php

namespace ACPT\Integrations\Breakdance\Provider\Helper;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Utils\Wordpress\WPAttachment;

class RawValueConverter
{
	/**
	 * @param $rawValue
	 * @param $fieldType
	 *
	 * @return array|string|null
	 */
	public static function convert($rawValue, $fieldType)
	{
		switch ($fieldType){

			case AbstractMetaBoxFieldModel::CHECKBOX_TYPE:
			case AbstractMetaBoxFieldModel::SELECT_MULTI_TYPE:

				if(!is_array($rawValue)){
					return null;
				}

				$rawValue = implode(', ', $rawValue);

				break;

			case AbstractMetaBoxFieldModel::CURRENCY_TYPE:

				if(!is_array($rawValue)){
					return null;
				}

				if(!isset($rawValue['amount'])){
					return null;
				}

				if(!isset($rawValue['unit'])){
					return null;
				}

				$rawValue = $rawValue['amount'] . ' ' . $rawValue['unit'];

				break;

			case AbstractMetaBoxFieldModel::DATE_RANGE_TYPE:

				if(!is_array($rawValue)){
					return null;
				}

				if(count($rawValue) !== 2){
					return null;
				}

				$rawValue = $rawValue[0].' / '.$rawValue[1];
				break;

			case AbstractMetaBoxFieldModel::EMAIL_TYPE:
				$rawValue = '<a href="mailto:'.$rawValue.'">'.$rawValue.'</a>';
				break;

			case AbstractMetaBoxFieldModel::FILE_TYPE:

				if(!is_array($rawValue)){
					return null;
				}

				if(!isset($rawValue['file']) or !$rawValue['file'] instanceof WPAttachment){
					return null;
				}

				$src = $rawValue['file']->getSrc();
				$label = (isset($rawValue['label'])) ? $rawValue['label'] : $src;

				$rawValue = '<a href="'.$src.'" target="_blank">'.$label.'</a>';
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

			case AbstractMetaBoxFieldModel::IMAGE_TYPE:

				if(empty($rawValue)){
					return null;
				}

				if(!$rawValue instanceof WPAttachment){
					return null;
				}

				$rawValue = $rawValue->getId();
				break;

			case AbstractMetaBoxFieldModel::LENGTH_TYPE:

				if(!is_array($rawValue)){
					return null;
				}

				if(!isset($rawValue['length'])){
					return null;
				}

				if(!isset($rawValue['unit'])){
					return null;
				}

				$rawValue = $rawValue['length'] . ' ' . $rawValue['unit'];

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

			case AbstractMetaBoxFieldModel::NUMBER_TYPE:
				$rawValue = (int)$rawValue;
				break;

			case AbstractMetaBoxFieldModel::PHONE_TYPE:
				$rawValue = '<a href="tel:'.$rawValue.'" target="_blank">'.$rawValue.'</a>';
				break;

			case AbstractMetaBoxFieldModel::VIDEO_TYPE:

				if(empty($rawValue)){
					return null;
				}

				if(!$rawValue instanceof WPAttachment){
					return null;
				}

				$rawValue = '<video controls>
              				<source src="'.esc_url($rawValue->getSrc()).'" type="video/mp4">
            				Your browser does not support the video tag.
            				</video>';

				break;

			case AbstractMetaBoxFieldModel::WEIGHT_TYPE:

				if(!is_array($rawValue)){
					return null;
				}

				if(!isset($rawValue['weight'])){
					return null;
				}

				if(!isset($rawValue['unit'])){
					return null;
				}

				$rawValue = $rawValue['weight'] . ' ' . $rawValue['unit'];

				break;

			case AbstractMetaBoxFieldModel::URL_TYPE:

				if(!is_array($rawValue)){
					return null;
				}

				if(!isset($rawValue['url'])){
					return null;
				}

				if(!isset($rawValue['label'])){
					return null;
				}

				$href = $rawValue['url'];
				$anchorText = (!empty($rawValue['label'])) ? $rawValue['label'] : $rawValue['url'];

				$rawValue = '<a href="'.$href.'" target="_blank">'.$anchorText.'</a>';
				break;
		}

		return $rawValue;
	}
}