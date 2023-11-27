<?php

$platform_obj= new AWP_Platform_Shell_Table('agilecrm');

add_filter( 'awp_action_providers', 'awp_agilecrm_actions', 10, 1 );

function awp_agilecrm_actions( $actions ) {
    $actions['agilecrm'] = array('title'        => esc_html__( 'Agile CRM'         , 'automate_hub'),
                'tasks' => array('add_contact'  => esc_html__( 'Create New Contact', 'automate_hub'))
    );
    return $actions;
}

add_filter( 'awp_settings_tabs', 'awp_agilecrm_settings_tab', 10, 1 );

function awp_agilecrm_settings_tab( $providers ) {
    $providers['agilecrm'] = array( 'name'=>esc_html__( 'Agile CRM', 'automate_hub'), 'cat'=>array('crm'));
    return $providers;
}

add_action( 'awp_settings_view', 'awp_agilecrm_settings_view', 10, 1 );

function awp_agilecrm_settings_view( $current_tab ) {
    if( $current_tab != 'agilecrm' ) {
        return;
    }
    $nonce     = wp_create_nonce( "awp_agilecrm_settings" );

    $id = isset($_GET['id']) ? intval( sanitize_text_field($_GET['id'] )) : "";
    $api_key = isset($_GET['api_key']) ? sanitize_text_field( $_GET['api_key']) : "";
    $url     = isset($_GET['url']) ? sanitize_text_field( $_GET['url']) : "";
    $email     = isset($_GET['email']) ? sanitize_text_field($_GET['email']) : "";
    $display_name     = isset($_GET['account_name']) ? sanitize_text_field( $_GET['account_name']) : "";


    ?>


    <div class="platformheader">



    <a href="https://sperse.io/go/agilecrm" target="_blank"><img src="<?php echo esc_url(AWP_ASSETS.'/images/logos/agilecrm.png'); ?>" width="225" height="50" alt="AgileCRM Logo"></a><br/><br/>

    <?php 
        require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
        $instruction_obj = new AWP_Updates_Manager(sanitize_text_field($_GET['tab']));
        $instruction_obj->prepare_instructions();


    ?>

    <br/>

    

    <?php 

            
$form_fields = '';
$app_name= 'agilecrm';
$agilecrm_form = new AWP_Form_Fields($app_name);

$form_fields = $agilecrm_form->awp_wp_text_input(
    array(
        'id'            => "awp_agilecrm_display_name",
        'name'          => "awp_agilecrm_display_name",
        'value'         => $display_name,
        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);
$form_fields .= $agilecrm_form->awp_wp_text_input(
    array(
        'id'            => "awp_agilecrm_subdomain",
        'name'          => "awp_agilecrm_subdomain",
        'value'         => $url,
        'placeholder'   =>  esc_html__('Enter the subdomain for your AgileCRM app', 'automate_hub' ),
        'label'         =>  esc_html__('AgileCRM Subdomain', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'data-type'=>'url',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $agilecrm_form->awp_wp_text_input(
    array(
        'id'            => "awp_agilecrm_api_key",
        'name'          => "awp_agilecrm_api_key",
        'value'         => $api_key,
        'placeholder'   => esc_html__( 'Enter your AgileCRM API Key', 'automate_hub' ),
        'label'         =>  esc_html__( 'REST API Key', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);
$form_fields .= $agilecrm_form->awp_wp_text_input(
    array(
        'id'            => "awp_agilecrm_email",
        'name'          => "awp_agilecrm_email",
        'value'         => $email,
        'placeholder'   => esc_html__( 'Enter the user login email', 'automate_hub' ),
        'label'         =>  esc_html__( 'User Email', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $agilecrm_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_save_agilecrm_api_key',
    )
);


$form_fields .= $agilecrm_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $agilecrm_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$agilecrm_form->render($form_fields);

?>

    </div>   
    <div class="wrap">
            <form id="form-list" method="post">
              <input type="hidden" name="page" value="automate_hub"/>
                <?php
                $data=[
                    'table-cols'=>['account_name'=>'Display name','url'=>'Agile CRM Url','email'=>'Email Address','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
                $platform_obj= new AWP_Platform_Shell_Table('agilecrm');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();
                ?>
            </form>
    </div>
    <?php
}

add_action( 'admin_post_awp_save_agilecrm_api_key', 'awp_save_agilecrm_api_key', 10, 0 );

function awp_save_agilecrm_api_key() {

    if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }

    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'awp_agilecrm_settings' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }
    $api_key   = sanitize_text_field( $_POST["awp_agilecrm_api_key"] );
    $email     = sanitize_text_field( $_POST["awp_agilecrm_email"] );
    $subdomain = sanitize_text_field( $_POST["awp_agilecrm_subdomain"] );
    $display_name     = sanitize_text_field( $_POST["awp_agilecrm_display_name"] );
    
    $platform_obj= new AWP_Platform_Shell_Table('agilecrm');
    $platform_obj->save_platform(['account_name'=>$display_name,'api_key'=>$api_key,'url'=>$subdomain,'email'=>$email]);
    AWP_redirect ("admin.php?page=automate_hub&tab=agilecrm" );
}

add_action( 'awp_add_js_fields', 'awp_agilecrm_js_fields', 10, 1 );

function awp_agilecrm_js_fields( $field_data ) {}

add_action( 'awp_action_fields', 'awp_agilecrm_action_fields' );

function awp_agilecrm_action_fields() {
    ?>

    <script type="text/template" id="agilecrm-action-template">
        <?php

        $app_data=array(
            'app_slug'=>'agilecrm',
           'app_name'=>'Agile CRM',
           'app_icon_url'=>AWP_ASSETS.'/images/icons/agilecrm.png',
           'app_icon_alter_text'=>'Agile Crm',
           'account_select_onchange'=>'getAgileCRMPipelines',
        ); 

        require (AWP_VIEWS.'/awp_app_integration_format.php');
        ?>
    </script>

    <?php
}

/* Saves connection mapping */
function awp_agilecrm_save_integration() {
    $params = array();
    parse_str( awp_sanitize_text_or_array_field( $_POST['formData'] ), $params );
    $trigger_data      = isset( $_POST["triggerData"]) ? awp_sanitize_text_or_array_field( $_POST["triggerData"]) : array();
    $action_data       = isset( $_POST["actionData" ]) ? awp_sanitize_text_or_array_field( $_POST["actionData" ]) : array();
    $field_data        = isset( $_POST["fieldData"  ]) ? awp_sanitize_text_or_array_field( $_POST["fieldData"  ]) : array();

    $integration_title = isset( $trigger_data["integrationTitle"]) ? $trigger_data["integrationTitle"] : "";
    $form_provider_id  = isset( $trigger_data["formProviderId"  ]) ? $trigger_data["formProviderId"  ] : "";
    $form_id           = isset( $trigger_data["formId"          ]) ? $trigger_data["formId"          ] : "";
    $form_name         = isset( $trigger_data["formName"        ]) ? $trigger_data["formName"        ] : "";
    $action_provider   = isset( $action_data ["actionProviderId"]) ? $action_data ["actionProviderId"] : "";
    $task              = isset( $action_data ["task"            ]) ? $action_data ["task"            ] : "";
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

add_action( 'wp_ajax_awp_get_agilecrm_pipelines', 'awp_get_agilecrm_pipelines', 10, 0 );

function awp_get_agilecrm_pipelines() {

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
    $platform_obj= new AWP_Platform_Shell_Table('agilecrm');
    $data=$platform_obj->awp_get_platform_detail_by_id($id);
    if(!$data){
        die( esc_html__( 'No Data Found', 'automate_hub' ) );
    }
    $api_key =$data->api_key;
    $subdomain     = $data->url;
    $subdomain     = $data->email;



    if( !$api_key || !$subdomain || !$user_email ) {
        exit;
    }

    $users     = "";
    $pipelines = "";
    $sources   = "";

    $args = array(
        "headers" => array(
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
            'Authorization' => 'Basic ' . base64_encode( $user_email . ':' . $api_key )
        )
    );

    $user_url = "https://{$subdomain}.agilecrm.com/dev/api/users";
    $user_response = wp_remote_get( $user_url, $args );
    
    awp_add_to_log( $user_response, $user_url, $args, array( "id" => "999" ) );

    if( !is_wp_error( $user_response ) ) {
        $user_body = json_decode( wp_remote_retrieve_body( $user_response ) );

        foreach( $user_body as $single ) {
            $users .= $single->name . ': ' . $single->id . ' ';
        }
    }

    $url = "https://{$subdomain}.agilecrm.com/dev/api/milestone/pipelines";

    $response = wp_remote_get( $url, $args );

    $args['headers']['Authorization']='Basic XXXXXXXXXXXX';
    awp_add_to_log( $response, $url, $args, array( "id" => "999" ) );

    if( !is_wp_error( $response ) ) {
        $body = json_decode( wp_remote_retrieve_body( $response ) );

        foreach( $body as $single ) {
            $pipelines .= $single->name . ': ' . $single->id . ' ';
        }

        $deal_fields = array(
            array( 'key' => 'dealName', 'value' => 'Name [Deal]', 'description' => 'Required for Deal creation, otherwise leave blank' ),
            array( 'key' => 'dealValue', 'value' => 'Value [Deal]', 'description' => 'Required for Deal creation, otherwise leave blank' ),
            array( 'key' => 'dealProbability', 'value' => 'Probability [Deal]', 'description' => 'Integer value' ),
            array( 'key' => 'dealCloseDate', 'value' => 'Close Date [Deal]', 'description' => 'Use YYYY-MM-DD format' ),
            array( 'key' => 'dealSource', 'value' => 'Source ID [Deal]', 'description' => '' ),
            array( 'key' => 'dealDescription', 'value' => 'Description [Deal]', 'description' => '' ),
            array( 'key' => 'dealTrack', 'value' => 'Track/Pipeline ID [Deal]', 'description' => $pipelines ),
            array( 'key' => 'dealMilestone', 'value' => 'Milestone [Deal]', 'description' => 'Example: New, Prospect, Proposal, Won, Lost' ),
            array( 'key' => 'dealOwner', 'value' => 'Owner ID [Deal]', 'description' => $users ),
            array( 'key' => 'noteSubject', 'value' => 'Subject [Note]', 'description' => '' ),
            array( 'key' => 'noteDescription', 'value' => 'Description [Note]', 'description' => '' ),

        );

        wp_send_json_success( $deal_fields );

    }


}

/*
 * Handles sending data to Agile CRM API
 */
function awp_agilecrm_send_data( $record, $posted_data ) {


    $temp    = json_decode( $record["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('agilecrm');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_key=$temp->api_key;
    $subdomain=$temp->url;
    $user_email=$temp->email;

   

    if( !$api_key || !$subdomain || !$user_email ) {
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
  
    $data       = $record_data["field_data"];
    $task       = $record["task"];

        $record_data = json_decode( $record["data"], true );
    if( array_key_exists( "cl", $record_data["action_data"]) ) {
        if( $record_data["action_data"]["cl"]["active"] == "yes" ) {
            $chk = awp_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data );
            if( !awp_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data ) ) {
                return;
            }
        }
    }

    $email      = empty( $data["email"] ) ? "" : awp_get_parsed_values( $data["email"], $posted_data );
    $first_name = empty( $data["firstName"] ) ? "" : awp_get_parsed_values( $data["firstName"], $posted_data );
    $last_name  = empty( $data["lastName"] ) ? "" : awp_get_parsed_values( $data["lastName"], $posted_data );
    $title      = empty( $data["title"] ) ? "" : awp_get_parsed_values( $data["title"], $posted_data );
    $company    = empty( $data["company"] ) ? "" : awp_get_parsed_values( $data["company"], $posted_data );
    $phone      = empty( $data["phone"] ) ? "" : awp_get_parsed_values( $data["phone"], $posted_data );
    $address    = empty( $data["address"] ) ? "" : awp_get_parsed_values( $data["address"], $posted_data );
    $city       = empty( $data["city"] ) ? "" : awp_get_parsed_values( $data["city"], $posted_data );
    $state      = empty( $data["state"] ) ? "" : awp_get_parsed_values( $data["state"], $posted_data );
    $zip        = empty( $data["zip"] ) ? "" : awp_get_parsed_values( $data["zip"], $posted_data );
    $country    = empty( $data["country"] ) ? "" : awp_get_parsed_values( $data["country"], $posted_data );
    $deal_name  = empty( $data["dealName"] ) ? "" : awp_get_parsed_values( $data["dealName"], $posted_data );
    $note_sub   = empty( $data["noteSubject"] ) ? "" : awp_get_parsed_values( $data["noteSubject"], $posted_data );



    if( $task == "add_contact" ) {


        $headers = array(
            'Content-Type'  => 'application/json',
            'Authorization' => 'Basic ' . base64_encode( $user_email . ':' . $api_key )
        );

        $url = "https://{$subdomain}.agilecrm.com/dev/api/contacts";

        $body = array(
            "properties" => array(
                array(
                    "type"  => "SYSTEM",
                    "name"  => "first_name",
                    "value" => $first_name
                ),
                array(
                    "type"  => "SYSTEM",
                    "name"  => "last_name",
                    "value" => $last_name
                ),
                array(
                    "type"  => "SYSTEM",
                    "name"  => "email",
                    "value" => $email
                ),
                array(
                    "type"  => "SYSTEM",
                    "name"  => "title",
                    "value" => $title
                ),
                array(
                    "type"  => "SYSTEM",
                    "name"  => "company",
                    "value" => $company
                ),
                array(
                    "type"  => "SYSTEM",
                    "name"  => "phone",
                    "value" => $phone
                ),
                array(
                    "name"  => "address",
                    "value" => json_encode( array(
                        "address"     => $address,
                        "city"        => $city,
                        "state"       => $state,
                        "zip"         => $zip,
                        "countryname" => $country
                    ))
                )
            )
        );

        $args = array(
            "headers" => $headers,
            "body"    => json_encode( $body )
        );

        $response = wp_remote_post( $url, $args );

        //decoding json objects in the log before saving it for the edit log problem
        $args['body']=json_decode($args['body'],true);
        $args['headers']['Authorization']="XXXXXXXXXXXX";
        $args['body']['properties'][6]['value']=json_decode($args['body']['properties'][6]['value'],true);
     
        awp_add_to_log( $response, $url, $args, $record );
        

        
        if( !is_wp_error( $response ) ) {
            $body = json_decode( wp_remote_retrieve_body( $response ) );

            if( !isset( $body->id ) ) {
                return;
            }
        }

        $contact_id =!empty($body->id) ? $body->id : false;

        if( $contact_id && $deal_name ) {
            $deal_name        = empty( $data["dealName"] ) ? "" : awp_get_parsed_values( $data["dealName"], $posted_data );
            $deal_value       = empty( $data["dealValue"] ) ? "" : awp_get_parsed_values( $data["dealValue"], $posted_data );
            $deal_probability = empty( $data["dealProbability"] ) ? "" : awp_get_parsed_values( $data["dealProbability"], $posted_data );
            $deal_close_date  = empty( $data["dealCloseDate"] ) ? "" : strtotime( awp_get_parsed_values( $data["dealCloseDate"], $posted_data ) );
            $deal_source      = empty( $data["dealSource"] ) ? "" : awp_get_parsed_values( $data["dealSource"], $posted_data );
            $deal_description = empty( $data["dealDescription"] ) ? "" : awp_get_parsed_values( $data["dealDescription"], $posted_data );
            $deal_track       = empty( $data["dealTrack"] ) ? "" : awp_get_parsed_values( $data["dealTrack"], $posted_data );
            $deal_milestone   = empty( $data["dealMilestone"] ) ? "" : awp_get_parsed_values( $data["dealMilestone"], $posted_data );
            $deal_owner       = empty( $data["dealOwner"] ) ? "" : awp_get_parsed_values( $data["dealOwner"], $posted_data );

            $deal_url = "https://{$subdomain}.agilecrm.com/dev/api/opportunity";

            $deal_args = array(
                "headers" => $headers,
                "body"    => json_encode( array(
                    "name"           => $deal_name,
                    "contact_ids"    => array( $contact_id ),
                    "expected_value" => $deal_value,
                    "owner_id"       => $deal_owner,
                    "pipeline_id"    => $deal_track,
                    "milestone"      => $deal_milestone,
                    "description"    => $deal_description,
                    "probability"    => intval( $deal_probability ),
                    "close_date"     => $deal_close_date,
                    "deal_source_id" => $deal_source,

                ) )
            );

            $deal_response = wp_remote_post( $deal_url, $deal_args );
            $deal_args['headers']['Authorization']="XXXXXXXXXXXX";
            $deal_args['body']=json_decode($deal_args['body'],true);
            awp_add_to_log( $deal_response, $deal_url, $deal_args, $record );
        }

        if( $contact_id && $note_sub ) {
            $note_desc = empty( $data["noteDescription"] ) ? "" : awp_get_parsed_values( $data["noteDescription"], $posted_data );

            $note_url = "https://{$subdomain}.agilecrm.com/dev/api/notes/";

            $note_args = array(
                "headers" => $headers,
                "body"    => json_encode( array(
                    "subject"     => $note_sub,
                    "description" => $note_desc,
                    "contact_ids" => array( $contact_id ),
                ) )
            );

            $note_response = wp_remote_post( $note_url, $note_args );
            $note_args['headers']['Authorization']="XXXXXXXXXXXX";
            $note_args['body']=json_decode($note_args['body'],true);

            awp_add_to_log( $note_response, $note_url, $note_args, $record );
        }

    }


     
    return $note_response;
}

function awp_agilecrm_resend_data($log_id,$data,$integration){


    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('activecampaign');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_key=$temp->api_key;
    $user_email=$temp->email;
    $subdomain=$temp->url;

   

    if( !$api_key || !$subdomain || !$user_email ) {
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

    if( $task == "add_contact" ) {
        $headers = array(
                'Content-Type'  => 'application/json',
                'Authorization' => 'Basic ' . base64_encode( $user_email . ':' . $api_key )
            );
        if(strpos($data['url'], 'contacts')){
            

            $url = "https://{$subdomain}.agilecrm.com/dev/api/contacts";

            $data['args']['body']['properties'][6]['value']=json_encode($data['args']['body']['properties'][6]['value']);
            

            $args = array(
                "headers" => $headers,
                "body"    => json_encode( $data['args']['body'] )
            );

            $resp = wp_remote_post( $url, $args );


            //decoding json objects in the log before saving it for the edit log problem
            $args['body']=json_decode($args['body'],true);
            $args['headers']['Authorization']="XXXXXXXXXXXX";
            $args['body']['properties'][6]['value']=json_decode($args['body']['properties'][6]['value'],true);
            awp_add_to_log( $resp, $url, $args, $integration );
            
            if( !is_wp_error( $resp ) ) {
                $body = json_decode( wp_remote_retrieve_body( $resp ) );

                if( !isset( $body->id ) ) {
                    return;
                }
            }
        }
        else if(strpos($data['url'], 'opportunity')){
            $deal_url = $data['url'];

            $deal_args = array(
                "headers" => $headers,
                "body"    => json_encode( $data['args']['body'] )
            );

            $deal_response = wp_remote_post( $deal_url, $deal_args );
            $deal_args['headers']['Authorization']="XXXXXXXXXXXX";
            $deal_args['body']=json_decode($deal_args['body'],true);
            awp_add_to_log( $deal_response, $deal_url, $deal_args, $integration );
        }
        else if(strpos($data['url'], 'notes')){
            $note_url = $data['url'];

            $note_args = array(
                "headers" => $headers,
                "body"    => json_encode( $data['args']['body'] )
            );

            $note_response = wp_remote_post( $note_url, $note_args );
            $note_args['headers']['Authorization']="XXXXXXXXXXXX";
            $note_args['body']=json_decode($note_args['body'],true);
            awp_add_to_log( $note_response, $note_url, $note_args, $integration );
        }
        


        

    

    }

    $response['success']=true;
    return $response;
}
