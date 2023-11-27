<?php

namespace ACPT\Integrations\Breakdance\Provider\Fields;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;
use ACPT\Core\Models\Taxonomy\TaxonomyMetaBoxFieldModel;
use ACPT\Integrations\Breakdance\Provider\Helper\RawValueConverter;
use ACPT\Utils\Wordpress\Translator;
use ACPT\Utils\Wordpress\WPAttachment;

class ACPTField
{
	/**
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 *
	 * @return string
	 */
	public static function label(AbstractMetaBoxFieldModel $fieldModel)
	{
		$label = '';

		if($fieldModel instanceof CustomPostTypeMetaBoxFieldModel){
			$label .= '['.Translator::translate($fieldModel->getMetaBox()->getPostType()).']';
		}

		if($fieldModel instanceof TaxonomyMetaBoxFieldModel){
			$label .= '['.Translator::translate($fieldModel->getMetaBox()->getTaxonomy()).']';
		}

		if($fieldModel instanceof OptionPageMetaBoxFieldModel){
			$label .= '['.Translator::translate($fieldModel->getMetaBox()->getOptionPage()).']';
		}

		if($fieldModel->hasParent()){
			$label .= '['.$fieldModel->getParentField()->getName().']';
		}

		$label .= ' - ' . $fieldModel->getMetaBox()->getName() . ' ' . $fieldModel->getName();

		return $label;
	}

	/**
	 * @return string
	 */
	public static function category()
	{
		return 'ACPT Meta fields';
	}

	/**
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 *
	 * @return string
	 */
	public static function subcategory(AbstractMetaBoxFieldModel $fieldModel)
	{
		if($fieldModel->hasParent()){
			return '[Repeater] - ' . $fieldModel->getParentField()->getName();
		}

		if($fieldModel->isNestedInABlock()){
			return '[Flexible block] - ' . $fieldModel->getParentBlock()->getName();
		}

		if($fieldModel instanceof CustomPostTypeMetaBoxFieldModel){
			return '[CPT] - ' . Translator::translate($fieldModel->getMetaBox()->getPostType());
		}

		if($fieldModel instanceof TaxonomyMetaBoxFieldModel){
			return '[TAX] - ' . Translator::translate($fieldModel->getMetaBox()->getTaxonomy());
		}

		if($fieldModel instanceof OptionPageMetaBoxFieldModel){
			return'[OP] - ' .  Translator::translate($fieldModel->getMetaBox()->getOptionPage());
		}
	}

	/**
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 *
	 * @return string
	 */
	public static function slug(AbstractMetaBoxFieldModel $fieldModel)
	{
		if($fieldModel->hasParent()){
			return $fieldModel->getMetaBox()->getName() . '_' . $fieldModel->getParentField()->getName() . '_' . $fieldModel->getName();
		}

		if($fieldModel->isNestedInABlock()){
			return $fieldModel->getMetaBox()->getName() . '_' . $fieldModel->getParentBlock()->getMetaBoxField()->getName() . '_' . $fieldModel->getParentBlock()->getName() . '_' . $fieldModel->getName();
		}

		return $fieldModel->getMetaBox()->getName() . '_' . $fieldModel->getName();
	}

	/**
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 * @param $attributes
	 *
	 * @return string|null
	 * @throws \Exception
	 */
	public static function getValue(AbstractMetaBoxFieldModel $fieldModel, $attributes)
	{
		$rawValue = null;
		$isVisible = true;

		// Custom Post Type fields
		if($fieldModel instanceof CustomPostTypeMetaBoxFieldModel){

			$postId = get_the_ID();

			if($postId === null){
				return null;
			}

			// REPEATER
			if($fieldModel->hasParent()){
				$parentField = $fieldModel->getParentField();
				$breakdanceLoop = \Breakdance\DynamicData\LoopController::getInstance($parentField->getId());

				if(isset($breakdanceLoop->field['field']) and isset($breakdanceLoop->field['index'])){
					$loopField = $breakdanceLoop->field['field'];
					$loopIndex = $breakdanceLoop->field['index'];

					if($parentField->isEqualsTo($loopField)){

						$rawValue = get_acpt_child_field([
							'post_id' => $postId,
							'box_name' => $fieldModel->getMetaBox()->getName(),
							'field_name' => $fieldModel->getName(),
							'parent_field_name' => $parentField->getName(),
							'index' => $loopIndex,
						]);

						$isVisible = is_acpt_field_visible([
							'post_id' => $postId,
							'box_name' => $fieldModel->getMetaBox()->getName(),
							'field_name' => $fieldModel->getName(),
							'parent_field_name' => $parentField->getName(),
							'index' => $loopIndex,
						]);

						if(empty($rawValue)){
							return null;
						}

						if($isVisible === false){
							return null;
						}

						return RawValueConverter::convert($rawValue, $fieldModel->getType());
					}

					return null;
				}

				return null;
			}

			// FLEXIBLE
			if($fieldModel->isNestedInABlock()){

				$parentBlock = $fieldModel->getParentBlock();
				$breakdanceLoop = \Breakdance\DynamicData\LoopController::getInstance($parentBlock->getId());

				if(
					isset($breakdanceLoop->field['block']) and
					isset($breakdanceLoop->field['limit']) and
					isset($breakdanceLoop->field['block_index']) and
					isset($breakdanceLoop->field['field_index'])
				){
					$loopBlock = $breakdanceLoop->field['block'];
					$blockIndex = $breakdanceLoop->field['block_index'];
					$fieldIndex = $breakdanceLoop->field['field_index'];

					if($parentBlock->isEqualsTo($loopBlock)){

						$rawValue = get_acpt_block_child_field([
							'post_id' => $postId,
							'box_name' => $fieldModel->getMetaBox()->getName(),
							'field_name' => $fieldModel->getName(),
							'parent_field_name' => $parentBlock->getMetaBoxField()->getName(),
							'block_name' => $parentBlock->getName(),
							'block_index' => $blockIndex,
							'index' => $fieldIndex,
						]);

						$isVisible = is_acpt_field_visible([
							'post_id' => $postId,
							'box_name' => $fieldModel->getMetaBox()->getName(),
							'field_name' => $fieldModel->getName(),
							'parent_field_name' => $parentBlock->getMetaBoxField()->getName(),
							'block_name' => $parentBlock->getName(),
							'block_index' => $blockIndex,
							'index' => $fieldIndex,
						]);

						if(empty($rawValue)){
							return null;
						}

						if($isVisible === false){
							return null;
						}

						return RawValueConverter::convert($rawValue, $fieldModel->getType());
					}

					return null;
				}

				return null;
			}

			$rawValue = get_acpt_field([
				'post_id' => $postId,
				'box_name' => $fieldModel->getMetaBox()->getName(),
				'field_name' => $fieldModel->getName(),
			]);

			$isVisible = is_acpt_field_visible([
				'post_id' => $postId,
				'box_name' => $fieldModel->getMetaBox()->getName(),
				'field_name' => $fieldModel->getName(),
			]);
		}

		// Taxonomies fields
		if($fieldModel instanceof TaxonomyMetaBoxFieldModel){

			$queriedObject = get_queried_object();
			$termId = $queriedObject->term_id;

			if($termId === null){
				return null;
			}

			$rawValue = get_acpt_tax_field([
				'term_id' => $termId,
				'box_name' => $fieldModel->getMetaBox()->getName(),
				'field_name' => $fieldModel->getName(),
			]);

			$isVisible = is_acpt_tax_field_visible([
				'term_id' => $termId,
				'box_name' => $fieldModel->getMetaBox()->getName(),
				'field_name' => $fieldModel->getName(),
			]);
		}

		// Option page fields
		if($fieldModel instanceof OptionPageMetaBoxFieldModel){

			// REPEATER
			if($fieldModel->hasParent()){
				$parentField = $fieldModel->getParentField();
				$breakdanceLoop = \Breakdance\DynamicData\LoopController::getInstance($parentField->getId());

				if(isset($breakdanceLoop->field['field']) and isset($breakdanceLoop->field['index'])){
					$loopField = $breakdanceLoop->field['field'];
					$loopIndex = $breakdanceLoop->field['index'];

					if($parentField->isEqualsTo($loopField)){

						$rawValue = get_acpt_option_page_child_field([
							'post_id' => $postId,
							'box_name' => $fieldModel->getMetaBox()->getName(),
							'field_name' => $fieldModel->getName(),
							'parent_field_name' => $parentField->getName(),
							'index' => $loopIndex,
						]);

						$isVisible = is_acpt_option_page_field_visible([
							'post_id' => $postId,
							'box_name' => $fieldModel->getMetaBox()->getName(),
							'field_name' => $fieldModel->getName(),
							'parent_field_name' => $parentField->getName(),
							'index' => $loopIndex,
						]);

						if(empty($rawValue)){
							return null;
						}

						if($isVisible === false){
							return null;
						}

						return RawValueConverter::convert($rawValue, $fieldModel->getType());
					}

					return null;
				}

				return null;
			}

			// FLEXIBLE
			if($fieldModel->isNestedInABlock()){

				$parentBlock = $fieldModel->getParentBlock();
				$breakdanceLoop = \Breakdance\DynamicData\LoopController::getInstance($parentBlock->getId());

				if(
					isset($breakdanceLoop->field['block']) and
					isset($breakdanceLoop->field['limit']) and
					isset($breakdanceLoop->field['block_index']) and
					isset($breakdanceLoop->field['field_index'])
				){
					$loopBlock = $breakdanceLoop->field['block'];
					$blockIndex = $breakdanceLoop->field['block_index'];
					$fieldIndex = $breakdanceLoop->field['field_index'];

					if($parentBlock->isEqualsTo($loopBlock)){

						$rawValue = get_acpt_option_page_block_child_field([
							'post_id' => $postId,
							'box_name' => $fieldModel->getMetaBox()->getName(),
							'field_name' => $fieldModel->getName(),
							'parent_field_name' => $parentBlock->getMetaBoxField()->getName(),
							'block_name' => $parentBlock->getName(),
							'block_index' => $blockIndex,
							'index' => $fieldIndex,
						]);

						$isVisible = is_acpt_option_page_field_visible([
							'post_id' => $postId,
							'box_name' => $fieldModel->getMetaBox()->getName(),
							'field_name' => $fieldModel->getName(),
							'parent_field_name' => $parentBlock->getMetaBoxField()->getName(),
							'block_name' => $parentBlock->getName(),
							'block_index' => $blockIndex,
							'index' => $fieldIndex,
						]);

						if(empty($rawValue)){
							return null;
						}

						if($isVisible === false){
							return null;
						}

						return RawValueConverter::convert($rawValue, $fieldModel->getType());
					}

					return null;
				}

				return null;
			}

			$rawValue = get_acpt_option_page_field([
				'option_page' => $fieldModel->getMetaBox()->getOptionPage(),
				'box_name' => $fieldModel->getMetaBox()->getName(),
				'field_name' => $fieldModel->getName(),
			]);

			$isVisible = is_acpt_option_page_field_visible([
				'option_page' => $fieldModel->getMetaBox()->getOptionPage(),
				'box_name' => $fieldModel->getMetaBox()->getName(),
				'field_name' => $fieldModel->getName(),
			]);
		}

		if(empty($rawValue)){
			return null;
		}

		if($isVisible === false){
			return null;
		}

		return RawValueConverter::convert($rawValue, $fieldModel->getType());
	}
}