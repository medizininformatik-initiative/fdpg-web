<?php

class AWP_Testmonitor extends appfactory
{

   
    public function init_actions(){
       
        add_action('admin_post_awp_testmonitor_save_api_token', [$this, 'awp_save_testmonitor_api_token'], 10, 0);
        
        add_action( 'wp_ajax_awp_fetch_source', [$this, 'awp_fetch_source']);
        
    }

    public function init_filters(){

        add_filter('awp_platforms_connections', [$this, 'awp_testmonitor_platform_connection'], 10, 1);
    }

    public function action_provider( $providers ) {
        $providers['testmonitor'] = array(
            'title' => esc_html__( 'Testmonitor', 'automate_hub' ),
            'tasks' => array(
                'createproject'   => esc_html__( 'Create Project', 'automate_hub' ),
                'createteam'   => esc_html__( 'Create Team', 'automate_hub' ),
                'createenvironment'   => esc_html__( 'Create Test Environment', 'automate_hub' ),
                'createapplication'   => esc_html__( 'Create Application', 'automate_hub' ),
            )
        );
    
        return  $providers;
    }

    public function settings_tab( $tabs ) {
        $tabs['testmonitor'] = array('name'=>esc_html__( 'Testmonitor', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'testmonitor') {
            return;
        }
        $nonce = wp_create_nonce("awp_testmonitor_settings");
        $api_token = isset($_GET['api_key']) ? sanitize_text_field($_GET['api_key']) : "";
        $id = isset($_GET['id']) ? intval( sanitize_text_field($_GET['id'] )) : "";
        $url = isset($_GET['url']) ? intval( sanitize_text_field($_GET['url'] )) : "";
        $display_name     = isset($_GET['account_name']) ? sanitize_text_field($_GET['account_name']) : "";
        ?>
        <div class="platformheader">

            <a href="https://sperse.io/go/testmonitor" target="_blank"><img src="<?php echo esc_url(AWP_ASSETS.'/images/logos/testmonitor.png'); ?>"   alt="Testmonitor Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager(sanitize_text_field($_GET['tab']));
                    $instruction_obj->prepare_instructions();
                        
                ?>

                <br />

                <?php 

                $form_fields = '';
                $app_name= 'testmonitor';
                $testmonitor_form = new AWP_Form_Fields($app_name);

                $form_fields = $testmonitor_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_testmonitor_display_name",
                        'name'          => "awp_testmonitor_display_name",
                        'value'         => $display_name,
                        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
                        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $testmonitor_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_testmonitor_api_token",
                        'name'          => "awp_testmonitor_api_token",
                        'value'         => $api_token,
                        'placeholder'   => esc_html__( 'Enter your Testmonitor API Key', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Testmonitor API Key', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );
                $form_fields .= $testmonitor_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_testmonitor_domain_url",
                        'name'          => "awp_testmonitor_domain_url",
                        'value'         => $url,
                        'placeholder'   => esc_html__( 'https://abc.testmonitor.com/', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Testmonitor Domain Url', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $testmonitor_form->awp_wp_hidden_input(
                    array(
                        'name'          => "action",
                        'value'         => 'awp_testmonitor_save_api_token',
                    )
                );


                $form_fields .= $testmonitor_form->awp_wp_hidden_input(
                    array(
                        'name'          => "_nonce",
                        'value'         =>$nonce,
                    )
                );
                $form_fields .= $testmonitor_form->awp_wp_hidden_input(
                    array(
                        'name'          => "id",
                        'value'         =>wp_unslash($id),
                    )
                );


                $testmonitor_form->render($form_fields);

                ?>


        </div>

        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                 $data=[
                    'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('testmonitor');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();

                ?>
                        </form>
                </div>
        <?php
    }

    public function awp_save_testmonitor_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_testmonitor_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_token = sanitize_text_field($_POST["awp_testmonitor_api_token"]);
        $display_name     = sanitize_text_field( $_POST["awp_testmonitor_display_name"] );
        $url     = sanitize_text_field( $_POST["awp_testmonitor_domain_url"] );

        if ($url != "") {
            $url_regexPattern = '/^https:\/\/.[a-zA-Z0-9][a-zA-Z0-9\-]*\.testmonitor\.com\/\z/';
            
            if (preg_match($url_regexPattern, $url) == 1) {
                // Save tokens
                $platform_obj = new AWP_Platform_Shell_Table('testmonitor');
                $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_token, 'url' => $url]);

                AWP_redirect("admin.php?page=automate_hub&tab=testmonitor");
            } else {
                wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=testmonitor'));
                exit();
            }
        } else {
            wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=testmonitor'));
            exit();
        }

       
    } 
    
    public function load_custom_script() {
        wp_enqueue_script( 'awp-testmonitor-script', AWP_URL . '/apps/t/testmonitor/testmonitor.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="testmonitor-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'testmonitor',
                           'app_name'=>'Testmonitor ',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/testmonitor.png',
                           'app_icon_alter_text'=>'Testmonitor  Icon',
                           'account_select_onchange'=>'getsourcesid',
                           'tasks'=>array(
                                'createproect'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Source',
                                            'type'=>'select',
                                            'name'=>"source_id",
                                            'required' => 'required',
                                            'onchange' => 'selectedsource',
                                            'option_for_loop'=>'(item) in data.sourceList',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.provider}}',
                                            'select_default'=>'Select Source...',
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

    
    public function awp_fetch_source() {
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
        $platform_obj= new AWP_Platform_Shell_Table('testmonitor');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;

        $url = "https://api.testmonitor.com/v1/sources";
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$api_key
            )
        );

        $response = wp_remote_get( $url, $args );
        $body = wp_remote_retrieve_body( $response );
        $body = json_decode( $body, true );
        wp_send_json_success($body["sources"]);
    }

};


$Awp_Testmonitor = new AWP_Testmonitor();

function awp_testmonitor_save_integration() {
    Appfactory::save_integration();
}

function awp_testmonitor_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('testmonitor');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $url=$temp->url;
    $task = $decoded_data["task"]; 

   

    if( $task == "createproject" ) {
        
        $name = empty( $data["name"] ) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $description = empty( $data["description"] ) ? "" : awp_get_parsed_values($data["description"], $posted_data);
        $starts_at = empty( $data["starts_at"] ) ? "" : awp_get_parsed_values($data["starts_at"], $posted_data);
        $ends_at = empty( $data["ends_at"] ) ? "" : awp_get_parsed_values($data["ends_at"], $posted_data);
        $uses_applications = true;
        $uses_requirements = true;
        $uses_risks = true;
        $uses_issues = true;
        $uses_messages = true;
       

        $token = 'Bearer '.$api_key;

        $body = json_encode([
            "name" => $name ,
            "description" => $description,
            "starts_at" => $starts_at,
            "ends_at" => $ends_at,
            "uses_applications" => (boolean) $uses_applications,
            "uses_requirements" => (boolean) $uses_requirements,
            "uses_risks" => (boolean) $uses_risks,
            "uses_issues" => (boolean) $uses_issues,
            "uses_messages" => (boolean) $uses_messages

        ]);

        $url = $url. "api/v1/projects";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $token,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } else if($task == "createteam"){

        $name = empty( $data["tname"] ) ? "" : awp_get_parsed_values($data["tname"], $posted_data);
        $description = empty( $data["tdescription"] ) ? "" : awp_get_parsed_values($data["tdescription"], $posted_data);
       
        $token = 'Bearer '.$api_key;

        

        $body = json_encode([
            "name"=>$name,
            "description"=>$description
        ]);

       
        
        $url = $url = $url. "api/v1/teams";


        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $token,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),

            'body'=> $body
        ];

        

        $response  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } else if($task == "createenvironment"){

        $name = empty( $data["envname"] ) ? "" : awp_get_parsed_values($data["envname"], $posted_data);
        $description = empty( $data["envdescription"] ) ? "" : awp_get_parsed_values($data["envdescription"], $posted_data);
       
        $token = 'Bearer '.$api_key;

        

        $body = json_encode([
            "name"=>$name,
            "description"=>$description
        ]);

        $url = $url. "api/v1/test-environments";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $token,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),

            'body'=> $body
        ];

        

        $response  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } else if($task == "createapplication"){

        $name = empty( $data["appname"] ) ? "" : awp_get_parsed_values($data["appname"], $posted_data);
        $description = empty( $data["appdescription"] ) ? "" : awp_get_parsed_values($data["appdescription"], $posted_data);
       
        $token = 'Bearer '.$api_key;

        

        $body = json_encode([
            "name"=>$name,
            "description"=>$description
        ]);

        $url = $url. "api/v1/applications";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
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


function awp_testmonitor_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('testmonitor');
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
