<?php

add_filter( 'awp_action_providers', 'awp_smartsheet_actions', 10, 1 );
function awp_smartsheet_actions( $actions ) {
    $actions['smartsheet'] = array(
        'title' => esc_html__( 'Smartsheet', 'automate_hub' ),
        'tasks' => array('add_row'   => esc_html__( 'Add New Row', 'automate_hub' ))
    );
    return $actions;
}

add_filter( 'awp_settings_tabs', 'awp_smartsheet_settings_tab', 10, 1 );
function awp_smartsheet_settings_tab( $providers ) {
    $providers['smartsheet'] = array('name'=>esc_html__( 'Smartsheet', 'automate_hub'), 'cat'=>array('other'));
    return $providers;
}

add_action( 'awp_settings_view', 'awp_smartsheet_settings_view', 10, 1 );

function awp_smartsheet_settings_view( $current_tab ) {
    if( $current_tab != 'smartsheet' ) {
        return;
    }
    $nonce     = wp_create_nonce( "awp_smartsheet_settings" );
    $id = isset($_GET['id']) ? $_GET['id'] : "";
    $api_key = isset($_GET['api_key']) ? $_GET['api_key'] : "";
    $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";
    ?>
    <div class="platformheader">
    <a href="https://sperse.io/go/smartsheet" target="_blank"><img src="<?php echo AWP_ASSETS; ?>/images/logos/smartsheet.png" width="294" height="50" alt="Smartsheet Logo"></a><br/><br/>
    <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?>
                <br/>   
                <?php 

$form_fields = '';
$app_name= 'smartsheet';
$smartsheet_form = new AWP_Form_Fields($app_name);

$form_fields = $smartsheet_form->awp_wp_text_input(
    array(
        'id'            => "awp_smartsheet_display_name",
        'name'          => "awp_smartsheet_display_name",
        'value'         => $display_name,
        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $smartsheet_form->awp_wp_text_input(
    array(
        'id'            => "awp_smartsheet_api_token",
        'name'          => "awp_smartsheet_api_token",
        'value'         => $api_key,
        'placeholder'   => esc_html__( 'Enter Your SmartSheet API Token', 'automate_hub' ),
        'label'         =>  esc_html__( 'Smartsheet API Token', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $smartsheet_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_smartsheet_save_api_token',
    )
);


$form_fields .= $smartsheet_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $smartsheet_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$smartsheet_form->render($form_fields);

?>

    </div>    
    <div class="wrap">
        <form id="form-list" method="post">
                    
            
            <input type="hidden" name="page" value="automate_hub"/>

            <?php
            $data=[
                        'table-cols'=>['account_name'=>'Display name','api_key'=>'API Token','spots'=>'Active Spots','active_status'=>'Active']
                ];
            $platform_obj= new AWP_Platform_Shell_Table('smartsheet');
            $platform_obj->initiate_table($data);
            $platform_obj->prepare_items();
            $platform_obj->display_table();
                    
            ?>
        </form>
    </div>
    <?php
}

add_action( 'admin_post_awp_smartsheet_save_api_token', 'awp_save_smartsheet_api_token', 10, 0 );

function awp_save_smartsheet_api_token() { // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'awp_smartsheet_settings' ) ) {
        die( __( 'Security check Failed', 'automate_hub' ) );
    }
    $api_token = sanitize_text_field( $_POST["awp_smartsheet_api_token"] );
    $display_name     = sanitize_text_field( $_POST["awp_smartsheet_display_name"] );
            
    // Save tokens
    $platform_obj= new AWP_Platform_Shell_Table('smartsheet');
    $platform_obj->save_platform(['account_name'=>$display_name,'api_key'=>$api_token]);

    AWP_redirect( "admin.php?page=automate_hub&tab=smartsheet" );
}

add_action( 'awp_action_fields', 'awp_smartsheet_action_fields' );

function awp_smartsheet_action_fields() {
    ?>
    <script type="text/template" id="smartsheet-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'smartsheet',
                           'app_name'=>'Smartsheet',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/smartsheet.png',
                           'app_icon_alter_text'=>'Smartsheet Icon',
                           'account_select_onchange'=>'getSmartsheetList',
                           'tasks'=>array(
                                        'add_row'=>array(
                                                            'task_assignments'=>array(

                                                                                    array(
                                                                                        'label'=>'Sheet Name',
                                                                                        'type'=>'select',
                                                                                        'name'=>"list_id",
                                                                                        'model'=>'fielddata.listId',
                                                                                        'required'=>'required',
                                                                                        'onchange'=>'getFields',
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

add_action( 'wp_ajax_awp_get_smartsheet_list', 'awp_get_smartsheet_list', 10, 0 );
/* Get Smartheets add_rowr lists */
function awp_get_smartsheet_list() {   // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
        die( __( 'Security check Failed', 'automate_hub' ) );
    }
    if (!isset( $_POST['platformid'] ) ) {
        die( esc_html__( 'Invalid Request', 'automate_hub' ) );
    }
    $id=sanitize_text_field($_POST['platformid']);
    $platform_obj= new AWP_Platform_Shell_Table('smartsheet');
    $data=$platform_obj->awp_get_platform_detail_by_id($id);
    if(!$data){
        die( esc_html__( 'No Data Found', 'automate_hub' ) );
    }
    $api_token =$data->api_key;
    

    if( ! $api_token ) {return array();}
    $args = array('headers' => array('Content-Type'  => 'application/json','Authorization' => 'Bearer ' . $api_token));
    $url  = "https://api.smartsheet.com/2.0/sheets";
    $data = wp_remote_get( $url, $args );
    if( is_wp_error( $data ) ) {wp_snd_json_error();}
    $body  = json_decode( $data["body"] );
    $lists = wp_list_pluck( $body->data, 'name', 'id' );
    wp_send_json_success( $lists );
}

add_action( 'wp_ajax_awp_get_smartsheet_fields', 'awp_get_smartsheet_fields', 10, 0 );

/* Get smartsheet add_rowr lists */
function awp_get_smartsheet_fields() { // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
        die( __( 'Security check Failed', 'automate_hub' ) );
    }
    $sheet_id  = isset( $_REQUEST['listId'] ) ? $_REQUEST['listId'] : "";
    if (!isset( $_POST['platformid'] ) ) {
        die( esc_html__( 'Invalid Request', 'automate_hub' ) );
    }
    $id=sanitize_text_field($_POST['platformid']);
    $platform_obj= new AWP_Platform_Shell_Table('smartsheet');
    $data=$platform_obj->awp_get_platform_detail_by_id($id);
    if(!$data){
        die( esc_html__( 'No Data Found', 'automate_hub' ) );
    }
    $api_token =$data->api_key;

    if( !$api_token || !$sheet_id ) {return array();}
    $args = array('headers' => array('Content-Type'  => 'application/json', 'Authorization' => 'Bearer ' . $api_token));
    $url  = "https://api.smartsheet.com/2.0/sheets/{$sheet_id}";
    $data = wp_remote_get( $url, $args );
    if( is_wp_error( $data ) ) {wp_snd_json_error();}
    $body  = json_decode( $data["body"] );
    $lists = wp_list_pluck( $body->columns, 'title', 'id' );
    wp_send_json_success( $lists );
}

/* Saves connection mapping */
function awp_smartsheet_save_integration() {
    $params = array();
    parse_str( awp_sanitize_text_or_array_field( $_POST['formData'] ), $params );
    $trigger_data      = isset( $_POST["triggerData"] ) ? awp_sanitize_text_or_array_field( $_POST["triggerData"] ) : array();
    $action_data       = isset( $_POST["actionData"] ) ? awp_sanitize_text_or_array_field( $_POST["actionData"] ) : array();
    $field_data        = isset( $_POST["fieldData"] ) ? awp_sanitize_text_or_array_field( $_POST["fieldData"] ) : array();
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

/* Handles sending data to Klaviyo API */
function awp_smartsheet_send_data( $record, $posted_data ) {
    $temp    = json_decode( $record["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('smartsheet');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_token=$temp->api_key;
    
    if(!$api_token ) {
        return;
    }
    $record_data    = json_decode( $record["data"], true );
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
    if( $task == "add_row" ) {
        unset( $data["listId"] );
        unset( $data["list"] );
        $holder = array();
        foreach ( $data as $key => $value ) {

           if(is_int($key)){ 
                array_push( $holder, array( "columnId" => $key, "value" => awp_get_parsed_values( $data[$key], $posted_data ) ) );
            }
        }
        $to_be_sent = array( "toTop" => "true", "cells" => $holder );
        $url        = "https://api.smartsheet.com/2.0/sheets/{$list_id}/rows";
        $args = array('headers' => array('Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $api_token), 'body' => json_encode( array( $to_be_sent )));
        $return = wp_remote_post( $url, $args );
        $args['headers']['Authorization']= 'Bearer XXXXXXXXXXXX';
        $args['body']=$to_be_sent;
        awp_add_to_log( $return, $url, $args, $record );
    }
    return $return;
}



function awp_smartsheet_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('smartsheet');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_token=$temp->api_key;
   
    if(!$api_token ) {
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
    if( $task == "add_row" ) {
        
       
        
        $args = array('headers' => array('Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $api_token), 'body' => json_encode( array( $data['args']['body'] )));
        $return = wp_remote_post( $url, $args );
        $args['headers']['Authorization']= 'Bearer XXXXXXXXXXXX';
        $args['body']=$data['args']['body'];
        awp_add_to_log( $return, $url, $args, $integration );
    }
    $response['success']=true;
    return $response;
}
