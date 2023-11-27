<?php

namespace ACPT\Core\Values;

use ACPT\Core\Models\User\UserMetaBoxFieldModel;
use ACPT\Utils\Wordpress\WPAttachment;

class UserMetaValue extends AbstractMetaValue
{
    /**
     * @var int
     */
    private $userId;

    /**
     * PostMetaValue constructor.
     *
     * @param UserMetaBoxFieldModel $metaFieldModel
     * @param int               $userId
     */
    public function __construct(
        UserMetaBoxFieldModel $metaFieldModel,
        int $userId
    ) 
    {
	    parent::__construct($metaFieldModel);
        $this->metaFieldModel = $metaFieldModel;
        $this->userId         = $userId;
    }
	/**
	 * @inheritDoc
	 */
	protected function getData( $key = '' )
	{
		return get_user_meta($this->userId, $this->metaFieldModel->getDbName().$key, true);
	}
}