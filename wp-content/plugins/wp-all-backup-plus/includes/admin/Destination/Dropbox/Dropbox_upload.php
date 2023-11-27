<?php
add_action('wp_all_backup_completed', array('WPALLBackupDropbox', 'wp_db_backup_completed'));

class WPALLBackupDropbox
{

    public static function wp_db_backup_completed(&$args)
    {
        include plugin_dir_path(__FILE__) . 'DropboxClient.php';
        $dropbox = new WPDBBackup_Destination_Dropbox_API('dropbox');
        $wpall_dropboxtoken=get_option('wpall_dropboxtoken');
        $dropboxtoken = (!empty($wpall_dropboxtoken)) ? maybe_unserialize($wpall_dropboxtoken) : array();
        if (isset($dropboxtoken['access_token']) && !empty($dropboxtoken['access_token'])) {
            $dropbox->setOAuthTokens($dropboxtoken);
            $wpall_dropbbox_dir=get_option('wpall_dropbbox_dir');
            $wpall_dropbbox_dir=!empty($wpall_dropbbox_dir) ? '/'.get_option('wpall_dropbbox_dir').'/' : '';
            error_log('wpall_dropbbox_dir');
            error_log($args[1]);
            error_log(print_r($args,1));
            $response = $dropbox->upload($args[1], $wpall_dropbbox_dir . $args[0]);
            if ($response)
                $args[2] = $args[2] . "<br> Upload Database Backup on Dropbox";
                $args[6] = $args[6] .="DropBox, ";
        }

    }

}