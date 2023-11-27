<?php

add_filter( 'awp_action_providers', 'awp_moosend_actions', 10, 1 );

function awp_moosend_actions( $actions ) {
    $actions['moosend'] = array(
        'title' => esc_html__( 'Moosend', 'automate_hub'),
        'tasks' => array('subscribe'   => esc_html__( 'Subscribe To List', 'automate_hub' ),)
    );
    return $actions;
}

add_filter( 'awp_settings_tabs', 'awp_moosend_settings_tab', 10, 1 );

function awp_moosend_settings_tab( $providers ) {
    $providers['moosend'] = array('name'=>esc_html__( 'Moosend', 'automate_hub'), 'cat'=>array('esp'));
    return $providers;
}

add_action( 'awp_settings_view', 'awp_moosend_settings_view', 10, 1 );

function awp_moosend_settings_view( $current_tab ) {
    if( $current_tab != 'moosend' ) { return; }
    $nonce     = wp_create_nonce( "awp_moosend_settings" );
    $id = isset($_GET['id']) ? $_GET['id'] : "";
    $api_key = isset($_GET['api_key']) ? $_GET['api_key'] : "";
    $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";
    ?>
    <div class="platformheader">
	<a href="https://sperse.io/go/moosend" target="_blank"><img src="<?php echo AWP_ASSETS; ?>/images/logos/moosend.png" width="177" height="50" alt="Moosend Logo"></a><br/><br/>
	<?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?><br/>

   <?php 

            $form_fields = '';
            $app_name= 'moosend';
            $moosend_form = new AWP_Form_Fields($app_name);

            $form_fields = $moosend_form->awp_wp_text_input(
                array(
                    'id'            => "awp_moosend_display_name",
                    'name'          => "awp_moosend_display_name",
                    'value'         => $display_name,
                    'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
                    'label'         =>  esc_html__('Display Name', 'automate_hub' ),
                    'wrapper_class' => 'form-row',
                    'show_copy_icon'=>true
                    
                )
            );

            $form_fields .= $moosend_form->awp_wp_text_input(
                array(
                    'id'            => "awp_moosend_api_token",
                    'name'          => "awp_moosend_api_token",
                    'value'         => $api_key,
                    'placeholder'   => esc_html__( 'Enter your Moosend API Key', 'automate_hub' ),
                    'label'         =>  esc_html__( 'Moosend API Key', 'automate_hub' ),
                    'wrapper_class' => 'form-row',
                    'show_copy_icon'=>true
                    
                )
            );

            $form_fields .= $moosend_form->awp_wp_hidden_input(
                array(
                    'name'          => "action",
                    'value'         => 'awp_moosend_save_api_token',
                )
            );


            $form_fields .= $moosend_form->awp_wp_hidden_input(
                array(
                    'name'          => "_nonce",
                    'value'         =>$nonce,
                )
            );
            $form_fields .= $moosend_form->awp_wp_hidden_input(
                array(
                    'name'          => "id",
                    'value'         =>wp_unslash($id),
                )
            );


            $moosend_form->render($form_fields);

            ?>


    </div>
    <div class="wrap">
        <form id="form-list" method="post">
                    
            
            <input type="hidden" name="page" value="automate_hub"/>

            <?php
            $data=[
                        'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
            $platform_obj= new AWP_Platform_Shell_Table('moosend');
            $platform_obj->initiate_table($data);
            $platform_obj->prepare_items();
            $platform_obj->display_table();
                    
            ?>
        </form>
    </div>
    <?php
}

add_action( 'admin_post_awp_moosend_save_api_token', 'awp_save_moosend_api_token', 10, 0 );

function awp_save_moosend_api_token() {

      if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'awp_moosend_settings' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }

    $api_token = sanitize_text_field( $_POST["awp_moosend_api_token"] );
    $display_name     = sanitize_text_field( $_POST["awp_moosend_display_name"] );
    // Save tokens

    $platform_obj= new AWP_Platform_Shell_Table('moosend');
    $platform_obj->save_platform(['account_name'=>$display_name,'api_key'=>$api_token]);

    AWP_redirect( "admin.php?page=automate_hub&tab=moosend" );
}

add_action( 'awp_add_js_fields', 'awp_moosend_js_fields', 10, 1 );

function awp_moosend_js_fields( $field_data ) {}

add_action( 'awp_action_fields', 'awp_moosend_action_fields' );

function awp_moosend_action_fields() {
    ?>
    <script type="text/template" id="moosend-action-template">
		        <?php

                    $app_data=array(
                            'app_slug'=>'moosend',
                           'app_name'=>'Moosend',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/moosend.png',
                           'app_icon_alter_text'=>'Moosend Icon',
                           'account_select_onchange'=>'getMoosendList',
                           'tasks'=>array(
                                        'subscribe'=>array(
                                                            'task_assignments'=>array(

                                                                                    array(
                                                                                        'label'=>'List',
                                                                                        'type'=>'select',
                                                                                        'name'=>"list_id",
                                                                                        'model'=>'fielddata.listId',
                                                                                        'required'=>'required',
                                                                                        'onchange'=>'',
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

add_action( 'wp_ajax_awp_get_moosend_list', 'awp_get_moosend_list', 10, 0 );
/*
 * Get Kalviyo subscriber lists
 */
function awp_get_moosend_list() {

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
    $platform_obj= new AWP_Platform_Shell_Table('moosend');
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

    $url = "https://api.moosend.com/v3/lists.json?apikey=" . $api_token;

    $data      = wp_remote_request( $url, $args );

    if( is_wp_error( $data ) ) {
        wp_snd_json_error();
    }

    $body  = json_decode( $data["body"] );
    $lists = wp_list_pluck( $body->Context->MailingLists, 'Name', 'ID' );

    wp_send_json_success( $lists );
}

/*
 * Saves connection mapping
 */
function awp_moosend_save_integration() {
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
 * Handles sending data to moosend API
 */
function awp_moosend_send_data( $record, $posted_data ) {

    $temp    = json_decode( $record["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('moosend');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_token=$temp->api_key;
    
    if(!$api_token ) {
        return;
    }

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
        $email = empty( $data["email"] ) ? "" : awp_get_parsed_values( $data["email"], $posted_data );
        $name  = empty( $data["name"] ) ? "" : awp_get_parsed_values( $data["name"], $posted_data );
        $cf    = empty( $data["customFields"] ) ? "" : awp_get_parsed_values( $data["customFields"], $posted_data );


        $data = array(
            'email' => $email,
            'name'  => $name
        );

        
        if( $cf ) {
            $cf = explode( ",", $cf );
            $data["customFields"] = $cf;
        }

        $url = "https://api.moosend.com/v3/subscribers/{$list_id}/subscribe.json?apikey={$api_token}";

        $args = array(

            'headers' => array(
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode( $data )
        );

        $return = wp_remote_post( $url, $args );
        $back_url = "https://api.moosend.com/v3/subscribers/{$list_id}/subscribe.json?apikey=XXXXXXXXX";
        $args['body']=$data;
        awp_add_to_log( $return, $back_url, $args, $record );
    }

    return $return;
}


function awp_moosend_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('moosend');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_token=$temp->api_key;

    if(!$api_token ) {
        return;
    }


    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];
    $list_id = $temp["listId"];

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
        

        $url = "https://api.moosend.com/v3/subscribers/{$list_id}/subscribe.json?apikey={$api_token}";

        $args = array(

            'headers' => array(
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode( $data['args']['body'] )
        );

        $return = wp_remote_post( $url, $args );
        $back_url = "https://api.moosend.com/v3/subscribers/{$list_id}/subscribe.json?apikey=XXXXXXXXX";
        $args['body']=$data['args']['body'];
        awp_add_to_log( $return, $back_url, $args, $integration );
    }

    $response['success']=true;
    return $response;
}
