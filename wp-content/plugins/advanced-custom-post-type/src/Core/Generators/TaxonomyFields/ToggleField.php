<?php

namespace ACPT\Core\Generators\TaxonomyFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\Taxonomy\TaxonomyMetaBoxFieldModel;

class ToggleField extends AbstractTaxonomyField implements MetaFieldInterface
{
    public function render()
    {
        $checked = ($this->getToggleValue() == 1) ? 'checked' : '';

        $icon = 'bx:bx-toggle-right';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.TaxonomyMetaBoxFieldModel::TOGGLE_TYPE.'">';
        $field .= '<input type="hidden" id="'.esc_attr($this->getIdName()).'" name="'.esc_html($this->getIdName()).'" value="'.esc_attr($this->getToggleValue()).'">';
        $field .= '<input id="'.esc_attr($this->getIdName()).'" type="checkbox" class="wppd-ui-toggle" '.$checked.'>';

        echo $this->renderField($icon, $field);
    }

    /**
     * @return int
     */
    private function getToggleValue()
    {
        if($this->getDefaultValue()){
            return $this->getDefaultValue();
        }

        return 0;
    }
}

