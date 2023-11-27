<?php
/**
 * The Template for displaying single custom post type
 *
 * This template can be overridden by copying it to yourtheme/taxonomy-{slug}.php.
 *
 * @since      1.0.140
 * @package    advanced-custom-post-type
 * @subpackage advanced-custom-post-type/templates
 * @author     Mauro Cassani <maurocassani1978@gmail.com>
 */

use ACPT\Core\Repository\TemplateRepository;
use ACPT\Costants\MetaTypes;

defined( 'ABSPATH' ) || exit;

try {
    $queriedObject = get_queried_object();
    $template = TemplateRepository::get(MetaTypes::TAXONOMY, 'single', $queriedObject->taxonomy);

    add_action('wp_head', function () use ($template) {
        do_action( 'acpt_custom_styles', $template );
    }, 100);

    do_action( 'acpt_before_main_content' );
    do_action( 'acpt_template_content', $template );
    do_action( 'acpt_after_main_content' );

} catch (\Exception $exception){
    return 'There was an error generating the template: ' . $exception->getMessage();
}