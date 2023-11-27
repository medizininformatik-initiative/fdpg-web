<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapseII">
                <h2><?php _e('Email Notification', 'wpallbkp'); ?></h2>
            </a>
        </h4>
    </div>
    <div id="collapseII" class="panel-collapse collapse in">
        <div class="panel-body">
            <?php
            echo '<form name="wp-email_form" method="post" action="" >';

            $wp_all_backup_email_id = "";
            $wp_all_backup_email_id = get_option('wp_all_backup_email_id');
            $wp_all_backup_email_attachment = "";
            $wp_all_backup_email_attachment = get_option('wp_all_backup_email_attachment');
            echo '<p>';
            echo '<span class="glyphicon glyphicon-envelope"></span>';
            _e('Send Email Notification', 'wpallbkp');
           echo '<div class="row form-group"><label class="col-sm-2" for="wp_all_backup_email_id">';
            _e('Email Id : ', 'wpallbkp');
            echo '</label>';
            echo '<div class="col-sm-6"><input type="text" id="wp_all_backup_email_id" class="form-control" name="wp_all_backup_email_id" value="' . $wp_all_backup_email_id . '" placeholder="';
            _e('Your Email Id', 'wpallbkp');
            echo '">';
            echo '</div><div class="col-sm-4">';
            _e('Leave blank if you don\'t want use this feature', 'wpallbkp');
            echo '</div></div>';
            echo '<div class="row form-group"><label for="lead-theme" class="col-sm-2" for="lead-theme">';
            _e('Attach log file : ', 'wpallbkp');
            echo '</label>';
            $selected_option = get_option('wp_all_backup_email_attachment');

            if ($selected_option == "yes")
                $selected_yes = "selected=\"selected\"";
            else
                $selected_yes = "";
            if ($selected_option == "no")
                $selected_no = "selected=\"selected\"";
            else
                $selected_no = "";
            echo '<div class="col-sm-2"><select id="lead-theme" class="form-control" name="wp_all_backup_email_attachment">';
            echo '<option value="none">';
            _e('Select', 'wpallbkp');
            echo '</option>';
            echo '<option  value="yes"' . $selected_yes . '>';
            _e('Yes', 'wpallbkp');
            echo '</option>';
            echo '<option  value="no" ' . $selected_no . '>';
            _e('No', 'wpallbkp');
            echo '</option>';
            echo '</select></div>';
            echo '<div class="col-sm-8">';
            _e('If you want attache log file to email then select "yes" (File attached only when log file size <=25MB)', 'wpallbkp');
            echo '</div>';
            echo '</div>';
            echo '<input name="wpallbackup_update_setting" type="hidden" value="' . wp_create_nonce('wpallbackup-update-setting') . '" />';
            echo '<p class="submit">';
            echo '<input type="submit" name="Submit" class="btn btn-primary" value="';
            _e('Save Settings', 'wpallbkp');
            echo'" />';
            echo '</p>';
            echo '</form>';
            ?>
        </div>		
    </div>
</div>