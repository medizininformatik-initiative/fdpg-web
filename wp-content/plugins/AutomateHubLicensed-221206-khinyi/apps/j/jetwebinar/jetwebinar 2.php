<?php

add_filter( 'awp_action_providers', 'aws_jetwebinar_actions', 10, 1 );

// *** ACTIONS AVAILABLE IN JetWebinar        *** 
function aws_jetwebinar_actions( $actions ) {
    $actions['jetwebinar'] = array(
        'title' => esc_html__( 'JetWebinar', 'automate_hub'),
        'tasks' => array(
            'createLead'  => esc_html__( 'Add a Contact to a List'  , 'automate_hub' ),
         // 'UpdateSubscriberData'  => esc_html__('Edit the Existing Contact'        , 'automate_hub' ),
         // 'unsubscribe' => esc_html__( 'Unsubscribe Contact from Group', 'automate_hub' ),            
        )
    );
    return $actions;
}

add_filter( 'awp_settings_tabs', 'aws_jetwebinar_settings_tab', 10, 1 );

function aws_jetwebinar_settings_tab( $providers ) {
    $providers['jetwebinar'] = 
    array('name'=>esc_html__( 'JetWebinar', 'automate_hub'), 'cat'=>array('webinar'));
    return $providers;
}

add_action( 'awp_settings_view', 'aws_jetwebinar_settings_view', 10, 1 );

// ***  SPERSE SETTINGS AND INSTRUCTIONS  *** 
function aws_jetwebinar_settings_view( $current_tab ) {
     if( $current_tab != 'jetwebinar' ) {return;}
    $nonce     = wp_create_nonce( "awp_jetwebinar_settings" );
    $id = isset($_GET['id']) ? $_GET['id'] : "";
    $api_key = isset($_GET['api_key']) ? $_GET['api_key'] : "";
    $url     = isset($_GET['url']) ? $_GET['url'] : "";
    $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";
    ?>
    <div class="platformheader"><a href="https://sperse.io/go/jetwebinar" target="_blank"><img src="<?php echo AWP_ASSETS; ?>/images/logos/jetwebinar.png" width="175" height="50" alt="JetWebinar Logo"></a><br/><br/>
    <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?>
                <br/>
                <?php 

$form_fields = '';
$app_name= 'jetwebinar';
$jetwebinar_form = new AWP_Form_Fields($app_name);

$form_fields = $jetwebinar_form->awp_wp_text_input(
    array(
        'id'            => "awp_jetwebinar_display_name",
        'name'          => "awp_jetwebinar_display_name",
        'value'         => $display_name,
        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $jetwebinar_form->awp_wp_text_input(
    array(
        'id'            => "awp_Jetwebinar_api_key",
        'name'          => "awp_Jetwebinar_api_key",
        'value'         => $api_key,
        'placeholder'   => esc_html__( 'Please enter API Key', 'automate_hub' ),
        'label'         =>  esc_html__( 'JetWebinar API Key', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);
$form_fields .= $jetwebinar_form->awp_wp_text_input(
    array(
        'id'            => "awp_Jetwebinar_domain_name",
        'name'          => "awp_Jetwebinar_domain_name",
        'value'         => $url,
        'placeholder'   => esc_html__( 'Please enter domain name', 'automate_hub' ),
        'label'         =>  esc_html__( 'JetWebinar Domain Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $jetwebinar_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_save_jetwebinar_api_key',
    )
);


$form_fields .= $jetwebinar_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $jetwebinar_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$jetwebinar_form->render($form_fields);

?>

    </div>

    <div class="wrap">
        <form id="form-list" method="post">
                    
            
            <input type="hidden" name="page" value="automate_hub"/>

            <?php
            $data=[
                        'table-cols'=>['account_name'=>'Display name','url'=>'Domain Name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
            $platform_obj= new AWP_Platform_Shell_Table('jetwebinar');
            $platform_obj->initiate_table($data);
            $platform_obj->prepare_items();
            $platform_obj->display_table();
                    
            ?>
        </form>
    </div>
    <?php
}

add_action( 'admin_post_awp_save_jetwebinar_api_key', 'aws_save_jetwebinar_api_key', 10, 0 );
    // *** SAVE SPERSE API KEY AND URL IN DB  *** 
    function aws_save_jetwebinar_api_key() {
        
            if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }


        // Security Check
        if (! wp_verify_nonce( $_POST['_nonce'], 'awp_jetwebinar_settings' ) ) {
            die( esc_html__( 'Security check Failed', 'automate_hub' ) );
        }
        $api_key = sanitize_text_field( $_POST["awp_Jetwebinar_api_key"] );
        $domain_name = sanitize_text_field( $_POST["awp_Jetwebinar_domain_name"] );
        $display_name     = sanitize_text_field( $_POST["awp_jetwebinar_display_name"] );
        // Save tokens
        $platform_obj= new AWP_Platform_Shell_Table('jetwebinar');
        $platform_obj->save_platform(['account_name'=>$display_name,'api_key'=>$api_key,'url'=>$domain_name]);


        AWP_redirect( "admin.php?page=automate_hub&tab=jetwebinar" );
    }


add_action( 'awp_action_fields', 'aws_jetwebinar_action_fields');
// ****************************************** 
// *** Map Fields for Login Action        *** 
// ****************************************** 

function aws_jetwebinar_action_fields() {
    global $wpdb;
    $integration_id = !empty($_GET['id']) ? $_GET['id'] : '';
    $sperse_accountId='';
    if(!empty($integration_id)){
        $relation_table = $wpdb->prefix . "awp_integration";
        $status_data    = $wpdb->get_row( "SELECT * FROM ".$relation_table." WHERE id = ".$integration_id, ARRAY_A );
        $result_data = json_decode($status_data['data']);
        $sperse_accountId = !empty($result_data->field_data->sperse_accountId) ? $result_data->field_data->sperse_accountId : '';

        if(!empty($sperse_accountId)){
            ?>
            <script type="text/javascript">
                var integration_id = <?php echo $sperse_accountId; ?>
            </script>
            <?php 
        }
    }
    ?>
    <script type="text/template" id="jetwebinar-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'jetwebinar',
                           'app_name'=>'JetWebinar',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/jetwebinar.png',
                           'app_icon_alter_text'=>'JetWebinar Icon',
                           'account_select_onchange'=>'getJetwebinarList',
                           'tasks'=>array(
                                        'createLead'=>array(
                                                            'task_assignments'=>array(

                                                                                    array(
                                                                                        'label'=>'JetWebinar List',
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


add_action( 'wp_ajax_awp_get_jetwebinar_account', 'aws_get_jetwebinar_accounts', 10, 0 );
function aws_get_jetwebinar_accounts() {
        if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }


    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }

    global $wpdb;
    $data = array();
    $add_user_table = $wpdb->prefix.'jetwebinar_accounts';
    $results = $wpdb->get_results( "SELECT * FROM $add_user_table", OBJECT );
    foreach ($results as $key => $value) {
            if( !empty($value->active_status) && ($value->active_status=='yes')){
                $data[$value->account_id] = ucfirst($value->account_name);
            }   
    }
    wp_send_json_success( $data );
}


add_action( 'wp_ajax_awp_get_jetwebinar_list', 'awp_get_jetwebinar_list', 10, 0 );


function jetwebinar_post($url, $fields)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}


/*
 * Get Mailchimp subscriber lists
 */
function awp_get_jetwebinar_list() {
    
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
    $platform_obj= new AWP_Platform_Shell_Table('jetwebinar');
    $data=$platform_obj->awp_get_platform_detail_by_id($id);
    if(!$data){
        die( esc_html__( 'No Data Found', 'automate_hub' ) );
    }
    $api_key =$data->api_key;
    $domain_name     = $data->url;

    if(!$api_key || !$domain_name){
        return array();
    }

    $fields = array();
    $fields["key"] = $api_key;
    $url = "https://".$domain_name."/papi/list_webinars";
    $data = jetwebinar_post($url,$fields);


    if( !is_wp_error( $data ) ) {
        $body  = json_decode( $data );

        $lists = wp_list_pluck( $body, 'title', 'webinar_id' );
        wp_send_json_success( $lists );
    } else {
        wp_send_json_error();
    }
}



// ****************************************** 
// *** SAVE THE CONNECTION FIELD MAPPING  *** 
// ****************************************** 

function awp_jetwebinar_save_integration() {

    $params = array();
    parse_str( awp_sanitize_text_or_array_field( $_POST['formData'] ), $params );
    $trigger_data      = isset( $_POST["triggerData"] ) ? awp_sanitize_text_or_array_field( $_POST["triggerData"] ) : array();
    $action_data       = isset( $_POST["actionData" ] ) ? awp_sanitize_text_or_array_field( $_POST["actionData" ] ) : array();
    $field_data        = isset( $_POST["fieldData"  ] ) ? awp_sanitize_text_or_array_field( $_POST["fieldData"  ] ) : array();

    $integration_title = isset( $trigger_data["integrationTitle"] ) ? $trigger_data["integrationTitle"] : "";    
    $form_provider_id  = isset( $trigger_data["formProviderId"  ] ) ? $trigger_data["formProviderId"  ] : "";
    $form_id           = isset( $trigger_data["formId"          ] ) ? $trigger_data["formId"          ] : "";
    $form_name         = isset( $trigger_data["formName"        ] ) ? $trigger_data["formName"        ] : "";
    $action_provider   = isset( $action_data ["actionProviderId"] ) ? $action_data ["actionProviderId"] : "";
    $task              = isset( $action_data ["task"            ] ) ? $action_data ["task"            ] : "";
    $type              = isset( $params      ["type"            ] ) ? $params      ["type"            ] : "";
    $all_data = array(
        'trigger_data' => $trigger_data,
        'action_data'  => $action_data,
        'field_data'   => $field_data,
    );

    global $wpdb;
    $integration_table = $wpdb->prefix .'awp_integration';
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

// ****************************************** 
// *** GET, MAP & SEND DATA TO SPERSE API *** 
// ****************************************** 


function awp_jetwebinar_send_data( $record, $posted_data ) {

    $temp    = json_decode( $record["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('jetwebinar');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_key=$temp->api_key;
    $domain_name=$temp->url;
    $result_data = json_decode($record['data']);	


    if(!$api_key || !$domain_name){
        return '';
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
//get_all_contact_Groups($account_name,$api_key);

    if( $task == "createLead" ) {

        $lastName  = empty( $data["lastName"] ) ? "" : awp_get_parsed_values( $data["lastName"], $posted_data );
        $email       = empty( $data["email"] ) ? "" : awp_get_parsed_values( $data["email"], $posted_data );
        $firstName       = empty( $data["firstName"] ) ? "" : awp_get_parsed_values( $data["firstName"], $posted_data );
        $listid       = !empty($data['listId'])?$data['listId']:'';


        $start_time = current_time('mysql',true);


        $fields = array(
                "webinar_id"=>$listid,
                 "date"=>date("m/d/y"),
                "time"=>date("h:i A"),
                "fname"=>$firstName,
                "lname"=>$lastName,
                "email"=>$email,
                "timezone"=>"America/Chicago"
            );


        $fields["key"] = $api_key;
        $url = "https://".$domain_name."/papi/new_registrant";
        $resp = jetwebinar_post($url,$fields);
        $resp = json_decode($resp);

                // echo "<pre>";print_r($resp);echo "</pre>";
                // echo "<pre>";print_r($fields);echo "</pre>";
                // die();
        if($resp->result == "New Registrant Created"){
            $resp = (array)$resp;
                        $resp['response']['code'] =200;
            $resp['response']['message'] =$resp['result'];
                        $resp['body'] =$resp['result'];
             awp_add_to_log( $resp, $url, $fields, $record,$start_time );


        }
        else{
            $resp = (array)$resp;
                        $resp['response']['code'] =400;
            $resp['response']['message'] =$resp['result'];
                        $resp['body'] =$resp['result'];
             awp_add_to_log( $resp, $url, $fields, $record,$start_time );
        }

    }

    return $resp;
}




function awp_jetwebinar_resend_data( $log_id,$data,$integration ) {

    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('jetwebinar');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_key=$temp->api_key;
    $domain_name=$temp->url;
   
    $start_time = current_time('mysql',true);
    if(!$api_key || !$domain_name){
        return '';
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

    if( $task == "createLead" ) {

        

        $resp = jetwebinar_post($url,$data['args']);
        $resp = json_decode($resp);

                
        if($resp->result == "New Registrant Created"){
            $resp = (array)$resp;
            $resp['response']['code'] =200;
            $resp['response']['message'] =$resp['result'];
            $resp['body'] =$resp['result'];
             awp_add_to_log( $resp, $url, $data['args'], $integration,$start_time );


        }
        else{
            $resp = (array)$resp;
            $resp['response']['code'] =400;
            $resp['response']['message'] =$resp['result'];
            $resp['body'] =$resp['result'];
            awp_add_to_log( $resp, $url, $data['args'], $integration,$start_time );
        }

    }

    $response['success']=true;
    return $response;
}

