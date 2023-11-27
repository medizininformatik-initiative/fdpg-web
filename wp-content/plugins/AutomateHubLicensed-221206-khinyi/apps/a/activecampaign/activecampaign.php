<?php
    $platform_obj= new AWP_Platform_Shell_Table('activecampaign');
    add_filter( 'awp_settings_tabs','awp_activecampaign_settings_tab' , 10, 1 );
    add_action( 'awp_action_providers','awp_activecampaign_actions' , 10, 1 );
    add_action( 'awp_settings_view','awp_activecampaign_settings_view' , 10, 1 );
    add_action( 'admin_post_awp_save_activecampaign_api_key','awp_save_activecampaign_api_key' , 10, 1 );
    add_action( 'awp_add_js_fields','awp_activecampaign_js_fields' , 10, 1 );
    add_action( 'awp_action_fields','awp_activecampaign_action_fields' , 10, 1 );
    add_action( 'wp_ajax_awp_get_activecampaign_list','awp_get_activecampaign_list' , 10, 1 );
    add_action( 'wp_ajax_awp_get_activecampaignpro_custom_fields','awp_get_activecampaignpro_custom_fields' , 10, 1 );

    function awp_activecampaign_settings_tab( $providers ) {
        $providers['activecampaign'] = array('name'=>esc_html__( 'Active Campaign', 'automate_hub'), 'cat'=>array('crm'));
        return $providers;
    }

    function awp_activecampaign_actions( $actions ) {
        $actions['activecampaign'] = array(
            'title' => esc_html__( 'Active Campaign', 'automate_hub'), 
            'tasks' => array(
                'subscribe' => esc_html__( 'Add Contact To List', 'automate_hub'),
                'createmessage' => esc_html__( 'Create Message', 'automate_hub'),
                'createtag' => esc_html__( 'Create Tag', 'automate_hub'),
                'creategroup' => esc_html__( 'Create Group', 'automate_hub')
            )
        );
        return $actions;
    }

    function awp_activecampaign_settings_view( $current_tab ) {
        if( $current_tab != 'activecampaign' ) { return; }
        $nonce   = wp_create_nonce( "awp_activecampaign_settings" );
        $id = isset($_GET['id']) ? intval( sanitize_text_field($_GET['id'] )) : "";
        $api_key = isset($_GET['api_key']) ?  sanitize_text_field($_GET['api_key']) : "";
        $url     = isset($_GET['url']) ? sanitize_text_field($_GET['url']) : "";
        $display_name     = isset($_GET['account_name']) ? sanitize_text_field($_GET['account_name']) : "";
        ?>


        <div class="platformheader">


        <a href="https://sperse.io/go/activecampaign" target="_blank"><img src="<?php echo esc_url(AWP_ASSETS.'/images/logos/activecampaign.png'); ?>" width="255" height="50" alt="ActiveCampaign Logo"></a><br/><br/>
        <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager(sanitize_text_field($_GET['tab']));
                    $instruction_obj->prepare_instructions();
                    
        ?>
                <br/>

      <?php 

        
        $form_fields = '';
        $app_name= 'activecampaign';
        $activecampaign_form = new AWP_Form_Fields($app_name);

        $form_fields = $activecampaign_form->awp_wp_text_input(
            array(
                'id'            => "awp_activecampaign_display_name",
                'name'          => "awp_activecampaign_display_name",
                'value'         => $display_name,
                'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
                'label'         =>  esc_html__('Display Name', 'automate_hub' ),
                'wrapper_class' => 'form-row',
                'show_copy_icon'=>true
                
            )
        );
        $form_fields .= $activecampaign_form->awp_wp_text_input(
            array(
                'id'            => "awp_activecampaign_url",
                'name'          => "awp_activecampaign_url",
                'value'         => $url,
                'placeholder'   =>  esc_html__('Enter your ActiveCampaign URL', 'automate_hub' ),
                'label'         =>  esc_html__('ActiveCampaign URL', 'automate_hub' ),
                'wrapper_class' => 'form-row',
                'data-type'=>'url',
                'show_copy_icon'=>true
                
            )
        );

        $form_fields .= $activecampaign_form->awp_wp_text_input(
            array(
                'id'            => "awp_activecampaign_api_key",
                'name'          => "awp_activecampaign_api_key",
                'value'         => $api_key,
                'placeholder'   => esc_html__( 'Enter your ActiveCampaign Key', 'automate_hub' ),
                'label'         =>  esc_html__( 'ActiveCampaign Key', 'automate_hub' ),
                'wrapper_class' => 'form-row',
                'show_copy_icon'=>true
                
            )
        );

        $form_fields .= $activecampaign_form->awp_wp_hidden_input(
            array(
                'name'          => "action",
                'value'         => 'awp_save_activecampaign_api_key',
            )
        );


        $form_fields .= $activecampaign_form->awp_wp_hidden_input(
            array(
                'name'          => "_nonce",
                'value'         =>$nonce,
            )
        );
        $form_fields .= $activecampaign_form->awp_wp_hidden_input(
            array(
                'name'          => "id",
                'value'         =>wp_unslash($id),
            )
        );


        $activecampaign_form->render($form_fields);

        ?>


        </div>
        <div class="wrap">
                <form id="form-list" method="post">
                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                    $data=[
                        'table-cols'=>['account_name'=>'Display name','url'=>'Active Campaign Url','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                    ];
                    $platform_obj= new AWP_Platform_Shell_Table('activecampaign');
                    $platform_obj->initiate_table($data);
                    $platform_obj->prepare_items();
                    $platform_obj->display_table();
                    
                    ?>
                </form>
        </div>


        <?php
        
    }

    function awp_save_activecampaign_api_key() {

        if ( ! current_user_can('administrator') ){
            die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
        }
        // Security Check
        if (! wp_verify_nonce( $_POST['_nonce'], 'awp_activecampaign_settings' ) ) {
            die( esc_html__( 'Security check Failed', 'automate_hub' ) );
        }
        $api_key = sanitize_text_field( $_POST["awp_activecampaign_api_key"] );
        $url     = sanitize_text_field( $_POST["awp_activecampaign_url"] );
        $account_name     = sanitize_text_field( $_POST["awp_activecampaign_display_name"] );

        $platform_obj= new AWP_Platform_Shell_Table('activecampaign');
        $platform_obj->save_platform(['account_name'=>$account_name,'api_key'=>$api_key,'url'=>$url]);

        AWP_redirect ("admin.php?page=automate_hub&tab=activecampaign");
    }
    
    function awp_activecampaign_js_fields( $field_data ) { }

    function awp_activecampaign_action_fields() {
       ?>

       <script type="text/template" id="activecampaign-action-template">

            <?php

                        $app_data=array(
                            'app_slug'=>'activecampaign',
                        'app_name'=>'ActiveCampaign',
                        'app_icon_url'=>AWP_ASSETS.'/images/icons/activecampaign.png',
                        'app_icon_alter_text'=>'ActiveCampaign Icon',
                        'account_select_onchange'=>'getActiveCampaignList',
                        'tasks'=>array(
                                'subscribe'=>array(
                                    'task_assignments'=>array(

                                        array(
                                            'label'=>'List',
                                            'type'=>'select',
                                            'name'=>"list_id",
                                            'model'=>'fielddata.listId',
                                            'required' => 'required',
                                            'option_for_loop'=>'(item, index) in fielddata.list',
                                            'select_default'=>'Select List...',
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

    /* Get ActiveCampaign subscriber lists */
    function awp_get_activecampaign_list() {

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
        $platform_obj= new AWP_Platform_Shell_Table('activecampaign');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }
        $api_key =$data->api_key;
        $url     = $data->url;

        if( !$api_key || !$url ) {
            return array();
        }
        $request_data = array(
            "api_action" => "list_list",
            "api_key"    => $api_key,
            "api_output" => "json",
            "ids"        => "all"
        );
        $query = http_build_query( $request_data );
        $url   = "{$url}/admin/api.php?{$query}";

        $data  = wp_remote_get( $url );

        if( !is_wp_error( $data ) ) {
            $body  = json_decode( $data["body"] );
            unset( $body->result_code );
            unset( $body->result_message );
            unset( $body->result_output );
            $body=is_array($body)?$body:json_decode(json_encode($body), true);
            $lists = wp_list_pluck( $body, 'name', 'id' );

            wp_send_json_success( $lists );
        } else {
            wp_send_json_error();
        }
    }

    /* Get Mailchimp subscriber lists */
    function awp_get_activecampaignpro_custom_fields() {
        // Security Check
        if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
            die( esc_html__( 'Security check Failed', 'automate_hub' ) );
        }
        $api_key = get_option( "awp_activecampaign_api_key" );
        $url     = get_option( "awp_activecampaign_url" );
        if( !$api_key || !$url ) {
            return array();
        }
        $request_data = array(
            "api_action" => "list_field_view",
            "api_key"    => $api_key,
            "api_output" => "json",
            "ids"        => "all"
        );
        $query = http_build_query( $request_data );
        $url   = "{$url}/admin/api.php?{$query}";
        $data  = wp_remote_get( $url );
        if( !is_wp_error( $data ) ) {
            $body  = json_decode( $data["body"] );
            unset( $body->result_code );
            unset( $body->result_message );
            unset( $body->result_output );
            $custom_fields = array();
            foreach( $body as $single ) {
                array_push( $custom_fields, array( 'key' => $single->id, 'value' => $single->title ) );
            }
            wp_send_json_success( $custom_fields );
        } else {
            wp_send_json_error();
        }
    }

    /* Saves connection mapping */
    function awp_activecampaign_save_integration() {
        $params = array();
        parse_str( awp_sanitize_text_or_array_field( $_POST['formData'] ), $params );
        $trigger_data      = isset( $_POST["triggerData"] ) ? awp_sanitize_text_or_array_field( $_POST["triggerData"] ) : array();
        $action_data       = isset( $_POST["actionData"] ) ? awp_sanitize_text_or_array_field( $_POST["actionData"] ) : array();
        $field_data        = isset( $_POST["fieldData"] ) ? awp_sanitize_text_or_array_field( $_POST["fieldData"] ) : array();
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
                array('id' => $id)
            );
        }
        $return=array();
        $return['type']=$type;
        $return['result']=$result;
        $return['insertid']=$wpdb->insert_id;
        return $return;
        
    }

    /* Handles sending data to ActiveCampaign API */
    function awp_activecampaign_send_data( $record, $posted_data ) {

        $temp    = json_decode( $record["data"], true );
        $temp    = $temp["field_data"];
        $platform_obj= new AWP_Platform_Shell_Table('activecampaign');
        $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
        $api_key=$temp->api_key;
        $url=$temp->url;

        if(!$api_key ) { return; }
        
        $data    = json_decode( $record["data"], true );
        $data    = $data["field_data"];
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
            $first_name   = empty( $data["firstName"  ]) ? "" : awp_get_parsed_values($data["firstName"], $posted_data);
            $last_name    = empty( $data["lastName"   ]) ? "" : awp_get_parsed_values($data["lastName"], $posted_data);
            $phone_number = empty( $data["phoneNumber"]) ? "" : awp_get_parsed_values($data["phoneNumber"], $posted_data);
            $email   = empty( $data["email"] ) ? "" : awp_get_parsed_values($data["email"], $posted_data);

            $body = json_encode([
                "contact" => [
                    "email" => $email,
                    "firstName" => $first_name,
                    "lastName" => $last_name,
                    "phone" => $phone_number
                ]
            ]);

            $url = "{$url}/api/3/contacts";

            $args = [
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'Api-Token' => $api_key,
                    'User-Agent' => "Sperse (https://www.sperse.com/)"
                ),

                'body'=> $body
            ];

            $response  = wp_remote_post($url,  $args );

            $args['headers']['api_key']="XXXXXXXXXXXX";

            awp_add_to_log( $response, $url, $args, $record_data );

        }else if($task == "createmessage"){

            $fromname   = empty( $data["fromname"  ]) ? "" : awp_get_parsed_values($data["fromname"], $posted_data);
            $fromemail    = empty( $data["fromemail"   ]) ? "" : awp_get_parsed_values($data["fromemail"], $posted_data);
            $reply2 = empty( $data["reply2"]) ? "" : awp_get_parsed_values($data["reply2"], $posted_data);
            $subject = empty( $data["subject"]) ? "" : awp_get_parsed_values($data["subject"], $posted_data);
            $preheader_text = empty( $data["preheader_text"]) ? "" : awp_get_parsed_values($data["preheader_text"], $posted_data);
            $text = empty( $data["text"]) ? "" : awp_get_parsed_values($data["text"], $posted_data);

            $body = json_encode([
                "message" => [
                    "fromname" => $fromname,
                    "fromemail" => $fromemail,
                    "reply2" => $reply2,
                    "subject" => $subject,
                    "preheader_text" => $preheader_text,
                    "text" => $text,
                    "html" => "<div>".$text."</div>"
                ]
            ]);

            $url = "{$url}/api/3/messages";

            $args = [
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'Api-Token' => $api_key,
                    'User-Agent' => "Sperse (https://www.sperse.com/)"
                ),

                'body'=> $body
            ];

            $response  = wp_remote_post($url,  $args );

            $args['headers']['api_key']="XXXXXXXXXXXX";

            awp_add_to_log( $response, $url, $args, $record_data );

        } else if($task == "createtag"){

            $tag   = empty( $data["tagname"  ]) ? "" : awp_get_parsed_values($data["tagname"], $posted_data);
            $tagType    = empty( $data["tagType"   ]) ? "" : awp_get_parsed_values($data["tagType"], $posted_data);
            $description = empty( $data["description"]) ? "" : awp_get_parsed_values($data["description"], $posted_data);

            $body = json_encode(array(
                "tag" => [
                    "tag" => $tag,
                    "tagType" => $tagType,
                    "description" => $description
                ]
            ));

            $url = "{$url}/api/3/tags";

            $args = [
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'Api-Token' => $api_key,
                    'User-Agent' => "Sperse (https://www.sperse.com/)"
                ),

                'body'=> $body
            ];

            $response  = wp_remote_post($url,  $args );

            $args['headers']['api_key']="XXXXXXXXXXXX";

            awp_add_to_log( $response, $url, $args, $record_data );
        } else if($task == "creategroup"){

            $groupname   = empty( $data["groupname"  ]) ? "" : awp_get_parsed_values($data["groupname"], $posted_data);

            $body = json_encode(array(
                "group" => [
                    "title" => $groupname
                ]
            ));

            $url = "{$url}/api/3/groups";

            $args = [
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'Api-Token' => $api_key,
                    'User-Agent' => "Sperse (https://www.sperse.com/)"
                ),

                'body'=> $body
            ];

            $response  = wp_remote_post($url,  $args );

            $args['headers']['api_key']="XXXXXXXXXXXX";

            awp_add_to_log( $response, $url, $args, $record_data );
        } else if($task == "createuser"){

            $username   = empty( $data["username"  ]) ? "" : awp_get_parsed_values($data["username"], $posted_data);
            $fname   = empty( $data["fname"  ]) ? "" : awp_get_parsed_values($data["fname"], $posted_data);
            $lname   = empty( $data["lname"  ]) ? "" : awp_get_parsed_values($data["lname"], $posted_data);
            $uemail   = empty( $data["uemail"  ]) ? "" : awp_get_parsed_values($data["uemail"], $posted_data);
            $upassword   = empty( $data["upassword"  ]) ? "" : awp_get_parsed_values($data["upassword"], $posted_data);
            $group   = empty( $data["group"  ]) ? "" : awp_get_parsed_values($data["group"], $posted_data);

            $body = json_encode(array(
                "user" => [
                    "username" => $username,
                    "firstName" => $fname,
                    "lastName" => $lname,
                    "email" => $uemail,
                    "password" => $upassword,
                    "group" => $group
                ]
            ));

            $url = "{$url}/api/3/groups";

            $args = [
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'Api-Token' => $api_key,
                    'User-Agent' => "Sperse (https://www.sperse.com/)"
                ),

                'body'=> $body
            ];

            $response  = wp_remote_post($url,  $args );

            $args['headers']['api_key']="XXXXXXXXXXXX";

            awp_add_to_log( $response, $url, $args, $record_data );
        }
    }

    function awp_activecampaign_resend_data($log_id,$data,$integration){
        $temp    = json_decode( $integration["data"], true );
        $temp    = $temp["field_data"];
        $platform_obj= new AWP_Platform_Shell_Table('activecampaign');
        $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
        $api_key=$temp->api_key;
        $url=$temp->url;
        
        if(!$api_key ) { return; }
        //$data=stripslashes($data);
        //$data=preg_replace('/\s+/', '',$data); 

        $data=json_decode($data,true);
        $task=$integration['task'];
        parse_str($data['args']['body'],$params);
        $url=$data['url'];
        if(!$url){
            $response['success']=false;
            $response['msg']="Syntax Error! Request is invalid";
            return $response;
        }
        if( $task == "subscribe" ) {
            $params['api_key']=$api_key;
            $query=http_build_query($params);
            
            $args = array( 'headers' => array('Content-Type' => 'application/x-www-form-urlencoded'), 'body' => $query );
            $return = wp_remote_post( $url, $args );

            $params['api_key']='XXXXXXXX';
            $query = http_build_query( $params );
            $backup_args = array( 'headers' => array('Content-Type' => 'application/x-www-form-urlencoded'), 'body' => $query );
            awp_add_to_log( $return, $url, $backup_args, $integration );
        }

        $response['success']=true;
        return $response;
    }


    function awp_activecampaign_platform_connection($response){

        if(!empty(get_option('awp_activecampaign_api_key')) && !empty(get_option('awp_activecampaign_url'))){
            $temp= true;
        }
        else{
            $temp= false;
        }
        $response['activecampaign']=array(
            'isConnected' => $temp
        );
        return $response;
    }




