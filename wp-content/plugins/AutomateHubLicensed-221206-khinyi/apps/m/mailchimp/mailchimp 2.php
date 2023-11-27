<?php

add_action( 'awp_custom_script', 'awp_mailchimppro_custom_script' );

function awp_mailchimppro_custom_script() {
    wp_enqueue_script( 'awp-mailchimppro-script', AWP_URL . '/apps/m/mailchimp/mailchimppro.js', array( 'awp-vuejs' ), '', 1 );
}

add_filter( 'awp_action_providers', 'awp_mailchimp_actions', 10, 1 );

function awp_mailchimp_actions( $actions ) {
    $actions['mailchimp'] = array(
        'title' => esc_html__( 'Mailchimp', 'automate_hub'),
        'tasks' => array( 'subscribe'   => esc_html__( 'Subscribe To List', 'automate_hub' ),
                          'unsubscribe' => esc_html__( 'Unsubscribe From List', 'automate_hub'))
    );
    return $actions;
}

add_filter( 'awp_settings_tabs', 'awp_mailchimp_settings_tab', 10, 1 );

function awp_mailchimp_settings_tab( $providers ) {
    $providers['mailchimp'] = array('name'=>esc_html__( 'Mailchimp', 'automate_hub'), 'cat'=>array('esp'));
    return $providers;
}

add_action( 'awp_settings_view', 'awp_mailchimp_settings_view', 10, 1 );

function awp_mailchimp_settings_view( $current_tab ) {
    if( $current_tab != 'mailchimp' ) { return; }
    $nonce     = wp_create_nonce( "awp_mailchimp_settings" );
    $id = isset($_GET['id']) ? intval( sanitize_text_field($_GET['id'] )) : "";
    $api_key = isset($_GET['api_key']) ? sanitize_text_field($_GET['api_key']) : "";
    $display_name     = isset($_GET['account_name']) ? sanitize_text_field($_GET['account_name']) : "";  
    ?>
    <div class="platformheader">
	<a href="https://sperse.io/go/mailchimp" target="_blank"><img src="<?php echo esc_url(AWP_ASSETS.'/images/logos/mailchimp.png'); ?>" width="184" height="50" alt="Mailchimp Logo"></a><br/><br/>
	<?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager(sanitize_text_field($_GET['tab']));
                    $instruction_obj->prepare_instructions();
                        
                ?>
                <br/>
                <?php 

$form_fields = '';
$app_name= 'mailchimp';
$mailchimp_form = new AWP_Form_Fields($app_name);

$form_fields = $mailchimp_form->awp_wp_text_input(
    array(
        'id'            => "awp_mailchimp_display_name",
        'name'          => "awp_mailchimp_display_name",
        'value'         => $display_name,
        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $mailchimp_form->awp_wp_text_input(
    array(
        'id'            => "awp_mailchimp_api_key",
        'name'          => "awp_mailchimp_api_key",
        'value'         => $api_key,
        'placeholder'   => esc_html__( 'Please enter API Key', 'automate_hub' ),
        'label'         =>  esc_html__( 'Mailchimp API Key', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $mailchimp_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_save_mailchimp_api_key',
    )
);


$form_fields .= $mailchimp_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $mailchimp_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$mailchimp_form->render($form_fields);

?>

    </div>
    <div class="wrap">
        <form id="form-list" method="post">
                    
            
            <input type="hidden" name="page" value="automate_hub"/>

            <?php
            $data=[
                        'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
            $platform_obj= new AWP_Platform_Shell_Table('mailchimp');
            $platform_obj->initiate_table($data);
            $platform_obj->prepare_items();
            $platform_obj->display_table();
                    
            ?>
        </form>
    </div>
    <?php
}

add_action( 'admin_post_awp_save_mailchimp_api_key', 'awp_save_mailchimp_api_key', 10, 0 );

function awp_save_mailchimp_api_key() {
    
    if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }


    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'awp_mailchimp_settings' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }

    $api_key = isset( $_POST["awp_mailchimp_api_key"] ) ? sanitize_text_field( $_POST["awp_mailchimp_api_key"] ) :'';
    $display_name     = isset( $_POST["awp_mailchimp_display_name"]) ? sanitize_text_field( $_POST["awp_mailchimp_display_name"] ) :'';
    // Save tokens
    $platform_obj= new AWP_Platform_Shell_Table('mailchimp');
    $platform_obj->save_platform(['account_name'=>$display_name,'api_key'=>$api_key]);
   

    AWP_redirect("admin.php?page=automate_hub&tab=mailchimp" );
}

add_action( 'awp_add_js_fields', 'awp_mailchimp_js_fields', 10, 1 );

function awp_mailchimp_js_fields( $field_data ) { }

add_action( 'awp_action_fields', 'awp_mailchimp_action_fields' );

function awp_mailchimp_action_fields() {
?>
    <script type="text/template" id="mailchimp-action-template">
		        <?php
                    $task_assignment_list=array(
                                                            'task_assignments'=>array(

                                                                                    array(
                                                                                        'label'=>'List',
                                                                                        'type'=>'select',
                                                                                        'name'=>"list_id",
                                                                                        'model'=>'fielddata.listId',
                                                                                        'required'=>'required',
                                                                                        'onchange'=>'',
                                                                                        'select_default'=>'Select Sequence...',
                                                                                        'option_for_loop'=>'(item, index) in fielddata.list',
                                                                                        'spinner'=>array(
                                                                                                        'bind-class'=>"{'is-active': listLoading}",
                                                                                                    )
                                                                                    ),
                                                                                    
                                                                                                                                                                        
                                                                                ),

                                                        );
                    $app_data=array(
                            'app_slug'=>'mailchimp',
                           'app_name'=>'Mailchimp',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/mailchimp.png',
                           'app_icon_alter_text'=>'Mailchimp Icon',
                           'account_select_onchange'=>'getMailchimpList',
                           'tasks'=>array(
                                        'subscribe'=>$task_assignment_list,
                                        'unsubscribe'=>$task_assignment_list,

                                    ),
                        ); 

                        require (AWP_VIEWS.'/awp_app_integration_format.php');
                ?>
    </script>


<?php
}

add_action( 'wp_ajax_awp_get_mailchimp_list', 'awp_get_mailchimp_list', 10, 0 );

/*
 * Get Mailchimp subscriber lists
 */
function awp_get_mailchimp_list() {
    
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
    $id = isset($_POST['platformid']) ?  sanitize_text_field($_POST['platformid']) :'';
    $platform_obj= new AWP_Platform_Shell_Table('mailchimp');
    $data=$platform_obj->awp_get_platform_detail_by_id($id);
    if(!$data){
        die( esc_html__( 'No Data Found', 'automate_hub' ) );
    }
    $api_key =$data->api_key;
   

    if( ! $api_key ) {
        return array();
    }

    $prefix = explode( "-", $api_key )[1];
    $url    = "https://{$prefix}.api.mailchimp.com/3.0/lists";

    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'Authorization' => 'api_key ' . $api_key
        )
    );

    $data = wp_remote_request( $url, $args );

    if( !is_wp_error( $data ) ) {
        $body  = json_decode( $data["body"] );
        $lists = wp_list_pluck( $body->lists, 'name', 'id' );

        wp_send_json_success( $lists );
    } else {
        wp_send_json_error();
    }
}

add_action( 'wp_ajax_awp_get_mailchimppro_mergefields', 'awp_get_mailchimppro_mergefields', 10, 0 );

/*
 * Get Mailchimp List merge fields
 */
function awp_get_mailchimppro_mergefields() {
   
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
    $id= isset($_POST['platformid']) ? sanitize_text_field($_POST['platformid']) :'';
    $platform_obj= new AWP_Platform_Shell_Table('mailchimp');
    $data=$platform_obj->awp_get_platform_detail_by_id($id);
    if(!$data){
        die( esc_html__( 'No Data Found', 'automate_hub' ) );
    }
    $api_key =$data->api_key;
    
    if( ! $api_key ) {
        return array();
    }

    $list_id = $_POST["listId"] ? sanitize_text_field( $_POST["listId"] ) : "";

    $prefix = explode( "-", $api_key )[1];
    $url    = "https://{$prefix}.api.mailchimp.com/3.0/lists/{$list_id}/merge-fields";

    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'Authorization' => 'api_key ' . $api_key
        )
    );

    $data = wp_remote_request( $url, $args );
    $attributes = array();

    if( !is_wp_error( $data ) ) {
        $body  = json_decode( $data["body"] );

        foreach( $body->merge_fields as $single ) {
            array_push( $attributes, array( 'key' => $single->tag, 'value' => $single->name ) );
        }

        wp_send_json_success( $attributes );
    } else {
        wp_send_json_error();
    }
}


/*
 * Saves connection mapping
 */
function awp_mailchimp_save_integration() {
    $params = array();
    parse_str( awp_sanitize_text_or_array_field( $_POST['formData'] ), $params );

    $trigger_data = isset( $_POST["triggerData"] ) ? awp_sanitize_text_or_array_field( $_POST["triggerData"] ) : array();
    $action_data  = isset( $_POST["actionData"] ) ? awp_sanitize_text_or_array_field( $_POST["actionData"] ) : array();
    $field_data   = isset( $_POST["fieldData"] ) ? awp_sanitize_text_or_array_field( $_POST["fieldData"] ) : array();

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

/*
 * Handles sending data to Mailchimp API
 */
function awp_mailchimp_send_data( $record, $posted_data ) {

    $temp    = json_decode( $record["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('mailchimp');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_key=$temp->api_key;
    

    if(!$api_key ) {
        return;
    }

    $data    = json_decode( $record["data"], true );
    $data    = $data["field_data"];
    $list_id = $data["listId"];
    $task    = $record["task"];
    $email   = empty( $data["email"] ) ? "" : awp_get_parsed_values($data["email"], $posted_data);
    $prefix  = explode( "-", $api_key )[1];

        $record_data = json_decode( $record["data"], true );
    if( array_key_exists( "cl", $record_data["action_data"]) ) {
        if( $record_data["action_data"]["cl"]["active"] == "yes" ) {
            $chk = awp_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data );
            if( !awp_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data ) ) {
                return;
            }
        }
    }

   /* if( $task == "subscribe" ) {

        $tags = array();


        $first_name   = empty( $data["firstName"] ) ? "" : awp_get_parsed_values($data["firstName"], $posted_data);
        $last_name    = empty( $data["lastName"] ) ? "" : awp_get_parsed_values($data["lastName"], $posted_data);
        $phone_number = empty( $data["phoneNumber"] ) ? "" : awp_get_parsed_values($data["phoneNumber"], $posted_data);

        $subscriber_data = array(
            "email_address"  => $email,
            "status" => "subscribed",
            "merge_fields" => array(
                "FNAME" => $first_name === NULL ? "" : $first_name,
                "LNAME" => $last_name === NULL ? "" : $last_name,
                "PHONE" => $phone_number === NULL ? "" : $phone_number
            )
        );

        $sub_url = "https://{$prefix}.api.mailchimp.com/3.0/lists/{$list_id}/members";

        $sub_args = array(

            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'api_key ' . $api_key
            ),
            'body' => json_encode( $subscriber_data )
        );

        $return = wp_remote_post( $sub_url, $sub_args );

        awp_add_to_log( $return, $sub_url, $sub_args, $record );

        if ( $return['response']['code'] == 200 ) {
            return array( 1 );
        } else {
            return array( 0, $return )  ;
        }
    }

    if( $task == "unsubscribe" ) {

        $search_url  = "https://{$prefix}.api.mailchimp.com/3.0/search-members?query={$email}";
        $search_args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'api_key ' . $api_key
            )
        );

        $member = wp_remote_get( $search_url, $search_args );

        if( !is_wp_error( $member ) ) {
            $body      = json_decode( $member["body"], true );
            $id        = $body["exact_matches"]["members"][0]["id"];
            $unsub_url = "https://{$prefix}.api.mailchimp.com/3.0/lists/{$list_id}/members/{$id}";

            $unsub_args = array(
                'headers' => array(
                    'Authorization' => 'api_key ' . $api_key
                ),
                'method' => 'DELETE'
            );

            $return = wp_remote_request( $unsub_url, $unsub_args );

            awp_add_to_log( $return, $unsub_url, $unsub_args, $record );

            if ( $return['response']['code'] == 204 ) {
                return array( 1 );
            } else {
                return array( 0, $return )  ;
            }

        } else {
            return;
        }
    }
*/


    if( $task == "subscribe" ) {

        $tags = array();

        if( isset( $data["tags"] ) ) {
            $tags = explode( ",", $data["tags"] );
        }

        unset( $data["email"] );
        unset( $data["list"] );
        unset( $data["listId"] );
        unset( $data["tags"] );

        $holder = array();

        foreach ( $data as $key => $value ) {
            if(!(is_array($value))){
                $holder[$key] = awp_get_parsed_values( $data[$key], $posted_data );
            }
        }

        $subscriber_data = array(
            "email_address"  => $email,
            "status" => "subscribed"
        );

        if( !empty( $holder ) ) {
            $subscriber_data["merge_fields"] = $holder;
        }

        if( !empty( $tags ) ) {
            $subscriber_data["tags"] = $tags;
        }

        $sub_url = "https://{$prefix}.api.mailchimp.com/3.0/lists/{$list_id}/members";

        $sub_args = array(

            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'api_key ' . $api_key
            ),
            'body' => json_encode( $subscriber_data )
        );

        $return = wp_remote_post( $sub_url, $sub_args );
        $sub_args['headers']['Authorization'] = 'api_key XXXXXXXXXX';
        $sub_args['body']=$subscriber_data;
        awp_add_to_log( $return, $sub_url, $sub_args, $record );

        return;
    }

    if( $task == "unsubscribe" ) {

        $search_url  = "https://{$prefix}.api.mailchimp.com/3.0/search-members?query={$email}";
        $search_args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'api_key ' . $api_key
            )
        );

        $member = wp_remote_get( $search_url, $search_args );


        if( !is_wp_error( $member ) ) {
            $body      = json_decode( $member["body"], true );
            $id        = $body["exact_matches"]["members"][0]["id"];
            $unsub_url = "https://{$prefix}.api.mailchimp.com/3.0/lists/{$list_id}/members/{$id}";

            $unsub_args = array(
                'headers' => array(
                    'Authorization' => 'api_key ' . $api_key
                ),
                'method' => 'DELETE'
            );

            $return = wp_remote_request( $unsub_url, $unsub_args );
            $unsub_args['headers']['Authorization'] = 'api_key XXXXXXXXXX';


            awp_add_to_log( $return, $unsub_url, $unsub_args, $record );

            if ( $return['response']['code'] == 204 ) {
                return $return;
            } else {
                return array( 0, $return )  ;
            }
            return $return;

        } else {
             return $return;
        }
    }
}


function awp_mailchimp_resend_data( $log_id,$data,$integration ) {

    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('mailchimp');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_key=$temp->api_key;
    

    if(!$api_key ) {
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
    $prefix  = explode( "-", $api_key )[1];
    if(!$url){
        $response['success']=false;
        $response['msg']="Syntax Error! Request is invalid";
        return $response;
    }


    if( $task == "subscribe" ) {

        

        $sub_args = array(

            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'api_key ' . $api_key
            ),
            'body' => json_encode( $data['args']['body'] )
        );


  

        $return = wp_remote_post( $url, $sub_args );
        $sub_args['headers']['Authorization'] = 'api_key XXXXXXXXXX';
        $sub_args['body']=$data['args']['body'];

        awp_add_to_log( $return, $url, $sub_args, $integration );

    }

    if( $task == "unsubscribe" ) {
        $email=$data['args']['body']['email_address'];
        $search_url  = "https://{$prefix}.api.mailchimp.com/3.0/search-members?query={$email}";
        $search_args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'api_key ' . $api_key
            )
        );

        $member = wp_remote_get( $search_url, $search_args );


        if( !is_wp_error( $member ) ) {
            $body      = json_decode( $member["body"], true );
            $id        = $body["exact_matches"]["members"][0]["id"];
            $unsub_url = "https://{$prefix}.api.mailchimp.com/3.0/lists/{$list_id}/members/{$id}";

            $unsub_args = array(
                'headers' => array(
                    'Authorization' => 'api_key ' . $api_key
                ),
                'method' => 'DELETE'
            );

            $return = wp_remote_request( $unsub_url, $unsub_args );
            $unsub_args['headers']['Authorization'] = 'api_key XXXXXXXXXX';
            awp_add_to_log( $return, $unsub_url, $unsub_args, $integration );

            

        } else {
            $response['success']=false;
            $response['msg']="There was an error with the request please check it in activity log";
            return $response;
        }
    }

    $response['success']=true;
    return $response;
}
