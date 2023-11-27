<?php

use ACPT\Core\Repository\MetaRepository;
use ACPT\Costants\MetaTypes;
use ACPT\Integrations\WPAllExport\Helper\WPAEXmlFormatter;

if(!function_exists('wpae_acpt_export_all_user_fields'))
{
	/**
	 * Usage in WP All Export:
	 * [wpae_acpt_export_all_user_fields({ID})]
	 *
	 * @param $userId
	 *
	 * @return string
	 * @throws Exception
	 */
	function wpae_acpt_export_all_user_fields($userId)
	{
		$metaBoxes = MetaRepository::get([
			'belongsTo' => MetaTypes::USER,
		]);

		$xml = WPAEXmlFormatter::formatMetadata($userId, MetaTypes::USER, null, $metaBoxes);

		return WPAEXmlFormatter::removeCDATA($xml);
	}
}