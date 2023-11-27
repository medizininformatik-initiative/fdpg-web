<?php

namespace ACPT\Core\Generators\UserFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\User\UserMetaBoxFieldModel;
use ACPT\Utils\Wordpress\Translator;
use ACPT\Utils\Wordpress\WPAttachment;

class GalleryField extends AbstractUserField implements MetaFieldInterface
{
    public function render()
    {
	    $attachmentIds = [];
	    foreach ($this->getAttachments() as $index => $attachment){
		    $attachmentIds[] = $attachment->getId();
	    }

	    $this->enqueueAssets();

        $icon = 'bx:bx-images';
        $deleteButtonClass = ($this->getDefaultValue() !== '') ? '' : 'hidden';
        $defaultValue = $this->defaultValue();

        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.UserMetaBoxFieldModel::GALLERY_TYPE.'">';
        $field .= '<div class="btn-wrapper">';
	    $field .= '<input id="'.esc_attr($this->getIdName()).'_id" name="'. esc_html($this->getIdName()).'_id" type="hidden" value="' .implode(',', $attachmentIds).'">';
        $field .= '<input readonly '.$this->required().' id="'. esc_attr($this->getIdName()).'_copy" type="text" class="regular-text mr-1" value="'. $defaultValue .'">';
        $field .= '<div class="inputs-wrapper" data-target="'. esc_attr($this->getIdName()).'">';

        if(is_array($this->getDefaultValue())){
            foreach ($this->getDefaultValue() as $index => $value){
                $field .= '<input name="'. esc_attr($this->getIdName()).'[]" data-index="'.$index.'" type="hidden" value="'.$value.'">';
            }
        }

        $field .= '</div>';
        $field .= '<a class="upload-gallery-btn button">'.Translator::translate("Select images").'</a>';
        $field .= '<a data-target-id="'.esc_attr($this->getIdName()).'" class="upload-delete-btn button button-danger '.esc_attr($deleteButtonClass).'">'.Translator::translate("Delete all images").'</a>';

        $field .= '</div>';
        $field .= '<div class="gallery-preview image-preview" data-target="'. esc_attr($this->getIdName()).'">'. $this->getGalleryPreview() .'</div>';

        echo $this->renderField($icon, $field);
    }

	/**
	 * @return string|void
	 */
	private function defaultValue()
	{
		if(empty($this->getDefaultValue()) or !is_array($this->getDefaultValue())){
			return '';
		}

		return ( !empty($this->getDefaultValue()) ) ? esc_attr(implode(',', $this->getDefaultValue())) : '';
	}

	/**
	 * @return string
	 */
    private function getGalleryPreview()
    {
        $defaultGallery = $this->getDefaultValue();

        if($defaultGallery === ''){
            return '';
        }

        if(empty($defaultGallery)){
            return '';
        }

        $preview = '';

        foreach ($defaultGallery as $index => $image){
            $preview .= '<div class="image" data-index="'.$index.'" draggable="true"><img src="'.esc_url($image).'"/><div><a class="delete-gallery-img-btn" data-index="'.$index.'" href="#">'.Translator::translate("Delete").'</a></div></div>';
        }

        return $preview;
    }

	/**
	 * Enqueue necessary assets
	 */
	private function enqueueAssets()
	{
		wp_enqueue_script( 'html5sortable', plugin_dir_url( dirname( __FILE__ ) ) . '../../../assets/vendor/html5sortable/dist/html5sortable.min.js', [], '2.2.0', true);
	}
}