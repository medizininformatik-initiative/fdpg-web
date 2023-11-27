<?php

namespace ACPT\Core\Models\MetaField;

use ACPT\Core\Helper\Uuid;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Core\Models\Abstracts\AbstractModel;
use ACPT\Core\ValueObjects\RelatedEntityValueObject;
use ACPT\Costants\Relationships;

/**
 * MetaBoxFieldRelationshipModel
 *
 * @since      1.0.0
 * @package    advanced-custom-post-type
 * @subpackage advanced-custom-post-type/core
 * @author     Mauro Cassani <maurocassani1978@gmail.com>
 */
class MetaBoxFieldRelationshipModel extends AbstractModel implements \JsonSerializable
{
    /**
     * @var AbstractMetaBoxFieldModel
     */
    private $metaBoxField;

	/**
	 * @var RelatedEntityValueObject
	 */
    private $relatedEntity;

    /**
     * @var string
     */
    private $relationship;

    /**
     * @var AbstractMetaBoxFieldModel
     */
    private $inversedBy;

	/**
	 * MetaBoxFieldRelationshipModel constructor.
	 *
	 * @param $id
	 * @param AbstractMetaBoxFieldModel $metaBoxField
	 * @param RelatedEntityValueObject $relatedEntity
	 * @param $relationship
	 *
	 * @throws \Exception
	 *
	 */
    public function __construct(
        $id,
        AbstractMetaBoxFieldModel $metaBoxField,
	    RelatedEntityValueObject $relatedEntity,
        $relationship
    ) {
        parent::__construct( $id );
        $this->metaBoxField  = $metaBoxField;
        $this->relatedEntity = $relatedEntity;
        $this->setRelationship($relationship);
    }

    /**
     * @param $relationship
     *
     * @throws \Exception
     */
    private function setRelationship($relationship)
    {
        $allowedValues = [
            Relationships::ONE_TO_ONE_UNI,
            Relationships::ONE_TO_ONE_BI,
            Relationships::ONE_TO_MANY_UNI,
            Relationships::ONE_TO_MANY_BI,
            Relationships::MANY_TO_ONE_UNI,
            Relationships::MANY_TO_ONE_BI,
            Relationships::MANY_TO_MANY_UNI,
            Relationships::MANY_TO_MANY_BI,
        ];

        if(!in_array($relationship, $allowedValues)){
            throw new \Exception($relationship . ' is not an allowed relation');
        }

        $this->relationship  = $relationship;
    }

    /**
     * @return AbstractMetaBoxFieldModel
     */
    public function getMetaBoxField()
    {
        return $this->metaBoxField;
    }

	/**
	 * @return RelatedEntityValueObject
	 */
	public function getRelatedEntity()
	{
		return $this->relatedEntity;
	}

    /**
     * @return string
     */
    public function getRelationship()
    {
        return $this->relationship;
    }

    /**
     * @return bool
     */
    public function isMany()
    {
        return $this->relationship === Relationships::ONE_TO_MANY_UNI or
                $this->relationship === Relationships::MANY_TO_MANY_UNI or
                $this->relationship === Relationships::ONE_TO_MANY_BI or
                $this->relationship === Relationships::MANY_TO_MANY_BI;
    }

    /**
     * @return bool
     */
    public function isBidirectional()
    {
        return $this->relationship === Relationships::ONE_TO_ONE_BI or
                $this->relationship === Relationships::MANY_TO_ONE_BI or
                $this->relationship === Relationships::ONE_TO_MANY_BI or
                $this->relationship === Relationships::MANY_TO_MANY_BI;
    }

    /**
     * @return string
     */
    public function getOppositeRelationship()
    {
        switch ($this->relationship) {
            case Relationships::ONE_TO_ONE_BI:
                return Relationships::ONE_TO_ONE_BI;

            case Relationships::ONE_TO_MANY_BI:
                return Relationships::MANY_TO_ONE_BI;

            case Relationships::MANY_TO_ONE_BI:
                return Relationships::ONE_TO_MANY_BI;

            case Relationships::MANY_TO_MANY_BI:
                return Relationships::MANY_TO_MANY_BI;
        }

        return null;
    }

    /**
     * @param AbstractMetaBoxFieldModel $inversedBy
     */
    public function setInversedBy( AbstractMetaBoxFieldModel $inversedBy )
    {
        $this->inversedBy = $inversedBy;
    }

    /**
     * @return AbstractMetaBoxFieldModel
     */
    public function getInversedBy()
    {
        return $this->inversedBy;
    }

	#[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'boxId' => $this->getMetaBoxField()->getMetaBox()->getId(),
            'fieldId' => $this->getMetaBoxField()->getId(),
            'type' => $this->relationship,
            'relatedEntity' => ($this->getRelatedEntity() !== null) ? $this->getRelatedEntity() : null,
            'inversedBoxId' => ($this->getInversedBy() !== null) ? $this->getInversedBy()->getMetaBox()->getId() : null,
            'inversedBoxName' => ($this->getInversedBy() !== null) ? $this->getInversedBy()->getMetaBox()->getName() : null,
            'inversedFieldName' => ($this->getInversedBy() !== null) ? $this->getInversedBy()->getName() : null,
            'inversedFieldId' => ($this->getInversedBy() !== null) ? $this->getInversedBy()->getId() : null,
        ];
    }

	/**
	 * @param AbstractMetaBoxFieldModel $duplicateFrom
	 *
	 * @return MetaBoxFieldRelationshipModel
	 */
	public function duplicateFrom( AbstractMetaBoxFieldModel $duplicateFrom )
	{
		$duplicate = clone $this;
		$duplicate->id = Uuid::v4();
		$duplicate->metaBoxField = $duplicateFrom;

		return $duplicate;
	}
}
