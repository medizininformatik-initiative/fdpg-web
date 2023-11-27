<?php

class awp_Trustpilot extends AWP_OAuth2 {

    protected $domain           = '';
    protected $business_unit_id = '';

    const service_name           = 'trustpilot';
    const authorization_endpoint = 'https://authenticate.trustpilot.com';
    const token_endpoint         = 'https://api.trustpilot.com/v1/oauth/oauth-business-users-for-applications/accesstoken';
    const refresh_token_endpoint = 'https://api.trustpilot.com/v1/oauth/oauth-business-users-for-applications/refresh';

    private static $instance;

    public static function get_instance() {

        if ( empty( self::$instance ) ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    private function __construct() {

        $this->authorization_endpoint = self::authorization_endpoint;
        $this->token_endpoint         = self::token_endpoint;
        $this->refresh_token_endpoint         = self::refresh_token_endpoint;

        $option = (array) maybe_unserialize( get_option( 'awp_trustpilot_keys' ) );

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

        add_action( 'admin_init', array( $this, 'auth_redirect' ) );
        add_action( 'awp_custom_script', array( $this, 'custom_script' ) );
        add_filter( 'awp_action_providers', array( $this, 'awp_trustpilot_actions' ), 10, 1 );
        add_filter( 'awp_settings_tabs', array( $this, 'awp_trustpilot_settings_tab' ), 10, 1 );
        add_action( 'awp_settings_view', array( $this, 'awp_trustpilot_settings_view' ), 10, 1 );
        add_action( 'admin_post_awp_save_trustpilot_keys', array( $this, 'awp_save_trustpilot_keys' ), 10, 0 );
        add_action( 'awp_action_fields', array( $this, 'action_fields' ), 10, 1 );
        add_action( "rest_api_init", array( $this, "create_webhook_route" ) );
        add_filter( 'awp_platform_connections', 'awp_trustpilot_platform_connection', 10, 1 );

    }

    public function awp_trustpilot_platform_connection($response){
        $option       = (array) maybe_unserialize( get_option( 'awp_trustpilot_keys' ) );



        if(!empty($option['domain']) && !empty($option['client_id']) && !empty($option['client_secret']) && !empty($this->get_redirect_uri())){
            $temp= true;
        }
        else{
            $temp= false;
        }
        $response['trustpilot']=array(
            'isConnected' => $temp
        );
        return $response;
    }

    public function create_webhook_route() {
        register_rest_route( 'advancedformintegration', '/trustpilot',
            array(
                'methods'  => 'GET',
                'callback' => array( $this, 'get_webhook_data' )
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
                    'action'  => 'awp_trustpilot_auth_redirect',
                    'code'    => $code,
                ],
                admin_url( 'admin.php?page=automate_hub')
            );

            wp_safe_redirect( $redirect_to );
            exit();
        }
    }

    public function custom_script() {
        wp_enqueue_script( 'awp-trustpilot-script', AWP_URL . '/pro/trustpilot/script.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function awp_trustpilot_actions( $actions ) {

        $actions['trustpilot'] = array(
            'title' => esc_html__( 'Trustpilot', 'automate_hub' ),
            'tasks' => array(
                'create_invitation'   => esc_html__( 'Create Invitation', 'automate_hub' )
            )
        );

        return $actions;
    }

    public function awp_trustpilot_settings_tab( $providers ) {
        $providers['trustpilot'] = esc_html__( 'Trustpilot', 'automate_hub' );

        return $providers;
    }

    public function awp_trustpilot_settings_view( $current_tab ) {
        if( $current_tab != 'trustpilot' ) {
            return;
        }

        $option       = (array) maybe_unserialize( get_option( 'awp_trustpilot_keys' ) );
        $nonce        = wp_create_nonce( "awp_trustpilot_settings" );
        $domain       = $option['domain'];
        $api_key      = $option['client_id'];
        $api_secret   = $option['client_secret'];
        $redirect_uri = $this->get_redirect_uri();

        ?>

        <form name="trustpilot_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
              method="post" class="container">

            <input type="hidden" name="action" value="awp_save_trustpilot_keys">
            <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row"> <?php esc_html_e( 'Status', 'automate_hub' ); ?></th>
                    <td>
                        <?php
                        if( $this->is_active() ) {
                            esc_html_e( 'Connected', 'automate_hub' );
                        } else {
                            esc_html_e( 'Not Connected', 'automate_hub' );
                        }
                        ?>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"> <?php esc_html_e( 'Domain', 'automate_hub' ); ?></th>
                    <td>
                        <input type="text" name="awp_trustpilot_domain"
                               value="<?php echo $domain; ?>" placeholder="<?php esc_html_e( 'Enter Domain', 'automate_hub' ); ?>"
                               class="basic-text"/>
                        <p class="description" id="code-description"><?php esc_html_e( 'Domain of the company like \'mydoamin.com\' or \'yourdomain.com\'', 'automate_hub' ); ?></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"> <?php esc_html_e( 'API Key', 'automate_hub' ); ?></th>
                    <td>
						<div class="form-table__input-wrap">
                        <input type="text" name="awp_trustpilot_api_key" id="awp_trustpilot_api_key" value="<?php echo $api_key; ?>" placeholder="<?php esc_html_e( 'Enter API Key', 'automate_hub' ); ?>" class="basic-text"/>
						<span class="spci_btn" data-clipboard-action="copy" data-clipboard-target="#awp_trustpilot_api_key"><img src="<?php echo AWP_ASSETS; ?>/images/copy.png" alt="Copy to clipboard"></span> </div>             																																																									  
					</td>
                </tr>
                <tr valign="top">
                    <th scope="row"> <?php esc_html_e( 'Secret', 'automate_hub' ); ?></th>
                    <td>
						<div class="form-table__input-wrap">
                        <input type="text" name="awp_trustpilot_api_secret" id="awp_trustpilot_api_secret" value="<?php echo $api_secret; ?>" placeholder="<?php esc_html_e( 'Enter Secret', 'automate_hub' ); ?>" class="basic-text"/>
						<span class="spci_btn" data-clipboard-action="copy" data-clipboard-target="#awp_trustpilot_api_secret"><img src="<?php echo AWP_ASSETS; ?>/images/copy.png" alt="Copy to clipboard"></span> </div>             																																																									                   
				   </td>
                </tr>
                <tr valign="top">
                    <th scope="row"> <?php esc_html_e( 'Redirect URI', 'automate_hub' ); ?></th>
                    <td>
						<div class="form-table__input-wrap">
                        <input type="text" value="<?php echo $redirect_uri; ?>" id="redirect_uri" name="redirect_uri" class="basic-text code" readonly="readonly" onfocus="this.select();" />
						<span class="spci_btn" data-clipboard-action="copy" data-clipboard-target="#redirect_uri"><img src="<?php echo AWP_ASSETS; ?>/images/copy.png" alt="Copy to clipboard"></span>              			</div>																																																						     
					</td>
                </tr>
            </table>
           <div class="submit-button-plugin"> <?php submit_button( esc_html__( 'Authorize', 'automate_hub' ) ); ?></div>
        </form>

        <?php
    }

    public function awp_save_trustpilot_keys() {
          if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }
        // Security Check
        if (! wp_verify_nonce( $_POST['_nonce'], 'awp_trustpilot_settings' ) ) {
            die( esc_html__( 'Security check Failed', 'automate_hub' ) );
        }

        $domain     = isset( $_POST["awp_trustpilot_domain"] ) ? sanitize_text_field( $_POST["awp_trustpilot_domain"] ) : "";
        $api_key    = isset( $_POST["awp_trustpilot_api_key"] ) ? sanitize_text_field( $_POST["awp_trustpilot_api_key"] ) : "";
        $api_secret = isset( $_POST["awp_trustpilot_api_secret"] ) ? sanitize_text_field( $_POST["awp_trustpilot_api_secret"] ) : "";

        if( !$domain || !$api_key || !$api_secret ) {
            return;
        }

        $this->domain           = trim( $domain );
        $this->client_id        = trim( $api_key );
        $this->client_secret    = trim( $api_secret );
        $this->business_unit_id = $this->get_business_unit_id( $this->client_id );

        $this->save_data();
        $this->authorize();

        AWP_redirect( "admin.php?page=automate_hub&tab=trustpilot" );
    }

    protected function authorize( $scope = '' ) {

        $endpoint = add_query_arg(
            array(
                'response_type' => 'code',
                'client_id'     => $this->client_id,
                'redirect_uri'  => urlencode( $this->get_redirect_uri() )
            ),
            $this->authorization_endpoint
        );

        if ( wp_redirect( esc_url_raw( $endpoint ) ) ) {
            exit();
        }
    }

    protected function request_token( $authorization_code ) {

        $request = array(
            'headers' => array(
                'Authorization' => $this->get_http_authorization_header( 'basic' ),
                'Content-Type'  => 'application/x-www-form-urlencoded'
            ),
            'body' => array(
                'code'          => $authorization_code,
                'redirect_uri'  => $this->get_redirect_uri(),
                'grant_type'    => 'authorization_code',
                'client_id'     => $this->client_id,
                'client_secret' => $this->client_secret
            )
        );

        $response      = wp_remote_post( esc_url_raw( $this->token_endpoint ), $request );
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

        $data = (array) maybe_unserialize( get_option( 'awp_trustpilot_keys' ) );

        $option = array_merge(
            $data,
            array(
                'access_token'  =>    $this->access_token,
                'refresh_token' =>    $this->refresh_token

            )
        );

        update_option( 'awp_trustpilot_keys', maybe_serialize( $option ) );

        return;
    }

    public function action_fields() {
        ?>
        <script type="text/template" id="trustpilot-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'trustpilot',
                           'app_name'=>'Trustpilot',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/trustpilot.png',
                           'app_icon_alter_text'=>'Trustpilot Icon',
                           'account_select_onchange'=>'',
                           'tasks'=>array(
                                        'subscribe'=>array(
                                                            'task_assignments'=>array(

                                                                                    array(
                                                                                        'label'=>'Constant Contact List',
                                                                                        'type'=>'select',
                                                                                        'name'=>"list_id",
                                                                                        'model'=>'fielddata.listId',
                                                                                        'required'=>'required',
                                                                                        'onchange'=>'',
                                                                                        'select_default'=>'Select Sequence...',
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

        if ( 'awp_trustpilot_auth_redirect' == $action ) {
            $code = isset( $_GET['code'] ) ? $_GET['code'] : '';

            if ( $code ) {
                $this->request_token( $code );
            }

            if ( ! empty( $this->access_token ) ) {
                $message = 'success';
            } else {
                $message = 'failed';
            }

            wp_safe_redirect( admin_url( 'admin.php?page=automate_hub&tab=trustpilot' ) );

            exit();
        }
    }

    protected function save_data() {

        $data = (array) maybe_unserialize( get_option( 'awp_trustpilot_keys' ) );

        $option = array_merge(
            $data,
            array(
                'domain'           =>    $this->domain,
                'client_id'        =>    $this->client_id,
                'client_secret'    =>    $this->client_secret,
                'access_token'     =>    $this->access_token,
                'refresh_token'    =>    $this->refresh_token,
                'business_unit_id' => $this->business_unit_id,
            )
        );

        update_option( 'awp_trustpilot_keys', maybe_serialize( $option ) );
    }

    protected function get_business_unit_id( ) {
        $url = "https://api.trustpilot.com/v1/business-units/find?apikey={$this->client_id}&name={$this->domain}";

        $result = wp_remote_get( $url );

        if( is_wp_error( $result ) ) {
            return;
        }

        $body  = json_decode( $result["body"] );
        $id    = isset( $body->id ) ? $body->id : "";

        return $id;
    }

    protected function reset_data() {

        $this->client_id     = '';
        $this->client_secret = '';
        $this->access_token  = '';
        $this->refresh_token = '';

        $this->save_data();
    }

    protected function get_redirect_uri() {
        return site_url( '/wp-json/advancedformintegration/trustpilot' );
    }

    public function create_invitation( $properties ) {

        $endpoint = 'https://api.cc.email/v3/contacts';

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

}

$trustpilot = awp_Trustpilot::get_instance();

/*
 * Saves connection mapping
 */
function awp_trustpilot_save_integration() {
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
 * Handles sending data to Constant Contact API
 */
function awp_trustpilot_send_data( $record, $posted_data ) {

    $data    = json_decode( $record["data"], true );
    $data    = $data["field_data"];
    $list_id = $data["listId"];
    $task    = $record["task"];

    $record_data = json_decode( $record["data"], true );
    if( array_key_exists( "cl", $record_data["action_data"]) ) {
        if( $record_data["action_data"]["cl"]["active"] == "yes" ) {
            $chk = awp_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data );
            if( !awp_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data ) ) {
                return;
            }
        }
    }

    if( $task == "subscribe" ) {
        $email   = empty( $data["email"] ) ? "" : awp_get_parsed_values( $data["email"], $posted_data );
        $first_name   = empty( $data["firstName"] ) ? "" : awp_get_parsed_values($data["firstName"], $posted_data);
        $last_name    = empty( $data["lastName"] ) ? "" : awp_get_parsed_values($data["lastName"], $posted_data);

        $properties = array(
            "email_address"  => array(
                "address" => $email
            ),
            "first_name"    => $first_name,
            "last_name"     => $last_name,
            "create_source" => "Account"
        );

        $trustpilot = awp_ConstantContact::get_instance();
        $return = $trustpilot->create_contact( $properties );

        awp_add_to_log( $return, 'https://api.cc.email/v3/contacts', $properties, $record );

    }

    return $return;
}
