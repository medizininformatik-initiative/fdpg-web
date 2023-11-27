<?php

add_filter( 'awp_action_providers', 'awp_highlevel_actions', 10, 1 );

function awp_highlevel_actions( $actions ) {
    $actions['highlevel'] = array(
        'title' => esc_html__( 'Highlevel', 'automate_hub'),
        'tasks' => array(
            'add_contact' => esc_html__( 'Create New Contact', 'automate_hub'),
            'add_contact_with_opportunity' => esc_html__( 'Create Contact With Opportunity', 'automate_hub')
        )
    );

    return $actions;
}

add_filter( 'awp_settings_tabs', 'awp_highlevel_settings_tab', 10, 1 );

function awp_highlevel_settings_tab( $providers ) {
    $providers['highlevel'] =array('name'=>esc_html__( 'Highlevel', 'automate_hub'), 'cat'=>array('crm'));
    return $providers;
}

add_action( 'awp_settings_view', 'awp_highlevel_settings_view', 10, 1 );

function awp_highlevel_settings_view( $current_tab ) {
    if( $current_tab != 'highlevel' ) { return; }
    $nonce     = wp_create_nonce( "awp_highlevel_settings" );
    $id = isset($_GET['id']) ? $_GET['id'] : "";
    $api_key = isset($_GET['api_key']) ? $_GET['api_key'] : "";
    $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";
    ?>
    <div class="platformheader">
	<a href="https://sperse.io/go/highlevel" target="_blank"><img src="<?php echo AWP_ASSETS; ?>/images/logos/highlevel.png" width="232" height="50" alt="highlevel Logo"></a><br/><br/>
	<?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?>
                <br/>

                   <?php 

                $form_fields = '';
                $app_name= 'highlevel';
                $highlevel_form = new AWP_Form_Fields($app_name);

                $form_fields = $highlevel_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_highlevel_display_name",
                        'name'          => "awp_highlevel_display_name",
                        'value'         => $display_name,
                        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
                        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $highlevel_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_highlevel_api_key",
                        'name'          => "awp_highlevel_api_key",
                        'value'         => $api_key,
                        'placeholder'   => esc_html__( 'Please enter API Token', 'automate_hub' ),
                        'label'         =>  esc_html__( 'API Key', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $highlevel_form->awp_wp_hidden_input(
                    array(
                        'name'          => "action",
                        'value'         => 'awp_save_highlevel_api_key',
                    )
                );


                $form_fields .= $highlevel_form->awp_wp_hidden_input(
                    array(
                        'name'          => "_nonce",
                        'value'         =>$nonce,
                    )
                );
                $form_fields .= $highlevel_form->awp_wp_hidden_input(
                    array(
                        'name'          => "id",
                        'value'         =>wp_unslash($id),
                    )
                );


                $highlevel_form->render($form_fields);

                ?>

    </div>
    <div class="wrap">
        <form id="form-list" method="post">
                    
            
            <input type="hidden" name="page" value="automate_hub"/>

            <?php
            $data=[
                        'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
            $platform_obj= new AWP_Platform_Shell_Table('highlevel');
            $platform_obj->initiate_table($data);
            $platform_obj->prepare_items();
            $platform_obj->display_table();
                    
            ?>
        </form>
    </div>
    <?php
}

add_action( 'admin_post_awp_save_highlevel_api_key', 'awp_save_highlevel_api_key', 10, 0 );

function awp_save_highlevel_api_key() {
    
        if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }

    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'awp_highlevel_settings' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }

    $api_key = sanitize_text_field( $_POST["awp_highlevel_api_key"] );
    $display_name     = sanitize_text_field( $_POST["awp_highlevel_display_name"] );
    // Save tokens
    $platform_obj= new AWP_Platform_Shell_Table('highlevel');
    $platform_obj->save_platform(['account_name'=>$display_name,'api_key'=>$api_key]);


    AWP_redirect( "admin.php?page=automate_hub&tab=highlevel" );
}

add_action( 'awp_add_js_fields', 'awp_highlevel_js_fields', 10, 1 );

function awp_highlevel_js_fields( $field_data ) {}



add_action( 'wp_ajax_awp_get_custom_fields', 'awp_get_custom_fields', 10, 0 );
function awp_get_custom_fields(){
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
        $platform_obj= new AWP_Platform_Shell_Table('highlevel');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }
        $api_key =$data->api_key;

        if( ! $api_key ) {
            return array();
        }
        $headers = array(
            "Authorization" => "Bearer " .$api_key,
            "Content-Type"  => "application/json",
            "Accept"        => "application/json"
        );

        $url = "https://rest.gohighlevel.com/v1/custom-fields/";
        

        $args = array(
                    "headers" => $headers,
            );
        $data = wp_remote_request( $url, $args );
        $data=json_decode($data['body'],true);
        wp_send_json_success( $data );
        
     
        die();
        //custom fields
}


add_action( 'awp_action_fields', 'awp_highlevel_action_fields' );

function awp_highlevel_action_fields() {
    ?>
    <script type="text/template" id="highlevel-action-template">
		<?php
                    $app_data=array(
                            'app_slug'=>'highlevel',
                           'app_name'=>'Highlevel',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/highlevel.png',
                           'app_icon_alter_text'=>'Highlevel Icon',
                           'account_select_onchange'=>'getPipelines',
                           'tasks'=>array(
                                        'add_contact'=>array(
                                                       ),

                                        'add_contact_with_opportunity'=>array(
                                                            'task_assignments'=>array(

                                                                                    array(
                                                                                        'label'=>'Pipelines',
                                                                                        'type'=>'select',
                                                                                        'name'=>"pipelineId",
                                                                                        'model'=>'fielddata.pipelineId',
                                                                                        'required'=>'required',
                                                                                        'onchange'=>'getStages',
                                                                                        'select_default'=>'Select Pipeline...',
                                                                                        'option_for_loop'=>'(item, index) in fielddata.pipelineList',
                                                                                        'spinner'=>array(
                                                                                                        'bind-class'=>"{'is-active': listLoading}",
                                                                                                    )
                                                                                    ),
                                                                                    array(
                                                                                        'label'=>'Stages',
                                                                                        'type'=>'select',
                                                                                        'name'=>"stageId",
                                                                                        'model'=>'fielddata.stageId',
                                                                                        'required'=>'required',
                                                                                        'onchange'=>'getOwnerList',
                                                                                        'select_default'=>'Select Stage...',
                                                                                        'option_for_loop'=>'(item, index) in fielddata.stageList',
                                                                                        'spinner'=>array(
                                                                                                        'bind-class'=>"{'is-active': stageListLoading}",
                                                                                                    )
                                                                                    ),
                                                                                    // array(
                                                                                    //     'label'=>'Owner',
                                                                                    //     'type'=>'select',
                                                                                    //     'name'=>"ownerId",
                                                                                    //     'model'=>'fielddata.ownerId',
                                                                                    //     'required'=>'required',
                                                                                    //     'onchange'=>'',
                                                                                    //     'select_default'=>'Select Owner...',
                                                                                    //     'option_for_loop'=>'(item, index) in fielddata.ownerList',
                                                                                    //     'spinner'=>array(
                                                                                    //                     'bind-class'=>"{'is-active': ownerListLoading}",
                                                                                    //                 )
                                                                                        
                                                                                    // ),

                                                                                    array(
                                                                                        'label'=>'Status',
                                                                                        'type'=>'select',
                                                                                        'name'=>"statusId",
                                                                                        'model'=>'fielddata.statusId',
                                                                                        'required'=>'required',
                                                                                        'onchange'=>'',
                                                                                        'select_default'=>'Select Status...',
                                                                                        'option_for_loop'=>'(item, index) in fielddata.statusList',
                                                                                        
                                                                                    ),

                                                                                    
                                                                                   
                                                                                    
                                                                                                                                                                        
                                                                                ),
                                                            // 'custom_fields'=> array(

                                                                                    
                                                            //                     ),

                                                        ),

                                                                                
                                    ),

                        ); 

                        require (AWP_VIEWS.'/awp_app_integration_format.php');
        ?>
    </script>

    <?php
}

add_action( 'wp_ajax_awp_get_owners_list', 'awp_get_owners_list', 10, 0 );
function awp_get_owners_list(){
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
    $platform_obj= new AWP_Platform_Shell_Table('highlevel');
    $data=$platform_obj->awp_get_platform_detail_by_id($id);
    if(!$data){
        die( esc_html__( 'No Data Found', 'automate_hub' ) );
    }
    $api_key =$data->api_key;

    if( ! $api_key ) {
        return array();
    }

    $headers = array(
            "Authorization" => "Bearer " .$api_key,
            "Content-Type"  => "application/json",
            "Accept"        => "application/json"
    );

    $url = "https://rest.gohighlevel.com/v1/users/";


    $args = array(
            "headers" => $headers,
    );
   

 
    $data = wp_remote_request( $url, $args );

    if( is_wp_error( $data ) ) {
        wp_send_json_error();
    }
    $body  = json_decode( $data["body"] );
    echo 'tempholded';
    $lists = wp_list_pluck( $body->pipelines, 'name', 'id' );
    wp_send_json_success( $lists );
}

add_action( 'wp_ajax_awp_get_stages_list', 'awp_get_stages_list', 10, 0 );
function awp_get_stages_list(){
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

    if (!isset( $_POST['pipelineId'] ) ) {
        die( esc_html__( 'Invalid Request', 'automate_hub' ) );
    }


    $id=sanitize_text_field($_POST['platformid']);
    $pipelineId=sanitize_text_field($_POST['pipelineId']);
    $platform_obj= new AWP_Platform_Shell_Table('highlevel');
    $data=$platform_obj->awp_get_platform_detail_by_id($id);
    if(!$data){
        die( esc_html__( 'No Data Found', 'automate_hub' ) );
    }
    $api_key =$data->api_key;

    if( ! $api_key ) {
        return array();
    }

    $headers = array(
            "Authorization" => "Bearer " .$api_key,
            "Content-Type"  => "application/json",
            "Accept"        => "application/json"
    );

    $url = "https://rest.gohighlevel.com/v1/pipelines/";


    $args = array(
            "headers" => $headers,
    );
   


 
    $data = wp_remote_request( $url, $args );

    if( is_wp_error( $data ) ) {
        wp_send_json_error();
    }
    $body  = json_decode( $data["body"] );
    
    foreach ($body->pipelines as $key => $pipeline) {
        if($pipeline->id==$pipelineId){
            $lists = wp_list_pluck( $pipeline->stages, 'name', 'id' );
            wp_send_json_success( $lists );
        }

    }
    wp_send_json_error("Stages not found");
}

add_action( 'wp_ajax_awp_get_pipeline_list', 'awp_get_pipeline_list', 10, 0 );
function awp_get_pipeline_list(){
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
    $platform_obj= new AWP_Platform_Shell_Table('highlevel');
    $data=$platform_obj->awp_get_platform_detail_by_id($id);
    if(!$data){
        die( esc_html__( 'No Data Found', 'automate_hub' ) );
    }
    $api_key =$data->api_key;

    if( ! $api_key ) {
        return array();
    }

    $headers = array(
            "Authorization" => "Bearer " .$api_key,
            "Content-Type"  => "application/json",
            "Accept"        => "application/json"
    );

    $url = "https://rest.gohighlevel.com/v1/pipelines/";


    $args = array(
            "headers" => $headers,
    );
   

    // $url = "https://api2.autopilothq.com/v1/lists";
    // $args = array(
    //     'headers' => array(
    //         'Content-Type'    => 'application/json',
    //         'autopilotapikey' => $api_key
    //     )
    // );

 
    $data = wp_remote_request( $url, $args );

    if( is_wp_error( $data ) ) {
        wp_send_json_error();
    }
    $body  = json_decode( $data["body"] );
    

    $lists = wp_list_pluck( $body->pipelines, 'name', 'id' );
    wp_send_json_success( $lists );
}
/*
 * Saves connection mapping
 */
function awp_highlevel_save_integration() {
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

function awp_prepare_highlevel_attachment($endpoint,$attachment_key,$genuine_key){
    $filesize=0;
    $path = str_replace( site_url('/'), ABSPATH, esc_url( $endpoint) );
    if ( is_file( $path ) ){
        $filesize = filesize( $path );
    }
    $filePath = $endpoint;
    $fileInfo = pathinfo($filePath);
    $extension = $fileInfo['extension'];
    $filename = $fileInfo['filename'];
    if($extension == 'jpg' || $extension == 'png'){
        $extension='image/'.$extension;
    }                        
    
    return array(
        'meta'=>array(
            'fieldname'=>$genuine_key,
            'size' => $filesize,
            'originalname'=>$filename,
            'encoding'=>'7bit',
            'mimetype' => $extension,
            'uuid' => $attachment_key,
        ),
        'url' => $endpoint,
    );
}

function awp_get_highlevel_custom_fields($data,$posted_data){
    $custom_fields=array();
    foreach ($data as $key => $value) {
        

        if(substr($key,0,4) == 'cus_' && !strpos($key, 'dis')){
            $genuine_key=substr($key,4);
            $value  = empty( $data[$key] ) ? "" : awp_get_parsed_values( $data[$key], $posted_data );
            $custom_fields[$genuine_key]=$value;
        }
        elseif(substr($key,0,8) == 'cusfile_' && !strpos($key, 'dis')){
            //file handling
            $genuine_key=substr($key,8);
            $value  = empty( $data[$key] ) ? "" : awp_get_parsed_values( $data[$key], $posted_data );
            if(isJson($value)){
                $fileendpoints= json_decode($value,true);
                $attachments=array();
                foreach ($fileendpoints as $key => $endpoint) {
                        
                        $attachment_key = substr(md5(mt_rand()), 0, 7);
                        $attachments[$attachment_key] =awp_prepare_highlevel_attachment($endpoint,$attachment_key,$genuine_key);
                }

                $custom_fields[$genuine_key]=$attachments;
            }
            else{
                $attachment_key = substr(md5(mt_rand()), 0, 7);
                $attachments[$attachment_key] =awp_prepare_highlevel_attachment($value,$attachment_key,$genuine_key);
                $custom_fields[$genuine_key]=$attachments;
            }
        
        }
    }
    
    return $custom_fields;
}


/*
 * Handles sending data to highlevel API
 */
function awp_highlevel_send_data( $record, $posted_data ) {

    $temp    = json_decode( $record["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('highlevel');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_key=$temp->api_key;
    
    if( !$api_key ) {
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
    if( $task == "add_contact" || $task == 'add_contact_with_opportunity' ) {
        $email      = empty( $data["email"] ) ? "" : awp_get_parsed_values( $data["email"], $posted_data );
        $first_name = empty( $data["firstName"] ) ? "" : awp_get_parsed_values( $data["firstName"], $posted_data );
        $last_name  = empty( $data["lastName"] ) ? "" : awp_get_parsed_values( $data["lastName"], $posted_data );
        
        $name  = empty( $data["name"] ) ? "" : awp_get_parsed_values( $data["name"], $posted_data );
        $phoneNumber  = empty( $data["phoneNumber"] ) ? "" : awp_get_parsed_values( $data["phoneNumber"], $posted_data );
        $address1  = empty( $data["address1"] ) ? "" : awp_get_parsed_values( $data["address1"], $posted_data );
        $city  = empty( $data["city"] ) ? "" : awp_get_parsed_values( $data["city"], $posted_data );
        $state  = empty( $data["state"] ) ? "" : awp_get_parsed_values( $data["state"], $posted_data );
        $country  = empty( $data["country"] ) ? "" : awp_get_parsed_values( $data["country"], $posted_data );
        $postCode  = empty( $data["postCode"] ) ? "" : awp_get_parsed_values( $data["postCode"], $posted_data );
        $website  = empty( $data["website"] ) ? "" : awp_get_parsed_values( $data["website"], $posted_data );
        $companyName  = empty( $data["companyName"] ) ? "" : awp_get_parsed_values( $data["companyName"], $posted_data );
        $refAffiliateCode  = empty( $data["refAffiliateCode"] ) ? "" : awp_get_parsed_values( $data["refAffiliateCode"], $posted_data );
        $raw_tag  = empty( $data["tags"] ) ? "" : awp_get_parsed_values( $data["tags"], $posted_data );
        

        $tags = explode(',', $raw_tag);

        $custom_fields=awp_get_highlevel_custom_fields($data,$posted_data);

        $headers = array(
            "Authorization" => "Bearer " .$api_key,
            "Content-Type"  => "application/json",
            "Accept"        => "application/json"
        );

        $url = "https://rest.gohighlevel.com/v1/contacts/";

        $body = array(
            "firstName"    => $first_name,
            "lastName"     => $last_name,
            "email" => $email,
            "phone" => $phoneNumber,
            "name" => $name,
            "address1" => $address1,
            "city" => $city,
            "state" => $state,
            "country" => $country,
            "postalCode" => $postCode,
            "website" => $website ,
            "tags" => $tags,
            "companyName" => $companyName,

        );

        if(count($custom_fields)){
            $body['customField']=$custom_fields;
        }

        $response=check_if_contact_exists($api_key,$body);

        if(!$response['success']){

            //contact does not exists so need to be created
            $args = array(
                "headers" => $headers,
                "body" => json_encode( $body )
            );
            $response = wp_remote_post( $url, $args );
            $args['headers']['Authorization'] = "Bearer XXXXXXXXX";
            awp_add_to_log( $response, $url, $args, $record );
        
        }
        else{
            //contact already exists update contact tags if needed
            $respo=json_decode($response['body'],true);
            $contact_id=$respo['contact']['id'];
            $url='https://rest.gohighlevel.com/v1/contacts/'.$contact_id.'/tags/';
            $body=array(
                'tags'=>$tags,
            );
            $args = array(
                "headers" => $headers,
                "body" => json_encode( $body )
            );
            $response = wp_remote_post( $url, $args );
            $args['headers']['Authorization'] = "Bearer XXXXXXXXX";
            awp_add_to_log( $response, $url, $args, $record );

        }

      
    
        if($response['response']['code'] == '200' && $task == 'add_contact_with_opportunity'){
            $res=json_decode($response['body'],true);
            $contact_id=$res['contact']['id'];
            $pipelineId=empty( $data["pipelineId"] ) ? $email : awp_get_parsed_values( $data["pipelineId"], $posted_data );
            $opportunityTitle=empty( $data["opportunityTitle"] ) ? $first_name.' '.$last_name : awp_get_parsed_values( $data["opportunityTitle"], $posted_data );
            $opportunityTitle=empty( $opportunityTitle ) ? $email : $opportunityTitle;
            $opportunityTitle=empty( $opportunityTitle ) ? 'Opportunity' : $opportunityTitle;
            $stageId=empty( $data["stageId"] ) ? "" : awp_get_parsed_values( $data["stageId"], $posted_data );
            $statusId=empty( $data["statusId"] ) ? "" : awp_get_parsed_values( $data["statusId"], $posted_data );
            
            $leadValue=empty( $data["leadValue"] ) ? "" : awp_get_parsed_values( $data["leadValue"], $posted_data );
            $source=empty( $data["source"] ) ? "" : awp_get_parsed_values( $data["source"], $posted_data );
            $ownerId=empty( $data["ownerId"] ) ? "" : awp_get_parsed_values( $data["ownerId"], $posted_data );
            

            $data=array(
                    'api_key'=>$api_key,
                    'pipelineId'=>$pipelineId,
                    'contact_id'=>$contact_id,
                    'opportunityTitle'=>$opportunityTitle,
                    'stageId'=>$stageId,
                    'statusId'=>$statusId,
                    'leadValue'=>$leadValue,
                    'source'=>$source,
                    'ownerId'=>$ownerId,

                );

            if(!empty($contact_id) && !empty($opportunityTitle) && !empty($stageId) && !empty($statusId)){
             
                attach_opportunity($data,$record);
            }
            else{
            }

        }
        return $response;
    }

    
}


function check_if_contact_exists($api_key,$params){
    $headers = array(
            "Authorization" => "Bearer " .$api_key,
            "Content-Type"  => "application/json",
            "Accept"        => "application/json"
    );
    $url = 'https://rest.gohighlevel.com/v1/contacts/lookup?email='.$params['email'].'&phone='.$params['phone'];

    $args = array(
        "headers" => $headers,
        "body" => '',
    );
    $response = wp_remote_get( $url, $args );

    if($response['response']['code']=='200'){
        $res=json_decode($response['body'],true);
        $contact_id=$res['contacts'][0]['id'];
        
        $return=array(
            'success'=>true,
            'body'=> json_encode(array('contact'=>array('id'=>$contact_id))),
            'response'=>array('code'=>'200'),
        );
    }
    else{
        $return=array('success'=>false,'response'=>array('code'=>'123'));
    }

    return $return;
}

function fetch_opportunities($url,$params){
    $headers = array(
            "Authorization" => "Bearer " .$params['api_key'],
            "Content-Type"  => "application/json",
            "Accept"        => "application/json"
    );
    $url=empty($url)?"https://rest.gohighlevel.com/v1/pipelines/".$params['pipelineId']."/opportunities/?limit=100":$url;

    $args = array(
            "headers" => $headers,
            "body" => '',
    );
    $response = wp_remote_get( $url, $args );
    $opportunities=json_decode($response['body'],true);
    return $opportunities;
}

function check_if_opportunity_exists($params){
    
    $safecounter=0;
    $opportunities=fetch_opportunities("",$params);
    $allopponames=array();
    while(!empty($opportunities['meta']['nextPageUrl'])){
        
        if(count($opportunities['opportunities'])){
            foreach ($opportunities['opportunities'] as $key => $oppo) {
                array_push($allopponames, $oppo['name']);
            }

        }

        $opportunities=fetch_opportunities($opportunities['meta']['nextPageUrl'],$params);
        
        $safecounter=$safecounter+1;
        if($safecounter>100){
            //safe counter if the api is not responding as expected then we will come out of this loop
            return false;
        }
    }
    if(in_array($params['opportunityTitle'], $allopponames)){
        return true;
    }
    else{
        return false;
    }

}
function attach_opportunity($params,$record){

        $opportunity_exists=check_if_opportunity_exists($params);
        if(!$opportunity_exists){
            $headers = array(
                "Authorization" => "Bearer " .$params['api_key'],
                "Content-Type"  => "application/json",
                "Accept"        => "application/json"
            );

            

            $url = "https://rest.gohighlevel.com/v1/pipelines/".$params['pipelineId']."/opportunities/";

            $body = array(
                "title"    => $params['opportunityTitle'],
                "status"     => $params['statusId'],
                "stageId" => $params['stageId'],
                "contactId" => $params['contact_id'],
                "monetaryValue" => (int)$params['leadValue'],
                "assignedTo" => $params['ownerId'],
                "source" => $params['source'],

            );

            $args = array(
                "headers" => $headers,
                "body" => json_encode( $body )
            );
            $response = wp_remote_post( $url, $args );

            
            $res=json_decode($response['body'],true);
      
            if( isset($res['contactId']['message']) && strpos($res['contactId']['message'], 'opportunity exists') !== false){
                //ignore this log
            }
            else{
                //resetting body to save it in log with json_decoded type
                $args['body']=$body;
                $args['headers']['Authorization'] = "Bearer XXXXXXXXX";
                awp_add_to_log( $response, $url, $args, $record );    
            }
            
        
        }
        

}



function awp_highlevel_resend_data($log_id,$data,$integration){
    
        $temp    = json_decode( $integration["data"], true );
        $temp    = $temp["field_data"];
        $platform_obj= new AWP_Platform_Shell_Table('highlevel');
        $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
        $api_key=$temp->api_key;

        if(!$api_key ) {
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
                "Authorization" => "Bearer ".$api_key,
                "Content-Type"  => "application/json"
        );

        
        $temp = $body;
        $body=json_encode($body);
        $args = array(
                "headers" => $headers,
                "body" => $body
        );

        $return = wp_remote_post( $url, $args );

        $args['headers']['Authorization']='api_key  XXXXXXXXXXX';
        $args["body"]=$temp;
        awp_add_to_log( $return, $url, $args, $integration );


        $response['success']=true;
        return $response;
}
