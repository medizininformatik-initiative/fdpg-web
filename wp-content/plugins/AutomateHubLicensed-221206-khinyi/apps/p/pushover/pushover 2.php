<?php

add_filter( 'awp_action_providers', 'awp_pushover_actions', 10, 1 );
function awp_pushover_actions( $actions ) {
    $actions['pushover'] = array(
        'title' => esc_html__( 'Pushover', 'automate_hub' ),
        'tasks' => array('push'   => esc_html__( 'Send Push Message', 'automate_hub'))
    );
    return $actions;
}

add_filter( 'awp_settings_tabs', 'awp_pushover_settings_tab', 10, 1 );
function awp_pushover_settings_tab( $providers ) {
    $providers['pushover'] =  array('name'=>esc_html__( 'Pushover', 'automate_hub'), 'cat'=>array('crm'));
    return $providers;
}
add_action( 'awp_settings_view', 'awp_pushover_settings_view', 10, 1 );

function awp_pushover_settings_view( $current_tab ) {
    if( $current_tab != 'pushover' ) { return; }
    $nonce   = wp_create_nonce( "awp_pushover_settings" );
    $id = isset($_GET['id']) ? $_GET['id'] : "";
    $api_key = isset($_GET['api_key']) ? $_GET['api_key'] : "";
    $email     = isset($_GET['email']) ? $_GET['email'] : "";
    $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";
    ?>
    <div class="platformheader">
    <a href="https://sperse.io/go/pushover" target="_blank"><img src="<?php echo AWP_ASSETS; ?>/images/logos/pushover.png" width="220" height="50" alt="Pushover Logo"></a><br/><br/>
    <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?>
                <br/>
                <?php 

$form_fields = '';
$app_name= 'pushover';
$pushover_form = new AWP_Form_Fields($app_name);

$form_fields = $pushover_form->awp_wp_text_input(
    array(
        'id'            => "awp_pushover_display_name",
        'name'          => "awp_pushover_display_name",
        'value'         => $display_name,
        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $pushover_form->awp_wp_text_input(
    array(
        'id'            => "awp_pushover_user_key",
        'name'          => "awp_pushover_user_key",
        'value'         => $email,
        'placeholder'   => esc_html__( 'Enter your Pushover User Key', 'automate_hub' ),
        'label'         =>  esc_html__( 'User Key', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);
$form_fields .= $pushover_form->awp_wp_text_input(
    array(
        'id'            => "awp_pushover_api_token",
        'name'          => "awp_pushover_api_token",
        'value'         => $api_key,
        'placeholder'   => esc_html__( 'Please enter API Token', 'automate_hub' ),
        'label'         =>  esc_html__( 'API Token', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $pushover_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_save_pushover_api_key',
    )
);


$form_fields .= $pushover_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $pushover_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$pushover_form->render($form_fields);

?>
    </div>   
    <div class="wrap">
        <form id="form-list" method="post">
                    
            
            <input type="hidden" name="page" value="automate_hub"/>

            <?php
            $data=[
                        'table-cols'=>['account_name'=>'Display name','email'=>'User Key','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
            $platform_obj= new AWP_Platform_Shell_Table('pushover');
            $platform_obj->initiate_table($data);
            $platform_obj->prepare_items();
            $platform_obj->display_table();
                    
            ?>
        </form>
    </div>
    <?php
}

add_action( 'admin_post_awp_save_pushover_api_key', 'awp_save_pushover_api_key', 10, 0 );

function awp_save_pushover_api_key() {

      if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'awp_pushover_settings' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }

    $user_key  = sanitize_text_field( $_POST["awp_pushover_user_key"] );
    $api_token = sanitize_text_field( $_POST["awp_pushover_api_token"] );
    $display_name     = sanitize_text_field( $_POST["awp_pushover_display_name"] );

    // Save tokens
    $platform_obj= new AWP_Platform_Shell_Table('pushover');
    $platform_obj->save_platform(['account_name'=>$display_name,'api_key'=>$api_token,'email'=>$user_key]);

    AWP_redirect( "admin.php?page=automate_hub&tab=pushover" );
}

add_action( 'awp_add_js_fields', 'awp_pushover_js_fields', 10, 1 );

function awp_pushover_js_fields( $field_data ) { }

add_action( 'awp_action_fields', 'awp_pushover_action_fields' );

function awp_pushover_action_fields() {
    ?>
    <script type="text/template" id="pushover-action-template">
		        <?php

                    $app_data=array(
                            'app_slug'=>'pushover',
                           'app_name'=>'Pushover',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/pushover.png',
                           'app_icon_alter_text'=>'Pushover Icon',
                           'account_select_onchange'=>'',
                           'tasks'=>array(
                                        'push'=>array(

                                                        ),

                                    ),
                        ); 

                        require (AWP_VIEWS.'/awp_app_integration_format.php');
                ?>
    </script>


    <?php
}

add_action( 'wp_ajax_awp_get_pushover_list', 'awp_get_pushover_list', 10, 0 );

/*
 * Saves connection mapping
 */
function awp_pushover_save_integration() {
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
function awp_pushover_send_data( $record, $posted_data ) {
    $temp    = json_decode( $record["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('pushover');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_token=$temp->api_key;
    $user_key=$temp->email;


    if( !$user_key || !$api_token ) {
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
    $task    = $record["task"];
    $title   = empty( $data["title"] ) ? "" : awp_get_parsed_values($data["title"], $posted_data );
    $message = empty( $data["message"] ) ? "" : awp_get_parsed_values($data["message"], $posted_data );
    $device  = empty( $data["device"] ) ? "" : awp_get_parsed_values($data["device"], $posted_data );

    if( $task == "push" ) {

        $request_data = array(
            "user"    => $user_key,
            "token"   => $api_token,
            "title"   => $title,
            "message" => $message,
            "device"  => $device
        );

//        $query = http_build_query( $request_data );
        $url   = "https://api.pushover.net/1/messages.json";

        $args = array(

            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded'
            ),
            'body' => $request_data
        );

        $return = wp_remote_post( $url, $args );
        $args['body']['token'] = 'XXXXXXXXXX';
        awp_add_to_log( $return, $url, $args, $record );

        return $return;
    }
}

function awp_pushover_resend_data( $log_id,$data,$integration) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('pushover');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_token=$temp->api_key;
    $user_key=$temp->email;
    

    if( !$user_key || !$api_token ) {
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

    if( $task == "push" ) {


        $data['args']['body']['token']=$api_token;


        $args = array(

            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded'
            ),
            'body' => $data['args']['body']
        );

        $return = wp_remote_post( $url, $args );
        $args['body']['token'] = 'XXXXXXXXXX';
        awp_add_to_log( $return, $url, $args, $integration );

        
    }
    $response['success']=true;
    return $response;
}
