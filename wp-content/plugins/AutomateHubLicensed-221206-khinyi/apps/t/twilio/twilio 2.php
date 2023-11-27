<?php

add_filter( 'awp_action_providers', 'awp_twilio_actions', 10, 1 );

function awp_twilio_actions( $actions ) {
    $actions['twilio'] = array(
        'title' => esc_html__( 'Twilio', 'automate_hub' ),
        'tasks' => array('subscribe'   => esc_html__( 'Send SMS', 'automate_hub'))
    );
    return $actions;
}

add_filter( 'awp_settings_tabs', 'awp_twilio_settings_tab', 10, 1 );

function awp_twilio_settings_tab( $providers ) {
    $providers['twilio'] = array('name'=>esc_html__( 'Twilio', 'automate_hub'), 'cat'=>array('sms'));
    return $providers;
}

add_action( 'awp_settings_view', 'awp_twilio_settings_view', 10, 1 );

function awp_twilio_settings_view( $current_tab ) {
    if( $current_tab != 'twilio' ) { return; }
    $nonce       = wp_create_nonce( "awp_twilio_settings" );
    $id = isset($_GET['id']) ? $_GET['id'] : "";
    $client_id = isset($_GET['client_id']) ? $_GET['client_id'] : "";
    $client_secret = isset($_GET['client_secret']) ? $_GET['client_secret'] : "";
    $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";
    ?>
    <div class="platformheader">
    <a href="https://sperse.io/go/twilio" target="_blank"><img src="<?php echo AWP_ASSETS; ?>/images/logos/twilio.png" width="165" height="50" alt="Twilio Logo"></a><br/><br/>
    <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?>
    <br/>
    <?php 

$form_fields = '';
$app_name= 'twilio';
$twilio_form = new AWP_Form_Fields($app_name);

$form_fields = $twilio_form->awp_wp_text_input(
    array(
        'id'            => "awp_twilio_display_name",
        'name'          => "awp_twilio_display_name",
        'value'         => $display_name,
        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $twilio_form->awp_wp_text_input(
    array(
        'id'            => "awp_twilio_account_sid",
        'name'          => "awp_twilio_account_sid",
        'value'         => $client_id,
        'placeholder'   => esc_html__( 'Enter Twilio Account SID', 'automate_hub' ),
        'label'         =>  esc_html__( 'Twilio Account SID', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);
$form_fields .= $twilio_form->awp_wp_text_input(
    array(
        'id'            => "awp_twilio_account_sid",
        'name'          => "awp_twilio_account_sid",
        'value'         => $client_id,
        'placeholder'   => esc_html__( 'Enter Twilio Account SID', 'automate_hub' ),
        'label'         =>  esc_html__( 'Twilio Account SID', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);
$form_fields .= $twilio_form->awp_wp_text_input(
    array(
        'id'            => "awp_twilio_auth_token",
        'name'          => "awp_twilio_auth_token",
        'value'         => $client_secret,
        'placeholder'   => esc_html__( 'Enter Twilio Auth Token', 'automate_hub' ),
        'label'         =>  esc_html__( 'Twilio Auth Token', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $twilio_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_save_twilio_api_key',
    )
);


$form_fields .= $twilio_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $twilio_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$twilio_form->render($form_fields);

?>
    </div>
    <div class="wrap">
        <form id="form-list" method="post">
                    
            
            <input type="hidden" name="page" value="automate_hub"/>

            <?php
            $data=[
                        'table-cols'=>['account_name'=>'Display name','client_id'=>'Account SID','client_secret'=>'Auth Token','spots'=>'Active Spots','active_status'=>'Active']
                ];
            $platform_obj= new AWP_Platform_Shell_Table('twilio');
            $platform_obj->initiate_table($data);
            $platform_obj->prepare_items();
            $platform_obj->display_table();
                    
            ?>
        </form>
    </div>
    <?php
}

add_action( 'admin_post_awp_save_twilio_api_key', 'awp_save_twilio_api_key', 10, 0 );

function awp_save_twilio_api_key() {

      if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'awp_twilio_settings' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }

    $account_sid = sanitize_text_field( $_POST["awp_twilio_account_sid"] );
    $auth_token  = sanitize_text_field( $_POST["awp_twilio_auth_token"] );
    $display_name     = sanitize_text_field( $_POST["awp_twilio_display_name"] );

    // Save tokens
    $platform_obj= new AWP_Platform_Shell_Table('twilio');
    $platform_obj->save_platform(['account_name'=>$display_name,'client_id'=>$account_sid,'client_secret'=>$auth_token]);

    AWP_redirect( "admin.php?page=automate_hub&tab=twilio" );
}

add_action( 'awp_add_js_fields', 'awp_twilio_js_fields', 10, 1 );

function awp_twilio_js_fields( $field_data ) { }

add_action( 'awp_action_fields', 'awp_twilio_action_fields' );

function awp_twilio_action_fields() {
    ?>
    <script type="text/template" id="twilio-action-template">
		        <?php

                    $app_data=array(
                            'app_slug'=>'twilio',
                           'app_name'=>'Twilio',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/twilio.png',
                           'app_icon_alter_text'=>'Twilio Icon',
                           'account_select_onchange'=>'getTwilioList',
                           'tasks'=>array(
                                        'subscribe'=>array(
                                                            'task_assignments'=>array(

                                                                                    array(
                                                                                        'label'=>'From',
                                                                                        'type'=>'select',
                                                                                        'name'=>"from",
                                                                                        'model'=>'fielddata.from',
                                                                                        'required'=>'required',
                                                                                        'onchange'=>'',
                                                                                        'select_default'=>'Select Number...',
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

add_action( 'wp_ajax_awp_get_twilio_list', 'awp_get_twilio_list', 10, 0 );

/*
 * Get Mailchimp subscriber lists
 */
function awp_get_twilio_list() {

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
    $platform_obj= new AWP_Platform_Shell_Table('twilio');
    $data=$platform_obj->awp_get_platform_detail_by_id($id);
    if(!$data){
        die( esc_html__( 'No Data Found', 'automate_hub' ) );
    }
    $account_sid =$data->client_id;
    $auth_token =$data->client_secret;


    if( !$account_sid || !$auth_token ) {
        return array();
    }

    $url = "https://api.twilio.com/2010-04-01/Accounts/{$account_sid}/IncomingPhoneNumbers.json";

    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode( $account_sid . ':' . $auth_token )
        )
    );
    $data  = wp_remote_get( $url, $args );

    if( !is_wp_error( $data ) ) {
        $body  = json_decode( $data["body"] );

        $lists = wp_list_pluck( $body->incoming_phone_numbers, 'phone_number', 'phone_number' );

        wp_send_json_success( $lists );
    } else {
        wp_send_json_error();
    }
}

/*
 * Saves connection mapping
 */
function awp_twilio_save_integration() {
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
 * Handles sending data to Mailchimp API
 */
function awp_twilio_send_data( $record, $posted_data ) {
    $temp    = json_decode( $record["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('twilio');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $account_sid =$temp->client_id;
    $auth_token =$temp->client_secret;

    if(!$account_sid || !$auth_token ) {
        return;
    }

    $record_data = json_decode( $record["data"], true );

    if( array_key_exists( "cl", $record_data["action_data"]) ) {
        if( $record_data["action_data"]["cl"]["active"] == "yes" ) {
            if( !awp_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data ) ) {
                return;
            }
        }
    }

    $data    = $record_data["field_data"];
//    $list_id = $data["listId"];
    $task    = $record["task"];

    if( $task == "subscribe" ) {
        $from = empty( $data["from"] ) ? "" : awp_get_parsed_values( $data["from"], $posted_data );
        $to   = empty( $data["to"] ) ? "" : awp_get_parsed_values( $data["to"], $posted_data );
        $body = empty( $data["body"] ) ? "" : awp_get_parsed_values( $data["body"], $posted_data );

        $url = "https://api.twilio.com/2010-04-01/Accounts/{$account_sid}/Messages.json";

        $sms_data = array(
            'From' => $from,
            'To'   => $to,
            'Body' => $body
        );

        $args = array(

            'headers' => array(
                'Content-Type'  => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic ' . base64_encode( $account_sid . ':' . $auth_token )
            ),
            'body' => $sms_data
        );

        $return = wp_remote_post( $url, $args );
        $args['headers']['Authorization']='Basic XXXXXXXXXX';
        awp_add_to_log( $return, $url, $args, $record );

        return $return;
    }
}


function awp_twilio_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('twilio');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $account_sid =$temp->client_id;
    $auth_token =$temp->client_secret;
    

    if(!$account_sid || !$auth_token ) {
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

    if( $task == "subscribe" ) {
        

        

        $args = array(

            'headers' => array(
                'Content-Type'  => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic ' . base64_encode( $account_sid . ':' . $auth_token )
            ),
            'body' => $data['args']['body']
        );

        $return = wp_remote_post( $url, $args );
        $args['headers']['Authorization']='Basic XXXXXXXXXX';
        awp_add_to_log( $return, $url, $args, $integration );

    }
    $response['success']=true;
    return $response;
}
