<?php

class AWP_Helpwise extends appfactory
{

   
    public function init_actions(){
       
        add_action('admin_post_awp_helpwise_save_api_token', [$this, 'awp_save_helpwise_api_token'], 10, 0);
        
        add_action( 'wp_ajax_awp_fetch_mailboxes_list', [$this, 'awp_fetch_mailboxes_list']);
        
    }

    public function init_filters(){

        add_filter('awp_platforms_connections', [$this, 'awp_helpwise_platform_connection'], 10, 1);
    }

    public function action_provider( $providers ) {
        $providers['helpwise'] = array(
            'title' => esc_html__( 'Helpwise', 'automate_hub' ),
            'tasks' => array(
                'createcontact'   => esc_html__( 'Create Contact', 'automate_hub' ),
                'sendemail'   => esc_html__( 'Send Email', 'automate_hub' ),
                'createmailbox'   => esc_html__( 'Create Mailbox', 'automate_hub' ),
            )
        );
    
        return  $providers;
    }

    public function settings_tab( $tabs ) {
        $tabs['helpwise'] = array('name'=>esc_html__( 'Helpwise', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'helpwise') {
            return;
        }
        $nonce = wp_create_nonce("awp_helpwise_settings");
        $api_token = isset($_GET['api_key']) ? sanitize_text_field($_GET['api_key']) : "";
        $id = isset($_GET['id']) ? intval( sanitize_text_field($_GET['id'] )) : "";
        $display_name     = isset($_GET['account_name']) ? sanitize_text_field($_GET['account_name']) : "";
        ?>
        <div class="platformheader">

            <a href="https://sperse.io/go/helpwise" target="_blank"><img src="<?php echo esc_url(AWP_ASSETS.'/images/logos/helpwise.png'); ?>" height="50" alt="Helpwise Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager(sanitize_text_field($_GET['tab']));
                    $instruction_obj->prepare_instructions();
                        
                ?>

                <br />

                <?php 

                $form_fields = '';
                $app_name= 'helpwise';
                $helpwise_form = new AWP_Form_Fields($app_name);

                $form_fields = $helpwise_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_helpwise_display_name",
                        'name'          => "awp_helpwise_display_name",
                        'value'         => $display_name,
                        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
                        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $helpwise_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_helpwise_api_token",
                        'name'          => "awp_helpwise_api_token",
                        'value'         => $api_token,
                        'placeholder'   => esc_html__( 'Enter your Helpwise API Key', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Helpwise API Key', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $helpwise_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_helpwise_secret_key",
                        'name'          => "awp_helpwise_secret_key",
                        'value'         => $api_token,
                        'placeholder'   => esc_html__( 'Enter your Helpwise Secreet Key', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Helpwise Secreet Key', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

               
                $form_fields .= $helpwise_form->awp_wp_hidden_input(
                    array(
                        'name'          => "action",
                        'value'         => 'awp_helpwise_save_api_token',
                    )
                );


                $form_fields .= $helpwise_form->awp_wp_hidden_input(
                    array(
                        'name'          => "_nonce",
                        'value'         =>$nonce,
                    )
                );
                $form_fields .= $helpwise_form->awp_wp_hidden_input(
                    array(
                        'name'          => "id",
                        'value'         =>wp_unslash($id),
                    )
                );


                $helpwise_form->render($form_fields);

                ?>


        </div>

        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                 $data=[
                    'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('helpwise');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();

                ?>
                        </form>
                </div>
        <?php
    }

    public function awp_save_helpwise_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_helpwise_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_token = sanitize_text_field($_POST["awp_helpwise_api_token"]);
        $display_name     = sanitize_text_field( $_POST["awp_helpwise_display_name"] );
        $secret_key     = sanitize_text_field( $_POST["awp_helpwise_secret_key"] );
        

        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('helpwise');
        $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_token, 'client_secret' => $secret_key ]);

        AWP_redirect("admin.php?page=automate_hub&tab=helpwise");
    } 
    
    public function load_custom_script() {
        wp_enqueue_script( 'awp-helpwise-script', AWP_URL . '/apps/h/helpwise/helpwise.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="helpwise-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'helpwise',
                           'app_name'=>'Helpwise ',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/helpwise.png',
                           'app_icon_alter_text'=>'Helpwise  Icon',
                           'account_select_onchange'=>'getmailid',
                           'tasks'=>array(
                                'sendemail'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Mailbox',
                                            'type'=>'select',
                                            'name'=>"mailbox_id",
                                            'required' => 'required',
                                            'onchange' => 'selectedMailbox',
                                            'option_for_loop'=>'(item) in data.mailboxesList',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.displayName}}',
                                            'select_default'=>'Select Mailbox...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': mailboxloding}",
                                                        )
                                        )
                                    ),
                                )
                            ),
                    ); 

                        require (AWP_VIEWS.'/awp_app_integration_format.php');
                ?>
            </script>
        <?php
    }

    
    public function awp_fetch_mailboxes_list() {
        if ( ! current_user_can('administrator') ){
            die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
        }
    
        if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
            die( esc_html__( 'Security check Failed', 'automate_hub' ) );
        }

        if (!isset( $_POST['platformid'] ) ) {
            die( esc_html__( 'Invalid Request', 'automate_hub' ) );
        }

        $id=sanitize_text_field($_POST['platformid']);
        $platform_obj= new AWP_Platform_Shell_Table('helpwise');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;
        $api_secret= $data->client_secret;

        $url = "https://app.helpwise.io/dev-apis/mailboxes/list";
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $api_key.":".$api_secret
            )
        );

        $response = wp_remote_post( $url, $args );
       
        $body = wp_remote_retrieve_body( $response );
        $body = json_decode( $body, true );
        wp_send_json_success($body['data']);
    }

};


$Awp_Helpwise = new AWP_Helpwise();

function awp_helpwise_save_integration() {
    Appfactory::save_integration();
}

function awp_helpwise_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('helpwise');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    
    $api_key= $temp->api_key;
    $api_secret= $temp->client_secret;

    $task = $decoded_data["task"]; 

    $token = $api_key.":".$api_secret;

    if( $task == "createcontact" ) {

        $firstname = empty( $data["firstname"] ) ? "" : awp_get_parsed_values($data["firstname"], $posted_data);
        $lastname = empty( $data["lastname"] ) ? "" : awp_get_parsed_values($data["lastname"], $posted_data);
        $companyName = empty( $data["companyName"] ) ? "" : awp_get_parsed_values($data["companyName"], $posted_data);
        $jobTitle = empty( $data["jobTitle"] ) ? "" : awp_get_parsed_values($data["jobTitle"], $posted_data);
        $email = empty( $data["email_id"] ) ? "" : awp_get_parsed_values($data["email_id"], $posted_data);
        $phone = empty( $data["phone"] ) ? "" : awp_get_parsed_values($data["phone"], $posted_data);
       

        $body = json_encode([
            "firstname" => $firstname,
	        "lastname" => $lastname,
	        "companyName" => $companyName,
	        "jobTitle" => $jobTitle,
	        "emails" => [
                $email    
            ],
	        "phones" => [
		        ["phone" => $phone, "type" => 1 ]
		      
		    ]
        ]);

        $url = "https://app.helpwise.io/dev-apis/contacts/create";

        $args = [
            'headers' => array(
                "Accept" =>"application/json", 
                'Authorization' => $token,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } else if($task == "sendemail"){

        $mailbox_id  = empty( $data["mailbox_id"] ) ? "" : awp_get_parsed_values($data["mailbox_id"], $posted_data);
        $to = empty( $data["eto"] ) ? "" : awp_get_parsed_values($data["eto"], $posted_data);
        $subject = empty( $data["subject"] ) ? "" : awp_get_parsed_values($data["subject"], $posted_data);
        $emailbody = empty( $data["emailbody"] ) ? "" : awp_get_parsed_values($data["emailbody"], $posted_data);
        
 
        $body = json_encode([   
            "mailbox_id"=> (integer) $mailbox_id,
            "to"=>$to,
            "subject" => $subject,
            "body"=>$emailbody,
        ]);

        $url = "https://app.helpwise.io/dev-apis/emails/send_mail";

        $args = [
            'headers' => array(
                "Accept" =>"application/json", 
                "Content-Type" => "application/json",
                'Authorization' => $token,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),

            'body'=> $body
        ];        

        $response  = wp_remote_post($url,  $args );
        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } else if($task == "createmailbox"){

        $email  = empty( $data["email"] ) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $displayName = empty( $data["displayName"] ) ? "" : awp_get_parsed_values($data["displayName"], $posted_data);
 
        $body = json_encode([   
            "email"=> $email,
            "displayName"=>$displayName,
        ]);

        $url = "https://app.helpwise.io/dev-apis/mailboxes/create";

        $args = [
            'headers' => array(
                "Accept" =>"application/json", 
                "Content-Type" => "application/json",
                'Authorization' => $token,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),

            'body'=> $body
        ];        

        $response  = wp_remote_post($url,  $args );
        
        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    }
    return $response;
}


function awp_helpwise_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('helpwise');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_key=$temp->api_key;
    if( !$api_key ) {
        return;
    }
    $data=stripslashes($data);
    $data=preg_replace('/\s+/', '',$data); 
    $data=str_replace('"{', '{', $data);
    $data=str_replace('}"', '}', $data);        
    $data=json_decode($data,true);
    $body=$data['args']['body'];
    $url=$data['url'];
    if(!$url){
        $response['success']=false;
        $response['msg']="Syntax Error! Request is invalid";
        return $response;
    }

   
        $campfireUrl = $url;
        $token = 'Bearer '.$api_key;
        

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $token,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),

            'body'=> json_encode($body)
        ];


        $return  = wp_remote_post($campfireUrl,  $args );

        $args['headers']['Authorization']="Bearer XXXXXXXXXXXX";
        awp_add_to_log( $return, $campfireUrl, $args, $integration );

    $response['success']=true;    
    return $response;
}
