<?php
/**
 * The Template for displaying archive custom post type page
 *
 * This template can be overridden by copying it to yourtheme/archive-{custom-post-type-slug}.php.
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
	$archiveTemplate = null;

	foreach ($templates as $template){
		if($template->getTemplateType() === 'archive'){
			$archiveTemplate = $template;
		}

		// load all stylesheets for this CPT (including repeated field templates)
		add_action('wp_head', function () use ($template) {
			do_action( 'acpt_custom_styles', $template );
		}, 100);
	}

//	var_dump($archiveTemplate->getDecodedHtml());
//die();

    do_action( 'acpt_before_main_content' );
    do_action( 'acpt_template_content', $archiveTemplate );
    do_action( 'acpt_after_main_content' );
} catch (\Exception $exception){
    return 'There was an error generating the template: ' . $exception->getMessage();
}
