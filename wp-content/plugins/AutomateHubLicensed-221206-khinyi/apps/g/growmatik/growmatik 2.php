<?php

class AWP_Growmatik extends appfactory
{

   
    public function init_actions(){
       
        add_action('admin_post_awp_growmatik_save_api_token', [$this, 'awp_save_growmatik_api_token'], 10, 0);
        
        add_action( 'wp_ajax_awp_fetch_product_category', [$this, 'awp_fetch_product_category']);
       
    }

    public function init_filters(){

        add_filter('awp_platforms_connections', [$this, 'awp_growmatik_platform_connection'], 10, 1);
    }

    public function action_provider( $providers ) {
        $providers['growmatik'] = array(
            'title' => esc_html__( 'Growmatik', 'automate_hub' ),
            'tasks' => array(
                'createcontact'   => esc_html__( 'Create Contact', 'automate_hub' ),
                'createcategory'   => esc_html__( 'Create Category', 'automate_hub' ),
                'createproduct'   => esc_html__( 'Create Product', 'automate_hub' )
            )
        );
    
        return  $providers;
    }

    public function settings_tab( $tabs ) {
        $tabs['growmatik'] = array('name'=>esc_html__( 'Growmatik', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'growmatik') {
            return;
        }
        $nonce = wp_create_nonce("awp_growmatik_settings");
        $api_token = isset($_GET['api_key']) ? sanitize_text_field($_GET['api_key']) : "";
        $secret = isset($_GET['awp_growmatik_secret']) ? sanitize_text_field($_GET['awp_growmatik_secret']) : "";
        $id = isset($_GET['id']) ? intval( sanitize_text_field($_GET['id'] )) : "";
        $display_name     = isset($_GET['account_name']) ? sanitize_text_field($_GET['account_name']) : "";
        ?>
        <div class="platformheader">

            <a href="https://sperse.io/go/growmatik" target="_blank"><img src="<?php echo esc_url(AWP_ASSETS.'/images/logos/growmatik.png'); ?>"  height="50" alt="Growmatik Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager(sanitize_text_field($_GET['tab']));
                    $instruction_obj->prepare_instructions();
                        
                ?>

                <br />

                <?php 

                $form_fields = '';
                $app_name= 'growmatik';
                $growmatik_form = new AWP_Form_Fields($app_name);

                $form_fields = $growmatik_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_growmatik_display_name",
                        'name'          => "awp_growmatik_display_name",
                        'value'         => $display_name,
                        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
                        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $growmatik_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_growmatik_api_token",
                        'name'          => "awp_growmatik_api_token",
                        'value'         => $api_token,
                        'placeholder'   => esc_html__( 'Enter your growmatik API Key', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Growmatik API Key', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $growmatik_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_growmatik_secret",
                        'name'          => "awp_growmatik_secret",
                        'value'         => $secret,
                        'placeholder'   => esc_html__( 'Enter your growmatik secret Key', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Growmatik Secret Key', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $growmatik_form->awp_wp_hidden_input(
                    array(
                        'name'          => "action",
                        'value'         => 'awp_growmatik_save_api_token',
                    )
                );


                $form_fields .= $growmatik_form->awp_wp_hidden_input(
                    array(
                        'name'          => "_nonce",
                        'value'         =>$nonce,
                    )
                );
                $form_fields .= $growmatik_form->awp_wp_hidden_input(
                    array(
                        'name'          => "id",
                        'value'         =>wp_unslash($id),
                    )
                );


                $growmatik_form->render($form_fields);

                ?>


        </div>

        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                 $data=[
                    'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('growmatik');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();

                ?>
                        </form>
                </div>
        <?php
    }

    public function awp_save_growmatik_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_growmatik_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_token = sanitize_text_field($_POST["awp_growmatik_api_token"]);
        $display_name     = sanitize_text_field( $_POST["awp_growmatik_display_name"] );
        $secret     = sanitize_text_field( $_POST["awp_growmatik_secret"] );

        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('growmatik');
        $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_token, 'client_secret' => $secret]);

        AWP_redirect("admin.php?page=automate_hub&tab=growmatik");
    } 
    
    public function load_custom_script() {
        wp_enqueue_script( 'awp-growmatik-script', AWP_URL . '/apps/g/growmatik/growmatik.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="growmatik-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'growmatik',
                           'app_name'=>'Growmatik ',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/growmatik.png',
                           'app_icon_alter_text'=>'Growmatik  Icon',
                           'account_select_onchange'=>'getcategorylist',
                           'tasks'=>array(
                                'createproduct'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Category',
                                            'type'=>'select',
                                            'name'=>"categoryid",
                                            'required' => 'required',
                                            'onchange' => 'selectedcategory',
                                            'option_for_loop'=>'(item) in data.categoryList',
                                            'option_for_value'=>'item.termId',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Category...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': categoryLoading}",
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

    
    public function awp_fetch_product_category() {
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
        $platform_obj= new AWP_Platform_Shell_Table('growmatik');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;
        $api_secret =$data->client_secret;

        $url = "https://api.growmatik.ai/public/v1/products/category";
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'apiKey' => $api_key,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            )
        );

        $response = wp_remote_get( $url, $args );
       
        $body = wp_remote_retrieve_body( $response );
        $body = json_decode( $body, true );
        wp_send_json_success($body['data']);
    }

};


$Awp_Growmatik = new AWP_Growmatik();

function awp_growmatik_save_integration() {
    Appfactory::save_integration();
}

function awp_growmatik_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('growmatik');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $api_secret=$temp->client_secret;
    $task = $decoded_data["task"]; 

   
    if( $task == "createcontact" ) {

        $email = empty( $data["email"] ) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $firstName = empty( $data["firstName"] ) ? "" : awp_get_parsed_values($data["firstName"], $posted_data);
        $lastName = empty( $data["lastName"] ) ? "" : awp_get_parsed_values($data["lastName"], $posted_data);
        $address = empty( $data["address"] ) ? "" : awp_get_parsed_values($data["address"], $posted_data);
        $phoneNumber = empty( $data["phoneNumber"] ) ? "" : awp_get_parsed_values($data["phoneNumber"], $posted_data);
        $country = empty( $data["country"] ) ? "" : awp_get_parsed_values($data["country"], $posted_data);
        $region = empty( $data["region"] ) ? "" : awp_get_parsed_values($data["region"], $posted_data);
        $city = empty( $data["city"] ) ? "" : awp_get_parsed_values($data["city"], $posted_data);

        $body = json_encode([
            "apiSecret" => $api_secret,
            "users" => array(
                array(
                    "userId" => rand(100, 999),
                    "userName" => "user".substr(str_shuffle($firstName), 0, 3),
                    "email" => $email,
                    "firstName" => $firstName,
                    "lastName" => $lastName,
                    "address" => $address,
                    "phoneNumber" => $phoneNumber,
                    "country" => $country,
                    "region" => $region,
                    "city" => $city
                )
            )
        ]);

        $url = "https://api.growmatik.ai/public/v1/contacts";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'apiKey' => $api_key,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } else if( $task == "createcategory" ) {

        $name = empty( $data["name"] ) ? "" : awp_get_parsed_values($data["name"], $posted_data);

        $body = json_encode([
            "apiSecret" => $api_secret,
            "category" => array(
                array(
                    "id" => rand(11, 99),
                    "name" => $name,
                    "slug" => $name
                )
            )
        ]);

        $url = "https://api.growmatik.ai/public/v1/products/category";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'apiKey' => $api_key,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } else if( $task == "createproduct" ) {

        $productname = empty( $data["productname"] ) ? "" : awp_get_parsed_values($data["productname"], $posted_data);
        $content = empty( $data["content"] ) ? "" : awp_get_parsed_values($data["content"], $posted_data);
        $excerpt = empty( $data["excerpt"] ) ? "" : awp_get_parsed_values($data["excerpt"], $posted_data);
        $price = empty( $data["price"] ) ? "" : awp_get_parsed_values($data["price"], $posted_data);
        $sprice = empty( $data["sprice"] ) ? "" : awp_get_parsed_values($data["sprice"], $posted_data);
        $categoryid = empty( $data["categoryid"] ) ? "" : awp_get_parsed_values($data["categoryid"], $posted_data);

        $body = json_encode([
            "apiSecret" => $api_secret,
            "products" => array(
                array(
                    "id" => rand(999, 100),
                    "name" => $productname,
                    "sku" => rand(100, 999),
                    "content" => $content,
                    "excerpt" => $excerpt,
                    "price" => $price,
                    "salePrice" => $sprice,
                    "publishAt" => date('Y-m-d h:i:s'),
                    "url" => "/SampleProduct",
                    "images" => [
                        "/SampleProduct.png"
                    ],
                    "categories" => [
                        (integer) $categoryid
                    ]
                )
            )
        ]);

        $url = "https://api.growmatik.ai/public/v1/products";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'apiKey' => $api_key,
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


function awp_growmatik_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('growmatik');
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
