<?php

add_filter( 'awp_action_providers', 'awp_campaignmonitor_actions', 10, 1 );

function awp_campaignmonitor_actions( $actions ) {
    $actions['campaignmonitor'] = array(
        'title' => esc_html__( 'Campaign Monitor', 'automate_hub'),
        'tasks' => array(
            'create_subscriber' => esc_html__( 'Subscribe to List', 'automate_hub' )
        )
    );
    return $actions;
}

add_filter( 'awp_settings_tabs', 'awp_campaignmonitor_settings_tab', 10, 1 );

function awp_campaignmonitor_settings_tab( $providers ) {
    $providers['campaignmonitor'] = array('name'=>esc_html__( 'Campaign Monitor', 'automate_hub'), 'cat'=>array('esp'));
    return $providers;
}

add_action( 'awp_settings_view', 'awp_campaignmonitor_settings_view', 10, 1 );

function awp_campaignmonitor_settings_view( $current_tab ) {
    if( $current_tab != 'campaignmonitor' ) { return; }
    $nonce     = wp_create_nonce( "awp_campaignmonitor_settings" );
    $id = isset($_GET['id']) ? $_GET['id'] : "";
    $api_key = isset($_GET['api_key']) ? $_GET['api_key'] : "";
    $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";

    ?>
    <div class="platformheader">
    <a href="https://sperse.io/go/campaignmonitor" target="_blank"><img src="<?php echo AWP_ASSETS; ?>/images/logos/campaignmonitor.png" width="350" height="50" alt="Campaign Monitor Logo"></a><br/><br/>
    <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?>
                <br/>
                <?php 

$form_fields = '';
$app_name= 'campaignmonitor';
$campaignmonitor_form = new AWP_Form_Fields($app_name);

$form_fields = $campaignmonitor_form->awp_wp_text_input(
    array(
        'id'            => "awp_campaignmonitor_display_name",
        'name'          => "awp_campaignmonitor_display_name",
        'value'         => $display_name,
        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $campaignmonitor_form->awp_wp_text_input(
    array(
        'id'            => "awp_campaignmonitor_api_token",
        'name'          => "awp_campaignmonitor_api_token",
        'value'         => $api_key,
        'placeholder'   => esc_html__( 'Please enter API Token', 'automate_hub' ),
        'label'         =>  esc_html__( 'Campaign Monitor API Key', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $campaignmonitor_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_save_campaignmonitor_api_token',
    )
);


$form_fields .= $campaignmonitor_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $campaignmonitor_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$campaignmonitor_form->render($form_fields);

?>

    </div>

    <div class="wrap">
        <form id="form-list" method="post">
                    
            
            <input type="hidden" name="page" value="automate_hub"/>

            <?php
            $data=[
                        'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
            $platform_obj= new AWP_Platform_Shell_Table('campaignmonitor');
            $platform_obj->initiate_table($data);
            $platform_obj->prepare_items();
            $platform_obj->display_table();
                    
            ?>
        </form>
    </div>
    <?php
}

add_action( 'admin_post_awp_save_campaignmonitor_api_token', 'awp_save_campaignmonitor_api_token', 10, 0 );

function awp_save_campaignmonitor_api_token() {

    if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }

    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'awp_campaignmonitor_settings' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }

    $api_token   = sanitize_text_field( $_POST["awp_campaignmonitor_api_token"] );
    $display_name     = sanitize_text_field( $_POST["awp_campaignmonitor_display_name"] );
    // Save tokens
    $platform_obj= new AWP_Platform_Shell_Table('campaignmonitor');
    $platform_obj->save_platform(['account_name'=>$display_name,'api_key'=>$api_token]);

    AWP_redirect( "admin.php?page=automate_hub&tab=campaignmonitor" );
}

add_action( 'awp_add_js_fields', 'awp_campaignmonitor_js_fields', 10, 1 );

function awp_campaignmonitor_js_fields( $field_data ) {}

add_action( 'awp_action_fields', 'awp_campaignmonitor_action_fields' );

function awp_campaignmonitor_action_fields() {
    ?>
    <script type="text/template" id="campaignmonitor-action-template">
		        <?php

                    $app_data=array(
                            'app_slug'=>'campaignmonitor',
                           'app_name'=>'Campaign Monitor',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/campaignmonitor.png',
                           'app_icon_alter_text'=>'Campaignmonitor Icon',
                           'account_select_onchange'=>'getCampaignMonitorAccounts',
                           'tasks'=>array(
                                        'create_subscriber'=>array(
                                                            'task_assignments'=>array(

                                                                                    array(
                                                                                        'label'=>'Client',
                                                                                        'type'=>'select',
                                                                                        'name'=>"account",
                                                                                        'model'=>'fielddata.accountId',
                                                                                        'required'=>'true',
                                                                                        'onchange'=>'getList',
                                                                                        'option_for_loop'=>'(item, index) in fielddata.accounts',
                                                                                        'select_default'=>'Select...',
                                                                                        'spinner'=>array(
                                                                                                        'bind-class'=>"{'is-active': accountLoading}",
                                                                                                    )
                                                                                    ),
                                                                                    array(
                                                                                        'label'=>'List',
                                                                                        'type'=>'select',
                                                                                        'name'=>"list",
                                                                                        'model'=>'fielddata.listId',
                                                                                        'required'=>'true',
                                                                                        
                                                                                        'option_for_loop'=>'(item, index) in fielddata.list',
                                                                                        'select_default'=>'Select...',
                                                                                        
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

add_action( 'wp_ajax_awp_get_campaignmonitor_accounts', 'awp_get_campaignmonitor_accounts', 10, 0 );
/*
 * Get Campaign Monitor accounts
 */
function awp_get_campaignmonitor_accounts() {
    
    if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }

    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }
    if (!isset( $_POST['platformid'] ) ) {
        die( esc_html__( 'Invalid Request', 'automate_hub' ) );
    }
    $id=sanitize_text_field($_POST['platformid']);
    $platform_obj= new AWP_Platform_Shell_Table('campaignmonitor');
    $data=$platform_obj->awp_get_platform_detail_by_id($id);
    if(!$data){
        die( esc_html__( 'No Data Found', 'automate_hub' ) );
    }
    $api_token =$data->api_key;



    if( ! $api_token ) {
        return array();
    }

    $url    = "https://api.createsend.com/api/v3.2/clients.json";

    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode( $api_token . ':')
        )
    );

    $accounts = wp_remote_get( $url, $args );

    if( !is_wp_error( $accounts ) ) {
        $body  = json_decode( $accounts["body"] );
        $lists = wp_list_pluck( $body, 'Name', 'ClientID' );

        wp_send_json_success( $lists );
    } else {
        wp_send_json_error();
    }
}

add_action( 'wp_ajax_awp_get_campaignmonitor_list', 'awp_get_campaignmonitor_list', 10, 0 );
/*
 * Get Campaign Monitor accounts
 */
function awp_get_campaignmonitor_list() {
   
    if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }

    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }

    if (!isset( $_POST['platformid'] ) ) {
        die( esc_html__( 'Invalid Request', 'automate_hub' ) );
    }
    $id=sanitize_text_field($_POST['platformid']);
    $platform_obj= new AWP_Platform_Shell_Table('campaignmonitor');
    $data=$platform_obj->awp_get_platform_detail_by_id($id);
    if(!$data){
        die( esc_html__( 'No Data Found', 'automate_hub' ) );
    }
    $api_token =$data->api_key;



    if( ! $api_token ) {
        wp_send_json_error();
    }

    $client = $_POST['accountId'] ? sanitize_text_field( $_POST['accountId'] ) : "";

    if( ! $client ) {
        wp_send_json_error();
    }

    $url    = "https://api.createsend.com/api/v3.2/clients/{$client}/lists.json";

    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode( $api_token . ':')
        )
    );

    $accounts = wp_remote_get( $url, $args );

    if( !is_wp_error( $accounts ) ) {
        $body  = json_decode( $accounts["body"] );
        $lists = wp_list_pluck( $body, 'Name', 'ListID' );

        wp_send_json_success( $lists );
    } else {
        wp_send_json_error();
    }
}

/*
 * Saves connection mapping
 */
function awp_campaignmonitor_save_integration() {
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
 * Handles sending data to Campaign Monitor API
 */
function awp_campaignmonitor_send_data( $record, $posted_data ) {

    $temp    = json_decode( $record["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('campaignmonitor');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_token=$temp->api_key;

    if( !$api_token ) {
        return;
    }

    $data    = json_decode( $record["data"], true );
    $data    = $data["field_data"];
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
    $account = empty( $data["accountId"] ) ? "" : $data["accountId"];
    $list    = empty( $data["listId"] ) ? "" : $data["listId"];
    $email   = empty( $data["email"] ) ? "" : awp_get_parsed_values( $data["email"], $posted_data );
    $name    = empty( $data["name"] ) ? "" : awp_get_parsed_values( $data["name"], $posted_data );
    // Pro
    $custom_fields = empty( $data["customFields"] ) ? "" : awp_get_parsed_values( $data["customFields"], $posted_data );

    if( $task == "create_subscriber" ) {

        $url = "https://api.createsend.com/api/v3.2/subscribers/{$list}.json";

        $body = array(
            "EmailAddress" => $email,
            "Name" => $name,
            "ConsentToTrack" => "Yes"
        );

        // Pro 
        if( $custom_fields ) {
            $holder = explode( ",", $custom_fields );
            $body["customFields"] = array();
            foreach( $holder as $single ) {
                $single = explode( "=", $single );
                array_push( $body["customFields"], array( "key" => $single[0], "value" => $single[1] ) );
            }
        }


        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode( $api_token . ':')
            ),
            'body' => json_encode( $body )
        );

        $response = wp_remote_post( $url, $args );
        $args['headers']['Authorization'] = 'Basic  XXXXXXXXXX';
        $args['body']=$body;
        awp_add_to_log( $response, $url, $args, $record );
    }

    return $response;
}


function awp_campaignmonitor_resend_data($log_id,$data,$integration){
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('campaignmonitor');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_token=$temp->api_key;

    if( !$api_token ) {
        return;
    }

    $task=$integration['task'];
    $data=stripslashes($data);

    $data=preg_replace('/\s+/', '',$data); 

    $data=json_decode($data,true);


    $url=$data['url'];

    if(!$url){
        $response['success']=false;
        $response['msg']="Syntax Error! Request is invalid";
        return $response;
    }
    if( $task == "create_subscriber" ) {

        

        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode( $api_token . ':')
            ),
            'body' => json_encode( $data['args']['body'] )
        );

        $response = wp_remote_post( $url, $args );
        $args['headers']['Authorization'] = 'Basic  XXXXXXXXXX';
        $args['body']=$data['args']['body'];
        awp_add_to_log( $response, $url, $args, $integration );
    }

    $response['success']=true;
    return $response;
}
