<?php

namespace ACPT\Core\Generators\OptionPageFields;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Utils\Wordpress\WPAttachment;

abstract class AbstractOptionPageField
{
	/**
	 * @var AbstractMetaBoxFieldModel
	 */
	protected AbstractMetaBoxFieldModel $fieldModel;

	/**
	 * OptionPageMetaBoxFieldGenerator constructor.
	 *
	 * @param AbstractMetaBoxFieldModel $fieldModel
	 */
	public function __construct(AbstractMetaBoxFieldModel $fieldModel)
	{
		$this->fieldModel = $fieldModel;
	}

	/**
	 * @return string
	 */
	protected function getIdName()
	{
		$idName = Strings::toDBFormat($this->fieldModel->getMetaBox()->getName()) . '_' . Strings::toDBFormat($this->fieldModel->getName());

		return esc_html($idName);
	}

	/**
	 * @return string
	 */
	protected function getLabel()
	{
		foreach ($this->fieldModel->getAdvancedOptions() as $advancedOption){
			if ($advancedOption->getKey() === 'label' and $advancedOption->getValue() !== '') {
				return $this->addAsteriskToLabel($advancedOption->getValue());
			}
		}

		return $this->addAsteriskToLabel($this->fieldModel->getName());
	}

	/**
	 * @param $label
	 *
	 * @return mixed
	 */
	private function addAsteriskToLabel($label)
	{
		if($this->fieldModel->isRequired()){
			return $label . '<span class="required">*</span>';
		}

		return $label;
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
	 * @return string
	 */
	protected function required()
	{
		return ($this->fieldModel->isRequired()) ? 'required="required"' : '';
	}

	/**
	 * @return mixed|null
	 */
	protected function getDefaultValue()
	{
		$value = get_option($this->getIdName());

		return ($value !== null and $value !== '' ) ? $value : null;
	}

	/**
	 * @return WPAttachment[]
	 */
	protected function getAttachments()
	{
		$attachments = [];
		$id = get_option($this->getIdName().'_id', true);
		$url = get_option($this->getIdName(), true);

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
	protected function generateRandomId()
	{
		return 'id_'.rand(999999,111111);
	}

	/**
	 * @return string
	 */
	protected function getGridCssClass()
	{
		foreach ($this->fieldModel->getAdvancedOptions() as $advancedOption){
			if ($advancedOption->getKey() === 'width' and $advancedOption->getValue() !== '') {
				return "grid-".$advancedOption->getValue();
			}
		}

		return '';
	}

	/**
	 * @param $icon
	 * @param $field
	 *
	 * @return string
	 */
	protected function renderField($icon, $field)
	{
		$hideIcon = $this->getAdvancedOption('hide_icon');
		$headlineAlignment = $this->getAdvancedOption('headline') ? $this->getAdvancedOption('headline') : 'top';
		$width = $this->getAdvancedOption('width') ? $this->getAdvancedOption('width') : '100';
		$widthStyle = $width.'%';

		$return = '<div class="option-page-meta-field-wrapper" style="width: '.$widthStyle.'">';
		$return .= '<div class="option-page-meta">';

		if(empty($hideIcon) or $hideIcon == "0"){
			$return .= '<div class="acpt-admin-meta-icon">';
			$return .= '<span class="icon"><span class="iconify" style="color: white;" data-width="18" data-height="18" data-icon="'.esc_attr($icon).'"></span></span>';
			$return .= '</div>';
		}

		$return .= '<div class="option-page-meta-field '.$headlineAlignment.'">';

		if($headlineAlignment !== 'none'){
			$return .= '<label for="'.$this->getIdName().'">'.$this->getLabel().'</label>';
		}

		$return .= $field;
		$return .= '</div>';
		$return .= '</div>';
		$return .= '</div>';

		return $return;
	}
}

