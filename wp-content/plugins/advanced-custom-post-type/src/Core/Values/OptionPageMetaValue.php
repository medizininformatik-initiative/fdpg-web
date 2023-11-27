<?php

namespace ACPT\Core\Values;

class OptionPageMetaValue extends AbstractMetaValue
{
	/**
	 * @inheritDoc
	 */
	protected function getData( $key = '' )
	{
		return get_option($this->metaFieldModel->getDbName().$key);
	}
}