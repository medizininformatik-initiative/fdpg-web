<?php

namespace ACPT\Integrations\Breakdance\Provider\Fields;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;
use Breakdance\DynamicData\DynamicDataController;
use Breakdance\DynamicData\LoopController;
use Breakdance\DynamicData\RepeaterField;
use Breakdance\DynamicData\RepeaterData;

class ACPTRepeaterField extends RepeaterField implements ACPTFieldInterface
{
	/**
	 * @var AbstractMetaBoxFieldModel
	 */
	protected AbstractMetaBoxFieldModel $fieldModel;

	/**
	 * @var LoopController
	 */
	private LoopController $loop;

	/**
	 * @var int
	 */
	private $index;

	/**
	 * AbstractACPTField constructor.
	 *
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 */
	public function __construct(AbstractMetaBoxFieldModel $fieldModel)
	{
		$this->fieldModel = $fieldModel;
		$this->loop = \Breakdance\DynamicData\LoopController::getInstance($fieldModel->getId());
		$this->index = 0;
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
	 * Is this never called?
	 *
	 * @param mixed $attributes
	 *
	 * @return RepeaterData
	 * @throws \Exception
	 */
	public function handler($attributes): RepeaterData
	{
		return RepeaterData::fromArray([]);
	}

	/**
	 * @param null $postId
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function hasSubFields( $postId = null )
	{
		$fieldLoop = $this->loop->get();

		switch ($this->fieldModel->getType()){

			// Handle Repeater fields
			case AbstractMetaBoxFieldModel::REPEATER_TYPE:

				if($this->fieldModel instanceof CustomPostTypeMetaBoxFieldModel){

					if($postId === null){
						$postId = get_the_ID();
					}

					if($postId === null){
						return null;
					}

					$nestedValues = get_acpt_field([
						'post_id' => $postId,
						'box_name' => $this->fieldModel->getMetaBox()->getName(),
						'field_name' => $this->fieldModel->getName(),
					]);

				} elseif($this->fieldModel instanceof OptionPageMetaBoxFieldModel){
					$nestedValues = get_acpt_option_page_field([
						'option_page' => $this->fieldModel->getMetaBox()->getOptionPage(),
						'box_name' => $this->fieldModel->getMetaBox()->getName(),
						'field_name' => $this->fieldModel->getName(),
					]);
				}

				if(empty($nestedValues)){
					return false;
				}

				$maxLoops = count($nestedValues) - 1;

				if($maxLoops <= $fieldLoop['index']){
					$this->index = 0;
					$this->loop->reset();

					return false;
				}

				$this->loop->set([
					'field' => $this->fieldModel,
					'index' => $this->index,
				]);

				$this->index++;

				return true;
		}

		return false;
	}

	/**
	 * @inheritDoc
	 */
	public function setSubFieldIndex( $index )
	{
		$blockLoop = $this->loop->get();

		return $blockLoop['index'];
	}

	/**
	 * @return \Breakdance\DynamicData\Field|RepeaterField|false|null
	 * @throws \Exception
	 */
	public function parentField()
	{
		$parentFieldModel =  $this->fieldModel->getParentField();

		if($parentFieldModel === null){
			return null;
		}

		return DynamicDataController::getInstance()->getField(ACPTField::slug($parentFieldModel));
	}
}