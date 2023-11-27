<?php

namespace ACPT\Core\Generators\CustomPostTypeFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;

class EmbedField extends AbstractCustomPostTypeField implements MetaFieldInterface
{
    public function render()
    {
        $icon = 'bx:bx-extension';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.CustomPostTypeMetaBoxFieldModel::EMBED_TYPE.'">';
        $field .= '<input '.$this->required().' id="'.esc_attr($this->getIdName()).'" name="'. esc_attr($this->getIdName()).'" type="url" class="acpt-admin-meta-field-input embed" value="'.esc_attr($this->getDefaultValue()).'">';
        $field .= $this->getPreview();

        echo $this->renderField($icon, $field);
    }

    /**
     * @return string
     */
    private function getPreview()
    {
	    if( empty($this->getDefaultValue())){
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