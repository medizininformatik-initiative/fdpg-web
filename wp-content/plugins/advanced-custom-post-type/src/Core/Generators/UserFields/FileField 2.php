<?php

namespace ACPT\Core\Generators\UserFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\User\UserMetaBoxFieldModel;
use ACPT\Utils\Wordpress\Translator;
use ACPT\Utils\Wordpress\WPAttachment;

class FileField extends AbstractUserField implements MetaFieldInterface
{
    public function render()
    {
	    $defaultLabel = (get_user_meta($this->userId, esc_attr($this->getIdName()).'_label', true) !== null and get_user_meta($this->userId, esc_attr($this->getIdName()).'_label', true) !== '' ) ? get_user_meta($this->userId, esc_attr($this->getIdName()).'_label', true) : Translator::translate('Download');
	    $attachmentId = (isset($this->getAttachments()[0])) ? $this->getAttachments()[0]->getId() : '';

	    $icon = 'bx:bx-cloud-upload';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.UserMetaBoxFieldModel::FILE_TYPE.'">';
	    $field .= '<input id="'.esc_attr($this->getIdName()).'_label" name="'. esc_attr($this->getIdName()).'_label" value="'.esc_attr($defaultLabel).'" type="text" class="regular-text" placeholder="'.Translator::translate("Enter download text link").'" />';
        $field .= '<div class="btn-wrapper">';
	    $field .= '<input id="'.esc_attr($this->getIdName()).'_id" name="'. esc_html($this->getIdName()).'_id" type="hidden" value="' .$attachmentId.'">';
        $field .= '<input readonly '.$this->required().' id="'.esc_attr($this->getIdName()).'" name="'. esc_html($this->getIdName()).'" type="text" class="regular-text" value="' .esc_attr
                ($this->getDefaultValue()) .'">';
        $field .= '<button class="upload-file-btn button button-primary">'.Translator::translate("Upload").'</button>';

        if($this->getDefaultValue() !== ''){
            $field .= '<button data-target-id="'.esc_attr($this->getIdName()).'" class="file-delete-btn button button-secondary">'.Translator::translate("Delete").'</button>';
        }

	    $preview = ($this->getDefaultValue() !== '') ? '<div class="preview-file"><span>'.Translator::translate("Preview").'</span><a target="_blank" href="'.esc_url($this->getDefaultValue()).'">'.esc_attr($defaultLabel).'</a></div>' : '';

        $field .= '</div>';
        $field .= '<div class="file-preview"><div class="file">'. $preview .'</div></div>';

        echo $this->renderField($icon, $field);
    }
}