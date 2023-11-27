<?php

namespace ACPT\Core\Models\OptionPage;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;

/**
 * OptionPageMetaBoxFieldModel
 *
 * @since      1.0.150
 * @package    advanced-custom-post-type
 * @subpackage advanced-custom-post-type/core
 * @author     Mauro Cassani <maurocassani1978@gmail.com>
 */
class OptionPageMetaBoxFieldModel extends AbstractMetaBoxFieldModel implements \JsonSerializable
{
	/**
	 * TaxonomyMetaBoxFieldModel constructor.
	 * @param int $id
	 * @param OptionPageMetaBoxModel $metaBox
	 * @param string $name
	 * @param string $type
	 * @param bool $required
	 * @param int $sort
	 * @param null $defaultValue
	 * @param null $description
	 */
	public function __construct(
		$id,
		OptionPageMetaBoxModel $metaBox,
		$name,
		$type,
		$required,
		$sort,
		$defaultValue = null,
		$description = null
	) {
		// @TODO Retro-compatibility issue. Too dangerous for now
//	    if(!Strings::alphanumericallyValid($name)){
//		    throw new \DomainException($name . ' is not valid name');
//	    }

		parent::__construct($id);
		$this->metaBox = $metaBox;
		$this->name    = $name;
		$this->setType($type);
		$this->required = $required;
		$this->sort  = $sort;
		$this->defaultValue  = $defaultValue;
		$this->description  = $description;
	}

	/**
	 * @inheritDoc
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
	 * @inheritDoc
	 */
	public function getDbName()
	{
		return Strings::toDBFormat($this->getMetaBox()->getName()).'_'.Strings::toDBFormat($this->name);
	}

	/**
	 * @inheritDoc
	 */
	public function getUiName()
	{
		return Strings::toHumanReadableFormat($this->getMetaBox()->getUiName()) . ' - ' . Strings::toHumanReadableFormat($this->name);
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
			'sort' => (int)$this->sort,
			'advancedOptions' => $this->advancedOptions,
			'options' => $this->options,
			'visibilityConditions' => $this->visibilityConditions,
			'blocks' => $this->blocks,
			'blockId' => $this->getBlockId(),
			'hasChildren' => $this->hasChildren(),
			'children' => $this->getChildren(),
			'parentId' => $this->getParentId(),
			'hasTemplate' => $this->hasTemplate(),
		];
	}
}