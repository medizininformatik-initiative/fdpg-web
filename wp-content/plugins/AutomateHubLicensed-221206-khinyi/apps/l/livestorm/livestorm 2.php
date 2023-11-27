<?php

class AWP_Livestorm extends appfactory
{

   
    public function init_actions(){
        add_action('admin_post_awp_livestorm_save_api_token', [$this, 'awp_save_livestorm_api_token'], 10, 0);
        add_action( 'wp_ajax_awp_fetch_session', [$this, 'awp_fetch_session']);
    }

    public function init_filters(){

        add_filter('awp_platforms_connections', [$this, 'awp_livestorm_platform_connection'], 10, 1);
    }

    public function action_provider( $providers ) {
        $providers['livestorm'] = array(
            'title' => esc_html__( 'Livestorm', 'automate_hub' ),
            'tasks' => array(
                'createevent'   => esc_html__( 'Create Event', 'automate_hub' ),
                'createsession'   => esc_html__( 'Create Session', 'automate_hub' )
            )
        );
    
        return  $providers;
    }

    public function settings_tab( $tabs ) {
        $tabs['livestorm'] = array('name'=>esc_html__( 'Livestorm', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        
        if ($current_tab != 'livestorm') {
            return;
        }
        $nonce = wp_create_nonce("awp_livestorm_settings");
        $api_token = isset($_GET['api_key']) ? sanitize_text_field($_GET['api_key']) : "";
        $id = isset($_GET['id']) ? intval( sanitize_text_field($_GET['id'] )) : "";
        $display_name     = isset($_GET['account_name']) ? sanitize_text_field($_GET['account_name']) : "";
        ?>
        <div class="platformheader">

            <a href="https://sperse.io/go/livestorm" target="_blank"><img src="<?php echo esc_url(AWP_ASSETS_REMOTE.'/images/logos/livestorm.png'); ?>" width="320" height="50" alt="Livestorm Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager(sanitize_text_field($_GET['tab']));
                    $instruction_obj->prepare_instructions();
                        
                ?>

                <br />

                <?php 

                $form_fields = '';
                $app_name= 'livestorm';
                $livestorm_form = new AWP_Form_Fields($app_name);

                $form_fields = $livestorm_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_livestorm_display_name",
                        'name'          => "awp_livestorm_display_name",
                        'value'         => $display_name,
                        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
                        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $livestorm_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_livestorm_api_token",
                        'name'          => "awp_livestorm_api_token",
                        'value'         => $api_token,
                        'placeholder'   => esc_html__( 'Enter your Livestorm Live API Key', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Livestorm API Key', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $livestorm_form->awp_wp_hidden_input(
                    array(
                        'name'          => "action",
                        'value'         => 'awp_livestorm_save_api_token',
                    )
                );


                $form_fields .= $livestorm_form->awp_wp_hidden_input(
                    array(
                        'name'          => "_nonce",
                        'value'         =>$nonce,
                    )
                );
                $form_fields .= $livestorm_form->awp_wp_hidden_input(
                    array(
                        'name'          => "id",
                        'value'         =>wp_unslash($id),
                    )
                );


                $livestorm_form->render($form_fields);

                ?>


        </div>

        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                 $data=[
                    'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('livestorm');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();

                ?>
                        </form>
                </div>
        <?php
    }

    public function awp_save_livestorm_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_livestorm_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_token = sanitize_text_field($_POST["awp_livestorm_api_token"]);
        $display_name     = sanitize_text_field( $_POST["awp_livestorm_display_name"] );

        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('livestorm');
        $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_token]);

        AWP_redirect("admin.php?page=automate_hub&tab=livestorm");
    } 
    
    public function load_custom_script() {
        wp_enqueue_script( 'awp-livestorm-script', AWP_URL . '/apps/l/livestorm/livestorm.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="livestorm-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'livestorm',
                           'app_name'=>'Livestorm ',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/livestorm.png',
                           'app_icon_alter_text'=>'Livestorm  Icon',
                           'account_select_onchange'=>'getlivestormeventtype',
                           'tasks'=>array(
                                'createevent'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Event',
                                            'type'=>'select',
                                            'name'=>"eventtype",
                                            'required' => 'required',
                                            'onchange' => 'getchartstatus',
                                            'option_for_loop'=>'(item) in data.eventtypelist',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Event...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': eventLoading}",
                                                        )
                                        ),
                                        array(
                                            'label'=>'Enable Chat Feature',
                                            'type'=>'select',
                                            'name'=>"chartstatus",
                                            'required' => 'required',
                                            'onchange' => 'getrecordingfeature',
                                            'option_for_loop'=>'(item) in data.chatstatuslist',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Status...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': chartLoading}",
                                                        )
                                        ),
                                        array(
                                            'label'=>'Enable Recording Feature',
                                            'type'=>'select',
                                            'name'=>"recordingstatus",
                                            'required' => 'required',
                                            'onchange' => 'selectedrecordingstatus',
                                            'option_for_loop'=>'(item) in data.recordstatuslist',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Status...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': recordingLoading}",
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

    
  
};


$Awp_Livestorm = new AWP_Livestorm();

function awp_livestorm_save_integration() {
    Appfactory::save_integration();
}

function awp_get_current_user($api_key){
        
    $url = "https://api.livestorm.co/v1/me";
    
    $args = array(
        'headers' => array(
            'accept' => 'application/vnd.api+json',
            'Authorization' => $api_key
        )
    );

    $response = wp_remote_get( $url, $args );
    $body = wp_remote_retrieve_body( $response );
    $body = json_decode( $body, true );

    $current_user = $body['data']['id'];

    return $current_user;
}

function awp_livestorm_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('livestorm');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $task = $decoded_data["task"]; 

   

    if( $task == "createevent" ) {

        $title = empty( $data["title"] ) ? "" : awp_get_parsed_values($data["title"], $posted_data);
        $description = empty( $data["description"] ) ? "" : awp_get_parsed_values($data["description"], $posted_data);
        $owner_id = awp_get_current_user($api_key);
        $eventtype = empty( $data["eventtype"] ) ? "" : awp_get_parsed_values($data["eventtype"], $posted_data);
        $chartstatus = empty( $data["chartstatus"] ) ? "" : awp_get_parsed_values($data["chartstatus"], $posted_data);
        $recordingstatus = empty( $data["recordingstatus"] ) ? "" : awp_get_parsed_values($data["recordingstatus"], $posted_data);

        $body = json_encode([
            "data" => [
                "type" => "events",
                "attributes" => [
                    "owner_id" => $owner_id,
                    "title" => $title,
                    "description" => "<h1>".$description."</h1>",
                    "eventtype" => $eventtype,
                    "chartstatus" => $chartstatus,
                    "recordingstatus" => $recordingstatus
                ]
            ]
        ]);

        $url = "https://api.livestorm.co/v1/events";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/vnd.api+json',
                'accept' => 'application/vnd.api+json',
                'Authorization' => $api_key,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        error_log(print_r('Response: ', true), 0);
        error_log(print_r($response, true), 0);

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } 
    return $response;
}


function awp_livestorm_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('livestorm');
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
