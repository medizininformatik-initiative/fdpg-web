<?php

add_filter( 'awp_action_providers', 'awp_pipedrive_actions', 10, 1 );

function awp_pipedrive_actions( $actions ) {
    $actions['pipedrive'] = array(
        'title' => esc_html__( 'Pipedrive', 'automate_hub'),
        'tasks' => array('add_contact'   => esc_html__( 'Create New Contact', 'automate_hub'))
    );
    return $actions;
}

add_filter( 'awp_settings_tabs', 'awp_pipedrive_settings_tab', 10, 1 );

function awp_pipedrive_settings_tab( $providers ) {
    $providers['pipedrive'] = 
    array('name'=>esc_html__( 'Pipedrive', 'automate_hub'), 'cat'=>array('crm'));
    return $providers;
}

add_action( 'awp_settings_view', 'awp_pipedrive_settings_view', 10, 1 );

function awp_pipedrive_settings_view( $current_tab ) {
    if( $current_tab != 'pipedrive' ) { return; }
    $nonce     = wp_create_nonce( "awp_pipedrive_settings" );
    $id = isset($_GET['id']) ? $_GET['id'] : "";
    $api_key = isset($_GET['api_key']) ? $_GET['api_key'] : "";
    $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";

    ?>
    <div class="platformheader">
	<a href="https://sperse.io/go/pipedrive" target="_blank"><img src="<?php echo AWP_ASSETS; ?>/images/logos/pipedrive.png" width="226" height="50" alt="Pipedrive Logo"></a><br/><br/>
	<?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?>
                <br/>
                <?php 

$form_fields = '';
$app_name= 'pipedrive';
$pipedrive_form = new AWP_Form_Fields($app_name);

$form_fields = $pipedrive_form->awp_wp_text_input(
    array(
        'id'            => "awp_pipedrive_display_name",
        'name'          => "awp_pipedrive_display_name",
        'value'         => $display_name,
        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $pipedrive_form->awp_wp_text_input(
    array(
        'id'            => "awp_pipedrive_api_token",
        'name'          => "awp_pipedrive_api_token",
        'value'         => $api_key,
        'placeholder'   => esc_html__( 'Enter your Pipedrive API Token', 'automate_hub' ),
        'label'         =>  esc_html__( 'API Token', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $pipedrive_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_save_pipedrive_api_token',
    )
);


$form_fields .= $pipedrive_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $pipedrive_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$pipedrive_form->render($form_fields);

?>
    </div>
    <div class="wrap">
        <form id="form-list" method="post">
                    
            
            <input type="hidden" name="page" value="automate_hub"/>

            <?php
            $data=[
                        'table-cols'=>['account_name'=>'Display name','api_key'=>'API Token','spots'=>'Active Spots','active_status'=>'Active']
                ];
            $platform_obj= new AWP_Platform_Shell_Table('pipedrive');
            $platform_obj->initiate_table($data);
            $platform_obj->prepare_items();
            $platform_obj->display_table();
                    
            ?>
        </form>
    </div>
    <?php
}

add_action( 'admin_post_awp_save_pipedrive_api_token', 'awp_save_pipedrive_api_token', 10, 0 );

function awp_save_pipedrive_api_token() {

      if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'awp_pipedrive_settings' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }

    $api_token = sanitize_text_field( $_POST["awp_pipedrive_api_token"] );
    $display_name     = sanitize_text_field( $_POST["awp_pipedrive_display_name"] );
    // Save tokens
    $platform_obj= new AWP_Platform_Shell_Table('pipedrive');
    $platform_obj->save_platform(['account_name'=>$display_name,'api_key'=>$api_token]);


    AWP_redirect( "admin.php?page=automate_hub&tab=pipedrive" );
}

add_action( 'awp_add_js_fields', 'awp_pipedrive_js_fields', 10, 1 );

function awp_pipedrive_js_fields( $field_data ) {}

add_action( 'awp_action_fields', 'awp_pipedrive_action_fields' );

function awp_pipedrive_action_fields() {
    ?>
    <script type="text/template" id="pipedrive-action-template">
	            <?php

                    $app_data=array(
                            'app_slug'=>'pipedrive',
                           'app_name'=>'Pipedrive',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/pipedrive.png',
                           'app_icon_alter_text'=>'Pipedrive Icon',
                           'account_select_onchange'=>'getPipeDriveData',
                           'tasks'=>array(
                                        'add_contact'=>array(
                                                            'task_assignments'=>array(

                                                                                    array(
                                                                                        'label'=>'Owner',
                                                                                        'type'=>'select',
                                                                                        'name'=>"owner",
                                                                                        'model'=>'fielddata.owner',
                                                                                        'required'=>'required',
                                                                                        'onchange'=>'',
                                                                                        'select_default'=>'Select Owner...',
                                                                                        'option_for_loop'=>'(item, index) in fielddata.ownerList',
                                                                                        'spinner'=>array(
                                                                                                        'bind-class'=>"{'is-active': ownerLoading}",
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

add_action( 'wp_ajax_awp_get_pipedrive_owner_list', 'awp_get_pipedrive_owner_list', 10, 0 );

/*
 * Get Pipedrive Owner list
 */
function awp_get_pipedrive_owner_list() {

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
    $platform_obj= new AWP_Platform_Shell_Table('pipedrive');
    $data=$platform_obj->awp_get_platform_detail_by_id($id);
    if(!$data){
        die( esc_html__( 'No Data Found', 'automate_hub' ) );
    }
    $api_token =$data->api_key;
    

    if( ! $api_token ) {
        return array();
    }

    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json'
        )
    );

    $url = "https://api.pipedrive.com/v1/users?api_token=" . $api_token;

    $data      = wp_remote_request( $url, $args );

    if( is_wp_error( $data ) ) {
        wp_snd_json_error();
    }

    $body  = json_decode( $data["body"] );
    $lists = wp_list_pluck( $body->data, 'name', 'id' );

    wp_send_json_success( $lists );
}


add_action( 'wp_ajax_awp_get_pipedrive_org_fields', 'awp_get_pipedrive_org_fields', 10, 0 );

/*
 * Get Pipedrive Organization Fields
 */
function awp_get_pipedrive_org_fields() {

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
    $platform_obj= new AWP_Platform_Shell_Table('pipedrive');
    $data=$platform_obj->awp_get_platform_detail_by_id($id);
    if(!$data){
        die( esc_html__( 'No Data Found', 'automate_hub' ) );
    }
    $api_token =$data->api_key;

    

    if( ! $api_token ) {
        return array();
    }

    $org_fields = array(
        array( 'key' => 'org_name', 'value' => 'Name [Organziation]', 'description' => '' ),
        array( 'key' => 'org_address', 'value' => 'Address [Organziation]', 'description' => '' ),
    );

    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json'
        )
    );

    $url = "https://api.pipedrive.com/v1/organizationFields?api_token=" . $api_token;

    $data      = wp_remote_request( $url, $args );

    if( is_wp_error( $data ) ) {
        wp_snd_json_error();
    }

    $body       = json_decode( $data["body"] );

    foreach( $body->data as $single ) {
        if( strlen( $single->key ) == 40 || $single->key == "label" ) {

            $description = "";

            if( $single->field_type == "enum" ) {
                foreach( $single->options as $value ) {
                    $description .= $value->label . ': ' . $value->id . '  ';
                }
            }

            array_push( $org_fields, array( 'key' => 'org_' . $single->key, 'value' => $single->name . ' [Organziation]', 'description' => $description ) );
        }
    }

    wp_send_json_success( $org_fields );
}

add_action( 'wp_ajax_awp_get_pipedrive_person_fields', 'awp_get_pipedrive_person_fields', 10, 0 );

/*
 * Get Pipedrive Peson Fields
 */
function awp_get_pipedrive_person_fields() {

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
    $platform_obj= new AWP_Platform_Shell_Table('pipedrive');
    $data=$platform_obj->awp_get_platform_detail_by_id($id);
    if(!$data){
        die( esc_html__( 'No Data Found', 'automate_hub' ) );
    }
    $api_token =$data->api_key;

    if( ! $api_token ) {
        return array();
    }

    $person_fields = array(
        array( 'key' => 'per_name', 'value' => 'Name [Person]', 'description' => '' ),
        array( 'key' => 'per_email', 'value' => 'Email [Person]', 'description' => '' ),
        array( 'key' => 'per_phone', 'value' => 'Phone [Person]', 'description' => '' ),
        array( 'key' => 'per_postal_address', 'value' => 'Postal Address [Person]', 'description' => '' ),
    );

    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json'
        )
    );

    $url = "https://api.pipedrive.com/v1/personFields?api_token=" . $api_token;

    $data      = wp_remote_request( $url, $args );

    if( is_wp_error( $data ) ) {
        wp_snd_json_error();
    }

    $body       = json_decode( $data["body"] );

    foreach( $body->data as $single ) {
        if( strlen( $single->key ) == 40 || $single->key == "label" ) {

            $description = "";

            if( $single->field_type == "enum" ) {
                foreach( $single->options as $value ) {
                    $description .= $value->label . ': ' . $value->id . '  ';
                }
            }

            array_push( $person_fields, array( 'key' => 'per_' . $single->key, 'value' => $single->name . ' [Person]', 'description' => $description ) );
        }
    }

    wp_send_json_success( $person_fields );
}

add_action( 'wp_ajax_awp_get_pipedrive_deal_fields', 'awp_get_pipedrive_deal_fields', 10, 0 );

/*
 * Get Pipedrive Dal Fields
 */
function awp_get_pipedrive_deal_fields() {

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
    $platform_obj= new AWP_Platform_Shell_Table('pipedrive');
    $data=$platform_obj->awp_get_platform_detail_by_id($id);
    if(!$data){
        die( esc_html__( 'No Data Found', 'automate_hub' ) );
    }
    $api_token =$data->api_key;
    

    if( ! $api_token ) {
        return array();
    }

    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json'
        )
    );

    $stages     = "";
    $stage_url  = "https://api.pipedrive.com/v1/stages?api_token=" . $api_token;
    $stage_data = wp_remote_request( $stage_url, $args );
    $stage_body = json_decode( $stage_data["body"] );

    foreach( $stage_body->data as $single ) {
        $stages .= $single->pipeline_name . '/' . $single->name . ': ' . $single->id . ' ';
    }

    $deal_fields = array(
        array( 'key' => 'deal_title', 'value' => 'Title [Deal]', 'description' => '' ),
        array( 'key' => 'deal_value', 'value' => 'Value [Deal]', 'description' => '' ),
        array( 'key' => 'deal_currency', 'value' => 'Currency [Deal]', 'description' => '' ),
        array( 'key' => 'deal_probability', 'value' => 'Probability [Deal]', 'description' => '' ),
        array( 'key' => 'deal_stage_id', 'value' => 'Stage ID [Deal]', 'description' => $stages ),
        array( 'key' => 'deal_status', 'value' => 'Status [Deal]', 'description' => 'Example: open, lost, won, deleted' ),
        array( 'key' => 'deal_lost_reason', 'value' => 'Lost Reason [Deal]', 'description' => '' ),
    );



    $url = "https://api.pipedrive.com/v1/dealFields?api_token=" . $api_token;

    $data = wp_remote_request( $url, $args );

    if( is_wp_error( $data ) ) {
        wp_snd_json_error();
    }

    $body = json_decode( $data["body"] );

    foreach( $body->data as $single ) {
        if( strlen( $single->key ) == 40 || $single->key == "label" ) {

            $description = "";

            if( $single->field_type == "enum" ) {
                foreach( $single->options as $value ) {
                    $description .= $value->label . ': ' . $value->id . '  ';
                }
            }

            array_push( $deal_fields, array( 'key' => 'deal_' . $single->key, 'value' => $single->name . ' [Deal]', 'description' => $description ) );
        }
    }

    wp_send_json_success( $deal_fields );
}


/*
 * Saves connection mapping
 */
function awp_pipedrive_save_integration() {
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
 * Handles sending data to Pipedrive API
 */
function awp_pipedrive_send_data( $record, $posted_data ) {
    $temp    = json_decode( $record["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('pipedrive');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_token=$temp->api_key;
    

    if( !$api_token ) {
        return;
    }

    $data    = json_decode( $record["data"], true );
    $data    = $data["field_data"];
    $task    = $record["task"];
    // Pro
    $owner     = $data["owner"];
    $org_id    = "";
    $person_id = "";
    $deal_id   = "";

        $record_data = json_decode( $record["data"], true );
    if( array_key_exists( "cl", $record_data["action_data"]) ) {
        if( $record_data["action_data"]["cl"]["active"] == "yes" ) {
            $chk = awp_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data );
            if( !awp_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data ) ) {
                return;
            }
        }
    }

    if( $task == "add_contact" ) {
        $email = empty( $data["email"] ) ? "" : awp_get_parsed_values( $data["email"], $posted_data );
        $name  = empty( $data["name"] ) ? "" : awp_get_parsed_values( $data["name"], $posted_data );
        $url   = "https://api.pipedrive.com/v1/persons?api_token=" . $api_token;

        $headers = array(
            "Accept"       => "application/json",
            "Content-Type" => "application/json"
        );

        $body = json_encode( array(
            "name"  => $name,
            "email" => $email
        ) , true );

        $args = array(
            "headers" => $headers,
            "body"    => $body
        );

        $response = wp_remote_post( $url, $args );


        $back_url   = "https://api.pipedrive.com/v1/persons?api_token=XXXXXXXXX";
        $args['body']=array(
            "name"  => $name,
            "email" => $email
        );
        awp_add_to_log( $response, $back_url, $args, $record );
    }

    
    if( $task == "add_contact" ) {

        $holder      = array();
        $org_data    = array();
        $person_data = array();
        $deal_data   = array();
        $note_data   = array();
        $act_data    = array();

        foreach( $data as $key => $value ) {
            $holder[$key] = awp_get_parsed_values( $data[$key], $posted_data );
        }

        foreach( $holder as $key => $value ) {
            if( substr( $key, 0, 4 ) == 'org_' && $value ) {
                $key = substr( $key, 4 );

                $org_data[$key] = $value;
            }

            if( substr( $key, 0, 4 ) == 'per_' && $value ) {
                $key = substr( $key, 4 );

                $person_data[$key] = $value;
            }

            if( substr( $key, 0, 5 ) == 'deal_' && $value ) {
                $key = substr( $key, 5 );

                $deal_data[$key] = $value;
            }

            if( substr( $key, 0, 5 ) == 'note_' && $value ) {
                $key = substr( $key, 5 );

                $note_data[$key] = $value;
            }

            if( substr( $key, 0, 4 ) == 'act_' && $value ) {
                $key = substr( $key, 4 );

                $act_data[$key] = $value;
            }
        }

        $headers = array(
            "Accept"       => "application/json",
            "Content-Type" => "application/json"
        );

        if( $org_data['name'] ) {
            $org_data["owner_id"] = $owner;

            $url = "https://api.pipedrive.com/v1/organizations?api_token={$api_token}";

            $body = json_encode( $org_data );

            $args = array(
                "headers" => $headers,
                "body"    => $body
            );

            $response = wp_remote_post( $url, $args );
            $burl = "https://api.pipedrive.com/v1/organizations?api_token=XXXXXXXXX";
            $args['body']=$org_data;
            awp_add_to_log( $response, $burl, $args, $record );

            $body = json_decode( $response["body"] );

            if( $body->success == true ) {
                $org_id = $body->data->id;
            }
        }

        if( $person_data['name'] ) {
            $person_data["owner_id"] = $owner;

            if( $org_id ) {
                $person_data['org_id'] = $org_id;
            }

            $url = "https://api.pipedrive.com/v1/persons?api_token={$api_token}";

            $body = json_encode( $person_data );

            $args = array(
                "headers" => $headers,
                "body"    => $body
            );

            $response = wp_remote_post( $url, $args );
            $purl = "https://api.pipedrive.com/v1/persons?api_token=XXXXXXXXX";
            $args['body']=$person_data;
            awp_add_to_log( $response, $purl, $args, $record );


            $body = json_decode( $response["body"] );

            if( $body->success == true ) {
                $person_id = $body->data->id;
            }
        }

        if( $deal_data['title'] ) {
            $deal_data["user_id"] = $owner;

            if( $org_id ) {
                $deal_data['org_id'] = $org_id;
            }

            if( $person_id ) {
                $deal_data['person_id'] = $person_id;
            }

            $url = "https://api.pipedrive.com/v1/deals?api_token={$api_token}";

            $body = json_encode( $deal_data );

            $args = array(
                "headers" => $headers,
                "body"    => $body
            );

            $response = wp_remote_post( $url, $args );
              $deurl = "https://api.pipedrive.com/v1/deals?api_token=XXXXXXXXX";
              $args['body']=$deal_data;
            awp_add_to_log( $response, $deurl, $args, $record );

            $body = json_decode( $response["body"] );

            if( $body->success == true ) {
                $deal_id = $body->data->id;
            }
        }

        if( $note_data['content'] ) {
            $note_data["user_id"] = $owner;

            if( $org_id ) {
                $note_data['org_id'] = $org_id;
            }

            if( $person_id ) {
                $note_data['person_id'] = $person_id;
            }

            if( $deal_id ) {
                $note_data['deal_id'] = $deal_id;
            }

            $url = "https://api.pipedrive.com/v1/notes?api_token={$api_token}";

            $body = json_encode( $note_data );

            $args = array(
                "headers" => $headers,
                "body"    => $body
            );

            $response = wp_remote_post( $url, $args );
            $notesurl = "https://api.pipedrive.com/v1/notes?api_token=XXXXXXXXX";
            $args['body']=$note_data;
            awp_add_to_log( $response, $notesurl, $args, $record );
        }
    }

    return $response;
}



function awp_pipedrive_resend_data( $log_id,$request_data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('pipedrive');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_token=$temp->api_key;
   

    if( !$api_token ) {
        return;
    }

    $data    = json_decode( $integration["data"], true );
    $data    = $data["field_data"];
    $owner     = $data["owner"];
    $org_id    = "";
    $person_id = "";
    $deal_id   = "";

    $task=$integration['task'];
    $request_data=stripslashes($request_data);
    $request_data=preg_replace('/\s+/', '',$request_data); 
    $request_data=json_decode($request_data,true);
    $url=$request_data['url'];
    if(!$url){
        $response['success']=false;
        $response['msg']="Syntax Error! Request is invalid";
        return $response;
    }


       

    if( $task == "add_contact" ) {
        
        $url   = "https://api.pipedrive.com/v1/persons?api_token=" . $api_token;

        $headers = array(
            "Accept"       => "application/json",
            "Content-Type" => "application/json"
        );

 

        $args = array(
            "headers" => $headers,
            "body"    => json_encode($request_data['args']['body'], true )
        );

        $response = wp_remote_post( $url, $args );


        $back_url   = "https://api.pipedrive.com/v1/persons?api_token=XXXXXXXXX";
        $args['body']=$request_data['args']['body'];
        awp_add_to_log( $response, $back_url, $args, $integration );
    }

    
    

    $response['success']=true;
    return $response;
}
