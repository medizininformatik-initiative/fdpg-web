<?php

namespace ACPT\Core\Values;

use ACPT\Core\Models\Abstracts\AbstractMetaBoxFieldModel;

class PostMetaValue extends AbstractMetaValue
{
    /**
     * @var int
     */
    private $postId;

    /**
     * PostMetaValue constructor.
     *
     * @param AbstractMetaBoxFieldModel $metaFieldModel
     * @param int               $postId
     */
    public function __construct(
        AbstractMetaBoxFieldModel $metaFieldModel,
        int $postId
    ) 
    {
    	parent::__construct($metaFieldModel);
        $this->postId = $postId;
    }

	/**
	 * @param string $key
	 *
	 * @return mixed
	 */
    protected function getData($key = '')
    {
        return get_post_meta($this->postId, $this->metaFieldModel->getDbName().$key, true);
    }
}