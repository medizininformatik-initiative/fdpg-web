<?php

namespace ACPT\Core\Generators\FlexibleFields;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Utils\Wordpress\WPUtils;

class EditorField extends AbstractFlexibleField implements FlexibleFieldInterface
{
    public function render()
    {
        $defaultValue = $this->getDefaultValue() !== '' ? $this->getDefaultValue() : 'Start typing...';
        $id = $this->generateRandomId();
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'[type]" value="'.AbstractMetaBoxFieldModel::EDITOR_TYPE.'">';
        $field .= '<input type="hidden" name="'. esc_attr($this->getIdName()).'[original_name]" value="'.$this->fieldModel->getName().'">';
        $field .= WPUtils::renderWpEditor($defaultValue, $id, [
                'textarea_name' => esc_attr($this->getIdName()).'[value]',
                'tinymce' => true,
                'media_buttons' => true,
                'tabindex' => $this->elementIndex,
                'quicktags' => false
        ]);

        return $this->renderField($field);
    }
}
