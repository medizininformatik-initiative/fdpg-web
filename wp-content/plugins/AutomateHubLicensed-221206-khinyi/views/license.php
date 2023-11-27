<?php include AWP_INCLUDES.'/header.php'; 
$consumptions=plugin_status();
//for limited version start
$vtype=version_type(get_option('sperse_license_key'));
if($vtype==3){
    $tfa=10;
    $tplat=10;
    $tintegrations=20;
    $tactions='10,000';
}
elseif ($vtype==5) {
    $tfa=': Unlimited';
    $tplat=': Unlimited';
    $tintegrations=': Unlimited';
    $tactions=': Unlimited';
}
elseif ($vtype==6) {
    $tfa=50;
    $tplat=50;
    $tintegrations=100;
    $tactions='100,000';
}
elseif ($vtype==9) {
    $tfa='-';
    $tplat=': Unlimited';
    $tintegrations=': Unlimited';
    $tactions=': Unlimited';
}
elseif ($vtype==10) {
    $tfa=5;
    $tplat=5;
    $tintegrations=5;
    $tactions='10,000';
}
elseif ($vtype==11) {
    $tfa=": Unlimited";
    $tplat=25;
    $tintegrations=25;
    $tactions='25,000';
}
elseif ($vtype==12) {
    $tfa=": Unlimited";
    $tplat="Unlimited";
    $tintegrations="Unlimited";
    $tactions='100,000';
}
elseif ($vtype==13) {
    $tfa=": Unlimited";
    $tplat="Unlimited";
    $tintegrations="Unlimited";
    $tactions="Unlimited";
}
else{
    $tal='';
    $tplat='';
    $tintegrations='';
    $tactions='';
}

// for limited version end

    if (isset($_POST['activate_license'])) {
        $url          = "https://".AWP_DOMAIN."/scripts/licenseManager/licenseManager.php";
        $license_key  =  isset( $_REQUEST['sperse_license_key']) ?  sanitize_text_field($_REQUEST['sperse_license_key']):'';
        $properties   = array( 'licenseKey' => $license_key, 'setStatus' => 'activate');
        $args = array('headers' => array('Content-Type' => 'application/json'), 'body' => json_encode($properties));
        $response = wp_remote_post( $url, $args );
        if (is_wp_error($response)){                                        
            echo esc_html__("Unexpected Error! The query returned with an error.",'automate_hub' );
        }
        $license_data = json_decode(wp_remote_retrieve_body($response));        
        if(($license_data->success == true)){ 
           
            if(!empty($license_data->data->expiresAt)){
                update_option('awp_l_exp',strtotime($license_data->data->expiresAt));
            }
            else{
                update_option('awp_l_exp','false');
            }
            
            update_option('sperse_license_key', $license_key);
            require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
            $obj=new AWP_Updates_Manager();
            $obj->trigger_action('license_activated');              
            AWP_redirect( admin_url( 'admin.php?page=automate_hub'));
        }
        else{  
           //Show error to the user. Probably entered incorrect license key.
           echo esc_html(sprintf('Status: %s', !empty($license_data->message)) ? $license_data->message :'' );
        }
    } 
    if (isset($_REQUEST['deactivate_license'])) {
        require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
        $obj=new AWP_Updates_Manager();
        $obj->trigger_action('license_deactivated');

        $url = "https://".AWP_DOMAIN."/scripts/licenseManager/licenseManager.php";
        $license_key  =  isset( $_REQUEST['sperse_license_key']) ?  sanitize_text_field($_REQUEST['sperse_license_key']):'';
        $properties = array('licenseKey'=> $license_key,'setStatus'=>'deactivate');
        $args       = array('headers'   => array('Content-Type' => 'application/json'), 'body' => json_encode($properties));
        $response = wp_remote_post( $url, $args );
        
        if (is_wp_error($response)){
            echo esc_html__("Unexpected Error! The query returned with an error.",'automate_hub' );
        }                                                                   
        $license_data = json_decode(wp_remote_retrieve_body($response));    
        if($license_data->success == true){                                 
            update_option('sperse_license_key', '');
            AWP_redirect( admin_url( 'admin.php?page=automate_license' ) ); 
        }else{
            echo esc_html(sprintf('Status: %s', !empty($license_data->message)) ? $license_data->message :'' );
        }
    } 
if(!empty(get_option('sperse_license_key'))){

?>




    <div id="root" style="width: 99%;">
      <div class="container pt-5 myrules" style="width: 100%;">
        <h4 style="padding-left: 10px;"> <?php esc_html_e("Overview",'automate_hub'); ?></h4>
        <div class="row align-items-stretch">


            <div class="c-dashboardInfo col-lg-3 col-md-6">
                <div class="wrap">
                  <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title"><?php esc_html_e("Connected Accounts",'automate_hub'); ?>
                  </h4>
                  <span class="hind-font caption-12 c-dashboardInfo__count"><?php 
                  $consumptions_y = isset($consumptions['Y']) ? sanitize_text_field($consumptions['Y']) :'';
                  echo esc_html($consumptions_y);  ?></span>
                  <!-- for limited version start -->
                  <span style="text-align:right;font-size: 11.5px;color: #808080eb;">Max Apps <strong><?php echo $tplat ?></strong></span>
                  <!-- for limited version end -->
                </div>
            </div>
            <div class="c-dashboardInfo col-lg-3 col-md-6">
                <div class="wrap">
                  <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">
                    <?php esc_html_e("Form Sources",'automate_hub'); ?>
                  </h4>

                  <span class="hind-font caption-12 c-dashboardInfo__count"><?php 
                  $consumptions_fs = isset($consumptions['FS']) ? sanitize_text_field($consumptions['FS']) :'';
                  echo esc_html($consumptions_fs);  ?></span>
                  <!-- for limited version start -->
                  <span style="text-align:right;font-size: 11.5px;color: #808080eb;">Max Form Sources <strong><?php echo $tfa ?></strong></span>
                  <!-- for limited version end -->

                </div>
            </div>

            <div class="c-dashboardInfo col-lg-3 col-md-6">
                <div class="wrap">
                  <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">
                      
                      <?php esc_html_e("Active Integrations (SPOTS)",'automate_hub'); ?>
                  </h4>
                  <span class="hind-font caption-12 c-dashboardInfo__count"><?php 
                  $consumptions_w = isset($consumptions['W']) ? sanitize_text_field($consumptions['W']) :'';
                  echo esc_html($consumptions_w);    ?></span>
                  <!-- for limited version start -->
                  <span style="text-align:right;font-size: 11.5px;color: #808080eb;">Max Integrations <strong><?php echo $tintegrations ?></strong></span>
                  <!-- for limited version end -->
                </div>
            </div>

            <div class="c-dashboardInfo col-lg-3 col-md-6">
                <div class="wrap">
                  <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">
                    <?php esc_html_e("API Calls (BOT Actions)",'automate_hub'); ?>
                  </h4>
                  <span class="hind-font caption-12 c-dashboardInfo__count"><?php 
                    $consumptions_z = isset($consumptions['Z']) ? $consumptions['Z'] :'';
                    echo esc_html($consumptions_z);  ?></span>
                  <!-- for limited version start -->
                  <span style="text-align:right;font-size: 11.5px;color: #808080eb;">Max API Calls <strong><?php echo $tactions ?></strong></span>
                  <!-- for limited version end -->
             
                </div>
            </div>
        </div>
      </div>
    </div>


<?php
  
}

?>

<main>
    <section class="main-section">
		  <div class="container-license">
			<div class="pages-background"></div>
            <div class="row-license">   
                    <div class="form-container">
                        <div class="form-container-left">                  
                            <?php 
                            if(get_option('sperse_license_key')){ ?>
                                <div class="activate-instruction"><center>
                                <h3><?php esc_html_e('Congratulation!','automate_hub'); ?></h3>
                                <h4><?php esc_html_e('Your software license is successfully activated and current.','automate_hub'); ?></h4></center>
                                </div>
                            <?php } else { ?>
                                <div class="activate-instruction"><center>
                                <h3><?php esc_html_e('Activate your license and access all 100+ SaaS apps and forms!','automate_hub'); ?></h3>
                                <h5><?php esc_html_e('To activate Sperse Automate Hub plugin, enter the license key that you received after your order.','automate_hub'); ?></h5></center>
                                </div>
                            <?php } ?>
                            <div id="form-wrap">
                                <form action="" method="post">
                                    <table class="form-table">
                                        <tr>
                                            <td><?php 
                                            $sperse_license_key = get_option('sperse_license_key');
                                            if($sperse_license_key){ 
                                                ?>
                                                <span><label for="sperse_license_key"><p class="license-key"><?php esc_html_e("Your License Key",'automate_hub'); ?></p></label></span>
                                                <span class="simple_active_license"><center><input class="license-code" type="text" id="sperse_license_key_copy" name="sperse_license_key"  placeholder="Enter your license key" data-required="true" value="<?php echo esc_attr($sperse_license_key); ?>" readonly>
                                                <span class="spci_btn" data-clipboard-action="copy" data-clipboard-target="#sperse_license_key_copy"><img src="<?php echo AWP_ASSETS; ?>/images/copy.png" alt="Copy to clipboard"></span></center></span>
                                                <?php   }else{ ?>
                                                <input class="license-code-activation" type="text"   id="sperse_license_key" name="sperse_license_key"  placeholder="Enter your license key" data-required="true" 
                                                <?php if(get_option('sperse_license_key')){ echo 'disabled';} ?> value="<?php echo esc_attr($sperse_license_key);  ?>" >
                                                 <?php } ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <div class="submit-button-plugin">
                                        <?php if(get_option('sperse_license_key')){ ?>
                                            <input type="submit" name="deactivate_license" value="Deactivate License Key" class="button-deactivate" />
                                        <?php } else { ?>
                                            <input type="submit" name="activate_license" value="Activate Your License" class="button-activate" />
                                        <?php } ?>
                                    </div>
                                </form>
                            </div>
                            <p class="get-license-key"><?php echo sprintf( esc_html__( 'How can I  get my license key?%s', 'automate_hub' ), '<a href="https://sperse.io/?utm_source=WordPress&utm_campaign=freeplugin&utm_medium=upgradelink&utm_content=Activate&offfer=" target="_blank">read this article.</a>' ); ?></a></p>
			</div>
                        <div class="form-container-right">
                            <h3><?php esc_html_e(" Need A New License Key?",'automate_hub'); ?></h3>
                            <ul><li><i class="checkmarks"></i> <?php esc_html_e("Unlock access to all 100+ apps",'automate_hub'); ?></li>
                                <li><i class="checkmarks"></i> <?php esc_html_e("Integrate with multiple WP forms",'automate_hub'); ?></li>
                                <li><i class="checkmarks"></i> <?php esc_html_e("Sperse CRM integration",'automate_hub'); ?></li>
                                <li><i class="checkmarks"></i> <?php esc_html_e("Monthly new additions and updates",'automate_hub'); ?></li>
                                <li><i class="checkmarks"></i> <?php esc_html_e("Upgrade for unlimited actions",'automate_hub'); ?></li>                                                                
                                <li><i class="checkmarks"></i> <?php esc_html_e("24/7 online customer support",'automate_hub'); ?></li>
                            </ul>
                            <div class="sign-up">
                                <?php if(get_option('sperse_license_key')){ ?>
                                <a href="https://sperse.io/upgrade" target="_blank"><?php esc_html_e("Upgrade Options",'automate_hub'); ?></a>
                                <?php } else { ?>
                                <a href="https://sperse.io/automate" target="_blank"><?php esc_html_e("Sign Up Now",'automate_hub'); ?></a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </section>
</main>
