<?php

add_filter( 'awp_action_providers', 'aws_influencersoft_actions', 10, 1 );
// ****************************************** 
// *** ACTIONS AVAILABLE IN InfluenserSoft        *** 
// ****************************************** 
function aws_influencersoft_actions( $actions ) {
    $actions['influencersoft'] = array(
        'title' => esc_html__( 'InfluencerSoft', 'automate_hub'),
        'tasks' => array(
            'createLead'  => esc_html__( 'Add a Contact to a Group'  , 'automate_hub' ),
            'UpdateSubscriberData'  => esc_html__('Edit the Existing Contact'        , 'automate_hub' ),
            'unsubscribe' => esc_html__( 'Unsubscribe Contact from Group', 'automate_hub' ),            
        )
    );
    return $actions;
}

add_filter( 'awp_settings_tabs', 'aws_influencersoft_settings_tab', 10, 1 );

function aws_influencersoft_settings_tab( $providers ) {
    $providers['influencersoft'] = 
    array(
        'name'=>esc_html__( 'InfluencerSoft', 'automate_hub'),
        'cat'=>array('crm,esp')
    );
    return $providers;
}

add_action( 'awp_settings_view', 'aws_influencersoft_settings_view', 10, 1 );

// ************************************************** 
// ***  INFLUENCERSOFT SETTINGS AND INSTRUCTIONS  *** 
// ************************************************** 
function aws_influencersoft_settings_view( $current_tab ) {
    if( $current_tab != 'influencersoft' ) { return; }
    $nonce      = wp_create_nonce( "awp_sperse_settings" );
    $api_key    = get_option( 'awp_sperse_api_key' ) ? get_option( 'awp_sperse_api_key' ) : "";
    $url        = get_option( 'awp_sperse_url') ? get_option( 'awp_sperse_url' ) : "";
    $Environment_url = array( 'https://testadmin.sperse.com','https://testadmin.sperse.com','https:/beta.sperse.com');
    $action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
    $id     = isset( $_GET['account_id'] ) ? intval( $_GET['account_id'] ) : 0;
    $sync_contact_id = isset( $_GET['sync_contacts'] ) ? intval( $_GET['sync_contacts'] ) : 0;
    switch ( $action ) {
        case 'edit'         : aws_sperse_settings( $id )                     ; aws_list_page() ; break;
        case 'status'       : aws_change_status($id)                         ; aws_list_page() ; break;
        case 'active_status': aws_change_active_status($id)                  ; aws_list_page() ; break;    
        case 'sync_contacts': aws_change_sync_contacts($id, $sync_contact_id); aws_list_page() ; break;      
        default             : aws_sperse_settings()                          ; aws_list_page() ; break;
    }
}

add_action( 'admin_post_aws_save_sperse_api_key', 'aws_save_sperse_api_key', 10, 0 );

    function aws_change_sync_contacts( $id = '', $sync_contact_id ) {
        global $wpdb;
        $config_data = aws_get_sperse_active_status_account($id);
        $ins_url = $config_data['base_url'] ? $config_data['base_url'] : "";
        $api_key = $config_data['apikey'] ? $config_data['apikey'] : "";
        $relation_table = $wpdb->prefix . "influencersoft_accounts";
        if(($sync_contact_id == 0)) {
            $targetUrl = get_home_url(). '/wp-content/plugins/automate_hub/scripts/userCreatedOrUpdated.php';            
            $properties =  array(
                "eventName" => "User.CreatedOrUpdated",
                "targetUrl" => $targetUrl
                
                );
            $url = $ins_url . "/api/services/Platform/Event/Subscribe";
            $args = array(
                'headers' => array(
                    'api-key'      => $api_key,
                    'Content-Type' => 'application/json'
                ),
                'body' => json_encode($properties)
            );
            $start_time = current_time('mysql',true);
            $return = wp_remote_post( $url, $args );
            $record = [];
            if(is_wp_error($return) || empty($return["body"])){
                aws_add_to_log( 'Wrong URL UPDATE', $url, $properties, $record,$start_time );
            }else{
                aws_add_to_log( $return, $url, $properties, $record,$start_time );
                $resp = json_decode($return["body"]);
                if(empty($resp->error)){
	                $respId = $resp->result->id;
	                $wpdb->query($wpdb->prepare("UPDATE $relation_table SET sync_contacts='%d' WHERE account_id='%d'",$respId,$id));
            	}
            }
        } else {
            $properties = array(
            'id'   => $sync_contact_id
            );
            $query = http_build_query( $properties );
            $url = $ins_url . "/api/services/Platform/Event/Unsubscribe?" . $query;
            $args = array(
                'headers' => array(
                    'api-key'      => $api_key,
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ),
                'body' => json_encode($properties)
            );
            $start_time = current_time('mysql',true);
            $return = wp_remote_post( $url, $args );
            
            $record = [];
            aws_add_to_log( $return, $url, $properties, $record,$start_time );
            
            $wpdb->query($wpdb->prepare("UPDATE $relation_table SET sync_contacts=0 WHERE account_id = '%d' ",$id));   
        }
       AWP_redirect( admin_url( 'admin.php?page=automate_hub' ) );   
    }
    
    function aws_change_active_status( $id = '' ) {
        global $wpdb;
        $relation_table = $wpdb->prefix . "influencersoft_accounts";
        $action_status = $wpdb->update( $relation_table,
                array( 'active_status' => "yes", ),
                array( 'account_id'=> $id )
            );
       AWP_redirect( admin_url( 'admin.php?page=automate_hub' ) );
    }
    
   function aws_change_status( $id = '' ) {
        global $wpdb;
        $relation_table = $wpdb->prefix . "awp_integration";
        $status_data    = $wpdb->get_row( "SELECT * FROM {$relation_table} WHERE account_id = {$id}", ARRAY_A );
        $status         = $status_data["status"];
        if ( $status ) {
            $action_status = $wpdb->update( $relation_table,
                array( 'status' => false, ),
                array( 'id'=> $id )
            );
        }else{
            $action_status = $wpdb->update( $relation_table,
                array('status' => true,),
                array( 'id'=> $id )
            );
        }
        AWP_redirect( admin_url( 'admin.php?page=automate_hub' ) );
    }
    
    function aws_list_page() {
        if ( isset( $_GET['status'] ) ) {
            $status = $_GET['status'];
        }
        ?>
<!--         <div class="wrap">
            <h1 class="wp-heading-inline"><?php esc_html_e( 'InfluencerSoft Accounts', 'automate_hub' ); ?></h1>
            <a href="<?php echo admin_url( 'admin.php?page=automate_hub' ); ?>" class="page-title-action"><?php esc_html_e( 'Add New', 'automate_hub' ); ?></a>
            <form id="form-list" method="post">
                <input type="hidden" name="page" value="automate_hub"/>
                <?php
                // $list_table = new AWP_Influencersoft_Table();
                // $list_table->prepare_items();
                // $list_table->display();
                ?>
            </form>
        </div> -->


        <div class="wrap">
            <form id="form-list" method="post">
                        
                
                <input type="hidden" name="page" value="automate_hub"/>

                <?php
                $data=[
                            'table-cols'=>['account_name'=>'Display Name','url'=>'InfluencerSoft Environment URL Path','api_key'=>' InfluencerSoft API Key','spots'=>'Active Spots','active_status'=>'Active']
                    ];
                $platform_obj= new AWP_Platform_Shell_Table('influencersoft');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();
                        
                ?>
            </form>
        </div>
        <?php
    }

function aws_sperse_settings($id=''){
    $id = isset($_GET['id']) ? $_GET['id'] : "";
    $api_key = isset($_GET['api_key']) ? $_GET['api_key'] : "";
    $url     = isset($_GET['url']) ? $_GET['url'] : "";
    $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";
   	$data=array();
   	if(!empty($id)){
   	    global $wpdb;
        $relation_table = $wpdb->prefix . "influencersoft_accounts";
        $data    = $wpdb->get_row( "SELECT * FROM {$relation_table} WHERE account_id = {$id}", ARRAY_A );
  	}

?>
<div class="no-platformheader">
<a href="https://sperse.io/go/influencersoft" target="_blank"><img src="<?php echo AWP_ASSETS; ?>/images/logos/influencersoft.png" width="367" height="50" alt="Influencersoft Logo"></a><br/><br/>
<?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?>
                <br/>
                <?php 

$form_fields = '';
$app_name= 'influencersoft';
$influencersoft_form = new AWP_Form_Fields($app_name);

$form_fields = $influencersoft_form->awp_wp_text_input(
    array(
        'id'            => "awp_influencersoft_display_name",
        'name'          => "awp_influencersoft_display_name",
        'value'         => $display_name,
        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $influencersoft_form->awp_wp_text_input(
    array(
        'id'            => "aws_sperse_api_key",
        'name'          => "aws_sperse_api_key",
        'value'         => $api_key,
        'placeholder'   => esc_html__( 'InfluencerSoft API Key', 'automate_hub' ),
        'label'         =>  esc_html__( 'Paste your InfluencerSoft API Key here', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);
$form_fields .= $influencersoft_form->awp_wp_text_input(
    array(
        'id'            => "aws_sperse_api_key",
        'name'          => "aws_sperse_api_key",
        'value'         => $url,
        'placeholder'   => esc_html__( 'Enter your InfluencerSoft Environment URL', 'automate_hub' ),
        'label'         =>  esc_html__( 'Environment URL', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'data_type'=>'url',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $influencersoft_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'aws_save_sperse_api_key',
    )
);


$form_fields .= $influencersoft_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>wp_create_nonce('aws_sperse_settings'),
    )
);
$form_fields .= $influencersoft_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$influencersoft_form->render($form_fields);

?>

</div>
<?php 
}

// ****************************************** 
// *** SAVE SPERSE API KEY AND URL IN DB  *** 
// ****************************************** 
function aws_save_sperse_api_key() {
        
        if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }

        // Security Check
        if (! wp_verify_nonce( $_POST['_nonce'], 'aws_sperse_settings' ) ) {
            die( esc_html__( 'Security check Failed', 'automate_hub' ) );
        }
    	// $settings = $_POST;
    	// global $wpdb;
    	// $influencersoft_accounts = $wpdb->prefix.'influencersoft_accounts';
    	// $where_clause = array();
    	// if(!empty($_POST['account_id'])){
    	// 	$where_clause['account_id'] = sanitize_text_field($_POST['account_id']);
    	// }
    	// unset($settings['_nonce']);
    	// unset($settings['action']);
    	// if(empty($settings['aws_active_Status'])){
    	// 	$settings['aws_active_Status'] = 'no';
    	// }
    	// $data = array(
    	// 	'account_name'=>sanitize_text_field($_POST['aws_sperse_account_name']),
    	// 	'base_url'=>sanitize_text_field($_POST['aws_sperse_url']),
    	// 	'api_key'=>sanitize_text_field($_POST['aws_sperse_api_key']),
     //        'active_status'=>$settings['aws_active_Status'],
    	// 	'sync_contacts' => 0,
    	// );
    	// if ( count($where_clause )>0 ) {
    	// 	$result = $wpdb->update( $influencersoft_accounts, $data, $where_clause );
    	//  } else {
    	//  	$result = $wpdb->insert( $influencersoft_accounts, $data );					
    	// }

        $platform_obj= new AWP_Platform_Shell_Table('influencersoft');
        $platform_obj->save_platform([
            'account_name'=>sanitize_text_field($_POST['awp_influencersoft_display_name']),
            'url'=>sanitize_text_field($_POST['aws_sperse_url']),
            'api_key'=>sanitize_text_field($_POST['aws_sperse_api_key']),
            'sync_contacts'=>'0'
        ]);
    	
        AWP_redirect( "admin.php?page=automate_hub&tab=influencersoft" );
    }

add_action( 'awp_action_fields', 'aws_influencersoft_action_fields' );

// ****************************************** 
// *** Map Fields for Login Action        *** 
// ****************************************** 
function aws_influencersoft_action_fields() {
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
    <script type="text/template" id="influencersoft-action-template">
                <?php

                    $tasks_assignments=array(
                                                            'task_assignments'=>array(

                                                                                    array(
                                                                                        'label'=>'InfluencerSoft List',
                                                                                        'type'=>'select',
                                                                                        'name'=>"influencersoft_list",
                                                                                        'model'=>'fielddata.listId',
                                                                                        'required'=>'required',
                                                                                        'onchange'=>'',
                                                                                        'select_default'=>'Select InfluencerSoft List...',
                                                                                        'option_for_loop'=>'(aitem, aindex) in fielddata.influencersoftList',
                                                                                        'option_for_value'=>'aindex',
                                                                                        'option_for_text'=>'{{aitem}}',
                                                                                        'spinner'=>array(
                                                                                                        'bind-class'=>"{'is-active': influencersoftListLoading}",
                                                                                                    )
                                                                                    ),
                                                                                    
                                                                                                                                                                     
                                                                                ),

                                                        );
                    $app_data=array(
                            'app_slug'=>'influencersoft',
                           'app_name'=>'Influencersoft',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/influencersoft.png',
                           'app_icon_alter_text'=>'Influencersoft Icon',
                           'account_select_onchange'=>'increaseCount',
                           'tasks'=>array(
                                        'createLead'=>$tasks_assignments,
                                        'UpdateSubscriberData'=>$tasks_assignments,
                                        'unsubscribe'=>$tasks_assignments,

                                    ),
                        ); 

                        require (AWP_VIEWS.'/awp_app_integration_format.php');
                ?>
    </script>
    <?php
}


add_action( 'wp_ajax_awp_get_influencersoft_account', 'aws_get_influencersoft_accounts', 10, 0 );
function aws_get_influencersoft_accounts() {
    
        if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }

    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }

    global $wpdb;
    $data = array();
    $add_user_table = $wpdb->prefix.'influencersoft_accounts';
    $results = $wpdb->get_results( "SELECT * FROM $add_user_table", OBJECT );
    foreach ($results as $key => $value) {
            if( !empty($value->active_status) && ($value->active_status=='yes')){
                $data[$value->account_id] = ucfirst($value->account_name);
            }   
    }
    wp_send_json_success( $data );
}



add_action( 'wp_ajax_awp_get_influencersoft_list', 'aws_get_sperse_list', 10, 0 );
/*
 * Get Mailchimp subscriber lists
 */
function aws_get_sperse_list() {
    
        if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }

    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }

    $integration_id = !empty($_POST['id']) ? sanitize_text_field($_POST['id']) : '';

    $config_data = awp_get_influencersoft_active_status_account($integration_id);
    if(!empty($config_data) && is_array($config_data) && count($config_data)>0){
        $api_key = $config_data['apikey'] ? $config_data['apikey'] : "";
        $account_name = $config_data['account_name'] ? $config_data['account_name'] : "";
        $ins_url = $config_data['base_url'] ? $config_data['base_url'] : "";
    }

    if( !$api_key || !$account_name ) {
        return array('empty');
    } 

    $account_name=str_replace('www.', '', $ins_url);
    $account_name=explode('.', $ins_url);
    $account_name=str_replace('https://', '', $account_name[0]);
    $account_name=str_replace('http://', '', $account_name);


    $user_rs['user_id'] = $account_name;
      // The key for forming a hash. See API section (the link in the bottom right corner of the personal account)
     $user_rs['user_rps_key'] = $api_key;
     
    // Forming the hash to the transmitted data
    $send_data['hash'] = sGetHash(null, $user_rs);

    // Calling the GetAllGroups on the client's mail function and decoding the received data
    $resp = json_decode(sSend('http://'.$account_name.'.influencersoft.com/api/GetAllGroups', $send_data));
    if($resp->error_code == 0) {

        $lists = wp_list_pluck( $resp->result, 'rass_title', 'rass_name' );
        wp_send_json_success($lists);
    } else {
        wp_send_json_error();
    }
}


add_action( 'wp_ajax_awp_get_sperse_tags', 'aws_get_sperse_tags', 10, 0 );
/*
 * Get Mailchimp subscriber lists
 */
function aws_get_sperse_tags() {
    
        if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }

    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }

    $integration_id = !empty($_POST['id']) ? sanitize_text_field($_POST['id']) : '';
    $config_data = aws_get_sperse_active_status_account($integration_id);

    if(!empty($config_data) && is_array($config_data) && count($config_data)>0){
        $api_key = $config_data['apikey'] ? $config_data['apikey'] : "";
        $ins_url = $config_data['base_url'] ? $config_data['base_url'] : "";
    }else{
        $api_key = get_option( 'aws_sperse_api_key' ) ? get_option( 'aws_sperse_api_key' ) : "";
        $ins_url = get_option( 'aws_sperse_url'     ) ? get_option( 'aws_sperse_url'     ) : "";
    }

    if( !$api_key || !$ins_url ) {
        return array('empty');
    }

    $url = $ins_url . "/api/services/CRM/Dictionary/GetTags";
    $properties = array(
        'purposeId'=>"L",
        'contactGroupId'=>'C'
    );
    $args = array(
        'headers' => array(
            'api-key'      => $api_key,
            'Content-Type' => 'application/json'
        )
    );
    $data = wp_remote_request( $url, $args );

    if( !is_wp_error( $data ) ) {
        $body  = json_decode( $data["body"] );
        $lists = wp_list_pluck( $body->result, 'name', 'id' );
        wp_send_json_success( $lists );
    } else {
        wp_send_json_error();
    }
}

add_action( 'wp_ajax_awp_get_sperse_lists', 'aws_get_sperse_lists', 10, 0 );
/*
 * Get Mailchimp subscriber lists
 */
function aws_get_sperse_lists() {
    
        if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }

    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }
    $integration_id = !empty($_POST['id']) ? sanitize_text_field($_POST['id']) : '';
    $config_data = aws_get_sperse_active_status_account($integration_id);

    if(!empty($config_data) && is_array($config_data) && count($config_data)>0){
        $api_key = $config_data['apikey'] ? $config_data['apikey'] : "";
        $ins_url = $config_data['base_url'] ? $config_data['base_url'] : "";
    }else{
        $api_key = get_option( 'aws_sperse_api_key' ) ? get_option( 'aws_sperse_api_key' ) : "";
        $ins_url = get_option( 'aws_sperse_url'     ) ? get_option( 'aws_sperse_url'     ) : "";
    }

    if( !$api_key || !$ins_url ) {
        return array('empty');
    }

    $url = $ins_url . "/api/services/CRM/Dictionary/GetLists";
    $properties = array(
        'purposeId'=>"L",
        'contactGroupId'=>'C'
    );
    $args = array(
        'headers' => array(
            'api-key'      => $api_key,
            'Content-Type' => 'application/json'
        )
    );
    $data = wp_remote_request( $url, $args );

    if( !is_wp_error( $data ) ) {
        $body  = json_decode( $data["body"] );
        $lists = wp_list_pluck( $body->result, 'name', 'id' );
        wp_send_json_success( $lists );
    } else {
        wp_send_json_error();
    }
}




// ****************************************** 
// *** SAVE THE CONNECTION FIELD MAPPING  *** 
// ****************************************** 

function awp_influencersoft_save_integration() {

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


function aws_get_sperse_active_status_account($id=''){
		global $wpdb;
		$data = array();
		$add_user_table = $wpdb->prefix.'influencersoft_accounts';

        if(!empty($id)){

            $result = $wpdb->get_row( "SELECT * FROM " . $add_user_table . " WHERE account_id =" . $id, ARRAY_A );            
            $data['apikey'] = $result['api_key'];
            $data['base_url'] = $result['base_url'];
            $data['sync_contacts'] = $result['sync_contacts'];

        }else{

            $results = $wpdb->get_results( "SELECT * FROM $add_user_table", OBJECT );
              foreach ($results as $key => $value) {
                    if( !empty($value->active_status) && ($value->active_status=='yes')){
                        $data['apikey'] = $value->api_key;
                        $data['base_url'] = $value->base_url;
                        $data['sync_contacts'] = $value->sync_contacts;
                        break;
                    }   
              }

        }

	    return $data;
	}


    function awp_get_influencersoft_active_status_account($id=''){
        global $wpdb;
        $data = array();
        $add_user_table = $wpdb->prefix.'awp_platform_settings';

        if(!empty($id)){

            $result = $wpdb->get_row( "SELECT * FROM " . $add_user_table . " WHERE id =" . $id, ARRAY_A );            
            $data['apikey'] = $result['api_key'];
            $data['base_url'] = $result['url'];
            $data['sync_contacts'] = $result['sync_contacts'];
            $data['account_name'] = $result['account_name'];

        }else{

            $results = $wpdb->get_results( "SELECT * FROM $add_user_table where platform_name='influencersoft'", OBJECT );
              foreach ($results as $key => $value) {
                    if( !empty($value->active_status) && ($value->active_status=='true')){
                        $data['apikey'] = $value->api_key;
                        $data['base_url'] = $value->url;
                        $data['sync_contacts'] = $value->sync_contacts;
                        $data['account_name'] = $value->account_name;

                        break;
                    }   
              }

        }

        return $data;
}




//Sending the query to the API service 
 function influencersoft_Send($url, $data){
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, $url);
     curl_setopt($ch, CURLOPT_POST, true);
     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // outputting the response to the variable. 
     $res = curl_exec($ch);
     curl_close($ch);
     return $res;
 }
 // Forming the transferred to the API data hash.
 function influencersoft_GetHash($params, $user_rs) {
     $params = http_build_query($params);
     $user_id = $user_rs['user_id'];
     $secret = $user_rs['user_rps_key'];
     $params = "$params::$user_id::$secret";
     return md5($params);
 }
 // Checking the received response hash.
 function influencersoft_CheckHash($resp, $user_rs) {
     $secret = $user_rs['user_rps_key'];
     $code = $resp->error_code;
     $text = $resp->error_text;
     $hash = md5("$code::$text::$secret");
     if($hash == $resp->hash)
     return true; // the hash is correct
     else
     return false; // the hash is not correct
 }



 // Sending the query to the API service 
function sSend($url, $data)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // outputting the response to the variable

    $res = curl_exec($ch);

    curl_close($ch);
    return $res;
}

// Forming the transferred to the API data hash
function sGetHash($params, $user_rs) {
    $params = http_build_query($params);
    $user_id = $user_rs['user_id'];
    $secret = $user_rs['user_rps_key'];
    $params = "$params::$user_id::$secret";
    return md5($params);
}

// Checking the received response hash
function sCheckHash($resp, $user_rs) {
    $secret = $user_rs['user_rps_key'];
    $code = $resp->error_code;
    $text = $resp->error_text;
    $hash = md5("$code::$text::$secret");
    if($hash == $resp->hash)
        return true; // the hash is correct
    else
        return false; // the hash is not correct. 
}




function get_all_contact_Groups($account_name,$api_key){

     $user_rs['user_id'] = $account_name;
  // The key for forming a hash. See API section (the link in the bottom right corner of the personal account)
 $user_rs['user_rps_key'] = $api_key;
 
// Forming the hash to the transmitted data
$send_data['hash'] = sGetHash(null, $user_rs);

// Calling the GetAllGroups on the client's mail function and decoding the received data
$resp = json_decode(sSend('http://'.$account_name.'.influencersoft.com/api/GetAllGroups', $send_data));

// Checking the service response
if(!sCheckHash($resp, $user_rs)){
    echo "Error! The response hash is not true!"; print_r($resp);
    exit;
}

if($resp->error_code == 0){
echo "Group List";
 echo "<pre>";  print_r($resp->result); echo "</pre>";
}
else
    echo "Error code:{$resp->error_code} - description: {$resp->error_text}";


}





function awp_influencersoft_send_data( $record, $posted_data ) {


    $result_data = json_decode($record['data']);

    $sperse_accountId = $result_data->field_data->activePlatformId;
	$config_data = awp_get_influencersoft_active_status_account($sperse_accountId);

	if(!empty($config_data) && is_array($config_data) && count($config_data)>0){
		$api_key = $config_data['apikey'] ? $config_data['apikey'] : "";
        $ins_url = $config_data['base_url'] ? $config_data['base_url'] : "";
        $account_name = $config_data['account_name'] ? $config_data['account_name'] : "";
	}else{
		$api_key = get_option( 'aws_sperse_api_key' ) ? get_option( 'aws_sperse_api_key' ) : "";
    	$ins_url = get_option( 'aws_sperse_url'     ) ? get_option( 'aws_sperse_url'     ) : "";
	}

    if( !$api_key || !$ins_url ) {
        return;
    }

    $account_name=str_replace('www.', '', $ins_url);
    $account_name=explode('.', $ins_url);
    $account_name=str_replace('https://', '', $account_name[0]);
    $account_name=str_replace('http://', '', $account_name);

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

        // Login to InfluencerSoft system
        $user_rs['user_id'] = $account_name;
        // The key for forming a hash. See API section (the link in the bottom right corner of the personal account).
        $user_rs['user_rps_key'] = $api_key;
        $phoneNumber  = empty( $data["phoneNumber"] ) ? "" : awp_get_parsed_values( $data["phoneNumber"], $posted_data );
        $email       = empty( $data["email"] ) ? "" : awp_get_parsed_values( $data["email"], $posted_data );
        $fullName       = empty( $data["fullName"] ) ? "" : awp_get_parsed_values( $data["fullName"], $posted_data );
        $city       = empty( $data["city"] ) ? "" : awp_get_parsed_values( $data["city"], $posted_data );
        $listid       = !empty($data['listId'])?$data['listId']:'';
        //Forming the data array for transferring to the API.
        $send_data = array(
             'rid[0]' => $listid, // <span style="font-weight: 400;" data-mce-style="font-weight: 400;">the group the subscriber will go to.</span>
             'lead_name' => $fullName,
             'lead_email' => $email,
             'lead_phone' => $phoneNumber,
             'lead_city' => $city,
             //'tag' => 'this', // unspecified tag
             //'doneurl2' => 'http://yandex.ru/', // address after subscription confirmation
             //'activation' => true, // requiring subscription confirmation 
        );
        $url = 'http://'.$account_name.'.influencersoft.com/api/AddLeadToGroup';
        $start_time = current_time('mysql',true);
         // Forming the hash to the transmitted data.
         $send_data['hash'] = influencersoft_GetHash($send_data, $user_rs);

         // Calling the AddLeadToGroup function in the API and decoding the received data.

         $resp = json_decode(influencersoft_Send('http://'.$account_name.'.influencersoft.com/api/AddLeadToGroup', $send_data));
        if($resp->error_code == 0){
            $resp = (array)$resp;
            $resp['response']['code'] =200;
            $resp['response']['message'] =$resp['error_text'];
            $resp['body'] =$resp['error_text'];
            awp_add_to_log( $resp, $url, $send_data, $record,$start_time );
            return $resp;
        }
        // Checking the service response.
        /* if(!influencersoft_CheckHash($resp, $user_rs)){
             echo "Error! The response hash is not valid!"; exit; }
         if($resp->error_code == 0)
         echo " User added to group {$send_data['rid[0]']}. Service response: {$resp->error_code}"; else
         echo "Error code:{$resp->error_code} - description: {$resp->error_text}";*/
    }
   if($task =="UpdateSubscriberData"){
       // Login to InfluencerSoft system
        $user_rs['user_id'] = $account_name;
        // The key for forming a hash. See API section (the link in the bottom right corner of the personal account).
        $user_rs['user_rps_key'] = $api_key;
        $phoneNumber  = empty( $data["phoneNumber"] ) ? "" : awp_get_parsed_values( $data["phoneNumber"], $posted_data );
        $email       = empty( $data["email"] ) ? "" : awp_get_parsed_values( $data["email"], $posted_data );
        $fullName       = empty( $data["fullName"] ) ? "" : awp_get_parsed_values( $data["fullName"], $posted_data );
        $city       = empty( $data["city"] ) ? "" : awp_get_parsed_values( $data["city"], $posted_data );
        $listid       = !empty($data['listId'])?$data['listId']:'';
        //Forming the data array for transferring to the API.
         $send_data = array(
             'rid[0]' => $listid, // <span style="font-weight: 400;" data-mce-style="font-weight: 400;">the group the subscriber will go to.</span>
             'lead_name' => $fullName,
             'lead_email' => $email,
             'lead_phone' => $phoneNumber,
             'lead_city' => $city,
             //'tag' => 'this', // unspecified tag
             //'doneurl2' => 'http://yandex.ru/', // address after subscription confirmation
             //'activation' => true, // requiring subscription confirmation
         );
        $url = 'http://'.$account_name.'.influencersoft.com/api/UpdateSubscriberData';
        $start_time = current_time('mysql',true);
        // Forming the hash to the transmitted data.
        $send_data['hash'] = influencersoft_GetHash($send_data, $user_rs);
        // Calling the AddLeadToGroup function in the API and decoding the received data.
        $resp = json_decode(influencersoft_Send('http://'.$account_name.'.influencersoft.com/api/UpdateSubscriberData', $send_data));
        if($resp->error_code == 0){
            $resp = (array)$resp;
            $resp['response']['code'] =200;
            $resp['response']['message'] =$resp['error_text'];
            $resp['body'] =$resp['error_text'];
            awp_add_to_log( $resp, $url, $send_data, $record,$start_time );
            return $resp;
        }
   }
      if($task =="unsubscribe"){
        // Login to InfluencerSoft system
        $user_rs['user_id'] = $account_name;
        // The key for forming a hash. See API section (the link in the bottom right corner of the personal account).
        $user_rs['user_rps_key'] = $api_key;
        $email       = empty( $data["email"] ) ? "" : awp_get_parsed_values( $data["email"], $posted_data );
        $start_time = current_time('mysql',true);
        $listid       = !empty($data['listId'])?$data['listId']:'';
         // Forming the data array for transferring to the API.
         $send_data = array(
           'lead_email' => $email, // user’s email 
           'rass_name' => $listid // unsubscribing group
           );
        // Forming the hash to the transmitted data.
         $send_data['hash'] = influencersoft_GetHash($send_data, $user_rs);
         $url = 'http://'.$account_name.'.influencersoft.com/api/DeleteSubscribe';
        // Forming the hash to the transmitted data.
         $resp = json_decode(influencersoft_Send('http://'.$account_name.'.influencersoft.com/api/DeleteSubscribe', $send_data));
        if($resp->error_code == 0){
            $resp = (array)$resp;
            $resp['response']['code'] =200;
            $resp['response']['message'] =$resp['error_text'];
            $resp['body'] =$resp['error_text'];
             awp_add_to_log( $resp, $url, $send_data, $record,$start_time );
             return $resp;
        }
    }
}


function awp_influencersoft_resend_data($log_id,$data,$integration){

    $result_data = json_decode($integration['data']);

   

    $sperse_accountId = $result_data->field_data->activePlatformId;
    $config_data = awp_get_influencersoft_active_status_account($sperse_accountId);

    if(!empty($config_data) && is_array($config_data) && count($config_data)>0){
        $api_key = $config_data['apikey'] ? $config_data['apikey'] : "";
        $ins_url = $config_data['base_url'] ? $config_data['base_url'] : "";
        $account_name = $config_data['account_name'] ? $config_data['account_name'] : "";
    }else{
        $api_key = get_option( 'aws_sperse_api_key' ) ? get_option( 'aws_sperse_api_key' ) : "";
        $ins_url = get_option( 'aws_sperse_url'     ) ? get_option( 'aws_sperse_url'     ) : "";
    }

    if( !$api_key || !$ins_url ) {
        return;
    }
    $data=stripslashes($data);
    $data=json_decode($data,true);
    $url=$data['url'];
    $properties=$data['args'];
    $resp=array();
    $resp['success']=true;
    if(!$url){
            $resp['success']=false;
            $resp['msg']="Syntax Error! Request is invalid";
            return $resp;
    }

    $task    = $integration["task"];


//get_all_contact_Groups($account_name,$api_key);
    if( $task == "createLead" ) {

        // Login to InfluencerSoft system
        $user_rs['user_id'] = $account_name;
        // The key for forming a hash. See API section (the link in the bottom right corner of the personal account).
        $user_rs['user_rps_key'] = $api_key;
        
        //Forming the data array for transferring to the API.
        $send_data=$properties;
        $start_time = current_time('mysql',true);
         // Forming the hash to the transmitted data.
        unset($send_data['hash']);
         $send_data['hash'] = influencersoft_GetHash($send_data, $user_rs);
         // Calling the AddLeadToGroup function in the API and decoding the received data.
         
         
         $resp = json_decode(influencersoft_Send($url, $send_data));

        if($resp->error_code == 0){
            $resp = (array)$resp;
            $resp['response']['code'] =200;
            $resp['response']['message'] =$resp['error_text'];
            $resp['body'] =$resp['error_text'];
            awp_add_to_log( $resp, $url, $send_data, $record,$start_time );
            $response['success']=true;    
            return $response;
        }
        // Checking the service response.
        /* if(!influencersoft_CheckHash($resp, $user_rs)){
             echo "Error! The response hash is not valid!"; exit; }
         if($resp->error_code == 0)
         echo " User added to group {$send_data['rid[0]']}. Service response: {$resp->error_code}"; else
         echo "Error code:{$resp->error_code} - description: {$resp->error_text}";*/
    }
   if($task =="UpdateSubscriberData"){
       // Login to InfluencerSoft system
        $user_rs['user_id'] = $account_name;
        // The key for forming a hash. See API section (the link in the bottom right corner of the personal account).
        $user_rs['user_rps_key'] = $api_key;
        $start_time = current_time('mysql',true);
        // Forming the hash to the transmitted data.
        unset($send_data['hash']);
        $send_data['hash'] = influencersoft_GetHash($send_data, $user_rs);
        // Calling the AddLeadToGroup function in the API and decoding the received data.
        $resp = json_decode(influencersoft_Send($url, $send_data));
        if($resp->error_code == 0){
            $resp = (array)$resp;
            $resp['response']['code'] =200;
            $resp['response']['message'] =$resp['error_text'];
            $resp['body'] =$resp['error_text'];
            awp_add_to_log( $resp, $url, $send_data, $record,$start_time );
            $response['success']=true;    
            return $response;
        }
   }
      if($task =="unsubscribe"){
        // Login to InfluencerSoft system
        $user_rs['user_id'] = $account_name;
        // The key for forming a hash. See API section (the link in the bottom right corner of the personal account).
        $user_rs['user_rps_key'] = $api_key;
       
        // Forming the hash to the transmitted data.
        unset($send_data['hash']);
         $send_data['hash'] = influencersoft_GetHash($send_data, $user_rs);

        // Forming the hash to the transmitted data.
         $resp = json_decode(influencersoft_Send($url, $send_data));
        if($resp->error_code == 0){
            $resp = (array)$resp;
            $resp['response']['code'] =200;
            $resp['response']['message'] =$resp['error_text'];
            $resp['body'] =$resp['error_text'];
             awp_add_to_log( $resp, $url, $send_data, $record,$start_time );
             $response['success']=true;    
             return $response;
        }
    }

}