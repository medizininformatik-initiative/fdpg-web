<?php

// phpcs:disable Squiz.NamingConventions.ValidVariableName

namespace BitApps\AssistPro;

use BitApps\AssistPro\Core\Update\Updater;
use BitApps\AssistPro\Views\Layout;

if (!\defined('ABSPATH')) {
    exit;
}

/**
 * Provides App configurations.
 */
class Config
{
    const SLUG = 'bit-assist-pro';

    const TITLE = ' Bit Assist Pro';

    const VAR_PREFIX = 'bit_assist_pro';

    const VERSION = '1.0.2';

    const DB_VERSION = '1.0.0';

    const REQUIRED_PHP_VERSION = '5.6.4';

    const REQUIRED_WP_VERSION = '5.0';

    const API_VERSION = '1.0.0';

    const APP_BASE = '../../index.php';

    const DEV_URL = 'http://localhost:3000';

    /**
     * Provides configuration for plugin.
     *
     * @param string $type    Type of conf
     * @param string $default Default value
     *
     * @return null|array|string
     */
    public static function get($type, $default = null)
    {
        switch ($type) {
            case 'MAIN_FILE':
                return realpath(__DIR__ . DIRECTORY_SEPARATOR . self::APP_BASE);

            case 'BASENAME':
                return plugin_basename(trim(self::get('MAIN_FILE')));

            case 'BASEDIR':
                return plugin_dir_path(self::get('MAIN_FILE')) . 'backend';

            case 'ROOT_DIR':
                return plugin_dir_path(self::get('MAIN_FILE'));

            case 'SITE_URL':
                $parsedUrl = parse_url(get_admin_url());
                $siteUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
                $siteUrl .= empty($parsedUrl['port']) ? null : ':' . $parsedUrl['port'];

                return $siteUrl;

            case 'SITE_DOMAIN':
                $parsedUrl = parse_url(get_admin_url());
                return $parsedUrl['host'];

            case 'ADMIN_URL':
                return str_replace(self::get('SITE_URL'), '', get_admin_url());

            case 'API_URL':
                global $wp_rewrite;

                return [
                    'base'      => get_rest_url() . self::SLUG . '/v1',
                    'separator' => $wp_rewrite->permalink_structure ? '?' : '&',
                ];

            case 'ROOT_URI':
                return set_url_scheme(plugins_url('', self::get('MAIN_FILE')), parse_url(home_url())['scheme']);

            case 'ASSET_URI':
                return self::get('ROOT_URI') . '/assets';

            case 'ASSET_JS_URI':
                return self::get('ASSET_URI') . '/js';

            case 'ASSET_CSS_URI':
                return self::get('ASSET_URI') . '/css';

            case 'PLUGIN_PAGE_LINKS':
                return self::pluginPageLinks();

            case 'SIDE_BAR_MENU':
                return self::sideBarMenu();

            default:
                return $default;
        }
    }

    /**
     * Check license exists.
     *
     * @return bool
     */
    public static function isPro()
    {
        $licenseData = self::getOption('license_data');

        if (!empty($licenseData) && is_array($licenseData) && $licenseData['status'] === 'success') {
            return true;
        }

        return false;
    }

    /**
     * Prefixed variable name with prefix.
     *
     * @param string $option Variable name
     *
     * @return array
     */
    public static function withPrefix($option)
    {
        return self::VAR_PREFIX . $option;
    }

    /**
     * Retrieves options from option table.
     *
     * @param string $option  Option name
     * @param bool   $default default value
     * @param bool   $wp      Whether option is default wp option
     *
     * @return mixed
     */
    public static function getOption($option, $default = false, $wp = false)
    {
        if ($wp) {
            return get_option($option, $default);
        }

        return get_option(self::withPrefix($option), $default);
    }

    /**
     * Saves option to option table.
     *
     * @param string $option   Option name
     * @param bool   $autoload Whether option will autoload
     * @param mixed  $value
     *
     * @return bool
     */
    public static function addOption($option, $value, $autoload = false)
    {
        return add_option(self::withPrefix($option), $value, '', $autoload ? 'yes' : 'no');
    }

    /**
     * Delete option from option table.
     *
     * @param string $option   Option name
     *
     * @return bool
     */
    public static function deleteOption($option)
    {
        return delete_option(self::withPrefix($option));
    }

    /**
     * Save or update option to option table.
     *
     * @param string $option   Option name
     * @param mixed  $value    Option value
     * @param bool   $autoload Whether option will autoload
     *
     * @return bool
     */
    public static function updateOption($option, $value, $autoload = null)
    {
        return update_option(self::withPrefix($option), $value, !\is_null($autoload) ? 'yes' : null);
    }

    public static function isDev()
    {
        return \defined('BITAPPS_DEV') && BITAPPS_DEV;
    }

    /**
     * Provides links for plugin pages. Those links will bi displayed in
     * all plugin pages under the plugin name.
     *
     * @return array
     */
    private static function pluginPageLinks()
    {
        return [];
    }

    /**
     * Provides menus for wordpress admin sidebar.
     * should return an array of menus with the following structure:
     * [
     *   'type' => menu | submenu,
     *  'name' => 'Name of menu will shown in sidebar',
     *  'capability' => 'capability required to access menu',
     *  'slug' => 'slug of menu after ?page=',.
     *
     *  'title' => 'page title will be shown in browser title if type is menu',
     *  'callback' => 'function to call when menu is clicked',
     *  'icon' =>   'icon to display in menu if menu type is menu',
     *  'position' => 'position of menu in sidebar if menu type is menu',
     *
     * 'parent' => 'parent slug if submenu'
     * ]
     *
     * @return array
     */
    private static function sideBarMenu()
    {
        return [];
    }
}
