<?php

namespace ACPT\Integrations\WPAllImport;

use ACPT\Integrations\AbstractIntegration;
use ACPT\Integrations\WPAllImport\Addon\WPAIAddon;

class ACPT_WPAllImport extends AbstractIntegration
{

	/**
	 * @inheritDoc
	 */
	protected function isActive()
	{
		return (
			is_plugin_active( 'wp-all-import-pro/wp-all-import-pro.php' ) or
			is_plugin_active( 'wp-all-import/wp-all-import.php' )
		);
	}

	/**
	 * @inheritDoc
	 */
	protected function runIntegration()
	{
		WPAIAddon::getInstance();
	}
}