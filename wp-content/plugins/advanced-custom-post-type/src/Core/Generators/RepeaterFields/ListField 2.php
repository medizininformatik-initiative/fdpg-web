<?php

namespace ACPT\Core\Generators\RepeaterFields;

use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Utils\Wordpress\Translator;

class ListField extends AbstractRepeaterField implements RepeaterFieldInterface
{
    public function render()
    {
        $id = $this->generateRandomId();
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'[type]" value="'.CustomPostTypeMetaBoxFieldModel::LIST_TYPE.'">';
        $field .= '<input type="hidden" name="'. esc_attr($this->getIdName()).'[original_name]" value="'.$this->fieldModel->getName().'">';
        $field .= '<div class="list-wrapper">';
        $field .= '<div class="list-element">
                <input '.$this->required().' id="'.$id.'" name="'. esc_html($this->getIdName()).'[value][]" type="text" class="acpt-admin-meta-field-input" value="' .esc_attr($this->defaultValue(0)) .'">
                </div>';

        if(is_array($this->getDefaultValue())){
            for ($i = 1; $i < count($this->getDefaultValue()); $i++){
                $field .= '<div class="list-element"><input '.$this->required().' id="'.esc_attr($this->getIdName()).'_'.$i.'" name="'. esc_attr($this->getIdName()).'[value][]" type="text" class="acpt-admin-meta-field-input list-element" value="' .esc_attr($this->defaultValue($i)).'">';
                $field .= '<a class="list-remove-element button-danger" data-target-id="'.esc_attr($this->getIdName()).'_'.$i.'" href="#">'.Translator::translate('Remove element').'</a></div>';
            }
        }

        $field .= '</div>';
        $field .= '<a id="list-add-element" href="#" class="button small">'.Translator::translate('Add element').'</a>';

        return $this->renderField($field);
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
