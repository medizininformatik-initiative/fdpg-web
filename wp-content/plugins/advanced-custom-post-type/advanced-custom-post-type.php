<?php

/**.0
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              #
 * @since             1.0.0
 * @package           advanced-custom-post-type
 *
 * @wordpress-plugin
 * Plugin Name:       ACPT
 * Plugin URI:        https://acpt.io
 * Description:       Create and manage custom post types, with advanced custom fields and taxonomies management
 * Version:           1.0.190
 * Author:            Mauro Cassani
 * Author URI:        https://github.com/mauretto78
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       advanced-custom-post-type
 * Domain Path:       /advanced-custom-post-type
 */

use ACPT\Admin\ACPT_Updater;
use ACPT\Includes\ACPT_Activator;
use ACPT\Includes\ACPT_DB;
use ACPT\Includes\ACPT_Deactivator;
use ACPT\Includes\ACPT_Loader;
use ACPT\Includes\ACPT_Plugin;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

// Fix PHP headers
ob_start();

/**
 * plugin settings
 */
define( 'ACPT_PLUGIN_NAME', 'advanced-custom-post-type' );
define( 'ACPT_PLUGIN_VERSION', '1.0.190' );

/**
 * Composer init
 */
require_once(plugin_dir_path(__FILE__) . '/vendor/autoload.php');

/**
 * Avoid Call to undefined function is_plugin_active() error
 */
if( !function_exists('is_plugin_active') ) {
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

class ACPT
{
    /**
     * Check for plugin upgrades
     */
    public static function checkForPluginUpgrades()
    {
        $old_version = get_option('acpt_version', 0);
        $current_version = filemtime(__FILE__);

        if ($old_version != $current_version) {

            ACPT_DB::createSchema();
            ACPT_DB::sync();

            update_option('acpt_version', $current_version, false);
        }
    }
}

/**
 * The code that runs during plugin activation.
 */
function activationHook()
{
	ACPT_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * @throws Exception
 */
function deactivationHook()
{
	ACPT_Deactivator::deactivate();
}

register_activation_hook( __FILE__,  'activationHook' );
register_deactivation_hook( __FILE__, 'deactivationHook' );

ACPT::checkForPluginUpgrades();

/**
 * Updates management
 */
$updated = new ACPT_Updater(__FILE__);
$updated->initialize();
$updated->sendInsights();

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
try {
    $plugin = new ACPT_Plugin( new ACPT_Loader());
    $plugin->run();
} catch (\Exception $exception){

    function wpb_admin_notice_error() {
        echo '<div class="notice notice-error is-dismissible">
            <p>Something went wrong.</p>
      </div>';
    }

    add_action( 'admin_notices', 'wpb_admin_notice_error' );
}

