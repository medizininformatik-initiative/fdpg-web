<?php

add_filter( 'awp_action_providers', 'awp_hubspot_actions', 10, 1 );
function awp_hubspot_actions( $actions ) {
    $actions['hubspot'] = array(
        'title' => __( 'Hubspot', 'automate_hub' ),
        'tasks' => array('add_contact'   => __( 'Create New Contact', 'automate_hub'))
    );
    return $actions;
}

add_filter( 'awp_settings_tabs', 'awp_hubspot_settings_tab', 10, 1 );
function awp_hubspot_settings_tab( $providers ) {
    $providers['hubspot'] = array('name'=>esc_html__( 'Hubspot', 'automate_hub'), 'cat'=>array('crm'));
    return $providers;
}

add_action( 'awp_settings_view', 'awp_hubspot_settings_view', 10, 1 );
function awp_hubspot_settings_view( $current_tab ) {
    if( $current_tab != 'hubspot' ) {
        return;
    }
   // $nonce     = wp_create_nonce( "awp_hubspot_settings" );
    $id = isset($_GET['id']) ? intval( sanitize_text_field($_GET['id'] )) : "";
    $api_key = isset($_GET['api_key']) ? sanitize_text_field($_GET['api_key']) : "";
    $display_name     = isset($_GET['account_name']) ? sanitize_text_field($_GET['account_name']) : "";
    ?>
    <div class="platformheader">
    <a href="https://sperse.io/go/hubspot" target="_blank"><img src="<?php echo esc_url(AWP_ASSETS.'/images/logos/hubspot.png'); ?>" width="170" height="50" alt="Hubspot Logo"></a><br/><br/>
    <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager(sanitize_text_field($_GET['tab']));
                    $instruction_obj->prepare_instructions();
                        
                ?>
                <br/>
                <?php 

$form_fields = '';
$app_name= 'hubspot';
$hubspot_form = new AWP_Form_Fields($app_name);

$form_fields = $hubspot_form->awp_wp_text_input(
    array(
        'id'            => "awp_hubspot_display_name",
        'name'          => "awp_hubspot_display_name",
        'value'         => $display_name,
        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $hubspot_form->awp_wp_text_input(
    array(
        'id'            => "awp_hubspot_api_token",
        'name'          => "awp_hubspot_api_token",
        'value'         => $api_key,
        'placeholder'   => esc_html__( 'Enter your Hubspot API Token', 'automate_hub' ),
        'label'         =>  esc_html__( 'Hubspot API Token', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $hubspot_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_save_hubspot_api_token',
    )
);


$form_fields .= $hubspot_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $hubspot_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$hubspot_form->render($form_fields);

?>

    </div>
    <div class="wrap">
        <form id="form-list" method="post">
                    
            
            <input type="hidden" name="page" value="automate_hub"/>

            <?php
            $data=[
                        'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
            $platform_obj= new AWP_Platform_Shell_Table('hubspot');
            $platform_obj->initiate_table($data);
            $platform_obj->prepare_items();
            $platform_obj->display_table();
                    
            ?>
        </form>
    </div>
    <?php
}

add_action( 'admin_post_awp_save_hubspot_api_token', 'awp_save_hubspot_api_token', 10, 0 );
function awp_save_hubspot_api_token() {
    if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'awp_hubspot_settings' ) ) {
        die( __( 'Security check Failed', 'automate_hub' ) );
    }
    $api_token = isset($_POST["awp_hubspot_api_token"]) ?  sanitize_text_field( $_POST["awp_hubspot_api_token"] ):'';
    $display_name     = isset($_POST["awp_hubspot_display_name"]) ? sanitize_text_field( $_POST["awp_hubspot_display_name"] ) : '';
    // Save tokens
    $platform_obj= new AWP_Platform_Shell_Table('hubspot');
    $platform_obj->save_platform(['account_name'=>$display_name,'api_key'=>$api_token]);
    AWP_redirect( "admin.php?page=automate_hub&tab=hubspot");
}

add_action( 'awp_add_js_fields', 'awp_hubspot_js_fields', 10, 1 );
function awp_hubspot_js_fields( $field_data ) {}

add_action( 'awp_action_fields', 'awp_hubspot_action_fields', 10, 1 );
function awp_hubspot_action_fields() {
    ?>
    <script type="text/template" id="hubspot-action-template">
<?php
                    $app_data=array(
                            'app_slug'=>'hubspot',
                           'app_name'=>'Hubspot',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/hubspot.png',
                           'app_icon_alter_text'=>'Hubspot Icon',
                           'account_select_onchange'=>'',
                           'tasks'=>array(
                                        'add_contact'=>array(
                                                            
                                                        ),
                                                                                
                                    ),
                        ); 

                        require (AWP_VIEWS.'/awp_app_integration_format.php');
        ?>


    </script>    
    <?php
}

add_action( 'wp_ajax_awp_get_hubspot_contact_fields', 'awp_get_hubspot_contact_fields', 10, 0 );
/* Get hubspot Person Fields */
function awp_get_hubspot_contact_fields() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
        die( __( 'Security check Failed', 'automate_hub' ) );
    }
    if (!isset( $_POST['platformid'] ) ) {
        die( esc_html__( 'Invalid Request', 'automate_hub' ) );
    }
    $id=sanitize_text_field($_POST['platformid']);
    $platform_obj= new AWP_Platform_Shell_Table('hubspot');
    $data=$platform_obj->awp_get_platform_detail_by_id($id);
    if(!$data){
        die( esc_html__( 'No Data Found', 'automate_hub' ) );
    }
    $api_token =$data->api_key;

  
    if( ! $api_token ) {
        return array();
    }
    $contact_fields = array();
    $args = array('headers' => array('Content-Type' => 'application/json'));
    $url = "https://api.hubapi.com/properties/v1/contacts/properties?hapikey=" . $api_token;
    $data      = wp_remote_request( $url, $args );
    if( is_wp_error( $data ) ) {
        wp_snd_json_error();
    }
    $body = json_decode( $data["body"] );
    if( is_array( $body ) ) {
        foreach( $body as $single ) {
            if( false == $single->readOnlyValue ) {
                $description = $single->description;

                if( $single->options ) {
                    if( is_array( $single->options ) ) {
                        $description .= " Possible values are: ";
                        $values = wp_list_pluck( $single->options, 'value' );
                        $description .= implode( ' | ', $values );
                    }
                }
                array_push( $contact_fields, array( 'key' => $single->name, 'value' => $single->label, 'description' => $description ) );
            }
        }
    }
    wp_send_json_success( $contact_fields );
}

/* Saves connection mapping */
function awp_hubspot_save_integration() {
    $params = array();
    parse_str( awp_sanitize_text_or_array_field( $_POST['formData'] ), $params );
    $trigger_data      = isset( $_POST["triggerData" ]) ? awp_sanitize_text_or_array_field( $_POST["triggerData"]) : array();
    $action_data       = isset( $_POST["actionData"  ]) ? awp_sanitize_text_or_array_field( $_POST["actionData" ]) : array();
    $field_data        = isset( $_POST["fieldData"   ]) ? awp_sanitize_text_or_array_field( $_POST["fieldData"  ]) : array();
    $integration_title = isset( $trigger_data["integrationTitle" ]) ? sanitize_text_field($trigger_data["integrationTitle"]) : "";
    $form_provider_id  = isset( $trigger_data["formProviderId"   ]) ? sanitize_text_field($trigger_data["formProviderId"  ]) : "";
    $form_id           = isset( $trigger_data["formId"           ]) ? sanitize_text_field($trigger_data["formId"          ]) : "";
    $form_name         = isset( $trigger_data["formName"         ]) ? sanitize_text_field($trigger_data["formName"        ]) : "";
    $action_provider   = isset( $action_data["actionProviderId"  ]) ? sanitize_text_field($action_data ["actionProviderId"]) : "";

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
            $platform_obj->awp_add_new_spot($wpdb->insert_id,$field_data['activePlatformId']);
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

/* Handles sending data to Hubspot API */
function awp_hubspot_send_data( $record, $posted_data ) {
    $temp    = json_decode( $record["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('hubspot');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_token=$temp->api_key;

    if( !$api_token ) {
        return;
    }
    $record_data      = json_decode( $record["data"], true );
    if( array_key_exists( "cl", $record_data["action_data"] ) ) {
        if( $record_data["action_data"]["cl"]["active"] == "yes" ) {
            if( !awp_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data ) ) {
                return;
            }
        }
    }
    $data      = $record_data["field_data"];
    $task      = $record["task"];

        $email      = empty( $data["email"] ) ? "" : awp_get_parsed_values( $data["email"], $posted_data );
        $first_name = empty( $data["firstName"] ) ? "" : awp_get_parsed_values( $data["firstName"], $posted_data );
        $last_name  = empty( $data["lastName"] ) ? "" : awp_get_parsed_values( $data["lastName"], $posted_data );
        $phone  = empty( $data["phone"] ) ? "" : awp_get_parsed_values( $data["phone"], $posted_data );
        $website  = empty( $data["website"] ) ? "example.com" : awp_get_parsed_values( $data["website"], $posted_data );
        $company  = empty( $data["company"] ) ? "" : awp_get_parsed_values( $data["company"], $posted_data );




    if( $task == "add_contact" ) {

        $properties = array('properties'=> array(
            'company'=>$company,
            'email'=>$email,
            'firstname'=>$first_name,
            'lastname'=>$last_name,
            'phone'=>$phone,
            'website'=>$website,
        ));
        
        $url = "https://api.hubapi.com/crm/v3/objects/contacts?hapikey=".$api_token;
        $args = array("headers" => array('Content-Type' => 'application/json'), "body" =>json_encode($properties));
        $response = wp_remote_post( $url, $args );
        $backurl = 'https://api.hubapi.com/crm/v3/objects/contacts?hapikey=XXXXXXXXXXXXXXXX';
        $args['body']=$properties;
        awp_add_to_log( $response, $backurl, $args, $record );
    }
    return $response;
}

function awp_hubspot_resend_data($log_id,$data,$integration){
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('hubspot');
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


    if( $task == "add_contact" ) {
        
        $url = "https://api.hubapi.com/crm/v3/objects/contacts?hapikey=".$api_token;
        $args = array("headers" => array('Content-Type' => 'application/json'), "body" =>json_encode($data['args']['body']));
        $response = wp_remote_post( $url, $args );

        $backurl = 'https://api.hubapi.com/crm/v3/objects/contacts?hapikey=XXXXXXXXXXXXXXXX';
        $args['body']=$data['args']['body'];
        awp_add_to_log( $response, $backurl, $args, $integration );
    }
    $response['success']=true;
    return $response;
}
