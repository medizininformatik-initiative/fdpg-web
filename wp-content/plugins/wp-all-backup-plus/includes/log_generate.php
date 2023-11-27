<?php
add_action('wp_all_backup_completed', array('WPALLBackupLog', 'wp_all_backup_completed'), 11);

class WPALLBackupLog {

    public static function wp_all_backup_completed(&$args) {
        
                        $options = get_option('wp_all_backup_backups');
                        $newoptions = array();
                        $count = 0;
                        foreach ($options as $option) {
                            if ($option['filename'] == $args[0]) {
                                   $newoptions[] = array(
                                    'date' => $option['date'],
                                    'filename' => $option['filename'],
                                    'url' =>$option['url'],
                                    'dir' => $option['dir'],
                                    'logfile' =>$option['logfile'],
                                    'destination' =>  $args[6],
                                    'type' => $option['type'],
                                    'size' => $option['size']
                                );
                               // error_log("set destination to $args[0]");
                            }else{
                                 $newoptions[] = $option;
                            }
                            $count++;
                        }                        
                        update_option('wp_all_backup_backups', $newoptions);

        if (get_option('wp_all_backup_enable_log') == 1) {
            if (is_writable($args[4]) || !file_exists($args[4])) {

                if (!$handle = @fopen($args[4], 'a'))
                    return;

                if (!fwrite($handle,  str_replace("<br>", "\n", $args[2])))
                    return;

                fclose($handle);

                return true;
            }
        }
    }

}