<?php

// phpcs:disable Squiz.NamingConventions.ValidVariableName.NotCamelCaps

namespace BitApps\AssistPro\Views;

use BitApps\Assist\Config as FreeConfig;
use BitApps\AssistPro\Config;
use BitApps\AssistPro\Core\Hooks\Hooks;

/**
 * The admin Layout and page handler class.
 */
class Layout
{
    public function __construct()
    {
        Hooks::addAction('in_admin_header', [$this, 'RemoveAdminNotices']);
        Hooks::addFilter(FreeConfig::withPrefix('localized_script'), [$this, 'createConfigVariable']);
        Hooks::addFilter(FreeConfig::withPrefix('admin_sidebar_menu'), [$this, 'filterSideBarMenu']);
    }

    public function RemoveAdminNotices()
    {
        global $plugin_page;
        if (empty($plugin_page) || strpos($plugin_page, FreeConfig::SLUG) === false) {
            return;
        }

        remove_all_actions('admin_notices');
        remove_all_actions('all_admin_notices');
    }

    public function filterSideBarMenu($prevArray)
    {
        $prevArray['Home']['title'] = 'Bit Assist Pro';
        $prevArray['Home']['name'] = 'Bit Assist Pro';

        return  $prevArray;
    }

    public function createConfigVariable($prevArray)
    {
        if (Config::isPro()) {
            $prevArray['isPro'] = true;
            $previousValue['proPluginVersion'] = Config::VERSION;
        }

        return $prevArray;
    }
}
