<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?><div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-title"><h3><span class="glyphicon glyphicon-upload" aria-hidden="true"></span> <?php _e('Destination', 'wpallbkp'); ?></h3></div>
    </div>
    <div class="panel-body">
<?php include plugin_dir_path(__FILE__) . 'Destination/wp-backup-destination.php'; ?>		
    </div>
    <div class="panel-footer"><h4>Get Flat 25% off on <a target="_blank" href="http://www.wpseeds.com/shop/">All WPSeeds other product.</a> Use Coupon code 'WPSEEDS25'</h4></div>
</div>