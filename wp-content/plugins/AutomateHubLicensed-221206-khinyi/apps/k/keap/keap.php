<?php
class AWP_keap extends AWP_OAuth2 {
    const service_name           = 'keap';
    const authorization_endpoint = 'https://accounts.infusionsoft.com/app/oauth/authorize';
    const token_endpoint         = 'https://api.infusionsoft.com/token';
    const refresh_token_endpoint = 'https://api.infusionsoft.com/token';
    const sperse_service_url      = 'https://sperse.io/scripts/authorization/auth.php';
    const client_id = '9lZ1v2cVpN7grU1WTbSMG1DMcJ8HNfjy';
    private static $instance;    
    //protected $contact_lists = array();

    public static function get_instance() {
        if ( empty( self::$instance ) ) {self::$instance = new self;}
        return self::$instance;
    }

    public function prepare_obj($objectid=false){
        $this->construct($objectid);
    }
    private function construct($objectid) {
        $option=array();
        $this->authorization_endpoint = self::authorization_endpoint;
        $this->token_endpoint         = self::token_endpoint;
        $this->refresh_token_endpoint = self::refresh_token_endpoint;
        $this->client_id=self::client_id;
        
        if($objectid){
            $option=$this->get_obj_by_id($objectid);
        }

        
        //$this->reset_data();
        // echo "<pre>";
        // print_r($option);
        // echo "</pre>";
        // die();
        if ( isset( $option['platformid'    ])) { $this->platformid     = $option['platformid'    ];}
        if ( isset( $option['client_id'    ])) { $this->client_id     = $option['client_id'    ];}
        if ( isset( $option['account_name'])) { $this->account_name = $option['account_name'];}
        if ( isset( $option['access_token' ])) { $this->access_token  = $option['access_token' ];}
        if ( isset( $option['refresh_token'])) { $this->refresh_token = $option['refresh_token'];}
    
        add_action( 'admin_init'                   , array( $this, 'auth_redirect'          ));
        add_filter( 'awp_action_providers'         , array( $this, 'awp_keap_actions'       ), 10, 1 );
        add_filter( 'awp_settings_tabs'            , array( $this, 'awp_keap_settings_tab'  ), 10, 1 );
        add_action( 'awp_settings_view'            , array( $this, 'awp_keap_settings_view' ), 10, 1 );
        add_action( 'admin_post_awp_save_keap_keys', array( $this, 'awp_save_keap_keys'     ), 10, 0 );
        add_action( 'awp_action_fields'            , array( $this, 'action_fields'          ), 10, 1 );
        add_action( 'wp_ajax_awp_get_keap_list'    , array( $this, 'get_keap_list'          ), 10, 0 );
        add_action( "rest_api_init"                , array( $this, "create_webhook_route"   ));


    }


    public function create_webhook_route() {
        register_rest_route( 'automatehub', '/keap',
            array('methods' => 'GET', 'callback' => array( $this, 'get_webhook_data' ), 'permission_callback' => '__return_true'));
    }

    public function get_webhook_data( $request ) {
        $params = $request->get_params();

        if ( isset( $params['access_token'] ) ) {
            $this->access_token = $params['access_token'];
        } else {
            $this->access_token = null;
        }
        if ( isset( $params['refresh_token'] ) ) {
            $this->refresh_token = $params['refresh_token'];
        } else {
            $this->refresh_token = null;
        }
        if ( isset( $params['api_domain'] ) ) {
            $this->api_domain = $params['api_domain'];
        } else {
            $this->api_domain = null;
        }
        $this->save_data();
        wp_safe_redirect( admin_url( 'admin.php?page=automate_hub&tab=keap' ) );
        exit();
    }

    public function awp_keap_actions( $actions ) {
        $actions['keap'] = array('title' => esc_html__( 'Keap', 'auomate_hub' ),  'tasks' => array('add_contact'  => esc_html__( 'Add Contact', 'automate_hub')));
        return $actions;
    }

    public function awp_keap_settings_tab( $providers ) {
        $providers['keap'] = esc_html__( 'Keap', 'automate_hub' );
        return $providers;
    }

    public function awp_keap_settings_view( $current_tab ) {
    if( $current_tab != 'keap' ) { return; }
    $option        = (array) maybe_unserialize( get_option( 'awp_keap_keys' ) );
    $nonce         = wp_create_nonce( 'awp_keap_settings' );
    $id = isset($_GET['id']) ? $_GET['id'] : "";
    $redirect_uri  = $this->get_redirect_uri();
    $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";
    ?>
    <div class="platformheader">
    <a href="https://sperse.io/go/keap" target="_blank"><img src="<?php echo AWP_ASSETS; ?>/images/logos/keap.png" width="111" height="50" alt="Keap Logo"></a><br/><br/>
    <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?><br/>       
    <form name="keap_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" class="container">
    <input type="hidden" name="action" value="awp_save_keap_keys">
    <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>
    <input type="hidden" name="id" value="<?php echo $id ?>">


    <table class="form-table">      
        <?php
         if(!empty($display_name)){
                    ?>
                        <tr valign="top">
                            <th scope="row"> <?php esc_html_e( 'Display name', 'automate_hub' ); ?></th>
                            <td>
                                <div class="form-table__input-wrap">
                                <input type="text" name="awp_keap_display_name" id="awp_keap_display_name" value="<?php echo $display_name ?>" placeholder="<?php esc_html_e( 'Enter Display name', 'automate_hub' ); ?>" class="basic-text"/>
                                <span class="spci_btn form-table__input-btn" data-clipboard-action="copy" data-clipboard-target="#awp_keap_display_name"><img src="<?php echo AWP_ASSETS; ?>/images/copy.png" alt="Copy to clipboard"></span>
                                </div>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row"></th>
                            <td>
                      
                                    <div class="submit-button-plugin"><?php submit_button(); ?></div>
                              
                            </td>
                        </tr>
                        

                    <?php
                        }
                        else{
                    ?>

                        <tr valign="top">
                            <th scope="row"> </th>
                            <td>
                                <a id="keapauthbtn" target="_blank" class="button button-primary"> Link Keap Account</a>
                            </td>
                        </tr>



                        <script type="text/javascript">
                            document.getElementById("keapauthbtn").addEventListener("click", function(){
                                var win=window.open('<?php echo $this->getLoginURL()?>','popup','width=600,height=600');
                                var id = setInterval(function() {
                                const queryString = win.location.search;
                                const urlParams = new URLSearchParams(queryString);
                                const page_type = urlParams.get('page');
                                if(page_type=='automate_hub'){win.close(); clearInterval(id); location.reload();}
                                }, 1000);
                            });
                        </script>


                    <?php
                        }
                    ?>
    </table>
    </form>
    </div>

    <div class="wrap">
        <form id="form-list" method="post">
                    
            
            <input type="hidden" name="page" value="automate_hub"/>

            <?php
            $data=[
                        'table-cols'=>['account_name'=>'Display name','spots'=>'Active Spots','active_status'=>'Active']
                ];
            $platform_obj= new AWP_Platform_Shell_Table('keap');
            $platform_obj->initiate_table($data);
            $platform_obj->prepare_items();
            $platform_obj->display_table();
                    
            ?>
        </form>
    </div>
    <?php
    }

    protected function request_token( $authorization_code ) {
        if(empty($this->client_secret) || empty($this->client_id)){
            $data=(array) maybe_unserialize( get_option( 'awp_keap_keys_holder' ) );
           $this->client_id=(isset($data['client_id'])?$data['client_id']:'');
           $this->client_secret=(isset($data['client_secret'])?$data['client_secret']:'');
           update_option('awp_keap_keys_holder','');
        }
        $args = array(
            'headers' => array(),
            'body'    => array(
                'code'          => $authorization_code,
                'client_id'     => $this->client_id,
                'client_secret' => $this->client_secret,
                'redirect_uri'  => $this->get_redirect_uri(),
                'grant_type'    => 'authorization_code',
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

    public function awp_save_keap_keys() { 
          if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }
        // Security Check
        if (! wp_verify_nonce( $_POST['_nonce'], 'awp_keap_settings' ) ) {
            die( esc_html__( 'Security check Failed', 'automate_hub' ) );
        }
        
        $display_name     = sanitize_text_field( $_POST["awp_keap_display_name"] );
        $this->account_name = $display_name;
        $this->platformid       =sanitize_text_field( $_POST["id"] );
        $this->save_data();
        AWP_redirect ("admin.php?page=automate_hub&tab=keap");    
    }

    function getLoginURL(){
        return $this->authorize( 'full' );
    }
    protected function authorize( $scope = '' ) {
        $data = array(
            'response_type' => 'code',
            'client_id'     => $this->client_id,
            'state'        => urlencode( $this->get_redirect_uri() ),
            'redirect_uri'  => self::sperse_service_url,
        );
        if( $scope ) {$data["scope"] = $scope;}
        $endpoint = add_query_arg( $data, $this->authorization_endpoint );
        return $endpoint;
    }

    public function action_fields() {?>
        <script type="text/template" id="keap-action-template">
            <?php
                    $app_data=array(
                            'app_slug'=>'keap',
                           'app_name'=>'Keap',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/keap.png',
                           'app_icon_alter_text'=>'Keap Icon',
                           'account_select_onchange'=>'getKeapList',
                           'tasks'=>array(
                                        'subscribe'=>array(
                                                            'task_assignments'=>array(

                                                                                    array(
                                                                                        'label'=>'Mailing List',
                                                                                        'type'=>'select',
                                                                                        'name'=>"list_id",
                                                                                        'model'=>'fielddata.listId',
                                                                                        'required'=>'required',
                                                                                        'onchange'=>'',
                                                                                        'select_default'=>'Select List...',
                                                                                        'option_for_loop'=>'(item, index) in fielddata.list',
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
        $auth   = isset( $_GET['auth'] ) ? trim( $_GET['auth'] ) : '';
        $code   = isset( $_GET['code'] ) ? trim( $_GET['code'] ) : '';
        $action = isset( $_GET['action'] ) ? trim( $_GET['action'] ) : '';
        if ( 'awp_keap_auth_redirect' == $action ) {
            $code = isset( $_GET['code'] ) ? $_GET['code'] : '';
            if ( $code ) {$this->request_token( $code );}
            if ( ! empty( $this->access_token ) ) {$message = 'success'; } else { $message = 'failed'; }
            wp_safe_redirect( admin_url( 'admin.php?page=automate_hub&tab=keap' ) );
            exit();
        }
    }

     protected function get_obj_by_id($id) {
        $platform_obj= new AWP_Platform_Shell_Table('keap');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        $data=[
            'client_id'=>$data->client_id,
            'access_token'=>$data->api_key,
            'refresh_token'=>$data->email,
            'account_name'=>$data->account_name,
            'platformid'=>$data->id,
        ];
        return $data;
     }
    protected function save_data_multiple($data) {
        $mapping=[
            'client_id'=>$data['client_id'],
            'account_name'=>$data['account_name'],
            'email'=>$data['refresh_token'],
            'api_key'=>$data['access_token'],
        ];
        $_POST['id']=(isset($data['platformid'])?$data['platformid']:'');
        if(empty($mapping['account_name'])){
            global $wpdb;
            $query="select * from ".$wpdb->prefix."awp_platform_settings where platform_name='keap'";

            $data=$wpdb->get_results($query);
            $len=count($data) + 1;
            $mapping['account_name']='Account Number '.$len;
        }
        $platform_obj= new AWP_Platform_Shell_Table('keap');
        $platform_obj->save_platform($mapping);
    }
    protected function save_data() {
        $option =  array(
                'client_id'     => $this->client_id,
                'client_secret' => $this->client_secret,
                'access_token'  => $this->access_token,
                'refresh_token' => $this->refresh_token,
                'account_name' => $this->account_name,
                'platformid'=>(isset($this->platformid)?$this->platformid:''),
                
        );

        $this->save_data_multiple($option);

        
    }

    protected function reset_data() {
        $this->client_id     = '';
        $this->access_token  = '';
        $this->refresh_token = '';
        $this->account_name = '';
      
        $this->save_data();
    }

    protected function get_redirect_uri() {
        return get_rest_url(null,'automatehub/keap');

    }

    function refresh_token() {

      

        $request = [
            'headers' => array(
                'Authorization' => $this->get_http_authorization_header(),
                "Content-Type"  => "application/x-www-form-urlencoded",
            ),
            'body'=>array(
                'refresh_token' => $this->refresh_token,
                'grant_type'    => 'refresh_token',
            ),
        ];


        $response      = wp_remote_post( $this->refresh_token_endpoint , $request );
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

    public function create_contact($endpoint,$properties ) {
        $headers = array(
            "Authorization" => "Bearer ".$this->access_token,
            "Content-Type"  => "application/json"
        );

        $body=json_encode($properties);

        $args = array(
                'method'  => 'POST',
                "headers" => $headers,
                "body" => $body
        );
        
        
        $response = $this->remote_request( $endpoint, $args );
        
        return $response;
    }

    public function get_keap_list() {  
          if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }
        // Security Check
        if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
            die( esc_html__( 'Security check Failed', 'automate_hub' ) );
        }
        $id=sanitize_text_field($_POST['platformid']);
        $platform_obj= new AWP_Platform_Shell_Table('keap');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);

      
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $this->access_token =$data->api_key;
        $this->get_contact_lists();
    }

    public function get_contact_lists() {

        $endpoint = "https://campaigns.keap.com/api/v1.1/getmailinglists?resfmt=JSON&sort=desc&fromindex=0&range=100";
        $request = ['method'  => 'GET', 'headers' => [
                'Content-Type'  => 'application/x-www-form-urlencoded',
                'Authorization' => 'keap-oauthtoken ' . $this->access_token ],];

        $response = $this->remote_request( $endpoint, $request );


        
        //awp_add_to_log( $response, $endpoint, $request, array( "id" => "76" ) );
        if( is_wp_error( $response ) ) {
            return false;
        }
        $response_body = wp_remote_retrieve_body( $response );
        if ( empty( $response_body ) ) {
            return false;
        }
        $response_body = json_decode( $response_body, true );
        if ( isset( $response_body['list_of_details'] ) && !empty( $response_body['list_of_details'] ) ) {
            $lists = wp_list_pluck( $response_body['list_of_details'], 'listname', 'listkey' );
            wp_send_json_success( $lists );
        } else {
            wp_send_json_error();
        }
    }

    protected function remote_request( $url, $request = array() ) {
        static $refreshed = false;
        $request = wp_parse_args( $request, [ ] );
        $request['headers'] = array_merge(
            array( 'Authorization' => $this->get_http_authorization_header( 'bearer' ), ),
            $request['headers']
        );
        $response = wp_remote_request( esc_url_raw( $url ), $request );
        
        if ( 401 === wp_remote_retrieve_response_code( $response )
            and !$refreshed
        ) { $this->refresh_token();
            $refreshed = true;
            $response = $this->remote_request( $url, $request );
        }
        $response_body = wp_remote_retrieve_body( $response );
        $response_body = json_decode( $response_body, true );
        if( isset( $response_body["message"] ) ) {
            if ( "Unauthorized request." == $response_body["message"] ) {
                $this->refresh_token();
                $refreshed = true;
                $response = $this->remote_request( $url, $request );
            }
        }
        return $response;
    }
}

$keap = AWP_keap::get_instance();
$keap->prepare_obj();
/* Saves connection mapping */
function awp_keap_save_integration() {
    $params = array();
    parse_str( awp_sanitize_text_or_array_field( $_POST['formData'] ), $params );
    $trigger_data = isset( $_POST["triggerData"]) ? awp_sanitize_text_or_array_field( $_POST["triggerData"]) : array();
    $action_data  = isset( $_POST["actionData" ]) ? awp_sanitize_text_or_array_field( $_POST["actionData" ]) : array();
    $field_data   = isset( $_POST["fieldData"  ]) ? awp_sanitize_text_or_array_field( $_POST["fieldData"  ]) : array();
    $integration_title = isset( $trigger_data["integrationTitle"]) ? $trigger_data["integrationTitle" ] : "";
    $form_provider_id  = isset( $trigger_data["formProviderId"  ]) ? $trigger_data["formProviderId"   ] : "";
    $form_id           = isset( $trigger_data["formId"          ]) ? $trigger_data["formId"           ] : "";
    $form_name         = isset( $trigger_data["formName"        ]) ? $trigger_data["formName"         ] : "";
    $action_provider   = isset( $action_data ["actionProviderId"]) ? $action_data  ["actionProviderId"] : "";
    $task              = isset( $action_data ["task"            ]) ? $action_data  ["task"            ] : "";
    $type              = isset( $params      ["type"            ]) ? $params       ["type"            ] : "";
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
        if ( $type != 'update_integration' &&  !empty( $id ) ) { exit; }
        $result = $wpdb->update( $integration_table,
            array(
                'title'           => $integration_title,
                'form_provider'   => $form_provider_id,
                'form_id'         => $form_id,
                'form_name'       => $form_name,
                'data'            => json_encode( $all_data, true ),
            ),
            array('id' => $id
            )
        );
    }
    
    $return=array();
    $return['type']=$type;
    $return['result']=$result;
    $return['insertid']=$wpdb->insert_id;
    return $return;
}

/* Handles sending data to Constant Contact API */
function awp_keap_send_data( $record, $posted_data ) {
    $record_data = json_decode( $record["data"], true );
    if( array_key_exists( "cl", $record_data["action_data"] ) ) {
        if( $record_data["action_data"]["cl"]["active"] == "yes" ) {
            if( !awp_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data ) ) {
                return;
            }
        }
    }
    $data    = $record_data["field_data"];
    $list_id = $data["listId"];
    $task    = $record["task"];
    if( $task == "add_contact" ) {
        $email   = empty( $data["email"] ) ? "" : awp_get_parsed_values( $data["email"], $posted_data );
        $first_name   = empty( $data["given_name"] ) ? "" : awp_get_parsed_values($data["given_name"], $posted_data);
        $middle_name   = empty( $data["middle_name"] ) ? "" : awp_get_parsed_values($data["middle_name"], $posted_data);
        $last_name   = empty( $data["family_name"] ) ? "" : awp_get_parsed_values($data["family_name"], $posted_data);
        $job_title   = empty( $data["job_title"] ) ? "" : awp_get_parsed_values($data["job_title"], $posted_data);
        $spouse_name   = empty( $data["spouse_name"] ) ? "" : awp_get_parsed_values($data["spouse_name"], $posted_data);
        $preferred_name   = empty( $data["preferred_name"] ) ? "" : awp_get_parsed_values($data["preferred_name"], $posted_data);
        $phone_numbers   = empty( $data["phone_numbers"] ) ? "" : awp_get_parsed_values($data["phone_numbers"], $posted_data);
        $time_zone   = empty( $data["time_zone"] ) ? "" : awp_get_parsed_values($data["time_zone"], $posted_data);
        $website   = empty( $data["website"] ) ? "" : awp_get_parsed_values($data["website"], $posted_data);
        $line1   = empty( $data["line1"] ) ? "" : awp_get_parsed_values($data["line1"], $posted_data);
        $line2   = empty( $data["line2"] ) ? "" : awp_get_parsed_values($data["line2"], $posted_data);
        $locality   = empty( $data["locality"] ) ? "" : awp_get_parsed_values($data["locality"], $posted_data);
        $region   = empty( $data["region"] ) ? "" : awp_get_parsed_values($data["region"], $posted_data);
        $zip_code   = empty( $data["zip_code"] ) ? "" : awp_get_parsed_values($data["zip_code"], $posted_data);
        $postal_code   = empty( $data["postal_code"] ) ? "" : awp_get_parsed_values($data["postal_code"], $posted_data);
        $opt_in_reason    = empty( $data["opt_in_reason"] ) ? "" : awp_get_parsed_values($data["opt_in_reason"], $posted_data);

        $properties = array(
            "email_addresses"  => array(array("field"=>"EMAIL1","email"=>$email)),
            "addresses"    => array(

                array(
                    "field"=>"BILLING",
                    "line1"=>$line1,
                    "line2"=> $line2,
                    "locality"=> $locality,
                    "region"=> $region,
                    "zip_code"=> $zip_code,
                    "postal_code"=>$postal_code
                )
            ),

            "given_name"=>$first_name,
            "middle_name"=>$middle_name,
            "family_name"=>$last_name,
            "job_title"=>$job_title,
            "spouse_name"=>$spouse_name,
            "preferred_name"=>$preferred_name,
            "phone_numbers"=>array(array("field"=>"PHONE1","number"=>$phone_numbers)),
            "time_zone"=>$time_zone,
            "website"=>$website,
            "opt_in_reason"=>$opt_in_reason

       
        );

    
        $temp    = json_decode( $record["data"], true );
        $temp    = $temp["field_data"];
        $keap = AWP_keap::get_instance();
        $keap->prepare_obj($temp['activePlatformId']);        

        
        
        
        $endpoint = 'https://api.infusionsoft.com/crm/rest/v1/contacts';        

        $request = ['method'  => 'POST', 'headers' => [
                'Content-Type' => 'application/json',
            'Authorization' => 'Bearer XXXXXXXXX' ],'body'=>$properties,
        ];

 
        $return = $keap->create_contact( $endpoint,$properties );

        awp_add_to_log( $return, $endpoint, $request, $record );
    }

    return $return;
}



function awp_keap_resend_data( $log_id,$data,$record ) {
    $record_data = json_decode( $record["data"], true );
    
    $data=stripslashes($data);
    $data=preg_replace('/\s+/', '',$data); 
    $data=json_decode($data,true);
    $url=$data['url'];
    $task    = $record["task"];
    
    if(!$url){
            $response['success']=false;
            $response['msg']="Syntax Error! Request is invalid";
            return $response;
    }
    
    if( $task == "add_contact" ) {
        
        
        $temp    = json_decode( $record["data"], true );
        $temp    = $temp["field_data"];
        $keap = AWP_keap::get_instance();
        $keap->prepare_obj($temp['activePlatformId']); 
      
               

        $request = ['method'  => 'POST', 'headers' => [
                'Accept'       => 'application/json',
                'Content-Type' => ' application/x-www-form-urlencoded',
            'Authorization' => 'keap-oauthtoken XXXXXXXXX' ],'body'=>$data['args']['body'],
        ];
        $return = $keap->create_contact( $url,$data['args']['body'] );
        awp_add_to_log( $return, $url, $request, $record );
    }
    $response['success']=true;
    return $response;
}
