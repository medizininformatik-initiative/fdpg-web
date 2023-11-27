<?php
/* Plugin Name: Automate Hub | Licensed
 * Description: Connect and automate your WordPress forms and workflows with Sperse CRM, and dozens of other major CRMs, Marketing and Webinar systems, SMS & unified messaging platforms and Email Service Providers (ESPs).
 * Author: Sperse.IO   
 * Author URI: https://sperse.io
 * Plugin URI: https://sperse.io/automate
 * Version: 23.05.15
 * License: GPLv2 or later
 * Text Domain: automate-hub
 * Domain Path: languages
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 * This is an add-on for WordPress http://wordpress.org
 *
 * Copyright 2018-2022 Sperse, Inc.
 * 
 * Sperse.io Automate Hub and App Marketplace is distributed under the 
 * terms of the GNU General Public License as published by the Free 
 * Software Foundation, either version 2 or later version of the License.

 * This program is distributed in the hope that it will be useful, but 
 * WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 * See the GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see http://www.gnu.org/licenses.
 * 
 * @package Sperse.IO Automate Hub
 * @author  Sperse.IO
 */

if ( !defined( 'ABSPATH' ) ) { exit; }  // don't call the file directly
/* SPERSE.IO AUTOMATE HUB MAIN CLASS */
class SPERSE
{
    /* Plugin Version @var  string */
    public  $version = '23.05.15' ; 
    public $plugin_type="Licensed";
    /* Initializes the SPERSE class | Checks for an existing SPERSE instance and if it doesn't find one, creates it.
     * @since 1.0.0 @return mixed | bool 
     */
    public static function init()
    {   static  $instance = false ;
        if ( !$instance ) { $instance = new SPERSE(); }
        return $instance;
    }
    
    /* Constructor for the SPERSE class | Sets up all the appropriate hooks and actions
     * @since 1.0 @return void 
     */

    public function __construct()
    {   
        $this->awp_deactivate_plugin_conditional();
        register_activation_hook( __FILE__, [ $this, 'awp_activate' ] );
        register_deactivation_hook( __FILE__, [ $this, 'awp_deactivate' ] );
        if( is_multisite() ){
          add_action( 'wpmu_new_blog', array( $this, 'awp_new_blog_generate'), 10, 6 );
          add_filter( 'wpmu_drop_tables', array( $this, 'awp_blog_delete') );
        }
        
        $this->awp_init_plugin();
       
        
    }

    function awp_deactivate_plugin_conditional() {
        if (!function_exists('is_plugin_active')) {
            include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }
        if ( is_plugin_active('automate-hub-free-by-sperse-io/automate_hub.php') ) {
            deactivate_plugins('automate-hub-free-by-sperse-io/automate_hub.php');   
            return false; 
        }
        
    }
    
    
    /* Initialize plugin  
     * @since 1.0.0 @return void 
     */
    public function awp_init_plugin()
    {   
        $this->awp_define_constants();              // Define constats
        $this->awp_includes();                      // Include files
        $this->init_classes();                  // Instantiate classes
        $this->init_actions();                  // Initialize the action hooks 
        $this->init_filters();                  // Initialize the filter hooks
    }

     function awp_new_blog_generate(  $blog_id, $user_id, $domain, $path, $site_id, $meta ){
            if ( is_plugin_active_for_network( plugin_basename(__FILE__) ) ) {
               switch_to_blog( $blog_id );
               $this->awp_create_table();
               $this->create_webhook();
               require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
               $obj=new AWP_Updates_Manager();
               $obj->trigger_action('plugin_activated');
               $ip = getUserIpAddrForSperse();
               $obj->trigger_action('ip_address',$ip );
               restore_current_blog();
             } 
     }

      function awp_blog_delete( $tables ){
        global $wpdb;
        $tables[] = str_replace( $wpdb->base_prefix, $wpdb->prefix, 'awp_integration' );
        $tables[] = str_replace( $wpdb->base_prefix, $wpdb->prefix, 'awp_message_template' );
        $tables[] = str_replace( $wpdb->base_prefix, $wpdb->prefix, 'automate_log' );
        $tables[] = str_replace( $wpdb->base_prefix, $wpdb->prefix, 'userCreatedOrUpdated' );
        return $tables; 
     }
     public function create_webhook(){
        if(empty(get_option('awp_webhook_api_key')) || get_option('awp_webhook_api_key')== '' ){
                $charid = strtoupper(md5(uniqid(rand(), true)));
                $hyphen = chr(45);// "-"
                $api_key = substr($charid, 0, 8).$hyphen
                        .substr($charid, 8, 4).$hyphen
                        .substr($charid,12, 4).$hyphen
                        .substr($charid,16, 4).$hyphen
                        .substr($charid,20,12);
                update_option("awp_webhook_api_key",$api_key);
                
        }
        awp_create_default_webhook();
     }
    
    /* Placeholder for activation function
     * @since 1.0 @return void
     */
    public function awp_activate($network_wide)
    {   
        if ( is_multisite() && $network_wide ) {
                global $wpdb;
                $currentblog = $wpdb->blogid;
                $activated = array();
                $blog_ids = get_sites();
                foreach ( $blog_ids as $blog_id ) {
                    
                    switch_to_blog( $blog_id->blog_id  );
                    $this->awp_create_table();
                    $this->create_webhook();

                    $activated[] = $blog_id->blog_id;
                    
                }
                switch_to_blog( $currentblog );
                update_site_option( 'sp_activated', $activated );
        }else{
            $this->awp_create_table();  
            $this->create_webhook();    
            require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
            $obj=new AWP_Updates_Manager();
            $obj->trigger_action('plugin_activated');  
            $ip = getUserIpAddrForSperse();
            $obj->trigger_action('ip_address',$ip);
        }      // Create default tables when plugin activates
    }
    
    /* Placeholder for creating tables while activationg plugin
     * @since 1.0 @return void
     */
    private function awp_create_table()
    {  
        require_once dirname(__FILE__) . '/includes/awp-default-settings.php';
    }
    
    /* Placeholder for deactivation function
     * @since 1.0 @return void
     */
    public function awp_deactivate()
    {
        require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
        $obj=new AWP_Updates_Manager();
        $obj->trigger_action('plugin_deactivated');
    }
    
    /* Define Add-on Constants*/
    public function awp_define_constants()
    {   
        $this->awp_define( 'AWP_VERSION'  , $this->version            );           // Plugin Version
        $this->awp_define( 'AWP_PLUGIN'  , $this->plugin_type            );           // Plugin Type
        $this->awp_define( 'AWP_FILE'     , __FILE__                  );           // Plugin Main Folder Path
        $this->awp_define( 'AWP_PATH'     , dirname(AWP_FILE)         );           // Parent Directory Path
        $this->awp_define( 'AWP_URL'      , plugins_url('', AWP_FILE ));           // URL Path
        $this->awp_define( 'AWP_ASSETS'   , AWP_URL     . '/assets'   );           // Folder path for assets, images, css and js
        $this->awp_define( 'AWP_ASSETS_REMOTE'   ,'https://sperse.io/scripts/authorization/instructions/assets'   );
        $this->awp_define( 'AWP_IMAGES'   , AWP_ASSETS  . '/images'   );           // Folder path for assets, images, css and js
        $this->awp_define( 'AWP_CSS'   ,   AWP_ASSETS  . '/css'   );           // Folder path for assets, images, css and js
        $this->awp_define( 'AWP_JS'   ,    AWP_ASSETS  . '/js'   );           // Folder path for assets, images, css and js      
        $this->awp_define( 'AWP_APPS'     , AWP_PATH    . '/apps'     );           // Folder path for all destination apps
        $this->awp_define( 'AWP_INCLUDES' , AWP_PATH    . '/includes' );           // Folder path for include files
        $this->awp_define( 'AWP_VIEWS'    , AWP_PATH    . '/views'    );           // Folder path for section views
        $this->awp_define( 'AWP_TEMPLATES', AWP_PATH    . '/views'    );           // Folder Path for templates
        $this->awp_define( 'AWP_WEB_FORMS', AWP_PATH    . '/webforms' );           // Folder path for source input forms
        $this->awp_define( 'AWP_SCRIPTS'  , AWP_PATH    . '/scripts'  );           // Folder path for scripts
        $this->awp_define( 'AWP_DOMAIN'   , 'sperse.io'  );                        // Domain API
        $this->awp_define( 'AWP_COPY_ICON'   ,AWP_ASSETS.'/images/copy.png');                        // Domain API

    }
    
    /* Include the required files
     * @since 1.0 @return void
     */
    function awp_define($name, $value)
    {
    if (!defined($name)) {
        define($name, $value);
    }
    }
    /* Include the required files */
    public function awp_includes(){        
    //------ADD CLASS FILES ------------------------------------------//
        include AWP_INCLUDES  . '/class_awp_admin_menu.php';
        include AWP_INCLUDES  . '/class_awp_list_table.php';
        if(file_exists(AWP_INCLUDES  . '/class_awp_message_table.php')){
            include AWP_INCLUDES  . '/class_awp_message_table.php';
        }
        include AWP_INCLUDES  . '/class_awp_log_table.php';
        include AWP_INCLUDES  . '/class_platform_shell.php';
        include AWP_INCLUDES  . '/class_awp_submission.php';
        include AWP_INCLUDES  . '/class_oauth.php';
        include AWP_INCLUDES  . '/class_awp_ajax.php';
        include_once AWP_INCLUDES  . '/functions_awp.php';
        include_once AWP_INCLUDES  . '/awp-form-fields.php';

//------ADD PLATFORM FACTORY------------------------------------------//
        include AWP_INCLUDES . '/appfactory.php';
//------ADD LIST OF APPS------------------------------------------//
        include_once AWP_APPS . '/s/sperse/sperse.php';
        include_once AWP_APPS . '/s/sperse/sperseRestApi.php';
        include_once AWP_APPS . '/a/activecampaign/activecampaign.php'; 
        include_once AWP_APPS . '/a/acumbamail/acumbamail.php';
        include_once AWP_APPS . '/a/agilecrm/agilecrm.php'; 
        include_once AWP_APPS . '/a/airtable/airtable.php';
        include_once AWP_APPS . '/a/airmeet/airmeet.php';
        include_once AWP_APPS . '/a/appcues/appcues.php';
        include_once AWP_APPS . '/a/asana/asana.php';
        include_once AWP_APPS . '/a/autopilot/autopilot.php';        
        include_once AWP_APPS . '/a/aweber/aweber.php';
        include_once AWP_APPS . '/b/baremetrics/baremetrics.php';
        include_once AWP_APPS . '/b/basecamp3/basecamp3.php';
        include_once AWP_APPS . '/b/benchmark/benchmark.php';
        include_once AWP_APPS . '/b/breeze/breeze.php';
        include_once AWP_APPS . '/c/calendly/calendly.php';
        include_once AWP_APPS . '/c/callrail/callrail.php';
        include_once AWP_APPS . '/c/calcom/calcom.php';
        include_once AWP_APPS . '/c/campaignmonitor/campaignmonitor.php';
        include_once AWP_APPS . '/c/capsulecrm/capsulecrm.php';        
        include_once AWP_APPS . '/c/chargebee/chargebee.php';        
        include_once AWP_APPS . '/c/cleverreach/cleverreach.php';        
        include_once AWP_APPS . '/c/clickup/clickup.php';        
        include_once AWP_APPS . '/c/clinchpad/clinchpad.php';
        include_once AWP_APPS . '/c/close/close.php';
        include_once AWP_APPS . '/c/clockify/clockify.php';

        include_once AWP_APPS . '/c/companyhub/companyhub.php';
        include_once AWP_APPS . '/c/contactsplus/contactsplus.php';
        include_once AWP_APPS . '/c/convertkit/convertkit.php';
        include_once AWP_APPS . '/c/copper/copper.php';
        include_once AWP_APPS . '/c/curated/curated.php';
        include_once AWP_APPS . '/c/customer/customer.php';
        include_once AWP_APPS . '/d/directiq/directiq.php';
        include_once AWP_APPS . '/d/drift/drift.php';
        include_once AWP_APPS . '/d/drip/drip.php';
        include_once AWP_APPS . '/e/easysendy/easysendy.php';
        include_once AWP_APPS . '/e/elasticemail/elasticemail.php';
        include_once AWP_APPS . '/e/emailoctopus/emailoctopus.php';
        include_once AWP_APPS . '/e/encharge/encharge.php';
        include_once AWP_APPS . '/e/esputnik/esputnik.php';
        include_once AWP_APPS . '/e/eventbrite/eventbrite.php';
        include_once AWP_APPS . '/e/everwebinar/everwebinar.php';        
        include_once AWP_APPS . '/f/firstpromoter/firstpromoter.php';        
        include_once AWP_APPS . '/f/fivetran/fivetran.php';        
        include_once AWP_APPS . '/f/followupboss/followupboss.php';
        include_once AWP_APPS . '/f/freshworks/freshworks.php';
        include_once AWP_APPS . '/f/freshdesk/freshdesk.php';
        include_once AWP_APPS . '/g/getgist/getgist.php';
        include_once AWP_APPS . '/g/getresponse/getresponse.php';
        include_once AWP_APPS . '/g/googlecalendar/googlecalendar.php';        
        //include_once AWP_APPS . '/g/googlecontact/googlecontact.php';        
        include_once AWP_APPS . '/g/googledrive/googledrive.php';        
        include_once AWP_APPS . '/g/googlesheets/googlesheets.php';
        include_once AWP_APPS . '/g/growmatik/growmatik.php';
        include_once AWP_APPS . '/g/gotomeeting/gotomeeting.php';
        include_once AWP_APPS . '/g/gotowebinar/gotowebinar.php';
        include_once AWP_APPS . '/g/go4client/go4client.php';
        include_once AWP_APPS . '/g/groundhogg/groundhogg.php';
        include_once AWP_APPS . '/h/helpscout/helpscout.php';
        include_once AWP_APPS . '/h/helpwise/helpwise.php';
        include_once AWP_APPS . '/h/highlevel/highlevel.php';
        include_once AWP_APPS . '/h/hubspot/hubspot.php';        
        //include_once AWP_APPS . '/i/influencersoft/influencersoft.php';        
        include_once AWP_APPS . '/i/insightly/insightly.php';
        include_once AWP_APPS . '/i/intercom/intercom.php';
        include_once AWP_APPS . '/j/jetwebinar/jetwebinar.php';        
        include_once AWP_APPS . '/j/jumplead/jumplead.php';
        include_once AWP_APPS . '/k/kajabi/kajabi.php';
        include_once AWP_APPS . '/k/kartra/kartra.php';
        include_once AWP_APPS . '/k/keap/keap.php';        
        include_once AWP_APPS . '/k/klaviyo/klaviyo.php';
        include_once AWP_APPS . '/k/klipfolio/klipfolio.php';
        include_once AWP_APPS . '/l/lemlist/lemlist.php';
        include_once AWP_APPS . '/l/lifterlms/lifterlms.php';        
        include_once AWP_APPS . '/l/liondesk/liondesk.php';
        include_once AWP_APPS . '/l/liveagent/liveagent.php';
        include_once AWP_APPS . '/l/livestorm/livestorm.php';
        include_once AWP_APPS . '/m/mailchimp/mailchimp.php';
        include_once AWP_APPS . '/m/mailercloud/mailercloud.php';
        include_once AWP_APPS . '/m/mailerlite/mailerlite.php';
        include_once AWP_APPS . '/m/mailgun/mailgun.php';        
        include_once AWP_APPS . '/m/mailify/mailify.php';
        include_once AWP_APPS . '/m/mailjet/mailjet.php';
        include_once AWP_APPS . '/m/mailpoet/mailpoet.php';
        include_once AWP_APPS . '/m/messagebird/messagebird.php';
        include_once AWP_APPS . '/m/mojohelpdesk/mojohelpdesk.php';
        include_once AWP_APPS . '/m/monday/monday.php';
        include_once AWP_APPS . '/m/moonmail/moonmail.php';
        include_once AWP_APPS . '/m/moosend/moosend.php';
      //include_once AWP_APPS . '/n/notepm/notepm.php';
      //include_once AWP_APPS . '/n/notion/notion.php';
        include_once AWP_APPS . '/o/onehash/onehash.php';
        include_once AWP_APPS . '/o/omnisend/omnisend.php';
        include_once AWP_APPS . '/o/ontraport/ontraport.php';
        include_once AWP_APPS . '/o/ortto/ortto.php';
        include_once AWP_APPS . '/p/pabbly/pabbly.php';        
        include_once AWP_APPS . '/p/paperform/paperform.php';        
        include_once AWP_APPS . '/p/pipedrive/pipedrive.php';
        include_once AWP_APPS . '/p/productlift/productlift.php';
        include_once AWP_APPS . '/p/postmark/postmark.php';
        include_once AWP_APPS . '/p/pushover/pushover.php';
        include_once AWP_APPS . '/r/readwise/readwise.php';
        include_once AWP_APPS . '/r/revue/revue.php';
        include_once AWP_APPS . '/s/salesmate/salesmate.php';        
        include_once AWP_APPS . '/s/salesflare/salesflare.php';     
        include_once AWP_APPS . '/s/salesforce/salesforce.php';      
        include_once AWP_APPS . '/s/samdock/samdock.php'; 
        include_once AWP_APPS . '/s/sellsy/sellsy.php';        
        include_once AWP_APPS . '/s/selzy/selzy.php';        
        include_once AWP_APPS . '/s/sendfox/sendfox.php';
        include_once AWP_APPS . '/s/sendgrid/sendgrid.php';
        include_once AWP_APPS . '/s/sendinblue/sendinblue.php';
        include_once AWP_APPS . '/s/sendpulse/sendpulse.php';
        include_once AWP_APPS . '/s/sendy/sendy.php';
        include_once AWP_APPS . '/s/simvoly/simvoly.php';
        include_once AWP_APPS . '/s/shopify/shopify.php';
        include_once AWP_APPS . '/s/slack/slack.php';
        include_once AWP_APPS . '/s/smartsheet/smartsheet.php';
        include_once AWP_APPS . '/s/smtp/smtp.php';
        include_once AWP_APPS . '/s/squarespace/squarespace.php';
        include_once AWP_APPS . '/s/surecart/surecart.php';
        include_once AWP_APPS . '/t/teachable/teachable.php';
        include_once AWP_APPS . '/t/teamwork/teamwork.php';
        include_once AWP_APPS . '/t/teamworkcrm/teamworkcrm.php';
        include_once AWP_APPS . '/t/testmonitor/testmonitor.php';
        include_once AWP_APPS . '/t/todoist/todoist.php';
        include_once AWP_APPS . '/t/trello/trello.php';
        include_once AWP_APPS . '/t/trigger/trigger.php';
        include_once AWP_APPS . '/t/twilio/twilio.php';
        include_once AWP_APPS . '/v/validto/validto.php';
        include_once AWP_APPS . '/v/vbout/vbout.php';

        include_once AWP_APPS . '/v/vercel/vercel.php';
        include_once AWP_APPS . '/v/vicodo/vicodo.php';
        include_once AWP_APPS . '/w/wealthbox/wealthbox.php';
        include_once AWP_APPS . '/w/webhookin/webhookin.php';
        include_once AWP_APPS . '/w/webhookout/webhookout.php';
        include_once AWP_APPS . '/w/webinarjam/webinarjam.php';        
        include_once AWP_APPS . '/w/woodpecker/woodpecker.php';
        include_once AWP_APPS . '/w/wufooforms/wufooforms.php';
        include_once AWP_APPS . '/z/zapier/zapier.php';
        include_once AWP_APPS . '/z/zoho/zoho.php';
        include_once AWP_APPS . '/z/zulip/zulip.php';
//------ADD LIST OF WEB FORMS------------------------------------------//
        include_once AWP_WEB_FORMS . '/arforms/arforms.php';
        include_once AWP_WEB_FORMS . '/buddyboss/buddyboss.php';
        include_once AWP_WEB_FORMS . '/calderaforms/calderaforms.php';
        include_once AWP_WEB_FORMS . '/calendly/calendly.php';
        include_once AWP_WEB_FORMS . '/contactform7/contactform7.php';  
        include_once AWP_WEB_FORMS . '/elementorpro/elementorpro.php';
        include_once AWP_WEB_FORMS . '/everestforms/everestforms.php';
        include_once AWP_WEB_FORMS . '/fluentforms/fluentforms.php';
        include_once AWP_WEB_FORMS . '/formcraft/formcraft.php';
        include_once AWP_WEB_FORMS . '/formidable/formidable.php';
        include_once AWP_WEB_FORMS . '/forminator/forminator.php';
        include_once AWP_WEB_FORMS . '/formmaker/formmaker.php';
        include_once AWP_WEB_FORMS . '/jetengineforms/jetengineforms.php';
        include_once AWP_WEB_FORMS . '/jetpack/jetpack.php';
        include_once AWP_WEB_FORMS . '/gravityforms/gravityforms.php';
        include_once AWP_WEB_FORMS . '/happyforms/happyforms.php';
        include_once AWP_WEB_FORMS . '/ninjaforms/ninjaforms.php';
        include_once AWP_WEB_FORMS . '/piotnetformspro/piotnetformspro.php';
        include_once AWP_WEB_FORMS . '/plansoforms/plansoforms.php';
        include_once AWP_WEB_FORMS . '/registrationmagic/registrationmagic.php';

        include_once AWP_WEB_FORMS . '/rssfeeds/rssfeeds.php';
        include_once AWP_WEB_FORMS . '/smartforms/smartforms.php';
        include_once AWP_WEB_FORMS . '/weforms/weforms.php';
        include_once AWP_WEB_FORMS . '/woocommerce/woocommerce.php';        
        include_once AWP_WEB_FORMS . '/wpforms/wpforms.php';   
        include_once AWP_WEB_FORMS . '/wsforms/wsforms.php';                 
        include_once AWP_WEB_FORMS . '/wufooforms/wufooforms.php';                 
        include_once AWP_WEB_FORMS . '/webhook/receiver.php';                 
    }        

    /* Instantiate classes @since 1.0 @return void */
    public function init_classes()
    {  
        if(class_exists('AWP_Admin_Menu')){
            new AWP_Admin_Menu();   // Admin Menu Class
        }
        if(class_exists('AWP_Submission')){

        new AWP_Submission();   // Submission Handler Class
        }
        if(class_exists('AWP_Ajax_Handler')){

        new AWP_Ajax_Handler(); //Ajax Handler Class
        }
    }    
    /* Initializes action hooks @since 1.0 @return  void */
    public function init_actions()
    {   add_action( 'init', array( $this, 'localization_setup' ) );
        add_action( 'init', [ $this, 'setAllCookies' ] );
        add_action( 'init',[ $this, 'process_form_queue_requests']);
        if(is_admin()){
            add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
        }
        add_action( 'admin_post_save_sperse_tenant', array($this,'save_sperse_tenant'));
        add_action( 'wp_footer', [ $this, 'clipboard_script' ]);
        add_action( 'awp_schedule_data_post',[ $this, 'integration_hook_trigger' ],1,3);
        add_action( 'wp_enqueue_scripts', [ $this, 'awp_add_scripts' ] );  
        add_action('init',[ $this, 'awp_change_l_status' ]);
        add_action('admin_footer', array($this, 'awp_deactivate_modal'));

    }

    function awp_deactivate_modal(){
        global $pagenow;
        if ('plugins.php' !== $pagenow) {
            return;
        }
        include_once AWP_VIEWS . '/deactivate_modal.php';
    }


    function awp_exp_admin_notice(){
             echo '<div class="notice notice-warning is-dismissible">
                 <p>Your Automate Hub plugin renewal was unsuccessful. <a href="https://sperse.io/upgrade" >Click here </a> to reactivate and  avoid suspension.</p>
             </div>';
        
    }

    function awp_get_l_status(){
        $url = "https://".AWP_DOMAIN."/scripts/licenseManager/licenseManager.php";
        $license_key = get_option('sperse_license_key');
        $return['success']=false;
        $properties = array('licenseKey'=> $license_key,'setStatus'=>'retrieve');
        $args       = array('headers'   => array('Content-Type' => 'application/json'), 'body' => json_encode($properties));
        $response = wp_remote_post( $url, $args );
        if (is_wp_error($response)){                                     
            return;
        }                                                                
        $license_data = json_decode(wp_remote_retrieve_body($response)); 
        if(($license_data->success == true)){
            $return['success']=true; 
            $return['exp']=strtotime($license_data->data->expiresAt);
        }
        return $return;
    }

    function awp_change_l_status() {  
        if(get_option('awp_l_exp') == 'false'){
            return;
        } 
        $exp =(int)get_option('awp_l_exp');
        $curr_t=(int) time();        

        if(!empty($exp)){
            if( $exp>$curr_t ){
                return;
            }
            else if($curr_t > $exp   && $curr_t < $exp+86400){
                $status=$this->awp_get_l_status();
                if($status['success'] == true){
                    if($status['exp'] > time()){

                        update_option('awp_l_exp',$status['exp']);
                        return;
                    }
                    else{
                        add_action('admin_notices', [ $this, 'awp_exp_admin_notice' ]);   
                        return;     
                    }
                }
                
                
            }            
        }

        $url = "https://".AWP_DOMAIN."/scripts/licenseManager/licenseManager.php";
        $license_key = get_option('sperse_license_key');
        $properties = array('licenseKey'=> $license_key,'setStatus'=>'deactivate');
        $args       = array('headers'   => array('Content-Type' => 'application/json'), 'body' => json_encode($properties));
        $response = wp_remote_post( $url, $args );
        $license_data = json_decode(wp_remote_retrieve_body($response));        // License data.       
            
        update_option('sperse_license_key', '');

    }

    function awp_delay_action_handling($request){
        //check for delay
        $record=$request['record']['data'];
        $settings=json_decode($record,true);        
        if(isset($settings['action_data']['integrationSettings']) && isset($settings['action_data']['integrationSettings']['delaytype']['value']) && isset($settings['action_data']['integrationSettings']['delayval']) ){
            $delaytype=$settings['action_data']['integrationSettings']['delaytype']['value'];
            $delayval=$settings['action_data']['integrationSettings']['delayval'];

            if( !empty($delaytype) && !empty($delayval) ){
                
                //$delayval=strtotime($delayval);  
                switch ($delaytype) {
                    case 'delay_for':

                        if(!empty($delayval)){
                            if( time() < ($request['created_at']+$delayval) ){
               
                                return false;
                            }
                        }
                        break;
                    case 'delay_until':
                    break;
                    default:
                        // code...
                        break;
                }
            }
        }
        return true;
    }
    function process_form_queue_requests(){
        //awp_write_log("queue_value 2: ".get_option('awp_processing_locked'));
        if(empty(get_option('awp_processing_locked',true))){
            update_option('awp_processing_locked',time(),false);
            wp_cache_delete( 'awp_processing_locked', 'options' );
            //awp_write_log("queue_after_lock_val 3: ".get_option('awp_processing_locked'));
            try {
                $requests=awp_get_form_submission_queue();
                //awp_write_log("requests 4: ");
                //awp_write_log($requests);
                $unprocessed_requests=[];
                if(count($requests)){
                    foreach ($requests as $key => $request) {
                        $needs_processing=$this->awp_delay_action_handling($request);
                        //awp_write_log('needs_processing_'.$key);
                        //awp_write_log($needs_processing);
                        if($needs_processing){
                            $callable=$request['callable'];
                            $record=$request['record'];
                            $posted_data=$request['posted_data'];
                  
                            //awp_write_log("function_executed_".$key." 6: ");
                            call_user_func( $callable, $record, $posted_data );
                     
                        }
                        else{
                            array_push($unprocessed_requests, $request);
                        }
                        
                    }
                    
                }
                
            } catch (Exception $e) {
                //incase anything goes wrong while executing unlock the queue
                update_option('awp_processing_locked','');
                //awp_write_log("catch_exception_ 7: ".get_option('awp_processing_locked'));
                
            }
        
            //resetting que
            if(count($unprocessed_requests)){
                $serialzed=serialize($unprocessed_requests);
                update_option('awp_form_submission_queue',$serialzed);
            }
            else{

                update_option('awp_form_submission_queue','',false);
                wp_cache_delete( 'awp_form_submission_queue', 'options' );
                //awp_write_log("submission_queue_reset 8: ");
                //awp_write_log(awp_get_form_submission_queue());
                
            }
            update_option('awp_processing_locked','');
            //awp_write_log("queue_after_unlock_val 9: ".get_option('awp_processing_locked'));

        }
        elseif (time() > get_option('awp_processing_locked') +60 ){
            update_option('awp_processing_locked','');
            //awp_write_log("queue_unlocked_auto_freeze 10: ".get_option('awp_processing_locked'));
        }
  
           
    }
    function awp_add_scripts() {
        wp_enqueue_script('awp-tracking-script',   AWP_ASSETS . '/js/tracking-info.js');
    }
    function integration_hook_trigger($function_name,$record,$posted_data){

        call_user_func( $function_name, $record, $posted_data );
    }

    function save_sperse_tenant(){
        if (! wp_verify_nonce( $_POST['_nonce'], 'awp_sperse_settings' ) ) {
            die( __( 'Security check Failed', 'automate_hub' ) );
        }
        $api_key = '';
        $ins_url = '';
        $url     = $ins_url . "/api/services/Platform/Tenant/CreateTenant";
        $properties = array(
            'adminEmailAddress'  =>$_POST['adminemailaddress'],
            'adminPassword'      =>$_POST['adminpassword'],
            'adminFirstName'     =>$_POST['adminfirstname'],
            'adminLastName'      =>$_POST['adminlastname'],
            'tenancyName'        =>$_POST['tenancyname'],
            'name'               =>$_POST['name'],
            'sendActivationEmail'=>true,
            'shouldChangePasswordOnNextLogin'=>false,
            'editions'=> array("editionId"=> '1',"maxUserCount"=> '5',"trialDayCount"=> '15'),
        );
        $start_time = current_time('mysql',true);
        $args = array('headers' => array('api-key' => $api_key, 'Content-Type' => 'application/json'), 'body' => json_encode($properties));
    }
    
    public function clipboard_script() {
        if( wp_script_is( 'jquery', 'done' ) ) {
        ?>
        <script type="text/javascript">         
          if (typeof ClipboardJS !== 'undefined') {
               new ClipboardJS('.btn');
          }
        </script>
        <?php
        }
    }
    
    public function setAllCookies()
    {   $hostName          = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        $actualUrl         = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";    
        $httpReferer       = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        $refCookieName     = "_referringURL";
        $entryUrl          = "_entryUrl";
        $affiliateCode     = "";
        $affiliateCodeName = 'refAffiliateCode';
        $url               = $actualUrl;
        $urlCopy           = explode("/", $url);
        $urlStatus         = true;
        foreach($urlCopy as $key => $val){
            if($val == "ref"){
                $affiliateCode = $urlCopy[$key+1];
                setcookie($affiliateCodeName, $affiliateCode,     time() + (86400 * 60), "/"); // 86400 = 1 day    
                $urlStatus = true;
                return;
            } else {
                $urlStatus = false;
            }
        }
        if(!$urlStatus) {
            $parts = parse_url($url);
            $p_query = !empty($parts['query']) ? strtolower($parts['query']) : '';
            parse_str($p_query, $query);

            if(isset($query['ref'])) {$affiliateCode = $query['ref'];    
                setcookie($affiliateCodeName, $affiliateCode,    time() + (86400 * 60), "/"); // 86400 = 1 day    
            }
        }


    
    }
    /* Initialize plugin for localization @since 1.0 @uses load_plugin_textdomain() @return void  */
    public function localization_setup()
    {   load_plugin_textdomain( 'automate_hub', false, AWP_FILE . '/languages/' );
    }

    /* Initializes action filters @since 1.0 @return  void */
    public function init_filters() {
        add_filter('awp_integration_fields',array($this,'awp_integration_form_fields'),10,3);
        add_filter('admin_footer_text', array($this,'remove_footer_admin'));
        add_filter( 'update_footer', '__return_empty_string', 11 );
    }
    function remove_footer_admin ($default){
            global $current_screen;
            $sperse_pages_base = array(
                'toplevel_page_automate_hub_dashboard'=>'',
                'automate-hub_page_awp_app_directory'=>'',
                'automate-hub_page_automate_hub'=>'',
                'automate-hub_page_my_integrations'=>'',
                'automate-hub_page_automate_hub-new'=>'#wpfooter{position:absolute}',
                'automate-hub_page_automate_hub_log'=>'',
                'automate-hub_page_automate_add_message_template'=>'',
                'automate-hub_page_automate_message_templates'=>'',
                'automate-hub_page_automate_license'=>'',
            );
        if(!empty($current_screen->base)  && !array_key_exists($current_screen->base, $sperse_pages_base) ){
            //this is not automate hub page
            return $default;
        }
      ?>
        <header>        
            <nav>           
            <div class="container-header">      
                <div class="container-header-inner">
                <p> <?php esc_html_e('© Sperse Inc. All Rights Reserved','automate_hub'); ?><span ><?php echo sprintf('Version %s',esc_html($this->version)); ?></span></p>
                </div>
            </div>
            </nav>    
        </header>
        <?php 
    }
    function awp_integration_form_fields($fields,$form_provider,$form_id){
        if(!empty($fields)){
            $fields['refAffiliateCode'] = esc_html__('RefAffiliateCode','automate_hub');            
        }
        return $fields;
    }
    
    /* Register Script @since 1.0 @return mixed | void */
    public function register_scripts( $hook )
    {   
        if(AWP_Admin_Menu::sp_is_admin_or_embed_page()){
        wp_enqueue_script('jquery');
        wp_enqueue_style ('jquery-ui');
        wp_enqueue_script('jquery-ui');
        wp_enqueue_script('jquery-ui-draggable');
        wp_enqueue_script('jquery-ui-droppable');
        wp_enqueue_script('masonry');
        wp_enqueue_script('awp-vuejs',             AWP_ASSETS . '/js/vue.min.js', array('jquery'), '', 1);
        wp_enqueue_script('awp-vue-selectjs',      AWP_ASSETS . '/js/vue-select.js', array('jquery'), '', 1);
        
        // wp_enqueue_script('awp-dateoucker-script',  'https://unpkg.com/vue@latest' , array('jquery','awp-vuejs'), rand(10,100), 1);
        wp_enqueue_script('awp-dateouckers-script',  'https://unpkg.com/vue-ctk-date-time-picker@2.5.0/dist/vue-ctk-date-time-picker.umd.min.js' , array('jquery','awp-vuejs'), rand(10,100), 1);
         wp_enqueue_style ('awp-vue-selectdateouckers-style',  'https://unpkg.com/vue-ctk-date-time-picker@2.5.0/dist/vue-ctk-date-time-picker.css');
        wp_enqueue_script('awp-main-script',       AWP_ASSETS . '/js/script.js?version='.$this->version , array('awp-vuejs'), rand(10,100), 1);
        wp_enqueue_script('awp-secondary-script',  AWP_ASSETS . '/js/awp_favorites.js' , array('jquery','awp-vuejs'), rand(10,100), 1);
        wp_enqueue_script('awp-sweetalert-script', AWP_ASSETS . '/js/sweetalert2.js' , array('jquery','awp-vuejs'), rand(10,100), 1);
        wp_enqueue_style ('awp-main-style',        AWP_ASSETS . '/css/asset.css', array(),rand(10,100));
        wp_enqueue_style ('awp-responsive-style',  AWP_ASSETS . '/css/responsive.css');
        do_action('awp_custom_script');        
        $localize_scripts = array(
            'nonce'          => wp_create_nonce( 'automate_hub' ),
            'list_url'       => admin_url( 'admin.php?page=my_integrations&status=1' ),
            'message_template_url'       => admin_url( 'admin.php?page=automate_message_templates' ),
            'referrerUrl'=>isset($_COOKIE['_referringURL']) ? sanitize_url($_COOKIE['_referringURL']) : '',
            'entryUrl'=>isset($_COOKIE['_entryUrl']) ? sanitize_url($_COOKIE['_entryUrl']) : '',
            'licenseUrl'=>admin_url( 'admin.php?page=automate_license' ),
            'userAgent'=>isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) :''  
        );
        wp_localize_script('awp-main-script', 'awp', $localize_scripts );
        wp_localize_script('awp-secondary-script', 'awp', $localize_scripts );
        wp_enqueue_style ('awp-vue-select-style',  AWP_ASSETS . '/css/vue-select.css');

         
        
        
        }
    }
}      

if (!function_exists('is_plugin_active')) {
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
}
if ( !is_plugin_active('automate-hub-free-by-sperse-io/automate_hub.php') ) {
    $awp = SPERSE::init();
    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
    $instruction=new AWP_Updates_Manager();
 }
 else{
    if ( is_plugin_active('automate-hub-free-by-sperse-io/automate_hub.php') ) {
        deactivate_plugins('automate-hub-free-by-sperse-io/automate_hub.php');   
    }
 }


//for limited version start...please make sure update url is relevant to the plugin type build

require 'updates/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://sperse.io/scripts/authorization/instructions/metadata_licensed.json',
    __FILE__,
    'awp'
);

//for limited version end

