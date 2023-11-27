<?php

class AWP_Productlift extends appfactory
{

   
    public function init_actions(){
       
        add_action('admin_post_awp_productlift_save_api_token', [$this, 'awp_save_productlift_api_token'], 10, 0);
        
        add_action('wp_ajax_awp_fetch_productlift_post_status', [$this, 'awp_fetch_productlift_post_status'], 10, 0);
        add_action('wp_ajax_awp_fetch_productlift_category', [$this, 'awp_fetch_productlift_category'], 10, 0);
        
    }

    public function init_filters(){

        add_filter('awp_platforms_connections', [$this, 'awp_productlift_platform_connection'], 10, 1);
    }

    public function action_provider( $providers ) {
        $providers['productlift'] = array(
            'title' => esc_html__( 'Productlift', 'automate_hub' ),
            'tasks' => array(
                'createuser'   => esc_html__( 'Create User', 'automate_hub' ),
                'createpost'   => esc_html__( 'Create Post', 'automate_hub' )
            )
        );
    
        return  $providers;
    }

    public function settings_tab( $tabs ) {
        $tabs['productlift'] = array('name'=>esc_html__( 'Productlift', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'productlift') {
            return;
        }
        $nonce = wp_create_nonce("awp_productlift_settings");
        $api_token = isset($_GET['api_key']) ? sanitize_text_field($_GET['api_key']) : "";
        $id = isset($_GET['id']) ? intval( sanitize_text_field($_GET['id'] )) : "";
        $display_name     = isset($_GET['account_name']) ? sanitize_text_field($_GET['account_name']) : "";
        $url     = isset($_GET['awp_productlift_domain_url']) ? sanitize_text_field($_GET['awp_productlift_domain_url']) : "";
        ?>
        <div class="platformheader">

            <a href="https://sperse.io/go/productlift" target="_blank"><img src="<?php echo esc_url(AWP_ASSETS.'/images/logos/productlift.png'); ?>" height="50" alt="Productlift Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager(sanitize_text_field($_GET['tab']));
                    $instruction_obj->prepare_instructions();
                        
                ?>

                <br />

                <?php 

                $form_fields = '';
                $app_name= 'productlift';
                $productlift_form = new AWP_Form_Fields($app_name);

                $form_fields = $productlift_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_productlift_display_name",
                        'name'          => "awp_productlift_display_name",
                        'value'         => $display_name,
                        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
                        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $productlift_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_productlift_api_token",
                        'name'          => "awp_productlift_api_token",
                        'value'         => $api_token,
                        'placeholder'   => esc_html__( 'Enter your Productlift API Key', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Productlift API Key', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $productlift_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_productlift_domain_url",
                        'name'          => "awp_productlift_domain_url",
                        'value'         => $url,
                        'placeholder'   => esc_html__( 'https://abc.productlift.dev', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Productlift Your Productlift Domain URL', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $productlift_form->awp_wp_hidden_input(
                    array(
                        'name'          => "action",
                        'value'         => 'awp_productlift_save_api_token',
                    )
                );


                $form_fields .= $productlift_form->awp_wp_hidden_input(
                    array(
                        'name'          => "_nonce",
                        'value'         =>$nonce,
                    )
                );
                $form_fields .= $productlift_form->awp_wp_hidden_input(
                    array(
                        'name'          => "id",
                        'value'         =>wp_unslash($id),
                    )
                );


                $productlift_form->render($form_fields);

                ?>


        </div>

        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                 $data=[
                    'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('productlift');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();

                ?>
                        </form>
                </div>
        <?php
    }

    public function awp_save_productlift_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_productlift_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_token = sanitize_text_field($_POST["awp_productlift_api_token"]);
        $display_name     = sanitize_text_field( $_POST["awp_productlift_display_name"] );
        $url     = sanitize_text_field( $_POST["awp_productlift_domain_url"] );

        if ($url != "") {
            $url_regexPattern = '/^https:\/\/.[a-zA-Z0-9][a-zA-Z0-9\-]*\.productlift\.dev\z/';
            
            if (preg_match($url_regexPattern, $url) == 1) {
                // Save tokens
                $platform_obj = new AWP_Platform_Shell_Table('productlift');
                $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_token, 'url' => $url ]);

                AWP_redirect("admin.php?page=automate_hub&tab=productlift");
            } else {
                wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=productlift'));
                exit();
            }
        } else {
            wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=productlift'));
            exit();
        }

        
    } 
    
    public function load_custom_script() {
        wp_enqueue_script( 'awp-productlift-script', AWP_URL . '/apps/p/productlift/productlift.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="productlift-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'productlift',
                           'app_name'=>'Productlift ',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/productlift.png',
                           'app_icon_alter_text'=>'Productlift  Icon',
                           'account_select_onchange'=>'getfunctions',
                           'tasks'=>array(
                                'createuser'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Role',
                                            'type'=>'select',
                                            'name'=>"role",
                                            'required' => 'required',
                                            'onchange' => 'selectedrole',
                                            'option_for_loop'=>'(item) in data.roleList',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Role...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': roleLoading}",
                                                        )
                                        )
                                    ),
                                ),
                                'createpost'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Status',
                                            'type'=>'select',
                                            'name'=>"status_id",
                                            'required' => 'required',
                                            'onchange' => 'getcategory',
                                            'option_for_loop'=>'(item) in data.statusList',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Status...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': statusLoading}",
                                                        )
                                        ),
                                        array(
                                            'label'=>'Select Category',
                                            'type'=>'select',
                                            'name'=>"category_id",
                                            'required' => 'required',
                                            'onchange' => 'selectedcategory',
                                            'option_for_loop'=>'(item) in data.categoryList',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Category...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': categoryLoading}",
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

    public function awp_fetch_productlift_post_status() {
        
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
        $platform_obj= new AWP_Platform_Shell_Table('productlift');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;
        $url= $data->url;

        $url = $url."/api/v1/statuses";

        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$api_key
            )
        );

        $response = wp_remote_get( $url, $args );


        $body = wp_remote_retrieve_body( $response );
        $body = json_decode( $body, true );
        wp_send_json_success($body['data']);
    }

    public function awp_fetch_productlift_category() {
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
        $platform_obj= new AWP_Platform_Shell_Table('productlift');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;
        $url= $data->url;

        $url = $url."/api/v1/categories";

        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$api_key
            )
        );

        $response = wp_remote_get( $url, $args );
        $body = wp_remote_retrieve_body( $response );
        $body = json_decode( $body, true );
        wp_send_json_success($body["data"]);
    }

};


$Awp_Productlift = new AWP_Productlift();

function awp_productlift_save_integration() {
    Appfactory::save_integration();
}

function awp_productlift_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('productlift');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    
    
    $api_key= $temp->api_key;
    $url= $temp->url;

    $task = $decoded_data["task"]; 
    
    $token = 'Bearer '.$api_key;

    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';

    if( $task == "createuser" ) {

        $name = empty( $data["name"] ) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $invitation_message = empty( $data["invitation_message"] ) ? "" : awp_get_parsed_values($data["invitation_message"], $posted_data);
        $email = empty( $data["email"] ) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $role = empty( $data["role"] ) ? "" : awp_get_parsed_values($data["role"], $posted_data);
       
        $body = json_encode([
                "name"=> $name,
                "email" => $email,
                "invitation_message" => $invitation_message,
                "role" => $role,
                "password" => substr(str_shuffle($permitted_chars), 0, 10),
                
        ]);

        $url = $url."/api/v1/users";

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

    } else if($task == "createpost"){

        $title = empty( $data["title"] ) ? "" : awp_get_parsed_values($data["title"], $posted_data);
        $description = empty( $data["description"] ) ? "" : awp_get_parsed_values($data["description"], $posted_data);
        $category_id = empty( $data["category_id"] ) ? "" : awp_get_parsed_values($data["category_id"], $posted_data);
        $status_id = empty( $data["status_id"] ) ? "" : awp_get_parsed_values($data["status_id"], $posted_data);
       
        $body = json_encode([
            "title"=>$title,
            "description"=>$description,
            "category_id"=>$category_id,
            "status_id"=>$status_id
        ]);

        $url = $url."/api/v1/posts";

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


function awp_productlift_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('productlift');
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
