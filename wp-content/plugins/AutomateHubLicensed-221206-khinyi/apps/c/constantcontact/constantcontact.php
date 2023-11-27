<?php
class awp_ConstantContact extends AWP_OAuth2 {
    const service_name           = 'constant_contact';
    const authorization_endpoint = 'https://api.cc.email/v3/idfed';
    const token_endpoint         = 'https://idfed.constantcontact.com/as/token.oauth2';
    const refresh_token_endpoint = 'https://idfed.constantcontact.com/as/token.oauth2';
    private static $instance;
    protected      $contact_lists = [ ];
    public static function get_instance() {
        if ( empty( self::$instance ) ) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct() {
        $this->authorization_endpoint = self::authorization_endpoint;
        $this->token_endpoint         = self::token_endpoint;
        $option = (array) maybe_unserialize( get_option( 'awp_constantcontact_keys' ) );
        if ( isset( $option['client_id'     ])) {$this->client_id      = $option['client_id'    ];}
        if ( isset( $option['client_secret' ])) {$this->client_secret  = $option['client_secret'];}
        if ( isset( $option['access_token'  ])) {$this->access_token   = $option['access_token' ];}
        if ( isset( $option['refresh_token' ])) {$this->refresh_token  = $option['refresh_token'];}
        if ( $this->is_active() ) {
            if ( isset( $option['contact_lists'] ) ) {
                $this->contact_lists = $option['contact_lists'];
            }
        }
        add_action( 'admin_init', array( $this, 'auth_redirect' ) );
        add_action( 'awp_custom_script', array( $this, 'custom_script' ) );
        add_filter( 'awp_action_providers', array( $this, 'awp_constantcontact_actions' ), 10, 1 );
        add_filter( 'awp_settings_tabs', array( $this, 'awp_constantcontact_settings_tab' ), 10, 1 );
        add_action( 'awp_settings_view', array( $this, 'awp_constantcontact_settings_view' ), 10, 1 );
        add_action( 'admin_post_awp_save_constantcontact_keys', array( $this, 'awp_save_constantcontact_keys' ), 10, 0 );
        add_action( 'awp_action_fields', array( $this, 'action_fields' ), 10, 1 );
        add_action( 'wp_ajax_awp_get_constantcontact_list', array( $this, 'get_constantcontact_list' ), 10, 0 );
        add_action( "rest_api_init", array( $this, "create_webhook_route" ) );
        add_filter( 'awp_platform_connections',array( $this, "awp_constantcontact_platform_connection" ) , 10, 1 );
    }

    public function awp_capsulecrm_platform_connection($response){

        $option       = (array) maybe_unserialize( get_option( 'awp_constantcontact_keys' ) );



        if(!empty($option['client_id']) && !empty($option['client_secret']) && !empty($this->get_redirect_uri())){
            $temp= true;
        }
        else{
            $temp= false;
        }
        $response['constantcontact']=array(
            'isConnected' => $temp
        );
        return $response;
    }
    public function create_webhook_route() {
        register_rest_route( 'advancedformintegration', '/constantcontact',
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
                [   'service' => 'authorize',
                    'action'  => 'awp_constantcontact_auth_redirect',
                    'code'    => $code, ],
                admin_url( 'admin.php?page=automate_hub')
            );
            wp_safe_redirect( $redirect_to );
            exit();
        }
    }

    public function custom_script() {
        //wp_enqueue_script( 'awp-constantcontact-script', AWP_URL . '/apps/constantcontact/script.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function awp_constantcontact_actions( $actions ) {
        $actions['constantcontact'] = array(
            'title' => esc_html__( 'Constant Contact', 'automate_hub' ),
            'tasks' => array('subscribe'   => esc_html__( 'Subscribe To List', 'automate_hub'))
        );
        return $actions;
    }
    public function awp_constantcontact_settings_tab( $providers ) {
        $providers['constantcontact'] = array(
        'name'=>esc_html__( 'Constant Contact', 'automate_hub'),
        'cat'=>array('crm')
    );
        return $providers;
    }

    public function awp_constantcontact_settings_view( $current_tab ) {
        if( $current_tab != 'constantcontact' ) { return; }
        $option       = (array) maybe_unserialize( get_option( 'awp_constantcontact_keys' ) );
        $nonce        = wp_create_nonce( "awp_constantcontact_settings" );
        $api_key      = $option['client_id'];
        $api_secret   = $option['client_secret'];
        $redirect_uri = $this->get_redirect_uri();
        ?>
        <form name="constantcontact_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" class="container">
            <input type="hidden" name="action" value="awp_save_constantcontact_keys">
            <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>
            <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php esc_html_e( 'Status', 'automate_hub' ); ?></th>
                <td><?php
                    if( $this->is_active() ) {
                        esc_html_e( 'Connected', 'automate_hub' );
                    } else {
                        esc_html_e( 'Not Connected', 'automate_hub' );
                    }
                    ?>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"> <?php esc_html_e( 'API Key', 'automate_hub' ); ?></th>
                <td>
					<div class="form-table__input-wrap">
					<input type="text" name="awp_constantcontact_api_key" id="awp_constantcontact_api_key" value="<?php echo $api_key; ?>" placeholder="<?php esc_html_e( 'Enter API Key', 'automate_hub' ); ?>" class="basic-text"/>
                <span class="spci_btn" data-clipboard-action="copy" data-clipboard-target="#awp_constantcontact_api_key"><img src="<?php echo AWP_ASSETS; ?>/images/copy.png" alt="Copy to clipboard"></span>  </div>                                           
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"> <?php esc_html_e( 'API Secret', 'automate_hub' ); ?></th>
                <td>
					<div class="form-table__input-wrap">
					<input type="text" name="awp_constantcontact_api_secret" id="awp_constantcontact_api_secret" value="<?php echo $api_secret; ?>" placeholder="<?php esc_html_e( 'Enter API Secret', 'automate_hub' ); ?>" class="basic-text"/>
                <span class="spci_btn" data-clipboard-action="copy" data-clipboard-target="#awp_constantcontact_api_secret"><img src="<?php echo AWP_ASSETS; ?>/images/copy.png" alt="Copy to clipboard"></span></div>                                             
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"> <?php esc_html_e( 'Redirect URI', 'automate_hub' ); ?></th>
                <td>
					<div class="form-table__input-wrap">
					<input type="text" value="<?php echo $redirect_uri; ?>" id="redirect_uri" name="redirect_uri" class="basic-text code" readonly="readonly" onfocus="this.select();" />
                <span class="spci_btn" data-clipboard-action="copy" data-clipboard-target="#redirect_uri"><img src="<?php echo AWP_ASSETS; ?>/images/copy.png" alt="Copy to clipboard"></span>                   </div>
                </td>
            </tr>
            </table>
			<div class="submit-button-plugin">
            <?php submit_button( esc_html__( 'Authorize', 'automate_hub' ) ); ?>
			</div>
        </form>
        <?php
    }

    public function awp_save_constantcontact_keys() {

            if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }

        // Security Check
        if (! wp_verify_nonce( $_POST['_nonce'], 'awp_constantcontact_settings' ) ) {
            die( esc_html__( 'Security check Failed', 'automate_hub' ) );
        }
        $api_key    = isset( $_POST["awp_constantcontact_api_key"] ) ? sanitize_text_field( $_POST["awp_constantcontact_api_key"] ) : "";
        $api_secret = isset( $_POST["awp_constantcontact_api_secret"] ) ? sanitize_text_field( $_POST["awp_constantcontact_api_secret"] ) : "";
        $this->client_id     = trim( $api_key );
        $this->client_secret = trim( $api_secret );
        $this->save_data();
        $this->authorize( 'contact_data' );
        AWP_redirect( "admin.php?page=automate_hub&tab=constantcontact" );
    }

    public function action_fields() {
        ?>
        <script type="text/template" id="constantcontact-action-template">
            <div>
            <table class="form-table">
                <tr valign="top" v-if="action.task == 'subscribe'">
                    <th scope="row">
                        <?php esc_html_e( 'Map Fields', 'automate_hub' ); ?>
                    </th>
                    <td scope="row">

                    </td>
                </tr>

                <tr valign="top" class="alternate" v-if="action.task == 'subscribe'">
                    <td scope="row-title">
                        <label for="tablecell">
                            <?php esc_html_e( 'Constant Contact List', 'automate_hub' ); ?>
                        </label>
                    </td>
                    <td>
                        <select name="list_id" v-model="fielddata.listId" required="required">
                            <option value=""> <?php esc_html_e( 'Select List...', 'automate_hub' ); ?> </option>
                            <option v-for="(item, index) in fielddata.list" :value="index" > {{item}}  </option>
                        </select>
                        <div class="spinner" v-bind:class="{'is-active': listLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                    </td>
                </tr>
            </table>

             <div class="form_fields sperse_reverse_draggable">
				<div class="dynamic-form-logo"> <img v-if="trigger.formProviderId" v-bind:src="'<?php echo AWP_ASSETS; ?>/images/icons/' + trigger.formProviderId + '.png'" v-bind:alt="trigger.formProviderId" class="logo-form">
<span>Drag your form field to map it to a destination field.</span></div>					 
                <ul>
                    <li class="form_fields_name"  v-for="(nfield, nindex) in trigger.formFields" :data-name="nindex"  :data-fname="nfield" v-if="CheckinDatabase(nindex,nfield)" >
                            <div class="field-actions hide">
                                <a type="remove" v-bind:id=nindex v-bind:data-name=nindex v-bind:data-field=nfield v-on:click="say($event)"  class="del-button btn formbuilder-icon-cancel delete-confirm" title="Remove Element"></a>
                            </div>
                        <span class="input-group-addon fx-dragdrop-handle">{{nfield}}</span>
                    </li>
                </ul>
            </div>
			<div class="form_placeholder_wrap">

                <div v-if="action.paltformConnected == true">
                    <table class="form-table form-fields-table">
    					<editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>
    				</table>
                </div>
                <div v-if="action.paltformConnected == false">
                    <div class="submit-button-plugin" style="width: 100%;display: flex;">
                    <a style="margin: 0 auto;" href="<?php echo admin_url( 'admin.php?page=automate_hub&tab=constantcontact' ) ?>">
                        <div  class="button button-primary" style="padding: 8px;font-size: 14px;">Connect Your Constant Contact Accout</div>
                    </a>
                    </div>
                </div>
			</div>			

        </div>
        </script>


        <?php
    }

    public function auth_redirect() {

        $auth   = isset( $_GET['auth'] ) ? trim( $_GET['auth'] ) : '';
        $code   = isset( $_GET['code'] ) ? trim( $_GET['code'] ) : '';
        $action = isset( $_GET['action'] ) ? trim( $_GET['action'] ) : '';

        if ( 'awp_constantcontact_auth_redirect' == $action ) {
            $code = isset( $_GET['code'] ) ? $_GET['code'] : '';

            if ( $code ) {
                $this->request_token( $code );
            }

            if ( ! empty( $this->access_token ) ) {
                $message = 'success';
            } else {
                $message = 'failed';
            }

            wp_safe_redirect( admin_url( 'admin.php?page=automate_hub&tab=constantcontact' ) );

            exit();
        }
    }

    protected function save_data() {

        $data = (array) maybe_unserialize( get_option( 'awp_constantcontact_keys' ) );

        $option = array_merge(
            $data,
            array(
                'client_id'     => $this->client_id,
                'client_secret' => $this->client_secret,
                'access_token'  => $this->access_token,
                'refresh_token' => $this->refresh_token,
                'contact_lists' => $this->contact_lists,
            )
        );

        update_option( 'awp_constantcontact_keys', maybe_serialize( $option ) );
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

        return site_url( '/wp-json/advancedformintegration/constantcontact' );
    }

    public function email_exists( $email ) {

        $endpoint = add_query_arg(
            [ 'email' => $email ],
            'https://api.cc.email/v3/contacts'
        );

        $request = [
            'method'  => 'GET',
            'headers' => [
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json; charset=utf-8',
            ],
        ];

        $response = $this->remote_request( $endpoint, $request );

        if ( 400 <= (int) wp_remote_retrieve_response_code( $response ) ) {
            if ( WP_DEBUG ) {
                $this->log( $endpoint, $request, $response );
            }

            return false;
        }

        $response_body = wp_remote_retrieve_body( $response );

        if ( empty( $response_body ) ) {
            return false;
        }

        $response_body = json_decode( $response_body, true );

        return !empty( $response_body['contacts'] );
    }

    public function create_contact( $properties ) {

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

    public function get_constantcontact_list() {
            if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }

        // Security Check
        if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
            die( esc_html__( 'Security check Failed', 'automate_hub' ) );
        }

        $this->get_contact_lists();
    }

    public function get_contact_lists() {

        $endpoint = 'https://api.cc.email/v3/contact_lists';

        $request = [
            'method'  => 'GET',
            'headers' => [
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json; charset=utf-8',
            ],
        ];

        $response = $this->remote_request( $endpoint, $request );

        if ( 400 <= (int) wp_remote_retrieve_response_code( $response ) ) {
            if ( WP_DEBUG ) {
//                $this->log( $endpoint, $request, $response );
            }

            return false;
        }

        $response_body = wp_remote_retrieve_body( $response );

        if ( empty( $response_body ) ) {
            return false;
        }

        $response_body = json_decode( $response_body, true );

        if ( !empty( $response_body['lists'] ) ) {
            $lists = wp_list_pluck( $response_body['lists'], 'name', 'list_id' );

            wp_send_json_success( $lists );
        } else {
            wp_send_json_error();
        }
    }

    public function update_contact_lists( $selection = [ ] ) {

        $contact_lists        = [ ];
        $contact_lists_on_api = $this->get_contact_lists();

        if ( false !== $contact_lists_on_api ) {
            foreach ( (array) $contact_lists_on_api as $list ) {
                if ( isset( $list['list_id'] ) ) {
                    $list_id = trim( $list['list_id'] );
                } else {
                    continue;
                }

                if ( isset( $this->contact_lists[$list_id]['selected'] ) ) {
                    $list['selected'] = $this->contact_lists[$list_id]['selected'];
                } else {
                    $list['selected'] = [ ];
                }

                $contact_lists[$list_id] = $list;
            }
        } else {
            $contact_lists = $this->contact_lists;
        }

        foreach ( (array) $selection as $key => $ids_or_names ) {
            foreach ( $contact_lists as $list_id => $list ) {
                if ( in_array( $list['list_id'], (array) $ids_or_names, true )
                    or in_array( $list['name'], (array) $ids_or_names, true )
                ) {
                    $contact_lists[$list_id]['selected'][$key] = true;
                } else {
                    unset( $contact_lists[$list_id]['selected'][$key] );
                }
            }
        }

        $this->contact_lists = $contact_lists;

        if ( $selection ) {
            $this->save_data();
        }

        return $this->contact_lists;
    }
}

$constantcontact = awp_ConstantContact::get_instance();

/* Saves connection mapping */
function awp_constantcontact_save_integration() {
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
            array('id' => $id)
        );
    }
    $return=array();
    $return['type']=$type;
    $return['result']=$result;
    $return['insertid']=$wpdb->insert_id;
    return $return;
}

/* Handles sending data to Constant Contact API */
function awp_constantcontact_send_data( $record, $posted_data ) {
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
        $constantcontact = awp_ConstantContact::get_instance();
        $return = $constantcontact->create_contact( $properties );
        awp_add_to_log( $return, 'https://api.cc.email/v3/contacts', $properties, $record );
    }
    return;
}
