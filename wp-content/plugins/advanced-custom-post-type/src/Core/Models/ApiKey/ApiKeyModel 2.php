<?php

namespace ACPT\Core\Models\ApiKey;

use ACPT\Core\Models\Abstracts\AbstractModel;

/**
 * ApiKeyModel
 *
 * @since      1.0.5
 * @package    advanced-custom-post-type
 * @subpackage advanced-custom-post-type/core
 * @author     Mauro Cassani <maurocassani1978@gmail.com>
 */
class ApiKeyModel extends AbstractModel implements \JsonSerializable
{
    /**
     * @var int
     */
    private $uid;

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $secret;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * ApiKeyModel constructor.
     *
     * @param           $id
     * @param           $uid
     * @param           $key
     * @param           $secret
     * @param \DateTime $createdAt
     */
    public function __construct(
        $id,
        $uid,
        $key,
        $secret,
        \DateTime $createdAt
    ) {
        parent::__construct($id);
        $this->uid = $uid;
        $this->key = $key;
        $this->secret = $secret;
        $this->createdAt = $createdAt;
    }

    /**
     * @return int
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt( $createdAt )
    {
        $this->createdAt = $createdAt;
    }

	#[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'uid' => $this->getUid(),
            'key' => $this->getKey(),
            'secret' => '********',
            'createdAt' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
        ];
    }
}