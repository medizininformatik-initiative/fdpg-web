<?php

add_filter( 'awp_action_providers', 'awp_everwebinar_actions', 10, 1 );
function awp_everwebinar_actions( $actions ) {
    $actions['everwebinar'] = array(
        'title' => esc_html__( 'EverWebinar', 'automate_hub' ),
        'tasks' => array('register_webinar' => esc_html__( 'Register to webinar', 'automate_hub'))
    );
    return $actions;
}
add_filter( 'awp_settings_tabs', 'awp_everwebinar_settings_tab', 10, 1 );
function awp_everwebinar_settings_tab( $providers ) {
    $providers['everwebinar'] = 
    array('name'=>esc_html__( 'EverWebinar', 'automate_hub'), 'cat'=>array('webinar'));
    return $providers;
}
add_action( 'awp_settings_view', 'awp_everwebinar_settings_view', 10, 1 );
function awp_everwebinar_settings_view( $current_tab ) {
    if( $current_tab != 'everwebinar' ) { return; }
    $nonce     = wp_create_nonce( "awp_everwebinar_settings" );
    $id = isset($_GET['id']) ? $_GET['id'] : "";
    $api_key = isset($_GET['api_key']) ? $_GET['api_key'] : "";
    $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";
    ?>
    <div class="platformheader">
	<a href="https://sperse.io/go/everwebinar" target="_blank"><img src="<?php echo(AWP_ASSETS) ?>/images/logos/everwebinar.png" width="250" height="50" alt="Everwebinar Logo"></a><br/><br/>
	<?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?>
                <br/>
                <?php 

$form_fields = '';
$app_name= 'everwebinar';
$everwebinar_form = new AWP_Form_Fields($app_name);

$form_fields = $everwebinar_form->awp_wp_text_input(
    array(
        'id'            => "awp_everwebinar_display_name",
        'name'          => "awp_everwebinar_display_name",
        'value'         => $display_name,
        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $everwebinar_form->awp_wp_text_input(
    array(
        'id'            => "awp_everwebinar_api_token",
        'name'          => "awp_everwebinar_api_token",
        'value'         => $api_key,
        'placeholder'   => esc_html__( 'Please enter API Token', 'automate_hub' ),
        'label'         =>  esc_html__( 'API Token', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $everwebinar_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_save_everwebinar_api_token',
    )
);


$form_fields .= $everwebinar_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $everwebinar_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$everwebinar_form->render($form_fields);

?>


    </div>

    <div class="wrap">
        <form id="form-list" method="post">
                    
            
            <input type="hidden" name="page" value="automate_hub"/>

            <?php
            $data=[
                        'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
            $platform_obj= new AWP_Platform_Shell_Table('everwebinar');
            $platform_obj->initiate_table($data);
            $platform_obj->prepare_items();
            $platform_obj->display_table();
                    
            ?>
        </form>
    </div>

    <?php
}

add_action( 'admin_post_awp_save_everwebinar_api_token', 'awp_save_everwebinar_api_token', 10, 0 );

function awp_save_everwebinar_api_token() {
    
    if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }

    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'awp_everwebinar_settings' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }

    
    $api_token   = sanitize_text_field( $_POST["awp_everwebinar_api_token"] );
    $display_name     = sanitize_text_field( $_POST["awp_everwebinar_display_name"] );

    // Save tokens
    $platform_obj= new AWP_Platform_Shell_Table('everwebinar');
    $platform_obj->save_platform(['account_name'=>$display_name,'api_key'=>$api_token]);
   
    AWP_redirect( "admin.php?page=automate_hub&tab=everwebinar" );
}

add_action( 'awp_add_js_fields', 'awp_everwebinar_js_fields', 10, 1 );

function awp_everwebinar_js_fields( $field_data ) {}

add_action( 'awp_action_fields', 'awp_everwebinar_action_fields' );

function awp_everwebinar_action_fields() {
    ?>

    <script type="text/template" id="everwebinar-action-template">
		        <?php

                    $app_data=array(
                            'app_slug'=>'everwebinar',
                           'app_name'=>'EverWebinar',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/everwebinar.png',
                           'app_icon_alter_text'=>'EverWebinar Icon',
                           'account_select_onchange'=>'getEverwebinarList',
                           'tasks'=>array(
                                        'register_webinar'=>array(
                                                            'task_assignments'=>array(

                                                                                    array(
                                                                                        'label'=>'Webinar',
                                                                                        'type'=>'select',
                                                                                        'name'=>"webinar",
                                                                                        'model'=>'fielddata.webinarId',
                                                                                        'required'=>'required',
                                                                                        'onchange'=>'getSchedule',
                                                                                        'select_default'=>'Select Webinar...',
                                                                                        'option_for_loop'=>'(item, index) in fielddata.webinars',
                                                                                        'spinner'=>array(
                                                                                                        'bind-class'=>"{'is-active': webinarLoading}",
                                                                                                    )
                                                                                    ),
                                                                                    array(
                                                                                        'label'=>'Schedule',
                                                                                        'type'=>'select',
                                                                                        'name'=>"schedule",
                                                                                        'model'=>'fielddata.scheduleId', 
                                                                                        'required'=>'required',                     
                                                                                        'select_default'=>'Select Schedule...',
                                                                                        'option_for_loop'=>'(item, index) in fielddata.schedules',
                                                                                       
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

add_action( 'wp_ajax_awp_get_everwebinar_webinars', 'awp_get_everwebinar_webinars', 10, 0 );
/*
 * Get Drip accounts
 */
function awp_get_everwebinar_webinars() {
   
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
    $platform_obj= new AWP_Platform_Shell_Table('everwebinar');
    $data=$platform_obj->awp_get_platform_detail_by_id($id);
    if(!$data){
        die( esc_html__( 'No Data Found', 'automate_hub' ) );
    }
    $api_token =$data->api_key;

    if( ! $api_token ) {
        return array();
    }

    $url    = "https://api.webinarjam.com/everwebinar/webinars";

    $args = array(
        'method' => 'POST',
        'headers' => array(
            'Content-Type' => 'application/x-www-form-urlencoded',
        ),
        'body' => array(
            'api_key' => $api_token
        )
    );

    $accounts = wp_remote_request( $url, $args );

    if( !is_wp_error( $accounts ) ) {
        $body  = json_decode( $accounts["body"] );
        $lists = wp_list_pluck( $body->webinars, 'name', 'webinar_id' );

        wp_send_json_success( $lists );
    } else {
        wp_send_json_error();
    }
}

add_action( 'wp_ajax_awp_get_everwebinar_schedules', 'awp_get_everwebinar_schedules', 10, 0 );
/*
 * Get Drip list
 */
function awp_get_everwebinar_schedules() {
    
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
    $platform_obj= new AWP_Platform_Shell_Table('everwebinar');
    $data=$platform_obj->awp_get_platform_detail_by_id($id);
    if(!$data){
        die( esc_html__( 'No Data Found', 'automate_hub' ) );
    }
    $api_token =$data->api_key;
    
    if( ! $api_token ) {
        wp_send_json_error();
    }

    $webinar_id = $_POST["webinarId"] ? sanitize_text_field( $_POST["webinarId"] ) : "";

    if( ! $webinar_id ) {
        wp_send_json_error();
    }

    $url    = "https://api.webinarjam.com/everwebinar/webinar";

    $args = array(
        'method'  => 'POST',
        'headers' => array(
            'Content-Type' => 'application/x-www-form-urlencoded',
        ),
        'body' => array(
            'api_key'    => $api_token,
            'webinar_id' => $webinar_id
        )
    );

    $webinars = wp_remote_request( $url, $args );

    if( !is_wp_error( $webinars ) ) {
        $body  = json_decode( $webinars["body"] );
        $schedules = wp_list_pluck( $body->webinar->schedules, 'date', 'schedule' );

        wp_send_json_success( $schedules );
    } else {
        wp_send_json_error();
    }
}

/*
 * Saves connection mapping
 */
function awp_everwebinar_save_integration() {
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
 * Handles sending data to Drip API
 */
function awp_everwebinar_send_data( $record, $posted_data ) {

    $temp    = json_decode( $record["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('everwebinar');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_token=$temp->api_key;

    if( !$api_token ) {
        return;
    }

    $record_data = json_decode( $record["data"], true );

    if( array_key_exists( "cl", $record_data["action_data"] ) ) {
        if( $record_data["action_data"]["cl"]["active"] == "yes" ) {
            if( !awp_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data ) ) {
                return;
            }
        }
    }

    $data         = $record_data["field_data"];
    $task         = $record["task"];
    $webinar_id   = empty( $data["webinarId"] ) ? "" : $data["webinarId"];
    $schedule_id  = empty( $data["scheduleId"] ) ? "" : $data["scheduleId"];
    $email        = empty( $data["email"] ) ? "" : awp_get_parsed_values( $data["email"], $posted_data );
    $first_name   = empty( $data["firstName"] ) ? "" : awp_get_parsed_values( $data["firstName"], $posted_data );
    $last_name    = empty( $data["lastName"] ) ? "" : awp_get_parsed_values( $data["lastName"], $posted_data );
    $ip_address   = empty( $data["ipAddress"] ) ? "" : awp_get_parsed_values( $data["ipAddress"], $posted_data );
    $country_code = empty( $data["phoneCountryCode"] ) ? "" : awp_get_parsed_values( $data["phoneCountryCode"], $posted_data );
    $phone        = empty( $data["phone"] ) ? "" : awp_get_parsed_values( $data["phone"], $posted_data );
    $timezone     = empty( $data["timezone"] ) ? "" : awp_get_parsed_values( $data["timezone"], $posted_data );
    $date         = empty( $data["date"] ) ? "" : awp_get_parsed_values( $data["date"], $posted_data );

    if( $task == "register_webinar" ) {

        $url = "https://api.webinarjam.com/everwebinar/register";

        $args = array(
            'method'  => 'POST',
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded',
            ),
            'body' => array(
                'api_key'            => $api_token,
                'webinar_id'         => $webinar_id,
                'schedule'           => $schedule_id,
                'email'              => $email,
                'first_name'         => $first_name,
                'last_name'          => $last_name,
                'phone_country_code' => $country_code,
                'phone'              => $phone,
                'ip_address'         => $ip_address,
                'timezone'           => $timezone,
                'date'               => $date,
            )
        );

        $response = wp_remote_request( $url, $args );
        $args['body']['api_key'] = 'XXXXXXXXXX';
        // addtional handling needed to log errors as the page is redirecting on false requests
        if( !empty($response["response"]["code"]) && $response["response"]["code"]==302 || $response["response"]["code"]==405 ){
            $response["body"]="Invalid request";
        }
        awp_add_to_log( $response, $url, $args, $record );
    }

    return $response;
}

