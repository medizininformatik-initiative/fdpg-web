<?php

class AWP_Baremetrics extends appfactory
{

   
    public function init_actions(){
       
        add_action('admin_post_awp_baremetrics_save_api_token', [$this, 'awp_save_baremetrics_api_token'], 10, 0);
        
        add_action( 'wp_ajax_awp_fetch_source', [$this, 'awp_fetch_source']);
        add_action( 'wp_ajax_awp_fetch_barematrics_accounts', [$this, 'awp_fetch_barematrics_accounts']);
        add_action( 'wp_ajax_awp_fetch_customers', [$this, 'awp_fetch_customers']);
        add_action( 'wp_ajax_awp_fetch_planlist', [$this, 'awp_fetch_planlist']);
        
        
    }

    public function init_filters(){

        add_filter('awp_platforms_connections', [$this, 'awp_baremetrics_platform_connection'], 10, 1);
    }

    public function action_provider( $providers ) {
        $providers['baremetrics'] = array(
            'title' => esc_html__( 'Baremetrics', 'automate_hub' ),
            'tasks' => array(
                'createcustomer'   => esc_html__( 'Create Customer', 'automate_hub' ),
                'createplan'   => esc_html__( 'Create Plan', 'automate_hub' )
            )
        );
    
        return  $providers;
    }

    public function settings_tab( $tabs ) {
        $tabs['baremetrics'] = array('name'=>esc_html__( 'Baremetrics', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'baremetrics') {
            return;
        }
        $nonce = wp_create_nonce("awp_baremetrics_settings");
        $api_token = isset($_GET['api_key']) ? sanitize_text_field($_GET['api_key']) : "";
        $id = isset($_GET['id']) ? intval( sanitize_text_field($_GET['id'] )) : "";
        $display_name     = isset($_GET['account_name']) ? sanitize_text_field($_GET['account_name']) : "";
        ?>
        <div class="platformheader">

            <a href="https://sperse.io/go/baremetrics" target="_blank"><img src="<?php echo esc_url(AWP_ASSETS.'/images/logos/baremetrics.png'); ?>" width="320" height="50" alt="Baremetrics Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager(sanitize_text_field($_GET['tab']));
                    $instruction_obj->prepare_instructions();
                        
                ?>

                <br />

                <?php 

                $form_fields = '';
                $app_name= 'baremetrics';
                $baremetrics_form = new AWP_Form_Fields($app_name);

                $form_fields = $baremetrics_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_baremetrics_display_name",
                        'name'          => "awp_baremetrics_display_name",
                        'value'         => $display_name,
                        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
                        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $baremetrics_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_baremetrics_api_token",
                        'name'          => "awp_baremetrics_api_token",
                        'value'         => $api_token,
                        'placeholder'   => esc_html__( 'Enter your Baremetrics Live API Key', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Baremetrics API Key', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $baremetrics_form->awp_wp_hidden_input(
                    array(
                        'name'          => "action",
                        'value'         => 'awp_baremetrics_save_api_token',
                    )
                );


                $form_fields .= $baremetrics_form->awp_wp_hidden_input(
                    array(
                        'name'          => "_nonce",
                        'value'         =>$nonce,
                    )
                );
                $form_fields .= $baremetrics_form->awp_wp_hidden_input(
                    array(
                        'name'          => "id",
                        'value'         =>wp_unslash($id),
                    )
                );


                $baremetrics_form->render($form_fields);

                ?>


        </div>

        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                 $data=[
                    'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('baremetrics');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();

                ?>
                        </form>
                </div>
        <?php
    }

    public function awp_save_baremetrics_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_baremetrics_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_token = sanitize_text_field($_POST["awp_baremetrics_api_token"]);
        $display_name     = sanitize_text_field( $_POST["awp_baremetrics_display_name"] );

        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('baremetrics');
        $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_token]);

        AWP_redirect("admin.php?page=automate_hub&tab=baremetrics");
    } 
    
    public function load_custom_script() {
        wp_enqueue_script( 'awp-baremetrics-script', AWP_URL . '/apps/b/baremetrics/baremetrics.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="baremetrics-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'baremetrics',
                           'app_name'=>'Baremetrics ',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/baremetrics.png',
                           'app_icon_alter_text'=>'Baremetrics  Icon',
                           'account_select_onchange'=>'getsourcesid',
                           'tasks'=>array(
                                'createcustomer'=>array(
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
                                ),
                                'createplan'=>array(
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
                                        ),

                                    ),
                                ),

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
        $platform_obj= new AWP_Platform_Shell_Table('baremetrics');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;

        $url = "https://api.baremetrics.com/v1/sources";
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

    public function awp_fetch_barematrics_accounts() {
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
        $platform_obj= new AWP_Platform_Shell_Table('baremetrics');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }


        $api_key =$data->api_key;

        $url = "https://api.baremetrics.com/v1/account";
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

    public function awp_fetch_customers() {
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
        $platform_obj= new AWP_Platform_Shell_Table('baremetrics');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }


        $api_key =$data->api_key;

        $url = "https://api.baremetrics.com/v1/".$_POST['sourceid']."/customers";
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$api_key
            )
        );

        $response = wp_remote_get( $url, $args );
        $body = wp_remote_retrieve_body( $response );
        
        $body = json_decode( $body, true );
        wp_send_json_success($body['customers']);
    }

    public function awp_fetch_planlist() {
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
        $platform_obj= new AWP_Platform_Shell_Table('baremetrics');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }


        $api_key =$data->api_key;

        $url = "https://api.baremetrics.com/v1/".$_POST['sourceid']."/plans";
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$api_key
            )
        );

        $response = wp_remote_get( $url, $args );
        $body = wp_remote_retrieve_body( $response );
        
        $body = json_decode( $body, true );
        wp_send_json_success($body['plans']);
    }
   
};


$Awp_Baremetrics = new AWP_Baremetrics();

function awp_baremetrics_save_integration() {
    Appfactory::save_integration();
}

function awp_baremetrics_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('baremetrics');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $task = $decoded_data["task"]; 

   

    if( $task == "createcustomer" ) {

        $name = empty( $data["name"] ) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $note = empty( $data["notes"] ) ? "" : awp_get_parsed_values($data["notes"], $posted_data);
        $email = empty( $data["email"] ) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $oid = "CID".mt_rand(000210, 999999);

        $token = 'Bearer '.$api_key;

        $body = json_encode([
            "name"=>$name,
            "notes"=>$note,
            "oid"=>$oid,
            "email"=>$email,
            "created"=>strtotime("now")
        ]);

        $url = "https://api.baremetrics.com/v1/". $data['sourceid']."/customers";

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

    } else if($task == "createplan"){

     

        $name = empty( $data["planname"] ) ? "" : awp_get_parsed_values($data["planname"], $posted_data);
        $uniqueplanid = "PID".mt_rand(000210, 999999);
        $currency = empty( $data["currency"] ) ? "" : awp_get_parsed_values($data["currency"], $posted_data);
        $amount = empty( $data["amount"] ) ? "" : awp_get_parsed_values($data["amount"], $posted_data);
        $interval = empty( $data["interval"] ) ? "" : awp_get_parsed_values($data["interval"], $posted_data);
        $interval_count = empty( $data["interval_count"] ) ? "" : awp_get_parsed_values($data["interval_count"], $posted_data);

        $token = 'Bearer '.$api_key;

        

        $body = json_encode([
            "name"=>$name,
            "oid"=>$uniqueplanid,
            "currency"=>$currency,
            "amount"=>$amount,
            "interval"=>$interval,
            "interval_count"=>$interval_count
        ]);

       
        
        $url = "https://api.baremetrics.com/v1/". $data['sourceid']."/plans";


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


function awp_baremetrics_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('baremetrics');
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
