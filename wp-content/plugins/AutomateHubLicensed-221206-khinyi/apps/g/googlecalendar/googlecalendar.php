<?php
class awp_GoogleCalendar extends AWP_OAuth2 {
    const service_name           = 'googlecalendar';
    const authorization_endpoint = 'https://accounts.google.com/o/oauth2/auth';
    const token_endpoint         = 'https://www.googleapis.com/oauth2/v3/token';
    const google_service_url      = 'https://sperse.io/scripts/authorization/auth.php';
    private static $instance;
    protected $client_id          = '';
    protected $client_secret      = '';
    protected $google_access_code = '';
    protected $calendar_lists     = array();
    public static function get_instance() {
        if ( empty( self::$instance ) ) {
            self::$instance = new self;
        }
        return self::$instance;
    }
    private function __construct() {
        $this->token_endpoint         = self::token_endpoint;
        $this->authorization_endpoint = self::authorization_endpoint;
        $option = (array) maybe_unserialize( get_option( 'awp_googlecalendar_keys' ) );
        if ( isset( $option['client_id'] ) ) {
            $this->client_id = $option['client_id'];
        }
        if ( isset( $option['client_secret'] ) ) {
            $this->client_secret = $option['client_secret'];
        }
        if ( isset( $option['access_token'] ) ) {
            $this->access_token = $option['access_token'];
        }
        if ( isset( $option['refresh_token'] ) ) {
            $this->refresh_token = $option['refresh_token'];
        }
        if ( $this->is_active() ) {
            if ( isset( $option['calendar_lists'] ) ) {
                $this->calendar_lists = $option['calendar_lists'];
            }
        }
        add_action( 'admin_init', array( $this, 'auth_redirect' ) );
        add_filter( 'awp_action_providers', array( $this, 'awp_googlecalendar_actions' ), 10, 1 );
        add_filter( 'awp_settings_tabs', array( $this, 'awp_googlecalendar_settings_tab' ), 10, 1 );
        add_action( 'awp_settings_view', array( $this, 'awp_googlecalendar_settings_view' ), 10, 1 );
        add_action( 'admin_post_awp_save_googlecalendar_keys', array( $this, 'awp_save_googlecalendar_keys' ), 10, 0 );
        add_action( 'awp_action_fields', array( $this, 'action_fields' ), 10, 1 );
        add_action( 'wp_ajax_awp_get_googlecalendar_list', array( $this, 'get_calendar_list' ), 10, 0 );
        add_action( "rest_api_init", array( $this, "create_webhook_route" ) );
        add_action( 'wp_ajax_awp_get_gcalendar_accounts',array( $this, 'awp_get_gcalendar_accounts'), 10, 0 );
        

    }


    public function create_webhook_route() {
        register_rest_route( 'automatehub', '/googlecalendar',
            array(
                'methods'             => 'GET',
                'callback'            => array( $this, 'get_webhook_data' ),
                'permission_callback' => '__return_true'
            )
        );
    }

    public function get_webhook_data( $request ) {
        $params = $request->get_params();
        $code = isset( $params['code'] ) ? trim( $params['code'] ) : '';
        if ( $code ) {
            $redirect_to = add_query_arg(
                [
                    'service' => 'authorize',
                    'action'  => 'awp_googlecalendar_auth_redirect',
                    'code'    => $code,
                ],
                admin_url( 'admin.php?page=automate_hub')
            );
           
            $resp=$this->send_request(self::google_service_url,['access_code'=>$code,'action'=>'preauth'],'');
            $resp=json_decode($resp,true);
            $ags_token=$resp['tokenJson'];
            $google_access_code = sanitize_text_field($code);
            $email_account = $resp['email_account'];
            if(!empty($email_account)){
                $platform_obj= new AWP_Platform_Shell_Table('googleauth');
                $platform_obj->save_platform(['api_key'=>$ags_token,'email'=>sanitize_text_field($email_account),'client_secret'=>sanitize_text_field($google_access_code)]);
            }
            $tab=isset($params['tab'])?$params['tab']:'googlecalendar';
            $page=isset($params['page'])?$params['page']:'automate_hub-new';
            wp_safe_redirect( admin_url( 'admin.php?page='.$page.'&tab='.$tab ) );
            exit();
        }
    }

    public function auth_redirect() {
        $action = isset( $_GET['action'] ) ? sanitize_text_field( trim( $_GET['action'] ) ) : '';
        if ( 'awp_googlecalendar_auth_redirect' == $action ) {
            $code = isset( $_GET['code'] ) ? sanitize_text_field( $_GET['code'] ) : '';
            if ( $code ) {
                $this->request_token( $code );
            }
            wp_safe_redirect( admin_url( 'admin.php?page=automate_hub&tab=googlecalendar' ) );
            exit();
        }
    }

    public function awp_googlecalendar_actions( $actions ) {
        $actions['googlecalendar'] = array(
            'title' => __( 'Google Calendar', 'automate_hub' ),
            'tasks' => array(
                'addEvent'   => __( 'Add New Event', 'automate_hub' )
            )
        );
        return $actions;
    }

    public function awp_googlecalendar_settings_tab( $providers ) {
        $providers['googlecalendar'] = __( 'Google Calendar', 'automate_hub' );
        return $providers;
    }

    public function awp_googlecalendar_settings_view( $current_tab ) {
        if( $current_tab != 'googlecalendar' ) { return; }
        $option        = (array) maybe_unserialize( get_option( 'awp_googlecalendar_keys' ) );

       // delete_option('awp_googlecalendar_keys');

        $option = json_decode( get_option( 'ags_token' ), true );
        $nonce         = wp_create_nonce( "awp_googlecalendar_settings" );
        $client_id     = isset( $option['client_id'] ) ? $option['client_id'] : "";
        $client_secret = isset( $option['client_secret'] ) ? $option['client_secret'] : "";
        $redirect_uri  = $this->get_redirect_uri();
        $domain        = parse_url( get_site_url() );
        $host          = $domain['host'];
        $awp_accesstoken_id = !empty($option['google_access_code']) ? $option['google_access_code'] : '' ;




        ?>
    <div class="no-platformheader">
    <a href="https://sperse.io/go/googlecalendar" target="_blank"><img src="<?php echo AWP_ASSETS; ?>/images/logos/googlecalendar.png" width="165" height="50" alt="Google Calendar Logo"></a><br/><br/>
    <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?>
                <br/>
        <form name="googlecalendar_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" class="container">
            <input type="hidden" name="action" value="awp_save_googlecalendar_keys">
            <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>
            <table class="form-table">
               
                <!-- <tr valign="top">
                    <th scope="row"> <?php esc_html_e( 'Status', 'automate_hub' ); ?></th>
                    <td>
                        <?php 
                        if(!empty($option['access_token'])){
                            // require_once(AWP_LIB.'/google-sheets.php');
                            // $doc = new AWP_googlesheet();
                            // $doc->auth();               
                            // $email_account = $doc->gsheet_print_google_account_email(); 
                            $resp=$this->send_request(self::google_service_url,['access_code'=>$option,'action'=>'auth']);
                            $resp=json_decode($resp,true);
                            update_option( 'ags_token', $resp['tokenJson'] );
                            $email_account = $resp['email_account']; 



                        }
                        
                        if(!empty($option['access_token'])){

                            echo '<p class="connected-account">Connected email account: <b>'.$email_account.'</b></p>';
                        } else{
                                esc_html_e( 'Not Connected', 'automate_hub' );
                        } ?>
                    </td>
                </tr> -->


                <tr valign="top">
                    <td>
                        <a style="cursor:pointer;" id="googleauthbtn" target="popup"><img src="<?php echo AWP_ASSETS.'/images/buttons/btn_google_signin_dark_normal_web.png' ?>">
                            
                            <div class="googletest"></div>
                        </a>
                         <script type="text/javascript">
                            document.getElementById("googleauthbtn").addEventListener("click", function(){
                                var win=window.open('https://accounts.google.com/o/oauth2/auth?access_type=offline&approval_prompt=force&client_id=119614386927-anu0t5adv9726umkv6i257578daqrtpe.apps.googleusercontent.com&redirect_uri=<?php echo self::google_service_url ?>&response_type=code&scope=https%3A%2F%2Fspreadsheets.google.com%2Ffeeds%2F+https://www.googleapis.com/auth/userinfo.email+https://www.googleapis.com/auth/drive.metadata.readonly+https://www.googleapis.com/auth/spreadsheets+https://www.googleapis.com/auth/calendar+https://www.googleapis.com/auth/calendar.events&state=<?php echo $this->get_redirect_uri(); ?>!!page=automate_hub&tab=googlecalendar','popup','width=600,height=600');
                                var id = setInterval(function() {
                                const queryString = win.location.search;
                                const urlParams = new URLSearchParams(queryString);
                                const page_type = urlParams.get('page');
                                if(page_type=='automate_hub'){win.close(); clearInterval(id); location.reload();}
                                }, 1000);
                            });
                        </script>
                    </td>
                </tr>

            </table>
           <!-- <div class="submit-button-plugin"> <?php submit_button( __( 'Save & Authorize', 'automate_hub' ) ); ?> </div> -->
        </form>
        </div>
        <div class="wrap">
            <form id="form-list" method="post">
                        
                
                <input type="hidden" name="page" value="automate_hub"/>

                <?php
                $data=[
                            'table-cols'=>['email'=>'Email Address','spots'=>'Active Spots','active_status'=>'Active']
                    ];
                $platform_obj= new AWP_Platform_Shell_Table('googleauth');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();
                        
                ?>
            </form>
        </div>

        <?php
    }

    public function awp_save_googlecalendar_keys() {
        // Security Check
                    if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }

        if (! wp_verify_nonce( $_POST['_nonce'], 'awp_googlecalendar_settings' ) ) {
            die( __( 'Security check Failed', 'automate_hub' ) );
        }

        if(!empty($_POST['reset']) && ($_POST['reset']=='Deactivate')){
            delete_option('ags_token');
            delete_option('ags_access_code');
            AWP_redirect( "admin.php?page=automate_hub&tab=googlecalendar" );

        }
        if(!empty($_POST['submit']) && !empty($_POST['awp_googlesheets_access_code'])){

            $google_access_code = sanitize_text_field($_POST['awp_googlesheets_access_code']);
            update_option( 'ags_access_code', $google_access_code );

              if ( get_option( 'ags_access_code' ) != '' ) {
                     // include_once( AWP_LIB . '/google-sheets.php');
                     // AWP_googlesheet::preauth( get_option( 'ags_access_code' ),admin_url( 'admin.php?page=automate_hub&tab=googlecalendar' ) );

                     $resp=$this->send_request(self::google_service_url,['access_code'=>get_option( 'ags_access_code' ),'action'=>'preauth']);

                     $resp=json_decode($resp,true);

                     update_option( 'ags_token', $resp['tokenJson'] );
                     wp_safe_redirect('admin.php?page=automate_hub&tab=googlesheets');
                }
        }

        
        /*$client_id     = isset( $_POST['awp_googlecalendar_client_id'] ) ? sanitize_text_field( $_POST['awp_googlecalendar_client_id'] ) : '';
        
        $client_secret = isset( $_POST['awp_googlecalendar_client_secret'] ) ? sanitize_text_field( $_POST['awp_googlecalendar_client_secret'] ) : '';
        if( empty( $client_id ) || empty( $client_secret ) ) {
            $this->reset_data();           
            AWP_redirect( "admin.php?page=automate_hub-settings&tab=googlecalendar" );
            exit;
        }
        $this->client_id     = trim( $client_id );
        $this->client_secret = trim( $client_secret );
        $this->save_data();
        $this->authorize( 'https://www.googleapis.com/auth/calendar' );*/


    }

    protected function authorize( $scope = '' ) {
        $endpoint = add_query_arg(
            array(
                'response_type' => 'code',
                'access_type'   => 'offline',
                'client_id'     => $this->client_id,
                'redirect_uri'  => urlencode( $this->get_redirect_uri() ),
                'scope'         => urlencode( $scope ),
            ),
            $this->authorization_endpoint
        );
        if ( wp_redirect( esc_url_raw( $endpoint ) ) ) {
            exit();
        }
    }

    function awp_get_gcalendar_accounts(){
        if ( ! current_user_can('administrator') ){
            die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
        }
        // Security Check
        if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
            die( esc_html__( 'Security check Failed', 'automate_hub' ) );
        }

        global $wpdb;
        $data = array();
        $add_user_table = $wpdb->prefix.'awp_platform_settings';
        $results = $wpdb->get_results( "SELECT * FROM $add_user_table where platform_name='googlecalendar' or platform_name='googlesheets'", OBJECT );		
        foreach ($results as $key => $value) {
                if( !empty($value->active_status) && ($value->active_status=='true')){
                    $data[$value->id] = $value->email;
                }   
        }
        wp_send_json_success( $data );
    }
    protected function request_token( $authorization_code ) {
        $args = array(
            'headers' => array(),
            'body'    => array(
                'code'          => $authorization_code,
                'client_id'     => $this->client_id,
                'client_secret' => $this->client_secret,
                'redirect_uri'  => $this->get_redirect_uri(),
                'grant_type'    => 'authorization_code',
                'access_type'   => 'offline',
                'prompt'        => 'consent'
            )
        );
        $response      = wp_remote_post( esc_url_raw( $this->token_endpoint ), $args );
        $response_code = (int) wp_remote_retrieve_response_code( $response );
        $response_body = wp_remote_retrieve_body( $response );
        $response_body = json_decode( $response_body, true );
        if ( 401 == $response_code ) { // Unauthorized
            $this->access_token  = null;
            $this->refresh_token = null;
        } else {
            if ( isset( $response_body['access_token'] ) ) {
                $this->access_token = $response_body['access_token'];
            } else {
                $this->access_token = null;
            }

            if ( isset( $response_body['refresh_token'] ) ) {
                $this->refresh_token = $response_body['refresh_token'];
            } else {
                $this->refresh_token = null;
            }
        }
        $this->save_data();
        return $response;
    }

    public function action_fields() {
        ?>
    <script type="text/template" id="googlecalendar-action-template">

                <?php

                    $app_data=array(
                            'app_slug'=>'googlecalendar',
                           'app_name'=>'Google Calendar',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/googlecalendar.png',
                           'app_icon_alter_text'=>'Google Calendar Icon',
                           'account_select_name'=>'spreadsheetId',
                           'account_select_model'=>'fielddata.googleaccountID',
                           'account_select_onchange'=>'getCalendarList',
                           'account_select_vfor'=>'(item, index) in fielddata.gsheetaccounts',
                           'tasks'=>array(
                                        'addEvent'=>array(
                                                            'task_assignments'=>array(

                                                                                    array(
                                                                                        'label'=>'Calendar',
                                                                                        'type'=>'select',
                                                                                        'name'=>"calendarId",
                                                                                        'model'=>'fielddata.calendarId',
                                                                                        'required'=>'required',
                                                                                        'onchange'=>'',
                                                                                        'select_default'=>'Select Calendar...',
                                                                                        'option_for_loop'=>'(item, index) in fielddata.calendarList',
                                                                                        'spinner'=>array(
                                                                                                        'bind-class'=>"{'is-active': listLoading}",
                                                                                                    )
                                                                                    ),
                                                                                   
                                                                                    
                                                                                                                                                                        
                                                                                ),

                                                        ),

                                    ),
                        ); 

                        require (AWP_VIEWS.'/awp_app_integration_format.php');
                ?>
        </script>
        <?php
    }

    protected function save_data() {
        $data = (array) maybe_unserialize( get_option( 'awp_googlecalendar_keys' ) );
        $option = array_merge(
            $data,
            array(
                'client_id'      => $this->client_id,
                'client_secret'  => $this->client_secret,
                'access_token'   => $this->access_token,
                'refresh_token'  => $this->refresh_token,
                'calendar_lists' => $this->calendar_lists
            )
        );
        update_option( 'awp_googlecalendar_keys', maybe_serialize( $option ) );
    }

    protected function reset_data() {
        $this->client_id          = '';
        $this->client_secret      = '';
        $this->google_access_code = '';
        $this->access_token       = '';
        $this->refresh_token      = '';
        $this->calendar_lists     = array();
        $this->save_data();
    }

    protected function get_redirect_uri() {
        return get_rest_url(null,'automatehub/googlecalendar');

    }

    public function get_calendar_list() {
        // Security Check
        if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
            die( __( 'Security check Failed', 'automate_hub' ) );
        }
        $accountid = isset( $_POST['accountid'] ) ? $_POST['accountid'] : "";
            if( !$accountid ) { return; }

        $resp=$this->send_request(self::google_service_url,['action'=>'get_calendar_list'],$accountid);
        $resp=json_decode($resp,true);
        wp_send_json_success( $resp );

        
    }

    function get_account_details($accountid){
        global $wpdb;
        $accountcondition=(empty($accountid)?'':' AND id='.$accountid);
        $data = array();
        $add_user_table = $wpdb->prefix.'awp_platform_settings';
        $results = $wpdb->get_results( "SELECT * FROM $add_user_table where active_status='true'".$accountcondition,ARRAY_A);
        
        return $results;

    }

    function send_request($endpoint,$data,$accountid){

   
        $license_key  = get_option('sperse_license_key');
        $data['licenseKey']=$license_key;
        $data['ags_token']=(empty($accountid)?'':$this->get_account_details($accountid)[0]['api_key']);

        $args = array(
            'method' => 'POST',
            'headers'  => array(
                'Content-type: application/x-www-form-urlencoded'
            ),
            'sslverify' => false,
            'body' => $data,
            'timeout'=>'45'
        );
        $response = wp_remote_post( $endpoint, $args );
        // echo "<pre>";
        // print_r($license_data);
        // echo "</pre>";
        if (is_wp_error($response)){
           echo "Unexpected Error! The query returned with an error.";
        }                                                                       //var_dump($response);//uncomment it if you want to look at the full response
        $license_data = wp_remote_retrieve_body($response); 
        
        return $license_data;
    }
    protected function remote_request( $url, $request = array() ) {
        if( !$this->check_token_expiry( $this->access_token ) ) {
            $this->refresh_token();
        }
        $request = wp_parse_args( $request, array() );
        $request['headers'] = array_merge(
            $request['headers'],
            array(
                'Authorization' => $this->get_http_authorization_header( 'bearer' ),
            )
        );
        $response = wp_remote_request( esc_url_raw( $url ), $request );
        return $response;
    }

    public function check_token_expiry( $token ='' ) {
        $response = array();
        if ( empty( $token ) ) {
            return;
        }
        $return = wp_remote_get('https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=' . $token );
        if( is_wp_error( $return ) ) {
            return false;
        }
        $body = json_decode( $return['body'], true );
        if ( $return['response']['code'] == 200 ) {
            return true;
        }
        return false;
    }

    protected function refresh_token() {
        $args = array(
            'headers' => array(),
            'body'    => array(
                'refresh_token' => $this->refresh_token,
                'client_id'     => $this->client_id,
                'client_secret' => $this->client_secret,
                'grant_type'    => 'refresh_token',
            )
        );
        $response      = wp_remote_post( esc_url_raw( $this->token_endpoint ), $args );
        $response_code = (int) wp_remote_retrieve_response_code( $response );
        $response_body = wp_remote_retrieve_body( $response );
        $response_body = json_decode( $response_body, true );
        if ( 401 == $response_code ) { // Unauthorized
            $this->access_token  = null;
            $this->refresh_token = null;
        } else {
            if ( isset( $response_body['access_token'] ) ) {
                $this->access_token = $response_body['access_token'];
            } else {
                $this->access_token = null;
            }
            if ( isset( $response_body['refresh_token'] ) ) {
                $this->refresh_token = $response_body['refresh_token'];
            }
        }
        $this->save_data();
        return $response;
    }
    public function create_event( $calendar_id, $calendar_data, $record ) {
        if ( !$calendar_id || empty( $calendar_data ) ) {
            return false;
        }

            $record_data = json_decode( $record["data"], true );


        $endpoint = "https://www.googleapis.com/calendar/v3/calendars/{$calendar_id}/events";
        $googleaccountID =  !empty($record_data['field_data']['googleaccountID']) ? $record_data['field_data']['googleaccountID'] : '';
        if( !$googleaccountID ) { return; }

        $resp=$this->send_request(self::google_service_url,['action'=>'create_event','calendar_id'=>$calendar_id,'calendar_data'=>$calendar_data],$googleaccountID);
        
        
        $resp=json_decode($resp,true);
        $return["response"]["code"]=200;
        $return["body"]=json_encode($resp);
        $return["response"]["message"]="Success";
        
        awp_add_to_log( $return, $endpoint, $calendar_data, $record );
        return $return;
    }
}

$googlecalendar = AWP_GoogleCalendar::get_instance();


/* Saves connection mapping */
function awp_googlecalendar_save_integration() {
    $params = array();
    parse_str( awp_sanitize_text_or_array_field( $_POST['formData'] ), $params );
    $trigger_data      = isset( $_POST["triggerData"])? awp_sanitize_text_or_array_field( $_POST["triggerData"]) : array();
    $action_data       = isset( $_POST["actionData" ])? awp_sanitize_text_or_array_field( $_POST["actionData" ]) : array();
    $field_data        = isset( $_POST["fieldData"  ])? awp_sanitize_text_or_array_field( $_POST["fieldData"  ]) : array();
    $integration_title = isset( $trigger_data["integrationTitle" ]) ? $trigger_data["integrationTitle"] : "";
    $form_provider_id  = isset( $trigger_data["formProviderId"   ]) ? $trigger_data["formProviderId"  ] : "";
    $form_id           = isset( $trigger_data["formId"           ]) ? $trigger_data["formId"          ] : "";
    $form_name         = isset( $trigger_data["formName"         ]) ? $trigger_data["formName"        ] : "";
    $action_provider   = isset( $action_data["actionProviderId"  ]) ? $action_data ["actionProviderId"] : "";
    $task              = isset( $action_data["task"] ) ? $action_data["task"] : "";
    $type              = isset( $params["type"] ) ? $params["type"] : "";
    $all_data = array(
        'trigger_data' => $trigger_data,
        'action_data'  => $action_data,
        'field_data'   => $field_data
    );
    global $wpdb;
    $integration_table = $wpdb->prefix . 'awp_integration';
    if ( $type == 'new_integration' ) {
        $result = $wpdb->insert(
            $integration_table,
            array(
                'title'           => $integration_title,
                'form_provider'   => $form_provider_id,
                'form_id'         => $form_id,
                'form_name'       => $form_name,
                'action_provider' => $action_provider,
                'task'            => $task,
                'data'            => json_encode( $all_data, true ),
                'status'          => 1 ));

        if($result){
            $platform_obj= new AWP_Platform_Shell_Table($action_provider);
            $platform_obj->awp_add_new_spot($wpdb->insert_id,$field_data['googleaccountID']);
        }
    }
    if ( $type == 'update_integration' ) {
        $id = esc_sql( trim( $params['edit_id'] ) );
        if ( $type != 'update_integration' &&  !empty( $id ) ) {
            exit;
        }
        $result = $wpdb->update( $integration_table,
            array(
                'title'           => $integration_title,
                'form_provider'   => $form_provider_id,
                'form_id'         => $form_id,
                'form_name'       => $form_name,
                'data'            => json_encode( $all_data, true ),
            ),
            array( 'id' => $id));
    }
    $return=array();
    $return['type']=$type;
    $return['result']=$result;
    $return['insertid']=$wpdb->insert_id;
    return $return;
}

/* Handles sending data to Google Calendar API */
function awp_googlecalendar_send_data( $record, $posted_data ) {
    $record_data = json_decode( $record["data"], true );
    if( array_key_exists( 'cl', $record_data['action_data'] ) ) {
        if( $record_data['action_data']['cl']['active'] == 'yes' ) {
            if( !awp_match_conditional_logic( $record_data['action_data']['cl'], $posted_data ) ) {
                return;
            }
        }
    }
    $data = $record_data['field_data'];
    $task = $record['task'];
    if( $task == 'addEvent' ) {
        $calendar_id   = isset( $data['calendarId'] ) ? $data['calendarId'] : '';
        $all_day_event = isset( $data['allDayEvent'] ) ? $data['allDayEvent'] : '';
        $summary       = empty( $data['title'] ) ? '' : awp_get_parsed_values( $data['title'], $posted_data );
        $description   = empty( $data['description'] ) ? '' : awp_get_parsed_values( $data['description'], $posted_data );
        $start         = empty( $data['start'] ) ? '' : awp_get_parsed_values( $data['start'], $posted_data );
        $end           = empty( $data['end'] ) ? '' : awp_get_parsed_values( $data['end'], $posted_data );
        $timezone      = empty( $data['timezone'] ) ? '1' : awp_get_parsed_values( $data['timezone'], $posted_data );

        $startdatetime = '';
        $enddatetime   = '';
        $calendar_data = array(
            'summary'     => $summary,
            'description' => $description,
            'start'       => array()
        );

      

        if(!empty($start) && !empty($end) && $start != '1' && $end != '1'){
            
            $startdatetime = awp_googlecalendar_get_formatted_datetime( $start, $timezone);
            $enddatetime   = awp_googlecalendar_get_formatted_datetime( $end, $timezone);
                            
        }
        else{
            $date = date('Y-m-d H:i:s');
            $startdatetime = awp_googlecalendar_get_formatted_datetime( $date, $timezone);
            $enddatetime   = awp_googlecalendar_get_formatted_datetime( $date, $timezone);
        }

        $calendar_data['start']['dateTime'] = $startdatetime;
        $calendar_data['end']['dateTime'] = $enddatetime;

        if(!empty($timezone) && $timezone != '1'){
            if( isset( $calendar_data['start'] ) ) {
                $calendar_data['start']['timezone'] = $timezone;
            }
            if( isset( $calendar_data['end'] ) ) {
                $calendar_data['end']['timezone'] = $timezone;
            }
        }

        if ( $calendar_id ) {

            $googlecalendar = AWP_GoogleCalendar::get_instance();
            $resp=$googlecalendar->create_event( $calendar_id, $calendar_data, $record );
            return $resp;
        }
    }
    return;
}



function awp_googlecalendar_resend_data( $log_id,$data,$integration ) {

    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];

    $data=stripslashes($data);
    $data=preg_replace('/\s+/', '',$data); 
    $data=str_replace('"{', '{', $data);
    $data=str_replace('}"', '}', $data);        
    $data=json_decode($data,true);
    $body=$data['args'];
    $url=$data['url'];


    if(!$url){
        $response['success']=false;
        $response['msg']="Syntax Error! Request is invalid";
        return $response;
    }

    $record_data=json_decode($integration['data'],true);
    $googleaccountID =  !empty($record_data['field_data']['googleaccountID']) ? $record_data['field_data']['googleaccountID'] : '';
    if( !$googleaccountID ) { return; }
    $calendar_id=$temp['calendarId'];
    
        $googlecalendar = AWP_GoogleCalendar::get_instance();

        $resp=$googlecalendar->send_request(AWP_GoogleCalendar::google_service_url,['action'=>'create_event','calendar_id'=>$calendar_id,'calendar_data'=>$body],$googleaccountID);

        $resp=json_decode($resp,true);
        $return["response"]["code"]=200;
        $return["body"]=json_encode($resp);
        $return["response"]["message"]="Success";
        awp_add_to_log( $return, $url, $body, $integration );

    $response['success']=true;    
    return $response;
}

function awp_googlecalendar_get_formatted_datetime( $data, $timezone, $format = '' ) {
    if( false === strtotime( $data ) ) {
        return false;
    }

    if( empty( $timezone ) || $timezone == '1') {
        $timezone = wp_timezone();
    } else {
        $timezone = new DateTimeZone( $timezone );
    }

    $dt                 = date_create( $data, $timezone );
    $formatted_datetime = '';
    if( 'Y-m-d' == $format ) {
        $formatted_datetime = date_format( $dt, 'Y-m-d' );
    } else {
        $formatted_datetime = date_format( $dt, DateTime::RFC3339 );
    }
    return $formatted_datetime;
}
