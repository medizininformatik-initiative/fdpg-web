<?php

namespace ACPT\Core\Models\MetaField;

use ACPT\Core\Helper\Uuid;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Core\Models\Abstracts\AbstractModel;
use ACPT\Core\Models\CustomPostType\CustomPostTypeModel;

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
    const ONE_TO_ONE_UNI   = 'OneToOneUni';
    const ONE_TO_ONE_BI    = 'OneToOneBi';
    const ONE_TO_MANY_UNI  = 'OneToManyUni';
    const ONE_TO_MANY_BI   = 'OneToManyBi';
    const MANY_TO_ONE_UNI  = 'ManyToOneUni';
    const MANY_TO_ONE_BI   = 'ManyToOneBi';
    const MANY_TO_MANY_UNI = 'ManyToManyUni';
    const MANY_TO_MANY_BI  = 'ManyToManyBi';

    /**
     * @var AbstractMetaBoxFieldModel
     */
    private $metaBoxField;

    /**
     * @var CustomPostTypeModel
     */
    private $relatedCustomPostType;

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
     * @param                     $id
     * @param AbstractMetaBoxFieldModel   $metaBoxField
     * @param CustomPostTypeModel $relatedCustomPostType
     * @param string              $relationship
     *
     * @throws \Exception
     */
    public function __construct(
        $id,
        AbstractMetaBoxFieldModel $metaBoxField,
        CustomPostTypeModel $relatedCustomPostType,
        $relationship
    ) {
        parent::__construct( $id );
        $this->metaBoxField          = $metaBoxField;
        $this->relatedCustomPostType = $relatedCustomPostType;
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
            self::ONE_TO_ONE_UNI,
            self::ONE_TO_ONE_BI,
            self::ONE_TO_MANY_UNI,
            self::ONE_TO_MANY_BI,
            self::MANY_TO_ONE_UNI,
            self::MANY_TO_ONE_BI,
            self::MANY_TO_MANY_UNI,
            self::MANY_TO_MANY_BI,
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
     * @return CustomPostTypeModel
     */
    public function getRelatedCustomPostType()
    {
        return $this->relatedCustomPostType;
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
        return $this->relationship === self::ONE_TO_MANY_UNI or
                $this->relationship === self::MANY_TO_MANY_UNI or
                $this->relationship === self::ONE_TO_MANY_BI or
                $this->relationship === self::MANY_TO_MANY_BI;
    }

    /**
     * @return bool
     */
    public function isBidirectional()
    {
        return $this->relationship === self::ONE_TO_ONE_BI or
                $this->relationship === self::MANY_TO_ONE_BI or
                $this->relationship === self::ONE_TO_MANY_BI or
                $this->relationship === self::MANY_TO_MANY_BI;
    }

    /**
     * @return string
     */
    public function getOppositeRelationship()
    {
        switch ($this->relationship) {
            case self::ONE_TO_ONE_BI:
                return self::ONE_TO_ONE_BI;

            case self::ONE_TO_MANY_BI:
                return self::MANY_TO_ONE_BI;

            case self::MANY_TO_ONE_BI:
                return self::ONE_TO_MANY_BI;

            case self::MANY_TO_MANY_BI:
                return self::MANY_TO_MANY_BI;
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
            'relatedPostType' => ($this->getRelatedCustomPostType() !== null) ? $this->getRelatedCustomPostType()->getName() : null,
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
