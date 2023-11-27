<?php
class awp_Aweber extends AWP_OAuth2 {
    const service_name           = 'aweber';
    const authorization_endpoint = 'https://auth.aweber.com/oauth2/authorize';
    const token_endpoint         = 'https://auth.aweber.com/oauth2/token';
    const refresh_token_endpoint = 'https://auth.aweber.com/oauth2/token';
    private static $instance;
    protected      $contact_lists = array();
    protected      $refresh_token_endpoint = '';
    public static function get_instance() {
        if ( empty( self::$instance ) ) {
            self::$instance = new self;
        }
        return self::$instance;
    }


    public function prepare_obj($objectid=false){
        $this->construct($objectid);
    }

    private function construct($objectid) {

        $this->authorization_endpoint = self::authorization_endpoint;
        $this->token_endpoint         = self::token_endpoint;
        $this->refresh_token_endpoint = self::refresh_token_endpoint;
        $this->client_id='36RiGq9w0MFlRYLF1NRCiFK0Bx3QIunF';
        if($objectid){
            $option=$this->get_obj_by_id($objectid);
        }
        
             
        if ( isset( $option['account_name'] ) ) { $this->account_name = $option['account_name'];}
        if ( isset( $option['client_secret'] ) ) { $this->client_secret = $option['client_secret'];}
        if ( isset( $option['access_token'] ) ) { $this->access_token = $option['access_token']; }
        if ( isset( $option['refresh_token'] ) ) { $this->refresh_token = $option['refresh_token']; }
        if ( $this->is_active() ) {
            if ( isset( $option['contact_lists'] ) ) {
                $this->contact_lists = $option['contact_lists'];
            }
        }
        add_action( 'admin_init', array( $this, 'auth_redirect' ) );
        add_filter( 'awp_action_providers', array( $this, 'awp_aweber_actions' ), 10, 1 );
        add_filter( 'awp_settings_tabs', array( $this, 'awp_aweber_settings_tab' ), 10, 1 );
        add_action( 'awp_settings_view', array( $this, 'awp_aweber_settings_view' ), 10, 1 );
        add_action( 'admin_post_awp_save_aweber_keys', array( $this, 'awp_save_aweber_keys' ), 10, 0 );
        add_action( 'awp_action_fields', array( $this, 'action_fields' ), 10, 1 );
        add_action( 'wp_ajax_awp_get_aweber_accounts', array( $this, 'get_aweber_accounts' ), 10, 0 );
        add_action( 'wp_ajax_awp_get_aweber_lists', array( $this, 'get_aweber_lists' ), 10, 0 );
        add_action( "rest_api_init", array( $this, "create_webhook_route" ) );
        add_filter( 'awp_platform_connections', array( $this, "awp_aweber_platform_connection" ), 10, 1 );
    }

    public function create_webhook_route() {
        register_rest_route( 'automatehub', '/aweber', array('methods'  => 'GET', 'callback' => array( $this, 'get_webhook_data'),                 'permission_callback' => function() { return ''; }
        ));
        register_rest_route( 'automatehub', '/aweber-authorize', array('methods'  => 'GET', 'callback' => array( $this, 'authorize_url'),                 'permission_callback' => function() { return ''; }
        ));
    }

    public function authorize_url(){
        $scopes = array(
            "account.read",
            "list.read",
            "list.write",
            "subscriber.read",
            "subscriber.write",
        );
        $this->authorize( $scopes );
    }
    public function get_webhook_data( $request ) {
        $params = $request->get_params();
        $code = isset( $params['code'] ) ? trim( $params['code'] ) : '';
        if ( $code ) {
            $redirect_to = add_query_arg(
                [   'service' => 'authorize',
                    'action'  => 'awp_aweber_auth_redirect',
                    'code'    => $code, ],
                admin_url( 'admin.php?page=automate_hub')
            );
            wp_safe_redirect( $redirect_to );
            exit();
        }
    }

    function authorize($scope = ''){
         // Generate the code challenge using the OS / cryptographic random function
        $verifierBytes = random_bytes(64);
        $codeVerifier = rtrim(strtr(base64_encode($verifierBytes), "+/", "-_"), "=");

        // Very important, "raw_output" must be set to true or the challenge
        // will not match the verifier.
        $challengeBytes = hash("sha256", $codeVerifier, true);
        $codeChallenge = rtrim(strtr(base64_encode($challengeBytes), "+/", "-_"), "=");
        update_option("awp_aweber_keys_holder",$codeVerifier);
        // State token, a uuid is fine here
        $state = uniqid();
        $endpoint = add_query_arg(
            array(
                'response_type' => 'code',
                'client_id'     => $this->client_id,
                'redirect_uri'  => urlencode( $this->get_redirect_uri() ),
                "scope" => implode(" ",$scope),
                "state" => $state,
                "code_challenge" => $codeChallenge,
                "code_challenge_method" => "S256"
            ),
            $this->authorization_endpoint
        );

        if ( wp_redirect( esc_url_raw( $endpoint ) ) ) {
            exit();
        }
    }

    public function awp_aweber_actions( $actions ) {
        $actions['aweber'] = array(
            'title' => esc_html__( 'Aweber', 'automate_hub'),
            'tasks' => array( 'subscribe'   => esc_html__( 'Subscribe To List', 'automate_hub' )
            )
        );
        return $actions;
    }

    public function awp_aweber_settings_tab( $providers ) {
        $providers['aweber'] = array('name'=>esc_html__( 'Aweber', 'automate_hub'), 'cat'=>array('esp'));
        return $providers;
    }

    public function awp_aweber_settings_view( $current_tab ) {
        if( $current_tab != 'aweber' ) { return; }
        $option       = (array) maybe_unserialize( get_option( 'awp_aweber_keys' ) );
        $nonce        = wp_create_nonce( "awp_aweber_settings" );

        $api_key   = !empty($_GET['api_key']) ? sanitize_text_field($_GET['api_key']) :'';
        $redirect_uri = $this->get_redirect_uri();
        $display_name     = isset($_GET['account_name']) ?sanitize_text_field( $_GET['account_name']) : "";

        ?>

    <div class="platformheader">
    <a href="https://sperse.io/go/aweber" target="_blank"><img src="<?php echo esc_url(AWP_ASSETS.'/images/logos/aweber.png'); ?>" width="210" height="50" alt="Aweber Logo"></a><br/><br/>
    <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager(sanitize_text_field($_GET['tab']));
                    $instruction_obj->prepare_instructions();
                        
                ?>
   
    <?php 
                if(!isset($_GET['id'])){
                ?>
            
                            <a onclick="aweberauthbtn()" id="aweberauthbtn" target="_blank" class="button button-primary"> <?php echo esc_html__(' Sign in with AWeber','automate_hub');?></a>
                            <script type="text/javascript">
                                function aweberauthbtn(){
                                    var win=window.open('<?php echo esc_url($this->get_permission_url()); ?>','popup','width=600,height=600');
                                        var id = setInterval(function() {
                                        const queryString = win.location.search;
                                        const urlParams = new URLSearchParams(queryString);
                                        const page_type = urlParams.get('page');
                                        if(page_type=='automate_hub'){win.close(); clearInterval(id); location.reload();}
                                        }, 1000);
                                }
                            
                        </script>           
                    

                <?php
                }
    

$form_fields = '';
$app_name= 'aweber';
$sperse_form = new AWP_Form_Fields($app_name);

$form_fields = $sperse_form->awp_wp_text_input(
    array(
        'id'            => "awp_aweber_display_name",
        'name'          => "awp_aweber_display_name",
        'value'         => $display_name,
        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $sperse_form->awp_wp_text_input(
    array(
        'id'            => "awp_aweber_api_key",
        'name'          => "awp_aweber_api_key",
        'value'         => $api_key,
        'placeholder'   => esc_html__( 'Enter Your AWeber Verification Code', 'automate_hub' ),
        'label'         =>  esc_html__( 'AWeber Verification code', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $sperse_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_save_aweber_keys',
    )
);


$form_fields .= $sperse_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>wp_create_nonce('awp_aweber_settings'),
    )
);




$sperse_form->render($form_fields);

?>

    </div>

    <div class="wrap">
        <form id="form-list" method="post">
                    
            
            <input type="hidden" name="page" value="automate_hub"/>

            <?php
            $data=[
                        'table-cols'=>['account_name'=>'Display Name','spots'=>'Active Spots','active_status'=>'Active']
                ];
            $platform_obj= new AWP_Platform_Shell_Table('aweber');
            $platform_obj->initiate_table($data);
            $platform_obj->prepare_items();
            $platform_obj->display_table();
                    
            ?>
        </form>
    </div>

    <?php
    }

    public function awp_save_aweber_keys() {

        if ( ! current_user_can('administrator') ){
            die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
        }

        // Security Check
        if (! wp_verify_nonce( $_POST['_nonce'], 'awp_aweber_settings' ) ) {
            die( esc_html__( 'Security check Failed', 'automate_hub' ) );
        }

        $api_key    = isset( $_POST["awp_aweber_api_key"] ) ? sanitize_text_field( $_POST["awp_aweber_api_key"] ) : "";
        $this->account_name    = isset($_POST["awp_aweber_display_name"]) ?  sanitize_text_field( $_POST["awp_aweber_display_name"] ) :'';
        if(empty($this->account_name) || empty($api_key)){
                //invalid token
                AWP_redirect( "admin.php?page=automate_hub&tab=aweber" );
                die();
        }

        if(!empty($api_key)){
            $tokenQuery=array(
                'code'         => $api_key,
                'grant_type'   => 'authorization_code',
                "code_verifier" => get_option('awp_aweber_keys_holder'),
                "client_id" => $this->client_id,
            );


            $tokenUrl = $this->token_endpoint . "?" . http_build_query($tokenQuery);
            $response      = wp_remote_post( $tokenUrl );           
            // Save the credentials to the credentials.ini file
            $body = !empty( $response['body']) ? $response['body'] :'';
            $creds =!empty($body) ? json_decode($body, true):'';
            $this->access_token = isset($creds['access_token'])?$creds['access_token']:'';
            $this->refresh_token = isset($creds['refresh_token'])?$creds['refresh_token']:'';
            
            if(empty($this->access_token)){
                AWP_redirect( "admin.php?page=automate_hub&tab=aweber" );
                die();
            }
            update_option("awp_aweber_keys_holder","");
        }
        
        $this->save_data();
        

        AWP_redirect( "admin.php?page=automate_hub&tab=aweber" );
    }

    public function action_fields() {
        ?>
        <script type="text/template" id="aweber-action-template">

        <?php

                $app_data=array(
                    'app_slug'=>'aweber',
                   'app_name'=>'Aweber',
                   'app_icon_url'=>AWP_ASSETS.'/images/icons/aweber.png',
                   'app_icon_alter_text'=>'Aweber Icon',
                   'account_select_onchange'=>'getAweberAccounts',
                   'tasks'=>array(
                                'subscribe'=>array(
                                                    'task_assignments'=>array(

                                                                            array(
                                                                                'label'=>'Aweber Account',
                                                                                'type'=>'select',
                                                                                'name'=>"account_id",
                                                                                'model'=>'fielddata.accountId',
                                                                                'required' => 'required',
                                                                                'onchange' => 'getLists',
                                                                                'option_for_loop'=>'(item, index) in fielddata.accounts',
                                                                                'select_default'=>'Select Account...',
                                                                                'spinner'=>array(
                                                                                                'bind-class'=>"{'is-active': accountLoading}",
                                                                                            )
                                                                            ),
                                                                            array(
                                                                                'label'=>'Aweber List',
                                                                                'type'=>'select',
                                                                                'name'=>"list_id",
                                                                                'model'=>'fielddata.listId',
                                                                                'required' => 'required',
                                                                                
                                                                                'option_for_loop'=>'(item, index) in fielddata.lists',
                                                                                'select_default'=>'Select List...',
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

    public function auth_redirect() {

        $auth   = isset( $_GET['auth'] ) ? trim(sanitize_text_field( $_GET['auth'] )) : '';
        $code   = isset( $_GET['code'] ) ? trim( sanitize_text_field($_GET['code'] )) : '';
        $action = isset( $_GET['action'] ) ? trim( sanitize_text_field($_GET['action'] )) : '';

        if ( 'awp_aweber_auth_redirect' == $action ) {
            $code = isset( $_GET['code'] ) ? sanitize_text_field($_GET['code']) : '';

            if ( $code ) {
                $this->request_token( $code );
            }

            if ( ! empty( $this->access_token ) ) {
                $message = 'success';
            } else {
                $message = 'failed';
            }

            wp_safe_redirect( admin_url( 'admin.php?page=automate_hub&tab=aweber' ) );

            exit();
        }
    }

    protected function get_obj_by_id($id) {
        $platform_obj= new AWP_Platform_Shell_Table('aweber');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        $data=[
            'client_id'=>$data->client_id,
            'client_secret'=>$data->client_secret,
            'access_token'=>$data->api_key,
            'refresh_token'=>$data->email,
            'account_name'=>$data->account_name,
        ];
        return $data;
     }
    protected function save_data_multiple($data) {
        $mapping=[
            'client_id'=>$data['client_id'],
            'client_secret'=>$data['client_secret'],
            'api_key'=>$data['access_token'],
            'email'=>$data['refresh_token'],
            'account_name'=>$data['account_name'],
            
        ];

        $platform_obj= new AWP_Platform_Shell_Table('aweber');
        $platform_obj->save_platform($mapping);
    }
    protected function save_data() {
        if(!empty(get_option('awp_aweber_keys_holder'))){
            return;
        }

        $option =  array(
                'client_id'     => $this->client_id,
                'client_secret' => $this->client_secret,
                'access_token'  => $this->access_token,
                'refresh_token' => $this->refresh_token,
                'account_name' => $this->account_name,
                
        );

        $this->save_data_multiple($option);
    }

    protected function reset_data() {

        $this->client_id     = '';
        $this->client_secret = '';
        $this->access_token  = '';
        $this->refresh_token = '';
        $this->contact_lists = [ ];

        $this->save_data();
    }

    protected function get_redirect_uri() {
        //return site_url( '/wp-json/automatehub/aweber' );
        return "urn:ietf:wg:oauth:2.0:oob";
    }

    protected function get_permission_url(){
        return get_rest_url(null,'automatehub/aweber-authorize');
    }

    public function create_contact( $properties, $account_id, $list_id,$endpoint=false ) {
        if(!$endpoint){
            $endpoint = "https://api.aweber.com/1.0/accounts/{$account_id}/lists/{$list_id}/subscribers";
        }
        

        $request = [
            'method'  => 'POST',
            'headers' => [
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json; charset=utf-8',
            ],
            'body'    => json_encode( $properties ),
        ];

        $response = $this->remote_request( $endpoint, $request );

        return $response;
    }

    public function get_aweber_accounts() {

        if (!isset( $_POST['platformid'] ) ) {
            die( esc_html__( 'Invalid Request', 'automate_hub' ) );
        }


        $id=sanitize_text_field($_POST['platformid']);
        $platform_obj= new AWP_Platform_Shell_Table('activecampaign');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $this->access_token=$data->api_key;
        $endpoint = 'https://api.aweber.com/1.0/accounts';

        $request = array(
            'method'  => 'GET',
            'headers' => array(
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
            )
        );


        $response = $this->remote_request( $endpoint, $request );

        if ( 400 <= (int) wp_remote_retrieve_response_code( $response ) ) {
            wp_send_json_error();
        }

        $response_body = wp_remote_retrieve_body( $response );

        if ( empty( $response_body ) ) {
            wp_send_json_error();
        }

        $response_body = json_decode( $response_body, true );

        if ( !empty( $response_body['entries'] ) ) {
            $accounts = wp_list_pluck( $response_body['entries'], 'id', 'id' );

            wp_send_json_success( $accounts );
        } else {
            wp_send_json_error();
        }
    }

    public function get_aweber_lists() {
        if (!isset( $_POST['platformid'] ) ) {
            die( esc_html__( 'Invalid Request', 'automate_hub' ) );
        }

        $account_id = isset( $_POST['accountId'] ) ? sanitize_text_field($_POST['accountId']) : '';

        if( !$account_id ) {
            wp_send_json_error();
        }
        $id=sanitize_text_field($_POST['platformid']);
        $platform_obj= new AWP_Platform_Shell_Table('activecampaign');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $this->access_token=$data->api_key;

        $endpoint = "https://api.aweber.com/1.0/accounts/{$account_id}/lists";

        $request = array(
            'method'  => 'GET',
            'headers' => array(
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
            )
        );

        $response = $this->remote_request( $endpoint, $request );

        if ( 400 <= (int) wp_remote_retrieve_response_code( $response ) ) {
            wp_send_json_error();
        }

        $response_body = wp_remote_retrieve_body( $response );

        if ( empty( $response_body ) ) {
            wp_send_json_error();
        }

        $response_body = json_decode( $response_body, true );

        if ( !empty( $response_body['entries'] ) ) {
            $lists = wp_list_pluck( $response_body['entries'], 'name', 'id' );

            wp_send_json_success( $lists );
        } else {
            wp_send_json_error();
        }
    }
}

$aweber = awp_Aweber::get_instance();
$aweber->prepare_obj();
/*
 * Saves connection mapping
 */
function awp_aweber_save_integration() {
    $params = array();
    parse_str( awp_sanitize_text_or_array_field( $_POST['formData'] ), $params );

    $trigger_data = isset( $_POST["triggerData"] ) ? awp_sanitize_text_or_array_field( $_POST["triggerData"] ) : array();
    $action_data  = isset( $_POST["actionData"] ) ? awp_sanitize_text_or_array_field( $_POST["actionData"] ) : array();
    $field_data   = isset( $_POST["fieldData"] ) ? awp_sanitize_text_or_array_field( $_POST["fieldData"] ) : array();

    $integration_title = isset( $trigger_data["integrationTitle"] ) ? sanitize_text_field($trigger_data["integrationTitle"]) : "";
    $form_provider_id  = isset( $trigger_data["formProviderId"] ) ? sanitize_text_field($trigger_data["formProviderId"]) : "";
    $form_id           = isset( $trigger_data["formId"] ) ? sanitize_text_field($trigger_data["formId"]) : "";
    $form_name         = isset( $trigger_data["formName"] ) ? sanitize_text_field($trigger_data["formName"]) : "";
    $action_provider   = isset( $action_data["actionProviderId"] ) ? sanitize_text_field( $action_data["actionProviderId"]) : "";
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
                'status'          => 1
            )
        );
        if($result){
            $platform_obj= new AWP_Platform_Shell_Table($action_provider);
            $activePlatformId= isset($field_data['activePlatformId']) ? sanitize_text_field($field_data['activePlatformId']) :'';
            $platform_obj->awp_add_new_spot($wpdb->insert_id,$activePlatformId);
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
            array(
                'id' => $id
            )
        );
    }

    $return=array();
    $return['type']=$type;
    $return['result']=$result;
    $return['insertid']=$wpdb->insert_id;
    return $return;
}

/*
 * Handles sending data to Aweber API
 */
function awp_aweber_send_data( $record, $posted_data ) {

    $data       = !empty($record["data"]) ?   json_decode( $record["data"], true ):array();
    $data       = !empty($data["field_data"]) ? $data["field_data"]:array()  ;
    $account_id = !empty($data["accountId"]) ? sanitize_text_field($data["accountId"]) :''  ;
    $list_id    = !empty($data["listId"]) ? sanitize_text_field($data["listId"]) :'';
    $task       = !empty($record["task"]) ? sanitize_text_field($record["task"]) :'' ;

    $record_data = !empty($record["data"]) ? json_decode( $record["data"], true ) :'';
    
    if(!empty($record_data["action_data"]) &&  array_key_exists( "cl", $record_data["action_data"]) ) {
        if( !empty($record_data["action_data"]["cl"]["active"]) && $record_data["action_data"]["cl"]["active"] == "yes" ) {
            $chk = awp_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data );
            if( !awp_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data ) ) {
                return;
            }
        }
    }

    if( $task == "subscribe" ) {
        $email      = empty( $data["email"] ) ? "" : awp_get_parsed_values( $data["email"], $posted_data );
        $first_name = empty( $data["firstName"] ) ? "" : awp_get_parsed_values($data["firstName"], $posted_data);
        $last_name  = empty( $data["lastName"] ) ? "" : awp_get_parsed_values($data["lastName"], $posted_data);

        // PRo
        $name          = empty( $data["name"] ) ? "" : awp_get_parsed_values( $data["name"], $posted_data );
        $ip_address    = empty( $data["ipAddress"] ) ? "" : awp_get_parsed_values( $data["ipAddress"], $posted_data );
        $ad_tracking   = empty( $data["adTracking"] ) ? "" : awp_get_parsed_values( $data["adTracking"], $posted_data );
        $misc_notes    = empty( $data["miscNotes"] ) ? "" : awp_get_parsed_values( $data["miscNotes"], $posted_data );
        $tags          = empty( $data["tags"] ) ? "" : awp_get_parsed_values( $data["tags"], $posted_data );
        $custom_fields = empty( $data["customFields"] ) ? "" : awp_get_parsed_values( $data["customFields"], $posted_data );


        // $properties = array(
        //     "email"  => $email,
        //     "name"   => $first_name . " " . $last_name
        // );
        //Pro
        $properties = array(
            "email"  => $email,
            "name"   => $first_name . " " . $last_name,
            "ip_address"  => $ip_address,
            "ad_tracking" => $ad_tracking,
            "misc_notes"  => $misc_notes
        );

        // Pro        
        if( $tags ) {
            $properties["tags"] = explode( ",", $tags );
        }

        if( $custom_fields ) {
            $holder = explode( ",", $custom_fields );

            foreach( $holder as $single ) {
                $single = explode( "=", $single );

                $properties["custom_fields"][$single[0]] = $single[1];
            }
        }

        $temp    =  !empty($record["data"]) ? json_decode( $record["data"], true ) :'';
        $temp    =  !empty($temp["field_data"]) ? $temp["field_data"] :'' ;
        $aweber = awp_Aweber::get_instance();
        $aweber->prepare_obj($temp['activePlatformId']);    
        $endpoint = "https://api.aweber.com/1.0/accounts/{$account_id}/lists/{$list_id}/subscribers";  
        $return = $aweber->create_contact( $properties, $account_id, $list_id );
        awp_add_to_log( $return, $endpoint, $properties, $record );
    }
    return $return;
}





function awp_aweber_resend_data( $log_id,$data,$record) {

    $data=stripslashes($data);
    $data=preg_replace('/\s+/', '',$data); 
    $data=json_decode($data,true);
    $url=$data['url'];
    if(!$url){
            $response['success']=false;
            $response['msg']="Syntax Error! Request is invalid";
            return $response;
    }

    $account_id = isset($data["accountId"]) ? $data["accountId"] :'' ;
    $list_id    = isset($data["listId"]) ? $data["listId"]:'' ;
    $task       = isset($record["task"]) ? $record["task"]:'' ;

    $record_data = json_decode( $record["data"], true );


    if( $task == "subscribe" ) {

        $temp    = json_decode( $record["data"], true );
        $temp    = $temp["field_data"];
        $aweber = awp_Aweber::get_instance();
        $aweber->prepare_obj($temp['activePlatformId']);        
        

        $return = $aweber->create_contact( $data['args'], '', '',$url);
        awp_add_to_log( $return, $url, $data['args'] , $record );
    }
    $response['success']=true;
    return $response;
}
