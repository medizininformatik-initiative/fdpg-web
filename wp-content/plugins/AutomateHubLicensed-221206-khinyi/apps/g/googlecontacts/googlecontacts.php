<?php
class awp_googlecontacts extends AWP_OAuth2 {
    const service_name            = 'googlecontacts';
    const authorization_endpoint  = 'https://accounts.google.com/o/oauth2/auth';
    const token_endpoint          = 'https://www.googleapis.com/oauth2/v3/token';
    const google_service_url      = 'https://sperse.io/scripts/authorization/auth.php';

    private static $instance;
    protected $client_id          = '';
    protected $client_secret      = '';
    protected $google_access_code = '';
    protected $sheet_lists   = array();
    public static function get_instance() {
        if ( empty( self::$instance ) ) { self::$instance = new self; }
        return self::$instance;
    }

    private function __construct() {
        $this->token_endpoint         = self::token_endpoint;
        $this->authorization_endpoint = self::authorization_endpoint;
        $option = json_decode( get_option( 'gs_token' ), true );
        if (isset( $option['client_id'      ])) {$this->client_id      = $option['client_id'    ];}
        if (isset( $option['client_secret'  ])) {$this->client_secret  = $option['client_secret'];}
        if (isset( $option['access_token'   ])) {$this->access_token   = $option['access_token' ];}
        if (isset( $option['refresh_token'  ])) {$this->refresh_token  = $option['refresh_token'];}
        if (isset( $option['google_access_code'  ])) {$this->google_access_code  = $option['google_access_code'];}
        if ($this->is_active()) {
            if ( isset( $option['sheet_lists'])) {$this->sheet_lists = $option['sheet_lists'];}
        }
        add_action( 'admin_init'                             , array( $this, 'auth_redirect'                 ));
        add_filter( 'awp_action_providers'                   , array( $this, 'awp_googlecontacts_actions'      ), 10, 1 );
        add_filter( 'awp_settings_tabs'                      , array( $this, 'awp_googlecontacts_settings_tab' ), 10, 1 );
        add_action( 'awp_settings_view'                      , array( $this, 'awp_googlecontacts_settings_view'), 10, 1 );
        add_action( 'admin_post_awp_save_googlecontacts_keys'  , array( $this, 'awp_save_googlecontacts_keys'    ), 10, 0 );
        add_action( 'awp_action_fields'                      , array( $this, 'action_fields'                 ), 10, 1 );
        add_action( 'wp_ajax_awp_get_spreadsheet_list'       , array( $this, 'get_spreadsheet_list'          ), 10, 0 );
        add_action( 'wp_ajax_awp_googlecontacts_get_worksheets', array( $this, 'get_worksheets'                ), 10, 0 );
        add_action( 'wp_ajax_awp_googlecontacts_create_spreadsheet', array( $this, 'create_spreadsheet'                ), 10, 0 );
        add_action( 'wp_ajax_awp_googlecontacts_create_worksheet', array( $this, 'create_worksheet'                ), 10, 0 );
        add_action( 'wp_ajax_awp_googlecontacts_get_headers'   , array( $this, 'get_headers'                   ), 10, 0 );
        add_action( "rest_api_init"                          , array( $this, "create_webhook_route"          ));
        add_action( 'wp_ajax_awp_get_gsheet_accounts'        , array( $this, 'awp_get_gsheet_accounts'       ), 10, 0 );
        add_action( 'wp_ajax_awp_get_gcontacts_accounts',array( $this, 'awp_get_gcontacts_accounts'), 10, 0 );

    }

    public function create_webhook_route() {
        $res=register_rest_route( 'automatehub', '/googlecontacts',
        array('methods' => 'GET', 'callback' => array( $this, 'get_webhook_data'),                 'permission_callback' => function() { return ''; }
));

    }

    public function get_webhook_data( $request ) {
        global $wpdb;
        $params = $request->get_params();
        echo "<pre>";print_r($params);echo "</pre>";

        $code = isset( $params['code'] ) ? trim( $params['code'] ) : '';
        if ( $code ) {
            $redirect_to = add_query_arg(
                [   'service' => 'authorize',
                    'action'  => 'awp_googlecontacts_auth_redirect',
                    'code'    => $code, ],
                admin_url( 'admin.php?page=automate_hub')
            );

            $resp=$this->send_request(self::google_service_url,['access_code'=>$code,'action'=>'preauth'],'');
            $resp=json_decode($resp,true);
            echo "<pre>";print_r($resp);echo "</pre>";
            die();
            $ags_token=$resp['tokenJson'];
            $google_access_code = sanitize_text_field($code);
            $email_account = $resp['email_account']; 
            if(!empty($email_account)){
                $platform_obj= new AWP_Platform_Shell_Table('googleauth');
                $platform_obj->save_platform(['api_key'=>$ags_token,'email'=>sanitize_text_field($email_account),'client_secret'=>sanitize_text_field($google_access_code)]);    
            }
            
            $tab=isset($params['tab'])?$params['tab']:'googlecontacts';
            $page=isset($params['page'])?$params['page']:'automate_hub-new';
            wp_safe_redirect( admin_url( 'admin.php?page='.$page.'&tab='.$tab ) );
            exit();
        }
    }

    public function auth_redirect() {
        $action = isset( $_GET['action'] ) ? sanitize_text_field( trim( $_GET['action'] ) ) : '';
        if ( 'awp_googlecontacts_auth_redirect' == $action ) {
            $code = isset( $_GET['code'] ) ? sanitize_text_field( $_GET['code'] ) : '';
            if ( $code ) { $this->request_token( $code ); }
            wp_safe_redirect( admin_url( 'admin.php?page=automate_hub&tab=googlecontacts' ) );
            exit();
        }
    }

    public function awp_googlecontacts_actions( $actions ) {
        $actions['googlecontacts'] = array(
            'title' => esc_html__( 'Google Contacts', 'automate_hub'),
            'tasks' => array( 'add_contact'   => esc_html__( 'Add New Contact', 'automate_hub'))
        );
        return $actions;
    }

    public function awp_googlecontacts_settings_tab( $providers ) {
        $providers['googlecontacts'] = 
        array('name'=>esc_html__( 'Google Contacts', 'automate_hub'), 'cat'=>array('app')
    );
        return $providers;
    }

    public function awp_googlecontacts_settings_view( $current_tab ) {
        if( $current_tab != 'googlecontacts' ) { return; }
        $option        = (array) maybe_unserialize( get_option( 'awp_googlecontacts_keys' ) );
        $option = json_decode( get_option( 'ags_token' ), true );
        $nonce         = wp_create_nonce( "awp_googlecontacts_settings" );
        $redirect_uri  = $this->get_redirect_uri();
        $domain        = parse_url( get_site_url() );
        $host          = $domain['host'];
        $awp_accesstoken_id = !empty($option['google_access_code']) ? $option['google_access_code'] : '' ;
        ?>
    <div class="no-platformheader">
    <a href="https://sperse.io/go/googlecontacts" target="_blank"><img src="<?php echo esc_url(AWP_ASSETS.'/images/logos/googlecontacts.png'); ?>" width="286" height="50" alt="Google Sheets Logo"></a><br/><br/>
    <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager(sanitize_text_field($_GET['tab']));
                    $instruction_obj->prepare_instructions();
                        
                ?>
                <br/>
     <form name="googlecontacts_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" class="container">
        <input type="hidden" name="action" value="awp_save_googlecontacts_keys">
        <input type="hidden" name="_nonce" value="<?php echo wp_create_nonce( "awp_googlecontacts_settings" ); ?>"/>
        <table class="form-table">
            <!-- <tr valign="top">
                <th scope="row"> <?php esc_html_e( 'Status', 'automate_hub' ); ?></th>
                <td>
                    <?php 
                    if(!empty($option['access_token'])){
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
                        var win=window.open('https://accounts.google.com/o/oauth2/auth?access_type=offline&approval_prompt=force&client_id=119614386927-anu0t5adv9726umkv6i257578daqrtpe.apps.googleusercontent.com&redirect_uri=<?php echo self::google_service_url ?>&response_type=code&scope=https%3A%2F%2Fspreadsheets.google.com%2Ffeeds%2F+https://www.googleapis.com/auth/userinfo.email+https://www.googleapis.com/auth/drive.metadata.readonly+https://www.googleapis.com/auth/spreadsheets+https://www.googleapis.com/auth/calendar+https://www.googleapis.com/auth/calendar.events+https://www.google.com/m8/feeds/&state=<?php echo $this->get_redirect_uri(); ?>!!page=automate_hub&tab=googlecontacts','popup','width=600,height=600');
                        var id = setInterval(function() {
                        const queryString = win.location.search;
                        const urlParams = new URLSearchParams(queryString);
                        const page_type = urlParams.get('page');
                        //if(page_type=='automate_hub'){win.close(); clearInterval(id); location.reload();}
                        }, 1000);
                    });
                    </script>
                </td>
            </tr>
        </table>
    </form>
    </div>
    <div class="wrap">
        <form id="form-list" method="post">
            <input type="hidden" name="page" value="automate_hub"/>
            <?php
            $data=['table-cols'=>['email'=>'Email Address','spots'=>'Active Spots','active_status'=>'Active']];
            $platform_obj= new AWP_Platform_Shell_Table('googleauth');
            $platform_obj->initiate_table($data);
            $platform_obj->prepare_items();
            $platform_obj->display_table();                    
            ?>
        </form>
    </div>
    <?php
    }

    public function awp_save_googlecontacts_keys() {
        if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }
        // Security Check
        if (! wp_verify_nonce( $_POST['_nonce'], 'awp_googlecontacts_settings' ) ) {
            die( esc_html__( 'Security check Failed', 'automate_hub' ) );
        }
        if(!empty($_POST['reset']) && (sanitize_text_field($_POST['reset'])=='Deactivate')){
            delete_option('ags_token');
            delete_option('ags_access_code');
            AWP_redirect( "admin.php?page=automate_hub&tab=googlecontacts" );
        }
        if(!empty($_POST['submit']) && !empty($_POST['awp_googlecontacts_access_code'])){

            $google_access_code = sanitize_text_field($_POST['awp_googlecontacts_access_code']);
            update_option( 'ags_access_code', $google_access_code );
                if ( get_option( 'ags_access_code' ) != '' ) {
                     // include_once( AWP_LIB . '/google-sheets.php');
                     // AWP_googlesheet::preauth( get_option( 'ags_access_code' ),admin_url( 'admin.php?page=automate_hub&tab=googlecontacts' ) );
                     $resp=$this->send_request(self::google_service_url,['access_code'=>get_option( 'ags_access_code' ),'action'=>'preauth'],'');
                     $resp=json_decode($resp,true);
                     update_option( 'ags_token', $resp['tokenJson'] );
                     wp_safe_redirect('admin.php?page=automate_hub&tab=googlecontacts');
                }
        }
        //echo "<pre>";print_r($_POST);echo "</pre>";
    }
    function get_account_details($accountid){
        global $wpdb;
        $accountcondition=(empty($accountid)?'':' AND id='.$accountid);
        $data = array();
        $add_user_table = $wpdb->prefix.'awp_platform_settings';
        if(!empty($accountid)){

            $query = $wpdb->prepare("SELECT * FROM {$add_user_table} where active_status='true' AND id=%s", $accountid);

        }else{
            $query = $wpdb->prepare("SELECT * FROM {$add_user_table} where active_status='true'");
        }

        $results = $wpdb->get_results( $query,ARRAY_A);
        
        return $results;

    }
    function send_request($endpoint,$data,$accountid){
        $license_key  = get_option('sperse_license_key');
        $data['licenseKey']=$license_key;
        $data['ags_token']=(empty($accountid)?'':$this->get_account_details($accountid)[0]['api_key']);
        $args = array(
            'method' => 'POST',
            'headers'  => array('Content-type: application/x-www-form-urlencoded'),
            'sslverify' => false,
            'body' => $data,
            'timeout'=>'45'
        );

        $response = wp_remote_post( $endpoint, $args );
        
        if (is_wp_error($response)){
           echo "Unexpected Error! The query returned with an error.";
        }                                                                       //var_dump($response);//uncomment it if you want to look at the full response
        $response = wp_remote_retrieve_body($response); 
        return $response;
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
        if ( wp_redirect( esc_url_raw($endpoint ))) { exit(); }
    }

    function awp_get_gsheet_accounts() {
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
        $results = $wpdb->get_results($wpdb->prepare( "SELECT * FROM {$add_user_table} where platform_name=%s",'googlecontacts'), OBJECT );
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
            if ( isset( $response_body['access_token' ])) {$this->access_token  = $response_body['access_token' ];} else {$this->access_token  = null;}
            if ( isset( $response_body['refresh_token'])) {$this->refresh_token = $response_body['refresh_token'];} else {$this->refresh_token = null;}
        }
        $this->save_data();
        return $response;
    }

    function awp_get_gcontacts_accounts(){
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
        $results = $wpdb->get_results( "SELECT * FROM $add_user_table where platform_name='googlecontacts'", OBJECT );		
        foreach ($results as $key => $value) {
                if( !empty($value->active_status) && ($value->active_status=='true')){
                    $data[$value->id] = $value->email;
                }   
        }
        wp_send_json_success( $data );
    }

    public function action_fields() { ?>
    <script type="text/template" id="googlecontacts-action-template">
		        <?php

                    $app_data=array(
                            'app_slug'=>'googlecontacts',
                           'app_name'=>'Google Contacts',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/googlecontacts.png',
                           'app_icon_alter_text'=>'Google Contact Icon',
                           'account_select_name'=>'spreadsheetId',
                           'account_select_model'=>'fielddata.googleaccountID',
                           'account_select_vfor'=>'(item, index) in fielddata.gsheetaccounts',
                           'tasks'=>array(
                                        'add_row'=>array(


                                                            'task_assignments'=>array(
                                                                                      
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
        $data = (array) maybe_unserialize( get_option( 'awp_googlecontacts_keys' ) );
        $option = array_merge($data,array(
                'google_access_code'     => $this->google_access_code,
                'client_secret' => $this->client_secret,
                'access_token'  => $this->access_token,
                'refresh_token' => $this->refresh_token,
                'sheet_lists'   => $this->sheet_lists));
        update_option( 'awp_googlecontacts_keys', maybe_serialize( $option ) );
    }

    protected function reset_data() {
        $this->client_id          = '';
        $this->client_secret      = '';
        $this->google_access_code = '';
        $this->access_token       = '';
        $this->refresh_token      = '';
        $this->sheet_lists        = array();
        $this->save_data();
    }

    protected function get_redirect_uri() {
        return get_rest_url(null,'automatehub/googlecontacts');
    }

 
    protected function remote_request ( $url, $request = [] ) {
    }

    public function check_token_expiry( $token ='' ) {
        $response = array();
        if (empty($token)) { return; }
        $return = wp_remote_get('https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=' . $token );
        if (is_wp_error( $return)) {return false;}
        $body = json_decode( $return['body'], true );
        if ($return['response']['code'] == 200 ) {return true; }
        return false;
    }

    protected function refresh_token() {
        $args = array(
            'headers' => array(),
            'body'    => array(
                'refresh_token' => $this->refresh_token,
                'client_id'     => $this->client_id,
                'client_secret' => $this->client_secret,
                'grant_type'    => 'refresh_token',));
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


}

$googlecontacts = awp_googlecontacts::get_instance();

/* Saves connection mapping */
function awp_googlecontacts_save_integration() {
    $params = array();
    parse_str( awp_sanitize_text_or_array_field( $_POST['formData'] ), $params );
    $trigger_data      = isset( $_POST["triggerData"])? awp_sanitize_text_or_array_field( $_POST["triggerData"]) : array();
    $action_data       = isset( $_POST["actionData" ])? awp_sanitize_text_or_array_field( $_POST["actionData" ]) : array();
    $field_data        = isset( $_POST["fieldData"  ])? awp_sanitize_text_or_array_field( $_POST["fieldData"  ]) : array();
    
    $integration_title = isset( $trigger_data["integrationTitle" ]) ? sanitize_text_field($trigger_data["integrationTitle"]) : "";
    $form_provider_id  = isset( $trigger_data["formProviderId"   ]) ? sanitize_text_field($trigger_data["formProviderId"  ]) : "";
    $form_id           = isset( $trigger_data["formId"           ]) ? sanitize_text_field($trigger_data["formId"          ]) : "";
    $form_name         = isset( $trigger_data["formName"         ]) ? sanitize_text_field($trigger_data["formName"        ]) : "";
    $action_provider   = isset( $action_data["actionProviderId"  ]) ? sanitize_text_field($action_data ["actionProviderId"]) : "";

    $task              = isset( $action_data["task"] ) ? sanitize_text_field($action_data["task"]) : "";
    $type              = isset( $params["type"] ) ? sanitize_text_field($params["type"]) : "";
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
        $id = isset( $params['edit_id']) ?    trim( sanitize_text_field( $params['edit_id'] ) ):'';
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

/* Handles sending data to Google Sheets */
function awp_googlecontacts_send_data( $record, $posted_data ) {

    $data    = isset($record["data"]) ? json_decode( $record["data"], true ) : array();
    $data    = isset($data["field_data"]) ? $data["field_data"] :array() ;

    
    $accountid      = isset($data["googleaccountID"]) ? sanitize_text_field( $data["googleaccountID"]) :''  ;
    $task           = isset($record["task"]) ? sanitize_text_field($record["task"]) :''  ;
    $record_data    = isset($record["data"]) ? json_decode( $record["data"], true ) : array();
    
    $emailAddress      = empty( $data["email"] ) ? "" : awp_get_parsed_values( $data["email"], $posted_data );
    $first_name = empty( $data["firstName"] ) ? "" : awp_get_parsed_values( $data["firstName"], $posted_data );
    $last_name = empty( $data["lastName"] ) ? "" : awp_get_parsed_values( $data["lastName"], $posted_data );
    $phone  = empty( $data["phoneNumber"] ) ? "" : awp_get_parsed_values( $data["phoneNumber"], $posted_data );
   
    $address1  = empty( $data["address1"] ) ? "" : awp_get_parsed_values( $data["address1"], $posted_data );
    $address2  = empty( $data["address2"] ) ? "" : awp_get_parsed_values( $data["address2"], $posted_data );
    $stateName  = empty( $data["stateName"] ) ? "" : awp_get_parsed_values( $data["stateName"], $posted_data );
    $country  = empty( $data["country"] ) ? "" : awp_get_parsed_values( $data["country"], $posted_data );
    $zipCode  = empty( $data["zipCode"] ) ? "" : awp_get_parsed_values( $data["zipCode"], $posted_data );

    if(!empty($record_data["action_data"]) && array_key_exists( "cl", $record_data["action_data"]) ) {
        if( !empty($record_data["action_data"]["cl"]["active"]) && ($record_data["action_data"]["cl"]["active"] == "yes") ) {
            $chk = awp_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data );
            if( !awp_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data ) ) {
                return;
            }
        }
    }
    if( $task == "add_contact" ) {


        $contact_data = array(
            'names' => array(
                array(
                    'givenName' => $first_name,
                    'familyName' => $last_name,
                    'displayName' =>$first_name.' '.$last_name,
                    'metadata' => array(
                        'primary' => true
                    )
                )
            ),
            'emailAddresses' => array(
                array(
                    'value' => $emailAddress,
                    'type' => 'home',
                    'metadata' => array(
                        'primary' => true
                    )
                )
                
            ),
            'phoneNumbers' => array(
                array(
                    'value' => $phone,
                    'type' => 'home',
                    'metadata' => array(
                        'primary' => true
                    )
                )
                
            ),
            'addresses' => array(
                array(
                    'streetAddress' => $address1,
                    'extendedAddress' => $address2,
                    'region' => $stateName,
                    'postalCode' => $zipCode,
                    'country' => $country,
                    'type' => 'home'
                )
            ),
        );        
        // Convert the data array to JSON
        $contact_data = json_encode($contact_data);
        $send_data=[
            'action'=>'contact_send_data',
            'contact_data'=>$contact_data,
        ];

        $obj=awp_googlecontacts::get_instance();
        $resp=$obj->send_request($obj::google_service_url,$send_data,$accountid);
        $resp=json_decode($resp,true);
        $return["response"]["code"]=200;
        $return["body"]=json_encode($resp);
        $return["response"]["message"]="Success";
        awp_add_to_log( $return, $obj::google_service_url, $data, $record );
        return $return;
    }
    return;
}







function awp_googlecontacts_resend_data( $log_id,$data,$integration ) {
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
            $data=[
                'action'=>'contact_send_data',
                'contact_data'=>$body['contact_data'],
            ];
            $obj=awp_googlecontacts::get_instance();
            $resp=$obj->send_request($obj::google_service_url,$data,$googleaccountID);
            $resp=json_decode($resp,true);
            $return["response"]["code"]=200;
            $return["body"]=json_encode($resp);
            $return["response"]["message"]="Success";
            awp_add_to_log( $return, $url, $body, $integration );

    $response['success']=true;    
    return $response;
}
