<?php
/**
 * Plugin Name: Bit Assist Pro
 * Plugin URI:  https://bitapps.pro/bit-assist
 * Description: Communicate with your customers using different messaging apps.
 * Version:     1.0.2
 * Author:      Bit Apps
 * Author URI:  https://bitapps.pro
 * Text Domain: bit-assist
 * Requires PHP: 5.6
 * Requires WP: 5.0
 * Domain Path: /languages
 * License: gpl2+
 */

function includeBitAssistProLoader()
{
    if (!did_action('bit_assist_loaded')) {
        add_action('admin_notices', 'bitAssistNotFound');
        return;
    }

    require_once plugin_dir_path(__FILE__) . 'backend/bootstrap.php';
}

add_action('plugins_loaded', 'includeBitAssistProLoader', 12);

function bitAssistNotFound()
{
    echo '<div class="notice notice-error is-dismissible"><p>Bit Assist plugin is required</p></div>';
}
