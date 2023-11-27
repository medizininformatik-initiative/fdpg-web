<?php

class AWP_Salesflare extends appfactory
{

   
    public function init_actions(){
       
        add_action('admin_post_awp_salesflare_save_api_token', [$this, 'awp_save_salesflare_api_token'], 10, 0);
        
        add_action( 'wp_ajax_awp_fetch_account', [$this, 'awp_fetch_account']);
       
        
        
    }

    public function init_filters(){

        add_filter('awp_platforms_connections', [$this, 'awp_salesflare_platform_connection'], 10, 1);
    }

    public function action_provider( $providers ) {
        $providers['salesflare'] = array(
            'title' => esc_html__( 'Salesflare', 'automate_hub' ),
            'tasks' => array(
                'createcontact'   => esc_html__( 'Create Contact', 'automate_hub' ),
                'createaccount'   => esc_html__( 'Create Task', 'automate_hub' ),
                'createnote'   => esc_html__( 'Create Internal Note', 'automate_hub' )
            )
        );
    
        return  $providers;
    }

    public function settings_tab( $tabs ) {
        $tabs['salesflare'] = array('name'=>esc_html__( 'Salesflare', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'salesflare') {
            return;
        }
        $nonce = wp_create_nonce("awp_salesflare_settings");
        $api_token = isset($_GET['api_key']) ? sanitize_text_field($_GET['api_key']) : "";
        $id = isset($_GET['id']) ? intval( sanitize_text_field($_GET['id'] )) : "";
        $display_name     = isset($_GET['account_name']) ? sanitize_text_field($_GET['account_name']) : "";
        ?>
        <div class="platformheader">

            <a href="https://sperse.io/go/salesflare" target="_blank"><img src="<?php echo esc_url(AWP_ASSETS.'/images/logos/salesflare.png'); ?>"  height="50" alt="SalesflareLogo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager(sanitize_text_field($_GET['tab']));
                    $instruction_obj->prepare_instructions();
                        
                ?>

                <br />

                <?php 

                $form_fields = '';
                $app_name= 'salesflare';
                $salesflare_form = new AWP_Form_Fields($app_name);

                $form_fields = $salesflare_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_salesflare_display_name",
                        'name'          => "awp_salesflare_display_name",
                        'value'         => $display_name,
                        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
                        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $salesflare_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_salesflare_api_token",
                        'name'          => "awp_salesflare_api_token",
                        'value'         => $api_token,
                        'placeholder'   => esc_html__( 'Enter your SalesflareLive API Key', 'automate_hub' ),
                        'label'         =>  esc_html__( 'SalesflareAPI Key', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $salesflare_form->awp_wp_hidden_input(
                    array(
                        'name'          => "action",
                        'value'         => 'awp_salesflare_save_api_token',
                    )
                );


                $form_fields .= $salesflare_form->awp_wp_hidden_input(
                    array(
                        'name'          => "_nonce",
                        'value'         =>$nonce,
                    )
                );
                $form_fields .= $salesflare_form->awp_wp_hidden_input(
                    array(
                        'name'          => "id",
                        'value'         =>wp_unslash($id),
                    )
                );


                $salesflare_form->render($form_fields);

                ?>


        </div>

        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                 $data=[
                    'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('salesflare');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();

                ?>
                        </form>
                </div>
        <?php
    }

    public function awp_save_salesflare_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_salesflare_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_token = sanitize_text_field($_POST["awp_salesflare_api_token"]);
        $display_name     = sanitize_text_field( $_POST["awp_salesflare_display_name"] );

        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('salesflare');
        $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_token]);

        AWP_redirect("admin.php?page=automate_hub&tab=salesflare");
    } 
    
    public function load_custom_script() {
        wp_enqueue_script( 'awp-salesflare-script', AWP_URL . '/apps/s/salesflare/salesflare.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="salesflare-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'salesflare',
                           'app_name'=>'Salesflare',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/salesflare.png',
                           'app_icon_alter_text'=>'Salesflare Icon',
                           'account_select_onchange'=>'getaccounts',
                           'tasks'=>array(
                                'createcontact'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Account Owner',
                                            'type'=>'select',
                                            'name'=>"accountid",
                                            'required' => 'required',
                                            'onchange' => 'selectedowner',
                                            'option_for_loop'=>'(item) in data.accountList',
                                            'option_for_value'=>'item.owner.id',
                                            'option_for_text'=>'{{item.owner.name}}',
                                            'select_default'=>'Select Account Owner...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': accountLoading}",
                                                        )
                                        )
                                    ),
                                ),
                                'createaccount'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Account Owner',
                                            'type'=>'select',
                                            'name'=>"accountid",
                                            'required' => 'required',
                                            'onchange' => 'selectedowner',
                                            'option_for_loop'=>'(item) in data.accountList',
                                            'option_for_value'=>'item.owner.id',
                                            'option_for_text'=>'{{item.owner.name}}',
                                            'select_default'=>'Select Account Owner...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': accountLoading}",
                                                        )
                                        )
                                    ),
                                ),
                                'createnote'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Account',
                                            'type'=>'select',
                                            'name'=>"accountid",
                                            'required' => 'required',
                                            'onchange' => 'selectedowner',
                                            'option_for_loop'=>'(item) in data.accountList',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Account Owner...',
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

    
    public function awp_fetch_account() {
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
        $platform_obj= new AWP_Platform_Shell_Table('salesflare');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;

        $url = "https://api.salesflare.com/accounts";
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


$Awp_salesflare= new AWP_Salesflare();

function awp_salesflare_save_integration() {
    Appfactory::save_integration();
}

function awp_salesflare_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('salesflare');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $task = $decoded_data["task"]; 

   

    if( $task == "createcontact" ) {

        $firstname = empty( $data["firstname"] ) ? "" : awp_get_parsed_values($data["firstname"], $posted_data);
        $middle = empty( $data["middle"] ) ? "" : awp_get_parsed_values($data["middle"], $posted_data);
        $lastname = empty( $data["lastname"] ) ? "" : awp_get_parsed_values($data["lastname"], $posted_data);
        $email = empty( $data["email"] ) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $city = empty( $data["city"] ) ? "" : awp_get_parsed_values($data["city"], $posted_data);
        $country = empty( $data["country"] ) ? "" : awp_get_parsed_values($data["country"], $posted_data);
        $state_region = empty( $data["state_region"] ) ? "" : awp_get_parsed_values($data["state_region"], $posted_data);
        $phone_number = empty( $data["phone_number"] ) ? "" : awp_get_parsed_values($data["phone_number"], $posted_data);
        $owner = empty( $data["owner"] ) ? "" : awp_get_parsed_values($data["owner"], $posted_data);
        

        $token = 'Bearer '.$api_key;

        $body = json_encode([
            "owner"=>$owner,
            "firstname"=>$firstname, 
            "middle"=>$middle, 
            "lastname"=>$lastname, 
            "email"=>$email, 
            "address" => array(
                "city"=>$city, 
                "country"=>$country, 
                "state_region"=>$state_region 
            ),
            "phone_number"=>$phone_number
        ]);

        $url = "https://api.salesflare.com/contacts";

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
    } else if( $task == "createaccount" ) {

        $name = empty( $data["name"] ) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $size = empty( $data["size"] ) ? "" : awp_get_parsed_values($data["size"], $posted_data);
        $website = empty( $data["website"] ) ? "" : awp_get_parsed_values($data["website"], $posted_data);
        $description = empty( $data["description"] ) ? "" : awp_get_parsed_values($data["description"], $posted_data);
        $email = empty( $data["email"] ) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $city = empty( $data["city"] ) ? "" : awp_get_parsed_values($data["city"], $posted_data);
        $country = empty( $data["country"] ) ? "" : awp_get_parsed_values($data["country"], $posted_data);
        $state_region = empty( $data["state_region"] ) ? "" : awp_get_parsed_values($data["state_region"], $posted_data);
        $phone_number = empty( $data["phone_number"] ) ? "" : awp_get_parsed_values($data["phone_number"], $posted_data);
        $owner = empty( $data["owner"] ) ? "" : awp_get_parsed_values($data["owner"], $posted_data);
        

        $token = 'Bearer '.$api_key;

        $body = json_encode([
            "owner"=>$owner,
            "name"=>$name,
            "website"=>$website, 
            "description"=>$description, 
            "size"=>$size, 
            "email"=>$email, 
            "address" => array(
                "city"=>$city, 
                "country"=>$country, 
                "state_region"=>$state_region 
            ),
            "phone_number"=>$phone_number
        ]);

        $url = "https://api.salesflare.com/accounts";

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

    } else if( $task == "createnote" ) {

        $messagebody = empty( $data["body"] ) ? "" : awp_get_parsed_values($data["body"], $posted_data);
        $date = empty( $data["date"] ) ? "" : awp_get_parsed_values($data["date"], $posted_data);
        $owner = empty( $data["owner"] ) ? "" : awp_get_parsed_values($data["owner"], $posted_data);
        

        $token = 'Bearer '.$api_key;

        $body = json_encode([
            "account"=>$owner,
            // "date"=>date('Y-m-d\TH:i:s\z', strtotime($date)),
            "date"=>date('Y-m-d\TH:i:sP', strtotime($date)),
            "body"=>$messagebody 
        ]);

        $url = "https://api.salesflare.com/messages";

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


function awp_salesflare_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('salesflare');
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

   
        $token = 'Bearer '.$api_key;
        

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $token,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),

            'body'=> json_encode($body)
        ];


        $return  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="Bearer XXXXXXXXXXXX";
        awp_add_to_log( $return, $url, $args, $integration );

    $response['success']=true;    
    return $response;
}
