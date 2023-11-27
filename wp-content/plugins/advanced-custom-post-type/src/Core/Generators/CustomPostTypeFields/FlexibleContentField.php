<?php

namespace ACPT\Core\Generators\CustomPostTypeFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Generators\FieldBlockGenerator;
use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Core\Repository\MetaRepository;
use ACPT\Costants\MetaTypes;
use ACPT\Utils\Wordpress\Translator;
use ACPT\Utils\Wordpress\WPAttachment;

class FlexibleContentField extends AbstractCustomPostTypeField implements MetaFieldInterface
{
	/**
	 * @var int
	 */
	private $minimumBlocks;

	/**
	 * @var int
	 */
	private $maximumBlocks;

	/**
	 * @inheritDoc
	 * @throws \Exception
	 */
	public function render()
	{
		$this->minimumBlocks = $this->getAdvancedOption('minimum_blocks');
		$this->maximumBlocks = $this->getAdvancedOption('maximum_blocks');

		$this->enqueueAssets();

		$icon = 'bx:bx-hive';
		$field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.CustomPostTypeMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE.'">';

		if($this->minimumBlocks){
			$field .= '<input type="hidden" name="'. esc_attr($this->getIdName()).'_min_blocks" value="'.$this->minimumBlocks.'">';
		}

		if($this->maximumBlocks){
			$field .= '<input type="hidden" name="'. esc_attr($this->getIdName()).'_max_blocks" value="'.$this->maximumBlocks.'">';
		}

		$field .= '<div class="acpt_flexible">';
		$field .= $this->blocksList();
		$field .= $this->addButton();
		$field .= '</div>';

		echo $this->renderField($icon, $field);
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	private function blocksList()
	{
		$list = '<ul id="'.$this->id.'" class="acpt_blocks_list acpt-sortable"';

		if($this->minimumBlocks){
			$list .= ' data-min-blocks="'.$this->minimumBlocks.'"';
		}

		if($this->maximumBlocks){
			$list .= ' data-max-blocks="'.$this->maximumBlocks.'"';
		}

		$list .= '>';

		$defaultData = $this->getDefaultValue();

		if($defaultData and $defaultData !== '' and is_array($defaultData) and isset($defaultData['blocks'])){

			$blockIndex = 0;
			foreach ($defaultData['blocks'] as $blockRawData){
				foreach ($blockRawData as $blockName => $blockRawDatum){
					$boxName = $this->metaBoxModel->getName();
					$fieldName = $this->name;

					$blockModel = MetaRepository::getMetaBlockByName([
						'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
						'find' => $this->metaBoxModel->getPostType(),
						'boxName' => $boxName,
						'fieldName' => $fieldName,
						'blockName' => $this->getOriginalBlockName($blockName),
					]);

					if($blockModel !== null){
						$flexibleFieldGenerator = new FieldBlockGenerator($blockModel);
						$flexibleFieldGenerator->setData($blockRawDatum);
						$flexibleFieldGenerator->setDataId($this->postId);
						$list .= $flexibleFieldGenerator->generate($blockIndex);
					}

					$blockIndex++;
				}
			}

		} else {
			$list .= '<p data-message-id="'.$this->id.'" class="update-nag notice notice-warning inline no-records">'.Translator::translate('No blocks saved, generate the first one clicking on "Add block" button').'</p>';
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
		foreach ($this->metaBoxModel->getFields() as $fieldModel){
			if($fieldModel->getName() === $this->name){
				foreach ($fieldModel->getBlocks() as $blockModel){
					if($blockModel->getNormalizedName() === $name){
						return $blockModel->getName();
					}
				}
			}
		}

		return $name;
	}

	/**
	 * @return string
	 */
	private function addButton()
	{
		if(empty($this->blocks)){
			return 'First, you need to add a block in ACPT settings.';
		}

		$button = '<div class="acpt_add_flexible_block">';
		$button .= '<button class="button acpt_add_flexible_btn"';

		if($this->minimumBlocks){
			$button .= ' data-min-blocks="'.$this->minimumBlocks.'"';
		}

		if($this->maximumBlocks){
			$button .= ' data-max-blocks="'.$this->maximumBlocks.'"';
		}

		$button .= '>';
		$button .= '<span class="acpt_add_flexible_btn_label">'.Translator::translate('Add block').'</span>';
		$button .= '<span class="acpt_add_flexible_btn_icon">
					<svg viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg" width="18" height="18" class="components-panel__arrow" aria-hidden="true" focusable="false"><path d="M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z"></path></svg>
					</span>';
		$button .= '</button>';
		$button .= '<ul class="acpt_flexible_block_items">';

		foreach ($this->blocks as $block){
			$label = (!empty($block['label'])) ? $block['label'] : $block['name'];
			$button .= '<li class="acpt_flexible_block_item" data-field-id="'.$this->id.'" data-media-type="'.MetaTypes::CUSTOM_POST_TYPE.'" data-value="'.$block['id'].'">'.$label.'</li>';
		}

		$button .= '</ul>';

		if($this->minimumBlocks or $this->maximumBlocks){
			$button .= '<div class="min-max-counts">';
		}

		if($this->minimumBlocks){
			$button .= '<span class="min">Min blocks required <span class="count">'.$this->minimumBlocks.'</span></span>';
		}

		if($this->maximumBlocks){
			$button .= '<span class="max">Max blocks allowed <span class="count">'.$this->maximumBlocks.'</span></span>';
		}

		if($this->minimumBlocks or $this->maximumBlocks){
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

		$metaField = MetaRepository::getMetaField([
			'belongsTo' => MetaTypes::CUSTOM_POST_TYPE,
			'id' => $this->id,
		]);

		foreach ($metaField->getBlocks() as $blockModel){
			foreach ($blockModel->getFields() as $nestedFieldModel){
				if($nestedFieldModel->getType() === CustomPostTypeMetaBoxFieldModel::DATE_RANGE_TYPE){
					return true;
				}
			}
		}

		return $return;
	}
}