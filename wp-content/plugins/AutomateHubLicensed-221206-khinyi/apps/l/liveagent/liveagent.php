<?php

class AWP_LiveAgent extends appfactory
{

   
    public function init_actions(){
       
        add_action('admin_post_awp_liveagent_save_api_token', [$this, 'awp_save_liveagent_api_token'], 10, 0);
        
        add_action( 'wp_ajax_awp_fetch_company_list', [$this, 'awp_fetch_company_list']);
        
    }

    public function init_filters(){

        add_filter('awp_platforms_connections', [$this, 'awp_liveagent_platform_connection'], 10, 1);
    }

    public function action_provider( $providers ) {
        $providers['liveagent'] = array(
            'title' => esc_html__( 'LiveAgent', 'automate_hub' ),
            'tasks' => array(
                'createcustomer'   => esc_html__( 'Create Contact', 'automate_hub' )
            )
        );
    
        return  $providers;
    }

    public function settings_tab( $tabs ) {
        $tabs['liveagent'] = array('name'=>esc_html__( 'LiveAgent', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'liveagent') {
            return;
        }
        $nonce = wp_create_nonce("awp_liveagent_settings");
        $api_token = isset($_GET['api_key']) ? sanitize_text_field($_GET['api_key']) : "";
        $id = isset($_GET['id']) ? intval( sanitize_text_field($_GET['id'] )) : "";
        $display_name     = isset($_GET['account_name']) ? sanitize_text_field($_GET['account_name']) : "";
        $api_url     = isset($_GET['awp_liveagent_api_url']) ? sanitize_text_field($_GET['awp_liveagent_api_url']) : "";
        ?>
        <div class="platformheader">

            <a href="https://sperse.io/go/liveagent" target="_blank"><img src="<?php echo esc_url(AWP_ASSETS.'/images/logos/liveagent.png'); ?>" height="50" alt="LiveAgent Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager(sanitize_text_field($_GET['tab']));
                    $instruction_obj->prepare_instructions();
                        
                ?>

                <br />

                <?php 

                $form_fields = '';
                $app_name= 'liveagent';
                $liveagent_form = new AWP_Form_Fields($app_name);

                $form_fields = $liveagent_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_liveagent_display_name",
                        'name'          => "awp_liveagent_display_name",
                        'value'         => $display_name,
                        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
                        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $liveagent_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_liveagent_api_token",
                        'name'          => "awp_liveagent_api_token",
                        'value'         => $api_token,
                        'placeholder'   => esc_html__( 'Enter your LiveAgent Live API Key', 'automate_hub' ),
                        'label'         =>  esc_html__( 'LiveAgent API Key', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $liveagent_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_liveagent_api_url",
                        'name'          => "awp_liveagent_api_url",
                        'value'         => $api_url,
                        'placeholder'   => esc_html__( 'http://abc.ladesk.com', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Enter your LiveAgent API Url', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $liveagent_form->awp_wp_hidden_input(
                    array(
                        'name'          => "action",
                        'value'         => 'awp_liveagent_save_api_token',
                    )
                );


                $form_fields .= $liveagent_form->awp_wp_hidden_input(
                    array(
                        'name'          => "_nonce",
                        'value'         =>$nonce,
                    )
                );
                $form_fields .= $liveagent_form->awp_wp_hidden_input(
                    array(
                        'name'          => "id",
                        'value'         =>wp_unslash($id),
                    )
                );


                $liveagent_form->render($form_fields);

                ?>


        </div>

        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                 $data=[
                    'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('liveagent');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();

                ?>
                        </form>
                </div>
        <?php
    }

    public function awp_save_liveagent_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_liveagent_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_token = sanitize_text_field($_POST["awp_liveagent_api_token"]);
        $display_name     = sanitize_text_field( $_POST["awp_liveagent_display_name"] );
        $api_url     = sanitize_text_field( $_POST["awp_liveagent_api_url"] );

        if ($api_url != "") {
            $url_regexPattern = '/^https:\/\/.[a-zA-Z0-9][a-zA-Z0-9\-]*\.ladesk\.com\z/';
            if (preg_match($url_regexPattern, $api_url) == 1) {
                // Save tokens
                $platform_obj = new AWP_Platform_Shell_Table('liveagent');
                $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_token, 'url' => $api_url]);

                AWP_redirect("admin.php?page=automate_hub&tab=liveagent");

            } else {
                wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=liveagent'));
                exit();
            }
        } else {
            wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=liveagent'));
            exit();
        }

    } 
    
    public function load_custom_script() {
        wp_enqueue_script( 'awp-liveagent-script', AWP_URL . '/apps/l/liveagent/liveagent.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="liveagent-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'liveagent',
                           'app_name'=>'LiveAgent ',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/liveagent.png',
                           'app_icon_alter_text'=>'LiveAgent  Icon',
                           'account_select_onchange'=>'getcompany',
                           'tasks'=>array(
                                'createcustomer'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Company',
                                            'type'=>'select',
                                            'name'=>"company_id",
                                            'required' => 'required',
                                            'onchange' => 'selectedCompany',
                                            'option_for_loop'=>'(item) in data.companyList',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Company...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': companyLoading}",
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

    
    public function awp_fetch_company_list() {
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
        $platform_obj= new AWP_Platform_Shell_Table('liveagent');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;

        $url = $data->url."/api/v3/companies?_page=1&_perPage=10&_from=0&_to=0&_sortDir=ASC";
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'apikey' => $api_key,
                'scope' => '*'
            )
        );

        

        $response = wp_remote_get( $url, $args );

       

        $body = wp_remote_retrieve_body( $response );
        $body = json_decode( $body, true );
        wp_send_json_success($body);
    }
   
};


$Awp_LiveAgent = new AWP_LiveAgent();

function awp_liveagent_save_integration() {
    Appfactory::save_integration();
}

function awp_liveagent_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('liveagent');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $url=$temp->url;
    $task = $decoded_data["task"]; 

    if( $task == "createcustomer" ) {

        $firstname = empty( $data["firstname"] ) ? "" : awp_get_parsed_values($data["firstname"], $posted_data);
        $lastname = empty( $data["lastname"] ) ? "" : awp_get_parsed_values($data["lastname"], $posted_data);
        $city = empty( $data["city"] ) ? "" : awp_get_parsed_values($data["city"], $posted_data);
        $email = empty( $data["email"] ) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $phone = empty( $data["phone"] ) ? "" : awp_get_parsed_values($data["phone"], $posted_data);
        $job_position = empty( $data["job_position"] ) ? "" : awp_get_parsed_values($data["job_position"], $posted_data);
        

       ;

        $body = json_encode([
            "firstname" => $firstname,
            "lastname" => $lastname,
            "language" => "English",
            "city" => $city,
            "emails" => [
                $email
            ],
            "phones" => [
                $phone
            ],
            "job_position" => $job_position
        ]);

        $url = $url."/api/v3/contacts";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'apiKey' => $api_key,
                'scope' => '*',
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['apiKey']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } 
    return $response;
}


function awp_liveagent_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('liveagent');
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
