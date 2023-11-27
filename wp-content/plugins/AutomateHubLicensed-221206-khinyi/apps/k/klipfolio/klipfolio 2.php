<?php

class AWP_Klipfolio extends appfactory
{

   
    public function init_actions(){
        add_action('admin_post_awp_klipfolio_save_api_token', [$this, 'awp_save_klipfolio_api_token'], 10, 0);        
    }

    public function init_filters(){

        add_filter('awp_platforms_connections', [$this, 'awp_klipfolio_platform_connection'], 10, 1);
    }

    public function action_provider( $providers ) {
        $providers['klipfolio'] = array(
            'title' => esc_html__( 'Klipfolio', 'automate_hub' ),
            'tasks' => array(
                'createclient'   => esc_html__( 'Create Client', 'automate_hub' ),
                'createklip'   => esc_html__( 'Create Klips', 'automate_hub' ),
                'createuser'   => esc_html__( 'Create User', 'automate_hub' )
            )
        );
    
        return  $providers;
    }

    public function settings_tab( $tabs ) {
        $tabs['klipfolio'] = array('name'=>esc_html__( 'Klipfolio', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'klipfolio') {
            return;
        }
        $nonce = wp_create_nonce("awp_klipfolio_settings");
        $api_token = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/klipfolio" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/klipfolio.png" height="50" alt="Klipfolio Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?>

                <br />
                <?php 

$form_fields = '';
$app_name= 'klipfolio';
$klipfolio_form = new AWP_Form_Fields($app_name);

$form_fields = $klipfolio_form->awp_wp_text_input(
    array(
        'id'            => "awp_klipfolio_display_name",
        'name'          => "awp_klipfolio_display_name",
        'value'         => $display_name,
        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $klipfolio_form->awp_wp_text_input(
    array(
        'id'            => "awp_klipfolio_api_token",
        'name'          => "awp_klipfolio_api_token",
        'value'         => $api_token,
        'placeholder'   => esc_html__( 'Enter your Klipfolio Live API Key', 'automate_hub' ),
        'label'         =>  esc_html__( 'Klipfolio API Key', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $klipfolio_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_klipfolio_save_api_token',
    )
);


$form_fields .= $klipfolio_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $klipfolio_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$klipfolio_form->render($form_fields);

?>

        </div>

        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                 $data=[
                    'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('klipfolio');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();

                ?>
                        </form>
                </div>
        <?php
    }

    public function awp_save_klipfolio_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_klipfolio_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_token = sanitize_text_field($_POST["awp_klipfolio_api_token"]);
        $display_name     = sanitize_text_field( $_POST["awp_klipfolio_display_name"] );

        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('klipfolio');
        $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_token]);

        AWP_redirect("admin.php?page=automate_hub&tab=klipfolio");
    } 
    
    public function load_custom_script() {
        wp_enqueue_script( 'awp-klipfolio-script', AWP_URL . '/apps/k/klipfolio/klipfolio.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="klipfolio-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'klipfolio',
                           'app_name'=>'Klipfolio ',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/klipfolio.png',
                           'app_icon_alter_text'=>'Klipfolio  Icon',
                           'account_select_onchange'=>'getstatus',
                           'tasks'=>array(
                                'createclient'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Status',
                                            'type'=>'select',
                                            'name'=>"status",
                                            'required' => 'required',
                                            'onchange' => 'selectedstatus',
                                            'option_for_loop'=>'(item) in data.statuslist',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Status...'
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


$Awp_Klipfolio = new AWP_Klipfolio();

function awp_klipfolio_save_integration() {
    Appfactory::save_integration();
}

function awp_klipfolio_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('klipfolio');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $task = $decoded_data["task"]; 

   

    if( $task == "createclient" ) {

        $name = empty( $data["name"] ) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $description = empty( $data["description"] ) ? "" : awp_get_parsed_values($data["description"], $posted_data);
        $status = empty( $data["status"] ) ? "" : awp_get_parsed_values($data["status"], $posted_data);
        $external_id = "CID".mt_rand(000210, 999999);

        $body = json_encode([
            "name"=>$name,
            "description"=>$description,
            "external_id"=>$external_id,
            "status"=>$status
        ]);

        $url = "https://app.klipfolio.com/api/1.0/clients";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'kf-api-key' => $api_key
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['kf-api-key']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } else if( $task == "createklip" ) {

        $kname = empty( $data["kname"] ) ? "" : awp_get_parsed_values($data["kname"], $posted_data);
        $kdescription = empty( $data["kdescription"] ) ? "" : awp_get_parsed_values($data["kdescription"], $posted_data);
       

        $body = json_encode([
            "name"=>$kname,
            "description"=>$kdescription
        ]);

        $url = "https://app.klipfolio.com/api/1.0/klips";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'kf-api-key' => $api_key
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['kf-api-key']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } else if( $task == "createuser" ) {

        $firstname = empty( $data["first_name"] ) ? "" : awp_get_parsed_values($data["first_name"], $posted_data);
        $lastname = empty( $data["last_name"] ) ? "" : awp_get_parsed_values($data["last_name"], $posted_data);
        $email = empty( $data["email"] ) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $password = empty( $data["password"] ) ? "" : awp_get_parsed_values($data["password"], $posted_data);
        $external_id = "UID".mt_rand(000210, 999999);

        $body = json_encode([
            "first_name"=>$firstname,
            "last_name"=>$lastname,
            "email"=>$email,
            "password"=>$password,
            "external_id"=>$external_id,
            "send_email"=>true
        ]);

        $url = "https://app.klipfolio.com/api/1/users";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'kf-api-key' => $api_key
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['kf-api-key']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } 
    return $response;
}


function awp_klipfolio_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('klipfolio');
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

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'kf-api-key' => $api_key
            ),

            'body'=> json_encode($body)
        ];


        $return  = wp_remote_post($url,  $args );

        $args['headers']['kf-api-key']="Bearer XXXXXXXXXXXX";
        awp_add_to_log( $return, $url, $args, $integration );

    $response['success']=true;    
    return $response;
}
