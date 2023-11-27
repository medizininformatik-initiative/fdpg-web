<?php

namespace ACPT\Core\Generators\RepeaterFields;

use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Utils\Wordpress\Translator;
use ACPT\Utils\Wordpress\WPAttachment;

class ImageField extends AbstractRepeaterField implements RepeaterFieldInterface
{
    public function render()
    {
	    $attachmentId = (isset($this->getAttachments()[0])) ? $this->getAttachments()[0]->getId() : '';

        $id = $this->generateRandomId();
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'[type]" value="'.CustomPostTypeMetaBoxFieldModel::IMAGE_TYPE.'">';
        $field .= '<input type="hidden" name="'. esc_attr($this->getIdName()).'[original_name]" value="'.$this->fieldModel->getName().'">';
        $field .= '<div class="btn-wrapper">';
	    $field .= '<input id="'.$id.'_id" type="hidden" name="'. esc_attr($this->getIdName()).'[id]" value="' .$attachmentId.'">';
        $field .= '<input readonly '.$this->required().' id="'.$id.'" name="'. esc_attr($this->getIdName()).'[value]" type="text" class="regular-text acpt-admin-meta-field-input" value="' .esc_attr
                ($this->getDefaultValue()) .'">';
        $field .= '<a class="upload-image-btn button button-primary">'.Translator::translate("Upload").'</a>';

        if($this->getDefaultValue() !== ''){
            $field .= '<button data-target-id="'.$id.'" class="upload-delete-btn button button-secondary">'.Translator::translate("Delete").'</button>';
        }

        $preview = ($this->getDefaultValue() !== '') ? '<img src="'.esc_url($this->getDefaultValue()).'"/>' : '';

        $field .= '</div>';
        $field .= '<div class="image-preview"><div class="image">'. $preview .'</div></div>';

        return $this->renderField($field);
    }
}
