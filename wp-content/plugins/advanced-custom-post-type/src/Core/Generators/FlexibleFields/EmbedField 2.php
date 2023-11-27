<?php

namespace ACPT\Core\Generators\FlexibleFields;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;

class EmbedField extends AbstractFlexibleField implements FlexibleFieldInterface
{
    public function render()
    {
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'[type]" value="'.AbstractMetaBoxFieldModel::EMBED_TYPE.'">';
        $field .= '<input type="hidden" name="'. esc_attr($this->getIdName()).'[original_name]" value="'.$this->fieldModel->getName().'">';
        $field .= '<input '.$this->required().' id="'.esc_attr($this->getIdName()).'[value]" name="'. esc_attr($this->getIdName()).'[value]" type="url" class="acpt-admin-meta-field-input embed" value="'.esc_attr($this->getDefaultValue()).'">';
        $field .= $this->getPreview();

        return $this->renderField($field);
    }

    private function getPreview()
    {
        if( $this->getDefaultValue() === '' ){
            return '';
        }

        $preview = '<div class="embed-preview">';
        $preview .= '<div class="embed">';
        $preview .= (new \WP_Embed())->shortcode([
                'width' => 180,
                'height' => 135,
        ], esc_attr($this->getDefaultValue()));
        $preview .= '</div>';
        $preview .= '</div>';

        return $preview;
    }
}
