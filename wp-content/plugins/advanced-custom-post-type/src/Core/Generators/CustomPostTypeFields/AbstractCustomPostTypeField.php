<?php

namespace ACPT\Core\Generators\CustomPostTypeFields;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxModel;
use ACPT\Core\Models\MetaField\MetaBoxFieldRelationshipModel;
use ACPT\Costants\Relationships;
use ACPT\Utils\Data\Sanitizer;
use ACPT\Utils\Wordpress\WPAttachment;

abstract class AbstractCustomPostTypeField
{
    /**
     * @var int
     */
    protected $postId;

    /**
     * @var AbstractMetaBoxModel
     */
    protected $metaBoxModel;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $isRequired;

    /**
     * @var int
     */
    protected $sort;

    /**
     * @var null
     */
    protected $defaultValue;

    /**
     * @var null
     */
    protected $description;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var array
     */
    protected $relations;

    /**
     * @var array
     */
    protected $children;

    /**
     * @var array
     */
    protected $advancedOptions;

	/**
	 * @var array
	 */
    protected $blocks;

    /**
     * AbstractField constructor.
     * @param $postId
     * @param AbstractMetaBoxModel $metaBoxModel
     * @param $id
     * @param $name
     * @param $sort
     * @param bool $isRequired
     * @param null $defaultValue
     * @param null $description
     * @param array $options
     * @param array $relations
     * @param array $children
     * @param array $advancedOptions
     * @param array $blocks
     */
    public function __construct(
        $postId,
	    AbstractMetaBoxModel $metaBoxModel,
        $id,
        $name,
        $sort,
        $isRequired = false,
        $defaultValue = null,
        $description = null,
        $options = [],
        $relations = [],
        $children = [],
        $advancedOptions = [],
	    $blocks = []
    )
    {
        $this->postId = (int)$postId;
        $this->metaBoxModel = $metaBoxModel;
        $this->id = $id;
        $this->name = $name;
        $this->sort = (int)$sort;
        $this->isRequired = $isRequired;
        $this->defaultValue = $defaultValue;
        $this->description = $description;
        $this->options = $options;
        $this->relations = $relations;
        $this->children = $children;
        $this->advancedOptions = $advancedOptions;
        $this->blocks = $blocks;
    }

    /**
     * @return string
     */
    protected function getIdName()
    {
        $idName = Strings::toDBFormat($this->metaBoxModel->getName()) . '_' . Strings::toDBFormat($this->name);

        return esc_html($idName);
    }

    /**
     * @return mixed|null
     */
    protected function getDefaultValue()
    {
        $value = get_post_meta($this->postId, $this->getIdName(), true);

        return ($value !== null and $value !== '' ) ? $value : $this->defaultValue;
    }

	/**
	 * @return WPAttachment[]
	 */
    protected function getAttachments()
    {
    	$attachments = [];
	    $id = get_post_meta($this->postId, $this->getIdName().'_id', true);
	    $url = get_post_meta($this->postId, $this->getIdName(), true);

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
        return ($this->isRequired) ? 'required="required"' : '';
    }

    /**
     * Render the field in the UI
     *
     * @param      $icon
     * @param      $field
     * @param null $relation
     */
    protected function renderField($icon, $field, $relation = null)
    {
        $headlineAlignment = $this->getAdvancedOption('headline') ? $this->getAdvancedOption('headline') : 'left';
	    $width = $this->getAdvancedOption('width') ? $this->getAdvancedOption('width') : '100';
	    $widthStyle = $width.'%';
        ?>
        <div class="acpt-admin-meta-wrapper" style="width: <?php echo $widthStyle; ?>;">
            <div class="acpt-admin-meta sort-<?php echo esc_attr($this->sort); ?>">
		        <?php
                $hideIcon = $this->getAdvancedOption('hide_icon');

                if(empty($hideIcon) or $hideIcon == "0"){
	                echo $this->renderIcon($icon);
                }

                echo $this->renderFieldWrapper($field, $headlineAlignment, $relation);
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
        <div class="acpt-admin-meta-icon">
            <span class="icon">
                <span class="iconify" style="color: white;" data-width="24" data-height="24" data-icon="<?php echo esc_attr($icon); ?>"></span>
            </span>
        </div>
        <?php
    }

	/**
	 * @param $field
	 * @param string $alignment
	 * @param null $relation
	 */
	private function renderFieldWrapper($field, $alignment = 'left', $relation = null)
	{
	    ?>
        <div class="acpt-admin-meta-field-wrapper <?php echo $alignment; ?>">
            <?php
            if($alignment === 'top' or $alignment === 'left'){
	            echo $this->renderFieldLabel($relation).$this->renderFieldValue($field);
            } elseif($alignment === 'right'){
	            echo $this->renderFieldValue($field).$this->renderFieldLabel($relation);
            } elseif($alignment === 'none'){
	            echo $this->renderFieldValue($field);
            }
            ?>
        </div>
        <?php
	}

	/**
	 * @param null $relation
	 */
    private function renderFieldLabel($relation = null)
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
                <span class="description">
                        <?php echo esc_html($this->description); ?>
                    </span>
		    <?php endif; ?>
		    <?php if($relation): ?>
                <div class="relation">
				    <?php echo $this->displayRelation($relation); ?>
                </div>
		    <?php endif; ?>
        </div>
        <?php
    }

	/**
	 * @param $field
	 */
    private function renderFieldValue($field)
    {
        ?>
        <div class="acpt-admin-meta-field">
            <input type="hidden" name="meta_fields[]" value="<?php echo esc_html($this->getIdName()); ?>">
            <input type="hidden" name="meta_fields[]" value="<?php echo esc_html($this->getIdName()); ?>_type">
            <input type="hidden" name="<?php echo esc_attr($this->getIdName()); ?>_required" value="<?php echo esc_attr($this->isRequired); ?>">
		    <?php echo Sanitizer::escapeField($field); ?>
        </div>
        <?php
    }

	/**
	 * @param $key
	 *
	 * @return mixed|null
	 */
	protected function getAdvancedOption($key)
	{
		foreach ($this->advancedOptions as $advancedOption){
			if ($advancedOption['key'] === $key and $advancedOption['value'] !== '') {
				return $advancedOption['value'];
			}
		}

		return null;
	}

    /**
     * @return string
     */
    protected function displayLabel()
    {
        foreach ($this->advancedOptions as $advancedOption){
            if ($advancedOption['key'] === 'label' and $advancedOption['value'] !== '') {
                return $advancedOption['value'];
            }
        }

        return $this->name;
    }

    /**
     * @return string
     */
    protected function getGridCssClass()
    {
        foreach ($this->advancedOptions as $advancedOption){
            if ($advancedOption['key'] === 'columns' and $advancedOption['value'] !== '') {
                return "grid-".$advancedOption['value'];
            }
        }

        return '';
    }

    /**
     * @param string $relation
     *
     * @return string
     */
    private function displayRelation($relation)
    {
        switch ($relation){
            case Relationships::ONE_TO_ONE_UNI:
                return '<span class="relation-label">1</span><span class="relation-sign">⟶</span><span class="relation-label">1</span></span>';

            case Relationships::ONE_TO_ONE_BI:
                return '<span class="relation-label">1</span><span class="relation-sign">⟷</span><span class="relation-label">1</span></span>';

            case Relationships::ONE_TO_MANY_UNI:
                return '<span class="relation-label">1</span><span class="relation-sign">⟶</span><span class="relation-label">M</span></span>';

            case Relationships::ONE_TO_MANY_BI:
                return '<span class="relation-label">1</span><span class="relation-sign">⟷</span><span class="relation-label">M</span></span>';

            case Relationships::MANY_TO_ONE_UNI:
                return '<span class="relation-label">M</span><span class="relation-sign">⟶</span><span class="relation-label">1</span></span>';

            case Relationships::MANY_TO_ONE_BI:
                return '<span class="relation-label">M</span><span class="relation-sign">⟷</span><span class="relation-label">1</span></span>';

            case Relationships::MANY_TO_MANY_UNI:
                return '<span class="relation-label">M</span><span class="relation-sign">⟶</span><span class="relation-label">M</span></span>';

            case Relationships::MANY_TO_MANY_BI:
                return '<span class="relation-label">M</span><span class="relation-sign">⟷</span><span class="relation-label">M</span></span>';
        }


        return $relation;
    }
}