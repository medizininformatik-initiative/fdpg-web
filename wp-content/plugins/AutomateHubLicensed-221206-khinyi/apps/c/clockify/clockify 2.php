<?php

class AWP_Clockify extends appfactory
{

   
    public function init_actions(){
       
        add_action('admin_post_awp_clockify_save_api_token', [$this, 'awp_save_clockify_api_token'], 10, 0);
        
        add_action( 'wp_ajax_awp_fetch_workspace', [$this, 'awp_fetch_workspace']);
        add_action( 'wp_ajax_awp_fetch_project', [$this, 'awp_fetch_project']);
        
    }

    public function init_filters(){

        add_filter('awp_platforms_connections', [$this, 'awp_clockify_platform_connection'], 10, 1);
    }

    public function action_provider( $providers ) {
        $providers['clockify'] = array(
            'title' => esc_html__( 'Clockify', 'automate_hub' ),
            'tasks' => array(
                'createclient'   => esc_html__( 'Create Client', 'automate_hub' ),
                'createproject'   => esc_html__( 'Create Project', 'automate_hub' ),
                'createtag'   => esc_html__( 'Create Tag', 'automate_hub' ),
                'createtask'   => esc_html__( 'Create Task', 'automate_hub' )
            )
        );
    
        return  $providers;
    }

    public function settings_tab( $tabs ) {
        $tabs['clockify'] = array('name'=>esc_html__( 'Clockify', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'clockify') {
            return;
        }
        $nonce = wp_create_nonce("awp_clockify_settings");
        $api_token = isset($_GET['api_key']) ? sanitize_text_field($_GET['api_key']) : "";
        $id = isset($_GET['id']) ? intval( sanitize_text_field($_GET['id'] )) : "";
        $display_name     = isset($_GET['account_name']) ? sanitize_text_field($_GET['account_name']) : "";
        ?>
        <div class="platformheader">

            <a href="https://sperse.io/go/clockify" target="_blank"><img src="<?php echo esc_url(AWP_ASSETS.'/images/logos/clockify.png'); ?>"  alt="Clockify Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager(sanitize_text_field($_GET['tab']));
                    $instruction_obj->prepare_instructions();
                        
                ?>

                <br />

                <?php 

                $form_fields = '';
                $app_name= 'clockify';
                $clockify_form = new AWP_Form_Fields($app_name);

                $form_fields = $clockify_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_clockify_display_name",
                        'name'          => "awp_clockify_display_name",
                        'value'         => $display_name,
                        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
                        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $clockify_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_clockify_api_token",
                        'name'          => "awp_clockify_api_token",
                        'value'         => $api_token,
                        'placeholder'   => esc_html__( 'Enter your Clockify Live API Key', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Clockify API Key', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $clockify_form->awp_wp_hidden_input(
                    array(
                        'name'          => "action",
                        'value'         => 'awp_clockify_save_api_token',
                    )
                );


                $form_fields .= $clockify_form->awp_wp_hidden_input(
                    array(
                        'name'          => "_nonce",
                        'value'         =>$nonce,
                    )
                );
                $form_fields .= $clockify_form->awp_wp_hidden_input(
                    array(
                        'name'          => "id",
                        'value'         =>wp_unslash($id),
                    )
                );


                $clockify_form->render($form_fields);

                ?>


        </div>

        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                 $data=[
                    'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('clockify');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();

                ?>
                        </form>
                </div>
        <?php
    }

    public function awp_save_clockify_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_clockify_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_token = sanitize_text_field($_POST["awp_clockify_api_token"]);
        $display_name     = sanitize_text_field( $_POST["awp_clockify_display_name"] );

        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('clockify');
        $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_token]);

        AWP_redirect("admin.php?page=automate_hub&tab=clockify");
    } 
    
    public function load_custom_script() {
        wp_enqueue_script( 'awp-clockify-script', AWP_URL . '/apps/c/clockify/clockify.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="clockify-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'clockify',
                           'app_name'=>'Clockify ',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/clockify.png',
                           'app_icon_alter_text'=>'Clockify  Icon',
                           'account_select_onchange'=>'getworkspace',
                           'tasks'=>array(
                                'createclient'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Workspace',
                                            'type'=>'select',
                                            'name'=>"workspaceid",
                                            'required' => 'required',
                                            'onchange' => 'selectedworkspace',
                                            'option_for_loop'=>'(item) in data.workspaceList',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Workspace...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': workspaceLoading}",
                                                        )
                                        )
                                    ),
                                ),
                                'createtag'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Workspace',
                                            'type'=>'select',
                                            'name'=>"workspaceid",
                                            'required' => 'required',
                                            'onchange' => 'selectedworkspace',
                                            'option_for_loop'=>'(item) in data.workspaceList',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Workspace...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': workspaceLoading}",
                                                        )
                                        )
                                    ),
                                ),
                                'createproject'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Workspace',
                                            'type'=>'select',
                                            'name'=>"workspaceid",
                                            'required' => 'required',
                                            'onchange' => 'getcolor',
                                            'option_for_loop'=>'(item) in data.workspaceList',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Workspace...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': workspaceLoading}",
                                                        )
                                        ),
                                        array(
                                            'label'=>'Select Color',
                                            'type'=>'select',
                                            'name'=>"color",
                                            'required' => 'required',
                                            'onchange' => 'selectedcolor',
                                            'option_for_loop'=>'(item) in data.colorlist',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Color...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': colorLoading}",
                                                        )
                                        )
                                    ),
                                ),
                                'createtask'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Workspace',
                                            'type'=>'select',
                                            'name'=>"workspaceid",
                                            'required' => 'required',
                                            'onchange' => 'getproject',
                                            'option_for_loop'=>'(item) in data.workspaceList',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Workspace...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': workspaceLoading}",
                                                        )
                                        ),
                                        array(
                                            'label'=>'Select Project',
                                            'type'=>'select',
                                            'name'=>"color",
                                            'required' => 'required',
                                            'onchange' => 'selectedproject',
                                            'option_for_loop'=>'(item) in data.projectList',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Project...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': projectLoading}",
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

    
    public function awp_fetch_workspace() {
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
        $platform_obj= new AWP_Platform_Shell_Table('clockify');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;

        $url = "https://api.clockify.me/api/v1/workspaces";
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'X-Api-Key' => $api_key
            )
        );

        $response = wp_remote_get( $url, $args );

        $body = wp_remote_retrieve_body( $response );
        $body = json_decode( $body, true );
        wp_send_json_success($body);
    }

    public function awp_fetch_project() {
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
        $platform_obj= new AWP_Platform_Shell_Table('clockify');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;



        $url = "https://api.clockify.me/api/v1/workspaces/".$_POST['workspaceid']."/projects";
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'X-Api-Key' => $api_key
            )
        );

        $response = wp_remote_get( $url, $args );

        $body = wp_remote_retrieve_body( $response );
        $body = json_decode( $body, true );
        wp_send_json_success($body);
    }

};


$Awp_Clockify = new AWP_Clockify();

function awp_clockify_save_integration() {
    Appfactory::save_integration();
}

function awp_clockify_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('clockify');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $task = $decoded_data["task"]; 

   

    if( $task == "createclient" ) {

        $name = empty( $data["name"] ) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $note = empty( $data["note"] ) ? "" : awp_get_parsed_values($data["note"], $posted_data);
        $address = empty( $data["address"] ) ? "" : awp_get_parsed_values($data["address"], $posted_data);


        $body = json_encode([
            "name"=>$name,
            "note"=>$note,
            "ClientAddress"=>$address
        ]);

        $url = "https://api.clockify.me/api/v1/workspaces/".$data['workspaceid']."/clients";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'X-Api-Key' => $api_key,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } else if($task == "createtag"){

        $name = empty( $data["tagname"] ) ? "" : awp_get_parsed_values($data["tagname"], $posted_data);
        
        $body = json_encode([
            "name"=>$name
        ]);

        $url = "https://api.clockify.me/api/v1/workspaces/".$data['workspaceid']."/tags";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'X-Api-Key' => $api_key,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),

            'body'=> $body
        ];

        

        $response  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    }else if($task == "createproject"){

        $name = empty( $data["projectname"] ) ? "" : awp_get_parsed_values($data["projectname"], $posted_data);
        $note = empty( $data["projectnote"] ) ? "" : awp_get_parsed_values($data["projectnote"], $posted_data);
        $color = empty( $data["color"] ) ? "" : awp_get_parsed_values($data["color"], $posted_data);
        
        $body = json_encode([
            "name"=>$name,
            "note"=>$note,
            "color"=>$color,
            "public" => (boolean) false
        ]);

        $url = "https://api.clockify.me/api/v1/workspaces/".$data['workspaceid']."/projects";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'X-Api-Key' => $api_key,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),

            'body'=> $body
        ];

        

        $response  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    }else if($task == "createtask"){

        $name = empty( $data["taskname"] ) ? "" : awp_get_parsed_values($data["taskname"], $posted_data);
     
        $body = json_encode([
            "name"=>$name
        ]);

        $url = "https://api.clockify.me/api/v1/workspaces/".$data['workspaceid']."/projects/".$data['projectid']."/tasks";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'X-Api-Key' => $api_key,
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


function awp_clockify_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('clockify');
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
