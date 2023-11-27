<?php

namespace ACPT\Core\Models\MetaField;

use ACPT\Core\Helper\Uuid;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Core\Models\Abstracts\AbstractModel;

/**
 * MetaBoxFieldAdvancedOptionModel
 *
 * @since      1.0.130
 * @package    advanced-custom-post-type
 * @subpackage advanced-custom-post-type/core
 * @author     Mauro Cassani <maurocassani1978@gmail.com>
 */
class MetaBoxFieldAdvancedOptionModel extends AbstractModel implements \JsonSerializable
{
    /**
     * @var AbstractMetaBoxFieldModel
     */
    private $metaBoxField;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $value;

    /**
     * MetaBoxFieldModel constructor.
     *
     * @param string                     $id
     * @param AbstractMetaBoxFieldModel $metaBoxField
     * @param string                     $key
     * @param string                     $value
     */
    public function __construct(
        $id,
        AbstractMetaBoxFieldModel $metaBoxField,
        $key,
        $value
    ) {
        parent::__construct($id);
        $this->metaBoxField = $metaBoxField;
        $this->key = $key;
        $this->value = $value;
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
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

	#[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'boxId' => $this->getMetaBoxField()->getMetaBox()->getId(),
            'fieldId' => $this->getMetaBoxField()->getId(),
            'key' => $this->key,
            'value' => $this->value,
        ];
    }

	/**
	 * @param AbstractMetaBoxFieldModel $duplicateFrom
	 *
	 * @return MetaBoxFieldAdvancedOptionModel
	 */
	public function duplicateFrom( AbstractMetaBoxFieldModel $duplicateFrom )
	{
		$duplicate = clone $this;
		$duplicate->id = Uuid::v4();
		$duplicate->metaBoxField = $duplicateFrom;

		return $duplicate;
	}
}