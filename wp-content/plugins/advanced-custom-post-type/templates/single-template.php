<?php
/**
 * The Template for displaying single custom post type
 *
 * This template can be overridden by copying it to yourtheme/single-{custom-post-type-slug}.php.
 *
 * @since      1.0.0
 * @package    advanced-custom-post-type
 * @subpackage advanced-custom-post-type/templates
 * @author     Mauro Cassani <maurocassani1978@gmail.com>
 */

use ACPT\Core\Repository\TemplateRepository;
use ACPT\Costants\MetaTypes;

defined( 'ABSPATH' ) || exit;

global $post;

try {
    $templates = TemplateRepository::getBy(MetaTypes::CUSTOM_POST_TYPE,  $post->post_type);
    $singleTemplate = null;

	foreach ($templates as $template){
		if($template->getTemplateType() === 'single'){
			$singleTemplate = $template;
		}

		// load all stylesheets for this CPT (including repeated field templates)
		add_action('wp_head', function () use ($template) {
			do_action( 'acpt_custom_styles', $template );
		}, 100);
	}

	if($singleTemplate){
		do_action( 'acpt_before_main_content' );
		do_action( 'acpt_template_content', $singleTemplate );
		do_action( 'acpt_after_main_content' );
	}

} catch (\Exception $exception){
    return 'There was an error generating the template: ' . $exception->getMessage();
}
