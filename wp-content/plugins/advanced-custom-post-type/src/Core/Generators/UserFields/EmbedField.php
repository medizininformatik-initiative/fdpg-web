<?php

namespace ACPT\Core\Generators\UserFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\User\UserMetaBoxFieldModel;

class EmbedField extends AbstractUserField implements MetaFieldInterface
{
    public function render()
    {
        $icon = 'bx:bx-extension';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.UserMetaBoxFieldModel::EMBED_TYPE.'">';
        $field .= '<input '.$this->required().' id="'.esc_attr($this->getIdName()).'" name="'. esc_attr($this->getIdName()).'" type="url" class="regular-text embed" value="'.esc_attr
                ($this->getDefaultValue())
                .'">';
        $field .= $this->getPreview();

        echo $this->renderField($icon, $field);
    }

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