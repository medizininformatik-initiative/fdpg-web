<?php

namespace ACPT\Core\Shortcodes\ACPT;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Core\Repository\OptionPageRepository;
use ACPT\Core\Shortcodes\ACPT\DTO\ShortcodePayload;
use ACPT\Costants\MetaTypes;

class OptionPageMetaShortcode extends AbstractACPTShortcode
{
	/**
	 * @param $atts
	 *
	 * @return string|null
	 * @throws \Exception
	 */
	public function render( $atts )
	{
		if(!isset($atts['box']) or !isset($atts['field'])){
			return '';
		}

		$box = $atts['box'];
		$field = $atts['field'];

		if(isset($atts['page'])){
			$page = $atts['page'];
		} else {
			$pageFieldModel =  MetaRepository::getMetaFieldByName([
				'boxName' => $atts['box'],
				'fieldName' => $atts['field'],
				'belongsTo' => MetaTypes::OPTION_PAGE,
			]);

			if($pageFieldModel === null){
				return '';
			}

			$page = $pageFieldModel->getMetaBox()->getOptionPage();
		}

		$pageModel = OptionPageRepository::getByMenuSlug($page);

		if($pageModel === null){
			return '';
		}

		$blockName = isset ($atts['block_name'] ) ? $atts['block_name'] : null;
		$blockIndex = isset ($atts['block_index'] ) ? $atts['block_index'] : null;
		$width = isset ($atts['width'] ) ? $atts['width'] : null;
		$height = isset ($atts['height'] ) ? $atts['height'] : null;
		$target = isset ($atts['target'] ) ? $atts['target'] : null;
		$dateFormat = isset ($atts['date-format'] ) ? $atts['date-format'] : null;
		$elements = isset ($atts['elements'] ) ? $atts['elements'] : null;
		$preview = (isset($atts['preview']) and $atts['preview'] === 'true') ? true : false;
		$parent = (isset($atts['parent'])) ? $atts['parent'] : null;
		$index = (isset($atts['index'])) ? $atts['index'] : null;
		$render = (isset($atts['render'])) ? $atts['render'] : null;

		if($parent){

			$key = Strings::toDBFormat($box).'_'.Strings::toDBFormat($parent);
			@$groupRawValue = get_option($key, true);

			if($groupRawValue === null or $groupRawValue === ''){
				return '';
			}

			if($index !== null and isset($groupRawValue[Strings::toDBFormat($field)][$index])){
				$data = $groupRawValue[Strings::toDBFormat($field)][$index];
				$type = $data['type'];
			}

			if($blockName !== null and $blockIndex !== null){
				if(isset($groupRawValue['blocks']) and
				   isset($groupRawValue['blocks'][$blockIndex]) and
				   isset($groupRawValue['blocks'][$blockIndex][$blockName]) and
				   isset($groupRawValue['blocks'][$blockIndex][$blockName][Strings::toDBFormat($field)]) and
				   isset($groupRawValue['blocks'][$blockIndex][$blockName][Strings::toDBFormat($field)][$index])
				){
					$data = $groupRawValue['blocks'][$blockIndex][$blockName][Strings::toDBFormat($field)][$index];
					$type = $data['type'];
				}
			}

		} else {
			$key = Strings::toDBFormat($box).'_'.Strings::toDBFormat($field);
			$type = get_option($key.'_type');
			$data = get_option($key);

			if($data === null or $data === ''){
				return '';
			}
		}

		if(!empty($type)){

			$payload = new ShortcodePayload();
			$payload->id = $pageModel->getId();
			$payload->box = $box;
			$payload->field = $field;
			$payload->belongsTo = MetaTypes::OPTION_PAGE;
			$payload->find = $page;
			$payload->width = $width;
			$payload->height = $height;
			$payload->target = $target;
			$payload->dateFormat = $dateFormat;
			$payload->elements = $elements;
			$payload->preview = $preview;
			$payload->parent = $parent;
			$payload->index = $index;
			$payload->blockIndex = $blockIndex;
			$payload->blockName = $blockName;
			$payload->render = $render;

			$field = self::getField($type, $payload);

			if($field){
				return $field->render();
			}

			return null;
		}

		return null;
	}
}