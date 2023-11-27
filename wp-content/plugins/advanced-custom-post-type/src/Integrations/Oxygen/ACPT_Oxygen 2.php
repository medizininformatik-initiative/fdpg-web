<?php

namespace ACPT\Integrations\Oxygen;

use ACPT\Integrations\AbstractIntegration;
use ACPT\Integrations\Oxygen\Provider\OxygenDataProvider;

class ACPT_Oxygen extends AbstractIntegration
{
	/**
	 * @inheritDoc
	 */
	protected function isActive()
	{
		return is_plugin_active( 'oxygen/functions.php' );
	}

	/**
	 * @inheritDoc
	 */
	protected function runIntegration()
	{
		add_filter( 'oxygen_custom_dynamic_data', [new OxygenDataProvider(), 'initDynamicData'], 10, 1 );
	}
}
