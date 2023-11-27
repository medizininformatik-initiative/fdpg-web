<?php

namespace ACPT\Core\Generators\UserFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\User\UserMetaBoxFieldModel;

class EditorField extends AbstractUserField implements MetaFieldInterface
{
	/**
	 * @return mixed
	 */
    public function render()
    {
	    $hideIcon = $this->getAdvancedOption('hide_icon');
        ?>
        <tr>
            <th>
                <div class="acpt-admin-meta-label-wrapper">
                    <?php if(empty($hideIcon) or $hideIcon == "0"): ?>
                        <div class="acpt-admin-meta-icon">
                            <span class="icon">
                                <span class="iconify" style="color: white;" data-width="24" data-height="24" data-icon="bx:bx-font-color"></span>
                            </span>
                        </div>
                    <?php endif; ?>
                    <label for="<?php echo $this->getIdName() ?>">
                        <?php
                        echo $this->name;
                        echo ($this->isRequired) ? '<span class="required">*</span>': '';
                        ?>
                    </label>
                </div>
            </th>
            <td>
                <div class="acpt-admin-meta-field meta-editor">
                    <input type="hidden" name="<?php echo esc_html($this->getIdName()); ?>_type" value="<?php echo UserMetaBoxFieldModel::EDITOR_TYPE; ?>">
                    <?php wp_editor(
                            $this->getDefaultValue(),
                            esc_attr($this->getIdName()),
                            [
                                    'textarea_name' => esc_attr($this->getIdName()),
                                    'tinymce' => true,
                                    'media_buttons' => true,
                            ]);
                    ?>
                </div>
                <br>
                <?php if($this->description !== null and $this->description !== ''):?>
                    <span class="description">
                        <?php echo $this->description; ?>
                    </span>
                <?php endif; ?>
            </td>
        </tr>
        <?php
    }
}