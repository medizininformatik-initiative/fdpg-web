<?php

class AWP_Breeze extends appfactory
{

   
    public function init_actions(){
       
        add_action('admin_post_awp_breeze_save_api_token', [$this, 'awp_save_breeze_api_token'], 10, 0);
        
        add_action( 'wp_ajax_awp_fetch_workspaces', [$this, 'awp_fetch_workspaces']);
        
        
    }

    public function init_filters(){

        add_filter('awp_platforms_connections', [$this, 'awp_breeze_platform_connection'], 10, 1);
    }

    public function action_provider( $providers ) {
        $providers['breeze'] = array(
            'title' => esc_html__( 'Breeze', 'automate_hub' ),
            'tasks' => array(
                'createworkspace'   => esc_html__( 'Create Workspace', 'automate_hub' ),
                'createproject'   => esc_html__( 'Create Project', 'automate_hub' )
            )
        );
    
        return  $providers;
    }

    public function settings_tab( $tabs ) {
        $tabs['breeze'] = array('name'=>esc_html__( 'Breeze', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'breeze') {
            return;
        }
        $nonce = wp_create_nonce("awp_breeze_settings");
        $api_token = isset($_GET['api_key']) ? sanitize_text_field($_GET['api_key']) : "";
        $id = isset($_GET['id']) ? intval( sanitize_text_field($_GET['id'] )) : "";
        $display_name     = isset($_GET['account_name']) ? sanitize_text_field($_GET['account_name']) : "";
        ?>
        <div class="platformheader">

            <a href="https://sperse.io/go/breeze" target="_blank"><img src="<?php echo esc_url(AWP_ASSETS.'/images/logos/breeze.png'); ?>"  height="50" alt="Breeze Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager(sanitize_text_field($_GET['tab']));
                    $instruction_obj->prepare_instructions();
                        
                ?>

                <br />

                <?php 

                $form_fields = '';
                $app_name= 'breeze';
                $breeze_form = new AWP_Form_Fields($app_name);

                $form_fields = $breeze_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_breeze_display_name",
                        'name'          => "awp_breeze_display_name",
                        'value'         => $display_name,
                        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
                        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $breeze_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_breeze_api_token",
                        'name'          => "awp_breeze_api_token",
                        'value'         => $api_token,
                        'placeholder'   => esc_html__( 'Enter your Breeze API Key', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Breeze API Key', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $breeze_form->awp_wp_hidden_input(
                    array(
                        'name'          => "action",
                        'value'         => 'awp_breeze_save_api_token',
                    )
                );


                $form_fields .= $breeze_form->awp_wp_hidden_input(
                    array(
                        'name'          => "_nonce",
                        'value'         =>$nonce,
                    )
                );
                $form_fields .= $breeze_form->awp_wp_hidden_input(
                    array(
                        'name'          => "id",
                        'value'         =>wp_unslash($id),
                    )
                );


                $breeze_form->render($form_fields);

                ?>


        </div>

        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                 $data=[
                    'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('breeze');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();

                ?>
                        </form>
                </div>
        <?php
    }

    public function awp_save_breeze_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_breeze_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_token = sanitize_text_field($_POST["awp_breeze_api_token"]);
        $display_name     = sanitize_text_field( $_POST["awp_breeze_display_name"] );

        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('breeze');
        $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_token]);

        AWP_redirect("admin.php?page=automate_hub&tab=breeze");
    } 
    
    public function load_custom_script() {
        wp_enqueue_script( 'awp-breeze-script', AWP_URL . '/apps/b/breeze/breeze.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="breeze-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'breeze',
                           'app_name'=>'Breeze ',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/breeze.png',
                           'app_icon_alter_text'=>'Breeze  Icon',
                           'account_select_onchange'=>'getworkspaces',
                           'tasks'=>array(
                                'createproject'=>array(
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
                                                            'bind-class'=>"{'is-active': workspaceLoading }",
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

    
    public function awp_fetch_workspaces() {
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
        $platform_obj= new AWP_Platform_Shell_Table('breeze');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;

        $url = "https://api.breeze.pm/workspaces?api_token=".$api_key;

        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json'
            )
        );

        $response = wp_remote_get( $url, $args );
        $body = wp_remote_retrieve_body( $response );
        $body = json_decode( $body, true );
        
        wp_send_json_success($body);
    }

};


$Awp_Breeze = new AWP_Breeze();

function awp_breeze_save_integration() {
    Appfactory::save_integration();
}

function awp_breeze_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('breeze');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $task = $decoded_data["task"]; 

   

    if( $task == "createproject" ) {

        $name = empty( $data["name"] ) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $description = empty( $data["descriptions"] ) ? "" : awp_get_parsed_values($data["descriptions"], $posted_data);
        $budget_amount = empty( $data["budget_amount"] ) ? "" : awp_get_parsed_values($data["budget_amount"], $posted_data);
        $budget_hours = empty( $data["budget_hours"] ) ? "" : awp_get_parsed_values($data["budget_hours"], $posted_data);
        $hourly_rate = empty( $data["hourly_rate"] ) ? "" : awp_get_parsed_values($data["hourly_rate"], $posted_data);
        $currency_symbol = empty( $data["currency"] ) ? "" : awp_get_parsed_values($data["currency"], $posted_data);
        $workspace_id = empty( $data["workspaceid"] ) ? "" : awp_get_parsed_values($data["workspaceid"], $posted_data);

        $body = json_encode([
            "name" => $name,
            "description" => $description,
            "budget_amount" => $budget_amount,
            "budget_hours" => (integer) $budget_hours,
            "hourly_rate" => (integer) $hourly_rate,
            "currency_symbol" => $currency_symbol,
            "workspace_id" => $workspace_id,
            "invitees" => ['highbee4u@gmail.com']
        ]);

        $url = "https://api.breeze.pm/projects.json?api_token=".$api_key;

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );
        
        error_log(print_r("Response: ", true),0);
        error_log(print_r($response, true),0);

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } else if($task == "createworkspace"){

        $name = empty( $data["workspacename"] ) ? "" : awp_get_parsed_values($data["workspacename"], $posted_data);

        $body = json_encode([
            "name"=>$name
        ]);
        
        $url = "https://api.breeze.pm/workspaces?api_token=".$api_key;

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
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


function awp_breeze_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('breeze');
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