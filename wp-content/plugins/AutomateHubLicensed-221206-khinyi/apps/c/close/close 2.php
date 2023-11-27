<?php


add_filter( 'awp_action_providers', 'awp_close_actions', 10, 1 );

function awp_close_actions( $actions ) {
    $actions['close'] = array(
    'title' => esc_html__( 'Close', 'automate_hub'),
    'tasks' => array('add_lead'   => esc_html__( 'Create New Lead', 'automate_hub')));
    return $actions;
}

add_filter( 'awp_settings_tabs', 'awp_close_settings_tab', 10, 1 );

function awp_close_settings_tab( $providers ) {
    $providers['close'] = array('name'=>esc_html__( 'Close', 'automate_hub'), 'cat'=>array('crm'));
    return $providers;
}

add_action( 'awp_settings_view', 'awp_close_settings_view', 10, 1 );

function awp_close_settings_view( $current_tab ) {
    if( $current_tab != 'close' ) { return; }
    $nonce     = wp_create_nonce( "awp_close_settings" );
    $id = isset($_GET['id']) ? $_GET['id'] : "";
    $api_key = isset($_GET['api_key']) ? $_GET['api_key'] : "";
    $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";
    ?>
    <div class="platformheader">
    <a href="https://sperse.io/go/close" target="_blank"><img src="<?php echo(AWP_ASSETS) ?>/images/logos/close.png" width="155" height="50" alt="Close Logo"></a><br/><br/>
    <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?>
                <br/>

    <?php 

                $form_fields = '';
                $app_name= 'close';
                $close_form = new AWP_Form_Fields($app_name);

                $form_fields = $close_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_close_display_name",
                        'name'          => "awp_close_display_name",
                        'value'         => $display_name,
                        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
                        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $close_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_close_api_token",
                        'name'          => "awp_close_api_token",
                        'value'         => $api_key,
                        'placeholder'   => esc_html__( 'Enter your Close CRM API Key', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Close CRM API Key', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $close_form->awp_wp_hidden_input(
                    array(
                        'name'          => "action",
                        'value'         => 'awp_save_close_api_token',
                    )
                );


                $form_fields .= $close_form->awp_wp_hidden_input(
                    array(
                        'name'          => "_nonce",
                        'value'         =>$nonce,
                    )
                );
                $form_fields .= $close_form->awp_wp_hidden_input(
                    array(
                        'name'          => "id",
                        'value'         =>wp_unslash($id),
                    )
                );


                $close_form->render($form_fields);

                ?>
    </div>
    <div class="wrap">
        <form id="form-list" method="post">
                    
            
            <input type="hidden" name="page" value="automate_hub"/>

            <?php
            $data=[
                        'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
            $platform_obj= new AWP_Platform_Shell_Table('close');
            $platform_obj->initiate_table($data);
            $platform_obj->prepare_items();
            $platform_obj->display_table();
                    
            ?>
        </form>
    </div>

    <?php
}

add_action( 'admin_post_awp_save_close_api_token', 'awp_save_close_api_token', 10, 0 );

function awp_save_close_api_token() {
    
        if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }

    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'awp_close_settings' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }
    $api_token = sanitize_text_field( $_POST["awp_close_api_token"] );
    $display_name     = sanitize_text_field( $_POST["awp_close_display_name"] );
    // Save tokens
    $platform_obj= new AWP_Platform_Shell_Table('close');
    $platform_obj->save_platform(['account_name'=>$display_name,'api_key'=>$api_token]);

    AWP_redirect( "admin.php?page=automate_hub&tab=close" );
}
add_action( 'awp_add_js_fields', 'awp_close_js_fields', 10, 1 );

function awp_close_js_fields( $field_data ) {}
add_action( 'awp_action_fields', 'awp_close_action_fields' );

function awp_close_action_fields() { ?>
    <script type="text/template" id="close-action-template">
                <?php
                    $app_data=array(
                            'app_slug'=>'close',
                           'app_name'=>'Close',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/close.png',
                           'app_icon_alter_text'=>'Close Icon',
                           'account_select_onchange'=>'',
                           'tasks'=>array(
                                        'add_lead'=>array(
                                                            
                                                        ),
                                                                                
                                    ),
                        ); 

                        require (AWP_VIEWS.'/awp_app_integration_format.php');
                ?>
    </script>
    <?php
}

/* Saves connection mapping */
function awp_close_save_integration() {
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
                'status'          => 1 ));


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
            array( 'title'           => $integration_title,
                   'form_provider'   => $form_provider_id,
                   'form_id'         => $form_id,
                   'form_name'       => $form_name,
                   'data'            => json_encode( $all_data, true ),),
            array( 'id' => $id )
        );
    }
    $return=array();
    $return['type']=$type;
    $return['result']=$result;
    $return['insertid']=$wpdb->insert_id;
    return $return;
}

/* Handles sending data to close API */
function awp_close_send_data( $record, $posted_data ) {
    $temp    = json_decode( $record["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('close');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_token=$temp->api_key;
    if( !$api_token ) { return; }
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
    if( $task == "add_lead" ) {
        $org_name     = empty( $data["orgName"] ) ? "" : awp_get_parsed_values( $data["orgName"], $posted_data );
        $org_url      = empty( $data["url"] ) ? "" : awp_get_parsed_values( $data["url"], $posted_data );
        $description  = empty( $data["description"] ) ? "" : awp_get_parsed_values( $data["description"], $posted_data );
        $contact_name = empty( $data["contactName"] ) ? "" : awp_get_parsed_values( $data["contactName"], $posted_data );
        $title        = empty( $data["title"] ) ? "" : awp_get_parsed_values( $data["title"], $posted_data );
        $email        = empty( $data["email"] ) ? "" : awp_get_parsed_values( $data["email"], $posted_data );
        $phone        = empty( $data["phone"] ) ? "" : awp_get_parsed_values( $data["phone"], $posted_data );
        $address1     = empty( $data["address1"] ) ? "" : awp_get_parsed_values( $data["address1"], $posted_data );
        $address2     = empty( $data["address2"] ) ? "" : awp_get_parsed_values( $data["address2"], $posted_data );
        $city         = empty( $data["city"] ) ? "" : awp_get_parsed_values( $data["city"], $posted_data );
        $zip          = empty( $data["zip"] ) ? "" : awp_get_parsed_values( $data["zip"], $posted_data );
        $state        = empty( $data["state"] ) ? "" : awp_get_parsed_values( $data["state"], $posted_data );
        $country      = empty( $data["country"] ) ? "" : awp_get_parsed_values( $data["country"], $posted_data );
        $url          = "https://api.close.com/api/v1/lead/";
        $headers = array('Content-Type' => 'application/json', 'Authorization' => 'Basic ' . base64_encode( $api_token . ':' ));
        $body =  array(
            "name"        => $org_name,
            "url"         => $org_url,
            "description" => $description,
            "contacts" => array(
            array( "name"   => $contact_name,
                   "title"  => $title,
                   "emails" => array(array("type" => "office", "email" => $email)),
                   "phones" => array(array("type" => "office", "phone" => $phone)))
            ),
            "addresses" => array(
            array( "label"     => "business",
                   "address_1" => $address1,
                   "address_2" => $address2,
                   "city"      => $city,
                   "state"     => $state,
                   "zipcode"   => $zip,
                   "country"   => $country )));
        $body_temp = json_encode($body, true);
        $args = array("headers" => $headers, "body" => $body_temp );
        $response = wp_remote_post( $url, $args);
        $args['headers']['Authorization'] = 'Basic XXXXXXXX';
        $args['body']=$body;
        awp_add_to_log( $response, $url, $args, $record );
    }
    return $response;
}

function awp_close_resend_data($log_id,$data,$integration){
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('close');
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
    if( $task == "add_lead" ) {
        

        $headers = array('Content-Type' => 'application/json', 'Authorization' => 'Basic ' . base64_encode( $api_token . ':' ));
        

        $args = array(
            "headers" => $headers,
            "body"    => json_encode($data['args']['body'])
        );
        $response = wp_remote_post( $url, $args );
        $args['headers']['Authorization'] = 'Basic XXXXXXXXXX';
        $args['body']=$data['args']['body'];
        awp_add_to_log( $response, $url, $args, $integration );
    }
    $response['success']=true;
    return $response;
}
