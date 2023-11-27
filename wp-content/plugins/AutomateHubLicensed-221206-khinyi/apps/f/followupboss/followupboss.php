<?php

class AWP_Followupboss extends appfactory
{
    public function init_actions(){      
        add_action('admin_post_awp_followupboss_save_api_token', [$this, 'awp_save_followupboss_api_token'], 10, 0);
        add_action( 'wp_ajax_awp_fetch_stage', [$this, 'awp_fetch_stage']);
    }

    public function init_filters(){
        add_filter('awp_platforms_connections', [$this, 'awp_followupboss_platform_connection'], 10, 1);
    }

    public function action_provider( $providers ) {
        $providers['followupboss'] = array(
            'title' => esc_html__( 'Follow Up Boss', 'automate_hub' ),
            'tasks' => array(
                'createpeople'   => esc_html__( 'Create People', 'automate_hub' )
            )
        );
        return  $providers;
    }

    public function settings_tab( $tabs ) {
        $tabs['followupboss'] = array('name'=>esc_html__( 'Follow Up Boss', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'followupboss') {
            return;
        }
        $nonce = wp_create_nonce("awp_followupboss_settings");
        $api_token = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/followupboss" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/followupboss.png" height="50" alt="Followupboss Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
            ?>
            <br />
            <?php 

$form_fields = '';
$app_name= 'followupboss';
$followupboss_form = new AWP_Form_Fields($app_name);

$form_fields = $followupboss_form->awp_wp_text_input(
    array(
        'id'            => "awp_followupboss_display_name",
        'name'          => "awp_followupboss_display_name",
        'value'         => $display_name,
        'placeholder'   =>  esc_html__('Enter Followupboss X-System Name', 'automate_hub' ),
        'label'         =>  esc_html__('Followupboss X-System Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $followupboss_form->awp_wp_text_input(
    array(
        'id'            => "awp_followupboss_X_System_key",
        'name'          => "awp_followupboss_X_System_key",
        'value'         => $X_System_key,
        'placeholder'   => esc_html__( 'Enter Followupboss X-System Key', 'automate_hub' ),
        'label'         =>  esc_html__( 'Followupboss X-System key', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);
$form_fields .= $followupboss_form->awp_wp_text_input(
    array(
        'id'            => "awp_followupboss_api_token",
        'name'          => "awp_followupboss_api_token",
        'value'         => $api_token,
        'placeholder'   => esc_html__( 'Enter your Followupboss API Key', 'automate_hub' ),
        'label'         =>  esc_html__( 'Followupboss API Key', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $followupboss_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_followupboss_save_api_token',
    )
);


$form_fields .= $followupboss_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $followupboss_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$followupboss_form->render($form_fields);

?>
        </div>
        <div class="wrap">
                <form id="form-list" method="post">
                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                        $data=[
                            'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                        ];
                        $platform_obj = new AWP_Platform_Shell_Table('followupboss');
                        $platform_obj->initiate_table($data);
                        $platform_obj->prepare_items();
                        $platform_obj->display_table();
                    ?>
                </form>
        </div>
        
        <?php
    }

    public function awp_save_followupboss_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_followupboss_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_token = sanitize_text_field($_POST["awp_followupboss_api_token"]);
        $display_name     = sanitize_text_field( $_POST["awp_followupboss_display_name"] );
        $X_System_key     = sanitize_text_field( $_POST["awp_followupboss_X_System_key"] );

        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('followupboss');
        $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_token, 'client_id'=> $X_System_key]);

        AWP_redirect("admin.php?page=automate_hub&tab=followupboss");
    } 
    
    public function load_custom_script() {
        wp_enqueue_script( 'awp-followupboss-script', AWP_URL . '/apps/f/followupboss/followupboss.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="followupboss-action-template">
                <?php

                    $app_data=array(
                          'app_slug'=>'followupboss',
                          'app_name'=>'Followupboss',
                          'app_icon_url'=>AWP_ASSETS.'/images/icons/followupboss.png',
                          'app_icon_alter_text'=>'Followupboss  Icon',
                          'account_select_onchange'=>'getstage',
                          'tasks'=>array(
                                'createpeople'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Stage',
                                            'type'=>'select',
                                            'name'=>"stageid",
                                            'required' => 'required',
                                            'onchange' => 'selectedstage',
                                            'option_for_loop'=>'(item) in data.stagelist',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Stage...',
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

    
    public function awp_fetch_stage() {
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
        $platform_obj= new AWP_Platform_Shell_Table('followupboss');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);

        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;

        $url = "https://api.followupboss.com/v1/stages";
       
        $args = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($api_key . ':'),
                'X-System' => $data->account_name,
                'X-System-Key' => $data->client_id,
            ]
        ];

        $response = wp_remote_get( $url, $args );
        $body = wp_remote_retrieve_body( $response );
        $body = json_decode( $body, true );
    
        wp_send_json_success($body["stages"]);
    }

   
};


$Awp_Followupboss = new AWP_Followupboss();

function awp_followupboss_save_integration() {
    Appfactory::save_integration();
}

function awp_followupboss_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('followupboss');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $task = $decoded_data["task"]; 

   

    if( $task == "createpeople" ) {

        $firstName = empty( $data["firstName"] ) ? "" : awp_get_parsed_values($data["firstName"], $posted_data);
        $lastName = empty( $data["lastName"] ) ? "" : awp_get_parsed_values($data["lastName"], $posted_data);
        $email = empty( $data["email"] ) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $phone = empty( $data["phone"] ) ? "" : awp_get_parsed_values($data["phone"], $posted_data);
        $address = empty( $data["address"] ) ? "" : awp_get_parsed_values($data["phone"], $posted_data);
        $stage = empty( $data["stageid"] ) ? "" : awp_get_parsed_values($data["stageid"], $posted_data);
       

        $token = 'Bearer '.$api_key;

        $body = json_encode([
            "firstName"=>$firstName,
            "lastName"=>$lastName,
            "emails"=>array($email),
            "addresses"=>array($address),
            "phones"=>array($phone),
            "stage"=> $stage
        ]);

        $url = "https://api.followupboss.com/v1/people";

        $args = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($api_key . ':'),
                'X-System' => $data->account_name,
                'X-System-Key' => $data->client_id,
            ],

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } 
    return $response;
}


function awp_followupboss_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('followupboss');
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
                'Authorization' => 'Basic ' . base64_encode($api_key . ':'),
                'X-System' => $data->account_name,
                'X-System-Key' => $data->client_id
            ),

            'body'=> json_encode($body)
        ];


        $return  = wp_remote_post($campfireUrl,  $args );

        $args['headers']['Authorization']="Bearer XXXXXXXXXXXX";
        awp_add_to_log( $return, $campfireUrl, $args, $integration );

    $response['success']=true;    
    return $response;
}
