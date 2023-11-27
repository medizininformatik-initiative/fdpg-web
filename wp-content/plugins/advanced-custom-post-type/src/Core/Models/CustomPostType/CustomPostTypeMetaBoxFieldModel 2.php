<?php

namespace ACPT\Core\Models\CustomPostType;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;

/**
 * CustomPostTypeModel
 *
 * @since      1.0.0
 * @package    advanced-custom-post-type
 * @subpackage advanced-custom-post-type/core
 * @author     Mauro Cassani <maurocassani1978@gmail.com>
 */
class CustomPostTypeMetaBoxFieldModel extends AbstractMetaBoxFieldModel implements \JsonSerializable
{
    /**
     * MetaBoxFieldModel constructor.
     *
     * @param              $id
     * @param CustomPostTypeMetaBoxModel $metaBox
     * @param string       $title
     * @param string       $type
     * @param bool         $showInArchive
     * @param bool         $required
     * @param int          $sort
     * @param null         $defaultValue
     * @param null         $description
     */
    public function __construct(
        $id,
        CustomPostTypeMetaBoxModel $metaBox,
        $title,
        $type,
        $showInArchive,
        $required,
        $sort,
        $defaultValue = null,
        $description = null
    ) {
        parent::__construct($id);
        $this->metaBox = $metaBox;
        $this->name    = $title;
        $this->setType($type);
        $this->showInArchive = $showInArchive;
        $this->required = $required;
        $this->sort  = $sort;
        $this->defaultValue  = $defaultValue;
        $this->description  = $description;
    }

    /**
     * @return array
     */
    public function getAllowedTypes()
    {
        return [
            self::ADDRESS_TYPE,
            self::CHECKBOX_TYPE,
            self::COLOR_TYPE,
            self::CURRENCY_TYPE,
            self::DATE_TYPE,
            self::DATE_RANGE_TYPE,
            self::EDITOR_TYPE,
            self::EMAIL_TYPE,
            self::EMBED_TYPE,
            self::FILE_TYPE,
            self::FLEXIBLE_CONTENT_TYPE,
            self::HTML_TYPE,
            self::GALLERY_TYPE,
            self::ICON_TYPE,
            self::IMAGE_TYPE,
            self::LENGTH_TYPE,
            self::LIST_TYPE,
            self::NUMBER_TYPE,
            self::POST_TYPE,
            self::PHONE_TYPE,
            self::RADIO_TYPE,
            self::REPEATER_TYPE,
            self::SELECT_TYPE,
            self::SELECT_MULTI_TYPE,
            self::TEXT_TYPE,
            self::TEXTAREA_TYPE,
            self::TIME_TYPE,
            self::TOGGLE_TYPE,
            self::VIDEO_TYPE,
            self::WEIGHT_TYPE,
            self::URL_TYPE,
        ];
    }

    /**
     * @return string
     */
    public function getDbName()
    {
        return Strings::toDBFormat($this->getMetaBox()->getName()).'_'.Strings::toDBFormat($this->name);
    }

    /**
     * @return string
     */
    public function getUiName()
    {
    	$uiName = Strings::toHumanReadableFormat($this->getMetaBox()->getUiName()) . ' - ' . Strings::toHumanReadableFormat($this->name);

    	if($this->getParentId()){
		    $uiName .= ' [children]';
	    }

        return $uiName;
    }

	#[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'boxId' => $this->getMetaBox()->getId(),
            'db_name' => $this->getDbName(),
            'ui_name' => $this->getUiName(),
            'name' => $this->name,
            'type' => $this->type,
            'defaultValue' => $this->defaultValue,
            'description' => $this->description,
            'isRequired' => (bool)$this->required,
            'showInArchive' => (bool)$this->showInArchive,
            'quickEdit' => (bool)$this->quickEdit,
            'filterableInAdmin' => (bool)$this->filterableInAdmin,
            'sort' => (int)$this->sort,
            'advancedOptions' => $this->advancedOptions,
            'options' => $this->options,
            'relations' => $this->relations,
            'blocks' => $this->blocks,
            'blockId' => $this->getBlockId(),
            'visibilityConditions' => $this->visibilityConditions,
            'hasManyRelation' => $this->hasManyRelation(),
            'hasChildren' => $this->hasChildren(),
            'children' => $this->getChildren(),
            'parentId' => $this->getParentId(),
            'hasTemplate' => $this->hasTemplate(),
            'isATextualField' => $this->isATextualField(),
            'isFilterable' => $this->isFilterable(),
        ];
    }
}