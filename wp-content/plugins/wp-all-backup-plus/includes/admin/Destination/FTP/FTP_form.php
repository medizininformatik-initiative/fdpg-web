<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapseI">
                <h2><?php _e('FTP/sFTP', 'wpallbkp'); ?> </h2>
            </a>
        </h4>
    </div>
    <div id="collapseI" class="panel-collapse collapse in">
        <div class="panel-body">
            <p><?php _e('FTP/sFTP Destination Define an FTP destination connection. You can define destination which use FTP.', 'wpallbkp'); ?></p>
            <?php include plugin_dir_path(__FILE__) . '/ftp-form.php'; ?>
        </div>		
    </div>
</div>