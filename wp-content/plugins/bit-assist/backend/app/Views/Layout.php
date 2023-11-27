<?php

// phpcs:disable Squiz.NamingConventions.ValidVariableName.NotCamelCaps

namespace BitApps\Assist\Views;

use BitApps\Assist\Config;
use BitApps\Assist\Core\Helpers\DateTimeHelper;
use BitApps\Assist\Core\Hooks\Hooks;
use BitApps\Assist\Core\Utils\Capabilities;

/**
 * The admin Layout and page handler class.
 */
class Layout
{
    const FONT_URL = 'https://fonts.googleapis.com/css2?family=Outfit:wght@200;300;400;500;600;700&display=swap';

    public function __construct()
    {
        Hooks::addAction('in_admin_header', [$this, 'RemoveAdminNotices']);
        Hooks::addAction('admin_menu', [$this, 'sideBarMenuItem']);
        Hooks::addAction('admin_enqueue_scripts', [$this, 'head'], 0);
        Hooks::addFilter('style_loader_tag', [$this, 'linkTagFilter'], 0, 3);
        Hooks::addFilter('script_loader_tag', [$this, 'scriptTagFilter'], 0, 3);
    }

    /**
     * Register the admin left sidebar menu item.
     */
    public function sideBarMenuItem()
    {
        $menus = Hooks::applyFilter(Config::withPrefix('admin_sidebar_menu'), Config::get('SIDE_BAR_MENU'));
        global $submenu;
        foreach ($menus as $menu) {
            if (isset($menu['capability']) && Capabilities::check($menu['capability'])) {
                if ($menu['type'] == 'menu') {
                    add_menu_page(
                        $menu['title'],
                        $menu['name'],
                        $menu['capability'],
                        $menu['slug'],
                        is_string($menu['callback']) ? (method_exists($this, $menu['callback']) ? [$this, $menu['callback']] : $menu['callback']) : $menu['callback'],
                        $menu['icon'],
                        $menu['position']
                    );
                } else {
                    $submenu[$menu['parent']][] = [$menu['name'], $menu['capability'], 'admin.php?page=' . $menu['slug']];
                }
            }
        }
    }

    /**
     * Load the asset libraries.
     *
     * @param string $currentScreen $top_level_page variable for current page
     */
    public function head($currentScreen)
    {
        if (strpos($currentScreen, Config::SLUG) === false) {
            return;
        }

        $version = Config::VERSION;
        $assetUri = Config::get('ASSET_URI');
        $slug = Config::SLUG;

        // loading google fonts
        wp_enqueue_style('googleapis-PRECONNECT', 'https://fonts.googleapis.com');
        wp_enqueue_style('gstatic-PRECONNECT-CROSSORIGIN', 'https://fonts.gstatic.com');
        wp_enqueue_style('font', self::FONT_URL, [], $version);

        // wp_dequeue_script('wp-element');

        if (Config::isDev()) {
            wp_enqueue_script($slug . '-vite-client-helper-MODULE', Config::DEV_URL . '/config/devHotModule.js', [], null);
            wp_enqueue_script($slug . '-vite-client-MODULE', Config::DEV_URL . '/@vite/client', [], null);
            wp_enqueue_script($slug . '-index-MODULE', Config::DEV_URL . '/index.tsx', [], null);
        } else {
            wp_enqueue_script($slug . '-index-MODULE', $assetUri . '/index.js', [], $version);
            wp_enqueue_style($slug . '-styles', $assetUri . '/index.css', null, $version);
        }

        if (!wp_script_is('media-upload')) {
            wp_enqueue_media();
        }

        // wp_enqueue_script(
        //     $slug.'-vendors',
        //     $jsURI.'/vendors-main.js',
        //     null,
        //     $version,
        //     false
        // );

        // wp_enqueue_script(
        //     $slug.'-runtime',
        //     $jsURI.'/runtime.js',
        //     null,
        //     $version,
        //     true
        // );

        // if (wp_script_is('wp-i18n')) {
        //     $deps = [$slug.'-vendors', $slug.'-runtime', 'wp-i18n'];
        // } else {
        //     $deps = [$slug.'-vendors', $slug.'-runtime'];
        // }

        // wp_enqueue_script(
        //     $slug.'-admin-script',
        //     $jsURI.'/index.js',
        //     $deps,
        //     $version,
        //     true
        // );

        // wp_enqueue_style(
        //     $slug.'-styles',
        //     Config::get('ASSET_CSS_URI').Config::SLUG.'.css',
        //     null,
        //     $version,
        //     'screen'
        // );

        wp_localize_script(Config::SLUG . '-index-MODULE', Config::VAR_PREFIX, self::createConfigVariable());
    }

    public function body()
    {
        $rootURL = Config::get('ROOT_URI');

        // phpcs:disable Generic.PHP.ForbiddenFunctions.Found

        echo <<<HTML
        <noscript>You need to enable JavaScript to run this app.</noscript>
        <div id="bit-apps-root">
        <div
            style="display: flex;flex-direction: column;justify-content: center;
            align-items: center;height: 90vh;font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
            <img alt="bit-assist-logo" class="bit-logo" width="70" src="{$rootURL}/img/logo.svg">
            <h1>Welcome to Bit Assist</h1>
            <p></p>
        </div>
        </div>
HTML;
    }

    public function RemoveAdminNotices()
    {
        global $plugin_page;
        if (empty($plugin_page) || strpos($plugin_page, Config::SLUG) === false) {
            return;
        }

        remove_all_actions('admin_notices');
        remove_all_actions('all_admin_notices');
    }

    /**
     * Modify style tags.
     *
     * @param string $html   link tag
     * @param mixed  $handle
     * @param mixed  $href
     *
     * @return string new link tag
     */
    public function linkTagFilter($html, $handle, $href)
    {
        $slug = Config::SLUG;
        $newTag = $html;
        if (strpos($handle, 'PRECONNECT') !== false && strpos($handle, $slug) !== false) {
            $newTag = preg_replace('/rel=("|\')stylesheet("|\')/', 'rel="preconnect"', $newTag);
        }

        if (strpos($handle, 'PRELOAD') !== false && strpos($handle, $slug) !== false) {
            $newTag = preg_replace('/rel=("|\')stylesheet("|\')/', 'rel="preload"', $newTag);
        }

        if (strpos($handle, 'CROSSORIGIN') !== false && strpos($handle, $slug) !== false) {
            $newTag = preg_replace('/<link /', '<link crossorigin ', $newTag);
        }

        if (strpos($handle, 'SCRIPT') !== false && strpos($handle, $slug) !== false) {
            $newTag = preg_replace('/<link /', '<link as="script" ', $newTag);
        }

        return $newTag;
    }

    /**
     * Modify script tags.
     *
     * @param string $html   script tag
     * @param mixed  $handle
     * @param mixed  $href
     *
     * @return string new script tag
     */
    public function scriptTagFilter($html, $handle, $href)
    {
        $newTag = $html;
        if (strpos($handle, 'MODULE') !== false && strpos($handle, Config::SLUG) !== false) {
            $newTag = preg_replace('/<script /', '<script type="module" ', $newTag);
        }

        return $newTag;
    }

    public function createConfigVariable()
    {
        $frontendVars = apply_filters(
            Config::withPrefix('localized_script'),
            [
                'nonce'       => wp_create_nonce(Config::withPrefix('nonce')),
                'assetsURL'   => Config::get('ASSET_URI'),
                'rootURL'     => Config::get('ROOT_URI'),
                'baseURL'     => Config::get('ADMIN_URL') . 'admin.php?page=' . Config::SLUG . '#',
                'ajaxURL'     => admin_url('admin-ajax.php'),
                'api'         => Config::get('API_URL'),
                'routePrefix' => Config::VAR_PREFIX,
                'settings'    => Config::getOption('settings'),
                'dateFormat'  => Config::getOption('date_format', true),
                'timeFormat'  => Config::getOption('time_format', true),
                'timeZone'    => DateTimeHelper::wp_timezone_string(),
            ]
        );
        if (get_locale() !== 'en_US' && file_exists(Config::get('BASEDIR') . '/languages/generatedString.php')) {
            include_once Config::get('BASEDIR') . '/languages/generatedString.php';
            $frontendVars['translations'] = Config::withPrefix('i18n_strings');
        }

        return $frontendVars;
    }
}
