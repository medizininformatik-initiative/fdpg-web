<?php

namespace ACPT\Core\Generators\OptionPageFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Generators\FieldBlockGenerator;
use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Costants\MetaTypes;
use ACPT\Utils\Wordpress\Translator;

class FlexibleContentField extends AbstractOptionPageField implements MetaFieldInterface
{
	/**
	 * @return string
	 * @throws \Exception
	 */
	public function render()
	{
		$this->enqueueAssets();

		$icon = 'bx:bx-hive';
		$field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.OptionPageMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE.'">';
		$field .= '<div class="acpt_flexible">';
		$field .= $this->blocksList();
		$field .= $this->addButton();
		$field .= '</div>';

		return $this->renderField($icon, $field);
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	private function blocksList()
	{
		$minimumBlocks = $this->getAdvancedOption('minimum_blocks');
		$maximumBlocks = $this->getAdvancedOption('maximum_blocks');

		$list = '<ul id="'.$this->fieldModel->getId().'" class="acpt_blocks_list acpt-sortable"';
		if($minimumBlocks){
			$list .= ' data-min-blocks="'.$minimumBlocks.'"';
		}

		if($maximumBlocks){
			$list .= ' data-max-blocks="'.$maximumBlocks.'"';
		}

		$list .= '>';

		$defaultData = $this->getDefaultValue();

		if($defaultData and $defaultData !== '' and is_array($defaultData) and isset($defaultData['blocks'])){

			$blockIndex = 0;
			foreach ($defaultData['blocks'] as $blockRawData){
				foreach ($blockRawData as $blockName => $blockRawDatum){
					$boxName = $this->fieldModel->getMetaBox()->getName();
					$fieldName = $this->fieldModel->getName();

					$blockModel = MetaRepository::getMetaBlockByName([
						'belongsTo' => MetaTypes::OPTION_PAGE,
						'find' => $this->fieldModel->getMetaBox()->getOptionPage(),
						'boxName' => $boxName,
						'fieldName' => $fieldName,
						'blockName' => $this->getOriginalBlockName($blockName),
					]);

					if($blockModel !== null){
						$flexibleFieldGenerator = new FieldBlockGenerator($blockModel);
						$flexibleFieldGenerator->setData($blockRawDatum);
						$list .= $flexibleFieldGenerator->generate($blockIndex);
					}

					$blockIndex++;
				}
			}

		} else {
			$list .= '<p data-message-id="'.$this->fieldModel->getId().'" class="update-nag notice notice-warning inline no-records">'.Translator::translate('No blocks saved, generate the first one clicking on "Add block" button').'</p>';
		}

		$list .= '</ul>';

		return $list;
	}

	/**
	 * @param $name
	 *
	 * @return mixed
	 */
	private function getOriginalBlockName($name)
	{
		foreach ($this->fieldModel->getBlocks() as $blockModel){
			if($blockModel->getNormalizedName() === $name){
				return $blockModel->getName();
			}
		}

		return $name;
	}

	/**
	 * @return string
	 */
	private function addButton()
	{
		if(empty($this->fieldModel->getBlocks())){
			return Translator::translate('First, you need to add a block in ACPT settings.');
		}

		$minimumBlocks = $this->getAdvancedOption('minimum_blocks');
		$maximumBlocks = $this->getAdvancedOption('maximum_blocks');

		$button = '<div class="acpt_add_flexible_block">';
		$button .= '<button class="button acpt_add_flexible_btn"';

		if($minimumBlocks){
			$button .= ' data-min-blocks="'.$minimumBlocks.'"';
		}

		if($maximumBlocks){
			$button .= ' data-max-blocks="'.$maximumBlocks.'"';
		}

		$button .= '>';
		$button .= '<span class="acpt_add_flexible_btn_label">'.Translator::translate('Add block').'</span>';
		$button .= '<span class="acpt_add_flexible_btn_icon">
					<svg viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="components-panel__arrow" aria-hidden="true" focusable="false"><path d="M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z"></path></svg>
					</span>';
		$button .= '</button>';
		$button .= '<ul class="acpt_flexible_block_items">';

		foreach ($this->fieldModel->getBlocks() as $block){
			$label = (!empty($block->getLabel())) ? $block->getLabel() : $block->getName();
			$button .= '<li class="acpt_flexible_block_item" data-field-id="'.$this->fieldModel->getId().'" data-media-type="'.MetaTypes::OPTION_PAGE.'" data-value="'.$block->getId().'">'.$label.'</li>';
		}

		$button .= '</ul>';

		if($minimumBlocks or $maximumBlocks){
			$button .= '<div class="min-max-counts">';
		}

		if($minimumBlocks){
			$button .= '<span class="min">'.Translator::translate('Min blocks required').' <span class="count">'.$minimumBlocks.'</span></span>';
		}

		if($maximumBlocks){
			$button .= '<span class="max">'.Translator::translate('Max blocks allowed').' <span class="count">'.$maximumBlocks.'</span></span>';
		}

		if($minimumBlocks or $maximumBlocks){
			$button .= '</div>';
		}

		$button .= '</div>';

		return $button;
	}

	/**
	 * Enqueue necessary assets
	 * @throws \Exception
	 */
	private function enqueueAssets()
	{
		wp_enqueue_script( 'html5sortable', plugin_dir_url( dirname( __FILE__ ) ) . '../../../assets/vendor/html5sortable/dist/html5sortable.min.js', [], '2.2.0', true);

		// for date range
		if($this->thereIsADaterangeField()){
			wp_enqueue_script( 'momentjs', plugin_dir_url( dirname( __FILE__ ) ) . '../../../assets/vendor/moment/moment.min.js', [], '2.18.1', true);
			wp_enqueue_script( 'daterangepicker-js', plugin_dir_url( dirname( __FILE__ ) ) . '../../../assets/vendor/daterangepicker/js/daterangepicker.min.js', [], '3.1.0', true);
			wp_enqueue_style( 'daterangepicker-css', plugin_dir_url( dirname( __FILE__ ) ) . '../../../assets/vendor/daterangepicker/css/daterangepicker.min.css', [], '3.1.0', 'all');
			wp_enqueue_script( 'custom-daterangepicker-js', plugin_dir_url( dirname( __FILE__ ) ) . '../../../assets/static/js/daterangepicker.js', [], '1.0.0', true);
		}
	}

	/**
	 * @return bool
	 * @throws \Exception
	 */
	private function thereIsADaterangeField()
	{
		$return = false;

		foreach ($this->fieldModel->getBlocks() as $blockModel){
			foreach ($blockModel->getFields() as $nestedFieldModel){
				if($nestedFieldModel->getType() === OptionPageMetaBoxFieldModel::DATE_RANGE_TYPE){
					return true;
				}
			}
		}

		return $return;
	}
}