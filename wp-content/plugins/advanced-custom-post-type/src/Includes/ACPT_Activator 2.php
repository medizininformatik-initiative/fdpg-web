<?php

namespace ACPT\Includes;

use ACPT\Admin\ACPT_License_Manager;

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    advanced-custom-post-type
 * @subpackage advanced-custom-post-type/includes
 * @author     Mauro Cassani <maurocassani1978@gmail.com>
 */
class ACPT_Activator
{
    /**
     * Activate plugin
     *
     * @since    1.0.0
     */
    public static function activate()
    {
        if ( version_compare( PHP_VERSION, '5.6', '<=' ) ) {
            deactivate_plugins( plugin_basename( __FILE__ ) );
            wp_die( __( 'This plugin requires PHP Version 5.6 or greater.  Sorry about that.', 'acpt' ) );
        }

        // destroy any license
        ACPT_License_Manager::destroy();

        // check for version lite
        // and deactivate lite version if enabled
        $pluginLite = 'advanced-custom-post-type-lite/advanced-custom-post-type-lite.php';

        if (is_plugin_active($pluginLite) ) {
            deactivate_plugins($pluginLite);
        } else {
            ACPT_DB::createSchema();
            ACPT_DB::sync();
        }
    }
}