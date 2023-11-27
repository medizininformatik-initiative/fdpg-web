<?php
class awp_Salesforce extends AWP_OAuth2 {
    
    const service_name           = 'Salesforce';
    const authorization_endpoint = 'https://login.salesforce.com/services/oauth2/authorize';
    const token_endpoint         = 'https://login.salesforce.com/services/oauth2/token';
    const refresh_token_endpoint = 'https://login.salesforce.com/services/oauth2/token';
    const salesforce_service_url = 'https://sperse.io/scripts/authorization/auth.php';


    private static $instance;
    protected      $contact_lists = array();
    protected      $refresh_token_endpoint = '';
    public static function get_instance() {
        if ( empty( self::$instance ) ) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    protected function authorize( $scope = '' ) {

        $endpoint = add_query_arg(
            array(
                'response_type' => 'code',
                'client_id'     => $this->client_id,
                'redirect_uri'  => urlencode( $this->get_redirect_uri() ),
                'scope'         => $scope,
            ),
            $this->authorization_endpoint
        );

        if ( wp_redirect( esc_url_raw( $endpoint ) ) ) {
            exit();
        }
    }

    private function __construct() {
        $this->authorization_endpoint = self::authorization_endpoint;
        $this->token_endpoint         = self::token_endpoint;
        $this->refresh_token_endpoint = self::refresh_token_endpoint;
        add_action( 'admin_init',              array( $this, 'auth_redirect' ) );
        add_filter( 'awp_action_providers', array( $this, 'awp_salesforce_actions' ), 10, 1 );
        add_filter( 'awp_settings_tabs',    array( $this, 'awp_salesforce_settings_tab' ), 10, 1 );
        add_action( 'awp_settings_view',    array( $this, 'awp_salesforce_settings_view' ), 10, 1 );
        add_action( 'awp_action_fields', array( $this, 'action_fields' ), 10, 1 );
        add_action( 'wp_ajax_awp_get_salesforce_accounts', array( $this, 'get_salesforce_accounts' ), 10, 0 );
        add_action( 'wp_ajax_awp_get_salesforce_lists', array( $this, 'get_salesforce_lists' ), 10, 0 );
        add_action( "rest_api_init", array( $this, "create_webhook_route" ) );


    }

    public function auth_redirect(){
        
    }
    public function create_webhook_route() {
        register_rest_route( 'automatehub', '/salesforce',
            array('methods'  => 'GET', 'callback' => array( $this, 'get_webhook_data'),                 'permission_callback' => function() { return ''; }
)
        );
    }

    public function get_webhook_data( $request ) {
        $params = $request->get_params();
        $code = isset( $params['code'] ) ? trim( $params['code'] ) : '';
        $state = isset( $params['state'] ) ? trim( $params['state'] ) : '';
        if ( $code ) {

            $client_accessToken = $params['access_token'];
            $client_refreshToken = $params['refresh_token'];
            $instance_url = $params['original_response']['instance_url'];


            if (isset($client_accessToken) && isset($client_refreshToken)) {

                $request = [
                        'headers' => [
                            'Authorization' => sprintf( 'Bearer %s', $client_accessToken ),
                        ],
                    ];
                    $user_info_endpoint = 'https://login.salesforce.com/services/oauth2/userinfo';
                    
                    $user_response      = wp_remote_get( esc_url_raw( $user_info_endpoint ), $request );
                    $user_response_body = wp_remote_retrieve_body( $user_response );
                    $user_response_body = json_decode( $user_response_body, true );
                if(!empty($user_response_body['email'])){
                    $platform_obj= new AWP_Platform_Shell_Table('salesforce');
                     $platform_obj->save_platform(['api_key'=>$client_accessToken,'account_name'=>$user_response_body['name'],  'url'=>$instance_url,'client_secret'=>sanitize_text_field($client_refreshToken),'email'=>sanitize_text_field($user_response_body['email'])]);    
                }else{

                    global $wpdb;
                    $query_salesforce_db = "select * from " . $wpdb->prefix . "awp_platform_settings where platform_name='salesforce'";
                    $data = $wpdb->get_results($query_salesforce_db);
                    $len = count($data) + 1;
                    $platform_obj = new AWP_Platform_Shell_Table('salesforce');
                    $platform_obj->save_platform(['account_name' => 'Account Number ' . $len, 'api_key' => $client_accessToken, 'client_secret' => $client_refreshToken,'url'=>$instance_url]);
                }
            }
        }

        wp_safe_redirect( admin_url( 'admin.php?page=automate_hub&tab=salesforce' ) );
        exit();
    }

    public function awp_salesforce_actions( $actions ) {
        $actions['salesforce'] = array(
            'title' => esc_html__( 'Salesforce', 'automate_hub'),
            'tasks' => array('create_contact'   => esc_html__( 'Create Contact', 'automate_hub'))
        );
        return $actions;
    }

public function awp_salesforce_settings_tab( $providers ) {
    $providers['salesforce'] =  array('name'=>esc_html__( 'Salesforce', 'automate_hub'), 'cat'=>array('crm')
    );
    return $providers;
}

public function awp_salesforce_settings_view( $current_tab ) {
    if( $current_tab != 'salesforce' ) {
        return;
    }

    $nonce        = wp_create_nonce( "awp_salesforce_settings" );
    $api_key      = !empty($_GET['client_id']) ? $_GET['client_id'] : '';
    $api_secret   = !empty($_GET['client_secret']) ? $_GET['client_secret'] : '' ;
    $email   = !empty($_GET['email']) ? $_GET['email'] : '' ;
    $password   = !empty($_GET['account_name']) ? $_GET['account_name'] : '' ;
    $redirect_uri = $this->get_redirect_uri();
    ?>
    <div class="platformheader">
    <a href="https://sperse.io/go/salesforce" target="_blank"><img src="<?php echo AWP_ASSETS; ?>/images/logos/salesforce.png" width="71" height="50" alt="Salesforce Logo"></a><br/><br/>
    <div id="introbox">
        <div style="float:right;clear: both;">
            <img data-appname="Salesforce" id="videobtn" src="https://automatehubultimate.faizanjaved.com/wp-content/plugins/AutomateHub-Ultimate/assets/images/videobutton.png" style="height: 120px;width: 240px;">
        </div>
        <?php 
       // include_once (AWP_INCLUDES.'/awp_app_videos.php');
        ?>
        See the instructions below to setup your Mailjet integration: <br/> 
        1. If you don't have a Salesforce developer account, <a href="https://www.salesforce.com/apps" target="_blank">click here to create a new account</a>.<br/>
        2. Copy Redirect URI from below and paste in \'OAuth Redirect URL\' field. For further details <a href="https://help.sperse.io/?page=salesforce" target="_blank">click here</a>. <br/>
        3. Copy Client ID and Client Secret from newly created app and save here.<br/>
        4. Once you've configured your settings, <?php printf( '%s <a href="%s">%s</a>', esc_html__( 'click here to setup a ', 'automate_hub'), admin_url( 'admin.php?page=automate_hub-new'), esc_html__( 'New Form Integration', 'automate_hub')); ?> <br/>        
    </div><br/>        
    <form name="salesforce_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" class="container">
    <input type="hidden" name="action" value="awp_save_salesforce_keys">
    <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>
    <table class="form-table">
        <tr valign="top">
                <td>
                    <a style="cursor:pointer;" id="salesforceauthbtn" target="popup" class="button button-primary">Login with Salesforce</a>
                    <?php $state = urldecode('http://localhost/sperse/wp-admin/admin.php?page=automate_hub&tab=salesforce&cat=favorites'); ?>
                    <script type="text/javascript">
                        document.getElementById("salesforceauthbtn").addEventListener("click", function(){
                        // Access Token generate by owner id not showing.   
                        //var win=window.open('https://login.salesforce.com/services/oauth2/authorize?response_type=code&state=<?php echo $state; ?>&client_id=3MVG9n_HvETGhr3BN8Ln45C0LFdqFtSypLNVZ.eKeyL3jPqb6jTodxgloE0ZhXXZje2cWETebkyPgSN_Fz3Tr&redirect_uri=<?php echo $redirect_uri; ?>&scope=<?php echo urlencode('api refresh_token'); ?>','popup','width=600,height=600');
                        // Pro
                        var win=window.open('https://login.salesforce.com/services/oauth2/authorize?response_type=code&client_id=3MVG9n_HvETGhr3DJt5ywkTFewX4A0o.y3sH5NuOz5gvfN6eGKrMVNDospv6AJVDWVXnE7fvkzAESIrE0Qjyt&redirect_uri=<?php echo self::salesforce_service_url; ?>&scope=<?php echo urlencode('api refresh_token'); ?>&state=<?php echo $this->get_redirect_uri(); ?>!!page=automate_hub&tab=salesforce','popup','width=600,height=600');
                        
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
    </form>
    </div>
    <div class="wrap">
        <form id="form-list" method="post">
            <input type="hidden" name="page" value="automate_hub"/>
            <?php
            $data=[
                'table-cols'=>['email'=>'E-mail','spots'=>'Active Spots','active_status'=>'Active']
                ];
            $platform_obj= new AWP_Platform_Shell_Table('salesforce');
            $platform_obj->initiate_table($data);
            $platform_obj->prepare_items();
            $platform_obj->display_table();          
            ?>
        </form>
    </div>
    <?php
    }



    public function action_fields() {
        ?>
        <script type="text/template" id="salesforce-action-template">
            
        <?php

            $app_data=array(
                'app_slug'=>'salesforce',
                'app_name'=>'Salesforce',
                'app_icon_url'=>AWP_ASSETS.'/images/icons/salesforce.png',
                'app_icon_alter_text'=>'Salesforce Icon',
                'tasks'=>array(
                                'create_contact'=>array(
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
  



    protected function get_redirect_uri() {
        return get_rest_url(null,'automatehub/salesforce');
    }

    public function create_contact( $properties, $accountid, $list_id ) {
        $api_version='v37.0';
        $endpoint = "https://ap1.salesforce.com/services/data/v24.0/sobjects/Contact";

        $account_details =(empty($accountid)?'':$this->get_account_details($accountid));
        $access_token = !empty($account_details['0']['api_key']) ? $account_details['0']['api_key'] :'';
        $refresh_access_token = !empty($account_details['0']['client_secret']) ? $account_details['0']['client_secret'] :'';
        $instance_url = !empty($account_details['0']['url']) ? $account_details['0']['url'] :'';
        if( !$access_token ) { return; }

        $request = [
            'method'  => 'POST',
            'headers' => [
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json; charset=utf-8',
                'Authorization'=>'Bearer '.$access_token,

            ],
            'body'    => json_encode( $properties ),
        ];
        $response = wp_remote_request( $endpoint, $request );

        if ( (400 <= (int) wp_remote_retrieve_response_code( $response )) ) {
            $new_accessToken= $this->generate_refresh_token( $refresh_access_token, $access_token,$accountid);
            if (isset($new_accessToken)) {
                $request['headers']['Authorization'] = 'Bearer ' . $new_accessToken;
                $response = wp_remote_request($endpoint, $request);
            }else{
                wp_send_json_error();
            }
           
        }

        return $response;
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

    function generate_refresh_token($refresh_token, $old_accessToken,$accountid ){
        $license_key  = get_option('sperse_license_key');
        $data['licenseKey']=$license_key;
        $data['refresh_token']=$refresh_token;
        $data['action']='salesforce_refresh';
        $args = array(
            'method' => 'POST',
            'headers'  => array('Content-type: application/x-www-form-urlencoded'),
            'sslverify' => false,
            'body' => $data,
            'timeout'=>'45'
        );
    
        $returned = wp_remote_post('https://sperse.io/scripts/authorization/auth.php', $args );
            
        if (is_wp_error($returned)){
            echo "Unexpected Error! The query returned with an error.";
        }
        $decoded_data = json_decode($returned['body']);
        $new_accessToken = $decoded_data->access_token;
        $accountid = (int) $accountid;
        if (isset($new_accessToken)) {
            global $wpdb;
            $res = $wpdb->update($wpdb->prefix . 'awp_platform_settings', ['api_key' => $new_accessToken], ['id' => $accountid]);
        }

         return $new_accessToken;       


    }
    


}

$salesforce = awp_Salesforce::get_instance();

/*
 * Saves connection mapping
 */
function awp_salesforce_save_integration1() {
    $params = array();
    parse_str( awp_sanitize_text_or_array_field( $_POST['formData'] ), $params );

    $trigger_data = isset( $_POST["triggerData"] ) ? awp_sanitize_text_or_array_field( $_POST["triggerData"] ) : array();
    $action_data  = isset( $_POST["actionData"] ) ? awp_sanitize_text_or_array_field( $_POST["actionData"] ) : array();
    $field_data   = isset( $_POST["fieldData"] ) ? awp_sanitize_text_or_array_field( $_POST["fieldData"] ) : array();

    $integration_title = isset( $trigger_data["integrationTitle"] ) ? $trigger_data["integrationTitle"] : "";
    $form_provider_id  = isset( $trigger_data["formProviderId"] ) ? $trigger_data["formProviderId"] : "";
    $form_id           = isset( $trigger_data["formId"] ) ? $trigger_data["formId"] : "";
    $form_name         = isset( $trigger_data["formName"] ) ? $trigger_data["formName"] : "";
    $action_provider   = isset( $action_data["actionProviderId"] ) ? $action_data["actionProviderId"] : "";
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
                'status'          => 1
            )
        );
        if($result){
            $platform_obj= new AWP_Platform_Shell_Table($action_provider);
            $platform_obj->awp_add_new_spot($wpdb->insert_id,$field_data['activePlatformId']);
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
 * Saves connection mapping
 */
function awp_salesforce_save_integration() {
    $params = array();
    parse_str( awp_sanitize_text_or_array_field( $_POST['formData'] ), $params );

    $trigger_data = isset( $_POST["triggerData"] ) ? awp_sanitize_text_or_array_field( $_POST["triggerData"] ) : array();
    $action_data  = isset( $_POST["actionData"] ) ? awp_sanitize_text_or_array_field( $_POST["actionData"] ) : array();
    $field_data   = isset( $_POST["fieldData"] ) ? awp_sanitize_text_or_array_field( $_POST["fieldData"] ) : array();

    $integration_title = isset( $trigger_data["integrationTitle"] ) ? $trigger_data["integrationTitle"] : "";
    $form_provider_id  = isset( $trigger_data["formProviderId"] ) ? $trigger_data["formProviderId"] : "";
    $form_id           = isset( $trigger_data["formId"] ) ? $trigger_data["formId"] : "";
    $form_name         = isset( $trigger_data["formName"] ) ? $trigger_data["formName"] : "";
    $action_provider   = isset( $action_data["actionProviderId"] ) ? $action_data["actionProviderId"] : "";
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
                'status'          => 1
            )
        );
        if($result){
            $platform_obj= new AWP_Platform_Shell_Table($action_provider);
            $platform_obj->awp_add_new_spot($wpdb->insert_id,$field_data['activePlatformId']);
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
 * Handles sending data to Salesforce API
 */
function awp_salesforce_send_data( $record, $posted_data ) {

    $data       = json_decode( $record["data"], true );

    $data       = $data["field_data"];
    $account_id = $data["activePlatformId"];
    $list_id    = $data["listId"];
    $task       = $record["task"];
    $record_data = json_decode( $record["data"], true );
    if( array_key_exists( "cl", $record_data["action_data"]) ) {
        if( $record_data["action_data"]["cl"]["active"] == "yes" ) {
            $chk = awp_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data );
            if( !awp_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data ) ) {
                return;
            }
        }
    }

    if( $task == "create_contact" ) {
        $email      = empty( $data["email"]     ) ? "" : awp_get_parsed_values( $data["email"], $posted_data );
        $first_name = empty( $data["firstName"] ) ? "" : awp_get_parsed_values($data["firstName"], $posted_data);
        $last_name  = empty( $data["lastName"]  ) ? "" : awp_get_parsed_values($data["lastName"], $posted_data);
        $phone  = empty( $data["phone"]  ) ? "" : awp_get_parsed_values($data["phone"], $posted_data);
        $properties = array(
            "email"  => $email,
            "name"   => $first_name . " " . $last_name
        );

        $properties = array(
            'FirstName'=>$first_name,
            "LastName"   =>  $last_name,
            "Phone"=>$phone,
            "Email"=>$email 
        );


        $salesforce = awp_Salesforce::get_instance();
        $return = $salesforce->create_contact( $properties, $account_id, $list_id );
        awp_add_to_log( $return, '', $properties, $record );
    }
    return;
}
