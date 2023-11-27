<?php

class AWP_Callrail extends appfactory
{

   
    public function init_actions(){
       
        add_action('admin_post_awp_callrail_save_api_token', [$this, 'awp_save_callrail_api_token'], 10, 0);
        
        add_action( 'wp_ajax_awp_get_account', [$this, 'awp_get_account']);
        add_action( 'wp_ajax_awp_fetch_companylist', [$this, 'awp_fetch_companylist']);
        add_action( 'wp_ajax_awp_get_account_for_functions  ', [$this, 'awp_get_account_for_functions ']);
        
    }

    public function init_filters(){

        add_filter('awp_platforms_connections', [$this, 'awp_callrail_platform_connection'], 10, 1);
    }

    public function action_provider( $providers ) {
        $providers['callrail'] = array(
            'title' => esc_html__( 'Callrail', 'automate_hub' ),
            'tasks' => array(
                'createuser'   => esc_html__( 'Create User', 'automate_hub' ),
                'createtag'   => esc_html__( 'Create Tag', 'automate_hub' ),
                'createcompany'   => esc_html__( 'Create Company', 'automate_hub' ),
                'createoutboundcallerid'   => esc_html__( 'Create Outbound Caller ID', 'automate_hub' )
            )
        );
    
        return  $providers;
    }

    public function settings_tab( $tabs ) {
        $tabs['callrail'] = array('name'=>esc_html__( 'Callrail', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'callrail') {
            return;
        }
        $nonce = wp_create_nonce("awp_callrail_settings");
        $api_token = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/callrail" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/callrail.png" height="50" alt="Callrail Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?>

                <br />

                <?php 

$form_fields = '';
$app_name= 'callrail';
$callrail_form = new AWP_Form_Fields($app_name);

$form_fields = $callrail_form->awp_wp_text_input(
    array(
        'id'            => "awp_callrail_display_name",
        'name'          => "awp_callrail_display_name",
        'value'         => $display_name,
        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $callrail_form->awp_wp_text_input(
    array(
        'id'            => "awp_callrail_api_token",
        'name'          => "awp_callrail_api_token",
        'value'         => $api_key,
        'placeholder'   => esc_html__( 'Enter your Callrail API Key', 'automate_hub' ),
        'label'         =>  esc_html__( 'Callrail API Key', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $callrail_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_callrail_save_api_token',
    )
);


$form_fields .= $callrail_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $callrail_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$callrail_form->render($form_fields);

?>




        </div>

        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                 $data=[
                    'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('callrail');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();

                ?>
                        </form>
                </div>
        <?php
    }

    public function awp_save_callrail_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_callrail_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_token = sanitize_text_field($_POST["awp_callrail_api_token"]);
        $display_name     = sanitize_text_field( $_POST["awp_callrail_display_name"] );

        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('callrail');
        $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_token]);

        AWP_redirect("admin.php?page=automate_hub&tab=callrail");
    } 
    
    public function load_custom_script() {
        wp_enqueue_script( 'awp-callrail-script', AWP_URL . '/apps/c/callrail/callrail.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="callrail-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'callrail',
                           'app_name'=>'Callrail ',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/callrail.png',
                           'app_icon_alter_text'=>'Callrail  Icon',
                           'account_select_onchange'=>'getcompanyaccountlist',
                           'tasks'=>array(
                                'createuser'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Account',
                                            'type'=>'select',
                                            'name'=>"account_id",
                                            'required' => 'required',
                                            'onchange' => 'get_companies',
                                            'option_for_loop'=>'(item) in data.accountlists',
                                            'option_for_value'=>'item.numeric_id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Account...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': accountLoading}",
                                                        )
                                        ),
                                        array(
                                            'label'=>'Select Company',
                                            'type'=>'select',
                                            'name'=>"company",
                                            'required' => 'required',
                                            'onchange' => 'getrole',
                                            'option_for_loop'=>'(item) in data.companylist',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Company...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': accountLoading}",
                                                        )
                                        ),
                                        array(
                                            'label'=>'Select Role',
                                            'type'=>'select',
                                            'name'=>"roleid",
                                            'required' => 'required',
                                            'onchange' => 'selectedrole',
                                            'option_for_loop'=>'(item) in data.role',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Role...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': accountLoading}",
                                                        )
                                        )
                                    ),
                                ),
                                'createoutboundcallerid'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Account',
                                            'type'=>'select',
                                            'name'=>"account_id",
                                            'required' => 'required',
                                            'onchange' => 'get_companies',
                                            'option_for_loop'=>'(item) in data.accountlists',
                                            'option_for_value'=>'item.numeric_id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Account...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': accountLoading}",
                                                        )
                                        ),
                                        array(
                                            'label'=>'Select Company',
                                            'type'=>'select',
                                            'name'=>"company",
                                            'required' => 'required',
                                            'onchange' => 'getrole',
                                            'option_for_loop'=>'(item) in data.companylist',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Company...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': accountLoading}",
                                                        )
                                        )
                                    ),
                                ),
                                'createtag'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Account',
                                            'type'=>'select',
                                            'name'=>"account_id",
                                            'required' => 'required',
                                            'onchange' => 'get_companies',
                                            'option_for_loop'=>'(item) in data.accountlists',
                                            'option_for_value'=>'item.numeric_id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Account...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': accountLoading}",
                                                        )
                                        ),
                                        array(
                                            'label'=>'Select Company',
                                            'type'=>'select',
                                            'name'=>"company",
                                            'required' => 'required',
                                            'onchange' => 'getrole',
                                            'option_for_loop'=>'(item) in data.companylist',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Company...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': accountLoading}",
                                                        )
                                        )
                                    ),
                                ),
                                'createcompany'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Account',
                                            'type'=>'select',
                                            'name'=>"account_id",
                                            'required' => 'required',
                                            'onchange' => 'get_companies',
                                            'option_for_loop'=>'(item) in data.accountlists',
                                            'option_for_value'=>'item.numeric_id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Account...',
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

    public function awp_get_account() {
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
        $platform_obj= new AWP_Platform_Shell_Table('callrail');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;

        $url = "https://api.callrail.com/v3/a.json";
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Token '.$api_key
            )
        );

        $response = wp_remote_get( $url, $args );
        $body = wp_remote_retrieve_body( $response );
        
        $body = json_decode( $body, true );

        wp_send_json_success($body['accounts']);
    }

    public function awp_fetch_companylist() {
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
        $platform_obj= new AWP_Platform_Shell_Table('callrail');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;

        $account_id = $this->awp_get_account_for_functions($api_key);

        $url = "https://api.callrail.com/v3/a/".$account_id."/companies.json";
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Token '.$api_key
            )
        );

        $response = wp_remote_get( $url, $args );
        $body = wp_remote_retrieve_body( $response );
        
        $body = json_decode( $body, true );

        wp_send_json_success($body['companies']);
    }

    public static function awp_get_account_for_functions($api_key) {

        $url = "https://api.callrail.com/v3/a.json";
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Token '.$api_key
            )
        );

        $response = wp_remote_get( $url, $args );
        $body = wp_remote_retrieve_body( $response );
        
        $body = json_decode( $body, true );

        return $body['accounts'][0]['numeric_id'];
    }

};


$Awp_Callrail = new AWP_Callrail();

function awp_callrail_save_integration() {
    Appfactory::save_integration();
}

function awp_callrail_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('callrail');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $task = $decoded_data["task"]; 
    $account_id = Awp_Callrail::awp_get_account_for_functions($api_key);;

    if( $task == "createuser" ) {

        $first_name = empty( $data["first_name"] ) ? "" : awp_get_parsed_values($data["first_name"], $posted_data);
        $last_name = empty( $data["last_name"] ) ? "" : awp_get_parsed_values($data["last_name"], $posted_data);
        $email = empty( $data["email"] ) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $company_id = empty( $data["company_id"] ) ? "" : awp_get_parsed_values($data["company_id"], $posted_data);
        $roleid = empty( $data["roleid"] ) ? "" : awp_get_parsed_values($data["roleid"], $posted_data);

        $token = 'Token '.$api_key;

        $body = json_encode([
            "first_name"=>$first_name,
            "last_name"=>$last_name,
            "email"=>$email,
            "company_id"=>[$company_id],
            "role"=> $roleid
        ]);

        $url = "https://api.callrail.com/v3/a/".$account_id."/users.json";

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

    }else if( $task == "createoutboundcallerid" ) {

        $phone_number = empty( $data["phone_number"] ) ? "" : awp_get_parsed_values($data["phone_number"], $posted_data);
        $name = empty( $data["name"] ) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $company_id = empty( $data["company_id"] ) ? "" : awp_get_parsed_values($data["company_id"], $posted_data);

        $token = 'Token '.$api_key;

        $body = json_encode([
            "phone_number"=>$phone_number,
            "name"=>$name,
            "company_id"=>[$company_id]
        ]);

        $url = "https://api.callrail.com/v3/a/".$account_id."/caller_ids.json";

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

    }else if( $task == "createtag" ) {

        $tagname = empty( $data["tagname"] ) ? "" : awp_get_parsed_values($data["tagname"], $posted_data);
        $company_id = empty( $data["company_id"] ) ? "" : awp_get_parsed_values($data["company_id"], $posted_data);

        $token = 'Token '.$api_key;

        $body = json_encode([
            "name"=>$tagname,
            "company_id"=>[$company_id]
        ]);

        $url = "https://api.callrail.com/v3/a/".$account_id."/tags.json";

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

    }else if( $task == "createcompany" ) {

        $name = empty( $data["companyname"] ) ? "" : awp_get_parsed_values($data["companyname"], $posted_data);

        $token = 'Token '.$api_key;

        $body = json_encode([
            "name"=>$name,
            "account_id"=> $account_id
        ]);

        $url = "https://api.callrail.com/v3/a/".$account_id."/companies.json";

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


function awp_callrail_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('callrail');
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

        $token = 'Token '.$api_key;
        

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
