<?php

namespace ACPT\Integrations\Breakdance;

use ACPT\Integrations\AbstractIntegration;
use ACPT\Integrations\Breakdance\Provider\BreakdanceProvider;

class ACPT_Breakdance extends AbstractIntegration
{
	/**
	 * @inheritDoc
	 */
	protected function isActive()
	{
		return is_plugin_active( 'breakdance/plugin.php' );
	}

	/**
	 * @inheritDoc
	 */
	protected function runIntegration()
	{
		add_action('init', function() {

			if (!function_exists('\Breakdance\DynamicData\registerField') or !class_exists('\Breakdance\DynamicData\Field')) {
				return;
			}

			BreakdanceProvider::init();
		});
	}
}
