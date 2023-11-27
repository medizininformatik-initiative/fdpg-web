<?php

add_filter( 'awp_action_providers', 'awp_kartra_actions', 10, 1 );
function awp_kartra_actions( $actions ) {
    $actions['kartra'] = array(
        'title' => esc_html__( 'Kartra', 'automate_hub' ),
        'tasks' => array('subscribe'   => esc_html__( 'Add Lead To List', 'automate_hub' ),)
    );
    return $actions;
}
add_filter( 'awp_settings_tabs', 'awp_kartra_settings_tab', 10, 1 );

function awp_kartra_settings_tab( $providers ) {
    $providers['kartra'] = array('name'=>esc_html__( 'Kartra', 'automate_hub'), 'cat'=>array('crm'));
    return $providers;
}
add_action( 'awp_settings_view', 'awp_kartra_settings_view', 10, 1 );

function awp_kartra_settings_view( $current_tab ) {
    if( $current_tab != 'kartra' ) { return; }
    $nonce        = wp_create_nonce( "awp_kartra_settings" );
    $id = isset($_GET['id']) ? $_GET['id'] : "";
    $api_key = isset($_GET['api_key']) ? $_GET['api_key'] : "";
    $client_secret = isset($_GET['client_secret']) ? $_GET['client_secret'] : "";
    $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";
    ?>
    <div class="platformheader">
	<a href="https://sperse.io/go/kartra" target="_blank"><img src="<?php echo(AWP_ASSETS) ?>/images/logos/kartra.png" width="280" height="50" alt="Kartra Logo"></a><br/><br/>
	<?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?>
                
	<br/>
    <?php 

$form_fields = '';
$app_name= 'kartra';
$kartra_form = new AWP_Form_Fields($app_name);

$form_fields = $kartra_form->awp_wp_text_input(
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

$form_fields .= $kartra_form->awp_wp_text_input(
    array(
        'id'            => "awp_kartra_api_key",
        'name'          => "awp_kartra_api_key",
        'value'         => $api_key,
        'placeholder'   => esc_html__( 'Please enter API Key', 'automate_hub' ),
        'label'         =>  esc_html__( 'Kartra API Key', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);
$form_fields .= $kartra_form->awp_wp_text_input(
    array(
        'id'            => "awp_kartra_api_password",
        'name'          => "awp_kartra_api_password",
        'value'         => $client_secret,
        'placeholder'   => esc_html__( 'Please enter API Password', 'automate_hub' ),
        'label'         =>  esc_html__( 'Kartra API Password', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $kartra_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_kartra_save_api_token',
    )
);


$form_fields .= $kartra_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $kartra_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$kartra_form->render($form_fields);

?>
    </div>
    <div class="wrap">
        <form id="form-list" method="post">
                    
            
            <input type="hidden" name="page" value="automate_hub"/>

            <?php
            $data=[
                        'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','client_secret'=>'API Password','spots'=>'Active Spots','active_status'=>'Active']
                ];
            $platform_obj= new AWP_Platform_Shell_Table('kartra');
            $platform_obj->initiate_table($data);
            $platform_obj->prepare_items();
            $platform_obj->display_table();
                    
            ?>
        </form>
    </div>
    <?php
}

add_action( 'admin_post_awp_kartra_save_api_token', 'awp_save_kartra_api_token', 10, 0 );

function awp_save_kartra_api_token() {
    
        if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }

    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'awp_kartra_settings' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }

    $api_key      = sanitize_text_field( $_POST["awp_kartra_api_key"] );
    $api_password = sanitize_text_field( $_POST["awp_kartra_api_password"] );
    $display_name     = sanitize_text_field( $_POST["awp_kartra_display_name"] );
    // Save tokens
    $platform_obj= new AWP_Platform_Shell_Table('kartra');
    $platform_obj->save_platform(['account_name'=>$display_name,'api_key'=>$api_key,'client_secret'=>$api_password]);


    AWP_redirect( "admin.php?page=automate_hub&tab=kartra" );
}

add_action( 'awp_add_js_fields', 'awp_kartra_js_fields', 10, 1 );

function awp_kartra_js_fields( $field_data ) {}

add_action( 'awp_action_fields', 'awp_kartra_action_fields' );

function awp_kartra_action_fields() {
    ?>
    <script type="text/template" id="kartra-action-template">
		        <?php

                    $app_data=array(
                            'app_slug'=>'kartra',
                           'app_name'=>'Kartra',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/kartra.png',
                           'app_icon_alter_text'=>'Kartra Icon',
                           'account_select_onchange'=>'getKartraList',
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

add_action( 'wp_ajax_awp_get_kartra_list', 'awp_get_kartra_list', 10, 0 );
/*
 * Get Kalviyo subscriber lists
 */
function awp_get_kartra_list() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }

    if (!isset( $_POST['platformid'] ) ) {
        die( esc_html__( 'Invalid Request', 'automate_hub' ) );
    }
    $id=sanitize_text_field($_POST['platformid']);
    $platform_obj= new AWP_Platform_Shell_Table('kartra');
    $data=$platform_obj->awp_get_platform_detail_by_id($id);
    if(!$data){
        die( esc_html__( 'No Data Found', 'automate_hub' ) );
    }
    $api_key =$data->api_key;
    $api_password     = $data->client_secret;
    
    $app_id       = "zrbNVFqSAJLw";

    if( !$api_key || !$api_password ) {
        return array();
    }

    $body = array(
        'actions' => array(
            array(
                'cmd' => 'retrieve_account_lists'
            )
        )
    );

    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json'
        ),
        'body' => json_encode( $body )
    );

    $url  = "https://app.kartra.com/api?app_id={$app_id}&api_key={$api_key}&api_password={$api_password}";
    $data = wp_remote_post( $url, $args );

    if( is_wp_error( $data ) ) {
        wp_snd_json_error();
    }

    $body  = json_decode( $data["body"] );
    $lists = array();
    if( is_array( $body->account_lists ) ) {
        $lists = array_combine( $body->account_lists, $body->account_lists );
    }

    wp_send_json_success( $lists );
}

/*
 * Saves connection mapping
 */
function awp_kartra_save_integration() {
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
 * Handles sending data to Kartra API
 */
function awp_kartra_send_data( $record, $posted_data ) {

    $temp    = json_decode( $record["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('kartra');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_key=$temp->api_key;
    $api_password=$temp->client_secret;

    $app_id       = "zrbNVFqSAJLw";

    if(!$api_key || !$api_password ) {
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

    $data    = $record_data["field_data"];
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
        $email         = empty( $data["email"] ) ? "" : awp_get_parsed_values( $data["email"], $posted_data );
        $first_name    = empty( $data["firstName"] ) ? "" : awp_get_parsed_values( $data["firstName"], $posted_data );
        $middle_name   = empty( $data["middleName"] ) ? "" : awp_get_parsed_values( $data["middleName"], $posted_data );
        $last_name     = empty( $data["lastName"] ) ? "" : awp_get_parsed_values( $data["lastName"], $posted_data );
        $last_name2    = empty( $data["lastName2"] ) ? "" : awp_get_parsed_values( $data["lastName2"], $posted_data );
        $p_c_code      = empty( $data["phoneCountryCode"] ) ? "" : awp_get_parsed_values( $data["phoneCountryCode"], $posted_data );
        $phone         = empty( $data["phone"] ) ? "" : awp_get_parsed_values( $data["phone"], $posted_data );
        $ip            = empty( $data["ip"] ) ? "" : awp_get_parsed_values( $data["ip"], $posted_data );
        $address       = empty( $data["address1"] ) ? "" : awp_get_parsed_values( $data["address1"], $posted_data );
        $zip           = empty( $data["zip"] ) ? "" : awp_get_parsed_values( $data["zip"], $posted_data );
        $city          = empty( $data["city"] ) ? "" : awp_get_parsed_values( $data["city"], $posted_data );
        $state         = empty( $data["state"] ) ? "" : awp_get_parsed_values( $data["state"], $posted_data );
        $country       = empty( $data["country"] ) ? "" : awp_get_parsed_values( $data["country"], $posted_data );
        $company       = empty( $data["company"] ) ? "" : awp_get_parsed_values( $data["company"], $posted_data );
        $website       = empty( $data["website"] ) ? "" : awp_get_parsed_values( $data["website"], $posted_data );
        $facebook      = empty( $data["facebook"] ) ? "" : awp_get_parsed_values( $data["facebook"], $posted_data );
        $twitter       = empty( $data["twitter"] ) ? "" : awp_get_parsed_values( $data["twitter"], $posted_data );
        $linkedin      = empty( $data["linkedin"] ) ? "" : awp_get_parsed_values( $data["linkedin"], $posted_data );

        $body = array(
            'lead' => array(
                array(
                    'email'              => $email,
                    'first_name'         => $first_name,
                    'middle_name'        => $middle_name,
                    'last_name'          => $last_name,
                    'last_name2'         => $last_name2,
                    'phone_country_code' => $p_c_code,
                    'phone'              => $phone,
                    'ip'                 => $ip,
                    'address'            => $address,
                    'zip'                => $zip,
                    'city'               => $city,
                    'state'              => $state,
                    'country'            => $country,
                    'company'            => $company,
                    'website'            => $website,
                    'facebook'           => $facebook,
                    'twitter'            => $twitter,
                    'linkedin'           => $linkedin,
                )
            ),
            'actions' => array(
                array(
                    'cmd' => 'create_lead'
                ),
                array(
                    'cmd' => 'subscribe_lead_to_list',
                    'list_name' => $list_id
                )
            )
        );

        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode( $body )
        );

        $url    = "https://app.kartra.com/api?app_id={$app_id}&api_key={$api_key}&api_password={$api_password}";
        $return = wp_remote_post( $url, $args );
        $app_id = 'XXXXXXXX';
        $api_key='XXXXXXXX';
        $api_password = 'XXXXXXXX';
        $backupurl    = "https://app.kartra.com/api?app_id={$app_id}&api_key={$api_key}&api_password={$api_password}";
        awp_add_to_log( $return, $backupurl, $args, $record );
    }

    return $return;
}
