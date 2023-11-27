<?php

namespace ACPT\Core\Generators\TaxonomyFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\Taxonomy\TaxonomyMetaBoxFieldModel;
use ACPT\Utils\Wordpress\Translator;
use ACPT\Utils\Wordpress\WPAttachment;

class VideoField extends AbstractTaxonomyField implements MetaFieldInterface
{
    public function render()
    {
	    $attachmentId = (isset($this->getAttachments()[0])) ? $this->getAttachments()[0]->getId() : '';

        $icon = 'bx:bx-video';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.TaxonomyMetaBoxFieldModel::VIDEO_TYPE.'">';
	    $field .= '<div class="media-wrapper">';
        $field .= '<div class="btn-wrapper">';
	    $field .= '<input id="'.esc_attr($this->getIdName()).'_id" name="'. esc_html($this->getIdName()).'_id" type="hidden" value="' .$attachmentId.'">';
        $field .= '<input readonly '.$this->required().' id="'.esc_attr($this->getIdName()).'" name="'. esc_attr($this->getIdName()).'" type="text" class="acpt-admin-meta-field-input" value="' .esc_attr($this->getDefaultValue()) .'">';
        $field .= '<a class="upload-video-btn button button-primary">'.Translator::translate("Upload").'</a>';

        if($this->getDefaultValue() !== ''){
            $field .= '<a data-target-id="'.esc_attr($this->getIdName()).'" class="upload-delete-btn button button-secondary">'.Translator::translate("Delete").'</a>';
        }

        $preview = (!empty($this->getDefaultValue())) ? $this->getPreviewVideo() : '';

        $field .= '</div>';
	    $field .= '<div class="image-preview"><div class="image">'. $preview .'</div></div>';
	    $field .= '</div>';

	    echo $this->renderField($icon, $field);
    }

    /**
     * @return string
     */
    private function getPreviewVideo()
    {
        return '<video controls>
              <source src="'.esc_url($this->getDefaultValue()).'" type="video/mp4">
            Your browser does not support the video tag.
            </video>';
    }
}