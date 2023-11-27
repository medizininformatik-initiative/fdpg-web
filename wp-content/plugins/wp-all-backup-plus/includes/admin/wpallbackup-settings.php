<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (isset($_POST['update'])) {
    if (!isset($_POST['wpallbackup_update_setting']))
        die("<br><span class='label label-danger'>Invalid form data. form request came from the somewhere else not current site! </span>");
    if (!wp_verify_nonce($_POST['wpallbackup_update_setting'], 'wpallbackup-update-setting'))
        die("<br><span class='label label-danger'>Invalid form data. form request came from the somewhere else not current site! </span>");

    if (isset($_POST['wp_all_backup_max_backups'])) {
        update_option('wp_all_backup_max_backups', sanitize_text_field($_POST['wp_all_backup_max_backups']));
    }

    if (isset($_POST['wp_all_backup_type'])) {
        update_option('wp_all_backup_type', sanitize_text_field($_POST['wp_all_backup_type']));
    }

    if (isset($_POST['wp_all_backup_exclude_dir'])) {
        update_option('wp_all_backup_exclude_dir', sanitize_text_field($_POST['wp_all_backup_exclude_dir']));
    }

    if (isset($_POST['wp_all_backup_enable_log'])) {
        update_option('wp_all_backup_enable_log', '1');
    } else {
        update_option('wp_all_backup_enable_log', '0');
    }
    
    if (isset($_POST['wp_all_backup_enable_htaccess'])) {
        update_option('wp_all_backup_enable_htaccess', '1');
    } else {
        update_option('wp_all_backup_enable_htaccess', '0');
        $path_info = wp_upload_dir();
        @unlink($path_info['basedir']  . '/' . WPALLBK_BACKUPS_DIR . '/.htaccess');
    }   
    

    if (isset($_POST['wp_all_backup_backups_dir'])) {
        update_option('wp_all_backup_backups_dir', sanitize_text_field($_POST['wp_all_backup_backups_dir']));
    }
    if (isset($_POST['wp_db_exclude_table'])) {
        update_option('wp_db_exclude_table', $_POST['wp_db_exclude_table']);
    } else {
        update_option('wp_db_exclude_table', '');
    }
    ?><br><span class="label label-success"><?php _e('Setting updated!', 'wpallbkp') ?></span>
<?php
}

function wp_db_backup_format_bytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}
?>

<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-title"><h3><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> <?php _e('Setting', 'wpallbkp') ?></h3></div>
    </div>
    <div class="panel-body">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab"><?php _e('Backup Setting', 'wpallbkp') ?></a></li>
            <li role="presentation"><a href="#schedule" aria-controls="schedule" role="tab" data-toggle="tab"><?php _e('Schedule Setting', 'wpallbkp') ?></a></li>
            <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab"><?php _e('Default Setting', 'wpallbkp') ?></a></li>
            <li role="presentation"><a href="#system" aria-controls="system" role="tab" data-toggle="tab"><?php _e('System Check', 'wpallbkp') ?></a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="home">
                <form action="" method="post">

                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingOne">
                                <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                        <span class=" glyphicon glyphicon-question-sign" aria-hidden="true"></span><?php _e('Backup Type', 'wpallbkp') ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <label>
<?php _e('Backup', 'wpallbkp'); ?>
                                        <select name="wp_all_backup_type" class="form-control" id="wp_all_backup_type">
                                            <option <?php selected(get_option('wp_all_backup_type'), 'Complete'); ?> value="complete"><?php _e('Both Database &amp; files', 'wpallbkp'); ?></option>
                                            <option <?php selected(get_option('wp_all_backup_type'), 'File'); ?> value="File"><?php _e('Files only', 'wpallbkp'); ?></option>
                                            <option <?php selected(get_option('wp_all_backup_type'), 'Database'); ?> value="Database"><?php _e('Database only', 'wpallbkp'); ?></option>
                                        </select>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="headingThree">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                                        <span class=" glyphicon glyphicon-question-sign" aria-hidden="true"></span> <?php _e('Number of backups', 'wpallbkp'); ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseThree" class="panel-collapse collapse in">
                                <div class="panel-body">

                                    <label>
<?php _e('Number of backups to store on this server', 'wpallbkp'); ?>
                                        <input name="wp_all_backup_max_backups" class="form-control" step="1" value="<?php echo get_option('wp_all_backup_max_backups') ?>" type="number">

                                        <p class="description"><?php _e('Past this limit older backups will be deleted automatically.', 'wpallbkp'); ?></p>
                                        <p class="description"><?php _e('The maximum number of Local Backups that should be kept, regardless of their size.</br>Leave blank for keep unlimited backups.', 'wpallbkp'); ?></p>

                                    </label>

                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading"  id="headingFour">
                                <h4 class="panel-title">
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                                        <span class=" glyphicon glyphicon-question-sign" aria-hidden="true"></span><?php _e('Exclude Setting', 'wpallbkp'); ?>

                                    </a>
                                </h4>
                            </div>
                            <div id="collapseFour" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">

                                            <label>
<?php _e('Exclude Folders and Files', 'wpallbkp'); ?>
                                            </label>
                                            <div class="col-sm-12">
                                                <textarea id="wp_all_backup_exclude_dir" class="form-control" name="wp_all_backup_exclude_dir" min value="<?php echo get_option('wp_all_backup_exclude_dir') ?>"><?php echo get_option('wp_all_backup_exclude_dir') ?></textarea>
                                            </div>
                                            <p class="description"><?php _e('Enter new exclude rules as a Pipe (|) separated list, e.g. .git|uploads|.zip', 'wpallbkp'); ?></p>
                                            <hr>
                                        </div>
 <div class="col-xs-12 col-sm-12 col-md-12">
     The directory paths and extensions above will be be excluded from the archive file if bellow list is checked.
     OR Enter new exclude rules manually.
     <br>Select Individual file and directory filters (select  file and directory to exclude files from backup).</div>
                                        <div class="col-sm-offset-1 col-xs-10 col-sm-10 col-md-10">


                                            <?php
                                            include('lib/php_file_tree.php');
                                            echo php_file_tree(WPAllBackup::wp_all_backup_wp_config_path(), "");
                                            ?>
</div>
                                      

                                    </div>
                                </div>

                            </div>
                        </div>
                        <script>
                            $("input[type='checkbox']").change(function () {
                                var wp_all_backup_exclude_dir = $("#wp_all_backup_exclude_dir").val();
                                if ($(this).is(":checked")) {
                                    if (!isEmpty(wp_all_backup_exclude_dir)) {
                                        wp_all_backup_exclude_dir = wp_all_backup_exclude_dir + '|';
                                    }
                                    $("#wp_all_backup_exclude_dir").val(wp_all_backup_exclude_dir + $(this).val());
                                    // alert($(this).val());
                                } else {
                                    removeItem = $(this).val();
                                    if (!isEmpty(wp_all_backup_exclude_dir)) {
                                        var arr_wp_all_backup_exclude_dir = wp_all_backup_exclude_dir.split('|');
                                        arr_wp_all_backup_exclude_dir.splice($.inArray(removeItem, arr_wp_all_backup_exclude_dir), 1);

                                        $("#wp_all_backup_exclude_dir").val(arr_wp_all_backup_exclude_dir.join('|'));
                                    }
                                }
                            });
                            function isEmpty(str) {
                                return typeof str == 'string' && !str.trim() || typeof str == 'undefined' || str === null;
                            }
                        </script>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseExclude">
                                        Exclude Table From Database Backup.
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseExclude" class="panel-collapse collapse in">
                                <div class="panel-body">  
                                    <table class="table table-condensed">
                                        <tr class="success">                                        
                                            <th>No.</th>
                                            <th>Tables</th>
                                            <th>Records</th>
                                            <th>Exclude Table</th>

                                        </tr>                                 
<?php
$wp_db_exclude_table = array();
$wp_db_exclude_table = get_option('wp_db_exclude_table');
global $wpdb;
$no = 0;
$row_usage = 0;
$data_usage = 0;
$tablesstatus = $wpdb->get_results("SHOW TABLE STATUS");
foreach ($tablesstatus as $tablestatus) {
    if ($no % 2 == 0) {
        $style = '';
    } else {
        $style = ' class="alternate"';
    }
    $no++;
    echo "<tr $style>\n";
    echo '<td>' . number_format_i18n($no) . '</td>';
    echo "<td>$tablestatus->Name</td>";
    echo '<td>' . number_format_i18n($tablestatus->Rows) . '</td>';
    if (!empty($wp_db_exclude_table) && in_array($tablestatus->Name, $wp_db_exclude_table)) {
        $checked = "checked";
    } else {
        $checked = "";
    }
    echo '<td> <input type="checkbox" ' . $checked . ' value="' . $tablestatus->Name . '" name="wp_db_exclude_table[' . $tablestatus->Name . ']"></td>';

    $row_usage += $tablestatus->Rows;


    echo '</tr>';
}
echo '<tr class="thead">' . "\n";
echo '<th>' . __('Total:', 'wp-dbmanager') . '</th>' . "\n";
echo '<th>' . sprintf(_n('%s Table', '%s Tables', $no, 'wp-dbmanager'), number_format_i18n($no)) . '</th>' . "\n";
echo '<th>' . sprintf(_n('%s Record', '%s Records', $row_usage, 'wp-dbmanager'), number_format_i18n($row_usage)) . '</th>' . "\n";
echo '<th></th>' . "\n";
echo '</tr>';
?>


                                    </table>
                                </div>		
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading"  id="headingbkdir">
                                <h4 class="panel-title">
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapsebkdir">
                                        <span class=" glyphicon glyphicon-question-sign" aria-hidden="true"></span><?php _e('Backup Folder', 'wpallbkp'); ?>

                                    </a>
                                </h4>
                            </div>
                            <div id="collapsebkdir" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">

                                            <label>
<?php _e('Backup Folder', 'wpallbkp'); ?>
                                                <input name="wp_all_backup_backups_dir" class="form-control" value="<?php echo get_option('wp_all_backup_backups_dir') ?>" type="text">
                                                <p class="description"><?php _e('The Backup Folder field is the name of the folder where your backup will be stored under upload folder. The best name is one that is easily identifiable by you', 'wpallbkp'); ?></p>
                                                <p><?php
                                                $path_info = wp_upload_dir();
                                                _e('Current Backup Folder : ', 'wpallbkp');
                                                $wp_all_backup_backups_dir = get_option('wp_all_backup_backups_dir');
                                                if (!empty($wp_all_backup_backups_dir)) {
                                                    _e($path_info['basedir'] . '/' . $wp_all_backup_backups_dir, 'wpallbkp');
                                                } else {
                                                    _e($path_info['basedir'] . '/' . WPALLBK_BACKUPS_DIR, 'wpallbkp');
                                                }
?></p>
                                            </label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading" id="headingFive">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
                                        <span class=" glyphicon glyphicon-question-sign" aria-hidden="true"></span><?php _e('Log Setting', 'wpallbkp'); ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseFive" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <label>    		
                                                <label>
                                                    <input type="checkbox" <?php checked(get_option('wp_all_backup_enable_log'), '1'); ?>  name="wp_all_backup_enable_log"> <?php _e('Enable Backup Log', 'wpallbkp'); ?>  
                                                    </div>

                                                    </div>
                                                    </div>
                                                    </div>
                                                    </div>
                        
                         <div class="panel panel-default">
                            <div class="panel-heading" id="headingFive">
                                <h4 class="panel-title">
                                    <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapsehtaccess">
                                        <span class=" glyphicon glyphicon-question-sign" aria-hidden="true"></span><?php _e(' Disable .htaccess', 'wpallbkp'); ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapsehtaccess" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <label>    		
                                                <label>
                                                    <input type="checkbox" <?php checked(get_option('wp_all_backup_enable_htaccess'), '1'); ?>  name="wp_all_backup_enable_htaccess"> <?php _e('Enable .htaccess File In Storage Directory', 'wpallbkp'); ?>  
                                                    <p>Disable if issues occur when downloading installer/archive files.</p>
                                                    </div>

                                                    </div>
                                                    </div>
                                                    </div>
                                                    </div>
                        

                                                    </div>
                                                    <input type="submit" class="btn btn-primary" name="update" value="<?php _e('Update', 'wpallbkp'); ?>">
                                                    <input name="wpallbackup_update_setting" type="hidden" value="<?php echo wp_create_nonce('wpallbackup-update-setting'); ?>" />
                                                    </form>

                                                    </div>

                                                    <div role="tabpanel" class="tab-pane" id="schedule">
                                                        <form method="post" action="options.php" name="wp_auto_commenter_form">
                                                            <div class="checkbox">
                                                                <label>
<?php
$settings = get_option('wp_all_backup_options');
echo '<input type="checkbox" name="wp_all_backup_options[enable_autobackups]" value="1" ' . @checked(1, $settings['enable_autobackups'], false) . '/>';
?>
                                                                    <?php _e('Enable Auto Backups', 'wpallbkp'); ?>
                                                                </label>
                                                            </div>
                                                            <label>
<?php
settings_fields('wp_all_backup_options');
_e('Auto Backup Frequency', 'wpallbkp');
?>
                                                                <select name="wp_all_backup_options[autobackup_frequency]" id="wp_all_backup_schedule_reoccurrence">    		               
                                                                    <option <?php echo selected('hourly', @$settings['autobackup_frequency'], false) ?> value="hourly"><?php _e('Once Hourly', 'wpallbkp'); ?></option>                
                                                                    <option <?php echo selected('twicedaily', @$settings['autobackup_frequency'], false); ?> value="twice daily"><?php _e('Twice Daily', 'wpallbkp'); ?></option>                
                                                                    <option <?php echo selected('daily', @$settings['autobackup_frequency'], false) ?> value="daily"><?php _e('Once Daily', 'wpallbkp'); ?></option>                
                                                                    <option <?php echo selected('weekly', @$settings['autobackup_frequency'], false); ?> value="weekly"><?php _e('Once Weekly', 'wpallbkp'); ?></option>                     
                                                                    <option <?php echo selected('monthly', @$settings['autobackup_frequency'], false); ?> value="monthly"><?php _e('Once Monthly', 'wpallbkp'); ?></option>                
                                                                </select>			
                                                            </label>
                                                            <p class="submit">
                                                                <input type="submit" name="Submit" class="btn btn-primary" value="Save Settings" />
                                                            </p>
                                                        </form>

                                                    </div>

                                                    <div role="tabpanel" class="tab-pane" id="profile">
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading">
                                                                <h4 class="panel-title">
                                                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapsedb">
<?php _e('Database Information', 'wpallbkp') ?>

                                                                    </a>
                                                                </h4>
                                                            </div>
                                                            <div id="collapsedb" class="panel-collapse collapse in">
                                                                <div class="panel-body">
                                                                    <table class="table table-condensed">
                                                                        <tr class="success">
                                                                            <th><?php _e('Setting', 'wpallbkp') ?></th>
                                                                            <th><?php _e('Value', 'wpallbkp') ?></th>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><?php _e('Database Host', 'wpallbkp') ?></td><td><?php echo DB_HOST; ?></td>
                                                                        </tr>
                                                                        <tr class="default">
                                                                            <td><?php _e('Database Name', 'wpallbkp') ?></td><td> <?php echo DB_NAME; ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><?php _e('Database User', 'wpallbkp') ?></td><td><?php echo DB_USER; ?></td></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><?php _e('Database Type', 'wpallbkp') ?></td><td>MYSQL</td>
                                                                        </tr>
                                                                        <tr>
<?php
// Get MYSQL Version
global $wpdb;
$mysqlversion = $wpdb->get_var("SELECT VERSION() AS version");
?>
                                                                            <td><?php _e('Database Version', 'wpallbkp') ?></td><td>v<?php echo $mysqlversion; ?></td>
                                                                        </tr>
                                                                    </table>

                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="panel panel-default">
                                                            <div class="panel-heading">
                                                                <h4 class="panel-title">
                                                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapsedbtable">
<?php _e('Tables Information', 'wpallbkp') ?>

                                                                    </a>
                                                                </h4>
                                                            </div>
                                                            <div id="collapsedbtable" class="panel-collapse collapse">
                                                                <div class="panel-body">
                                                                    <table class="table table-condensed">
                                                                        <tr class="success">                                        
                                                                            <th><?php _e('No.', 'wpallbkp') ?></th>
                                                                            <th><?php _e('Tables', 'wpallbkp') ?></th>
                                                                            <th><?php _e('Records', 'wpallbkp') ?></th>

                                                                        </tr>                                 
<?php
$no = 0;
$row_usage = 0;
$data_usage = 0;
$tablesstatus = $wpdb->get_results("SHOW TABLE STATUS");
foreach ($tablesstatus as $tablestatus) {
    if ($no % 2 == 0) {
        $style = '';
    } else {
        $style = ' class="alternate"';
    }
    $no++;
    echo "<tr$style>\n";
    echo '<td>' . number_format_i18n($no) . '</td>' . "\n";
    echo "<td>$tablestatus->Name</td>\n";
    echo '<td>' . number_format_i18n($tablestatus->Rows) . '</td>' . "\n";

    $row_usage += $tablestatus->Rows;


    echo '</tr>' . "\n";
}
echo '<tr class="thead">' . "\n";
echo '<th>' . __('Total:', 'wp-dbmanager') . '</th>' . "\n";
echo '<th>' . sprintf(_n('%s Table', '%s Tables', $no, 'wp-dbmanager'), number_format_i18n($no)) . '</th>' . "\n";
echo '<th>' . sprintf(_n('%s Record', '%s Records', $row_usage, 'wp-dbmanager'), number_format_i18n($row_usage)) . '</th>' . "\n";

echo '</tr>';
?>


                                                                    </table>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading">
                                                                <h4 class="panel-title">
                                                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapsewp">
<?php _e('WordPress Information', 'wpallbkp') ?>

                                                                    </a>
                                                                </h4>
                                                            </div>                            

                                                            <div id="collapsewp" class="panel-collapse collapse">
                                                                <div class="panel-body">
                                                                    <table class="table table-condensed">
                                                                        <tr class="success">                                        
                                                                            <th><?php _e('Setting', 'wpallbkp') ?></th>
                                                                            <th><?php _e('Value', 'wpallbkp') ?></th>

                                                                        </tr>     
                                                                        <tr>
                                                                            <td><?php _e('WordPress Version', 'wpallbkp') ?></td>
                                                                            <td><?php bloginfo('version'); ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><?php _e('Home URL', 'wpallbkp') ?></td>
                                                                            <td> <?php echo home_url(); ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><?php _e('Site URL', 'wpallbkp') ?></td>
                                                                            <td><?php echo site_url(); ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td><?php _e('Upload directory URL', 'wpallbkp') ?></td>
                                                                            <td><?php $upload_dir = wp_upload_dir(); ?>
<?php echo $upload_dir['baseurl']; ?></td>
                                                                        </tr>
                                                                    </table>

                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="panel panel-default">
                                                            <div class="panel-heading">
                                                                <h4 class="panel-title">
                                                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapsewpsetting">
<?php _e('WordPress Settings', 'wpallbkp') ?>

                                                                    </a>
                                                                </h4>
                                                            </div>
                                                            <div id="collapsewpsetting" class="panel-collapse collapse">
                                                                <div class="panel-body">
                                                                    <table class="table table-condensed">
                                                                        <tr class="success">                                        
                                                                            <th><?php _e('Plugin Name', 'wpallbkp') ?></th>
                                                                            <th><?php _e('Version', 'wpallbkp') ?></th>           
                                                                        </tr> 
<?php
$plugins = get_plugins();
foreach ($plugins as $plugin) {
    echo "<tr>
                                           <td>" . $plugin['Name'] . "</td>
                                           <td>" . $plugin['Version'] . "</td>                                         
                                        </tr>";
}
?>                                    
                                                                    </table>    

                                                                    <div class="row">
                                                                        <button class="btn btn-primary" type="button">
<?php _e('Drafts Post Count', 'wpallbkp') ?> <span class="badge"><?php
$count_posts = wp_count_posts();
echo $count_posts->draft;
?></span>
                                                                        </button>
                                                                        <button class="btn btn-primary" type="button">
                                                                            <?php _e('Publish Post Count', 'wpallbkp') ?> <span class="badge"><?php
                                                                            echo $count_posts->publish;
                                                                            ?></span>
                                                                        </button>
                                                                        <button class="btn btn-primary" type="button">
                                                                            <?php _e('Drafts Pages Count', 'wpallbkp') ?> <span class="badge"><?php
                                                                            $count_pages = wp_count_posts('page');
                                                                            echo $count_pages->draft;
                                                                            ?></span>
                                                                        </button>
                                                                        <button class="btn btn-primary" type="button">
                                                                            <?php _e('Publish Pages Count', 'wpallbkp') ?> <span class="badge"><?php
                                                                            echo $count_pages->publish;
                                                                            ?></span>
                                                                        </button>
                                                                        <button class="btn btn-primary" type="button">
                                                                            <?php _e('Approved Comments Count', 'wpallbkp') ?> <span class="badge"><?php
                                                                            $comments_count = wp_count_comments();
                                                                            echo $comments_count->approved;
                                                                            ?></span>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div role="tabpanel" class="tab-pane" id="system">
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading">
                                                                <h4 class="panel-title">
                                                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapsedb">
<?php _e('System Information', 'wpallbkp') ?>

                                                                    </a>
                                                                </h4>
                                                            </div>
                                                            <div id="collapsedb" class="panel-collapse collapse in">
                                                                <div class="panel-body">
                                                                    <div class="row">
                                                                        <?php
                                                                        /* get disk space free (in bytes) */
                                                                        $df = disk_free_space(WPALL_ROOTPATH);
                                                                        /* and get disk space total (in bytes)  */
                                                                        $dt = disk_total_space(WPALL_ROOTPATH);
                                                                        /* now we calculate the disk space used (in bytes) */
                                                                        $du = $dt - $df;
                                                                        /* percentage of disk used - this will be used to also set the width % of the progress bar */
                                                                        $dp = sprintf('%.2f', ($du / $dt) * 100);

                                                                        /* and we formate the size from bytes to MB, GB, etc. */
                                                                        $df = wp_db_backup_format_bytes($df);
                                                                        $du = wp_db_backup_format_bytes($du);
                                                                        $dt = wp_db_backup_format_bytes($dt);
                                                                        ?>
                                                                        <div class="col-md-1"><a href="" target="_blank" title="Help"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span></a></div>
                                                                        <div class="col-md-3">Disk Space</div>
                                                                        <div class="col-md-5">
                                                                            <div class="progress">
                                                                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo trim($dp) ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo trim($dp) ?>%"><?php echo $dp ?>%
                                                                                </div>
                                                                            </div>   
                                                                        </div>
                                                                        <div class="col-md-1"></div>
                                                                        <div class="col-md-4"></div>
                                                                        <div class="col-md-5">
                                                                            <div class='prginfo'>
                                                                                <p><?php echo "$du of $dt used"; ?></p>
                                                                                <p><?php echo "$df of $dt free"; ?></p>
                                                                                <p><small><?php _e("Note: This value is the physical servers hard-drive allocation.", 'wpdbbkp'); ?> <br/><?php _e("On shared hosts check your control panel for the 'TRUE' disk space quota value.", 'wpdbbkp'); ?>
                                                                                    </small>
                                                                                </p>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-1"><a href="" target="_blank" title="Help"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span></a></div>
                                                                        <div class="col-md-3"><?php _e('Upload directory URL', 'wpallbkp') ?></div>
                                                                        <div class="col-md-5"><?php $upload_dir = wp_upload_dir();
                                                                        echo $upload_dir['baseurl']
                                                                        ?></div>
                                                                        <div class="col-md-3"></div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-md-1"><a href="" target="_blank" title="Help"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span></a></div>
                                                                        <div class="col-md-3"><?php _e('Upload directory', 'wpallbkp') ?></div>
                                                                        <div class="col-md-5"><?php echo $upload_dir['basedir']; ?></div>
                                                                        <div class="col-md-1">
                                                                            <?php echo substr(sprintf('%o', fileperms($upload_dir['basedir'])), -4);
                                                                            ?></div><div class="col-md-2"><?php echo (!is_writable($upload_dir['basedir'])) ? '<p class="text-danger"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Not writable </p>' : '<p class="text-success"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> writable</p>';
                                                                            ?>
                                                                        </div>
                                                                    </div>


                                                                    <div class="row">
                                                                        <div class="col-md-1"><a href="" target="_blank" title="Help"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span></a></div>
                                                                        <div class="col-md-3"><?php _e('Max Execution Time', 'wpallbkp') ?></div>
                                                                        <div class="col-md-5"> <?php echo ini_get('max_execution_time'); ?></div>
                                                                        <div class="col-md-1"></div>
                                                                        <div class="col-md-2"><?php echo ini_get('max_execution_time') < 60 ? '<p class="text-danger"  data-toggle="tooltip" data-placement="left" title="For large site set high"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Low </p>' : '' ?></div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-md-1"><a href="" target="_blank" title="Help"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span></a></div>
                                                                        <div class="col-md-3">Root Path</div>
                                                                        <div class="col-md-5"><?php echo $_SERVER['DOCUMENT_ROOT'] ?></div>                           
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-1"><a href="" target="_blank" title="Help"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span></a></div>
                                                                        <div class="col-md-3">ABSPATH</div>
                                                                        <div class="col-md-5"><?php echo ABSPATH ?></div>                           
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-1"><a href="" target="_blank" title="Help"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span></a></div>
                                                                        <div class="col-md-3">Loaded PHP INI</div>
                                                                        <div class="col-md-5"><?php echo php_ini_loaded_file(); ?></div>                           
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-1"><a href="" target="_blank" title="Help"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span></a></div>
                                                                        <div class="col-md-3">Memory Limit</div>
                                                                        <div class="col-md-5"><?php
                                                                            echo WP_MEMORY_LIMIT;
                                                                            echo '(Max &nbsp;' . WP_MAX_MEMORY_LIMIT;
                                                                            ?>)</div>                           
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-1"><a href="" target="_blank" title="Help"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span></a></div>
                                                                        <div class="col-md-3"><?php _e('Backup directory', 'wpallbkp') ?></div>
                                                                        <div class="col-md-5"> <?php _e($upload_dir['basedir'] . '/' . WPALLBK_BACKUPS_DIR, 'wpallbkp'); ?></div>
                                                                        <div class="col-md-1"><?php echo substr(sprintf('%o', fileperms($upload_dir['basedir'] . '/' . WPALLBK_BACKUPS_DIR)), -4);
                                                                            ?></div><div class="col-md-2"><?php echo (!is_writable($upload_dir['basedir'] . '/' . WPALLBK_BACKUPS_DIR)) ? '<p class="text-danger"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Not writable </p>' : '<p class="text-success"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> writable</p>';
                                                                            ?></div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-md-1"><a href="" target="_blank" title="Help"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span></a></div>
                                                                        <div class="col-md-3"><?php _e('Class ZipArchive Present : ', 'wpallbkp') ?></div>
                                                                        <div class="col-md-5"> <?php
                                                                            echo (class_exists('ZipArchive')) ? 'Yes </p>' : '<p class="">No</p>';
                                                                            ?></div>
                                                                        <div class="col-md-3"></div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-md-1"><a href="" target="_blank" title="Help"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span></a></div>
                                                                        <div class="col-md-3"><?php _e('zip (cmd) Present : ', 'wpallbkp') ?></div>
                                                                        <div class="col-md-5"> <?php
                                                                            $WPALLMenu = new WPALLMenu();
                                                                            echo ($WPALLMenu->get_zip_command_path()) ? 'Yes </p>' : '<p class="">No</p>';
                                                                            ?></div>
                                                                        <div class="col-md-3"></div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-md-1"><a href="" target="_blank" title="Help"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span></a></div>
                                                                        <div class="col-md-3"><?php _e('mysqldump (cmd) Present : ', 'wpallbkp') ?></div>
                                                                        <div class="col-md-5"> <?php
                                                                            $WPALLMenu = new WPALLMenu();
                                                                            echo ($WPALLMenu->get_mysqldump_command_path()) ? 'Yes </p>' : '<p class="">No</p>';
                                                                            ?></div>
                                                                        <div class="col-md-3"></div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    </div>
                                                    </div>
                                                    <div class="panel-footer"><h4>Get Flat 25% off on <a target="_blank" href="http://www.wpseeds.com/shop/">All WPSeeds other product.</a> Use Coupon code 'WPSEEDS25'</h4></div>
                                                    </div>
