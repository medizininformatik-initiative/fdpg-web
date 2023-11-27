<?php

add_filter( 'awp_action_providers', 'awp_lifterlms_actions', 10, 1 );

function awp_lifterlms_actions( $actions ) {
    $actions['lifterlms'] = array(
        'title' => esc_html__( 'Lifter LMS', 'automate_hub' ),
        'tasks' => array(
            'add_student'   => esc_html__( 'Add a Student', 'automate_hub')
        )
    );
    return $actions;
}

add_filter( 'awp_settings_tabs', 'awp_lifterlms_settings_tab', 10, 1 );

function awp_lifterlms_settings_tab( $providers ) {
    $providers['lifterlms'] =  array('name'=>esc_html__( 'Lifter LMS', 'automate_hub'), 'cat'=>array('crm'));
    return $providers;
}

add_action( 'awp_settings_view', 'awp_lifterlms_settings_view', 10, 1 );

function awp_lifterlms_settings_view( $current_tab ) {
    if( $current_tab != 'lifterlms' ) { return; }
    $nonce     = wp_create_nonce( "awp_lifterlms_settings" );
    $id = isset($_GET['id']) ? $_GET['id'] : "";
    $client_id = isset($_GET['client_id']) ? $_GET['client_id'] : "";
    $client_secret = isset($_GET['client_secret']) ? $_GET['client_secret'] : "";
    $url     = isset($_GET['url']) ? $_GET['url'] : "";
    $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";
     ?>
     <div class="platformheader">
 	<a href="https://sperse.io/go/lifterlms" target="_blank"><img src="<?php echo AWP_ASSETS; ?>/images/logos/lifterlms.png" width="183" height="50" alt="lifterlms Logo"></a><br/><br/>
 	<?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?><br/>
      <?php 

$form_fields = '';
$app_name= 'lifterlms';
$lifterlms_form = new AWP_Form_Fields($app_name);

$form_fields = $lifterlms_form->awp_wp_text_input(
    array(
        'id'            => "awp_lifterlms_display_name",
        'name'          => "awp_lifterlms_display_name",
        'value'         => $display_name,
        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $lifterlms_form->awp_wp_text_input(
    array(
        'id'            => "awp_lifterlms_domain_name",
        'name'          => "awp_lifterlms_domain_name",
        'value'         => $url,
        'placeholder'   => esc_html__( 'Please enter Domain Name', 'automate_hub' ),
        'label'         =>  esc_html__( 'Domain Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);
$form_fields .= $lifterlms_form->awp_wp_text_input(
    array(
        'id'            => "awp_lifterlms_consumer_key",
        'name'          => "awp_lifterlms_consumer_key",
        'value'         => $client_id,
        'placeholder'   => esc_html__( 'Please enter Consumer Key', 'automate_hub' ),
        'label'         =>  esc_html__( 'Consumer Key', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);
$form_fields .= $lifterlms_form->awp_wp_text_input(
    array(
        'id'            => "awp_lifterlms_consumer_secret",
        'name'          => "awp_lifterlms_consumer_secret",
        'value'         => $client_secret,
        'placeholder'   => esc_html__( 'Please enter Consumer Secret', 'automate_hub' ),
        'label'         =>  esc_html__( 'Consumer Secret', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $lifterlms_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_save_lifterlms_api_key',
    )
);


$form_fields .= $lifterlms_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $lifterlms_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$lifterlms_form->render($form_fields);

?>

     </div>

     <div class="wrap">
        <form id="form-list" method="post">
                    
            
            <input type="hidden" name="page" value="automate_hub"/>

            <?php
            $data=[
                        'table-cols'=>['account_name'=>'Display name','url'=>'LifterLMS Domain','client_id'=>'Consumer Key','client_secret'=>'Consumer Secret','spots'=>'Active Spots','active_status'=>'Active']
                ];
            $platform_obj= new AWP_Platform_Shell_Table('lifterlms');
            $platform_obj->initiate_table($data);
            $platform_obj->prepare_items();
            $platform_obj->display_table();
                    
            ?>
        </form>
    </div>
     <?php
}

add_action( 'admin_post_awp_save_lifterlms_api_key', 'awp_save_lifterlms_api_key', 10, 0 );

function awp_save_lifterlms_api_key() {
    
        if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }

    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'awp_lifterlms_settings' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }
    $domain_name = sanitize_text_field( $_POST["awp_lifterlms_domain_name"] );
    $consumer_key = sanitize_text_field( $_POST["awp_lifterlms_consumer_key"] );
    $consumer_secret = sanitize_text_field( $_POST["awp_lifterlms_consumer_secret"] );
    $display_name     = sanitize_text_field( $_POST["awp_lifterlms_display_name"] );
    // Save tokens

    $platform_obj= new AWP_Platform_Shell_Table('lifterlms');
    $platform_obj->save_platform(['account_name'=>$display_name,'client_id'=>$consumer_key,'client_secret'=>$consumer_secret,'url'=>$domain_name]);

    AWP_redirect( "admin.php?page=automate_hub&tab=lifterlms" );
}

add_action( 'awp_add_js_fields', 'awp_lifterlms_js_fields', 10, 1 );

function awp_lifterlms_js_fields( $field_data ) { }
add_action( 'awp_action_fields', 'awp_lifterlms_action_fields' );

function awp_lifterlms_action_fields() {
     ?>
    <script type="text/template" id="lifterlms-action-template">
        <?php
                    $app_data=array(
                            'app_slug'=>'lifterlms',
                           'app_name'=>'Lifter LMS',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/lifterlms.png',
                           'app_icon_alter_text'=>'Lifter LMS Icon',
                           'account_select_onchange'=>'',
                           'tasks'=>array(
                                        'add_student'=>array(
                                                            
                                                        ),
                                                                                
                                    ),
                        ); 

                        require (AWP_VIEWS.'/awp_app_integration_format.php');
        ?>
    </script>
    <?php
   
 }

// /*
//  * Saves connection mapping
//  */
function awp_lifterlms_save_integration() {
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

function body_builder($arr,$name,$value){
	if(!empty($value)){
		$arr[$name]=$value;
	}
	return $arr;
}


/*
 * Handles sending data to emailoctopus API
 */
function awp_lifterlms_send_data( $record, $posted_data ) {

    $temp    = json_decode( $record["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('lifterlms');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $consumer_key=$temp->client_id;
    $consumer_secret=$temp->client_secret;
    $domain_name=$temp->url;

    if(!$domain_name || !$consumer_key || !$consumer_secret) {
        return;
    }
    
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
    $email   = empty( $data["email"] ) ? "" : awp_get_parsed_values($data["email"], $posted_data);
    $first_name   = empty( $data["first_name"] ) ? "" : awp_get_parsed_values($data["first_name"], $posted_data);
    $last_name    = empty( $data["last_name"] ) ? "" : awp_get_parsed_values($data["last_name"], $posted_data);
    $name    = empty( $data["name"] ) ? "" : awp_get_parsed_values($data["name"], $posted_data);

    $nickname    = empty( $data["nickname"] ) ? "" : awp_get_parsed_values($data["nickname"], $posted_data);
    $password    = empty( $data["password"] ) ? "" : awp_get_parsed_values($data["password"], $posted_data);
    $description    = empty( $data["description"] ) ? "" : awp_get_parsed_values($data["description"], $posted_data);
    $username    = empty( $data["username"] ) ? "" : awp_get_parsed_values($data["username"], $posted_data);
    $billing_address_1    = empty( $data["billing_address_1"] ) ? "" : awp_get_parsed_values($data["billing_address_1"], $posted_data);
    $billing_address_2    = empty( $data["billing_address_2"] ) ? "" : awp_get_parsed_values($data["billing_address_2"], $posted_data);
    $billing_city    = empty( $data["billing_city"] ) ? "" : awp_get_parsed_values($data["billing_city"], $posted_data);
    $billing_country    = empty( $data["billing_country"] ) ? "" : awp_get_parsed_values($data["billing_country"], $posted_data);
    $billing_postcode    = empty( $data["billing_postcode"] ) ? "" : awp_get_parsed_values($data["billing_postcode"], $posted_data);
    $billing_state    = empty( $data["billing_state"] ) ? "" : awp_get_parsed_values($data["billing_state"], $posted_data);
    $registered_date    = empty( $data["registered_date"] ) ? "" : awp_get_parsed_values($data["registered_date"], $posted_data);
    

    


    $payload=array();
   
    

    if( $task == "add_student" ) {
    	$payload=body_builder($payload,'email',$email);
    	$payload=body_builder($payload,'username',$username);
    	$payload=body_builder($payload,'password',$password);
    	$payload=body_builder($payload,'description',$description);
    	$payload=body_builder($payload,'registered_date',$registered_date);
    	$payload=body_builder($payload,'first_name',$first_name);
    	$payload=body_builder($payload,'last_name',$last_name);
    	$payload=body_builder($payload,'nickname',$nickname);
    	$payload=body_builder($payload,'name',$name);
    	$payload=body_builder($payload,'billing_address_1',$billing_address_1);
    	$payload=body_builder($payload,'billing_address_2',$billing_address_2);
    	$payload=body_builder($payload,'billing_city',$billing_city);
    	$payload=body_builder($payload,'billing_state',$billing_state);
    	$payload=body_builder($payload,'billing_postcode',$billing_postcode);
    	$payload=body_builder($payload,'billing_country',$billing_country);

        $payload['roles']=["student"];

        $headers = array(
            'method'  => 'POST',
            "X-LLMS-CONSUMER-KEY" => $consumer_key,
            "X-LLMS-CONSUMER-SECRET" => $consumer_secret,
            "Content-Type"  => "application/json"
    	);

    	$url = "https://".$domain_name."/wp-json/llms/v1/students";

	    $temp = $payload;
	    $body=json_encode($payload);
	    $args = array(
	            "headers" => $headers,
	            "body" => $body
	    );

	    $return = wp_remote_post( $url, $args );
	   


    }
   
    
    
   

    

    $args['headers']['X-LLMS-CONSUMER-KEY']='XXXXXXXXXXX';
    $args['headers']['X-LLMS-CONSUMER-SECRET']='XXXXXXXXXXX';
    
    //resetting body to save it in log with json_decoded type
    $args["body"]=$temp;
    awp_add_to_log( $return, $url, $args, $record );
    if ( $return['response']['code'] == 200 ) {
        return array( 1 );
    } else {
        return array( 0, $return )  ;
    }
}


function awp_lifterlms_resend_data($log_id,$data,$integration){

        $temp    = json_decode( $integration["data"], true );
        $temp    = $temp["field_data"];
        $platform_obj= new AWP_Platform_Shell_Table('lifterlms');
        $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
        $consumer_key=$temp->client_id;
        $consumer_secret=$temp->client_secret;
        $domain_name=$temp->url;

	    if(!$domain_name || !$consumer_key || !$consumer_secret) {
	        return;
	    }


        $data=stripslashes($data);
        $data=preg_replace('/\s+/', '',$data); 
        $data=json_decode($data,true);
        $body=$data['args']['body'];
        $url=$data['url'];

        if(!$url){
            $response['success']=false;
            $response['msg']="Syntax Error! Request is invalid";
            return $response;
        }
        $headers = array(
            'method'  => 'POST',
            "X-LLMS-CONSUMER-KEY" => $consumer_key,
            "X-LLMS-CONSUMER-SECRET" => $consumer_secret,
            "Content-Type"  => "application/json"
    	);

        
        
        $temp = $body;
        $body=json_encode($body);
        $args = array(
                "headers" => $headers,
                "body" => $body
        );

        $return = wp_remote_post( $url, $args );


        $args['headers']['X-LLMS-CONSUMER-KEY']='XXXXXXXXXXX';
	    $args['headers']['X-LLMS-CONSUMER-SECRET']='XXXXXXXXXXX';
	    
	    //resetting body to save it in log with json_decoded type
	    $args["body"]=$temp;

        awp_add_to_log( $return, $url, $args, $integration );


        $response['success']=true;
        return $response;
}
