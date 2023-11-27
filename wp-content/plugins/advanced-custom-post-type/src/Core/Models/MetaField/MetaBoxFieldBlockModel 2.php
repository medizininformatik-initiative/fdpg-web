<?php

namespace ACPT\Core\Models\MetaField;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Helper\Uuid;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Core\Models\Abstracts\AbstractModel;

/**
 * MetaBoxFieldAdvancedOptionModel
 *
 * @since      1.0.170
 * @package    advanced-custom-post-type
 * @subpackage advanced-custom-post-type/core
 * @author     Mauro Cassani <maurocassani1978@gmail.com>
 */
class MetaBoxFieldBlockModel extends AbstractModel implements \JsonSerializable
{
	/**
	 * @var AbstractMetaBoxFieldModel
	 */
	private $metaBoxField;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $label;

	/**
	 * @var int
	 */
	protected $sort;

	/**
	 * @var AbstractMetaBoxFieldModel[]
	 */
	private $fields;

	/**
	 * MetaBoxFieldBlockModel constructor.
	 *
	 * @param $id
	 * @param AbstractMetaBoxFieldModel $metaBoxField
	 * @param $name
	 * @param $sort
	 * @param null $label
	 */
	public function __construct(
		$id,
		AbstractMetaBoxFieldModel $metaBoxField,
		$name,
		$sort,
		$label = null
	)
	{
	    if(!Strings::alphanumericallyValid($name)){
		    throw new \DomainException($name . ' is not valid name');
	    }

		parent::__construct($id);
		$this->metaBoxField = $metaBoxField;
		$this->name     = $name;
		$this->sort     = $sort;
		$this->label    = $label;
		$this->fields   = [];
	}

	/**
	 * @param $name
	 * @param $sort
	 * @param null $label
	 */
	public function edit(
		$name,
		$sort,
		$label = null
	)
	{
	    if(!Strings::alphanumericallyValid($name)){
		    throw new \DomainException($name . ' is not valid name');
	    }

		$this->label    = $label;
		$this->name     = $name;
		$this->sort     = $sort;
	}

	/**
	 * @return AbstractMetaBoxFieldModel
	 */
	public function getMetaBoxField()
	{
		return $this->metaBoxField;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function changeName( string $name )
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 * @return int
	 */
	public function getSort()
	{
		return $this->sort;
	}

	/**
	 * @param AbstractMetaBoxFieldModel $field
	 *
	 * @return bool
	 */
	public function hasField(AbstractMetaBoxFieldModel $field)
	{
		return $this->existsInCollection($field->getId(), $this->fields);
	}

	/**
	 * @param AbstractMetaBoxFieldModel $field
	 */
	public function addField(AbstractMetaBoxFieldModel $field)
	{
		if(!$this->existsInCollection($field->getId(), $this->fields)){
			$this->fields[] = $field;
		}
	}

	/**
	 * @param AbstractMetaBoxFieldModel $field
	 */
	public function removeField(AbstractMetaBoxFieldModel $field)
	{
		$this->removeFromCollection($field->getId(), $this->fields);
	}

	/**
	 * @return array|AbstractMetaBoxFieldModel[]
	 */
	public function getFields()
	{
		return $this->fields;
	}

	/**
	 * @param AbstractMetaBoxFieldModel[] $fields
	 */
	public function setFields(array $fields)
	{
		$this->fields = $fields;
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
	 * @return string
	 */
	public function getUiName()
	{
		$name = (!empty($this->label)) ? $this->label : $this->name;

		return  Strings::toHumanReadableFormat($this->getMetaBoxField()->getMetaBox()->getUiName()) . ' - ' . Strings::toHumanReadableFormat($this->getMetaBoxField()->getName()) . ' #' . Strings::toHumanReadableFormat($name);
	}

	#[\ReturnTypeWillChange]
	public function jsonSerialize()
	{
		return [
			'id' => $this->id,
			'boxId' => $this->getMetaBoxField()->getMetaBox()->getId(),
			'fieldId' => $this->getMetaBoxField()->getId(),
			'name' => $this->name,
			'label' => $this->label,
			'sort' => (int)$this->sort,
			'fields' => $this->fields
		];
	}

	/**
	 * @param AbstractMetaBoxFieldModel $duplicateFrom
	 *
	 * @return MetaBoxFieldBlockModel
	 */
	public function duplicateFrom( AbstractMetaBoxFieldModel $duplicateFrom )
	{
		$duplicate = clone $this;
		$duplicate->id = Uuid::v4();
		$duplicate->metaBoxField = $duplicateFrom;

		foreach ($duplicate->getFields() as $field){
			$childModel = $field->duplicateFromBlock($duplicate);
			$duplicate->addField($childModel);
		}

		return $duplicate;
	}

	/**
	 * @return MetaBoxFieldBlockModel
	 */
	public function duplicate()
	{
		$duplicate = clone $this;
		$duplicate->id = Uuid::v4();
		$duplicatedFields = $duplicate->getFields();
		$duplicate->fields = [];

		foreach ($duplicatedFields as $field){
			$duplicatedFieldModel = $field->duplicate();
			$duplicate->addField($duplicatedFieldModel);
		}

		return $duplicate;
	}
}