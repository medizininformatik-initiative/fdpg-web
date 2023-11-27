<?php 

global  $wpdb ;
$collate = '';
if ( $wpdb->has_cap( 'collation' ) ) {
    if (!empty($wpdb->charset) ) {$collate .= "DEFAULT CHARACTER SET {$wpdb->charset}"; }
    if (!empty($wpdb->collate) ) {$collate .= " COLLATE {$wpdb->collate}"; }
}
$table_schema = array( 

"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}awp_integration` (
\n `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
\n `title` text NOT NULL,
\n `form_provider` varchar(255) NOT NULL,
\n `form_id` varchar(255) NOT NULL,
\n `form_name` varchar(255) DEFAULT NULL,
\n `action_provider` varchar(255) NOT NULL,
\n `task` varchar(255) NOT NULL,
\n `data` longtext DEFAULT NULL,
\n `extra_data` longtext DEFAULT NULL,
\n `status` int(1) NOT NULL,
\n `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
\n KEY `id` (`id`)
\n ) {$collate};",

"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}awp_message_template` (
\n `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
\n `title` varchar(255) NOT NULL,
\n `form_provider` varchar(255) NOT NULL,
\n `form_id` varchar(255) NOT NULL,
\n `subject_name` varchar(255) NOT NULL,
\n `sender_phone` varchar(255) DEFAULT NULL,
\n `sender_email` varchar(255) NOT NULL,
\n `action_provider` varchar(255) NOT NULL,
\n `message_template` longtext DEFAULT NULL,
\n `external_template_id` varchar(255) DEFAULT NULL,
\n `data` longtext DEFAULT NULL,
\n `status` int(1) NOT NULL,
\n `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
\n KEY `id` (`id`)
\n ) {$collate};", 

"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}automate_log` (
\n `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
\n `response_code` int(3) DEFAULT NULL,
\n `response_message` varchar(255) DEFAULT NULL,
\n `integration_id` bigint(20) DEFAULT NULL,
\n `request_data` longtext DEFAULT NULL,
\n `response_data` longtext DEFAULT NULL,
\n `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
\n `start_time` timestamp NULL,
\n `ip` varchar(255) NULL,
\n KEY `id` (`id`)
\n ) {$collate};", 

"CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}userCreatedOrUpdated` (
\n `id` int(11) NOT NULL,
\n `request` text,
\n `response` text,
\n `success` tinyint(1) DEFAULT NULL, 
\n `Ip` varchar(255) DEFAULT NULL,
\n `userAgent` varchar(255) DEFAULT NULL,
\n `dateAndTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
\n ) {$collate}");

require_once ABSPATH . 'wp-admin/includes/upgrade.php';
foreach ( $table_schema as $table ) {dbDelta( $table );}

$awp_platform_settings = $wpdb->prefix.'awp_platform_settings';
$awp_platform_settings_sql = 'CREATE TABLE IF NOT EXISTS '.$awp_platform_settings.' (
id int(11) NOT NULL AUTO_INCREMENT,
platform_name  varchar(255) DEFAULT NULL,
url  varchar(255) DEFAULT NULL,
api_key  text DEFAULT NULL,
email  varchar(255) DEFAULT NULL,
client_id  varchar(255) DEFAULT NULL,
client_secret  varchar(255) DEFAULT NULL,
account_name  varchar(255) DEFAULT NULL,
active_status varchar(255) DEFAULT NULL,
spots varchar(20) DEFAULT NULL,
sync_contacts varchar(10) DEFAULT NULL,
activity_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY  (id)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 ;';
dbDelta( $awp_platform_settings_sql );

$awp_apps = $wpdb->prefix.'awp_apps';
$awp_apps_accounts_sql = 'CREATE TABLE IF NOT EXISTS '.$awp_apps.' (
id int(11) NOT NULL AUTO_INCREMENT,
platform  varchar(255) DEFAULT NULL,
PRIMARY KEY  (id)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1';
dbDelta( $awp_apps_accounts_sql );
