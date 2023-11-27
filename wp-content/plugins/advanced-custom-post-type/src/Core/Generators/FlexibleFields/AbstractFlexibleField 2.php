<?php

namespace ACPT\Core\Generators\FlexibleFields;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Core\Models\MetaField\MetaBoxFieldBlockModel;
use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;
use ACPT\Core\Models\Taxonomy\TaxonomyMetaBoxFieldModel;
use ACPT\Core\Models\User\UserMetaBoxFieldModel;
use ACPT\Costants\MetaTypes;
use ACPT\Utils\Data\Sanitizer;
use ACPT\Utils\Wordpress\WPAttachment;

abstract class AbstractFlexibleField
{
	/**
	 * @var MetaBoxFieldBlockModel
	 */
	protected $parentBlockModel;

	/**
	 * @var AbstractMetaBoxFieldModel
	 */
	protected $fieldModel;

	/**
	 * @var null
	 */
	protected $value = null;

	/**
	 * @var int
	 */
	protected $dataId;

	/**
	 * @var int
	 */
	protected $elementIndex;

	/**
	 * @var int
	 */
	protected $blockIndex;

	/**
	 * @var string
	 */
	protected $metaType;

	/**
	 * AbstractRepeaterField constructor.
	 *
	 * @param MetaBoxFieldBlockModel $parentBlockModel
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 * @param $elementIndex
	 * @param $blockIndex
	 * @param null $dataId
	 * @param null $value
	 */
	public function __construct(
		MetaBoxFieldBlockModel $parentBlockModel,
		AbstractMetaBoxFieldModel $fieldModel,
		$elementIndex,
		$blockIndex,
		$dataId = null,
		$value = null
	)
	{
		$this->setMetaType($fieldModel);
		$this->parentBlockModel = $parentBlockModel;
		$this->fieldModel       = $fieldModel;
		$this->value            = $value;
		$this->dataId           = $dataId;
		$this->elementIndex     = $elementIndex;
		$this->blockIndex       = $blockIndex;
	}

	/**
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 *
	 * @return string
	 */
	public function setMetaType(AbstractMetaBoxFieldModel $fieldModel)
	{
		if($fieldModel instanceof CustomPostTypeMetaBoxFieldModel){
			return MetaTypes::CUSTOM_POST_TYPE;
		}

		if($fieldModel instanceof OptionPageMetaBoxFieldModel){
			return MetaTypes::OPTION_PAGE;
		}

		if($fieldModel instanceof TaxonomyMetaBoxFieldModel){
			return MetaTypes::TAXONOMY;
		}

		if($fieldModel instanceof UserMetaBoxFieldModel){
			return MetaTypes::USER;
		}
	}

	/**
	 * @return string
	 */
	public function getMetaType()
	{
		return $this->metaType;
	}

	/**
	 * @return string
	 */
	protected function generateRandomId()
	{
		return 'id_'.rand(999999,111111);
	}

	/**
	 * @return string
	 */
	protected function getIdName()
	{
		$parentFieldModel = $this->parentBlockModel->getMetaBoxField();

		return $parentFieldModel->getDbName().'[blocks]['.$this->blockIndex.']['.$this->parentBlockModel->getNormalizedName().']['.$this->fieldModel->getNormalizedName().']['.$this->elementIndex.']';
	}

	/**
	 * @return mixed
	 */
	protected function getDefaultValue()
	{
		return ($this->value) ? $this->value : $this->fieldModel->getDefaultValue();
	}

	/**
	 * @return WPAttachment[]
	 */
	protected function getAttachments()
	{
		$attachments = [];
		$data = $this->getParentData();
		$key = Strings::toDBFormat($this->fieldModel->getName());

		$id = (isset($data[$key]) and isset($data[$key][$this->elementIndex]) and isset($data[$key][$this->elementIndex]['id']) ) ? $data[$key][$this->elementIndex]['id'] : null;
		$url = (isset($data[$key]) and isset($data[$key][$this->elementIndex]) and isset($data[$key][$this->elementIndex]['value']) ) ? $data[$key][$this->elementIndex]['value'] : null;

		// from id
		if(!empty($id)){
			$ids =  explode(',', $id);

			foreach ($ids as $_id){
				$attachments[] = WPAttachment::fromId($_id);
			}

			return $attachments;
		}

		// from url
		if(!empty($url)){
			if(is_array($url)){
				foreach ($url as $_url){
					$attachments[] = WPAttachment::fromUrl($_url);
				}

				return $attachments;
			}

			$attachments[] = WPAttachment::fromUrl($url);
		}

		return $attachments;
	}

	/**
	 * @return string
	 */
	protected function required()
	{
		return ($this->fieldModel->isRequired()) ? 'required="required"' : '';
	}

	/**
	 * @param $key
	 *
	 * @return mixed|null
	 */
	protected function getAdvancedOption($key)
	{
		foreach ($this->fieldModel->getAdvancedOptions() as $advancedOption){
			if ($advancedOption->getKey() === $key and $advancedOption->getValue() !== '') {
				return $advancedOption->getValue();
			}
		}

		return null;
	}

	/**
	 * @param $field
	 *
	 * @return string
	 */
	protected function renderField($field)
	{
		$headlineAlignment = $this->getAdvancedOption('headline') ? $this->getAdvancedOption('headline') : 'left';
		$width = $this->getAdvancedOption('width') ? $this->getAdvancedOption('width') : '100';

		$return = '<div class="field '.$headlineAlignment.'" data-index="'.$this->elementIndex.'" style="width: '.$width.'%;">';
		$return .= '<div class="field-inner">';

		if ( $headlineAlignment === 'top' or $headlineAlignment === 'left' ) {
			$return .= $this->renderFieldLabel() . $this->renderFieldValue($field);
		} elseif ( $headlineAlignment === 'right' ) {
			$return .= $this->renderFieldValue( $field ) . $this->renderFieldLabel();
		} elseif ( $headlineAlignment === 'none' ) {
			$return .= $this->renderFieldValue( $field );
		}

		$return .= '</div>';
		$return .= '</div>';

		return $return;
	}

	/**
	 * @param $icon
	 *
	 * @return string
	 */
	private function renderFieldLabel()
	{
		$return = '<div class="acpt-admin-meta-label">';
		$return .= '<label for="'.$this->getIdName().'">';
		$return .= esc_html($this->displayLabel());

		if($this->fieldModel->isRequired()){
			$return .= '<span class="required">*</span>';
		}

		$return .= '</label>';
		$return .= '</div>';

		return $return;
	}

	/**
	 * @param $field
	 *
	 * @return mixed|string
	 */
	private function renderFieldValue($field)
	{
		$return = ($this->fieldModel->getType() !== CustomPostTypeMetaBoxFieldModel::EDITOR_TYPE) ? Sanitizer::escapeField($field) : $field;

		if($this->fieldModel->getDescription() !== null and $this->fieldModel->getDescription() !== ''){
			$return .= '<span class="description">'.$this->fieldModel->getDescription().'</span>';
		}

		return $return;
	}

	/**
	 * @return string
	 */
	protected function displayLabel()
	{
		foreach ($this->fieldModel->getAdvancedOptions() as $advancedOption){
			if ($advancedOption->getKey() === 'label' and $advancedOption->getValue() !== '') {
				return $advancedOption->getValue();
			}
		}

		return $this->fieldModel->getName();
	}

	/**
	 * @return mixed
	 */
	protected function getParentData()
	{
		$parentFieldModel = $this->parentBlockModel->getMetaBoxField();

		switch ($this->getMetaType()){
			case MetaTypes::OPTION_PAGE:
				return get_option($parentFieldModel->getDbName());

			case MetaTypes::TAXONOMY:
				return get_term_meta($this->dataId, $parentFieldModel->getDbName(), true);

			case MetaTypes::USER:
				return get_user_meta($this->dataId, $parentFieldModel->getDbName(), true);

			default:
			case MetaTypes::CUSTOM_POST_TYPE:
				return get_post_meta($this->dataId, $parentFieldModel->getDbName(), true);
		}
	}
}