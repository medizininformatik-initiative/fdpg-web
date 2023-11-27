<?php
if(class_exists('AWP_Submission')){return;}
class AWP_Submission {

    public function __construct() {
        add_action( 'wp_ajax_awp_get_forms', array( $this,'get_forms' ) );
        add_action( 'wp_ajax_awp_get_form_fields', array( $this,'get_form_fields' ) );
        add_action( 'wp_ajax_awp_get_tasks', array( $this,'get_tasks' ) );
        add_action( 'wp_ajax_awp_get_platform_status', array( $this,'get_platform_status' ) );
        add_action( 'wp_ajax_awp_save_integration', array( $this,'save_integration' ) );
        add_action( 'wp_ajax_awp_save_message', array( $this,'save_message' ) );
    }

    public function get_forms() {
        if( !empty($_POST['nonce']) && !wp_verify_nonce( $_POST['nonce'], 'automate_hub' ) ) { return; }
        $form_provider = !empty($_POST['formProviderId']) ? sanitize_text_field( $_POST['formProviderId'] ) :'';
        if( $form_provider ) {
            $forms = call_user_func( "awp_{$form_provider}_get_forms", $form_provider );
            if( !is_wp_error( $forms ) ) { wp_send_json_success( $forms ); }
        }
        wp_die();
    }

    /* Get all fields for a specific form */
    public function get_form_fields() {
        if(!empty($_POST['nonce']) && !wp_verify_nonce( $_POST['nonce'], 'automate_hub' ) ) { return; }
        $form_provider = !empty($_POST['formProviderId']) ? sanitize_text_field( $_POST['formProviderId'] ) :'';
        $form_id       = !empty($_POST['formId']) ? sanitize_text_field( $_POST['formId'] ) :'';
        if( $form_provider && $form_id ) {
            $fields = call_user_func( "awp_{$form_provider}_get_form_fields", $form_provider, $form_id );
            if( !is_wp_error( $fields ) ) {
                $backup_fields = $fields;
                $trimmed_fields = array();
                if(count($fields)>0){
                    foreach ($backup_fields as $key =>$field) {
                        $dots = '';
                        if(strlen($field)>20){
                            $dots = '...';
                        }
                        $trimmed_string = substr($field, 0, 20).$dots;
                        $trimmed_fields[$key] = $trimmed_string;
                    }
                    $fields = $trimmed_fields;
                }
                $fields = apply_filters('awp_integration_fields',$fields,$form_provider, $form_id);
                wp_send_json_success( $fields );
            }
        }
        wp_die();
    }

    /* Get Tasks for a action provider */
    public function get_tasks() {
        if( !empty($_POST['nonce']) && !wp_verify_nonce( $_POST['nonce'], 'automate_hub' ) ) { return; }
        $action_provider = !empty($_POST['actionProviderId']) ? sanitize_text_field( $_POST['actionProviderId'] ) :'';
        if( $action_provider ) {
            $tasks = awp_get_action_tasks( $action_provider );
            if( !is_wp_error( $tasks ) ) {
                wp_send_json_success( $tasks );
            }
        }
        wp_die();
    }

    public function get_platform_status() {
        if( !empty($_POST['nonce']) && !wp_verify_nonce( $_POST['nonce'], 'automate_hub' ) ) { return; }
        $action_provider = !empty($_POST['actionProviderId']) ? sanitize_text_field( $_POST['actionProviderId'] ) :'';
        if( $action_provider ) {

            $connected = call_user_func( "awp_{$form_provider}_platform_connection", $action_provider );
            if( !is_wp_error( $connected ) ) {
                wp_send_json_success( $connected );
            }
        }

        
        wp_die();
    }
    
    function rss_feed_manager($integration_id){
        if(!isset($_POST['triggerData']['formProviderId']) || $_POST['triggerData']['formProviderId']!='rssfeed' ){
            return false;
        }
        $formData=array();
        $form=sanitize_url($_REQUEST['formData']);
        parse_str($form, $formData);
        $_REQUEST['saved_integration_id']=$integration_id;
        $rss_actions_list=get_option('awp_rss_actions_list');
        $rss_actions_list=empty($rss_actions_list)?[]:unserialize($rss_actions_list);
        
        $cron=wp_schedule_event( time(), $formData['rssfeedInterval'], 'bl_awp_rss_cron_hook_'.$integration_id,[json_encode($_REQUEST)]);
        
        array_push($rss_actions_list, [$integration_id,[json_encode($_REQUEST)]]);

        update_option('awp_rss_actions_list',serialize($rss_actions_list));
        
        return $cron;
    }

    /* Save Integration */
    public function save_integration() {       
        
        $result="";
        if(!empty($_POST["nonce"]) &&  !wp_verify_nonce( $_POST["nonce"], 'automate_hub' )) { return; }
        $action_provider_id  = isset( $_POST["actionData"]["actionProviderId"] ) ? sanitize_text_field( $_POST["actionData"]["actionProviderId"] ) : "";     
        
        $fieldData = !empty($_POST["fieldData"]) ? sanitize_text_field($_POST["fieldData"]) : array();
        if ( isset($fieldData) ){
            $fieldData =  urldecode($fieldData);
            $fieldData =  stripslashes($fieldData);
            $_POST["fieldData"] = json_decode($fieldData,true);   
        } 
        
        // for ultimate version start
        // if( $action_provider_id ) {
        //     $result=call_user_func( "awp_{$action_provider_id}_save_integration" );   
        //     $insertid = !empty($result['insertid']) ? sanitize_text_field($result['insertid']) :'';
        //     $cron=$this->rss_feed_manager($insertid);
        // }
        //for ultimate version end


        // for limited version start (disable action_provider_id condition of ultimate version first)
        if( $action_provider_id ) {
            $params = array();
            parse_str( awp_sanitize_text_or_array_field( $_POST['formData'] ), $params );
            $type              = isset( $params["type"] ) ? $params["type"] : "";
            if ( $type == 'update_integration' ) {
                $result=call_user_func( "awp_{$action_provider_id}_save_integration" );
                   $insertid = !empty($result['insertid']) ? sanitize_text_field($result['insertid']) :'';
                   $cron=$this->rss_feed_manager($insertid);
            }
            elseif($type == 'new_integration' ){

                $usage_error=awp_usage_controller("A",$action_provider_id,["W","FS"]);
                if(!empty($usage_error)){
                    $res=json_decode($usage_error,true);
                    if($res['success'] == false){
                        echo json_encode($res);
                        wp_die();
                    }
                    else if($res['success']== true){
                        $result=call_user_func( "awp_{$action_provider_id}_save_integration" );
                    }
                    
                }

            }
            
        }
        // for limited version end

        $response['success']=true;
        $response['redirectUrl']=admin_url( 'admin.php?page=my_integrations' );
        $insertid = !empty($result['insertid']) ? sanitize_text_field($result['insertid']) :'';

        //steps functionality        
        if( isset($_POST['parentID']) ){
            //this means this is a child integration
            $parent_id = isset($_POST['parentID'])? sanitize_text_field($_POST['parentID']):''  ;
            // now we will attach this step to the parent integration
            $this->attach_child_to_parent_integration($parent_id, $insertid);
        }
        else{
            //this means this is the parent and the next integration will be its child
            $parent_id=$insertid;
        }
        if(isset($_POST['willHaveSibling'])){
            $response['parent_id']=$parent_id;
            $response['form_provider_id'] = !empty($_POST['triggerData']['formProviderId']) ? sanitize_text_field($_POST['triggerData']['formProviderId']) : ''  ;
            $response['form_id'] = !empty($_POST['triggerData']['formId']) ? sanitize_text_field($_POST['triggerData']['formId']) :''  ;
            $response['action_provider_id'] = !empty($_POST['actionData']['actionProviderId']) ? sanitize_text_field( $_POST['actionData']['actionProviderId'] ):'' ;
            $response['task'] = !empty($_POST['actionData']['task']) ? sanitize_text_field($_POST['actionData']['task']) :'';
            $response['page']='automate_hub-new';
            $response['redirectUrl']=esc_url_raw(admin_url( 'admin.php?page=automate_hub-new&'.http_build_query($response)));
            
        }
        echo json_encode($response);
        die();
    }

    /* Add Child Integration */
    function attach_child_to_parent_integration($parent_id,$child_id){
        global $wpdb;
        $integration_table = $wpdb->prefix . 'awp_integration';
        $data['parent']['integration_id']=$parent_id;
        $data=json_encode($data);
        
        $result = $wpdb->update( $integration_table,
            array(
                'extra_data'           => $data,
            ),
            array('id' => $child_id)
        );
    }



    /* Save Integration */
    public function save_message() {
        if(!empty($_POST["nonce"]) &&  !wp_verify_nonce( $_POST["nonce"], 'automate_hub' ) ) { return; }
        $action_provider_id  = isset( $_POST["actionData"]["actionProviderId"] ) ? sanitize_text_field( $_POST["actionData"]["actionProviderId"] ) : "";
        if( $action_provider_id ) {
            $params = array();
             parse_str( awp_sanitize_text_or_array_field( $_POST['formData'] ), $params );
            $type              = isset( $params["type"] ) ? sanitize_text_field($params["type"]) : "";
            $trigger_data = isset( $_POST["triggerData"] ) ? awp_sanitize_text_or_array_field( $_POST["triggerData"] ) : array();
            $action_data  = isset( $_POST["actionData"] ) ? awp_sanitize_text_or_array_field( $_POST["actionData"] ) : array();
            $field_data   = isset( $_POST["fieldData"] ) ? awp_sanitize_text_or_array_field( $_POST["fieldData"] ) : array();
            $messageTitle      = isset( $trigger_data["messageTitle"] ) ? sanitize_text_field($trigger_data["messageTitle"]) : "";
            $messageTemplate   = isset( $trigger_data["messageTemplate"] ) ?sanitize_text_field( $trigger_data["messageTemplate"]) : "";
            $subjectName       = isset( $trigger_data["subjectName"] ) ? sanitize_text_field($trigger_data["subjectName"]) : "";
            $externalTemplateID = isset( $trigger_data["externalTemplateID"] ) ? sanitize_text_field($trigger_data["externalTemplateID"]) : "";
            $senderPhone       = isset( $trigger_data["senderPhone"] ) ? sanitize_text_field($trigger_data["senderPhone"] ): "";
            $SenderEmail       = isset( $trigger_data["SenderEmail"] ) ? sanitize_email($trigger_data["SenderEmail"]) : "";
            $action_provider   = isset( $action_data["actionProviderId"] ) ? sanitize_text_field($action_data["actionProviderId"] ): "";
            $task              = isset( $action_data["task"] ) ? sanitize_text_field( $action_data["task"]) : "";
            $form_provider_id  = isset( $trigger_data["formProviderId"] ) ? sanitize_text_field($trigger_data["formProviderId"]) : "";
            $form_id           = isset( $trigger_data["formId"] ) ? sanitize_text_field($trigger_data["formId"]) : "";
            $all_data = array(
                'trigger_data' => $trigger_data,
                'action_data'  => $action_data,
                'field_data'   => $field_data
            );

            global $wpdb;
            $message_table = $wpdb->prefix . 'awp_message_template';
            if ( $type == 'new_message' ) {
                $result = $wpdb->insert(
                    $message_table,
                    array(
                        'title'           => $messageTitle,
                        'subject_name'    => $subjectName,
                        'form_provider'   => $form_provider_id,
                        'form_id'       => $form_id,
                        'sender_phone'    => $senderPhone,
                        'sender_email'    => $SenderEmail,
                        'action_provider' => $action_provider,
                        'message_template' => $messageTemplate,
                        'external_template_id' =>$externalTemplateID,
                        'data'                 => json_encode( $all_data, true ),
                        'status'               => 1
                    )
                );
            }

            if ( $type == 'update_message' ) {
                $id = !empty($params['id']) ? trim( sanitize_text_field( $params['id'] ) ) :'';
                if ( $type != 'update_message' &&  !empty( $id ) ) { exit; }
                $result = $wpdb->update( $message_table,
                    array(
                        'title'           => $messageTitle,
                        'subject_name'    => $subjectName,
                        'form_provider'   => $form_provider_id,
                        'form_id'       => $form_id,
                        'sender_phone'    => $senderPhone,
                        'sender_email'    => $SenderEmail,
                        'action_provider' => $action_provider,
                        'message_template' => $messageTemplate,
                        'external_template_id' =>$externalTemplateID,
                        'data'                 => json_encode( $all_data, true ),
                        'status'               => 1
                    ),
                    array('id' => $id)
                );
            }
            if ( $result ) { wp_send_json_success($result); } else { wp_send_json_error(); }
        }
    }
}
