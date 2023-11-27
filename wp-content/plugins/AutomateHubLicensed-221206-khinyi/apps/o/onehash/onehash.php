<?php

class AWP_Onehash extends appfactory
{

   
    public function init_actions(){
       
        add_action('admin_post_awp_onehash_save_api_token', [$this, 'awp_save_onehash_api_token'], 10, 0);
        
        add_action( 'wp_ajax_awp_fetch_source', [$this, 'awp_fetch_source']);
        
    }

    public function init_filters(){

        add_filter('awp_platforms_connections', [$this, 'awp_onehash_platform_connection'], 10, 1);
    }

    public function action_provider( $providers ) {
        $providers['onehash'] = array(
            'title' => esc_html__( 'Onehash', 'automate_hub' ),
            'tasks' => array(
                'createlead'   => esc_html__( 'Create Lead', 'automate_hub' ),
                'createcustomer'   => esc_html__( 'Create Customer', 'automate_hub' ),
                'createcontact'   => esc_html__( 'Create Contact', 'automate_hub' ),
            )
        );
    
        return  $providers;
    }

    public function settings_tab( $tabs ) {
        $tabs['onehash'] = array('name'=>esc_html__( 'Onehash', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'onehash') {
            return;
        }
        $nonce = wp_create_nonce("awp_onehash_settings");
        $api_token = isset($_GET['api_key']) ? sanitize_text_field($_GET['api_key']) : "";
        $id = isset($_GET['id']) ? intval( sanitize_text_field($_GET['id'] )) : "";
        $display_name     = isset($_GET['account_name']) ? sanitize_text_field($_GET['account_name']) : "";
        $url     = isset($_GET['awp_onehash_domain_url']) ? sanitize_text_field($_GET['awp_onehash_domain_url']) : "";
        ?>
        <div class="platformheader">

            <a href="https://sperse.io/go/onehash" target="_blank"><img src="<?php echo esc_url(AWP_ASSETS.'/images/logos/onehash.png'); ?>" height="50" alt="Onehash Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager(sanitize_text_field($_GET['tab']));
                    $instruction_obj->prepare_instructions();
                        
                ?>

                <br />

                <?php 

                $form_fields = '';
                $app_name= 'onehash';
                $onehash_form = new AWP_Form_Fields($app_name);

                $form_fields = $onehash_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_onehash_display_name",
                        'name'          => "awp_onehash_display_name",
                        'value'         => $display_name,
                        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
                        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $onehash_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_onehash_api_token",
                        'name'          => "awp_onehash_api_token",
                        'value'         => $api_token,
                        'placeholder'   => esc_html__( 'Enter your Onehash API Key', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Onehash API Key', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $onehash_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_onehash_secret_key",
                        'name'          => "awp_onehash_secret_key",
                        'value'         => $api_token,
                        'placeholder'   => esc_html__( 'Enter your Onehash Secreet Key', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Onehash Secreet Key', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $onehash_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_onehash_domain_url",
                        'name'          => "awp_onehash_domain_url",
                        'value'         => $url,
                        'placeholder'   => esc_html__( 'https://abc.onehash.ai/', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Your Onehash Domain URL', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $onehash_form->awp_wp_hidden_input(
                    array(
                        'name'          => "action",
                        'value'         => 'awp_onehash_save_api_token',
                    )
                );


                $form_fields .= $onehash_form->awp_wp_hidden_input(
                    array(
                        'name'          => "_nonce",
                        'value'         =>$nonce,
                    )
                );
                $form_fields .= $onehash_form->awp_wp_hidden_input(
                    array(
                        'name'          => "id",
                        'value'         =>wp_unslash($id),
                    )
                );


                $onehash_form->render($form_fields);

                ?>


        </div>

        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                 $data=[
                    'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('onehash');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();

                ?>
                        </form>
                </div>
        <?php
    }

    public function awp_save_onehash_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_onehash_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_token = sanitize_text_field($_POST["awp_onehash_api_token"]);
        $display_name     = sanitize_text_field( $_POST["awp_onehash_display_name"] );
        $secret_key     = sanitize_text_field( $_POST["awp_onehash_secret_key"] );
        $url     = sanitize_text_field( $_POST["awp_onehash_domain_url"] );

        if ($url != "") {
            $url_regexPattern = '/^https:\/\/.[a-zA-Z0-9][a-zA-Z0-9\-]*\.onehash\.ai\/\z/';
            
            if (preg_match($url_regexPattern, $url) == 1) {
                // Save tokens
                $platform_obj = new AWP_Platform_Shell_Table('onehash');
                $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_token, 'client_secret' => $secret_key, 'url' => $url ]);

                AWP_redirect("admin.php?page=automate_hub&tab=onehash");
            } else {
                wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=onehash'));
                exit();
            }
        } else {
            wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=onehash'));
            exit();
        }

        
    } 
    
    public function load_custom_script() {
        wp_enqueue_script( 'awp-onehash-script', AWP_URL . '/apps/o/onehash/onehash.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="onehash-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'onehash',
                           'app_name'=>'Onehash ',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/onehash.png',
                           'app_icon_alter_text'=>'Onehash  Icon',
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
        $platform_obj= new AWP_Platform_Shell_Table('onehash');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;

        $url = "https://api.onehash.com/v1/sources";
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


$Awp_Onehash = new AWP_Onehash();

function awp_onehash_save_integration() {
    Appfactory::save_integration();
}

function awp_onehash_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('onehash');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    
    
    $api_key= $temp->api_key;
    $api_secret= $temp->client_secret;
    $url= $temp->url;

    $task = $decoded_data["task"]; 
    
    $token = 'token '.$api_key.":".$api_secret;

    if( $task == "createcustomer" ) {

        $name = empty( $data["cfirst_name"] ) ? "" : awp_get_parsed_values($data["cfirst_name"], $posted_data);
        $last_name = empty( $data["clast_name"] ) ? "" : awp_get_parsed_values($data["clast_name"], $posted_data);
        $customer_primary_contact = empty( $data["customer_primary_contact"] ) ? "" : awp_get_parsed_values($data["customer_primary_contact"], $posted_data);
        $email = empty( $data["email"] ) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $mobile_no = empty( $data["mobile_no"] ) ? "" : awp_get_parsed_values($data["mobile_no"], $posted_data);
       
        $body = json_encode([
            "data" => [
                "name"=> $name,
                "customer_name" => $name." ".$last_name,
                "customer_type" =>  "Company",
                "customer_group" => "All Customer Groups",
                "territory" => "All Territories",
                "customer_primary_contact" => $customer_primary_contact,
                "mobile_no" => $mobile_no,
                "email_id" => $email,
            ]
        ]);

        $url = $url."api/resource/Customer";

        $args = [
            'headers' => array(
                "Accept" =>"application/json", 
                "Content-Type" => "application/json",
                'Authorization' => $token,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } else if($task == "createlead"){

        $first_name = empty( $data["first_name"] ) ? "" : awp_get_parsed_values($data["first_name"], $posted_data);
        $last_name = empty( $data["last_name"] ) ? "" : awp_get_parsed_values($data["last_name"], $posted_data);
        
        $company_name = empty( $data["company_name"] ) ? "" : awp_get_parsed_values($data["company_name"], $posted_data);
        $email_id = empty( $data["email"] ) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $country = empty( $data["country"] ) ? "" : awp_get_parsed_values($data["country"], $posted_data);
        $phone = empty( $data["phone"] ) ? "" : awp_get_parsed_values($data["phone"], $posted_data);
        $lead_name = $first_name ." ".$last_name;

        $body = json_encode([
            "first_name"=>$first_name,
            "last_name"=>$last_name,
            "lead_name"=>$lead_name,
            "company_name"=>$company_name,
            "email_id"=>$email_id,
            "country"=>$country,
            "phone"=>$phone,
            "address_type" => "Billing",
        ]);

        $url = $url."api/resource/Lead";

        $args = [
            'headers' => array(
                "Accept" =>"application/json", 
                "Content-Type" => "application/json",
                'Authorization' => $token,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),

            'body'=> $body
        ];

        

        $response  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } else if($task == "createcontact"){

        $first_name = empty( $data["ccfirst_name"] ) ? "" : awp_get_parsed_values($data["ccfirst_name"], $posted_data);
        $email_id = empty( $data["ccemail"] ) ? "" : awp_get_parsed_values($data["ccemail"], $posted_data);
        $phone = empty( $data["ccmobile_no"] ) ? "" : awp_get_parsed_values($data["ccmobile_no"], $posted_data);
       

        $body = json_encode([
            "data" => [
                "first_name"=>$first_name,
                "email_id"=>$email_id,
                "mobile_no"=>$phone
            ]
        ]);

        $url = $url."api/resource/Contact";

        $args = [
            'headers' => array(
                "Accept" =>"application/json", 
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


function awp_onehash_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('onehash');
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
