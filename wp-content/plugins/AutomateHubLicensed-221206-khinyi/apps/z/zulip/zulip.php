<?php

class AWP_Zulip extends appfactory
{

   
    public function init_actions(){
       
        add_action('admin_post_awp_zulip_save_api_token', [$this, 'awp_save_zulip_api_token'], 10, 0);
        
        add_action( 'wp_ajax_awp_fetch_stream', [$this, 'awp_fetch_stream']);
        
    }

    public function init_filters(){

        add_filter('awp_platforms_connections', [$this, 'awp_zulip_platform_connection'], 10, 1);
    }

    public function action_provider( $providers ) {
        $providers['zulip'] = array(
            'title' => esc_html__( 'Zulip', 'automate_hub' ),
            'tasks' => array(
                'createstream'   => esc_html__( 'Create Stream', 'automate_hub' ),
                'sendmessage'   => esc_html__( 'Send Message', 'automate_hub' ),
                'createuser'   => esc_html__( 'Create User', 'automate_hub' )
            )
        );
    
        return  $providers;
    }

    public function settings_tab( $tabs ) {
        $tabs['zulip'] = array('name'=>esc_html__( 'Zulip', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'zulip') {
            return;
        }
        $nonce = wp_create_nonce("awp_zulip_settings");
        $api_token = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";
        $email     = isset($_GET['email']) ? $_GET['email'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/zulip" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/zulip.png"  height="50" alt="Zulip Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?>

                <br />
                <?php 

$form_fields = '';
$app_name= 'zulip';
$zulip_form = new AWP_Form_Fields($app_name);

$form_fields = $zulip_form->awp_wp_text_input(
    array(
        'id'            => "awp_zulip_display_name",
        'name'          => "awp_zulip_display_name",
        'value'         => $display_name,
        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $zulip_form->awp_wp_text_input(
    array(
        'id'            => "awp_zulip_email",
        'name'          => "awp_zulip_email",
        'value'         => $email,
        'placeholder'   => esc_html__( 'Zulip Email', 'automate_hub' ),
        'label'         =>  esc_html__( 'Zulip Email', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);
$form_fields .= $zulip_form->awp_wp_text_input(
    array(
        'id'            => "awp_zulip_api_token",
        'name'          => "awp_zulip_api_token",
        'value'         => $api_token,
        'placeholder'   => esc_html__( 'Zulip API Key', 'automate_hub' ),
        'label'         =>  esc_html__( 'Zulip API Key', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $zulip_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_zulip_save_api_token',
    )
);


$form_fields .= $zulip_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $zulip_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$zulip_form->render($form_fields);

?>

        </div>

        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                 $data=[
                    'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('zulip');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();

                ?>
                        </form>
                </div>
        <?php
    }

    public function awp_save_zulip_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_zulip_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_token = sanitize_text_field($_POST["awp_zulip_api_token"]);
        $display_name     = sanitize_text_field( $_POST["awp_zulip_display_name"] );
        $email     = sanitize_text_field( $_POST["awp_zulip_email"] );

        if ($display_name != "") {
            $url_regexPattern = '/\A[a-zA-Z0-9][a-zA-Z0-9\-]*\.zulipchat\.com\z/';
            if (preg_match($url_regexPattern, $display_name) == 1) {
                // Save tokens
                $platform_obj = new AWP_Platform_Shell_Table('zulip');
                $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_token, 'email' => $email]);

                AWP_redirect("admin.php?page=automate_hub&tab=zulip");
            } else {
                wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=zulip'));
                exit();
            }
        } else {
            wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=zulip'));
            exit();
        }

        
    } 
    
    public function load_custom_script() {
        wp_enqueue_script( 'awp-zulip-script', AWP_URL . '/apps/z/zulip/zulip.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="zulip-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'zulip',
                           'app_name'=>'Zulip ',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/zulip.png',
                           'app_icon_alter_text'=>'Zulip  Icon',
                           'account_select_onchange'=>'getstream',
                           'tasks'=>array(
                                'sendmessage'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Stream',
                                            'type'=>'select',
                                            'name'=>"streamname",
                                            'required' => 'required',
                                            'onchange' => 'selectedstream',
                                            'option_for_loop'=>'(item) in data.streamlist',
                                            'option_for_value'=>'item.name',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Stream...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': streamLoading}",
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

    
    public function awp_fetch_stream() {
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
        $platform_obj= new AWP_Platform_Shell_Table('zulip');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key=$data->api_key;
        $email=$data->email;
        $domain = $data->account_name;

        $url = "https://".$domain."/api/v1/streams";
        $args = array(
            'headers' => array(
              'Content-Type' => 'application/x-www-form-urlencoded',
              'Authorization' => 'Basic '.base64_encode($email.":".$api_key)
            )
        );

        $response = wp_remote_get( $url, $args );
        $body = wp_remote_retrieve_body( $response );
        $body = json_decode( $body, true );
        wp_send_json_success($body["streams"]);
    }
   
};


$Awp_Zulip = new AWP_Zulip();

function awp_zulip_save_integration() {
    Appfactory::save_integration();
}

function awp_zulip_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('zulip');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $email=$temp->email;
    $domain = $temp->account_name;
    $task = $decoded_data["task"]; 

   

    if( $task == "createstream" ) {

        $name = empty( $data["name"] ) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $description = empty( $data["description"] ) ? "" : awp_get_parsed_values($data["description"], $posted_data);



        $url = "https://".$domain."/api/v1/users/me/subscriptions";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic '.base64_encode($email.":".$api_key)
            ),
            'body'=> 'subscriptions=[{"description": "'.$description.'", "name": "'.$name.'"}]'
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="Basic XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } else if( $task == "sendmessage" ) {

      $topic = empty( $data["topic"] ) ? "" : awp_get_parsed_values($data["topic"], $posted_data);
      $content = empty( $data["content"] ) ? "" : awp_get_parsed_values($data["content"], $posted_data);
      $streamname = empty( $data["streamname"] ) ? "" : awp_get_parsed_values($data["streamname"], $posted_data);

      $body = array(
          "topic"=>$topic,
          "content"=>$content,
          "to" => $streamname,
          "type"=>"stream"
      );

      $url = "https://".$domain."/api/v1/messages";

      $args = [
          'headers' => array(
              'Content-Type' => 'application/x-www-form-urlencoded',
              'Authorization' => 'Basic '.base64_encode($email.":".$api_key)
          ),

          'body'=> $body
      ];

      $response  = wp_remote_post($url,  $args );

      $args['headers']['Authorization']="Basic XXXXXXXXXXXX";

      awp_add_to_log( $response, $url, $args, $record );

    } else if( $task == "createuser" ) {

        
      $full_name = empty( $data["full_name"] ) ? "" : awp_get_parsed_values($data["full_name"], $posted_data);
      $useremail = empty( $data["email"] ) ? "" : awp_get_parsed_values($data["email"], $posted_data);
      $password = empty( $data["password"] ) ? "" : awp_get_parsed_values($data["password"], $posted_data);

      $body = [
              "full_name"=>$full_name,
              "email" => $useremail,
              "password"=>$password
      ];

      $url = "https://".$domain."/api/v1/users";

      $args = [
          'headers' => array(
              'Content-Type' => 'application/x-www-form-urlencoded',
              'Authorization' => 'Basic '.base64_encode($email.":".$api_key)
          ),

          'body'=> $body
      ];

      $response  = wp_remote_post($url,  $args );

      $args['headers']['Authorization']="Basic XXXXXXXXXXXX";

      awp_add_to_log( $response, $url, $args, $record );

    }
    return $response;
}


function awp_zulip_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('zulip');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_key=$temp->api_key;
    $email=$temp->email;
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

        $token = 'Basic '.base64_encode($email.":".$api_key);
        

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $token
            ),

            'body'=> json_encode($body)
        ];


        $return  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="Basic XXXXXXXXXXXX";
        awp_add_to_log( $return, $url, $args, $integration );

    $response['success']=true;    
    return $response;
}
