<?php

namespace ACPT\Core\Generators\CustomPostTypeFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Utils\Wordpress\Translator;

class ListField extends AbstractCustomPostTypeField implements MetaFieldInterface
{
    public function render()
    {
        $icon = 'bx:bx-list-ul';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.CustomPostTypeMetaBoxFieldModel::LIST_TYPE.'">';
        $field .= '<div class="list-wrapper '.$this->getGridCssClass().'">';
        $field .= '<div class="list-element"><input '.$this->required().' id="'.esc_attr($this->getIdName()).'_0" name="'. esc_html($this->getIdName()).'[]" type="text" class="acpt-admin-meta-field-input" value="'
                .esc_attr($this->defaultValue(0)).'"></div>';

        if(is_array($this->getDefaultValue())){
            for ($i = 1; $i < count($this->getDefaultValue()); $i++){
                $field .= '<div class="list-element"><input '.$this->required().' id="'.esc_attr($this->getIdName()).'_'.$i.'" name="'. esc_attr($this->getIdName()).'[]" type="text" class="acpt-admin-meta-field-input list-element" value="'
                        .esc_attr($this->defaultValue($i)).'">';
                $field .= '<a class="list-remove-element button-danger" data-target-id="'.esc_attr($this->getIdName()).'_'.$i.'" href="#">'.Translator::translate("Delete").'</a></div>';
            }
        }

        $field .= '</div>';
        $field .= '<a id="list-add-element" href="#" class="button small">'.Translator::translate('Add element').'</a>';

        echo $this->renderField($icon, $field);
    }

    /**
     * @param $index
     *
     * @return string
     */
    private function defaultValue($index)
    {
        return isset($this->getDefaultValue()[$index]) ? $this->getDefaultValue()[$index] : '';
    }
}