<?php


  $appname = !empty(($_GET['tab'])) ? sanitize_text_field($_GET['tab']) :"default";

  $video_base_url = 'https://sperse.io/setupvideo.php';

  $app_video_url = add_query_arg( 
    array( 
        'appname' => $appname,
    ), 
    $video_base_url
);

?>
<div id="myModal" class="modal myModal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title">
          <span id="dynamicappname" class="dynamicappname"></span><?php esc_html_e( ' Video Tutorial', 'automate_hub' ); ?>
        </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="main-section__video popup-iframe-parent" id="popup-iframe-parent">
        
        <object data="<?php echo esc_url($app_video_url); ?>" type="text/html" id="video" 
			   			standby="<?php esc_html_e( 'Video Loading, Please Wait...', 'automate_hub' ); ?>" title="<?php esc_html_e( 'Video is loading, please wait...', 'automate_hub' ); ?>" width="100%"
               height="650" scrolling="no" marginwidth="0" marginheight="0"  loading="lazy" frameborder="0" >
			   			<p> <?php echo sprintf('%s Video Cannot Load Here.', esc_html($appname)); ?><a href="<?php echo esc_url($app_video_url); ?>" target="_blank"><?php esc_html_e( 'Click Here to View.', 'automate_hub' ); ?></a></p>
						</object>

            </div>
      </div>
    </div>

  </div>
</div>






<!-- sperse promobox start -->
<div id="promobox" class="modal myModal" role="dialog">
  <div class="modal-dialog promo-banner">
    <div class="modal-content">
      <div class="modal-header background-white">
        
        <h4 class="modal-title">
          <span id="dynamicappname" class="dynamicappname"></span><?php esc_html_e( 'Grab This Exclusive Promotional Offer!', 'automate_hub' ); ?>
        </h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body promo-banner-spacing">
        <a target="_blank" href="https://sperse.io/cart?add-to-cart=5787">
          <img class="promo-banner-1" src="<?php echo AWP_ASSETS.'/images/sperse-promo-banner-1.png' ?>">
        </a>
        <a target="_blank" href="https://sperse.io/cart?add-to-cart=5789">
          <img class="promo-banner-2" src="<?php echo AWP_ASSETS.'/images/sperse-promo-banner-2.png' ?>">
        </a>
      </div>
    </div>

  </div>
</div>
<!-- sperse promobox end -->



