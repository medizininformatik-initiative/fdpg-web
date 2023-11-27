<?php

class AWP_Airmeet extends appfactory
{

   
    public function init_actions(){
       
        add_action('admin_post_awp_airmeet_save_api_token', [$this, 'awp_save_airmeet_api_token'], 10, 0);
        
        add_action( 'wp_ajax_awp_fetch_source', [$this, 'awp_fetch_source']);
        
    }

    public function init_filters(){

        add_filter('awp_platforms_connections', [$this, 'awp_airmeet_platform_connection'], 10, 1);
    }

    public function action_provider( $providers ) {
        $providers['airmeet'] = array(
            'title' => esc_html__( 'Airmeet', 'automate_hub' ),
            'tasks' => array(
                'createmeet'   => esc_html__( 'Create Meeting', 'automate_hub' )
            )
        );
    
        return  $providers;
    }

    public function settings_tab( $tabs ) {
        $tabs['airmeet'] = array('name'=>esc_html__( 'Airmeet', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'airmeet') {
            return;
        }
        $nonce = wp_create_nonce("awp_airmeet_settings");
        $api_token = isset($_GET['awp_airmeet_api_access_key']) ? sanitize_text_field($_GET['awp_airmeet_api_access_key']) : "";
        $api_secret_key = isset($_GET['awp_airmeet_api_secret_key']) ? sanitize_text_field($_GET['awp_airmeet_api_secret_key']) : "";
        $airmeet_email = isset($_GET['awp_airmeet_email']) ? sanitize_text_field($_GET['awp_airmeet_email']) : "";
        $id = isset($_GET['id']) ? intval( sanitize_text_field($_GET['id'] )) : "";
        $display_name     = isset($_GET['account_name']) ? sanitize_text_field($_GET['account_name']) : "";
        ?>
        <div class="platformheader">

            <a href="https://sperse.io/go/airmeet" target="_blank"><img src="<?php echo esc_url(AWP_ASSETS.'/images/logos/airmeet.png'); ?>" height="50" alt="Airmeet Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager(sanitize_text_field($_GET['tab']));
                    $instruction_obj->prepare_instructions();
                        
                ?>

                <br />

                <?php 

                $form_fields = '';
                $app_name= 'airmeet';
                $airmeet_form = new AWP_Form_Fields($app_name);

                $form_fields = $airmeet_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_airmeet_display_name",
                        'name'          => "awp_airmeet_display_name",
                        'value'         => $display_name,
                        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
                        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $airmeet_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_airmeet_api_access_key",
                        'name'          => "awp_airmeet_api_access_key",
                        'value'         => $api_token,
                        'placeholder'   => esc_html__( 'Enter your Airmeet API Access Key', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Airmeet API Acess Key', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $airmeet_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_airmeet_api_secret_key",
                        'name'          => "awp_airmeet_api_secret_key",
                        'value'         => $api_secret_key,
                        'placeholder'   => esc_html__( 'Enter your Airmeet API Secret Key', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Airmeet API Secret Key', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $airmeet_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_airmeet_email",
                        'name'          => "awp_airmeet_email",
                        'value'         => $airmeet_email,
                        'placeholder'   => esc_html__( 'Enter your Email Address', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Email Address', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                

                $form_fields .= $airmeet_form->awp_wp_hidden_input(
                    array(
                        'name'          => "action",
                        'value'         => 'awp_airmeet_save_api_token',
                    )
                );


                $form_fields .= $airmeet_form->awp_wp_hidden_input(
                    array(
                        'name'          => "_nonce",
                        'value'         =>$nonce,
                    )
                );
                $form_fields .= $airmeet_form->awp_wp_hidden_input(
                    array(
                        'name'          => "id",
                        'value'         =>wp_unslash($id),
                    )
                );


                $airmeet_form->render($form_fields);

                ?>


        </div>

        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                 $data=[
                    'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('airmeet');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();

                ?>
                        </form>
                </div>
        <?php
    }

    public function awp_save_airmeet_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_airmeet_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_token = sanitize_text_field($_POST["awp_airmeet_api_access_key"]);
        $api_secret_key = sanitize_text_field($_POST["awp_airmeet_api_secret_key"]);
        $airmeet_email = sanitize_text_field($_POST["awp_airmeet_email"]);
        $display_name     = sanitize_text_field( $_POST["awp_airmeet_display_name"] );

        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('airmeet');
        $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_token, 'client_id' => $api_secret_key, 'email' => $airmeet_email]);

        AWP_redirect("admin.php?page=automate_hub&tab=airmeet");
    } 
    
    public function load_custom_script() {
        wp_enqueue_script( 'awp-airmeet-script', AWP_URL . '/apps/a/airmeet/airmeet.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="airmeet-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'airmeet',
                           'app_name'=>'Airmeet ',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/airmeet.png',
                           'app_icon_alter_text'=>'Airmeet  Icon',
                           'account_select_onchange'=>'gettimezone',
                           'tasks'=>array(
                                'createmeet'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Timezone',
                                            'type'=>'select',
                                            'name'=>"timezone",
                                            'required' => 'required',
                                            'onchange' => 'selectedtimezone',
                                            'option_for_loop'=>'(item) in data.timezonelist',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Timezone...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': timezoneLoading}",
                                                        )
                                        )
                                    ),
                                ),
                            ),
                    ); 

                        require (AWP_VIEWS.'/awp_app_integration_format.php');
                ?>
            </script>
        <?php
    }

};


$Awp_Airmeet = new AWP_Airmeet();

function awp_airmeet_save_integration() {
    Appfactory::save_integration();
}

function awp_get_airmeet_token($access_key, $secreet_key){
    $url = "https://api.airmeet.com/api/v1/auth";

    $args = [
        'headers' => array(
            'Content-Type' => 'application/json',
            'X-Airmeet-Access-Key' => $access_key,
            'X-Airmeet-Secret-Key' => $secreet_key
        )
    ];

    $response  = wp_remote_post($url,  $args );

    $body = wp_remote_retrieve_body( $response );

    $body = json_decode( $body, true );

    return $body['token'];
}
function awp_airmeet_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 

    $platform_obj= new AWP_Platform_Shell_Table('airmeet');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    
    $api_access_key=$temp->api_key;
    $api_secret_key=$temp->client_id;
    $api_email=$temp->email;

    $api_token = $this->awp_get_airmeet_token($api_access_key, $api_secret_key);

    $task = $decoded_data["task"]; 

   

    if( $task == "createmeet" ) {

        $eventName = empty( $data["eventName"] ) ? "" : awp_get_parsed_values($data["eventName"], $posted_data);
        $shortDesc = empty( $data["shortDesc"] ) ? "" : awp_get_parsed_values($data["shortDesc"], $posted_data);
        $longDesc = empty( $data["longDesc"] ) ? "" : awp_get_parsed_values($data["longDesc"], $posted_data);
        $startTime = empty( $data["startTime"] ) ? "" : awp_get_parsed_values($data["startTime"], $posted_data);
        $endTime = empty( $data["endTime"] ) ? "" : awp_get_parsed_values($data["endTime"], $posted_data);
        $timezone = empty( $data["timezone"] ) ? "" : awp_get_parsed_values($data["timezone"], $posted_data);
       
        $body = json_encode([
            "hostEmail" => $api_email,
            "eventName" => $eventName,
            "shortDesc" => $shortDesc,
            "longDesc" => $longDesc,
            "access" => "INVITED_ONLY",
            "eventType" => "MEETUP",
            "timing" => [
                "startTime" => strtotime($startTime),   
                "endTime" =>  strtotime($endTime),   
                "timezone" => $timezone 
            ],
            "config" => [ 
                "networking" => true,   
                "tableCount" => 0 
            ]
        ]);

        $url = "https://api.airmeet.com/airmeet";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'X-Airmeet-Access-Token' => $api_token,
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['X-Airmeet-Access-Token']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } 
    return $response;
}


function awp_airmeet_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('airmeet');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);

    $api_access_key=$temp->api_key;
    $api_secret_key=$temp->client_id;

    $api_key = $this->awp_get_airmeet_token($api_access_key, $api_secret_key);

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
        

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'X-Airmeet-Access-Token' => $api_key,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),

            'body'=> json_encode($body)
        ];


        $return  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="Bearer XXXXXXXXXXXX";
        awp_add_to_log( $return, $url, $args, $integration );

    $response['success']=true;    
    return $response;
}
