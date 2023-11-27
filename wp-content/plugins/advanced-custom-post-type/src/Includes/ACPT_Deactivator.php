<?php

namespace ACPT\Includes;

use ACPT\Admin\ACPT_License_Manager;
use ACPT\Core\Models\Settings\SettingsModel;
use ACPT\Core\Repository\SettingsRepository;

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    advanced-custom-post-type
 * @subpackage advanced-custom-post-type/includes
 * @author     Mauro Cassani <maurocassani1978@gmail.com>
 */
class ACPT_Deactivator
{
    /**
     * Deactivate plugin
     *
     * destroy schema only if `delete_tables_when_deactivate` settings is set to 1 (default value)
     *
     * @throws \Exception
     * @since    1.0.0
     */
    public static function deactivate()
    {
        $deleteTablesSettings = SettingsRepository::getSingle(SettingsModel::DELETE_TABLES_WHEN_DEACTIVATE_KEY);
        $destroySchema = ($deleteTablesSettings !== null) ? $deleteTablesSettings->getValue() : 0;

        if($destroySchema == 1){
            ACPT_DB::destroySchema();
        }

        ACPT_License_Manager::destroy();
    }
}