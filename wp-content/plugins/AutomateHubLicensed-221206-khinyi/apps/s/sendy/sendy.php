<?php

add_filter( 'awp_action_providers', 'awp_sendy_actions', 10, 1 );

function awp_sendy_actions( $actions ) {
    $actions['sendy'] = array(
        'title' => esc_html__( 'Sendy', 'automate_hub'),
        'tasks' => array('subscribe'   => esc_html__( 'Subscribe To List', 'automate_hub'))
    );
    return $actions;
}

add_filter( 'awp_settings_tabs', 'awp_sendy_settings_tab', 10, 1 );

function awp_sendy_settings_tab( $providers ) {
    $providers['sendy'] = array('name'=>esc_html__( 'Sendy', 'automate_hub'), 'cat'=>array('crm','esp'));
    return $providers;
}

add_action( 'awp_settings_view', 'awp_sendy_settings_view', 10, 1 );

function awp_sendy_settings_view( $current_tab ) {
    if( $current_tab != 'sendy' ) { return; }
    $nonce      = wp_create_nonce( "awp_sendy_settings" );
    $id = isset($_GET['id']) ? $_GET['id'] : "";
    $api_key = isset($_GET['api_key']) ? $_GET['api_key'] : "";
    $url     = isset($_GET['url']) ? $_GET['url'] : "";
    $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";
    ?>
    <div class="platformheader">
	<a href="https://sperse.io/go/sendy" target="_blank"><img src="<?php echo AWP_ASSETS; ?>/images/logos/sendy.png" width="185" height="50" alt="Sendy Logo"></a><br/><br/>
	<?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?>
                <br/>
                <?php 

$form_fields = '';
$app_name= 'sendy';
$sendy_form = new AWP_Form_Fields($app_name);

$form_fields = $sendy_form->awp_wp_text_input(
    array(
        'id'            => "awp_sendy_display_name",
        'name'          => "awp_sendy_display_name",
        'value'         => $display_name,
        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $sendy_form->awp_wp_text_input(
    array(
        'id'            => "awp_sendy_api_key",
        'name'          => "awp_sendy_api_key",
        'value'         => $api_key,
        'placeholder'   => esc_html__( 'Please enter API Key', 'automate_hub' ),
        'label'         =>  esc_html__( 'API Key', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $sendy_form->awp_wp_text_input(
    array(
        'id'            => "awp_sendy_url",
        'name'          => "awp_sendy_url",
        'value'         => $url,
        'placeholder'   => esc_html__( 'Please enter Sendy Installation URL', 'automate_hub' ),
        'label'         =>  esc_html__( 'Installation URL', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $sendy_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_save_sendy_api_key',
    )
);


$form_fields .= $sendy_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $sendy_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$sendy_form->render($form_fields);

?>


    </div>
    <div class="wrap">
        <form id="form-list" method="post">
                    
            
            <input type="hidden" name="page" value="automate_hub"/>

            <?php
            $data=[
                        'table-cols'=>['account_name'=>'Display name','url'=>'Installation URL ','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
            $platform_obj= new AWP_Platform_Shell_Table('sendy');
            $platform_obj->initiate_table($data);
            $platform_obj->prepare_items();
            $platform_obj->display_table();
                    
            ?>
        </form>
    </div>
    <?php
}
add_action( 'admin_post_awp_save_sendy_api_key', 'awp_save_sendy_api_key', 10, 0 );

function awp_save_sendy_api_key() { // Security Check

      if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }
    if (! wp_verify_nonce( $_POST['_nonce'], 'awp_sendy_settings' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }
    $api_key = sanitize_text_field( $_POST["awp_sendy_api_key"] );
    $url     = sanitize_text_field( $_POST["awp_sendy_url"] );
    $display_name     = sanitize_text_field( $_POST["awp_sendy_display_name"] );

    // Save tokens
    $platform_obj= new AWP_Platform_Shell_Table('sendy');
    $platform_obj->save_platform(['account_name'=>$display_name,'api_key'=>$api_key,'url'=>$url]);
    AWP_redirect( "admin.php?page=automate_hub&tab=sendy" );
}
add_action( 'awp_add_js_fields', 'awp_sendy_js_fields', 10, 1 );

function awp_sendy_js_fields( $field_data ) {}

add_action( 'awp_action_fields', 'awp_sendy_action_fields' );

function awp_sendy_action_fields() { ?>
<script type="text/template" id="sendy-action-template">
		        <?php

                    $app_data=array(
                            'app_slug'=>'sendy',
                           'app_name'=>'Sendy',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/sendy.png',
                           'app_icon_alter_text'=>'Sendy Icon',
                           'account_select_onchange'=>'',
                           'tasks'=>array(
                                        'subscribe'=>array(
                                                            'task_assignments'=>array(

                                                                                    array(
                                                                                        'label'=>'Sendy List ID',
                                                                                        'type'=>'text',
                                                                                        'name'=>"list_id",
                                                                                        'model'=>'fielddata.listId',
                                                                                        'required'=>'required',
                                                                                        'onchange'=>'',

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

/* Saves connection mapping */
function awp_sendy_save_integration() {
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
        if ( $type != 'update_integration' &&  !empty( $id ) ) { exit; }
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

/* Handles sending data to Mailjet API */
function awp_sendy_send_data( $record, $posted_data ) {
    $temp    = json_decode( $record["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('sendy');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_key=$temp->api_key;
    $ins_url=$temp->url;

    
    if( !$api_key || !$ins_url ) { return; }
    $data    = json_decode( $record["data"], true );
    $data    = $data["field_data"];
    $task    = $record["task"];
    $list_id = $data["listId"];

        $record_data = json_decode( $record["data"], true );
    if( array_key_exists( "cl", $record_data["action_data"]) ) {
        if( $record_data["action_data"]["cl"]["active"] == "yes" ) {
            $chk = awp_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data );
            if( !awp_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data ) ) {
                return;
            }
        }
    }
    $email   = empty( $data["email"] ) ? "" : awp_get_parsed_values( $data["email"], $posted_data );
    if( $task == "subscribe" ) {
        $name          = empty( $data["name"] ) ? "" : awp_get_parsed_values( $data["name"], $posted_data );
        $country       = empty( $data["country"] ) ? "" : awp_get_parsed_values( $data["country"], $posted_data );
        $ipaddress     = empty( $data["ipaddress"] ) ? "" : awp_get_parsed_values( $data["ipaddress"], $posted_data );
        $referrer      = empty( $data["referrer"] ) ? "" : awp_get_parsed_values( $data["referrer"], $posted_data );
        $custom_fields = empty( $data["custom_fields"] ) ? "" : awp_get_parsed_values( $data["custom_fields"], $posted_data );
        $data = array(
            'api_key' => $api_key,
            'list'    => $list_id,
            'name'    => $name,
            'email'   => $email
        );
        if( $country ) { $data['country'] = $country; }
        if( $ipaddress ) { $data['ipaddress'] = $ipaddress; }
        if( $referrer ) { $data['referrer'] = $referrer; }
        if( $custom_fields ) {
            $custom_fields = explode( ',', trim( $custom_fields) );
            foreach( $custom_fields as $single ) {
                $parts = explode( ':', $single );
                $data[$parts[0]] = $parts[1];
            }
        }
        $url = $ins_url . "/subscribe";
        $args = array( 'headers' => array('Content-Type' => 'application/x-www-form-urlencoded'), 'body' => $data);
        $return = wp_remote_post( $url, $args );
                $args['body']['api_key']='XXXXXXXXXXX';

        awp_add_to_log( $return, $url, $args, $record );
    }

    if( $task == "unsubscribe" ) {
        $url = $ins_url . "/unsubscribe";
        $data = array('api_key' => $api_key, 'list' => $list_id, 'email' => $email);
        $args = array('headers' => array('Content-Type' => 'application/x-www-form-urlencoded'), 'body' => $data);
        $return = wp_remote_post( $url, $args );
        $args['body']['api_key']='XXXXXXXXXXX';
        awp_add_to_log( $return, $url, $args, $record );
    }
    return $return;
}
