<?php

class AWP_Esputnik extends appfactory
{
   
    public function init_actions(){
        add_action('admin_post_awp_esputnik_save_api_token', [$this, 'awp_save_esputnik_api_token'], 10, 0);
        add_action( 'wp_ajax_awp_fetch_group', [$this, 'awp_fetch_group']);
    }

    public function init_filters(){
        add_filter('awp_platforms_connections', [$this, 'awp_esputnik_platform_connection'], 10, 1);
    }

    public function action_provider( $providers ) {
        $providers['esputnik'] = array(
            'title' => esc_html__( 'Esputnik', 'automate_hub' ),
            'tasks' => array(
                'createcontact'   => esc_html__( 'Create Contact', 'automate_hub' ),
                'sendemail'   => esc_html__( 'Send Email', 'automate_hub' ),
                'sendsms'   => esc_html__( 'Send SMS', 'automate_hub' ),
            )
        );
        return  $providers;
    }

    public function settings_tab( $tabs ) {
        $tabs['esputnik'] = array('name'=>esc_html__( 'Esputnik', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'esputnik') {
            return;
        }
        $nonce = wp_create_nonce("awp_esputnik_settings");
        $api_token = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/esputnik" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/esputnik.png"  height="50" alt="Esputnik Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
            ?>
            <br />
            <?php 

$form_fields = '';
$app_name= 'esputnik';
$esputnik_form = new AWP_Form_Fields($app_name);

$form_fields = $esputnik_form->awp_wp_text_input(
    array(
        'id'            => "awp_esputnik_display_name",
        'name'          => "awp_esputnik_display_name",
        'value'         => $display_name,
        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $esputnik_form->awp_wp_text_input(
    array(
        'id'            => "awp_esputnik_api_token",
        'name'          => "awp_esputnik_api_token",
        'value'         => $api_token,
        'placeholder'   => esc_html__( 'Enter your Esputnik Live API Key', 'automate_hub' ),
        'label'         =>  esc_html__( 'Esputnik API Key', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $esputnik_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_save_emailoctopus_api_key',
    )
);


$form_fields .= $esputnik_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $esputnik_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$esputnik_form->render($form_fields);

?>
        </div>

        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                 $data=[
                    'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('esputnik');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();

                ?>
                        </form>
                </div>
        <?php
    }

    public function awp_save_esputnik_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_esputnik_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_token = sanitize_text_field($_POST["awp_esputnik_api_token"]);
        $display_name     = sanitize_text_field( $_POST["awp_esputnik_display_name"] );

        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('esputnik');
        $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_token]);

        AWP_redirect("admin.php?page=automate_hub&tab=esputnik");
    } 
    
    public function load_custom_script() {
        wp_enqueue_script( 'awp-esputnik-script', AWP_URL . '/apps/e/esputnik/esputnik.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="esputnik-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'esputnik',
                           'app_name'=>'Esputnik ',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/esputnik.png',
                           'app_icon_alter_text'=>'Esputnik  Icon',
                           'account_select_onchange'=>'getGroupList',
                           'tasks'=>array(
                                'createsubscribers'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Group',
                                            'type'=>'select',
                                            'name'=>"group_id",
                                            'required' => 'required',
                                            'onchange' => 'selectedgroup',
                                            'option_for_loop'=>'(item) in data.groupList',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Group...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': accountLoading}",
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

    
    public function awp_fetch_group() {
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
        $platform_obj= new AWP_Platform_Shell_Table('esputnik');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $user_name = $data->account_name;
        $api_key =$data->api_key;

        $url = "https://esputnik.com/api/v1/groups";

        $args = [
            "headers"=>[
                'Authorization'=> 'Basic '.base64_encode($user_name.":".$api_key),
                'Accept' => 'application/json', 
                'Content-Type' => 'application/json'
            ]
        ];
        $response = wp_remote_request( $url, $args );
        $body = json_decode($response['body'], true);    
        wp_send_json_success($body);
    }

   
};


$Awp_Esputnik = new AWP_Esputnik();

function awp_esputnik_save_integration() {
    Appfactory::save_integration();
}

function awp_esputnik_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 

    $platform_obj= new AWP_Platform_Shell_Table('esputnik');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $task = $decoded_data["task"]; 
    $user_name = $data->account_name;

    if( $task == "createcontact" ) {
        $firstName = empty( $data["firstName"] ) ? "" : awp_get_parsed_values($data["firstName"], $posted_data);
        $lastName = empty( $data["lastName"] ) ? "" : awp_get_parsed_values($data["lastName"], $posted_data);
        $sms = empty( $data["sms"] ) ? "" : awp_get_parsed_values($data["sms"], $posted_data);
        $email = empty( $data["email"] ) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $address = empty( $data["address"] ) ? "" : awp_get_parsed_values($data["address"], $posted_data);

        $body = json_encode([
          "contacts"=>array(
              "firstName"=>$firstName,
              "lastName"=>$lastName,
              "channels"=>array(
                "sms"=>$sms,
                "email"=>$email
              ),
            "address"=> array(
                  "address"=> $address
              )
            ),"dedupeOn"=>"email" 
        ]);

        $url = "https://esputnik.com/api/v1/contacts";

        $args = [
            "headers"=>[
                'Authorization'=> 'Basic '.base64_encode($user_name.":".$api_key),
                'Accept' => 'application/json', 
                'Content-Type' => 'application/json'
            ],
            'body'=> $body
        ];
        $response  = wp_remote_post($url,  $args );     
        $args['headers']['Authorization']="XXXXXXXXXXXX";
        awp_add_to_log( $response, $url, $args, $record );
    }  else if( $task == "sendemail" ) {
        $subject = empty( $data["subject"] ) ? "" : awp_get_parsed_values($data["subject"], $posted_data);
        $htmlText = empty( $data["htmlText"] ) ? "" : awp_get_parsed_values($data["htmlText"], $posted_data);
        $email = empty( $data["receiver"] ) ? "" : awp_get_parsed_values($data["receiver"], $posted_data);
        $result = awp_get_email_interface($api_key, $user_name);
        $decodedbody = json_decode(json_encode($result), true);
        $from = $decodedbody[0]['value'];
        $body = json_encode([
              "from"=>$from,
              "subject"=>$subject,
              "htmlText"=>"<html><body>".$htmlText."</body></html>",
              "emails"=>array($email),
        ]);

        $url = "https://esputnik.com/api/v1/message/email";

        $args = [
            "headers"=>[
                'Authorization'=> 'Basic '.base64_encode($user_name.":".$api_key),
                'Accept' => 'application/json', 
                'Content-Type' => 'application/json'
            ],
            'body'=> $body
        ];


        $response  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } else if( $task == "sendsms" ) {

        $text = empty( $data["text"] ) ? "" : awp_get_parsed_values($data["text"], $posted_data);
        $phoneNumbers = empty( $data["phoneNumbers"] ) ? "" : awp_get_parsed_values($data["phoneNumbers"], $posted_data);
        $result = awp_get_sms_interface($api_key, $user_name);
        $decodedbody = json_decode(json_encode($result), true);
        $from = $decodedbody[0]['value'];

        $body = json_encode([
              "from"=>$from,
              "text"=>$text,
              "phoneNumbers"=>array($phoneNumbers)
        ]);

        $url = "https://esputnik.com/api/v1/message/sms";

        $args = [
            "headers"=>[
                'Authorization'=> 'Basic '.base64_encode($user_name.":".$api_key),
                'Accept' => 'application/json', 
                'Content-Type' => 'application/json'
            ],
            'body'=> $body
        ];


        $response  = wp_remote_post($url,  $args );
    
        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } 
    return $response;
}

function awp_get_email_interface($api_key, $user_name){

    $url = "https://esputnik.com/api/v1/interfaces/email";

    $args = [
        "headers"=>[
            'Authorization'=> 'Basic '.base64_encode($user_name.":".$api_key),
            'Accept' => 'application/json', 
            'Content-Type' => 'application/json'
        ]
    ];

    $response  = wp_remote_get($url,  $args );
    $body = json_decode($response['body']);
    return $body;

}

function awp_get_sms_interface($api_key, $user_name){

    $url = "https://esputnik.com/api/v1/interfaces/sms";

    $args = [
        "headers"=>[
            'Authorization'=> 'Basic '.base64_encode($user_name.":".$api_key),
            'Accept' => 'application/json', 
            'Content-Type' => 'application/json'
        ]
    ];

    $response  = wp_remote_get($url,  $args );
    $body = json_decode($response['body']);
    return $body;

}


function awp_esputnik_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];

    $platform_obj= new AWP_Platform_Shell_Table('esputnik');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_key=$temp->api_key;
    $user_name = $temp->account_name;
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

        $token = 'Bearer '.$api_key;   

        $args = [
            'headers' => array(
                'Authorization'=> 'Basic '.base64_encode($user_name.":".$api_key),
                'Accept' => 'application/json', 
                'Content-Type' => 'application/json'
            ),

            'body'=> json_encode($body)
        ];


        $return  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="Bearer XXXXXXXXXXXX";
        awp_add_to_log( $return, $url, $args, $integration );

    $response['success']=true;    
    return $response;
}
