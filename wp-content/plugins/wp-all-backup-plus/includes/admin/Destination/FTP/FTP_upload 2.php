<?php

add_action('wp_all_backup_completed', array('WPALLBackupFTP', 'wp_all_backup_completed'));

class WPALLBackupFTP {

    public static function wp_all_backup_completed(&$args) {        
        $filename = $args[0];  ;
        include plugin_dir_path(__FILE__) . 'preflight.php';
        $filename = $args[0];  
        include plugin_dir_path(__FILE__) . 'sendaway.php';
    }
}
