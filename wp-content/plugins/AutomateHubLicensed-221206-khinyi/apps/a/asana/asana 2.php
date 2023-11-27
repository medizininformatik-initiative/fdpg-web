<?php
add_filter( 'awp_action_providers', 'awp_asana_actions', 10, 1 );
function awp_asana_actions( $actions ) {
    $actions['asana'] = array(
        'title' => __( 'Asana', 'automate_hub' ),
        'tasks' => array(
            'create_task' => __( 'Create Task', 'automate_hub' )
        )
    );
    return $actions;
}

add_filter( 'awp_settings_tabs', 'awp_asana_settings_tab', 10, 1 );

function awp_asana_settings_tab( $providers ) {
    $providers['asana'] = array('name'=>esc_html__( 'Asana', 'automate_hub'), 'cat'=>array('crm'));
    return $providers;
}
add_action( 'awp_settings_view', 'awp_asana_settings_view', 10, 1 );

function awp_asana_settings_view( $current_tab ) {
    if( $current_tab != 'asana' ) { return; }
    $nonce     = wp_create_nonce( "awp_asana_settings" );
    $id = isset($_GET['id']) ? intval( sanitize_text_field($_GET['id'] )) : "";
    $api_key = isset($_GET['api_key']) ? sanitize_text_field($_GET['api_key']) : "";
    $display_name     = isset($_GET['account_name']) ? sanitize_text_field($_GET['account_name']) : "";
    ?>
    <div class="platformheader">

    <a href="https://sperse.io/go/asana" target="_blank"><img src="<?php echo esc_url(AWP_ASSETS.'/images/logos/asana.png'); ?>" width="237" height="50" alt="Asana Logo"></a><br/><br/>

    <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?>
 <br/>

    
 <?php 

$form_fields = '';
$app_name= 'asana';
$asana_form = new AWP_Form_Fields($app_name);

$form_fields = $asana_form->awp_wp_text_input(
    array(
        'id'            => "awp_asana_display_name",
        'name'          => "awp_asana_display_name",
        'value'         => $display_name,
        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $asana_form->awp_wp_text_input(
    array(
        'id'            => "awp_asana_access_token",
        'name'          => "awp_asana_access_token",
        'value'         => $api_key,
        'placeholder'   => esc_html__( 'Please enter Personal Access Token', 'automate_hub' ),
        'label'         =>  esc_html__( 'Personal Access Token', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $asana_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_save_asana_access_token',
    )
);


$form_fields .= $asana_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $asana_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$asana_form->render($form_fields);

?>


    </div>
<div class="wrap">
    <form id="form-list" method="post">
                
        
        <input type="hidden" name="page" value="automate_hub"/>
    <?php
        $data=[
                'table-cols'=>['account_name'=>'Display name','api_key'=>'Personal Access Token','spots'=>'Active Spots','active_status'=>'Active']
                ];
        $platform_obj= new AWP_Platform_Shell_Table('asana');
        $platform_obj->initiate_table($data);
        $platform_obj->prepare_items();
        $platform_obj->display_table();
                    
    ?>
    </form>
</div>
    <?php
}

add_action( 'admin_post_awp_save_asana_access_token', 'awp_save_asana_access_token', 10, 0 );

function awp_save_asana_access_token() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'awp_asana_settings' ) ) {
        die( __( 'Security check Failed', 'automate_hub' ) );
    }
    $api_token   = sanitize_text_field( $_POST["awp_asana_access_token"] );
    $display_name     = sanitize_text_field( $_POST["awp_asana_display_name"] );


    // Save tokens
    $platform_obj= new AWP_Platform_Shell_Table('asana');
    $platform_obj->save_platform(['account_name'=>$display_name,'api_key'=>$api_token]);
    awp_redirect( "admin.php?page=automate_hub&tab=asana" );
}

add_action( 'awp_action_fields', 'awp_asana_action_fields' );
function awp_asana_action_fields() {
    ?>
    <script type="text/template" id="asana-action-template">

      <?php

                $app_data=array(
                    'app_slug'=>'asana',
                   'app_name'=>'Asana',
                   'app_icon_url'=>AWP_ASSETS.'/images/icons/asana.png',
                   'app_icon_alter_text'=>'Asana Icon',
                   'account_select_onchange'=>'getAsanaWorkspaces',
                   'tasks'=>array(
                                'create_task'=>array(
                                                    'task_assignments'=>array(

                                                                            array(
                                                                                'label'=>'Workspace',
                                                                                'type'=>'select',
                                                                                'name'=>"fieldData[workspaceId]",
                                                                                'model'=>'fielddata.workspaceId',
                                                                                'required' => 'true',
                                                                                'onchange'=>'getProjects',
                                                                                'option_for_loop'=>'(item, index) in fielddata.workspaces',
                                                                                'select_default'=>'Select...',
                                                                                'spinner'=>array(
                                                                                                'bind-class'=>"{'is-active': workspaceLoading}",
                                                                                            )
                                                                            ),
                                                                            array(
                                                                                'label'=>'Project',
                                                                                'type'=>'select',
                                                                                'name'=>"fieldData[projectId]",
                                                                                'model'=>'fielddata.projectId',
                                                                                'required' => 'true',
                                                                                'onchange'=>'getSections',
                                                                                'option_for_loop'=>'(item, index) in fielddata.projects',
                                                                                'select_default'=>'Select...',
                                                                                'spinner'=>array(
                                                                                                'bind-class'=>"{'is-active': projectLoading}",
                                                                                            )
                                                                            ),
                                                                            array(
                                                                                'label'=>'Section',
                                                                                'type'=>'select',
                                                                                'name'=>"fieldData[sectionId]",
                                                                                'model'=>'fielddata.sectionId',
                                                                                'option_for_loop'=>'(item, index) in fielddata.sections',
                                                                                'select_default'=>'Select...',
                                                                                'spinner'=>array(
                                                                                                'bind-class'=>"{'is-active': sectionLoading}",
                                                                                            )
                                                                            ),
                                                                            array(
                                                                                'label'=>'Assignee',
                                                                                'type'=>'select',
                                                                                'name'=>"fieldData[userId]",
                                                                                'model'=>'fielddata.userId',
                                                                                'option_for_loop'=>'(item, index) in fielddata.users',
                                                                                'select_default'=>'Select...',
                                                                                'spinner'=>array(
                                                                                                'bind-class'=>"{'is-active': userLoading}",
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

add_action( 'wp_ajax_awp_get_asana_workspaces', 'awp_get_asana_workspaces', 10, 0 );
/* Get Asana Workspaces */
function awp_get_asana_workspaces() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
        die( __( 'Security check Failed', 'automate_hub' ) );
    }
    if (!isset( $_POST['platformid'] ) ) {
        die( esc_html__( 'Invalid Request', 'automate_hub' ) );
    }

    if(!isset($_POST['platformid']) || empty($_POST['platformid'])){
        
    }
    $id=sanitize_text_field($_POST['platformid']);
    $platform_obj= new AWP_Platform_Shell_Table('asana');
    $data=$platform_obj->awp_get_platform_detail_by_id($id);
    if(!$data){
        die( esc_html__( 'No Data Found', 'automate_hub' ) );
    }
    $api_token =$data->api_key;

    if( ! $api_token ) {
        return array();
    }
    $url = 'https://app.asana.com/api/1.0/workspaces/';
    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $api_token
        )
    );
    $workspaces = wp_remote_get( $url, $args );
    if( !is_wp_error( $workspaces ) ) {
        $body  = json_decode( wp_remote_retrieve_body( $workspaces ) );
        $lists = wp_list_pluck( $body->data, 'name', 'gid' );
        wp_send_json_success( $lists );
    } else {
        wp_send_json_error();
    }
}

add_action( 'wp_ajax_awp_get_asana_projects', 'awp_get_asana_projects', 20, 0 );
/* Get Asana Projects */
function awp_get_asana_projects() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
        die( __( 'Security check Failed', 'automate_hub' ) );
    }
    if (!isset( $_POST['platformid'] ) ) {
        die( esc_html__( 'Invalid Request', 'automate_hub' ) );
    }


    $id=sanitize_text_field($_POST['platformid']);
    $platform_obj= new AWP_Platform_Shell_Table('asana');
    $data=$platform_obj->awp_get_platform_detail_by_id($id);
    if(!$data){
        die( esc_html__( 'No Data Found', 'automate_hub' ) );
    }
    $api_token =$data->api_key;
    if( ! $api_token ) {
        wp_send_json_error();
    }
    $workspace_id = $_POST['workspaceId'] ? sanitize_text_field( $_POST['workspaceId'] ) : '';
    if( ! $workspace_id ) {
        wp_send_json_error();
    }
    $url = "https://app.asana.com/api/1.0/workspaces/{$workspace_id}/projects";
    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $api_token
        )
    );
    $projects = wp_remote_get( $url, $args );
    if( !is_wp_error( $projects ) ) {
        $body  = json_decode( wp_remote_retrieve_body( $projects ) );
        $lists = wp_list_pluck( $body->data, 'name', 'gid' );
        wp_send_json_success( $lists );
    } else {
        wp_send_json_error();
    }
}
/* Saves connection mapping */
function awp_asana_save_integration() {
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
add_action( 'wp_ajax_awp_get_asana_users', 'awp_get_asana_users', 20, 0 );
/* Get Asana Users */
function awp_get_asana_users() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
        die( __( 'Security check Failed', 'automate_hub' ) );
    }
    if (!isset( $_POST['platformid'] ) ) {
        die( esc_html__( 'Invalid Request', 'automate_hub' ) );
    }


    $id=sanitize_text_field($_POST['platformid']);
    $platform_obj= new AWP_Platform_Shell_Table('asana');
    $data=$platform_obj->awp_get_platform_detail_by_id($id);
    if(!$data){
        die( esc_html__( 'No Data Found', 'automate_hub' ) );
    }
    $api_token =$data->api_key;
   
    if( ! $api_token ) {
        wp_send_json_error();
    }
    $workspace_id = $_POST['workspaceId'] ? sanitize_text_field( $_POST['workspaceId'] ) : '';
    if( ! $workspace_id ) {
        wp_send_json_error();
    }
    $url = "https://app.asana.com/api/1.0/workspaces/{$workspace_id}/users";
    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $api_token
        )
    );
    $users = wp_remote_get( $url, $args );
    if( !is_wp_error( $users ) ) {
        $body  = json_decode( wp_remote_retrieve_body( $users ) );
        $lists = wp_list_pluck( $body->data, 'name', 'gid' );
        wp_send_json_success( $lists );
    } else {
        wp_send_json_error();
    }
}

add_action( 'wp_ajax_awp_get_asana_sections', 'awp_get_asana_sections', 20, 0 );
/* Get Asana Project Sections */
function awp_get_asana_sections() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
        die( __( 'Security check Failed', 'automate_hub' ) );
    }
    if (!isset( $_POST['platformid'] ) ) {
        die( esc_html__( 'Invalid Request', 'automate_hub' ) );
    }


    $id=sanitize_text_field($_POST['platformid']);
    $platform_obj= new AWP_Platform_Shell_Table('asana');
    $data=$platform_obj->awp_get_platform_detail_by_id($id);
    if(!$data){
        die( esc_html__( 'No Data Found', 'automate_hub' ) );
    }
    $api_token =$data->api_key;

    if( ! $api_token ) {
        wp_send_json_error();
    }
    $project_id = $_POST['projectId'] ? sanitize_text_field( $_POST['projectId'] ) : '';
    if( ! $project_id ) {
        wp_send_json_error();
    }
    $url = "https://app.asana.com/api/1.0/projects/{$project_id}/sections";
    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $api_token
        )
    );
    $sections = wp_remote_get( $url, $args );
    if( !is_wp_error( $sections ) ) {
        $body  = json_decode( wp_remote_retrieve_body( $sections ) );
        $lists = wp_list_pluck( $body->data, 'name', 'gid' );
        wp_send_json_success( $lists );
    } else {
        wp_send_json_error();
    }
}

/* Handles sending data to Asana API */
function awp_asana_send_data( $record, $posted_data ) {

    $temp    = json_decode( $record["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('asana');
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
    $workspace_id = empty( $data["workspaceId"] ) ? "" : $data["workspaceId"];
    $project_id   = empty( $data["projectId"] ) ? "" : $data["projectId"];
    $section_id   = empty( $data["sectionId"] ) ? "" : $data["sectionId"];
    $user_id      = empty( $data["userId"   ] ) ? "" : $data["userId"];
    $name         = empty( $data["name"     ] ) ? "" : awp_get_parsed_values( $data["name"], $posted_data );
    $notes        = empty( $data["notes"    ] ) ? "" : awp_get_parsed_values( $data["notes"], $posted_data );
    $due_on       = empty( $data["dueOn"    ] ) ? "" : awp_get_parsed_values( $data["dueOn"], $posted_data );
    if( $task == 'create_task' ) {
        $url = 'https://app.asana.com/api/1.0/tasks';
        $body = array(
            'data' => array(
                    'workspace' => $workspace_id,
                    'projects'  => array( $project_id ),
                    'name'      => $name,
                    'notes'     => $notes,
                    'due_on'    => $due_on
            )
        );
        if( $user_id ) {
            $body['data']['assignee'] = $user_id;
        }
        $body['data'] = array_filter( $body['data'] );
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $api_token
            ),
            'body' => json_encode( $body )
        );
        $response = wp_remote_post( $url, $args );
        awp_add_to_log( $response, $url, $args, $record );
        $task_id = '';
        if( $section_id ) {
            if( '201' == wp_remote_retrieve_response_code( $response ) ) {
                $body = json_decode( wp_remote_retrieve_body( $response ) );
                $task_id = $body->data->gid;
                $url = "https://app.asana.com/api/1.0/sections/{$section_id}/addTask";
                $body = array(
                    'data' => array(
                        'task' => $task_id
                    )
                );
                $args = array(
                    'headers' => array(
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer ' . $api_token
                    ),
                    'body' => json_encode( $body )
                );
                $response = wp_remote_post( $url, $args );
                $args['headers']['Authorization']='Bearer  XXXXXXXXXXX';
                awp_add_to_log( $response, $url, $args, $record );
            }
        }   
    }
    return $response;
}




function awp_asana_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];

    
    $platform_obj= new AWP_Platform_Shell_Table('asana');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_token=$temp->api_key;
    
    

    if( !$api_token ) {
        return;
    }
    $data=stripslashes($data);
    $data=preg_replace('/\s+/', '',$data); 
    $data=str_replace('"{', '{', $data);
    $data=str_replace('}"', '}', $data);        
    $data=json_decode($data,true);
    $body=$data['args']['body'];
    $url=$data['url'];
    if(!$url){
        $response['success']=false;
        $response['msg']="Syntax Error! Request is invalid";
        return $response;
    }


    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $api_token
        ),
        'body' => json_encode( $body )
    );
    $return = wp_remote_post( $url, $args );
    $args['headers']['Authorization']='Bearer  XXXXXXXXXXX';
    $args["body"]=$body;
    awp_add_to_log( $return, $url, $args, $integration );
    
    $response['success']=true;    
    return $response;

 

}
