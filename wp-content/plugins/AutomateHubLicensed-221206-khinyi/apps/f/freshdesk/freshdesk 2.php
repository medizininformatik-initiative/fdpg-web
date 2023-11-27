<?php

class AWP_Freshdesk extends appfactory
{

   
    public function init_actions(){
        add_action('admin_post_awp_freshdesk_save_api_token', [$this, 'awp_save_freshdesk_api_token'], 10, 0);
    }

    public function init_filters(){

        add_filter('awp_platforms_connections', [$this, 'awp_freshdesk_platform_connection'], 10, 1);
    }

    public function action_provider( $providers ) {
        $providers['freshdesk'] = array(
            'title' => esc_html__( 'Freshdesk', 'automate_hub' ),
            'tasks' => array(
                'createcontact'   => esc_html__( 'Create Contact', 'automate_hub' )
            )
        );
    
        return  $providers;
    }

    public function settings_tab( $tabs ) {
        $tabs['freshdesk'] = array('name'=>esc_html__( 'Freshdesk', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'freshdesk') {
            return;
        }
        $nonce = wp_create_nonce("awp_freshdesk_settings");
        $api_token = isset($_GET['api_key']) ? sanitize_text_field($_GET['api_key']) : "";
        $id = isset($_GET['id']) ? intval( sanitize_text_field($_GET['id'] )) : "";
        $display_name     = isset($_GET['account_name']) ? sanitize_text_field($_GET['account_name']) : "";
        $api_url     = isset($_GET['awp_freshdesk_api_url']) ? sanitize_text_field($_GET['awp_freshdesk_api_url']) : "";
        ?>
        <div class="platformheader">

            <a href="https://sperse.io/go/freshdesk" target="_blank"><img src="<?php echo esc_url(AWP_ASSETS_REMOTE.'/images/logos/freshdesk.png'); ?>" height="50" alt="Freshdesk Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager(sanitize_text_field($_GET['tab']));
                    $instruction_obj->prepare_instructions();
                        
                ?>

                <br />

                <?php 

                $form_fields = '';
                $app_name= 'freshdesk';
                $freshdesk_form = new AWP_Form_Fields($app_name);

                $form_fields = $freshdesk_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_freshdesk_display_name",
                        'name'          => "awp_freshdesk_display_name",
                        'value'         => $display_name,
                        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
                        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $freshdesk_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_freshdesk_api_token",
                        'name'          => "awp_freshdesk_api_token",
                        'value'         => $api_token,
                        'placeholder'   => esc_html__( 'Enter your Freshdesk Live API Key', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Freshdesk API Key', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $freshdesk_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_freshdesk_api_url",
                        'name'          => "awp_freshdesk_api_url",
                        'value'         => $api_url,
                        'placeholder'   => esc_html__( 'https://abc.freshdesk.com', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Enter your Freshdesk APP Url', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $freshdesk_form->awp_wp_hidden_input(
                    array(
                        'name'          => "action",
                        'value'         => 'awp_freshdesk_save_api_token',
                    )
                );


                $form_fields .= $freshdesk_form->awp_wp_hidden_input(
                    array(
                        'name'          => "_nonce",
                        'value'         =>$nonce,
                    )
                );
                $form_fields .= $freshdesk_form->awp_wp_hidden_input(
                    array(
                        'name'          => "id",
                        'value'         =>wp_unslash($id),
                    )
                );


                $freshdesk_form->render($form_fields);

                ?>


        </div>

        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                 $data=[
                    'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('freshdesk');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();

                ?>
                        </form>
                </div>
        <?php
    }

    public function awp_save_freshdesk_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_freshdesk_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_token = sanitize_text_field($_POST["awp_freshdesk_api_token"]);
        $display_name     = sanitize_text_field( $_POST["awp_freshdesk_display_name"] );
        $api_url     = sanitize_text_field( $_POST["awp_freshdesk_api_url"] );

        if ($api_url != "") {
            
            $url_regexPatterna = '/^https:\/\/.[a-zA-Z0-9][a-zA-Z0-9\-]*\.freshdesk\.com\z/';
            $url_regexPatternb = '/^https:\/\/.[a-zA-Z0-9][a-zA-Z0-9\-]*\.freshdesk\.com\/\z/';
            

            if (preg_match($url_regexPatterna, $api_url)) {
                // Save tokens
                $platform_obj = new AWP_Platform_Shell_Table('freshdesk');
                $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_token, 'url' => $api_url]);

                AWP_redirect("admin.php?page=automate_hub&tab=freshdesk");

            } else if(preg_match($url_regexPatternb, $api_url)){
                $res = explode('/', $api_url);
                $new_url = $res[0]."//".$res['2'];

                // Save tokens
                $platform_obj = new AWP_Platform_Shell_Table('freshdesk');
                $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_token, 'url' => $new_url]);

                AWP_redirect("admin.php?page=automate_hub&tab=freshdesk");


            } else {
                wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=freshdesk'));
                exit();
            }
        } else {
            wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=freshdesk'));
            exit();
        }

    } 
    
    public function load_custom_script() {
        wp_enqueue_script( 'awp-freshdesk-script', AWP_URL . '/apps/f/freshdesk/freshdesk.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="freshdesk-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'freshdesk',
                           'app_name'=>'Freshdesk ',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/freshdesk.png',
                           'app_icon_alter_text'=>'Freshdesk  Icon',
                           'account_select_onchange'=>'',
                           'tasks'=>array(),
                    ); 

                        require (AWP_VIEWS.'/awp_app_integration_format.php');
                ?>
            </script>
        <?php
    }

};


$Awp_Freshdesk = new AWP_Freshdesk();

function awp_freshdesk_save_integration() {
    Appfactory::save_integration();
}

function awp_freshdesk_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('freshdesk');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $url=$temp->url;
    $task = $decoded_data["task"]; 

    if( $task == "createcontact" ) {

        $name = empty( $data["name"] ) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $email = empty( $data["email"] ) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $phone = empty( $data["phone"] ) ? "" : awp_get_parsed_values($data["phone"], $posted_data);
        $address = empty( $data["address"] ) ? "" : awp_get_parsed_values($data["address"], $posted_data);
       

        $body = json_encode([
            "name" => $name,
            "language" => "en",
            "address" => $address,
            "email" => $email,
            "phone" => $phone
            
        ]);

        $url = $url."/api/v2/contacts";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => base64_encode($api_key)
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } 
    return $response;
}


function awp_freshdesk_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('freshdesk');
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

        $token = base64_encode($api_key);

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $token
            ),

            'body'=> json_encode($body)
        ];


        $return  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="XXXXXXXXXXXX";
        awp_add_to_log( $return, $url, $args, $integration );

    $response['success']=true;    
    return $response;
}
