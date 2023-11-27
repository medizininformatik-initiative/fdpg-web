<?php
class AWP_Fivetran extends appfactory
{
    public function init_actions(){
        add_action('admin_post_awp_fivetran_save_api_token', [$this, 'awp_save_fivetran_api_token'], 10, 0);
        add_action( 'wp_ajax_awp_fetch_role', [$this, 'awp_fetch_role']);
    }

    public function init_filters(){
        add_filter('awp_platforms_connections', [$this, 'awp_fivetran_platform_connection'], 10, 1);
    }

    public function action_provider( $providers ) {
        $providers['fivetran'] = array(
            'title' => esc_html__( 'Fivetran', 'automate_hub' ),
            'tasks' => array(
                'createuser'   => esc_html__( 'Invite User', 'automate_hub' ),
                'createteam'   => esc_html__( 'Create Team', 'automate_hub' ),
                'creategroup'  => esc_html__( 'Create Group', 'automate_hub' ),
            )
        );
        return  $providers;
    }

    public function settings_tab( $tabs ) {
        $tabs['fivetran'] = array('name'=>esc_html__( 'Fivetran', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'fivetran') {
            return;
        }
        $nonce = wp_create_nonce("awp_fivetran_settings");
        $api_token = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/fivetran" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/fivetran.png" width="180" height="50" alt="Fivetran Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?>

                <br />
                <?php 

$form_fields = '';
$app_name= 'fivetran';
$fivetran_form = new AWP_Form_Fields($app_name);

$form_fields = $fivetran_form->awp_wp_text_input(
    array(
        'id'            => "awp_fivetran_display_name",
        'name'          => "awp_fivetran_display_name",
        'value'         => $display_name,
        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $fivetran_form->awp_wp_text_input(
    array(
        'id'            => "awp_fivetran_api_token",
        'name'          => "awp_fivetran_api_token",
        'value'         => $api_token,
        'placeholder'   => esc_html__( 'Enter your Fivetran API Key', 'automate_hub' ),
        'label'         =>  esc_html__( 'Fivetran API Key', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $fivetran_form->awp_wp_text_input(
    array(
        'id'            => "awp_fivetran_api_secret",
        'name'          => "awp_fivetran_api_secret",
        'value'         => $client_secret,
        'placeholder'   => esc_html__( 'Enter your Fivetran API Secret', 'automate_hub' ),
        'label'         =>  esc_html__( 'Fivetran API Secret', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $fivetran_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_fivetran_save_api_token',
    )
);


$form_fields .= $fivetran_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $fivetran_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$fivetran_form->render($form_fields);

?>
        </div>

        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                 $data=[
                    'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('fivetran');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();

                ?>
                        </form>
                </div>
        <?php
    }

    public function awp_save_fivetran_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_fivetran_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_token = sanitize_text_field($_POST["awp_fivetran_api_token"]);
        $display_name     = sanitize_text_field( $_POST["awp_fivetran_display_name"] );
        $api_secret     = sanitize_text_field( $_POST["awp_fivetran_api_secret"] );

        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('fivetran');
        $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_token, 'client_secret' => $api_secret]);

        AWP_redirect("admin.php?page=automate_hub&tab=fivetran");
    } 
    
    public function load_custom_script() {
        wp_enqueue_script( 'awp-fivetran-script', AWP_URL . '/apps/f/fivetran/fivetran.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="fivetran-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'fivetran',
                           'app_name'=>'Fivetran ',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/fivetran.png',
                           'app_icon_alter_text'=>'Fivetran  Icon',
                           'account_select_onchange'=>'getrole',
                           'tasks'=>array(
                                'createuser'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Role',
                                            'type'=>'select',
                                            'name'=>"source_id",
                                            'required' => 'required',
                                            'onchange' => 'selectedrole',
                                            'option_for_loop'=>'(item) in data.rolelist',
                                            'option_for_value'=>'item.name',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Role...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': roleLoading}",
                                                        )
                                        )
                                    ),
                                ),
                                'createteam'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Role',
                                            'type'=>'select',
                                            'name'=>"source_id",
                                            'required' => 'required',
                                            'onchange' => 'selectedrole',
                                            'option_for_loop'=>'(item) in data.rolelist',
                                            'option_for_value'=>'item.name',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Role...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': roleLoading}",
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

    
    public function awp_fetch_role() {
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
        $platform_obj= new AWP_Platform_Shell_Table('fivetran');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;
        $api_secret =$data->client_secret;


        $url = "https://api.fivetran.com/v1/roles";
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic '.base64_encode($api_key.":".$api_secret)
            )
        );

        $response = wp_remote_get( $url, $args );
        $body = wp_remote_retrieve_body( $response );
        $body = json_decode( $body, true );
        wp_send_json_success($body["data"]["items"]);
    }
 
};


$Awp_Fivetran = new AWP_Fivetran();

function awp_fivetran_save_integration() {
    Appfactory::save_integration();
}

function awp_fivetran_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 
    $platform_obj= new AWP_Platform_Shell_Table('fivetran');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $secret_key=$temp->client_secret;
    $task = $decoded_data["task"]; 
    if( $task == "createuser" ) {
        $given_name = empty( $data["given_name"] ) ? "" : awp_get_parsed_values($data["given_name"], $posted_data);
        $family_name = empty( $data["family_name"] ) ? "" : awp_get_parsed_values($data["family_name"], $posted_data);
        $email = empty( $data["email"] ) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $phone = empty( $data["phone"] ) ? "" : awp_get_parsed_values($data["phone"], $posted_data);
        $role_id = empty( $data["role_id"] ) ? "" : awp_get_parsed_values($data["role_id"], $posted_data);
        $token = 'Basic '.base64_encode($api_key.":".$secret_key);
        $body = json_encode([
            "given_name"=>$given_name,
            "family_name"=>$family_name,
            "email"=>$email,
            "phone"=>$phone,
            "role"=>$role_id
        ]);
        $url = "https://api.fivetran.com/v1/users";
        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $token
            ),
            'body'=> $body
        ];
        $response  = wp_remote_post($url,  $args );
        $args['headers']['Authorization']="XXXXXXXXXXXX";
        awp_add_to_log( $response, $url, $args, $record );
    } else if( $task == "createteam" ) {
      $name = empty( $data["name"] ) ? "" : awp_get_parsed_values($data["name"], $posted_data);
      $description = empty( $data["description"] ) ? "" : awp_get_parsed_values($data["description"], $posted_data);
      $role_id = empty( $data["role_id"] ) ? "" : awp_get_parsed_values($data["role_id"], $posted_data);
      $token = 'Basic '.base64_encode($api_key.":".$secret_key);
      $body = json_encode([
          "name"=>$name,
          "description"=>$description,
          "role"=>$role_id
      ]);
      $url = "https://api.fivetran.com/v1/teams";
      $args = [
          'headers' => array(
              'Content-Type' => 'application/json',
              'Authorization' => $token
          ),
          'body'=> $body
      ];
      $response  = wp_remote_post($url,  $args );
      $args['headers']['Authorization']="XXXXXXXXXXXX";
      awp_add_to_log( $response, $url, $args, $record );
    } else if( $task == "creategroup" ) {
      $group_name = empty( $data["group_name"] ) ? "" : awp_get_parsed_values($data["group_name"], $posted_data);
      $token = 'Basic '.base64_encode($api_key.":".$secret_key);
      $body = json_encode([
          "name"=>$group_name
      ]);
      $url = "https://api.fivetran.com/v1/groups";
      $args = [
          'headers' => array(
              'Content-Type' => 'application/json',
              'Authorization' => $token
          ),
          'body'=> $body
      ];
      $response  = wp_remote_post($url,  $args );
      $args['headers']['Authorization']="XXXXXXXXXXXX";
      awp_add_to_log( $response, $url, $args, $record );

    } 
    return $response;
}

function awp_fivetran_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('fivetran');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_key=$temp->api_key;
    $secret_key=$temp->client_secret;
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
        $token = 'Basic '.base64_encode($api_key.":".$secret_key);
        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $token
            ),
            'body'=> json_encode($body)
        ];
        $return  = wp_remote_post($url,  $args );
        $args['headers']['Authorization']="Bearer XXXXXXXXXXXX";
        awp_add_to_log( $return, $url, $args, $integration );
    $response['success']=true;    
    return $response;
}