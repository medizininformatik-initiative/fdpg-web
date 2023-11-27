<?php

use ACPT\Core\Repository\TemplateRepository;
use ACPT\Costants\MetaTypes;

if ( ! defined( 'ABSPATH' ) ) exit;

global $post;

try {
    $template = TemplateRepository::get(MetaTypes::CUSTOM_POST_TYPE, 'single', $post->post_type);

    add_action('wp_head', function () use ($template) {
        do_action('acpt_custom_styles', $template);
    }, 100);

    do_action('acpt_before_main_content');
    do_action('acpt_single_content', $template);
    do_action('acpt_after_main_content');
} catch (\Exception $exception){
    return 'There was an error generating the template: ' . $exception->getMessage();
}
