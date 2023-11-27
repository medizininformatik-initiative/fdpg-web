<?php 
$awp_deactivate_nonce = wp_create_nonce('awp-deactivate-nonce');
?>


<style type="text/css">
	.awp-hidden{

      overflow: hidden;
    }
    .awp-popup-overlay .awp-internal-message{
      margin: 3px 0 3px 22px;
      display: none;
    }
    .awp-reason-input{
      margin: 3px 0 3px 22px;
      display: none;
    }
    .awp-reason-input input[type="text"]{

      width: 100%;
      display: block;
    }
  .awp-popup-overlay{

    background: rgba(0,0,0, .8);
    position: fixed;
    top:0;
    left: 0;
    height: 100%;
    width: 100%;
    z-index: 1000;
    overflow: auto;
    visibility: hidden;
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .awp-popup-overlay.awp-active{
    opacity: 1;
    visibility: visible;
  }
  .awp-serveypanel{
    width: 600px;
    background: #fff;
    margin: 0 auto 0;
    border-radius: 3px;
  }
  .awp-popup-header{
    background: #f1f1f1;
    padding: 20px;
    border-bottom: 1px solid #ccc;
  }
  .awp-popup-header h2{
    margin: 0;
    text-transform: uppercase;
  }
  .awp-popup-body{
      padding: 10px 20px;
  }
  .awp-popup-footer{
    background: #f9f3f3;
    padding: 10px 20px;
    border-top: 1px solid #ccc;
  }
  .awp-popup-footer:after{

    content:"";
    display: table;
    clear: both;
  }
  .action-btns{
    float: right;
  }
  .awp-anonymous{

    display: none;
  }
  .attention, .error-message {
    color: red;
    font-weight: 600;
    display: none;
  }
  .awp-spinner{
    display: none;
  }
  .awp-spinner img{
    margin-top: 3px;
  }
  .awp-pro-message{
    padding-left: 24px;
    color: red;
    font-weight: 600;
    display: none;
  }
  .awp-popup-header{
    background: none;
        padding: 18px 15px;
    -webkit-box-shadow: 0 0 8px rgba(0,0,0,.1);
    box-shadow: 0 0 8px rgba(0,0,0,.1);
    border: 0;
}
.awp-popup-body h3{
    margin-top: 0;
    margin-bottom: 30px;
        font-weight: 700;
    font-size: 15px;
    color: #495157;
    line-height: 1.4;
    text-tranform: uppercase;
}
.awp-reason{
    font-size: 13px;
    color: #6d7882;
    margin-bottom: 15px;
}
.awp-reason input[type="radio"]{
margin-right: 15px;
}
.awp-popup-body{
padding: 30px 30px 0;

}
.awp-popup-footer{
background: none;
    border: 0;
    padding: 29px 39px 39px;
}
  .col-md-4{
        width: 33.3333333%;
        float: left;
        position: relative;
        min-height: 1px;
        padding-right: 15px;
        padding-left: 15px;
  }

.better-reason,.other-reason{
	margin-top: 10px;
}
</style>



<div class="awp-popup-overlay">
  <div class="awp-serveypanel">
    <form action="#" method="post" id="awp-deactivate-form">
    <div class="awp-popup-header">
      <h2>Quick feedback about Automate HUB | Ultimate</h2>
    </div>
    <div class="awp-popup-body">
      <h3>If you have a moment, please let us know why you are deactivating:</h3>
      <input type="hidden" class="Triad_Mark_deactivate_nonce" name="Triad_Mark_deactivate_nonce" value="<?php echo esc_attr($awp_deactivate_nonce ) ?>">
      <ul id="awp-reason-list">
        
        <li class="awp-reason" data-input-type="" data-input-placeholder="">
          <label>
            <span>
              <input type="radio" name="awp-selected-reason" value="1">
            </span>
            <span>I only needed the plugin for a short period</span>
          </label>
          <div class="awp-internal-message"></div>
        </li>


        <li class="awp-reason has-input" data-input-type="textfield">
          <label>
            <span>
              <input type="radio" name="awp-selected-reason" value="8">
            </span>
            <span>Couldn't find the required platform in the list</span>
          </label>
          <div class="awp-internal-message"></div>
          <div class="awp-reason-input" style="display: none;"><span class="message error-message " style="display: none;">Kindly tell us the platform that you couldn't find.</span><input class="better-reason" type="text" name="awpcannot_find_app_h3" placeholder="What's the platform name ?"></div>
        </li>


        <li class="awp-reason has-input" data-input-type="textfield">
          <label>
            <span>
              <input type="radio" name="awp-selected-reason" value="2">
            </span>
            <span>I found a better plugin</span>
          </label>
          <div class="awp-internal-message"></div>
          <div class="awp-reason-input" style="display: none;"><span class="message error-message " style="display: none;">Kindly tell us the Plugin name.</span><input class="better-reason" type="text" name="awpbetter_plugin_h3" placeholder="What's the plugin's name?"></div>
        </li>
        <li class="awp-reason" data-input-type="" data-input-placeholder="">
          <label>
            <span>
              <input type="radio" name="awp-selected-reason" value="3">
            </span>
            <span>The plugin broke my site</span>
          </label>
          <div class="awp-internal-message"></div>
        </li>
        <li class="awp-reason" data-input-type="" data-input-placeholder="">
          <label>
            <span>
              <input type="radio" name="awp-selected-reason" value="4">
            </span>
            <span>The plugin suddenly stopped working</span>
          </label>
          <div class="awp-internal-message"></div>
        </li>
        <li class="awp-reason" data-input-type="" data-input-placeholder="">
          <label>
            <span>
              <input type="radio" name="awp-selected-reason" value="5">
            </span>
            <span>I no longer need the plugin</span>
          </label>
          <div class="awp-internal-message"></div>
        </li>
        <li class="awp-reason" data-input-type="" data-input-placeholder="">
          <label>
            <span>
              <input type="radio" name="awp-selected-reason" value="6">
            </span>
            <span>It's a temporary deactivation. I'm just debugging an issue.</span>
          </label>
          <div class="awp-internal-message"></div>
        </li>
        <li class="awp-reason has-input" data-input-type="textfield">
          <label>
            <span>
              <input type="radio" name="awp-selected-reason" value="7">
            </span>
            <span>Other</span>
          </label>
          <div class="awp-internal-message"></div>
          <div class="awp-reason-input" style="display: none;"><span class="message error-message " style="display: none;">Kindly tell us the reason so we can improve.</span><input class="other-reason" type="text" name="awpother_reason_h3" placeholder="Kindly tell us the reason so we can improve."></div>
        </li>
      </ul>
    </div>
    <div class="awp-popup-footer">
      <label class="awp-anonymous"><input type="checkbox">Anonymous feedback</label>
        <input type="button" class="button button-secondary button-skip awp-popup-skip-feedback" value="Skip &amp; Deactivate">
      <div class="action-btns">
        <span class="awp-spinner"><img src="http://automatehubultimate.faizanjaved.com/wp-admin/images/spinner.gif" alt=""></span>
        <input type="submit" class="button button-secondary button-deactivate awp-popup-allow-deactivate" value="Submit &amp; Deactivate" disabled="disabled">
        <a href="#" class="button button-primary awp-popup-button-close">Cancel</a>

      </div>
    </div>
  </form>
    </div>
  </div>

<script>
    (function( $ ) {

      jQuery(function() {

       
        // Code to fire when the DOM is ready apna.
        jQuery(document).on('click', 'tr[data-slug="awp"] .deactivate', function(e){
          e.preventDefault();
          
          $('.awp-popup-overlay').addClass('awp-active');
          $('body').addClass('awp-hidden');
        });
        $(document).on('click', '.awp-popup-button-close', function () {
          close_popup();
        });
        $(document).on('click', ".awp-serveypanel,tr[data-slug='awp'] .deactivate",function(e){
            e.stopPropagation();
        });

        $(document).click(function(){
          close_popup();
        });
        $('.awp-reason label').on('click', function(){
          if($(this).find('input[type="radio"]').is(':checked')){
            //$('.bsp-anonymous').show();
            $(this).next().next('.awp-reason-input').show().end().end().parent().siblings().find('.awp-reason-input').hide();
          }
        });
        $('input[type="radio"][name="awp-selected-reason"]').on('click', function(event) {
          $(".awp-popup-allow-deactivate").removeAttr('disabled');
          $(".awp-popup-skip-feedback").removeAttr('disabled');
          $('.message.error-message').hide();
          $('.awp-pro-message').hide();
        });

        $('.awp-reason-pro label').on('click', function(){
          if($(this).find('input[type="radio"]').is(':checked')){
            $(this).next('.awp-pro-message').show().end().end().parent().siblings().find('.awp-reason-input').hide();
            $(this).next('.awp-pro-message').show()
            $('.awp-popup-allow-deactivate').attr('disabled', 'disabled');
            $('.awp-popup-skip-feedback').attr('disabled', 'disabled');
          }
        });
        $(document).on('submit', '#awp-deactivate-form', function(event) {
          event.preventDefault();

          var _reason =  $('input[type="radio"][name="awp-selected-reason"]:checked').val();
          var _reason_details = '';

          var deactivate_nonce = $('.Triad_Mark_deactivate_nonce').val();

          if ( _reason == 2 ) {
            _reason_details = jQuery("input[type='text'][name='awpbetter_plugin_h3']").val();
          } else if ( _reason == 7 ) {
            _reason_details = jQuery("input[type='text'][name='awpother_reason_h3']").val();
          }
          else if ( _reason == 8 ) {
            _reason_details = jQuery("input[type='text'][name='awpcannot_find_app_h3']").val();
          }



          if ( ( _reason == 7 || _reason == 2 || _reason == 8 ) && _reason_details == '' ) {
            $('.message.error-message').show();
            return ;
          }
          $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
              action        : 'awp_deactivate_feedback',
              reason        : _reason,
              reason_detail : _reason_details,
              security      : deactivate_nonce
            },
            beforeSend: function(){
              $(".awp-spinner").show();
              $(".awp-popup-allow-deactivate").attr("disabled", "disabled");
            }
          })
          .done(function() {
            $(".awp-spinner").hide();
            $(".awp-popup-allow-deactivate").removeAttr("disabled");
            window.location.href =  $("tr[data-slug='awp'] .deactivate a").attr('href');
          });

        });

        $('.awp-popup-skip-feedback').on('click', function(e){
          // e.preventDefault();
          window.location.href =  $("tr[data-slug='awp'] .deactivate a").attr('href');
        })

        function close_popup() {
          $('.awp-popup-overlay').removeClass('awp-active');
          $('#awp-deactivate-form').trigger("reset");
          $(".awp-popup-allow-deactivate").attr('disabled', 'disabled');
          $(".awp-reason-input").hide();
          $('body').removeClass('awp-hidden');
          $('.message.error-message').hide();
          $('.awp-pro-message').hide();
        }
        });

        })( jQuery ); // This invokes the function above and allows us to use '$' in place of 'jQuery' in our code.
</script>