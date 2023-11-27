<?php

namespace ACPT\Core\Generators\UserFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\User\UserMetaBoxFieldModel;
use ACPT\Utils\Wordpress\Translator;
use ACPT\Utils\Wordpress\Users;

class UserField extends AbstractUserField implements MetaFieldInterface
{
    public function render()
    {
        $icon = 'bx:bx-user';
        $field = '<input type="hidden" name="'. esc_attr($this->getIdName()).'_type" value="'.UserMetaBoxFieldModel::USER_TYPE.'">';
        $field .= '<select '.$this->required().' id="'.esc_attr($this->getIdName()).'" name="'. esc_attr($this->getIdName()).'" class="acpt-select2 regular-text">';
        $field .= '<option value="">'.Translator::translate("--Select--").'</option>';

        foreach(Users::getList($this->userId) as $id => $user){
            $selected = ($id === (int)$this->getDefaultValue()) ? 'selected="selected"' : '';
            $field .= '<option '.$selected.' value="'.$id.'">'.esc_html($user).'</option>';
        }

        $field .= '</select>';

        echo $this->renderField($icon, $field);
    }
}