<?php

namespace ACPT\Integrations\Zion;

use ACPT\Integrations\AbstractIntegration;
use ACPT\Integrations\Zion\Provider\ZionProvider;

class ACPT_Zion extends AbstractIntegration
{
	/**
	 * @inheritDoc
	 */
	protected function isActive()
	{
		return (
			is_plugin_active('zionbuilder-pro/zionbuilder-pro.php') or
			is_plugin_active('zionbuilder/zionbuilder.php')
		);
	}

	/**
	 * @inheritDoc
	 */
	protected function runIntegration()
	{
		$provider = new ZionProvider();
		$provider->init();
	}
}