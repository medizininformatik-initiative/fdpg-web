<?php

namespace ACPT\Core\Generators\OptionPageFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;
use ACPT\Utils\Wordpress\Translator;

class IconField extends AbstractOptionPageField implements MetaFieldInterface
{
	public function render()
	{
		$icon = 'bx:bx-wink-smile';
		$field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.OptionPageMetaBoxFieldModel::ICON_TYPE.'">';
		$field .= '<input type="hidden" class="acpt-icon-picker-value" data-target-id="'. $this->getIdName().'" id="'.$this->getIdName().'" name="'. esc_attr($this->getIdName()).'" value="'.htmlspecialchars($this->getDefaultValue()).'"/>';
		$field .= $this->renderIconPicker();

		$field .= '<div class="acpt-icon-picker-wrapper" data-target-id="'.$this->getIdName().'">';
		$field .= '<div class="acpt-icon-picker-preview" data-target-id="'.$this->getIdName().'">';

		if(!empty($this->getDefaultValue())){
			$field .= $this->getDefaultValue();
		}

		$field .= '</div>';
		$field .= '<div class="acpt-icon-picker-buttons" data-target-id="'.$this->getIdName().'">';
		$field .= '<a data-target-id="'.$this->getIdName().'" class="acpt-icon-picker-button button button-secondary" href="#">'.Translator::translate("Browser icons").'</a>';

		$deleteButtonCssClass = (!empty($this->getDefaultValue())) ? '': ' hidden';
		$field .= '<a data-target-id="'.$this->getIdName().'" class="acpt-icon-picker-delete button button-danger '.$deleteButtonCssClass .'" href="#">'.Translator::translate("Delete").'</a>';

		$field .= '</div>';
		$field .= '</div>';

		return $this->renderField($icon, $field);
	}

	/**
	 * @return string
	 */
	protected function renderIconPicker()
	{
		$idModal = $this->getIdName().'_modal';

		$picker = '<div class="acpt-icon-picker-bg hidden" id="'.$idModal.'">';
		$picker .= '<div class="acpt-icon-picker">';
		$picker .= '<div class="acpt-icon-picker-header">';
		$picker .= '<h3>'.Translator::translate("Search an icon").'</h3>';
		$picker .= '<button data-target-id="'.$idModal.'" type="button" class="components-button has-icon close-acpt-icon-picker" aria-label="Close this modal"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path d="M12 13.06l3.712 3.713 1.061-1.06L13.061 12l3.712-3.712-1.06-1.06L12 10.938 8.288 7.227l-1.061 1.06L10.939 12l-3.712 3.712 1.06 1.061L12 13.061z"></path></svg></button>';
		$picker .= '</div>';
		$picker .= '<p>'.Translator::translate("Type at least 3 characters to start searching.").'</p>';
		$picker .= '<input class="regular-text acpt-icon-picker-search" type="text" placeholder="'.Translator::translate("Example: heart").'">';
		$picker .= '<div class="acpt-icon-picker-results" data-target-id="'.$this->getIdName().'"></div>';
		$picker .= '</div>';
		$picker .= '</div>';

		return $picker;
	}
}