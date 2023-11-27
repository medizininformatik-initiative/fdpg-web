<?php

class AWP_Teamworkcrm extends appfactory
{

   
    public function init_actions(){
       
        add_action('admin_post_awp_teamworkcrm_save_api_token', [$this, 'awp_save_teamworkcrm_api_token'], 10, 0);
        
        add_action( 'wp_ajax_awp_fetch_source', [$this, 'awp_fetch_source']);
        
    }

    public function init_filters(){

        add_filter('awp_platforms_connections', [$this, 'awp_teamworkcrm_platform_connection'], 10, 1);
    }

    public function action_provider( $providers ) {
        $providers['teamworkcrm'] = array(
            'title' => esc_html__( 'Teamwork CRM', 'automate_hub' ),
            'tasks' => array(
                'createcontact'   => esc_html__( 'Create Contact', 'automate_hub' ),
            )
        );
    
        return  $providers;
    }

    public function settings_tab( $tabs ) {
        $tabs['teamworkcrm'] = array('name'=>esc_html__( 'Teamworkcrm', 'automate_hub'), 'cat'=>array('crm'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'teamworkcrm') {
            return;
        }
        $nonce = wp_create_nonce("awp_teamworkcrm_settings");
        $api_token = isset($_GET['api_key']) ? sanitize_text_field($_GET['api_key']) : "";
        $id = isset($_GET['id']) ? intval( sanitize_text_field($_GET['id'] )) : "";
        $display_name     = isset($_GET['account_name']) ? sanitize_text_field($_GET['account_name']) : "";
        $url     = isset($_GET['awp_teamworkcrm_domain_url']) ? sanitize_text_field($_GET['awp_teamworkcrm_domain_url']) : "";
        ?>
        <div class="platformheader">

            <a href="https://sperse.io/go/teamworkcrm" target="_blank"><img src="<?php echo esc_url(AWP_ASSETS_REMOTE.'/images/logos/teamworkcrm.png'); ?>" height="50" alt="Teamworkcrm Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager(sanitize_text_field($_GET['tab']));
                    $instruction_obj->prepare_instructions();
                        
                ?>

                <br />

                <?php 

                $form_fields = '';
                $app_name= 'teamworkcrm';
                $teamworkcrm_form = new AWP_Form_Fields($app_name);

                $form_fields = $teamworkcrm_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_teamworkcrm_display_name",
                        'name'          => "awp_teamworkcrm_display_name",
                        'value'         => $display_name,
                        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
                        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $teamworkcrm_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_teamworkcrm_api_token",
                        'name'          => "awp_teamworkcrm_api_token",
                        'value'         => $api_token,
                        'placeholder'   => esc_html__( 'Enter your Teamworkcrm API Key', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Teamworkcrm API Key', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $teamworkcrm_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_teamworkcrm_domain_url",
                        'name'          => "awp_teamworkcrm_domain_url",
                        'value'         => $url,
                        'placeholder'   => esc_html__( 'https://abc.teamworkcrm.com', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Teamworkcrm Your Teamworkcrm Domain URL', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $teamworkcrm_form->awp_wp_hidden_input(
                    array(
                        'name'          => "action",
                        'value'         => 'awp_teamworkcrm_save_api_token',
                    )
                );


                $form_fields .= $teamworkcrm_form->awp_wp_hidden_input(
                    array(
                        'name'          => "_nonce",
                        'value'         =>$nonce,
                    )
                );
                $form_fields .= $teamworkcrm_form->awp_wp_hidden_input(
                    array(
                        'name'          => "id",
                        'value'         =>wp_unslash($id),
                    )
                );


                $teamworkcrm_form->render($form_fields);

                ?>


        </div>

        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                 $data=[
                    'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('teamworkcrm');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();

                ?>
                        </form>
                </div>
        <?php
    }

    public function awp_save_teamworkcrm_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_teamworkcrm_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_token = sanitize_text_field($_POST["awp_teamworkcrm_api_token"]);
        $display_name     = sanitize_text_field( $_POST["awp_teamworkcrm_display_name"] );
        $url     = sanitize_text_field( $_POST["awp_teamworkcrm_domain_url"] );

        if ($url != "") {
            
            $url_regexPatterna = '/^https:\/\/.[a-zA-Z0-9][a-zA-Z0-9\-]*\.teamwork\.com\z/';
            $url_regexPatternb = '/^https:\/\/.[a-zA-Z0-9][a-zA-Z0-9\-]*\.teamwork\.com\/\z/';
            if (preg_match($url_regexPatterna, $url)) {
                // Save tokens
                $platform_obj = new AWP_Platform_Shell_Table('teamworkcrm');
                $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_token, 'url' => $url ]);

                AWP_redirect("admin.php?page=automate_hub&tab=teamworkcrm");
            } else if(preg_match($url_regexPatternb, $url)){

                $res = explode('/', $url);
                $new_url = $res[0]."//".$res['2'];

                // Save tokens
                $platform_obj = new AWP_Platform_Shell_Table('teamworkcrm');
                $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_token, 'url' => $new_url ]);

                AWP_redirect("admin.php?page=automate_hub&tab=teamworkcrm");
            }else {
                wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=teamworkcrm'));
                exit();
            }
        } else {
            wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=teamworkcrm'));
            exit();
        }

    } 
    
    public function load_custom_script() {
        wp_enqueue_script( 'awp-teamworkcrm-script', AWP_URL . '/apps/t/teamworkcrm/teamworkcrm.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="teamworkcrm-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'teamworkcrm',
                           'app_name'=>'Teamworkcrm ',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/teamworkcrm.png',
                           'app_icon_alter_text'=>'Teamworkcrm  Icon',
                           'account_select_onchange'=>'getsourcesid',
                           'tasks'=>array(
                                'createlcustomer'=>array(
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
        $platform_obj= new AWP_Platform_Shell_Table('teamworkcrm');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;

        $url = "https://api.teamworkcrm.com/v1/sources";
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


$Awp_Teamworkcrm = new AWP_Teamworkcrm();

function awp_teamworkcrm_save_integration() {
    Appfactory::save_integration();
}

function awp_teamworkcrm_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('teamworkcrm');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    
    
    $api_key= $temp->api_key;
    $url= $temp->url;

    $task = $decoded_data["task"]; 
    
    $token = 'Bearer '.$api_key;

    if($task == "createcontact"){

        $address = empty( $data["address"] ) ? "" : awp_get_parsed_values($data["address"], $posted_data);
        $city = empty( $data["city"] ) ? "" : awp_get_parsed_values($data["city"], $posted_data);
        $email = empty( $data["email"] ) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $firstname = empty( $data["firstname"] ) ? "" : awp_get_parsed_values($data["firstname"], $posted_data);
        $phonenumber = empty( $data["phonenumber"] ) ? "" : awp_get_parsed_values($data["phonenumber"], $posted_data);
        $country = empty( $data["country"] ) ? "" : awp_get_parsed_values($data["country"], $posted_data);
        $title = empty( $data["title"] ) ? "" : awp_get_parsed_values($data["title"], $posted_data);
        $zipcode = empty( $data["zipcode"] ) ? "" : awp_get_parsed_values($data["zipcode"], $posted_data);

        $body = json_encode([
            "contact" => [
                "addressLine1" => $address,
                "city" => $city,
                "emailAddresses" => [
                  [
                    "address" => $email,
                    "isMain" => (boolean) true
                  ]
                ],
                "firstName" => $firstname,
                "lastName" => $lastname,
                "phoneNumbers" => [
                    [
                        "isMain" => (boolean) true,
                        "number" => $phonenumber
                    ]
                ],
                "stateOrCounty" => $country,
                "title" => $title,
                "zipcode" => $zipcode
            ]
        ]);

        $url = $url."/crm/api/v2/contacts.json";

        $args = [
            'headers' => array(
                "Content-Type" => "application/json",
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


function awp_teamworkcrm_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('teamworkcrm');
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