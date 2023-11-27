<?php

namespace ACPT\Core\Models\Abstracts;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Helper\Uuid;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxModel;
use ACPT\Core\Models\MetaField\MetaBoxFieldAdvancedOptionModel;
use ACPT\Core\Models\MetaField\MetaBoxFieldBlockModel;
use ACPT\Core\Models\MetaField\MetaBoxFieldOptionModel;
use ACPT\Core\Models\MetaField\MetaBoxFieldRelationshipModel;
use ACPT\Core\Models\MetaField\MetaBoxFieldVisibilityModel;

/**
 * AbstractMetaBoxFieldModel
 *
 * @since      1.0.140
 * @package    advanced-custom-post-type
 * @subpackage advanced-custom-post-type/core
 * @author     Mauro Cassani <maurocassani1978@gmail.com>
 */
abstract class AbstractMetaBoxFieldModel extends AbstractModel
{
    const ADDRESS_TYPE = 'Address';
    const COLOR_TYPE = 'Color';
    const CHECKBOX_TYPE = 'Checkbox';
    const CURRENCY_TYPE = 'Currency';
    const DATE_TYPE = 'Date';
    const DATE_RANGE_TYPE = 'DateRange';
    const EDITOR_TYPE = 'Editor';
    const EMAIL_TYPE = 'Email';
    const EMBED_TYPE = 'Embed';
    const FILE_TYPE = 'File';
    const HTML_TYPE = 'HTML';
    const FLEXIBLE_CONTENT_TYPE = 'FlexibleContent';
    const GALLERY_TYPE = 'Gallery';
    const ICON_TYPE = 'Icon';
    const IMAGE_TYPE = 'Image';
    const LENGTH_TYPE = 'Length';
    const LIST_TYPE = 'List';
    const NUMBER_TYPE = 'Number';
    const POST_TYPE = 'Post';
    const PHONE_TYPE = 'Phone';
    const REPEATER_TYPE = 'Repeater';
    const RADIO_TYPE = 'Radio';
    const SELECT_TYPE = 'Select';
    const SELECT_MULTI_TYPE = 'SelectMulti';
    const TEXT_TYPE = 'Text';
    const TEXTAREA_TYPE = 'Textarea';
    const TIME_TYPE = 'Time';
    const TOGGLE_TYPE = 'Toggle';
    const VIDEO_TYPE = 'Video';
    const WEIGHT_TYPE = 'Weight';
    const URL_TYPE = 'Url';
    const USER_TYPE = 'User';
    const USER_MULTI_TYPE = 'UserMulti';

    /**
     * @var CustomPostTypeMetaBoxModel
     */
    protected $metaBox;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $defaultValue;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var bool
     */
    protected $showInArchive;

    /**
     * @var bool
     */
    protected $required;

	/**
	 * @var bool
	 */
	protected $quickEdit;

	/**
	 * @var bool
	 */
	protected $filterableInAdmin;

    /**
     * @var int
     */
    protected $sort;

    /**
     * @var MetaBoxFieldAdvancedOptionModel[]
     */
    protected $advancedOptions = [];

    /**
     * @var MetaBoxFieldOptionModel[]
     */
    protected $options = [];

    /**
     * @var MetaBoxFieldRelationshipModel[]
     */
    protected $relations = [];

    /**
     * @var CustomPostTypeMetaBoxFieldModel[]
     */
    protected $children = [];

	/**
	 * @var MetaBoxFieldBlockModel[]
	 */
    protected $blocks = [];

    /**
     * @var string
     */
    protected $parentId;

	/**
	 * @var string
	 */
	protected $blockId;

    /**
     * @var bool
     */
    protected $hasTemplate  = false;

    /**
     * @var MetaBoxFieldVisibilityModel[]
     */
    protected $visibilityConditions = [];

	/**
	 * @param AbstractMetaBoxModel $metaBoxModel
	 */
	public function changeMetaBox(AbstractMetaBoxModel $metaBoxModel)
	{
		$this->metaBox = $metaBoxModel;
	}

    /**
     * @return AbstractMetaBoxModel
     */
    public function getMetaBox()
    {
        return $this->metaBox;
    }

    /**
     * @param $name
     */
    public function changeName($name)
    {
    	if(!Strings::alphanumericallyValid($name)){
		    throw new \DomainException($name . ' is not valid name');
	    }

        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    /**
     * @return string
     */
    public function getNormalizedName()
    {
        return Strings::toDBFormat($this->name);
    }

	/**
	 * @param $type
	 */
    protected function setType($type)
    {
        if(!in_array($type, $this->getAllowedTypes())){
            throw new \DomainException($type . ' is not a valid field type for this meta box field');
        }

        $this->type = $type;
    }

	/**
	 * @param $type
	 */
	public function changeType($type)
	{
		$this->setType($type);
	}

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return bool
     */
    public function isShowInArchive()
    {
        return $this->showInArchive;
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return $this->required;
    }

	/**
	 * @return bool
	 */
	public function isForQuickEdit()
	{
		return $this->quickEdit;
	}

	/**
	 * @param bool $quickEdit
	 */
	public function setQuickEdit($quickEdit )
	{
		$this->quickEdit = $quickEdit;
	}

	/**
	 * @return bool
	 */
	public function isFilterableInAdmin()
	{
		return $this->filterableInAdmin;
	}

	/**
	 * @param bool $filterableInAdmin
	 */
	public function setFilterableInAdmin($filterableInAdmin )
	{
		$this->filterableInAdmin = $filterableInAdmin;
	}

	/**
	 * @return bool
	 */
	public function isHasTemplate()
	{
		return $this->hasTemplate;
	}

    /**
     * @return int
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param MetaBoxFieldAdvancedOptionModel $option
     */
    public function addAdvancedOption(MetaBoxFieldAdvancedOptionModel $option)
    {
        if(!$this->existsInCollection($option->getId(), $this->advancedOptions)){
            $this->advancedOptions[] = $option;
        }
    }

    /**
     * @param MetaBoxFieldAdvancedOptionModel $option
     */
    public function removeAdvancedOption(MetaBoxFieldAdvancedOptionModel $option)
    {
        $this->removeFromCollection($option->getId(), $this->advancedOptions);
    }

    /**
     * Clear all advanced options
     */
    public function clearAdvancedOptions()
    {
        $this->advancedOptions = [];
    }

    /**
     * @return MetaBoxFieldAdvancedOptionModel[]
     */
    public function getAdvancedOptions()
    {
        return $this->advancedOptions;
    }

	/**
	 * @param $key
	 *
	 * @return mixed|null
	 */
	public function getAdvancedOption($key)
	{
		foreach ($this->advancedOptions as $advancedOption){
			if ($advancedOption->getKey() === $key and $advancedOption->getValue() !== '') {
				return $advancedOption->getValue();
			}
		}

		return null;
	}

    /**
     * @param MetaBoxFieldOptionModel $option
     */
    public function addOption(MetaBoxFieldOptionModel $option)
    {
        if(!$this->existsInCollection($option->getId(), $this->options)){
            $this->options[] = $option;
        }
    }

    /**
     * @param MetaBoxFieldOptionModel $option
     */
    public function removeOption(MetaBoxFieldOptionModel $option)
    {
        $this->removeFromCollection($option->getId(), $this->options);
    }

    /**
     * Clear all options
     */
    public function clearOptions()
    {
        $this->options = [];
    }

    /**
     * @return MetaBoxFieldOptionModel[]
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return array
     */
    public function getOptionValues()
    {
        $values = [];

        foreach ($this->getOptions() as $option){
            $values[] = $option->getValue();
        }

        return $values;
    }

    /**
     * @param MetaBoxFieldRelationshipModel $relation
     */
    public function addRelation(MetaBoxFieldRelationshipModel $relation)
    {
        if(!$this->existsInCollection($relation->getId(), $this->relations)){
            $this->relations[] = $relation;
        }
    }

    /**
     * @param MetaBoxFieldRelationshipModel $relation
     */
    public function removeRelation(MetaBoxFieldRelationshipModel $relation)
    {
        $this->removeFromCollection($relation->getId(), $this->relations);
    }

    /**
     * Clear all relations
     */
    public function clearRelations()
    {
        $this->relations = [];
    }

    /**
     * @return MetaBoxFieldRelationshipModel[]
     */
    public function getRelations()
    {
        return $this->relations;
    }

    /**
     * @return bool
     */
    public function hasManyRelation()
    {
        if(empty($this->relations)){
            return false;
        }

        /** @var MetaBoxFieldRelationshipModel $relation */
        foreach ($this->relations as $relation){
            if($relation->isMany()){
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return !empty($this->children);
    }

    /**
     * @param AbstractMetaBoxFieldModel $field
     */
    public function addChild(AbstractMetaBoxFieldModel $field)
    {
        if(!$this->existsInCollection($field->getId(), $this->children)){
            $this->children[] = $field;
        }
    }

    /**
     * @param AbstractMetaBoxFieldModel $field
     */
    public function removeChild(AbstractMetaBoxFieldModel $field)
    {
        $this->removeFromCollection($field->getId(), $this->children);
    }

    /**
     * Clear all children
     */
    public function clearChildren()
    {
        $this->children = [];
    }

    /**
     * @return CustomPostTypeMetaBoxFieldModel[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param string $parentId
     */
    public function setParentId( $parentId )
    {
        $this->parentId = $parentId;
    }

    /**
     * @return string
     */
    public function getParentId()
    {
        return $this->parentId;
    }

	/**
	 * @return AbstractMetaBoxFieldModel|null
	 */
    public function getParentField()
    {
    	if(!$this->hasParent()){
    		return null;
	    }

    	foreach ($this->getMetaBox()->getFields() as $field){
    		if($this->getParentId() === $field->getId()){
    			return $field;
		    }
	    }

    	return null;
    }

	/**
	 * @return bool
	 */
    public function hasParent()
    {
    	return $this->getParentId() !== null;
    }

	/**
	 * @return string
	 */
	public function getBlockId()
	{
		return $this->blockId;
	}

	/**
	 * @param string $blockId
	 */
	public function setBlockId( string $blockId )
	{
		$this->blockId = $blockId;
	}

	/**
	 * @return MetaBoxFieldBlockModel[]
	 */
	public function getBlocks()
	{
		return $this->blocks;
	}

	/**
	 * @param MetaBoxFieldBlockModel $block
	 */
	public function addBlock(MetaBoxFieldBlockModel $block)
	{
		if(!$this->existsInCollection($block->getId(), $this->blocks)){
			$this->blocks[] = $block;
		}
	}

	/**
	 * @param MetaBoxFieldBlockModel $block
	 */
	public function removeBlock(MetaBoxFieldBlockModel $block)
	{
		$this->removeFromCollection($block->getId(), $this->blocks);
	}

	/**
	 * @return bool
	 */
	public function isNestedInABlock()
	{
		return $this->getBlockId() !== null;
	}

	/**
	 * @return MetaBoxFieldBlockModel|null
	 */
	public function getParentBlock()
	{
		if(!$this->isNestedInABlock()){
			return null;
		}

		foreach ($this->getMetaBox()->getFields() as $field){
			foreach ($field->getBlocks() as $blockModel) {
				if($this->getBlockId() === $blockModel->getId()){
					return $blockModel;
				}
			}
		}

		return null;
	}

	/**
	 * @return bool
	 */
	public function hasBlocks()
	{
		return !empty($this->blocks);
	}

    /**
     * @return bool
     */
    public function hasTemplate()
    {
        return $this->hasTemplate;
    }

    /**
     * @param bool $hasTemplate
     */
    public function setHasTemplate( $hasTemplate )
    {
        $this->hasTemplate = $hasTemplate;
    }

    /**
     * @param MetaBoxFieldVisibilityModel $condition
     */
    public function addVisibilityCondition(MetaBoxFieldVisibilityModel $condition)
    {
        if(!$this->existsInCollection($condition->getId(), $this->visibilityConditions)){
            $this->visibilityConditions[] = $condition;
        }
    }

    /**
     * @param MetaBoxFieldVisibilityModel $condition
     */
    public function removeVisibilityCondition(MetaBoxFieldVisibilityModel $condition)
    {
        $this->removeFromCollection($condition->getId(), $this->visibilityConditions);
    }

    /**
     * Clear all visibility conditions
     */
    public function clearVisibilityConditions()
    {
        $this->visibilityConditions = [];
    }

    /**
     * @return MetaBoxFieldVisibilityModel[]
     */
    public function getVisibilityConditions()
    {
        return $this->visibilityConditions;
    }

    /**
     * @return bool
     */
    public function hasVisibilityConditions()
    {
        return count($this->visibilityConditions) > 0;
    }

	/**
	 * @return bool
	 */
	public function isATextualField()
	{
		$textualTypes = [
			AbstractMetaBoxFieldModel::EMAIL_TYPE,
			AbstractMetaBoxFieldModel::NUMBER_TYPE,
			AbstractMetaBoxFieldModel::PHONE_TYPE,
			AbstractMetaBoxFieldModel::TEXT_TYPE,
			AbstractMetaBoxFieldModel::TEXTAREA_TYPE,
		];

		return in_array($this->type, $textualTypes);
	}

	/**
	 * @return bool
	 */
	public function isFilterable()
	{
		$filterableTypes = [
			AbstractMetaBoxFieldModel::COLOR_TYPE,
			AbstractMetaBoxFieldModel::DATE_TYPE,
			AbstractMetaBoxFieldModel::EMAIL_TYPE,
			AbstractMetaBoxFieldModel::NUMBER_TYPE,
			AbstractMetaBoxFieldModel::PHONE_TYPE,
			AbstractMetaBoxFieldModel::SELECT_TYPE,
			AbstractMetaBoxFieldModel::TEXT_TYPE,
			AbstractMetaBoxFieldModel::TEXTAREA_TYPE,
		];

		return in_array($this->type, $filterableTypes);
	}

    /**
     * @return array
     */
    abstract public function getAllowedTypes();

    /**
     * @return string
     */
    abstract public function getDbName();

    /**
     * @return string
     */
    abstract public function getUiName();

	/**
	 * @return AbstractMetaBoxFieldModel
	 */
	public function duplicate()
	{
		return $this->duplicateFrom($this->getMetaBox());
	}

	/**
	 * @param AbstractMetaBoxModel $duplicateFrom
	 *
	 * @return AbstractMetaBoxFieldModel
	 */
	public function duplicateFrom(AbstractMetaBoxModel $duplicateFrom)
	{
		$duplicate = clone $this;
		$duplicate->id = Uuid::v4();
		$duplicate->metaBox = $duplicateFrom;

		$duplicatedOptions = $duplicate->getOptions();
		$duplicatedAdvancedOptions = $duplicate->getAdvancedOptions();
		$duplicatedChildren = $duplicate->getChildren();
		$duplicatedVisibilityConditions = $duplicate->getVisibilityConditions();
		$duplicatedBlocks = $duplicate->getBlocks();

		$duplicate->options = [];
		$duplicate->advancedOptions = [];
		$duplicate->relations = [];
		$duplicate->children = [];
		$duplicate->visibilityConditions = [];
		$duplicate->blocks = [];

		foreach ($duplicatedOptions as $option){
			$optionFieldModel = $option->duplicateFrom($duplicate);
			$duplicate->addOption($optionFieldModel);
		}

		foreach ($duplicatedBlocks as $block){
			$blockFieldModel = $block->duplicateFrom($duplicate);
			$duplicate->addBlock($blockFieldModel);
		}

		foreach ($duplicatedAdvancedOptions as $advancedOption){
			$advancedOptionFieldModel = $advancedOption->duplicateFrom($duplicate);
			$duplicate->addAdvancedOption($advancedOptionFieldModel);
		}

		foreach ($duplicatedChildren as $child){
			$childModel = $child->duplicateFromParent($duplicate);
			$duplicate->addChild($childModel);
		}

		foreach ($duplicatedVisibilityConditions as $condition){
			$visibilityConditionModel = $condition->duplicateFrom($duplicate);
			$duplicate->addVisibilityCondition($visibilityConditionModel);
		}

		return $duplicate;
	}

	/**
	 * @param AbstractMetaBoxFieldModel $duplicateFrom
	 *
	 * @return AbstractMetaBoxFieldModel
	 */
	public function duplicateFromParent( AbstractMetaBoxFieldModel $duplicateFrom )
	{
		$duplicate = clone $this;
		$duplicate->id = Uuid::v4();
		$duplicate->parentId = $duplicateFrom->getId();

		return $duplicate;
	}

	/**
	 * @param MetaBoxFieldBlockModel $block
	 *
	 * @return AbstractMetaBoxFieldModel
	 */
	public function duplicateFromBlock( MetaBoxFieldBlockModel $block )
	{
		$duplicate = clone $this;
		$duplicate->id = Uuid::v4();
		$duplicate->blockId = $block->getId();

		return $duplicate;
	}
}