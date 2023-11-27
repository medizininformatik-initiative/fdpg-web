<?php

namespace ACPT\Integrations\Breakdance\Provider\Blocks;

use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Core\Models\MetaField\MetaBoxFieldBlockModel;
use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;
use ACPT\Utils\Wordpress\Translator;
use Breakdance\DynamicData\LoopController;
use Breakdance\DynamicData\RepeaterData;
use Breakdance\DynamicData\RepeaterField;

class ACPTBlock extends RepeaterField
{
	/**
	 * @var MetaBoxFieldBlockModel
	 */
	protected MetaBoxFieldBlockModel $blockModel;

	/**
	 * @var LoopController
	 */
	private LoopController $loop;

	/**
	 * @var int
	 */
	private $limit;

	/**
	 * @var int
	 */
	private $fieldIndex;

	/**
	 * @var int
	 */
	private $blockIndex;

	/**
	 * AbstractACPTField constructor.
	 *
	 * @param MetaBoxFieldBlockModel $blockModel
	 */
	public function __construct(MetaBoxFieldBlockModel $blockModel)
	{
		$this->blockModel = $blockModel;
		$this->loop = \Breakdance\DynamicData\LoopController::getInstance($blockModel->getId());
		$this->limit = 0;
	}

	/**
	 * @inheritDoc
	 */
	public function label()
	{
		$label = '';

		if($this->blockModel->getMetaBoxField() instanceof CustomPostTypeMetaBoxFieldModel){
			$label .= '['.Translator::translate($this->blockModel->getMetaBoxField()->getMetaBox()->getPostType()).']';
		}

		if($this->blockModel->getMetaBoxField() instanceof OptionPageMetaBoxFieldModel){
			$label .= '['.Translator::translate($this->blockModel->getMetaBoxField()->getMetaBox()->getOptionPage()).']';
		}

		$label .= ' - ' . $this->blockModel->getMetaBoxField()->getMetaBox()->getName() . ' ' . $this->blockModel->getMetaBoxField()->getName() . ' #' . $this->blockModel->getName();

		return $label;
	}

	/**
	 * @inheritDoc
	 */
	public function slug()
	{
		return $this->blockModel->getMetaBoxField()->getMetaBox()->getName() . '_' . $this->blockModel->getMetaBoxField()->getName() . '_#_' . $this->blockModel->getName();
	}

	/**
	 * @return string
	 */
	public function category()
	{
		return 'ACPT';
	}

	/**
	 *@return string
	 */
	public function subcategory()
	{
		return 'blocks';
	}

	/**
	 * @inheritDoc
	 */
	public function hasSubFields( $postId = null )
	{
		$blockLoop = $this->loop->get();

		if($this->blockModel->getMetaBoxField() instanceof CustomPostTypeMetaBoxFieldModel){

			if($postId === null){
				$postId = get_the_ID();
			}

			if($postId === null){
				return null;
			}

			$nestedBlocks = get_acpt_block([
				'post_id' => $postId,
				'box_name' => $this->blockModel->getMetaBoxField()->getMetaBox()->getName(),
				'parent_field_name' => $this->blockModel->getMetaBoxField()->getName(),
				'block_name' => $this->blockModel->getName(),
			]);

		} elseif($this->blockModel->getMetaBoxField() instanceof OptionPageMetaBoxFieldModel){

			$nestedBlocks = get_acpt_option_page_block([
				'option_page' => $this->blockModel->getMetaBoxField()->getMetaBox()->getOptionPage(),
				'box_name' => $this->blockModel->getMetaBoxField()->getMetaBox()->getName(),
				'parent_field_name' => $this->blockModel->getMetaBoxField()->getName(),
				'block_name' => $this->blockModel->getName(),
			]);
		}

		if(empty($nestedBlocks)){
			return false;
		}

		$maxLoops = -1;
		$blockIndexMap = [];

		//
		// Create an block index map, with the block index and the corresponding values count.
		//
		// Example:
		//
		// 0 => 2
		// 1 => 3
		// 2 => 2
		//
		foreach ($nestedBlocks as $nestedBlock){
			foreach ($nestedBlock as $nestedBlockName => $nestedBlockValues){
				$countOfNestedValues = count($nestedBlockValues[array_keys($nestedBlockValues)[0]]);
				$maxLoops = $maxLoops + $countOfNestedValues;
				$blockIndexMap[] = $countOfNestedValues;
			}
		}

		$this->blockIndex = $this->calculateBlockIndex($blockIndexMap);
		$this->fieldIndex = $this->calculateFieldIndex($blockIndexMap);

		if($maxLoops <= $blockLoop['limit']){
			$this->limit = 0;
			$this->blockIndex = 0;
			$this->fieldIndex =  0;
			$this->loop->reset();

			return false;
		}

		$this->loop->set([
			'block' => $this->blockModel,
			'limit' => $this->limit,
			'block_index' => $this->blockIndex,
			'field_index' => $this->fieldIndex,
		]);

		$this->limit++;

		return true;
	}

	/**
	 * @param $blockIndexMap
	 *
	 * @return int|string
	 */
	private function calculateBlockIndex($blockIndexMap)
	{
		$calculatedBlockIndex = 0;
		foreach ($blockIndexMap as $blockIndex => $countOfNestedValues){
			$calculatedBlockIndex = $calculatedBlockIndex + $countOfNestedValues;

			if($this->limit < $calculatedBlockIndex){
				return $blockIndex;
			}
		}
	}

	/**
	 * @param $blockIndexMap
	 *
	 * @return int
	 */
	private function calculateFieldIndex($blockIndexMap)
	{
		if($this->blockIndex > 0){
			$indexRest = 0;
			for ($i = 0; $i < $this->blockIndex; $i++){
				$indexRest = $indexRest + $blockIndexMap[$i];
			}

			return $this->limit - $indexRest;
		}

		return $this->limit;
	}

	/**
	 * @inheritDoc
	 */
	public function setSubFieldIndex( $index )
	{
		// TODO: Implement setSubFieldIndex() method.
	}

	/**
	 * @inheritDoc
	 */
	public function parentField()
	{
		// TODO: Implement parentField() method.
	}

	/**
	 * @inheritDoc
	 */
	public function handler( $attributes ): RepeaterData
	{
		return RepeaterData::fromArray([]);
	}
}