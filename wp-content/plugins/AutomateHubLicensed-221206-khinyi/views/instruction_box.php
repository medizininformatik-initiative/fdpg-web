<?php $appname=(isset(($_GET['tab']))?ucwords(sanitize_text_field($_GET['tab'])):'Sperse'); ?>

<?php 
    
    if(isset($appname) && $appname=='Sperse'){
        echo '<img id="exclusive-offer-btn" class="exclusive-offer-btn" src="'.AWP_ASSETS.'/images/exclusiveoffer.png">';

    }

?>


<div id="introbox">

        <div class="introbx_image_wrapper">
            <img class="introbx_image_wrapper_img videobtn" data-appname="<?php echo esc_attr__($appname, 'automate_hub'); ?>" id="videobtn" src="<?php echo AWP_ASSETS ?>/images/videobutton.png">
        </div>
        <?php 
            require (AWP_VIEWS.'/awp_app_videos.php');
        ?>

   <?php

    $allowed_html = array(
        'a' => array(
            'href' => array(),
            'title' => array()
        ),
        'br' => array(),
        'em' => array(),
        'strong' => array(),
    );
   		echo wp_kses($instructions,$allowed_html);
    ?>
    
</div>
