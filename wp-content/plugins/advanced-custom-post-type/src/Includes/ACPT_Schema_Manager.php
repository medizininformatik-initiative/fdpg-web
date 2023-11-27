<?php

namespace ACPT\Includes;

use ACPT\Costants\MetaTypes;

class ACPT_Schema_Manager
{
    /**
     * Creates the schema
     *
     * @return bool
     */
    public static function up()
    {
        $conn = ACPT_DB::getDbConn();
        $charset_collate = self::getCharsetCollation();

        ///////////////////////////////////////////////////
        /// 1. CREATE TABLES
        ///////////////////////////////////////////////////

        // custom_post_type
        $sql1 = "CREATE TABLE IF NOT EXISTS `".ACPT_DB::TABLE_CUSTOM_POST_TYPE."` (
            id VARCHAR(36) UNIQUE NOT NULL,
            post_name VARCHAR(20) UNIQUE NOT NULL,
            singular VARCHAR(255) NOT NULL,
            plural VARCHAR(255) NOT NULL,
            icon VARCHAR(50) NOT NULL,
            native TINYINT(1) DEFAULT 0,
            supports TEXT,
            labels TEXT,
            settings TEXT,
            PRIMARY KEY(id)
        ) $charset_collate;";

        // custom post type meta box
        $sql2 = "CREATE TABLE IF NOT EXISTS `".ACPT_DB::TABLE_CUSTOM_POST_TYPE_META_BOX."` (
            id VARCHAR(36) UNIQUE NOT NULL,
            post_type VARCHAR(20) NOT NULL,
            meta_box_name VARCHAR(50) NOT NULL,
            sort INT(11),
            PRIMARY KEY(id)
        ) $charset_collate;";

        // meta field (FROM v.1.0.140 THIS TABLE IS THE ONLY ONE META FIELD TABLE IN USE)
        $sql3 = "CREATE TABLE IF NOT EXISTS `".ACPT_DB::TABLE_CUSTOM_POST_TYPE_FIELD."` (
            id VARCHAR(36) UNIQUE NOT NULL,
            meta_box_id VARCHAR(36) NOT NULL,
            field_name VARCHAR(50) NOT NULL,
            field_type VARCHAR(50) NOT NULL,
            field_default_value VARCHAR(50) DEFAULT NULL,
            field_description TEXT DEFAULT NULL,
            showInArchive TINYINT(1) NOT NULL,
            required TINYINT(1) NOT NULL,
            sort INT(11),
            PRIMARY KEY(id)
        ) $charset_collate;";

        // meta options (FROM v.1.0.140 THIS TABLE IS THE ONLY ONE META FIELD OPTIONS TABLE IN USE)
        $sql4 = "CREATE TABLE IF NOT EXISTS `".ACPT_DB::TABLE_CUSTOM_POST_TYPE_OPTION."` (
            id VARCHAR(36) UNIQUE NOT NULL,
            meta_box_id VARCHAR(36) NOT NULL,
            meta_field_id VARCHAR(36) NOT NULL,
            option_label VARCHAR(50) NOT NULL,
            option_value VARCHAR(50) NOT NULL,
            sort INT(11),
            PRIMARY KEY(id)
        ) $charset_collate;";

	    // advanced options
	    $sql5 = "CREATE TABLE IF NOT EXISTS `".ACPT_DB::TABLE_CUSTOM_POST_TYPE_ADVANCED_OPTION."` (
            id VARCHAR(36) UNIQUE NOT NULL,
            meta_box_id VARCHAR(36) NOT NULL,
            meta_field_id VARCHAR(36) NOT NULL,
            option_key VARCHAR(50) NOT NULL,
            option_value VARCHAR(50) NOT NULL,
            PRIMARY KEY(id)
        ) $charset_collate;";

        // relations
        $sql6 = "CREATE TABLE IF NOT EXISTS `".ACPT_DB::TABLE_CUSTOM_POST_TYPE_RELATION."` (
            id VARCHAR(36) UNIQUE NOT NULL,
            meta_box_id VARCHAR(36) NOT NULL,
            meta_field_id VARCHAR(36) NOT NULL,
            relationship VARCHAR(50) NOT NULL,
            related_post_type VARCHAR(20) NOT NULL,
            inversed_meta_box_id VARCHAR(36) DEFAULT NULL,
            inversed_meta_box_name VARCHAR(50) DEFAULT NULL,
            inversed_meta_field_id VARCHAR(36) DEFAULT NULL,
            inversed_meta_field_name VARCHAR(50) DEFAULT NULL,
            PRIMARY KEY(id)
        ) $charset_collate;";

        // custom_post_type_import
        $sql7 = "CREATE TABLE IF NOT EXISTS `".ACPT_DB::TABLE_CUSTOM_POST_TYPE_IMPORT."` (
            id VARCHAR(36) UNIQUE NOT NULL,
            file VARCHAR(255) NOT NULL,
            url VARCHAR(255) NOT NULL,
            file_type VARCHAR(36) DEFAULT NULL,
            user_id INT(11),
            content TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY(id)
        ) $charset_collate;";

        // taxonomy
        $sql8 = "CREATE TABLE IF NOT EXISTS `".ACPT_DB::TABLE_TAXONOMY."` (
            id VARCHAR(36) UNIQUE NOT NULL,
            slug VARCHAR(32) UNIQUE NOT NULL,
            singular VARCHAR(255) NOT NULL,
            plural VARCHAR(255) NOT NULL,
            labels TEXT,
            settings TEXT,
            PRIMARY KEY(id)
        ) $charset_collate;";

        // taxonomy pivot
        $sql9 = "CREATE TABLE IF NOT EXISTS `".ACPT_DB::TABLE_TAXONOMY_PIVOT."` (
            custom_post_type_id VARCHAR(36) NOT NULL,
            taxonomy_id VARCHAR(36) NOT NULL,
            PRIMARY KEY( `custom_post_type_id`, `taxonomy_id`)
        ) $charset_collate;";

        // acpt_custom_post_template
        $sql10 = "CREATE TABLE IF NOT EXISTS `".ACPT_DB::TABLE_CUSTOM_POST_TEMPLATE."` (
            id VARCHAR(36) UNIQUE NOT NULL,
            post_type VARCHAR(20) NOT NULL,
            template_type VARCHAR(36) DEFAULT NULL,
            json TEXT,
            html TEXT,
            meta TEXT,
            PRIMARY KEY(id)
        ) $charset_collate;";

        // acpt_settings
        $sql11 = "CREATE TABLE IF NOT EXISTS `".ACPT_DB::TABLE_SETTINGS."` (
            id VARCHAR(36) UNIQUE NOT NULL,
            meta_key VARCHAR(32) UNIQUE NOT NULL,
            meta_value VARCHAR(255) NOT NULL,
            PRIMARY KEY(id)
        ) $charset_collate;";

        // woocommerce product data
        $sql12 = "CREATE TABLE IF NOT EXISTS `".ACPT_DB::TABLE_WOOCOMMERCE_PRODUCT_DATA."` (
            id VARCHAR(36) UNIQUE NOT NULL,
            product_data_name VARCHAR(32) NOT NULL,
            icon VARCHAR(255) NOT NULL,
            visibility TEXT NOT NULL,
            show_in_ui TINYINT(1) NOT NULL,
            content TEXT DEFAULT NULL,
            PRIMARY KEY(id)
        ) $charset_collate;";

        // woocommerce product data field
        $sql13 = "CREATE TABLE IF NOT EXISTS `".ACPT_DB::TABLE_WOOCOMMERCE_PRODUCT_DATA_FIELD."` (
            id VARCHAR(36) UNIQUE NOT NULL,
            product_data_id VARCHAR(36) NOT NULL,
            field_name VARCHAR(50) NOT NULL,
            field_type VARCHAR(50) NOT NULL,
            field_default_value VARCHAR(50) DEFAULT NULL,
            field_description TEXT DEFAULT NULL,
            required TINYINT(1) NOT NULL,
            sort INT(11),
            PRIMARY KEY(id)
        ) $charset_collate;";

        // woocommerce product data field option
        $sql14 = "CREATE TABLE IF NOT EXISTS `".ACPT_DB::TABLE_WOOCOMMERCE_PRODUCT_DATA_OPTION."` (
            id VARCHAR(36) UNIQUE NOT NULL,
            product_data_id VARCHAR(36) NOT NULL,
            product_data_field_id VARCHAR(36) NOT NULL,
            option_label VARCHAR(50) NOT NULL,
            option_value VARCHAR(50) NOT NULL,
            sort INT(11),
            PRIMARY KEY(id)
        ) $charset_collate;";

        // api keys
        $sql15 = "CREATE TABLE IF NOT EXISTS `".ACPT_DB::TABLE_API_KEYS."` (
            id VARCHAR(36) UNIQUE NOT NULL,
            uid INT(11) UNIQUE NOT NULL,
            api_key VARCHAR(36) NOT NULL,
            api_secret VARCHAR(36) NOT NULL,
            created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY(`id`, `uid`),
            UNIQUE KEY `api_key_and_secret` (`api_key`, `api_secret`) USING BTREE
        ) $charset_collate;";

        // user meta box
        $sql16 = "CREATE TABLE IF NOT EXISTS `".ACPT_DB::TABLE_USER_META_BOX."` (
            id VARCHAR(36) UNIQUE NOT NULL,
            meta_box_name VARCHAR(50) NOT NULL,
            sort INT(11),
            PRIMARY KEY(id)
        ) $charset_collate;";

        // user meta field (FROM v.1.0.140 THIS TABLE IS NO LONGER USED)
        $sql17 = "CREATE TABLE IF NOT EXISTS `".ACPT_DB::TABLE_USER_META_FIELD."` (
            id VARCHAR(36) UNIQUE NOT NULL,
            user_meta_box_id VARCHAR(36) NOT NULL,
            field_name VARCHAR(50) NOT NULL,
            field_type VARCHAR(50) NOT NULL,
            field_default_value VARCHAR(50) DEFAULT NULL,
            field_description TEXT DEFAULT NULL,
            showInArchive TINYINT(1) NOT NULL,
            required TINYINT(1) NOT NULL,
            sort INT(11),
            PRIMARY KEY(id)
        ) $charset_collate;";

        // user meta field option (FROM v.1.0.140 THIS TABLE IS NO LONGER USED)
        $sql18 = "CREATE TABLE IF NOT EXISTS `".ACPT_DB::TABLE_USER_META_FIELD_OPTION."` (
            id VARCHAR(36) UNIQUE NOT NULL,
            user_meta_box_id VARCHAR(36) NOT NULL,
            user_meta_field_id VARCHAR(36) NOT NULL,
            option_label VARCHAR(50) NOT NULL,
            option_value VARCHAR(50) NOT NULL,
            sort INT(11),
            PRIMARY KEY(id)
        ) $charset_collate;";

        // meta visibility conditions
        $sql19 = "CREATE TABLE IF NOT EXISTS `".ACPT_DB::TABLE_CUSTOM_POST_TYPE_VISIBILITY."` (
            id VARCHAR(36) UNIQUE NOT NULL,
            meta_box_id VARCHAR(36) NOT NULL,
            meta_field_id VARCHAR(36) NOT NULL,
            visibility_type TEXT NOT NULL,
            operator VARCHAR(20) NOT NULL,
            visibility_value VARCHAR(255) NOT NULL,
            logic VARCHAR(3) DEFAULT NULL,
            sort INT(11),
            PRIMARY KEY(id)
        ) $charset_collate;";

        // custom post type meta box
        $sql20 = "CREATE TABLE IF NOT EXISTS `".ACPT_DB::TABLE_TAXONOMY_META_BOX."` (
            id VARCHAR(36) UNIQUE NOT NULL,
            taxonomy VARCHAR(20) NOT NULL,
            meta_box_name VARCHAR(50) NOT NULL,
            sort INT(11),
            PRIMARY KEY(id)
        ) $charset_collate;";

        // option page
	    $sql21 = "CREATE TABLE IF NOT EXISTS `".ACPT_DB::TABLE_OPTION_PAGE."` (
            id VARCHAR(36) UNIQUE NOT NULL,
            page_title VARCHAR(64) NOT NULL,
            menu_title VARCHAR(64) NOT NULL,
            capability VARCHAR(64) NOT NULL,
            menu_slug VARCHAR(64) UNIQUE NOT NULL,
            icon VARCHAR(50) DEFAULT NULL,
            description TEXT,
            parent_id VARCHAR(36) DEFAULT NULL,
            sort INT(11),
            page_position INT(11),
            PRIMARY KEY(id)
        ) $charset_collate;";

	    // option page box
	    $sql22 = "CREATE TABLE IF NOT EXISTS `".ACPT_DB::TABLE_OPTION_PAGE_META_BOX."` (
            id VARCHAR(36) UNIQUE NOT NULL,
            page VARCHAR(64) NOT NULL,
            meta_box_name VARCHAR(50) NOT NULL,
            sort INT(11),
            PRIMARY KEY(id)
        ) $charset_collate;";

	    // custom post type meta block
	    $sql23 = "CREATE TABLE IF NOT EXISTS `".ACPT_DB::TABLE_CUSTOM_POST_TYPE_BLOCK."` (
            id VARCHAR(36) UNIQUE NOT NULL,
            meta_box_id VARCHAR(36) NOT NULL,
            meta_field_id VARCHAR(36) NOT NULL,
            block_name VARCHAR(50) NOT NULL,
            block_label VARCHAR(255) DEFAULT NULL,
            sort INT(11),
            PRIMARY KEY(id)
        ) $charset_collate;";

        $conn->query($sql1);
        $conn->query($sql2);
        $conn->query($sql3);
        $conn->query($sql4);
        $conn->query($sql5);
        $conn->query($sql6);
        $conn->query($sql7);
        $conn->query($sql8);
        $conn->query($sql9);
        $conn->query($sql10);
        $conn->query($sql11);
        $conn->query($sql12);
        $conn->query($sql13);
        $conn->query($sql14);
        $conn->query($sql15);
        $conn->query($sql16);
        $conn->query($sql17);
        $conn->query($sql18);
        $conn->query($sql19);
        $conn->query($sql20);
        $conn->query($sql21);
        $conn->query($sql22);
        $conn->query($sql23);

        ///////////////////////////////////////////////////
        /// 2. ALTER TABLES
        ///////////////////////////////////////////////////

        // add `native` to TABLE_TAXONOMY
        $result = $conn->query("SHOW TABLES LIKE '".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_TAXONOMY)."'");
        if($result->num_rows == 1){
            if(false === ACPT_DB::checkIfColumnExistsInTable(ACPT_DB::prefixedTableName(ACPT_DB::TABLE_TAXONOMY), 'native') ) {
                $conn->query("ALTER TABLE `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_TAXONOMY)."` ADD `native` TINYINT(1) NULL DEFAULT NULL ");
            }
        } else {
            if(false === ACPT_DB::checkIfColumnExistsInTable(ACPT_DB::TABLE_TAXONOMY, 'native') ) {
                $conn->query("ALTER TABLE `".ACPT_DB::TABLE_TAXONOMY."` ADD `native` TINYINT(1) NULL DEFAULT NULL ");
            }
        }

        // add `parent_id` to TABLE_CUSTOM_POST_TYPE_FIELD
        $result = $conn->query("SHOW TABLES LIKE '".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE_FIELD)."'");
        if($result->num_rows == 1){
            if(false === ACPT_DB::checkIfColumnExistsInTable(ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE_FIELD), 'parent_id')){
                $conn->query("ALTER TABLE `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE_FIELD)."` ADD `parent_id` VARCHAR(36) DEFAULT NULL ");
            }

	        if(false === ACPT_DB::checkIfColumnExistsInTable(ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE_FIELD), 'block_id')){
		        $conn->query("ALTER TABLE `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE_FIELD)."` ADD `block_id` VARCHAR(36) DEFAULT NULL ");
	        }

	        if(false === ACPT_DB::checkIfColumnExistsInTable(ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE_FIELD), 'quick_edit')){
		        $conn->query("ALTER TABLE `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE_FIELD)."` ADD `quick_edit` TINYINT(1) DEFAULT NULL ");
	        }

	        if(false === ACPT_DB::checkIfColumnExistsInTable(ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE_FIELD), 'filter_in_admin')){
		        $conn->query("ALTER TABLE `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE_FIELD)."` ADD `filter_in_admin` TINYINT(1) DEFAULT NULL ");
	        }

        } else {
            if(false === ACPT_DB::checkIfColumnExistsInTable(ACPT_DB::TABLE_CUSTOM_POST_TYPE_FIELD, 'parent_id')){
                $conn->query("ALTER TABLE `".ACPT_DB::TABLE_CUSTOM_POST_TYPE_FIELD."` ADD `parent_id` VARCHAR(36) DEFAULT NULL ");
            }

	        if(false === ACPT_DB::checkIfColumnExistsInTable(ACPT_DB::TABLE_CUSTOM_POST_TYPE_FIELD, 'block_id')){
		        $conn->query("ALTER TABLE `".ACPT_DB::TABLE_CUSTOM_POST_TYPE_FIELD."` ADD `block_id` VARCHAR(36) DEFAULT NULL ");
	        }

	        if(false === ACPT_DB::checkIfColumnExistsInTable(ACPT_DB::TABLE_CUSTOM_POST_TYPE_FIELD, 'quick_edit')){
		        $conn->query("ALTER TABLE `".ACPT_DB::TABLE_CUSTOM_POST_TYPE_FIELD."` ADD `quick_edit` TINYINT(1) DEFAULT NULL ");
	        }

	        if(false === ACPT_DB::checkIfColumnExistsInTable(ACPT_DB::TABLE_CUSTOM_POST_TYPE_FIELD, 'filter_in_admin')){
		        $conn->query("ALTER TABLE `".ACPT_DB::TABLE_CUSTOM_POST_TYPE_FIELD."` ADD `filter_in_admin` TINYINT(1) DEFAULT NULL ");
	        }
        }

        $result = $conn->query("SHOW TABLES LIKE '".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TEMPLATE)."'");

        if($result->num_rows == 1){

            if(false === ACPT_DB::checkIfColumnExistsInTable(ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TEMPLATE), 'meta_field_id')){
                $conn->query("ALTER TABLE `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TEMPLATE)."` ADD `meta_field_id` VARCHAR(36) DEFAULT NULL ");
            }

            if(false === ACPT_DB::checkIfColumnExistsInTable(ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TEMPLATE), 'belongs_to')){
                $conn->query("ALTER TABLE `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TEMPLATE)."` ADD `belongs_to` VARCHAR(36) DEFAULT '" . MetaTypes::CUSTOM_POST_TYPE. "'");
            }

            $conn->query("ALTER TABLE `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TEMPLATE)."` CHANGE `post_type` `find` VARCHAR(20) ");

        } else {
            if(false === ACPT_DB::checkIfColumnExistsInTable(ACPT_DB::TABLE_CUSTOM_POST_TEMPLATE, 'meta_field_id')){
                $conn->query("ALTER TABLE `".ACPT_DB::TABLE_CUSTOM_POST_TEMPLATE."` ADD `meta_field_id` VARCHAR(36) DEFAULT '" . MetaTypes::CUSTOM_POST_TYPE. "'");
            }

            if(false === ACPT_DB::checkIfColumnExistsInTable(ACPT_DB::TABLE_CUSTOM_POST_TEMPLATE, 'belongs_to')){
                $conn->query("ALTER TABLE `".ACPT_DB::TABLE_CUSTOM_POST_TEMPLATE."` ADD `belongs_to` VARCHAR(36) DEFAULT NULL ");
            }

            $conn->query("ALTER TABLE `".ACPT_DB::TABLE_CUSTOM_POST_TEMPLATE."` CHANGE `post_type` `find` VARCHAR(20)  ");
        }

	    // add `meta_box_label` to TABLE_CUSTOM_POST_TYPE_META_BOX, TABLE_TAXONOMY_META_BOX, TABLE_USER_META_BOX and TABLE_OPTION_PAGE_META_BOX
	    $result = $conn->query("SHOW TABLES LIKE '".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE_META_BOX)."'");
	    if($result->num_rows == 1){
		    if(false === ACPT_DB::checkIfColumnExistsInTable(ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE_META_BOX), 'meta_box_label')){
			    $conn->query("ALTER TABLE `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE_META_BOX)."` ADD `meta_box_label` VARCHAR(255) DEFAULT NULL ");
		    }
	    } else {
		    if(false === ACPT_DB::checkIfColumnExistsInTable(ACPT_DB::TABLE_CUSTOM_POST_TYPE_META_BOX, 'meta_box_label')){
			    $conn->query("ALTER TABLE `".ACPT_DB::TABLE_CUSTOM_POST_TYPE_META_BOX."` ADD `meta_box_label` VARCHAR(255) DEFAULT NULL ");
		    }
	    }

	    $result = $conn->query("SHOW TABLES LIKE '".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE)."'");
	    if($result->num_rows == 1){
		    $conn->query("ALTER TABLE `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE)."` CHANGE COLUMN `icon` `icon` VARCHAR(255) NOT NULL ");
	    } else {
	        $conn->query("ALTER TABLE `".ACPT_DB::TABLE_CUSTOM_POST_TYPE."` CHANGE COLUMN `icon` `icon` VARCHAR(255) NOT NULL ");
	    }

	    $result = $conn->query("SHOW TABLES LIKE '".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_OPTION_PAGE)."'");
	    if($result->num_rows == 1){
		    $conn->query("ALTER TABLE `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_OPTION_PAGE)."` CHANGE COLUMN `icon` `icon` VARCHAR(255) NOT NULL ");
	    } else {
		    $conn->query("ALTER TABLE `".ACPT_DB::TABLE_OPTION_PAGE."` CHANGE COLUMN `icon` `icon` VARCHAR(255) NOT NULL ");
	    }

	    $result = $conn->query("SHOW TABLES LIKE '".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_TAXONOMY_META_BOX)."'");
	    if($result->num_rows == 1){
		    if(false === ACPT_DB::checkIfColumnExistsInTable(ACPT_DB::prefixedTableName(ACPT_DB::TABLE_TAXONOMY_META_BOX), 'meta_box_label')){
			    $conn->query("ALTER TABLE `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_TAXONOMY_META_BOX)."` ADD `meta_box_label` VARCHAR(255) DEFAULT NULL ");
		    }
	    } else {
		    if(false === ACPT_DB::checkIfColumnExistsInTable(ACPT_DB::TABLE_TAXONOMY_META_BOX, 'meta_box_label')){
			    $conn->query("ALTER TABLE `".ACPT_DB::TABLE_TAXONOMY_META_BOX."` ADD `meta_box_label` VARCHAR(255) DEFAULT NULL ");
		    }
	    }

	    $result = $conn->query("SHOW TABLES LIKE '".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_USER_META_BOX)."'");
	    if($result->num_rows == 1){
		    if(false === ACPT_DB::checkIfColumnExistsInTable(ACPT_DB::prefixedTableName(ACPT_DB::TABLE_USER_META_BOX), 'meta_box_label')){
			    $conn->query("ALTER TABLE `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_USER_META_BOX)."` ADD `meta_box_label` VARCHAR(255) DEFAULT NULL ");
		    }
	    } else {
		    if(false === ACPT_DB::checkIfColumnExistsInTable(ACPT_DB::TABLE_USER_META_BOX, 'meta_box_label')){
			    $conn->query("ALTER TABLE `".ACPT_DB::TABLE_USER_META_BOX."` ADD `meta_box_label` VARCHAR(255) DEFAULT NULL ");
		    }
	    }

	    $result = $conn->query("SHOW TABLES LIKE '".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_OPTION_PAGE_META_BOX)."'");
	    if($result->num_rows == 1){
		    if(false === ACPT_DB::checkIfColumnExistsInTable(ACPT_DB::prefixedTableName(ACPT_DB::TABLE_OPTION_PAGE_META_BOX), 'meta_box_label')){
			    $conn->query("ALTER TABLE `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_OPTION_PAGE_META_BOX)."` ADD `meta_box_label` VARCHAR(255) DEFAULT NULL ");
		    }
	    } else {
		    if(false === ACPT_DB::checkIfColumnExistsInTable(ACPT_DB::TABLE_OPTION_PAGE_META_BOX, 'meta_box_label')){
			    $conn->query("ALTER TABLE `".ACPT_DB::TABLE_OPTION_PAGE_META_BOX."` ADD `meta_box_label` VARCHAR(255) DEFAULT NULL ");
		    }
	    }

	    $result = $conn->query("SHOW TABLES LIKE '".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE_RELATION)."'");
	    if($result->num_rows == 1){
		    $conn->query("ALTER TABLE `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE_RELATION)."` CHANGE COLUMN `related_post_type` `related_post_type` TEXT NOT NULL ");
	    } else {
		    $conn->query("ALTER TABLE `".ACPT_DB::prefixedTableName(ACPT_DB::TABLE_CUSTOM_POST_TYPE_RELATION)."` CHANGE COLUMN `related_post_type` `related_post_type` TEXT NOT NULL ");
	    }

        ///////////////////////////////////////////////////
        /// 3. ADD PREFIX TO TABLES
        ///////////////////////////////////////////////////

        foreach (self::getAllUnprefixedTables() as $table){
            if ($result = $conn->query("SHOW TABLES LIKE '".ACPT_DB::prefixedTableName($table)."'")) {
                if($result->num_rows !== 1) {
                    $conn->query("RENAME TABLE `".$table."` TO `".ACPT_DB::prefixedTableName($table)."`;");
                }
            }
        }

        ///////////////////////////////////////////////////
        /// 4. REMOVE LEGACY TABLES
        ///////////////////////////////////////////////////

        foreach (self::getAllUnprefixedTables() as $table){
            $conn->query("DROP TABLE IF EXISTS `".$table."`;");
        }

        return empty($conn->last_error);
    }

    /**
     * Return the correct charset collation
     *
     * @return string
     */
    private static function getCharsetCollation()
    {
        global $wpdb;

        $charset_collate = "";
        $collation = $wpdb->get_row("SHOW FULL COLUMNS FROM {$wpdb->posts} WHERE field = 'post_content'");

        if(isset($collation->Collation)) {
            $charset = explode('_', $collation->Collation);

            if(is_array($charset) && count($charset) > 1) {
                $charset = $charset[0];
                $charset_collate = "DEFAULT CHARACTER SET {$charset} COLLATE {$collation->Collation}";
            }
        }

        if(empty($charset_collate)) { $charset_collate = $wpdb->get_charset_collate(); }

        return $charset_collate;
    }

    /**
     * Destroy the schema
     *
     * @return bool
     */
    public static function down()
    {
        $conn = ACPT_DB::getDbConn();

        foreach (self::getAllUnprefixedTables() as $table){
            $conn->query("DROP TABLE IF EXISTS `".$table."`;");
            $conn->query("DROP TABLE IF EXISTS `".ACPT_DB::prefixedTableName($table)."`;");
        }

        return empty($conn->last_error);
    }

    /**
     * @return array
     */
    private static function getAllUnprefixedTables()
    {
        return [
            ACPT_DB::TABLE_API_KEYS,
            ACPT_DB::TABLE_CUSTOM_POST_TYPE_ADVANCED_OPTION,
            ACPT_DB::TABLE_CUSTOM_POST_TYPE,
            ACPT_DB::TABLE_CUSTOM_POST_TYPE_META_BOX,
            ACPT_DB::TABLE_CUSTOM_POST_TYPE_FIELD,
            ACPT_DB::TABLE_CUSTOM_POST_TYPE_OPTION,
            ACPT_DB::TABLE_CUSTOM_POST_TYPE_VISIBILITY,
            ACPT_DB::TABLE_CUSTOM_POST_TYPE_RELATION,
            ACPT_DB::TABLE_CUSTOM_POST_TYPE_BLOCK,
            ACPT_DB::TABLE_CUSTOM_POST_TYPE_IMPORT,
            ACPT_DB::TABLE_CUSTOM_POST_TEMPLATE,
	        ACPT_DB::TABLE_OPTION_PAGE,
			ACPT_DB::TABLE_OPTION_PAGE_META_BOX,
            ACPT_DB::TABLE_TAXONOMY,
            ACPT_DB::TABLE_TAXONOMY_META_BOX,
            ACPT_DB::TABLE_TAXONOMY_PIVOT,
            ACPT_DB::TABLE_SETTINGS,
            ACPT_DB::TABLE_WOOCOMMERCE_PRODUCT_DATA,
            ACPT_DB::TABLE_WOOCOMMERCE_PRODUCT_DATA_FIELD,
            ACPT_DB::TABLE_WOOCOMMERCE_PRODUCT_DATA_OPTION,
            ACPT_DB::TABLE_USER_META_BOX,
            ACPT_DB::TABLE_USER_META_FIELD,
            ACPT_DB::TABLE_USER_META_FIELD_OPTION
        ];
    }
}