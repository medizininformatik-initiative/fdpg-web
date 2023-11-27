<?php

namespace ACPT\Core\Models\MetaField;

use ACPT\Core\Helper\Uuid;
use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;
use ACPT\Core\Models\Abstracts\AbstractModel;

class MetaBoxFieldVisibilityModel extends AbstractModel implements \JsonSerializable
{
    const LOGICS = [
        '',
        'AND',
        'OR',
    ];

    const TYPES = [
        'VALUE',
        'POST_ID',
        'TERM_ID',
        'TAXONOMY',
        'OTHER_FIELDS',
    ];

    const OPERATORS = [
        '=',
        '!=',
        '<',
        '>',
        '<=',
        '>=',
        'LIKE',
        'NOT_LIKE',
        'IN',
        'NOT_IN',
        'NULL',
        'NOT_NULL',
        'BLANK',
        'NOT_BLANK',
        'CHECKED',
        'NOT_CHECKED',
    ];

    /**
     * @var AbstractMetaBoxFieldModel
     */
    private $metaBoxField;

    /**
     * @var array
     */
    private $type = [];

    /**
     * @var string
     */
    private $operator;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var ?string
     */
    private $logic;

    /**
     * @var int
     */
    private $sort;

    /**
     * MetaBoxFieldVisibilityModel constructor.
     *
     * @param                   $id
     * @param AbstractMetaBoxFieldModel $metaBoxField
     * @param                   $type
     * @param                   $operator
     * @param                   $value
     * @param                   $sort
     * @param null              $logic
     *
     * @throws \Exception
     */
    public function __construct(
        $id,
        AbstractMetaBoxFieldModel $metaBoxField,
        $type,
        $operator,
        $value,
        $sort,
        $logic = null
    )
    {
        parent::__construct($id);
        $this->setType($type);
        $this->setOperator($operator);
        $this->setLogic($logic);
        $this->metaBoxField = $metaBoxField;
        $this->value = $value;
        $this->sort = $sort;
    }

    /**
     * @return AbstractMetaBoxFieldModel
     */
    public function getMetaBoxField() {
        return $this->metaBoxField;
    }

    /**
     * @param $logic
     *
     * @throws \Exception
     */
    private function setLogic($logic)
    {
        if(!in_array($logic, self::LOGICS)){
            throw new \Exception($logic . ' is not a valid logic');
        }

        $this->logic = $logic;
    }

    /**
     * @param array $type
     * @example ["type" => "TAXONOMY", "value" => 3]
     *
     * @throws \Exception
     */
    private function setType(array $type)
    {
        if(!isset($type['type'])){
            throw new \Exception('Type is not a valid type');
        }

        if(!in_array($type['type'], self::TYPES)){
            throw new \Exception($type . ' is not a valid type');
        }

        $this->type = $type;
    }

    /**
     * @param $operator
     *
     * @throws \Exception
     */
    private function setOperator($operator)
    {
        if(!in_array($operator, self::OPERATORS)){
            throw new \Exception($operator . ' is not a valid operator');
        }

        $this->operator = $operator;
    }

    /**
     * @return array
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string|null
     */
    public function getLogic()
    {
        return $this->logic;
    }

    /**
     * @return int
     */
    public function getSort()
    {
        return $this->sort;
    }

	#[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'boxId' => $this->getMetaBoxField()->getMetaBox()->getId(),
            'fieldId' => $this->getMetaBoxField()->getId(),
            'type' => $this->type,
            'operator' => $this->operator,
            'value' => $this->value,
            'logic' => $this->logic,
            'sort' => (int)$this->sort,
        ];
    }

	/**
	 * @param AbstractMetaBoxFieldModel $duplicateFrom
	 *
	 * @return MetaBoxFieldVisibilityModel
	 */
	public function duplicateFrom( AbstractMetaBoxFieldModel $duplicateFrom )
	{
		$duplicate = clone $this;
		$duplicate->id = Uuid::v4();
		$duplicate->metaBoxField = $duplicateFrom;

		return $duplicate;
	}
}