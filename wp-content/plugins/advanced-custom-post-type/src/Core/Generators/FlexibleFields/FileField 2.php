<?php

namespace ACPT\Core\Generators\FlexibleFields;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Utils\Wordpress\Translator;
use ACPT\Utils\Wordpress\WPAttachment;

class FileField extends AbstractFlexibleField implements FlexibleFieldInterface
{
    public function render()
    {
        $id = $this->generateRandomId();
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'[type]" value="'.AbstractMetaBoxFieldModel::FILE_TYPE.'">';
        $field .= '<input type="hidden" name="'. esc_attr($this->getIdName()).'[original_name]" value="'.$this->fieldModel->getName().'">';
	    $field .= '<input type="hidden" name="meta_fields[]" value="'.esc_attr($this->getIdName()).'[label]">';

	    $attachmentId = (isset($this->getAttachments()[0])) ? $this->getAttachments()[0]->getId() : '';

	    $field .= '<input id="'.esc_attr($this->getIdName()).'[label]'.'" name="'. esc_attr($this->getIdName()).'[label]' .'" type="text" class="regular-text acpt-admin-meta-field-input mb-4" value="'.esc_attr($this->getDefaultLabel()) .'" placeholder="Enter text link">';
        $field .= '<div class="btn-wrapper">';
	    $field .= '<input id="'.$id.'_id" type="hidden" name="'. esc_html($this->getIdName()).'[id]" value="' .$attachmentId.'">';
        $field .= '<input readonly '.$this->required().' id="'.$id.'" name="'. esc_html($this->getIdName()).'[value]" type="text" class="regular-text acpt-admin-meta-field-input" value="' .esc_attr
                ($this->getDefaultValue()) .'">';
        $field .= '<button class="upload-file-btn button button-primary">'.Translator::translate("Upload").'</button>';

        if($this->getDefaultValue() !== ''){
            $field .= '<button data-target-id="'.$id.'" class="file-delete-btn button button-secondary">'.Translator::translate("Delete").'</button>';
        }

	    $preview = (!empty($this->getDefaultValue()) and $this->getDefaultValue() !== '') ? '<div class="preview-file"><span>'.Translator::translate("Preview").'</span><a target="_blank" href="'.esc_url($this->getDefaultValue()).'">'.$this->getDefaultLabel().'</a></div>' : '';

        $field .= '</div>';
        $field .= '<div class="file-preview"><div class="file">'. $preview .'</div></div>';


        return $this->renderField($field);
    }

	/**
	 * @return string
	 */
	private function getDefaultLabel()
	{
		if(!isset($this->id)){
			return 'Enter download text link';
		}

		$data = $this->getParentData();
		$key = Strings::toDBFormat($this->fieldModel->getName());

		return (isset($data[$key]) and isset($data[$key][$this->elementIndex]) and isset($data[$key][$this->elementIndex]['label'])) ? $data[$key][$this->elementIndex]['label'] : 'Enter download text link';
	}
}
