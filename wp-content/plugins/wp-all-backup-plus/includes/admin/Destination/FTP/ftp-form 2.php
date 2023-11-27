<?php
/*
 * @since 1.0
 * FTP FORM SETTINGS
 */

// Direct calls to this file are Forbidden when core files are not present
// Thanks to Ed from ait-pro.com for this  code 
// @since 2.1

if (!function_exists('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if (!current_user_can('manage_options')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

//
//
// variables for the field and option names 
$opt_name = 'wp_all_backup_ftp_host';
$opt_name2 = 'wp_all_backup_ftp_user';
$opt_name3 = 'wp_all_backup_ftp_pass';
$opt_name4 = 'wp_all_backup_ftp_subdir';
$opt_name5 = 'wp_all_backup_ftp_prefix';
$opt_name6 = 'wp_all_backup_add_dir1';
$opt_name7 = 'wp_all_backup_auto_interval';
$opt_name8 = 'wp_all_backup_auto_email';
$opt_name9 = 'wp_all_backup_ftp_port';

$hidden_field_name = 'wp_all_backup_ftp_hidden';
$hidden_field_name2 = 'wp_all_backup_backup_hidden';
$hidden_field_name3 = 'wp_all_backup_check_repo';
$data_field_name = 'wp_all_backup_ftp_host';
$data_field_name2 = 'wp_all_backup_ftp_user';
$data_field_name3 = 'wp_all_backup_ftp_pass';
$data_field_name4 = 'wp_all_backup_ftp_subdir';
$data_field_name5 = 'wp_all_backup_ftp_prefix';
$data_field_name6 = 'wp_all_backup_add_dir1';
$data_field_name7 = 'wp_all_backup_auto_interval';
$data_field_name8 = 'wp_all_backup_auto_email';
$data_field_name9 = 'wp_all_backup_ftp_port';

// Read in existing option value from database
$opt_val = get_option($opt_name);
$opt_val2 = get_option($opt_name2);
$opt_val3 = get_option($opt_name3);
$opt_val4 = get_option($opt_name4);
$opt_val5 = get_option($opt_name5);
$opt_val6 = get_option($opt_name6);
$opt_val7 = get_option($opt_name7);
$opt_val8 = get_option($opt_name8);
$opt_val9 = get_option($opt_name9);

// BUTTON 3: 
// UPDATE DIRECTORY
// If user pressed this button, this hidden field will be set to 'Y'
if (isset($_POST[$hidden_field_name3]) && $_POST[$hidden_field_name3] == 'Y') {
    // Read their posted value
    $opt_val6 = sanitize_text_field($_POST[$data_field_name6]);
    // Save the posted value in the database
    update_option($opt_name6, $opt_val6);
    // Put a "settings updated" message on the screen
    ?>
    <div class="updated"><p><strong><?php _e('Your additional directory has been saved.', 'wpallbkp'); ?></strong></p></div>
    <?php
}

// BUTTON 1: 
// SAVE SETTINGS
// If user pressed this button, this hidden field will be set to 'Y'
if (isset($_POST[$hidden_field_name]) && $_POST[$hidden_field_name] == 'Y') {
    // Read their posted value
    @$opt_val = sanitize_text_field($_POST[$data_field_name]);
    @$opt_val2 = sanitize_text_field($_POST[$data_field_name2]);
    @$opt_val3 = sanitize_text_field($_POST[$data_field_name3]);
    @$opt_val4 = sanitize_text_field($_POST[$data_field_name4]);
    if (isset($_POST[$data_field_name5]))
        @$opt_val5 = sanitize_text_field($_POST[$data_field_name5]);
    else
        $opt_val5 = "";
    @$opt_val9 = sanitize_text_field($_POST[$data_field_name9]);

    // Save the posted value in the database
    update_option($opt_name, $opt_val);
    update_option($opt_name2, $opt_val2);
    update_option($opt_name3, $opt_val3);
    update_option($opt_name4, $opt_val4);
    update_option($opt_name5, $opt_val5);
    update_option($opt_name9, $opt_val9);

    // Put a "settings updated" message on the screen
    ?>
    <div class="updated"><p><strong><?php _e('Your FTP details have been saved.', 'wpallbkp'); ?></strong></p></div>
    <?php
} // end if
//
// BUTTON 2: 
// TEST SETTINGS
// If user pressed this button, this hidden field will be set to 'Y'

if (isset($_POST[$hidden_field_name]) && $_POST[$hidden_field_name] == 'Test Connection') {
    include plugin_dir_path(__FILE__) . 'test-ftp.php';
    //
    // update all options while we're at it
    // @since 2.1
    $opt_val = sanitize_text_field($_POST[$data_field_name]);
    $opt_val2 = sanitize_text_field($_POST[$data_field_name2]);
    $opt_val3 = sanitize_text_field($_POST[$data_field_name3]);
    $opt_val4 = sanitize_text_field($_POST[$data_field_name4]);
    if (isset($_POST[$data_field_name5]))
        $opt_val5 = sanitize_text_field($_POST[$data_field_name5]);
    else
        $opt_val5 = "";
    $opt_val9 = sanitize_text_field($_POST[$data_field_name9]);

    // Save the posted value in the database
    update_option($opt_name, $opt_val);
    update_option($opt_name2, $opt_val2);
    update_option($opt_name3, $opt_val3);
    update_option($opt_name4, $opt_val4);
    update_option($opt_name5, $opt_val5);
    update_option($opt_name9, $opt_val9);
    $result = wp_all_backup_test_ftp();
    // echo "<h2>$result</h2>";

    if ($result != 'OK') {
        ?>
        <div class="error"><p><strong><?php _e('connection has failed!', 'wpallbkp'); ?><br /></strong></p>
            <?php _e($result, 'wpallbkp');
            echo '<br /><br />';
            ?>
        </div>
    <?php } else { ?>

        <div class="updated"><p><strong><?php _e('Subdirectory:', 'wpallbkp'); ?><?php _e('Connected to :', 'wpallbkp'); ?><?php echo $opt_val; ?>, <?php _e('for user:', 'wpallbkp'); ?> <?php echo $opt_val2; ?></strong></p></div>
        <?php
    } // end if 
} // end if
?>
<style>td, th {
        padding: 5px;
    }</style>
<p><?php _e('Enter your FTP details for your offsite backup repository. Leave these blank for local backups.', 'wpallbkp'); ?></p>		
<form name="form1" method="post" action="">
    <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

    <div class="row form-group">
        <label class="col-sm-2" for="FTP_host">FTP Host:</label>
        <div class="col-sm-6">
            <input type="text" id="FTP_host" class="form-control" name="<?php echo $data_field_name; ?>" value="<?php echo $opt_val; ?>" size="25" placeholder="<?php _e('e.g. ftp.yoursite.com', 'wpallbkp'); ?>">

        </div>
    </div>

    <div class="row form-group">
        <label class="col-sm-2" for="FTP_port">FTP Port:</label>
        <div class="col-sm-2">
            <input type="text" id="FTP_port" class="form-control" name="<?php echo $data_field_name9; ?>" value="<?php echo $opt_val9; ?>" size="4">
        </div>
        <div class="col-sm-4">
            <em><?php _e('defaults to 21 if left blank ', 'wpallbkp'); ?></em>
        </div>
    </div>

    <div class="row form-group">
        <label class="col-sm-2" for="FTP_user">FTP User:</label>
        <div class="col-sm-6">
            <input type="text" id="FTP_user" class="form-control" name="<?php echo $data_field_name2; ?>" value="<?php echo $opt_val2; ?>" size="25">
        </div>
    </div>

    <div class="row form-group">
        <label class="col-sm-2" for="FTP_password">FTP Password:</label>
        <div class="col-sm-6">
            <input type="password" id="FTP_password" class="form-control" name="<?php echo $data_field_name3; ?>" value="<?php echo $opt_val3; ?>" size="25">
        </div>
    </div>

    <div class="row form-group">
        <label class="col-sm-2" for="FTP_dir">Subdirectory:</label>
        <div class="col-sm-6">
            <input type="text" id="FTP_dir" class="form-control" name="<?php echo $data_field_name4; ?>" value="<?php echo $opt_val4; ?>" size="25">
        </div>
        <div class="col-sm-4"> 
            <em><?php _e('e.g. /httpdocs/backups or leave blank', 'wpallbkp'); ?></em>
        </div>
    </div>

    <p><input type="submit" name="Submit" class="btn btn-primary" value="<?php _e('Save FTP Details', 'wpallbkp'); ?>" />&nbsp;
        <input type="submit" name="<?php echo $hidden_field_name; ?>" class="btn btn-secondary" value="<?php _e('Test Connection', 'wpallbkp'); ?>" />

        <br />
    </p>
</form>