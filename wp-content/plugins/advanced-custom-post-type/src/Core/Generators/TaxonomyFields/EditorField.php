<?php

namespace ACPT\Core\Generators\TaxonomyFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\Taxonomy\TaxonomyMetaBoxFieldModel;

class EditorField extends AbstractTaxonomyField implements MetaFieldInterface
{
    public function render()
    {
	    $hideIcon = $this->getAdvancedOption('hide_icon');
        ?>
        <div class="taxonomy-meta-field">
            <?php if(empty($hideIcon) or $hideIcon == "0"): ?>
                <div class="taxonomy-meta-field-icon">
                    <span class="iconify" style="color: white;" data-width="24" data-height="24" data-icon="bx:bx-font-color"></span>
                </div>
            <?php endif; ?>
            <div class="form-field">
                <label for="<?php echo esc_attr($this->getIdName()); ?>">
                    <?php
                    echo esc_html($this->displayLabel());
                    echo ($this->metaBoxFieldModel->isRequired()) ? '<span class="required">*</span>': '';
                    ?>
                </label>
                <div class="acpt-admin-meta-field meta-editor">
                    <input type="hidden" name="<?php echo esc_html($this->getIdName()); ?>_type" value="<?php echo TaxonomyMetaBoxFieldModel::EDITOR_TYPE; ?>">
                    <?php
                    $defaultValue = $this->getDefaultValue() !== '' ? $this->getDefaultValue() : '';

                    wp_editor(
                        $defaultValue,
                        esc_attr($this->getIdName()),
                        [
                            'textarea_name' => esc_attr($this->getIdName()),
                            'tinymce' => true,
                            'media_buttons' => true,
                        ]);
                    ?>
                </div>
                <?php if($this->metaBoxFieldModel->getDescription()): ?>
                    <small><?php echo esc_html($this->metaBoxFieldModel->getDescription()); ?></small>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}