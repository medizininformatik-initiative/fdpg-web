<?php

namespace ACPT\Core\Generators\OptionPageFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\OptionPage\OptionPageMetaBoxFieldModel;
use ACPT\Utils\Wordpress\WPUtils;

class EditorField extends AbstractOptionPageField implements MetaFieldInterface
{
    public function render()
    {
    	$defaultValue = $this->getDefaultValue() !== '' ? $this->getDefaultValue() : 'Start typing...';
	    $id = $this->generateRandomId();

	    $icon = 'bx:bx-font-color';
	    $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.OptionPageMetaBoxFieldModel::EDITOR_TYPE.'">';
	    $field .= WPUtils::renderWpEditor($defaultValue, $id, [
		    'textarea_name' => esc_attr($this->getIdName()),
		    'tinymce' => true,
		    'media_buttons' => true,
		    'tabindex' => 1,
		    'quicktags' => false
	    ]);

	    return $this->renderField($icon, $field);
    }
}