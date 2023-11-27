<?php include AWP_INCLUDES.'/header.php';?>

<div class="wrap">
	<div class="pages-background"></div>
    <div id="icon-options-general" class="icon32"></div>
    <?php
    
    $current_tab = isset( $_REQUEST['tab'] ) ? sanitize_text_field($_REQUEST['tab']) : 'sperse';
    $current_cat = isset( $_REQUEST['cat'] ) ? sanitize_text_field($_REQUEST['cat']) : 'allcat';  
    $searched=isset( $_REQUEST['search'] ) ? sanitize_text_field($_REQUEST['search']) : false;  
    if (strtolower($current_cat) == "all") {
        $link = admin_url('admin.php?page=awp_app_directory');
    } else { ?>
    <div class="tab bottom-line">
      <div class="pull-left"><h3 class="sperse-app-page-title main-title"><?php esc_attr_e( "My Accounts", "automate_hub" ); ?></h3></div>
      <div class="pull-right">

        <?php   
        $cat_array = array(
                        'allcat'       => array( 'name'=>'All'      , 'default_tab'=>'sperse'  ),
                        'connectedapps'       => array( 'name'=>'Connected Apps'      , 'default_tab'=>''  ),
                        'favorites' => array( 'name'=>'Favorites', 'default_tab'=>'sperse'  ), 
                        'crm'       => array( 'name'=>'CRM'      , 'default_tab'=>'sperse'  ),
                        'esp'       => array( 'name'=>'ESP'      , 'default_tab'=>'sperse'  ),
                        'sms'       => array( 'name'=>'SMS'      , 'default_tab'=>'sperse'  ),
                        'webinars'  => array( 'name'=>'Webinars' , 'default_tab'=>'everwebinar'),
                        'other'     => array( 'name'=>'Other'    , 'default_tab'=>'sperse'  ),
                        
                   );
            $link = admin_url('admin.php?page=automate_hub');
            foreach ($cat_array as $cat_key => $cat_label_ar) { 
            $default_tab = !empty($cat_label_ar['default_tab']) ? $cat_label_ar['default_tab'] : 'sperse';
               $cat_path = add_query_arg( array(
                'cat' => $cat_key,
                'tab' => $default_tab,
            ), $link );
            if(strtolower($cat_key) == "all"){
                 $cat_path = admin_url('admin.php?page=awp_app_directory');
            }?>

            <a class="tablinks <?php echo esc_attr(( $current_cat == $cat_key ) ? 'active' : ''); ?>" href="<?php echo esc_url($cat_path); ?>"> <?php echo esc_html($cat_label_ar['name']); ?>
                
            </a>
        <?php } ?>
      </div>
    </div>
<?php
}
function get_platform_fav($tab_label){
$favicon = "";
$tabname = strtolower(preg_replace("/[^a-zA-Z]/ ", "", $tab_label));

switch ($tabname) {
    case "activecampaign"  : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/activecampaign.png\"  >" ; break;
    case "acumbamail"      : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/acumbamail.png\"  >" ; break;
    case "acton"           : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/acton.png\"           >" ; break;
    case "asana"           : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/asana.png\"           >" ; break;
    case "airmeet"         : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/airmeet.png\"        >" ; break;
    case "airtable"        : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/airtable.png\"        >" ; break;
    case "appcues"         : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/appcues.png\"        >" ; break;
    case "autopilot"       : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/autopilot.png\"                        >" ; break;
    case "agilecrm"        : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/agilecrm.png\"                         >" ; break;            
    case "aweber"          : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/aweber.png\"          >" ; break;
    case "benchmark"       : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/benchmark.png\"                        >" ; break;
    case "basecamp"        : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/basecamp3.png\">" ; break;
    case "baremetrics"     : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/baremetrics.png\">" ; break;
    case "breeze"          : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/breeze.png\">" ; break;
    case "calendly"        : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/calendly.png\"                         >" ; break;
    case "callrail"        : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/callrail.png\"                         >" ; break;
    case "calcom"          : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/calcom.png\"                         >" ; break;
    case "campaignmonitor" : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/campaignmonitor.png\" >" ; break;
    case "chargebee"       : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/chargebee.png\" >" ; break;
    case "cleverreach"     : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/cleverreach.png\" >" ; break;
    case "clickup"         : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/clickup.png\" >" ; break;
    case "clinchpad"       : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/clinchpad.png\">" ; break;
    case "close"           : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/close.png\"   >" ; break;
    case "clockify"        : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/clockify.png\"   >" ; break;
    case "companyhub"      : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/companyhub.png\"   >" ; break;
    case "contactsplus"    : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/contactsplus.png\"      >" ; break; 
    case "convertkit"      : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/convertkit.png\"      >" ; break; 
    case "copper"          : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/copper.png\"  >" ; break; 
    case "curated"         : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/curated.png\"          >" ; break; 
    case "customer"        : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/customer.png\"          >" ; break; 
    case "capsulecrm"      : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/capsulecrm.png\"                       >" ; break;
    case "directiq"        : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/directiq.png\">" ; break; 
    case "drift"           : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/drift.png\"  >" ; break; 
    case "drip"            : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/drip.png\"                             >" ; break; 
    case "easysendy"       : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/easysendy.png\"    >" ; break; 
    case "elasticemail"    : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/elasticemail.png\"    >" ; break; 
    case "emailoctopus"    : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/emailoctopus.png\"    >" ; break; 
    case "encharge"        : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/encharge.png\"    >" ; break; 
    case "esputnik"        : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/esputnik.png\"    >" ; break; 
    case "eventbrite"      : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/eventbrite.png\"    >" ; break; 
    case "everwebinar"     : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/everwebinar.png\"     >" ; break;
    case "firstpromoter"   : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/firstpromoter.png\"     >" ; break;
    case "fivetran"        : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/fivetran.png\"     >" ; break;
    case "followupboss"    : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/followupboss.png\"      >" ; break; 
    case "freshworks"      : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/freshworks.png\"      >" ; break; 
    case "freshdesk"       : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/freshdesk.png\"      >" ; break; 
    case "getgist"         : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/getgist.png\"     >" ; break; 
    case "getresponse"     : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/getresponse.png\"     >" ; break; 
    case "googlecalendar"  : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/googlecalendar.png\"                   >" ; break;     
    case "googlecontact"   : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/googlecontact.png\"                   >" ; break;     
    case "googledrive"     : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/googledrive.png\"                      >" ; break;
    case "gotomeeting"     : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/gotomeeting.png\"                     >" ; break; 
    case "googlesheets"    : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/googlesheets.png\"                     >" ; break; 
    case "growmatik"       : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/growmatik.png\"                     >" ; break; 
    case "gotowebinar"     : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/gotowebinar.png\"                     >" ; break; 
    case "goclient"        : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/go4client.png\">" ; break; 
    case "groundhogg"      : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/groundhogg.png\">" ; break; 
    case "helpscout"       : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/helpscout.png\">" ; break; 
    case "helpwise"        : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/helpwise.png\">" ; break; 
    case "highlevel"       : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/highlevel.png\"     >" ; break; 
    case "hubspot"         : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/hubspot.png\"         >" ; break;
    case "influencersoft"  : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/influencersoft.png\" >" ; break;
    case "insightly"       : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/insightly.png\"       >" ; break; 
    case "intercom"        : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/intercom.png\"       >" ; break; 
    case "jetwebinar"      : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/jetwebinar.png\" >" ; break;
    case "jumplead"        : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/jumplead.png\">" ; break; 
    case "kajabi"          : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/kajabi.png\"          >" ; break;
    case "kartra"          : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/kartra.png\"          >" ; break;
    case "keap"            : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/keap.png\"    >" ; break; 
    case "klaviyo"         : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/klaviyo.png\"                          >" ; break; 
    case "klipfolio"       : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/klipfolio.png\"                          >" ; break; 
    case "lemlist"         : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/lemlist.png\"         >" ; break; 
    case "liondesk"        : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/liondesk.png\"        >" ; break;
    case "lifterlms"       : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/lifterlms.png\">" ; break;  
    case "liveagent"       : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/liveagent.png\">" ; break;  
    case "livestorm"       : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/livestorm.png\">" ; break;  
    case "mailchimp"       : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/mailchimp.png\"       >" ; break;  
    case "mailercloud"     : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/mailercloud.png\"       >" ; break;  
    case "mailerlite"      : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/mailerlite.png\"      >" ; break;  
    case "mailify"         : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/mailify.png\"                     >" ; break;  
    case "mailjet"         : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/mailjet.png\"         >" ; break;  
    case "messagebird"     : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/messagebird.png\"     >" ; break;
    case "mailgun"         : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/mailgun.png"."\"                       >" ; break;  
    case "mailpoet"        : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/mailpoet.png"."\"                       >" ; break;  
    case "mojohelpdesk"    : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/mojohelpdesk.png"."\"                       >" ; break;  
    case "monday"          : $favicon = "<img src=\"https://www.google.com/s2/favicons?sz=16&domain=monday.com\"         >" ; break;
    case "moonmail"        : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/moonmail.png\"         >" ; break;  
    case "moosend"         : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/moosend.png\"         >" ; break;  
    case "omnisend"        : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/omnisend.png\"                         >" ; break;  
    case "onehash"         : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/onehash.png\"                         >" ; break;  
    case "ongage"          : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/ongage.png\"          >" ; break;  
    case "ontraport"       : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/ontraport.png\"       >" ; break;
    case "ortto"           : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/ortto.png\"       >" ; break;
    case "pabbly"          : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/pabbly.png\"          >" ; break;      
    case "paperform"       : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/paperform.png\"          >" ; break;      
    case "pipedrive"       : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/pipedrive.png\"       >" ; break;  
    case "productlift"     : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/productlift.png\"       >" ; break;  
    case "postmark"        : $favicon = "<img src=\"https://www.google.com/s2/favicons?sz=16&domain=postmark.com\"       >" ; break;
    case "pushover"        : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/pushover.png\">" ; break;
    case "readwise"        : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/readwise.png\"         >" ; break;  
    case "revue"           : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/revue.png\"         >" ; break;  
    case "salesforce"      : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/salesforce.png\"      >" ; break;  
    case "samdock"         : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/samdock.png\"      >" ; break;  
    case "salesflare"      : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/salesflare.png\"      >" ; break;  
    case "salesmate"       : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/salesmate.png\"      >" ; break;  
    case "sellsy"          : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/sellsy.png\"      >" ; break;  
    case "selzy"           : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/selzy.png\"      >" ; break;  
    case "sendfox"         : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/sendfox.png\"         >" ; break;  
    case "sendgrid"        : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/sendgrid.png\"        >" ; break;  
    case "sendinblue"      : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/sendinblue.png\"     >" ; break;  
    case "sendpulse"       : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/sendpulse.png\"       >" ; break;
    case "sendy"           : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/sendy.png\"                            >" ; break;  
    case "simvoly"         : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/simvoly.png\"                            >" ; break;  
    case "slack"           : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/slack.png\"           >" ; break;
    case "shopify"         : $favicon = "<img src=\"https://www.google.com/s2/favicons?sz=16&domain=shopify.com\"         >" ; break;
    case "smartsheet"      : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/smartsheet.png\"      >" ; break;  
    case "smtp"            : $favicon = "<img src=\"https://www.google.com/s2/favicons?sz=16&domain=smtp.com\"            >" ; break;  
    case "sperse"          : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/sperse.png\"          >" ; break;  
    case "squarespace"     : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/squarespace.png\"          >" ; break;  
    case "surecart"        : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/surecart.png\"          >" ; break;  
    case "teachable"       : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/teachable.png\"       >" ; break;  
    case "teamwork"       : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/teamwork.png\"       >" ; break;  
    case "teamworkcrm"       : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/teamworkcrm.png\"       >" ; break;  
    case "testmonitor"     : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/testmonitor.png\"       >" ; break;  
    case "todoist"         : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/todoist.png\"       >" ; break;  
    case "trello"          : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/trello.png\"          >" ; break;  
    case "trigger"         : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/trigger.png\"          >" ; break;  
    case "twilio"          : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/twilio.png\"                           >" ; break;
    case "validto"         : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/validto.png\"                           >" ; break;
    case "vbout"           : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/vbout.png\"                           >" ; break;
   
    case "vercel"          : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/vercel.png\"                           >" ; break;
    case "vicodo"          : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/vicodo.png\"                           >" ; break;
    case "wealthbox"       : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/wealthbox.png\"                        >" ; break; 
    case "webhookoutbound" : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/webhookin.png\"                        >" ; break; 
    case "webhookinbound"  : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/webhookout.png\"                       >" ; break;  
    case "webinarjam"      : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/webinarjam.png\"      >" ; break;
    case "woocommerce"     : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/woocommerce.png\"     >" ; break;
    case "woodpeckerco"    : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/woodpecker.png\"       >" ; break;  
    case "wufooforms"      : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/wufooforms.png\"       >" ; break;  
    case "zapier"          : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/zapier.png\"  >" ; break;
    case "zohocampaigns"   : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/zoho.png\"            >" ; break; 
    case "zulip"           : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/zulip.png\"            >" ; break; 
    case "default"         : $favicon = "<img src=\"".AWP_ASSETS."/images/favicons/default.png\"         >" ; break;  
    }
    return $favicon; 
}
?>


<?php 
$allcat = ($current_cat=='allcat')? 'display:block;' : 'display:none;';
?>

<div id="allcat" class="tabcontent" style="<?php echo  esc_attr($allcat); ?>">

    <div class="sperse-platform">
        <div class="nav-tab-wrapper nav-tab__switchable" id="navTabWrapper">
  <div class="nav-tab__filter">
    <input onkeyup="myFunction()" type="text" id="navTabSearch" autocomplete="off" placeholder="Filter by name">
  </div>

          <?php
           foreach ($tabs as $tab_key => $tab_label_ar) {       
             if($tab_key != "general") {
                 $favicon = "";
                 $tab_label = !empty($tab_label_ar['name']) ? $tab_label_ar['name'] :$tab_label_ar ;
                 $favicon = get_platform_fav($tab_label);
                 $tab_path = add_query_arg( array('tab' => $tab_key,'cat' => $current_cat,), admin_url( 'admin.php?page=automate_hub' ) );
              ?>
              <a class="nav-tab<?php echo ( $current_tab == $tab_key ) ? ' nav-tab-active' : ''; ?>" href="<?php echo esc_url($tab_path); ?>"><?php echo $favicon ?> &nbsp; <?php echo $tab_label ?></a>
          <?php }} ?>
      </div>
      <div  class="settings-form-wrap">
          <br>
          <?php
          if( $current_tab == 'general' ) {
          }
          do_action( 'awp_settings_view', $current_tab );
          ?>
      </div>
    </div>
  </div>






<?php 

$connectedapps = ($current_cat=='connectedapps')? 'display:block;' : 'display:none;';
?>
  <div id="connectedapps" class="tabcontent" style="<?php echo  esc_attr($connectedapps); ?>">
    <div class="sperse-platform">
        <div class="nav-tab-wrapper nav-tab__switchable">
            <div class="nav-tab__toggler">
                <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg"><path d="m15 4 2 2-6 6 6 6-2 2-8-8z" fill="#000" fill-rule="evenodd"/></svg>
            </div>
          <?php
            foreach ($tabs as $tab_key => $tab_label_ar) { 
                if($tab_key != "connectedapps") {
                  $favicon = "";
                  $tab_label = !empty($tab_label_ar['name']) ? $tab_label_ar['name'] :$tab_label_ar;
                  $favicon = get_platform_fav($tab_label);
                  $tab_label = !empty($tab_label_ar['name']) ? $tab_label_ar['name'] :$tab_label_ar ;
                  $match = false;
                  if(is_array($tab_label_ar) && count($tab_label_ar)>0 && !empty($tab_label_ar['cat']) ){
                    if(in_array('connectedapps', $tab_label_ar['cat'])){
                        $match = true;
                        }
                    }
                    if(!($match)) continue;
                    $tab_path = admin_url( 'admin.php?page=automate_hub' ).'&tab='.$tab_key.'&cat='.$current_cat;

                    $tab_path = add_query_arg( array(
                                      'tab' => $tab_key,
                                      'cat' => $current_cat,
                                  ), admin_url( 'admin.php?page=automate_hub' ) );

                    echo sprintf('<a class="nav-tab %s" href="%s">%s &nbsp; %s</a>', 
                    esc_attr(( $current_tab == $tab_key ) ? ' nav-tab-active' : ''),
                    esc_url($tab_path),
                    ($favicon),
                    $tab_label
                    );

             
                }
            
            }?>
      </div>
      <div  class="settings-form-wrap">
          <br>
          <?php
          if( $current_tab == 'general' ) {
          }
          do_action( 'awp_settings_view', $current_tab );
          ?>
      </div>
    </div>
  </div>
  

<?php 

$fv_active = ($current_cat=='favorites')? 'display:block;' : 'display:none;';
?>
  <div id="favorites" class="tabcontent" style="<?php echo  esc_attr($fv_active); ?>">

    <div class="sperse-platform">
		<div class="nav-tab-wrapper nav-tab__switchable">
			<div class="nav-tab__toggler">
				<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg"><path d="m15 4 2 2-6 6 6 6-2 2-8-8z" fill="#000" fill-rule="evenodd"/></svg>
			</div>
          <?php
            foreach ($tabs as $tab_key => $tab_label_ar) { 
                if($tab_key != "favorites") {
                  $favicon = "";
                  $tab_label = !empty($tab_label_ar['name']) ? $tab_label_ar['name'] :$tab_label_ar;
                  $favicon = get_platform_fav($tab_label);
                  $tab_label = !empty($tab_label_ar['name']) ? $tab_label_ar['name'] :$tab_label_ar ;
                  $match = false;
                  if(is_array($tab_label_ar) && count($tab_label_ar)>0 && !empty($tab_label_ar['cat']) ){
                    if(in_array('favorites', $tab_label_ar['cat'])){
                        $match = true;
                        }
                    }
                    if(!($match)) continue;
                    $tab_path = admin_url( 'admin.php?page=automate_hub' ).'&tab='.$tab_key.'&cat='.$current_cat;

                    $tab_path = add_query_arg( array(
                                      'tab' => $tab_key,
                                      'cat' => $current_cat,
                                  ), admin_url( 'admin.php?page=automate_hub' ) );

                    echo sprintf('<a class="nav-tab %s" href="%s">%s &nbsp; %s</a>', 
                    esc_attr(( $current_tab == $tab_key ) ? ' nav-tab-active' : ''),
                    esc_url($tab_path),
                    ($favicon),
                    $tab_label
                    );

             
                }
            
            }?>
      </div>
      <div  class="settings-form-wrap">
          <br>
          <?php
          if( $current_tab == 'general' ) {
          }
          do_action( 'awp_settings_view', $current_tab );
          ?>
      </div>
    </div>
  </div>
  <?php 
  $crm_active = ($current_cat=='crm')? 'display:block;' : 'display:none;';
  ?>
  <div id="crm" class="tabcontent" style="<?php echo esc_attr($crm_active); ?>">
    <div class="sperse-platform">
      <div class="nav-tab-wrapper">
		  <div class="nav-tab__toggler">
				<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg"><path d="m15 4 2 2-6 6 6 6-2 2-8-8z" fill="#000" fill-rule="evenodd"/></svg>
		  </div>
          <?php foreach ($tabs as $tab_key => $tab_label_ar) { 
              if($tab_key != "general") {
              $favicon = "";
              $tab_label = !empty($tab_label_ar['name']) ? $tab_label_ar['name'] :$tab_label_ar ;
              $match = false;
              if(is_array($tab_label_ar) && count($tab_label_ar)>0 && !empty($tab_label_ar['cat']) ){
                if(in_array('crm', $tab_label_ar['cat'])){
                                $match = true;
                }
              }
              if(!($match)) continue;
              $tabname = strtolower(preg_replace("/[^a-zA-Z]/ ", "", $tab_label));
              $favicon = get_platform_fav($tab_label);
              $tab_path = add_query_arg( array(
                              'tab' => $tab_key,
                              'cat' => $current_cat,
                          ), admin_url( 'admin.php?page=automate_hub' ) );

              
              echo sprintf('<a class="nav-tab %s" href="%s">%s &nbsp; %s</a>', 
                  esc_attr(( $current_tab == $tab_key ) ? ' nav-tab-active' : ''),
                  esc_url($tab_path),
                  ($favicon),
                  $tab_label
                  );

                }
        
        } ?>
      </div>
      <div class="settings-form-wrap">
          <br>
          <?php
          if( $current_tab == 'general' ) {
          }
          do_action( 'awp_settings_view', $current_tab );
          ?>
      </div>
    </div>
  </div>
  <?php 
$esp_active = ($current_cat=='esp')? 'display:block;' : 'display:none;';
?>
  <div id="esp" class="tabcontent" style="<?php echo esc_attr($esp_active); ?>">
    <div class="sperse-platform">
      <div class="nav-tab-wrapper">
		  	<div class="nav-tab__toggler">
				<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg"><path d="m15 4 2 2-6 6 6 6-2 2-8-8z" fill="#000" fill-rule="evenodd"/></svg>
			</div>
		          <?php
              
            foreach ($tabs as $tab_key => $tab_label_ar) { 
              if($tab_key != "general") {
              $favicon = "";
              $tab_label = !empty($tab_label_ar['name']) ? $tab_label_ar['name'] :$tab_label_ar;
              $favicon = get_platform_fav($tab_label);
              $tab_label = !empty($tab_label_ar['name']) ? $tab_label_ar['name'] :$tab_label_ar ;
              $match = false;
              if(is_array($tab_label_ar) && count($tab_label_ar)>0 && !empty($tab_label_ar['cat']) ){
                if(in_array('esp', $tab_label_ar['cat'])){
                    $match = true;
                }
          }
         if(!($match)) continue;
         $tab_path = admin_url( 'admin.php?page=automate_hub' ).'&tab='.$tab_key.'&cat='.$current_cat;

          $tab_path = add_query_arg( array(
                              'tab' => $tab_key,
                              'cat' => $current_cat,
                          ), admin_url( 'admin.php?page=automate_hub' ) );

            echo sprintf('<a class="nav-tab %s" href="%s">%s &nbsp; %s</a>', 
            esc_attr(( $current_tab == $tab_key ) ? ' nav-tab-active' : ''),
            esc_url($tab_path),
            ($favicon),
            $tab_label
            );

         
          }
        
        } ?>
      </div>
      <div class="settings-form-wrap">
          <br>
          <?php
          if( $current_tab == 'general' ) {
          }
          do_action( 'awp_settings_view', $current_tab );
          ?>
      </div>
    </div>
  </div>
<?php 
$sms_active = ($current_cat=='sms')? 'display:block;' : 'display:none;';
?>
  <div id="sms" class="tabcontent" style="<?php echo esc_attr($sms_active); ?>">
    <div class="sperse-platform">
      <div class="nav-tab-wrapper">
		  <div class="nav-tab__toggler">
				<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg"><path d="m15 4 2 2-6 6 6 6-2 2-8-8z" fill="#000" fill-rule="evenodd"/></svg>
		  </div>
          <?php foreach ($tabs as $tab_key => $tab_label_ar) { 
              if($tab_key != "general") {
              $favicon = "";
              $tab_label = !empty($tab_label_ar['name']) ? $tab_label_ar['name'] :$tab_label_ar ;
              $favicon = get_platform_fav($tab_label);
              $tab_label = !empty($tab_label_ar['name']) ? $tab_label_ar['name'] :$tab_label_ar ;
              $match = false;
              if(is_array($tab_label_ar) && count($tab_label_ar)>0 && !empty($tab_label_ar['cat']) ){
                if(in_array('sms', $tab_label_ar['cat'])){
                                $match = true;
                }
              }
              if(!($match)) continue;
              $tab_path = admin_url( 'admin.php?page=automate_hub' ).'&tab='.$tab_key.'&cat='.$current_cat;
               $tab_path = add_query_arg( array(
                              'tab' => $tab_key,
                              'cat' => $current_cat,
                          ), admin_url( 'admin.php?page=automate_hub' ) );
                          echo sprintf('<a class="nav-tab %s" href="%s">%s &nbsp; %s</a>', 
                          esc_attr(( $current_tab == $tab_key ) ? ' nav-tab-active' : ''),
                          esc_url($tab_path),
                          ($favicon),
                          $tab_label
                          );
                                   
              }
        
          } ?>
      </div>
      <div  class="settings-form-wrap">
          <br>
          <?php
          if( $current_tab == 'general' ) {
          }
          do_action( 'awp_settings_view', $current_tab );
          ?>
      </div>
    </div>
  </div>
<?php 
$webinars_active = ($current_cat=='webinars')? 'display:block;' : 'display:none;';
?>
  <div id="webinars" class="tabcontent" style="<?php echo esc_attr($webinars_active); ?>">
    <div class="sperse-platform">
      <div class="nav-tab-wrapper">
		  <div class="nav-tab__toggler">
				<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg"><path d="m15 4 2 2-6 6 6 6-2 2-8-8z" fill="#000" fill-rule="evenodd"/></svg>
			</div>

          <?php 
          foreach ($tabs as $tab_key => $tab_label_ar) { 
              if($tab_key != "general") {
              $favicon = "";
              $tab_label = !empty($tab_label_ar['name']) ? $tab_label_ar['name'] :$tab_label_ar ;
              $favicon = get_platform_fav($tab_label);
              $tab_label = !empty($tab_label_ar['name']) ? $tab_label_ar['name'] :$tab_label_ar ;
              $match = false;
              if(is_array($tab_label_ar) && count($tab_label_ar)>0 && !empty($tab_label_ar['cat']) ){
                if(in_array('webinar', $tab_label_ar['cat'])){
                                $match = true;
                }
              }
              if(!($match)) continue;
              $tab_path = admin_url( 'admin.php?page=automate_hub' ).'&tab='.$tab_key.'&cat='.$current_cat;

               $tab_path = add_query_arg( array(
                              'tab' => $tab_key,
                              'cat' => $current_cat,
                          ), admin_url( 'admin.php?page=automate_hub' ) );
                          echo sprintf('<a class="nav-tab %s" href="%s">%s &nbsp; %s</a>', 
                          esc_attr(( $current_tab == $tab_key ) ? ' nav-tab-active' : ''),
                          esc_url($tab_path),
                          ($favicon),
                          $tab_label
                          );
                          }
        
          } ?>
      </div>
      <div  class="settings-form-wrap">
          <br>
          <?php
          if( $current_tab == 'general' ) {
          }
          do_action( 'awp_settings_view', $current_tab );
          ?>
      </div>
    </div>
  </div>

<?php 
$other_active = ($current_cat=='other')? 'display:block;' : 'display:none;';
?>
  <div id="other" class="tabcontent" style="<?php echo esc_attr($other_active); ?>">
    <div class="sperse-platform">
      <div class="nav-tab-wrapper">
		  <div class="nav-tab__toggler">
				<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg"><path d="m15 4 2 2-6 6 6 6-2 2-8-8z" fill="#000" fill-rule="evenodd"/></svg>
			</div>
          <?php foreach ($tabs as $tab_key => $tab_label_ar) { 
              if($tab_key != "general") {
              $favicon = "";
              $tab_label = !empty($tab_label_ar['name']) ? $tab_label_ar['name'] :$tab_label_ar ;
              $favicon = get_platform_fav($tab_label);
              $tab_path = admin_url( 'admin.php?page=automate_hub' ).'&tab='.$tab_key.'&cat='.$current_cat;
              $tab_path = add_query_arg( array(
                              'tab' => $tab_key,
                              'cat' => $current_cat,
                          ), admin_url( 'admin.php?page=automate_hub' ) );

                          echo sprintf('<a class="nav-tab %s" href="%s">%s &nbsp; %s</a>', 
                          esc_attr(( $current_tab == $tab_key ) ? ' nav-tab-active' : ''),
                          esc_url($tab_path),
                          ($favicon),
                          $tab_label
                          );
                          }
        
        } ?>
      </div>
      <div class="settings-form-wrap">
          <br>
          <?php
          if( $current_tab == 'general' ) {
          }
          do_action( 'awp_settings_view', $current_tab );
          ?>
      </div>
    </div>
  </div>






