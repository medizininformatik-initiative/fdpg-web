<?php

add_filter( 'awp_action_providers', 'awp_woodpecker_actions', 10, 1 );

function awp_woodpecker_actions( $actions ) {
    $actions['woodpecker'] = array(
        'title' => esc_html__( 'Woodpecker.co', 'automate_hub'),
        'tasks' => array('subscribe'   => esc_html__( 'Add Subscriber', 'automate_hub'))
    );
    return $actions;
}

add_filter( 'awp_settings_tabs', 'awp_woodpecker_settings_tab', 10, 1 );

function awp_woodpecker_settings_tab( $providers ) {
    $providers['woodpecker'] = array('name'=>esc_html__( 'Woodpecker.co', 'automate_hub'), 'cat'=>array('esp'));
    return $providers;
}

add_action( 'awp_settings_view', 'awp_woodpecker_settings_view', 10, 1 );

function awp_woodpecker_settings_view( $current_tab ) {
    if( $current_tab != 'woodpecker' ) { return; }
    $nonce   = wp_create_nonce( "awp_woodpecker_settings" );
    $id = isset($_GET['id']) ? $_GET['id'] : "";
    $api_key = isset($_GET['api_key']) ? $_GET['api_key'] : "";
    $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";
    ?>
    <div class="platformheader">
	<a href="https://sperse.io/go/woodpecker" target="_blank"><img src="<?php echo AWP_ASSETS; ?>/images/logos/woodpecker.png" width="202px" height="50" alt="Woodpecker Logo"></a><br/><br/>
	<?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?>
                <br/>
                <?php 

$form_fields = '';
$app_name= 'woodpecker';
$woodpecker_form = new AWP_Form_Fields($app_name);

$form_fields = $woodpecker_form->awp_wp_text_input(
    array(
        'id'            => "awp_woodpecker_display_name",
        'name'          => "awp_woodpecker_display_name",
        'value'         => $display_name,
        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $woodpecker_form->awp_wp_text_input(
    array(
        'id'            => "awp_woodpecker_api_key",
        'name'          => "awp_woodpecker_api_key",
        'value'         => $api_key,
        'placeholder'   => esc_html__( 'Please enter Woodpecker API Key', 'automate_hub' ),
        'label'         =>  esc_html__( 'Woodpecker API Key', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $woodpecker_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_save_woodpecker_api_key',
    )
);


$form_fields .= $woodpecker_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $woodpecker_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$woodpecker_form->render($form_fields);

?>
    <div class="wrap">
        <form id="form-list" method="post">
                    
            
            <input type="hidden" name="page" value="automate_hub"/>

            <?php
            $data=[
                        'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
            $platform_obj= new AWP_Platform_Shell_Table('woodpecker');
            $platform_obj->initiate_table($data);
            $platform_obj->prepare_items();
            $platform_obj->display_table();
                    
            ?>
        </form>
    </div>
    <?php
}

add_action( 'admin_post_awp_save_woodpecker_api_key', 'awp_save_woodpecker_api_key', 10, 0 );

function awp_save_woodpecker_api_key() {

      if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'awp_woodpecker_settings' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }
    $api_key = sanitize_text_field( $_POST["awp_woodpecker_api_key"] );
    $display_name     = sanitize_text_field( $_POST["awp_woodpecker_display_name"] );
    // Save tokens
    $platform_obj= new AWP_Platform_Shell_Table('woodpecker');
    $platform_obj->save_platform(['account_name'=>$display_name,'api_key'=>$api_key]);

    AWP_redirect( "admin.php?page=automate_hub&tab=woodpecker" );
}
add_action( 'awp_add_js_fields', 'awp_woodpecker_js_fields', 10, 1 );

function awp_woodpecker_js_fields( $field_data ) { }

add_action( 'awp_action_fields', 'awp_woodpecker_action_fields' );

function awp_woodpecker_action_fields() {
?>
    <script type="text/template" id="woodpecker-action-template">
 		        <?php

                    $app_data=array(
                            'app_slug'=>'woodpecker',
                           'app_name'=>'Woodpecker.co',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/woodpecker.png',
                           'app_icon_alter_text'=>'Woodpecker.co Icon',
                           'account_select_onchange'=>'getWoodpeckerListPro',
                           'tasks'=>array(
                                        'subscribe'=>array(
                                                            'task_assignments'=>array(

                                                                                    array(
                                                                                        'label'=>'Campaign',
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

                                                        ),

                                    ),
                        ); 

                        require (AWP_VIEWS.'/awp_app_integration_format.php');
                ?>
    </script>


<?php
}

add_action( 'wp_ajax_awp_get_woodpreckerpro_list', 'awp_get_woodpreckerpro_list', 10, 0 );

/*
 * Get Mailchimp subscriber lists
 */
function awp_get_woodpreckerpro_list() {

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
    $platform_obj= new AWP_Platform_Shell_Table('woodpecker');
    $data=$platform_obj->awp_get_platform_detail_by_id($id);
    if(!$data){
        die( esc_html__( 'No Data Found', 'automate_hub' ) );
    }
    $api_key =$data->api_key;

    if( ! $api_key ) {
        return array();
    }

    $url = " https://api.woodpecker.co/rest/v1/campaign_list";

    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode( $api_key . ':' . "X" )
        )
    );

    $data = wp_remote_request( $url, $args );

    if( !is_wp_error( $data ) ) {
        $body  = json_decode( $data["body"] );
        $lists = wp_list_pluck( $body, 'name', 'id' );

        wp_send_json_success( $lists );
    } else {
        wp_send_json_error();
    }
}

/*
 * Saves connection mapping
 */
function awp_woodpecker_save_integration() {
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
function awp_woodpecker_send_data( $record, $posted_data ) {
    $temp    = json_decode( $record["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('woodpecker');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_key=$temp->api_key;
   

    if(!$api_key ) {
        return;
    }

    $data    = json_decode( $record["data"], true );
    $data    = $data["field_data"];
    $task    = $record["task"];
    $email   = empty( $data["email"] ) ? "" : awp_get_parsed_values($data["email"], $posted_data);
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
        $first_name = empty( $data["firstName"] ) ? "" : awp_get_parsed_values($data["firstName"], $posted_data);
        $last_name  = empty( $data["lastName"] ) ? "" : awp_get_parsed_values($data["lastName"], $posted_data);
        $company    = empty( $data["company"] ) ? "" : awp_get_parsed_values($data["company"], $posted_data);
        $website    = empty( $data["website"] ) ? "" : awp_get_parsed_values($data["website"], $posted_data);
        $industry   = empty( $data["industry"] ) ? "" : awp_get_parsed_values($data["industry"], $posted_data);
        $tags       = empty( $data["tags"] ) ? "" : awp_get_parsed_values($data["tags"], $posted_data);
        $title      = empty( $data["title"] ) ? "" : awp_get_parsed_values($data["title"], $posted_data);
        $phone      = empty( $data["phone"] ) ? "" : awp_get_parsed_values($data["phone"], $posted_data);
        $address    = empty( $data["address"] ) ? "" : awp_get_parsed_values($data["address"], $posted_data);
        $state      = empty( $data["state"] ) ? "" : awp_get_parsed_values($data["state"], $posted_data);
        $country    = empty( $data["country"] ) ? "" : awp_get_parsed_values($data["country"], $posted_data);
        $status     = empty( $data["status"] ) ? "" : awp_get_parsed_values($data["status"], $posted_data);
        $snippet1   = empty( $data["snippet1"] ) ? "" : awp_get_parsed_values($data["snippet1"], $posted_data);
        $snippet2   = empty( $data["snippet2"] ) ? "" : awp_get_parsed_values($data["snippet2"], $posted_data);
        $snippet3   = empty( $data["snippet3"] ) ? "" : awp_get_parsed_values($data["snippet3"], $posted_data);
        $snippet4   = empty( $data["snippet4"] ) ? "" : awp_get_parsed_values($data["snippet4"], $posted_data);
        $snippet5   = empty( $data["snippet5"] ) ? "" : awp_get_parsed_values($data["snippet5"], $posted_data);
        $snippet6   = empty( $data["snippet6"] ) ? "" : awp_get_parsed_values($data["snippet6"], $posted_data);
        $snippet7   = empty( $data["snippet7"] ) ? "" : awp_get_parsed_values($data["snippet7"], $posted_data);
        $snippet8   = empty( $data["snippet8"] ) ? "" : awp_get_parsed_values($data["snippet8"], $posted_data);
        $snippet9   = empty( $data["snippet9"] ) ? "" : awp_get_parsed_values($data["snippet9"], $posted_data);
        $snippet10  = empty( $data["snippet10"] ) ? "" : awp_get_parsed_values($data["snippet10"], $posted_data);
        $snippet11  = empty( $data["snippet11"] ) ? "" : awp_get_parsed_values($data["snippet11"], $posted_data);
        $snippet12  = empty( $data["snippet12"] ) ? "" : awp_get_parsed_values($data["snippet12"], $posted_data);
        $snippet13  = empty( $data["snippet13"] ) ? "" : awp_get_parsed_values($data["snippet13"], $posted_data);
        $snippet14  = empty( $data["snippet14"] ) ? "" : awp_get_parsed_values($data["snippet14"], $posted_data);
        $snippet15  = empty( $data["snippet15"] ) ? "" : awp_get_parsed_values($data["snippet15"], $posted_data);

        $subscriber_data = array(
            "prospects"  => array(
                array(
                    "email"      => $email,
                    "first_name" => $first_name,
                    "last_name"  => $last_name,
                    "company"    => $company,
                    "website"    => $website,
                    "industry"   => $industry,
                    "tags"       => $tags,
                    "title"      => $title,
                    "phone"      => $phone,
                    "address"    => $address,
                    "state"      => $state,
                    "country"    => $country,
                    "status"     => $status,
                    "snippet1"   => $snippet1,
                    "snippet2"  => $snippet2,
                    "snippet3"   => $snippet3,
                    "snippet4"   => $snippet4,
                    "snippet5"   => $snippet5,
                    "snippet6"   => $snippet6,
                    "snippet7"   => $snippet7,
                    "snippet8"   => $snippet8,
                    "snippet9"   => $snippet9,
                    "snippet10"  => $snippet10,
                    "snippet11"  => $snippet11,
                    "snippet12"  => $snippet12,
                    "snippet13"  => $snippet13,
                    "snippet14"  => $snippet14,
                    "snippet15"  => $snippet15
                )
            )
        );

        $sub_url = " https://api.woodpecker.co/rest/v1/add_prospects_list";

        if( $list_id ) {
            $sub_url = " https://api.woodpecker.co/rest/v1/add_prospects_campaign";

            $subscriber_data["campaign"]["campaign_id"] = $list_id;
        }

        $sub_args = array(

            'headers' => array(
                'Content-Type'  => 'application/json',
                'Authorization' => 'Basic ' . base64_encode( $api_key . ':' . "X" )
            ),
            'body' => json_encode( $subscriber_data )
        );

        $return = wp_remote_post( $sub_url, $sub_args );

        $sub_args['headers']['Authorization'] = 'Basic XXXXXXXXXXXXXX';
        $sub_args['body']=$subscriber_data;
        awp_add_to_log( $return, $sub_url, $sub_args, $record );

        if ( $return['response']['code'] == 200 ) {
            return $return;
        } else {
            return array( 0, $return )  ;
        }
        return $return;
    }

}



function awp_woodpecker_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('woodpecker');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_key=$temp->api_key;

    if(!$api_key ) {
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
        


        $sub_args = array(

            'headers' => array(
                'Content-Type'  => 'application/json',
                'Authorization' => 'Basic ' . base64_encode( $api_key . ':' . "X" )
            ),
            'body' => json_encode( $data['args']['body'] )
        );

        $return = wp_remote_post( $url, $sub_args );

        $sub_args['headers']['Authorization'] = 'Basic XXXXXXXXXXXXXX';
        $sub_args['body']=$data['args']['body'];
        awp_add_to_log( $return, $url, $sub_args, $integration );

    }

    $response['success']=true;
    return $response;

}
