<?php

namespace ACPT\Core\Generators;

use ACPT\Core\Generators\FlexibleFields\FlexibleFieldInterface;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Core\Models\MetaField\MetaBoxFieldBlockModel;
use ACPT\Utils\Data\DataAggregator;
use ACPT\Utils\Wordpress\Translator;

class FieldBlockGenerator
{
	/**
	 * @var MetaBoxFieldBlockModel
	 */
	private MetaBoxFieldBlockModel $fieldBlockModel;

	/**
	 * @var array
	 */
	private $data = [];

	/**
	 * @var int
	 */
	private $dataId;

	/**
	 * FieldBlockGenerator constructor.
	 *
	 * @param MetaBoxFieldBlockModel $fieldBlockModel
	 */
	public function __construct(MetaBoxFieldBlockModel $fieldBlockModel)
	{
		$this->fieldBlockModel = $fieldBlockModel;
		$this->data = [];
	}

	/**
	 * @param array $data
	 */
	public function setData($data)
	{
		$this->data = $data;
	}

	/**
	 * @param int $dataId
	 */
	public function setDataId( $dataId )
	{
		$this->dataId = $dataId;
	}

	/**
	 * @param null $blockIndex
	 *
	 * @return string
	 */
	public function generate($blockIndex = null)
	{
		$label = (!empty($this->fieldBlockModel->getLabel())) ? $this->fieldBlockModel->getLabel() : $this->fieldBlockModel->getName();
		$mediaType = $this->fieldBlockModel->getMetaBoxField()->getMetaBox()->metaType();
		$id = 'block-'.rand(999999,111111);

		$block = '<li draggable="true" id="'.$id.'" class="sortable-li acpt_blocks_list_item">';
		$block .= '<input type="hidden" name="'. esc_attr($this->fieldBlockModel->getMetaBoxField()->getDbName()).'[blocks]['.$blockIndex.']['.$this->fieldBlockModel->getNormalizedName().']" value="[]">';
		$block .= '<div class="handle">.<br/>.<br/>.</div>';
		$block .= '<div class="sortable-content">';
		$block .= '<div class="acpt_blocks_list_item_title">';
		$block .= '<h3>'.$label.'</h3>';
		$block .= '<div class="acpt_blocks_list_item_icons">';
		$block .= '<a title="'.Translator::translate("Remove this block").'" class="acpt_blocks_list_item_delete" data-target-id="'.$id.'" href="#">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
							<path d="M5 20a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8h2V6h-4V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v2H3v2h2zM9 4h6v2H9zM8 8h9v12H7V8z"></path><path d="M9 10h2v8H9zm4 0h2v8h-2z"></path>
						</svg>
					</a>';
		$block .= '<a title="'.Translator::translate("Show/hide elements in this block").'" class="acpt_blocks_list_item_toggle_visibility" data-target-id="'.$id.'" href="#">
						<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="components-panel__arrow" aria-hidden="true" focusable="false">
							<path d="M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z"></path>
						</svg>
					</a>';
		$block .= '</div>';
		$block .= '</div>';
		$block .= '<div class="acpt_blocks_list_item_fields" data-parent-id="'.$id.'">';
		$block .= $this->generateBlockElements($blockIndex);
		$block .= '</div>';
		$block .= '<a data-index="'.$blockIndex.'" data-media-type="'.$mediaType.'" data-block-id="'.$id.'" data-group-id="'.$this->fieldBlockModel->getId().'" href="#" class="acpt_add_flexible_element_btn button small">'.Translator::translate('Add element').'</a>';
		$block .= '</div>';
		$block .= '</li>';
		
		return $block;
	}

	/**
	 * @param $blockIndex
	 *
	 * @return string
	 */
	private function generateBlockElements($blockIndex)
	{
		$id = 'block-elements-'.$this->fieldBlockModel->getId(). '-'. $blockIndex;
		$list = '<ul id="'.$id.'" class="acpt_nested_fields_list acpt-nested-sortable">';
		$aggregateData = DataAggregator::aggregateNestedFieldsData($this->data);

		if(!empty($aggregateData)){
			foreach ($aggregateData as $elementIndex => $aggregateDatum){
				$list .= $this->generateElement($elementIndex, $blockIndex, $aggregateDatum);
			}
		} else {
			$list .= '<p data-message-id="'.$id.'" class="update-nag notice notice-warning inline no-records">No elements saved, generate the first one clicking on "Add element" button</p>';
		}

		$list .= '</ul>';

		return $list;
	}

	/**
	 * @param $elementIndex
	 * @param $blockIndex
	 * @param array $data
	 *
	 * @return string
	 */
	public function generateElement($elementIndex, $blockIndex, array $data = [])
	{
		$id = 'element-'.rand(999999,111111);

		$element = '<li id="'.$id.'" draggable="true" class="sortable-li">';
		$element .= '<div class="handle">.<br/>.<br/>.</div>';
		$element .= '<div class="sortable-content">';

		foreach ($this->fieldBlockModel->getFields() as $fieldModel){
			$value = $this->getDafaultNestedFieldValue($data, $fieldModel->getNormalizedName());
			$flexibleField = $this->getFlexibleField($fieldModel, $elementIndex, $blockIndex, $value);
			$element .= $flexibleField->render();
		}

		$element .= '</div>';
		$element .= '<a class="button small button-danger remove-grouped-element" data-element="element" data-elements="elements" data-target-id="'.$id.'" href="#">'.Translator::translate('Remove element').'</a>';
		$element .= '</li>';

		return $element;
	}

	/**
	 * @param $data
	 * @param $key
	 *
	 * @return string
	 */
	private function getDafaultNestedFieldValue($data, $key)
	{
		if(empty($data)){
			return null;
		}

		foreach ($data as $datum){
			if($key === $datum['key']){
				return $datum['value'];
			}
		}

		return null;
	}

	/**
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 * @param $elementIndex
	 * @param $blockIndex
	 * @param null $value
	 *
	 * @return FlexibleFieldInterface|null
	 */
	private function getFlexibleField( AbstractMetaBoxFieldModel $fieldModel, $elementIndex, $blockIndex, $value = null )
	{
		$className = 'ACPT\\Core\\Generators\\FlexibleFields\\'.$fieldModel->getType().'Field';

		if(!class_exists($className)){
			return null;
		}

		return new $className(
			$this->fieldBlockModel,
			$fieldModel,
			$elementIndex,
			$blockIndex,
			$this->dataId,
			$value
		);
	}
}
