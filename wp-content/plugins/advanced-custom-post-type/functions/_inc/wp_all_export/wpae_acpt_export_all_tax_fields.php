<?php

use ACPT\Core\Repository\MetaRepository;
use ACPT\Costants\MetaTypes;
use ACPT\Integrations\WPAllExport\Helper\WPAEXmlFormatter;

if(!function_exists('wpae_acpt_export_all_tax_fields'))
{
	/**
	 * Usage in WP All Export:
	 * [wpae_acpt_export_all_tax_fields({Term ID})]
	 *
	 * @param $termId
	 *
	 * @return string
	 * @throws Exception
	 */
	function wpae_acpt_export_all_tax_fields($termId)
	{
		$taxonomyObject = get_term($termId);

		if($taxonomyObject === null){
			return null;
		}

		$taxonomy = $taxonomyObject->taxonomy;

		$metaBoxes = MetaRepository::get([
			'belongsTo' => MetaTypes::TAXONOMY,
			'find' => $taxonomy,
		]);

		$xml = WPAEXmlFormatter::formatMetadata($termId, MetaTypes::TAXONOMY, $taxonomy, $metaBoxes);

		return WPAEXmlFormatter::removeCDATA($xml);
	}
}