<?php

namespace ACPT\Core\Models\Abstracts;

use ACPT\Core\Helper\Strings;
use ACPT\Core\Helper\Uuid;
use ACPT\Costants\MetaTypes;

/**
 * AbstractMetaBoxModel
 *
 * @since      1.0.140
 * @package    advanced-custom-post-type
 * @subpackage advanced-custom-post-type/core
 * @author     Mauro Cassani <maurocassani1978@gmail.com>
 */
abstract class AbstractMetaBoxModel extends AbstractModel
{
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
    protected $fields = [];

    /**
     * @return string
     */
    abstract public function metaType();

    /**
     * @return bool
     */
    public function belongsToCustomPostType()
    {
        return $this->metaType() === MetaTypes::CUSTOM_POST_TYPE;
    }

    /**
     * @return bool
     */
    public function belongsToTaxonomy()
    {
        return $this->metaType() === MetaTypes::TAXONOMY;
    }

	/**
	 * @return bool
	 */
	public function belongsToOptionPage()
	{
		return $this->metaType() === MetaTypes::OPTION_PAGE;
	}

    /**
     * @return bool
     */
    public function belongsToUser()
    {
        return $this->metaType() === MetaTypes::USER;
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
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 * @param string $label
	 */
	public function changeLabel( $label )
	{
		$this->label = $label;
	}

    /**
     * @param $sort
     */
    public function changeSort($sort)
    {
        $this->sort = $sort;
    }

    /**
     * @return int
     */
    public function getSort()
    {
        return $this->sort;
    }

	/**
	 * @return bool
	 */
    public function containsAFlexibleField()
    {
    	foreach ($this->getFields() as $field){
    		if($field->getType() === AbstractMetaBoxFieldModel::FLEXIBLE_CONTENT_TYPE){
    			return true;
		    }
	    }

    	return false;
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
	 * @return mixed
	 */
	public function getUiName()
	{
		if(!empty($this->getLabel())){
			return $this->getLabel();
		}

		return $this->getName();
	}

	/**
	 * @return AbstractMetaBoxModel
	 */
	public function duplicate()
	{
		$duplicate = clone $this;
		$duplicate->id = Uuid::v4();
		$duplicatedFields = $duplicate->getFields();
		$duplicate->fields = [];

		foreach ($duplicatedFields as $field){
			$duplicatedFieldModel = $field->duplicateFrom($duplicate);
			$duplicate->addField($duplicatedFieldModel);
		}

		return $duplicate;
	}
}