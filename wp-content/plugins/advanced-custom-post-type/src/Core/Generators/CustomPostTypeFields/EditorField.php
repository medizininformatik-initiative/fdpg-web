<?php

namespace ACPT\Core\Generators\CustomPostTypeFields;

use ACPT\Core\Generators\Contracts\MetaFieldInterface;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;

class EditorField extends AbstractCustomPostTypeField implements MetaFieldInterface
{
    public function render()
    {
	    $hideIcon = $this->getAdvancedOption('hide_icon');
	    $headlineAlignment = $this->getAdvancedOption('headline') ? $this->getAdvancedOption('headline') : 'left';
	    $width = $this->getAdvancedOption('width') ? $this->getAdvancedOption('width') : '100';
	    $widthStyle = $width.'%';
        ?>
        <div class="acpt-admin-meta-wrapper" style="width: <?php echo $widthStyle; ?>;">
            <div class="acpt-admin-meta sort-<?php echo esc_attr($this->sort); ?>">
                <?php if(empty($hideIcon) or $hideIcon == "0"): ?>
                    <div class="acpt-admin-meta-icon">
                        <span class="icon">
                            <span class="iconify" style="color: white;" data-width="24" data-height="24" data-icon="bx:bx-font-color"></span>
                        </span>
                    </div>
                <?php endif; ?>
                <div class="acpt-admin-meta-field-wrapper <?php echo $headlineAlignment; ?>">
	                <?php
	                if($headlineAlignment === 'top' or $headlineAlignment === 'left'){
		                echo $this->renderLabel().$this->renderEditor();
	                } elseif($headlineAlignment === 'right'){
		                echo $this->renderEditor().$this->renderLabel();
	                } elseif($headlineAlignment === 'none'){
		                echo $this->renderEditor();
	                }
	                ?>
                </div>
            </div>
        </div>
        <?php
    }

    private function renderLabel()
    {
        ?>
        <div class="acpt-admin-meta-label">
            <label for="<?php echo esc_attr($this->getIdName()); ?>">
			    <?php
			    echo esc_html($this->displayLabel());
			    echo ($this->isRequired) ? '<span class="required">*</span>': '';
			    ?>
            </label>
		    <?php if($this->description): ?>
                <small><?php echo esc_html($this->description); ?></small>
		    <?php endif; ?>
        </div>
        <?php
    }

	/**
	 * render the editor field
	 */
    private function renderEditor()
    {
        ?>
        <div class="acpt-admin-meta-field meta-editor">
            <input type="hidden" name="meta_fields[]" value="<?php echo esc_attr($this->getIdName()); ?>">
            <input type="hidden" name="meta_fields[]" value="<?php echo esc_attr($this->getIdName()); ?>_type">
            <input type="hidden" name="<?php echo esc_html($this->getIdName()); ?>_type" value="<?php echo CustomPostTypeMetaBoxFieldModel::EDITOR_TYPE; ?>">
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
        <?php
    }
}