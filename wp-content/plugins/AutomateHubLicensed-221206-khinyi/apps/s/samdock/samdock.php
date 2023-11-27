<?php

class AWP_Samdock extends appfactory
{

   
    public function init_actions(){
        add_action('admin_post_awp_samdock_save_api_token', [$this, 'awp_save_samdock_api_token'], 10, 0);
        add_action( 'wp_ajax_awp_fetch_organization_list', [$this, 'awp_fetch_organization_list']);
    }

    public function init_filters(){

        add_filter('awp_platforms_connections', [$this, 'awp_samdock_platform_connection'], 10, 1);
    }

    public function action_provider( $providers ) {
        $providers['samdock'] = array(
            'title' => esc_html__( 'Samdock', 'automate_hub' ),
            'tasks' => array(
                'createcontact'   => esc_html__( 'Create Contact', 'automate_hub' )
            )
        );
    
        return  $providers;
    }

    public function settings_tab( $tabs ) {
        $tabs['samdock'] = array('name'=>esc_html__( 'Samdock', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'samdock') {
            return;
        }
        $nonce = wp_create_nonce("awp_samdock_settings");
        $username = isset($_GET['api_key']) ? sanitize_text_field($_GET['api_key']) : "";
        $id = isset($_GET['id']) ? intval( sanitize_text_field($_GET['id'] )) : "";
        $display_name     = isset($_GET['account_name']) ? sanitize_text_field($_GET['account_name']) : "";
        $password     = isset($_GET['awp_samdock_password']) ? sanitize_text_field($_GET['awp_samdock_password']) : "";
        ?>
        <div class="platformheader">

            <a href="https://sperse.io/go/samdock" target="_blank"><img src="<?php echo esc_url(AWP_ASSETS_REMOTE.'/images/logos/samdock.jpg'); ?>"  height="50" alt="Samdock Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager(sanitize_text_field($_GET['tab']));
                    $instruction_obj->prepare_instructions();
                        
                ?>

                <br />

                <?php 

                $form_fields = '';
                $app_name= 'samdock';
                $samdock_form = new AWP_Form_Fields($app_name);

                $form_fields = $samdock_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_samdock_display_name",
                        'name'          => "awp_samdock_display_name",
                        'value'         => $display_name,
                        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
                        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $samdock_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_samdock_username",
                        'name'          => "awp_samdock_username",
                        'value'         => $username,
                        'placeholder'   => esc_html__( 'Enter your Samdock Account Email', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Samdock Account Email', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $samdock_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_samdock_password",
                        'name'          => "awp_samdock_password",
                        'value'         => $password,
                        'placeholder'   => esc_html__( 'Enter your Samdock Password', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Samdock Account Password', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $samdock_form->awp_wp_hidden_input(
                    array(
                        'name'          => "action",
                        'value'         => 'awp_samdock_save_api_token',
                    )
                );


                $form_fields .= $samdock_form->awp_wp_hidden_input(
                    array(
                        'name'          => "_nonce",
                        'value'         =>$nonce,
                    )
                );
                $form_fields .= $samdock_form->awp_wp_hidden_input(
                    array(
                        'name'          => "id",
                        'value'         =>wp_unslash($id),
                    )
                );


                $samdock_form->render($form_fields);

                ?>


        </div>

        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                 $data=[
                    'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('samdock');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();

                ?>
                        </form>
                </div>
        <?php
    }

    public function awp_save_samdock_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_samdock_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $username = sanitize_text_field($_POST["awp_samdock_username"]);
        $password = sanitize_text_field($_POST["awp_samdock_password"]);
        $display_name     = sanitize_text_field( $_POST["awp_samdock_display_name"] );

        $api = $this->awp_get_access_token($username, $password);

        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('samdock');
        $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => isset($api['accessToken']) ? $api['accessToken'] : $api['message'], 'client_id' => isset($api['refreshToken']) ? $api['refreshToken'] : '' , 'email' => $username, 'client_secret' => $password]);

        AWP_redirect("admin.php?page=automate_hub&tab=samdock");
    } 
    
    public function load_custom_script() {
        wp_enqueue_script( 'awp-samdock-script', AWP_URL . '/apps/s/samdock/samdock.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="samdock-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'samdock',
                           'app_name'=>'Samdock ',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/samdock.png',
                           'app_icon_alter_text'=>'Samdock  Icon',
                           'account_select_onchange'=>'getorganizationdetail',
                           'tasks'=>array(
                                'createcontact'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Organization',
                                            'type'=>'select',
                                            'name'=>"organizationid",
                                            'required' => 'required',
                                            'onchange' => 'selectedoganization',
                                            'option_for_loop'=>'(item) in data.oganizationList',
                                            'option_for_value'=>'item._tenantID +"+"+ item._id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Organization...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': oganizationLoading}",
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

    
    public function awp_get_access_token($email, $password) {

          
        $url = "https://auth.samdock.app/login";

        $data = "email=".$email."&password=".$password;

        $args = array(
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded'
            ),
            'body' => $data
        );

        $response = wp_remote_post( $url, $args );
        
        $retrievebody = wp_remote_retrieve_body( $response);

        $body = json_decode( $retrievebody, true );

        return $body;
    }

    public function awp_refresh_access_token($old_access_token, $old_refresh_token) {
          
        $url = "https://auth.samdock.app/login";

        $data = "accessToken=".$old_access_token."&refreshToken=".$old_refresh_token;

        $args = array(
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded'
            ),
            'body' => $data
        );

        $response = wp_remote_post( $url, $args );
        
        $retrievebody = wp_remote_retrieve_body( $response);

        $body = json_decode( $retrievebody, true );

        return $body;
    }

    public function awp_fetch_organization_list(){

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

        $platform_obj= new AWP_Platform_Shell_Table('samdock');

        $data=$platform_obj->awp_get_platform_detail_by_id($id);

        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_data = $this->awp_get_access_token($data->email, $data->client_secret);

        $api_key = isset($api_data['accessToken']) ? $api_data['accessToken'] : '';

        $url = "https://samdock.app/api/contacts/Organizations";
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$api_key
            )
        );

        $response = wp_remote_get( $url, $args );
        $body = wp_remote_retrieve_body( $response );
        $body = json_decode( $body, true );

        wp_send_json_success($body);
    }

};


$Awp_Samdock = new AWP_Samdock();

function awp_samdock_save_integration() {
    Appfactory::save_integration();
}



function awp_samdock_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('samdock');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $task = $decoded_data["task"]; 

    

    if( $task == "createcontact" ) {

        $street = empty( $data["street"] ) ? "" : awp_get_parsed_values($data["street"], $posted_data);
        $number = empty( $data["number"] ) ? "" : awp_get_parsed_values($data["number"], $posted_data);
        $postcode = empty( $data["postcode"] ) ? "" : awp_get_parsed_values($data["postcode"], $posted_data);
        $city = empty( $data["city"] ) ? "" : awp_get_parsed_values($data["city"], $posted_data);
        $country = empty( $data["country"] ) ? "" : awp_get_parsed_values($data["country"], $posted_data);
        $_tenantID = empty( $data["_tenantID"] ) ? "" : awp_get_parsed_values($data["_tenantID"], $posted_data);
        $_id = empty( $data["_id"] ) ? "" : awp_get_parsed_values($data["_id"], $posted_data);
        $email = empty( $data["email"] ) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $firstName = empty( $data["firstName"] ) ? "" : awp_get_parsed_values($data["firstName"], $posted_data);
        $lastName = empty( $data["lastName"] ) ? "" : awp_get_parsed_values($data["lastName"], $posted_data);
        $phoneNumber = empty( $data["phoneNumber"] ) ? "" : awp_get_parsed_values($data["phoneNumber"], $posted_data);
        $gender = empty( $data["gender"] ) ? "" : awp_get_parsed_values($data["gender"], $posted_data);

        $api_data = (new AWP_Samdock())->awp_get_access_token($temp->email, $temp->client_secret);

        $api_key = isset($api_data['accessToken']) ? $api_data['accessToken'] : "";

        $token = 'Bearer '.$api_key;

        $body = json_encode([
            "address" => [
                "street" => $street,
                "number" => $number,
                "postcode" => $postcode,
                "city" => $city,
                "country" => $country
            ],
            "_tenantID" => $_tenantID,
            "_id" => $_id,
            "email" => $email,
            "firstName" => $firstName,
            "lastName" => $lastName,
            "phoneNumber" => $phoneNumber,
            "gender" => $gender,
            "namePrefix" => substr($firstName, 0, 1),
            "nameSuffix" => substr($firstName, -1)
        ]);

        $url = "https://samdock.app/api/contacts/Persons";

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


function awp_samdock_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('samdock');
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
