<?php

namespace ACPT\Core\Values;

use ACPT\Core\Models\Taxonomy\TaxonomyMetaBoxFieldModel;
use ACPT\Utils\Wordpress\WPAttachment;

class TaxonomyMetaValue extends AbstractMetaValue
{
    /**
     * @var int
     */
    private $termId;

    /**
     * PostMetaValue constructor.
     *
     * @param TaxonomyMetaBoxFieldModel $metaFieldModel
     * @param int               $termId
     */
    public function __construct(
        TaxonomyMetaBoxFieldModel $metaFieldModel,
        $termId
    )
    {
	    parent::__construct($metaFieldModel);
        $this->metaFieldModel = $metaFieldModel;
        $this->termId         = $termId;
    }

	/**
	 * @inheritDoc
	 */
	protected function getData( $key = '' )
	{
		return get_term_meta($this->termId, $this->metaFieldModel->getDbName().$key, true);
	}
}