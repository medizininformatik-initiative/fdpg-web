<?php

namespace ACPT\Core\Generators\TaxonomyFields;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Utils\Data\Sanitizer;
use ACPT\Utils\Wordpress\WPAttachment;

abstract class AbstractTaxonomyField
{
    /**
     * @var AbstractMetaBoxFieldModel
     */
    protected AbstractMetaBoxFieldModel $metaBoxFieldModel;

    /**
     * @var string
     */
    protected $taxonomy;

    /**
     * @var null
     */
    protected $termId;

    /**
     * TaxonomyMetaBoxFieldGenerator constructor.
     * @param AbstractMetaBoxFieldModel $metaBoxFieldModel
     * @param string $taxonomy
     * @param null $termId
     */
    public function __construct(AbstractMetaBoxFieldModel $metaBoxFieldModel, $taxonomy, $termId = null)
    {
        $this->metaBoxFieldModel = $metaBoxFieldModel;
        $this->taxonomy = $taxonomy;
        $this->termId = $termId;
    }

    /**
     * @return string
     */
    protected function getIdName()
    {
        $idName = Strings::toDBFormat($this->metaBoxFieldModel->getMetaBox()->getName()) . '_' . Strings::toDBFormat($this->metaBoxFieldModel->getName());

        return esc_html($idName);
    }

    /**
     * @return mixed|null
     */
    protected function getDefaultValue()
    {
        if(null === $this->termId){
            return null;
        }

        $value = get_term_meta( $this->termId, $this->getIdName(), true );

        return ($value !== null and $value !== '' ) ? $value : $this->metaBoxFieldModel->getDefaultValue();
    }

	/**
	 * @return WPAttachment[]
	 */
	protected function getAttachments()
	{
		$attachments = [];
		$id = get_term_meta($this->termId, $this->getIdName().'_id', true);
		$url = get_term_meta($this->termId, $this->getIdName(), true);

		// from id
		if(!empty($id)){
			$ids =  explode(',', $id);

			foreach ($ids as $_id){
				$attachments[] = WPAttachment::fromId($_id);
			}

			return $attachments;
		}

		// from url
		if(!empty($url)){
			if(is_array($url)){
				foreach ($url as $_url){
					$attachments[] = WPAttachment::fromUrl($_url);
				}

				return $attachments;
			}

			$attachments[] = WPAttachment::fromUrl($url);
		}

		return $attachments;
	}

    /**
     * @return string
     */
    protected function required()
    {
        return ($this->metaBoxFieldModel->isRequired()) ? 'required="required"' : '';
    }

	/**
	 * @param $key
	 *
	 * @return mixed|null
	 */
	protected function getAdvancedOption($key)
	{
		foreach ($this->metaBoxFieldModel->getAdvancedOptions() as $advancedOption){
			if ($advancedOption->getKey() === $key and $advancedOption->getValue() !== '') {
				return $advancedOption->getValue();
			}
		}

		return null;
	}

    /**
     * @param $icon
     * @param $field
     */
    protected function renderField($icon, $field)
    {
	    $hideIcon = $this->getAdvancedOption('hide_icon');
	    $headlineAlignment = $this->getAdvancedOption('headline') ? $this->getAdvancedOption('headline') : 'left';
	    $width = $this->getAdvancedOption('width') ? $this->getAdvancedOption('width') : '100';
	    $widthStyle = $width.'%';
        ?>
        <div class="taxonomy-meta-field-wrapper" style="width: <?php echo $widthStyle; ?>;">
            <div class="taxonomy-meta-field">
		        <?php
	            if(empty($hideIcon) or $hideIcon == "0"){
		            echo $this->renderIcon($icon);
	            }

                echo $this->renderFieldWrapper($field, $headlineAlignment);
                ?>
            </div>
        </div>
        <?php
    }

	/**
	 * @param $icon
	 */
	private function renderIcon($icon)
    {
        ?>
        <div class="taxonomy-meta-field-icon">
            <span class="iconify" style="color: white;" data-width="18" data-height="18" data-icon="<?php echo $icon; ?>"></span>
        </div>
        <?php
    }

	private function renderFieldWrapper($field, $alignment = 'left')
	{
        ?>
        <div class="form-field <?php echo $alignment; ?>">
	        <?php
	        if($alignment === 'top' or $alignment === 'left'){
		        echo $this->renderFieldLabel().$this->renderFieldValue($field);
	        } elseif($alignment === 'right'){
		        echo $this->renderFieldValue($field).$this->renderFieldLabel();
	        } elseif($alignment === 'none'){
		        echo $this->renderFieldValue($field);
	        }
	        ?>
        </div>
        <?php
	}

	private function renderFieldLabel()
	{
		?>
		<label for="<?php echo esc_attr($this->getIdName()); ?>">
			<?php
			echo esc_html($this->displayLabel());
			echo ($this->metaBoxFieldModel->isRequired()) ? '<span class="required">*</span>' : '';
			?>
		</label>
		<?php
	}

	private function renderFieldValue($field)
	{
		echo Sanitizer::escapeField($field);
		if($this->metaBoxFieldModel->getDescription()): ?>
			<p class="description">
				<?php echo esc_html($this->metaBoxFieldModel->getDescription()); ?>
			</p>
		<?php endif;
	}

    /**
     * @return string
     */
    protected function displayLabel()
    {
        foreach ($this->metaBoxFieldModel->getAdvancedOptions() as $advancedOption){
            if ($advancedOption->getKey() === 'label' and $advancedOption->getValue() !== '') {
                return $advancedOption->getValue();
            }
        }

        return $this->metaBoxFieldModel->getName();
    }

    /**
     * @return string
     */
    protected function getGridCssClass()
    {
        foreach ($this->metaBoxFieldModel->getAdvancedOptions() as $advancedOption){
            if ($advancedOption->getKey() === 'width' and $advancedOption->getValue() !== '') {
                return "grid-".$advancedOption->getValue();
            }
        }

        return '';
    }
}