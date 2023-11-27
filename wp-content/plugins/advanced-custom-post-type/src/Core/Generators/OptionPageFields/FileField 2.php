<?php

namespace ACPT\Core\Generators\OptionPageFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;
use ACPT\Utils\Wordpress\Translator;
use ACPT\Utils\Wordpress\WPAttachment;

class FileField extends AbstractOptionPageField implements MetaFieldInterface
{
    public function render()
    {
	    $attachmentId = (isset($this->getAttachments()[0])) ? $this->getAttachments()[0]->getId() : '';

        $icon = 'bx:bx-cloud-upload';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.OptionPageMetaBoxFieldModel::FILE_TYPE.'">';
	    $field .= '<input id="'.esc_attr($this->getIdName()).'_label" name="'. esc_attr($this->getIdName()).'_label" type="text" class="acpt-form-control mb-4" value="'.esc_attr($this->getDefaultLabel()) .'" placeholder="'.Translator::translate("Enter download text link").'">';
        $field .= '<div class="btn-wrapper">';
	    $field .= '<input id="'.esc_attr($this->getIdName()).'_id" name="'. esc_html($this->getIdName()).'_id" type="hidden" value="' .$attachmentId.'">';
        $field .= '<input readonly '.$this->required().' id="'.esc_attr($this->getIdName()).'" name="'. esc_html($this->getIdName()).'" type="text" class="regular-text acpt-form-control" value="' .esc_attr($this->getDefaultValue()) .'">';
        $field .= '<button class="upload-file-btn button button-primary">'.Translator::translate("Upload").'</button>';

        if($this->getDefaultValue() !== ''){
            $field .= '<button data-target-id="'.esc_attr($this->getIdName()).'" class="file-delete-btn button button-secondary">'.Translator::translate("Delete").'</button>';
        }

        $preview = ($this->getDefaultValue() !== '') ? '<div class="preview-file"><span>'.Translator::translate("Preview").'</span><a target="_blank" href="'.esc_url($this->getDefaultValue()).'">'.$this->getDefaultLabel().'</a></div>' : '';

        $field .= '</div>';
        $field .= '<div class="file-preview"><div class="file">'. $preview .'</div></div>';

        return $this->renderField($icon, $field);
    }

	/**
	 * @return string
	 */
	private function getDefaultLabel()
	{
		return (get_option(esc_attr($this->getIdName()).'_label') !== null and get_option(esc_attr($this->getIdName()).'_label') !== '' ) ?
			get_option(esc_attr($this->getIdName()).'_label') : 'Enter download text link';
	}
}