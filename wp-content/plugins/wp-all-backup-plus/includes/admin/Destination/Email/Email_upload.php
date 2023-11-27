<?php

add_action('wp_all_backup_completed', array('WPALLBackupEmail', 'wp_all_backup_completed'), 12);

class WPALLBackupEmail {

    public static function wp_all_backup_completed(&$args) {

        if (get_option('wp_all_backup_email_id')) {
            $to = get_option('wp_all_backup_email_id');
            $subject = "Backup Created Successfully";
            $filename = $args[0];
            $filesze = $args[3];
            $site_url = site_url();
            $logMessageAttachment = "";
            include('template_email_notification.php');

            $headers = array('Content-Type: text/html; charset=UTF-8');
            $wp_all_backup_email_id_file = get_option('wp_all_backup_email_attachment');
            if ($wp_all_backup_email_id_file == "yes" && get_option('wp_all_backup_enable_log') == 1 && $args[3] <= 209700000) {
                $attachments = $args[4];
                $logMessageAttachment = " with attached log file.";
            } else
                $attachments = "";

            wp_mail($to, $subject, $message, $headers, $attachments);
            $args[2] = $args[2] . "<br> Send Backup Mail to:" . $to . $logMessageAttachment;             
        }
    }

    public static function wp_all_backup_format_bytes($bytes, $precision = 2) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

}
