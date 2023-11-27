<?php

class AWP_Mojohelpdesk extends appfactory
{

   
    public function init_actions(){
       
        add_action('admin_post_awp_mojohelpdesk_save_api_token', [$this, 'awp_save_mojohelpdesk_api_token'], 10, 0);
        
        add_action( 'wp_ajax_awp_fetch_source', [$this, 'awp_fetch_source']);
        
    }

    public function init_filters(){

        add_filter('awp_platforms_connections', [$this, 'awp_mojohelpdesk_platform_connection'], 10, 1);
    }

    public function action_provider( $providers ) {
        $providers['mojohelpdesk'] = array(
            'title' => esc_html__( 'Mojo helpdesk', 'automate_hub' ),
            'tasks' => array(
                'createticket'   => esc_html__( 'Create Ticket', 'automate_hub' )
            )
        );
    
        return  $providers;
    }

    public function settings_tab( $tabs ) {
        $tabs['mojohelpdesk'] = array('name'=>esc_html__( 'Mojo helpdesk', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'mojohelpdesk') {
            return;
        }
        $nonce = wp_create_nonce("awp_mojohelpdesk_settings");
        $api_token = isset($_GET['api_key']) ? sanitize_text_field($_GET['api_key']) : "";
        $id = isset($_GET['id']) ? intval( sanitize_text_field($_GET['id'] )) : "";
        $display_name     = isset($_GET['account_name']) ? sanitize_text_field($_GET['account_name']) : "";
        ?>
        <div class="platformheader">

            <a href="https://sperse.io/go/mojohelpdesk" target="_blank"><img src="<?php echo esc_url(AWP_ASSETS.'/images/logos/mojohelpdesk.png'); ?>"  height="50" alt="Mojohelpdesk Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager(sanitize_text_field($_GET['tab']));
                    $instruction_obj->prepare_instructions();
                        
                ?>

                <br />

                <?php 

                $form_fields = '';
                $app_name= 'mojohelpdesk';
                $mojohelpdesk_form = new AWP_Form_Fields($app_name);

                $form_fields = $mojohelpdesk_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_mojohelpdesk_display_name",
                        'name'          => "awp_mojohelpdesk_display_name",
                        'value'         => $display_name,
                        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
                        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $mojohelpdesk_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_mojohelpdesk_api_token",
                        'name'          => "awp_mojohelpdesk_api_token",
                        'value'         => $api_token,
                        'placeholder'   => esc_html__( 'Enter your Mojohelpdesk API Key', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Mojohelpdesk API Key', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $mojohelpdesk_form->awp_wp_hidden_input(
                    array(
                        'name'          => "action",
                        'value'         => 'awp_mojohelpdesk_save_api_token',
                    )
                );


                $form_fields .= $mojohelpdesk_form->awp_wp_hidden_input(
                    array(
                        'name'          => "_nonce",
                        'value'         =>$nonce,
                    )
                );
                $form_fields .= $mojohelpdesk_form->awp_wp_hidden_input(
                    array(
                        'name'          => "id",
                        'value'         =>wp_unslash($id),
                    )
                );


                $mojohelpdesk_form->render($form_fields);

                ?>


        </div>

        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                 $data=[
                    'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('mojohelpdesk');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();

                ?>
                        </form>
                </div>
        <?php
    }

    public function awp_save_mojohelpdesk_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_mojohelpdesk_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_token = sanitize_text_field($_POST["awp_mojohelpdesk_api_token"]);
        $display_name     = sanitize_text_field( $_POST["awp_mojohelpdesk_display_name"] );

        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('mojohelpdesk');
        $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_token]);

        AWP_redirect("admin.php?page=automate_hub&tab=mojohelpdesk");
    } 
    
    public function load_custom_script() {
        wp_enqueue_script( 'awp-mojohelpdesk-script', AWP_URL . '/apps/m/mojohelpdesk/mojohelpdesk.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="mojohelpdesk-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'mojohelpdesk',
                           'app_name'=>'Mojohelpdesk',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/mojohelpdesk.png',
                           'app_icon_alter_text'=>'Mojohelpdesk  Icon',
                           'account_select_onchange'=>'getpriority',
                           'tasks'=>array(
                                'createticket'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Priority',
                                            'type'=>'select',
                                            'name'=>"priority_id",
                                            'required' => 'required',
                                            'onchange' => 'getstatus',
                                            'option_for_loop'=>'(item) in data.priorityList',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Priority...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': priorityLoading}",
                                                        )
                                        ),
                                        array(
                                            'label'=>'Select Status',
                                            'type'=>'select',
                                            'name'=>"status_id",
                                            'required' => 'required',
                                            'onchange' => 'selectedstatus',
                                            'option_for_loop'=>'(item) in data.statusList',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Status...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': statusLoading}",
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
        $platform_obj= new AWP_Platform_Shell_Table('mojohelpdesk');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;

        $url = "https://api.mojohelpdesk.com/v1/sources";
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


$Awp_Mojohelpdesk = new AWP_Mojohelpdesk();

function awp_mojohelpdesk_save_integration() {
    Appfactory::save_integration();
}

function awp_mojohelpdesk_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('mojohelpdesk');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $task = $decoded_data["task"]; 

   

    if( $task == "createticket" ) {

        $title = empty( $data["title"] ) ? "" : awp_get_parsed_values($data["title"], $posted_data);
        $description = empty( $data["description"] ) ? "" : awp_get_parsed_values($data["description"], $posted_data);
        $email = empty( $data["email"] ) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $priority_id = empty( $data["priority_id"] ) ? "" : awp_get_parsed_values($data["priority_id"], $posted_data);
        $status_id = empty( $data["status_id"] ) ? "" : awp_get_parsed_values($data["status_id"], $posted_data);

        $body = json_encode([
            "title"=>$title,
            "description"=>$description,
            "status_id" => $status_id,
            "priority_id" => $priority_id,
            "user" => [
                "email" => $email
            ]
        ]);

        $url = "https://app.mojohelpdesk.com/api/v2/tickets?access_key=".$api_key;

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


function awp_mojohelpdesk_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('mojohelpdesk');
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
