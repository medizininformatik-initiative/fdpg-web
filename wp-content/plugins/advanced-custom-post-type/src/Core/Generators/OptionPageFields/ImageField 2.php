<?php

namespace ACPT\Core\Generators\OptionPageFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;
use ACPT\Utils\Wordpress\Translator;
use ACPT\Utils\Wordpress\WPAttachment;

class ImageField extends AbstractOptionPageField implements MetaFieldInterface
{
    public function render()
    {
	    $attachmentId = (isset($this->getAttachments()[0])) ? $this->getAttachments()[0]->getId() : '';

        $icon = 'bx:bx-image';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.OptionPageMetaBoxFieldModel::IMAGE_TYPE.'">';
        $field .= '<div class="btn-wrapper">';
	    $field .= '<input id="'.esc_attr($this->getIdName()).'_id" name="'. esc_html($this->getIdName()).'_id" type="hidden" value="' .$attachmentId.'">';
        $field .= '<input readonly '.$this->required().' id="'.esc_attr($this->getIdName()).'" name="'. esc_attr($this->getIdName()).'" type="text" class="regular-text acpt-form-control" value="' .esc_attr($this->getDefaultValue()) .'">';
        $field .= '<a class="upload-image-btn button button-primary">'.Translator::translate("Upload").'</a>';

        if($this->getDefaultValue() !== ''){
            $field .= '<button data-target-id="'.esc_attr($this->getIdName()).'" class="upload-delete-btn button button-secondary">'.Translator::translate("Delete").'</button>';
        }

        $preview = (!empty($this->getDefaultValue())) ? '<img src="'.esc_url($this->getDefaultValue()).'"/>' : '';

        $field .= '</div>';
        $field .= '<div class="image-preview"><div class="image">'. $preview .'</div></div>';

        return $this->renderField($icon, $field);
    }
}