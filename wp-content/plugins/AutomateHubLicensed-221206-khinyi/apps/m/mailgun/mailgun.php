<?php

class AWP_Mailgun extends appfactory  {
    public static $url = "https://api.mailgun.net/v3";

    public function init_actions(){
        add_action( 'admin_post_awp_mailgun_save_api_key'  , [ $this, 'save_api_key'], 10, 0 );
        add_action( 'wp_ajax_awp_fetch_mail_lists', [$this, 'fetch_mail_lists']);
    }
    
    public function init_filters(){}

    public function settings_tab( $tabs ) {
        $tabs['mailgun'] = array('name'=>esc_html__( 'Mailgun', 'automate_hub'), 'cat'=>array('crm'));
        return $tabs;
    }

    public function load_custom_script() {
        wp_enqueue_script( 'awp-mailgun-script', AWP_URL.'/apps/m/mailgun/mailgun.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function save_api_key() {
        if ( ! current_user_can('administrator') ){
            die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
        }

        // Security Check
        if (! wp_verify_nonce( $_POST['_nonce'], 'awp_mailgun_api_key' ) ) {
            die( esc_html__( 'Security check Failed', 'automate_hub' ) );
        }

        $api_key = sanitize_text_field( $_POST["awp_mailgun_api_key"] );
        $display_name     = sanitize_text_field( $_POST["awp_mailgun_display_name"] );
        // Save tokens
        $platform_obj= new AWP_Platform_Shell_Table('mailgun');
        $platform_obj->save_platform(['account_name'=>$display_name,'api_key'=>$api_key]);
        AWP_redirect( "admin.php?page=automate_hub&tab=mailgun" ); 
    }

    public function action_provider( $providers ) {
        $providers['mailgun'] = [
            'title' => __( 'Mailgun', 'automate_hub' ),
            'tasks' => array(
                'subscribe_to_list'   => __( 'Subscribe To List', 'automate_hub' )
                )
            ];

        return  $providers;
    }

    public function fetch_mail_lists() {
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
        $platform_obj= new AWP_Platform_Shell_Table('mailgun');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }
        $api_key =$data->api_key;

        $url = self::$url.'/lists/pages';
        $auth = 'Basic ' . base64_encode("api:".$api_key);

        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $auth,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
                )
            );

        $response = wp_remote_get( $url, $args );        
        $body = wp_remote_retrieve_body( $response );
        $body = json_decode( $body );
        wp_send_json_success($body);
    }

    public function settings_view( $current_tab ) {
        
        if( $current_tab != 'mailgun' ) { return; }

        $nonce = wp_create_nonce( "awp_mailgun_api_key" );
        $api_key = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";

        ?>
            <div class="no-platformheader">
                <a href="https://sperse.io/go/mailgun" target="_blank"><img src="<?php echo AWP_ASSETS; ?>/images/logos/mailgun.png" width="180" height="50" alt="Mailgun Logo"></a><br/><br/>
                <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?>
                <br/>
                <?php 

                $form_fields = '';
                $app_name= 'mailgun';
                $mailgun_form = new AWP_Form_Fields($app_name);

                $form_fields = $mailgun_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_mailgun_display_name",
                        'name'          => "awp_mailgun_display_name",
                        'value'         => $display_name,
                        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
                        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $mailgun_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_mailgun_api_key",
                        'name'          => "awp_mailgun_api_key",
                        'value'         => $api_key,
                        'placeholder'   => esc_html__( 'Enter your Mailgun API Key', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Mailgun API Key', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $mailgun_form->awp_wp_hidden_input(
                    array(
                        'name'          => "action",
                        'value'         => 'awp_mailgun_save_api_key',
                    )
                );


                $form_fields .= $mailgun_form->awp_wp_hidden_input(
                    array(
                        'name'          => "_nonce",
                        'value'         =>$nonce,
                    )
                );
                $form_fields .= $mailgun_form->awp_wp_hidden_input(
                    array(
                        'name'          => "id",
                        'value'         =>wp_unslash($id),
                    )
                );


                $mailgun_form->render($form_fields);

                ?>
            </div>

            <div class="wrap">
                    <form id="form-list" method="post">
                        
             
                        <input type="hidden" name="page" value="automate_hub"/>

                        <?php
                        $data=[
                            'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                        ];
                        $platform_obj= new AWP_Platform_Shell_Table('mailgun');
                        $platform_obj->initiate_table($data);
                        $platform_obj->prepare_items();
                        $platform_obj->display_table();
                        
                        ?>
                    </form>
            </div>
        <?php
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="mailgun-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'mailgun',
                           'app_name'=>'Mailgun',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/mailgun.png',
                           'app_icon_alter_text'=>'Mailgun Icon',
                           'account_select_onchange'=>'getMailLists',
                           'tasks'=>array(
                                        'subscribe_to_list'=>array(
                                                            'task_assignments'=>array(

                                                                                    array(
                                                                                        'label'=>'Select Mail List',
                                                                                        'type'=>'select',
                                                                                        'name'=>"list",
                                                                                        
                                                                                        'required'=>'required',
                                                                                        'onchange'=>'onselect',
                                                                                        'select_default'=>'Select Mail List...',
                                                                                        'option_for_loop'=>'(item) of mailingLists',
                                                                                        'option_for_value'=>'item.address',
                                                                                        'option_for_text'=>'{{item.name }}',
                                                                                        'spinner'=>array(
                                                                                                        'bind-class'=>"{'is-active': listLoading}",
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
}

$Awp_Mailgun = AWP_Mailgun::get_instance();

function awp_mailgun_save_integration() {
    Appfactory::save_integration();
}

function awp_mailgun_send_data( $record, $posted_data ) {
    $temp = json_decode(($record["data"]), true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('mailgun');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_key=$temp->api_key;

    $decoded_data = AWP_Mailgun::decode_data($record, $posted_data);

    $task = $decoded_data["task"]; 
    $data = $decoded_data["data"]; 

    $api_key = 'Basic ' . base64_encode("api:".$api_key);
    $mailListAddress = $data["mailListAddress"];

    if( $task == "subscribe_to_list" ) {
        $name = empty( $data["name"] ) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $email = empty( $data["email"] ) ? "" : awp_get_parsed_values($data["email"], $posted_data);

        $args = [
            'headers' => array(
                'Authorization' => $api_key,
            ),
            'body'=> [
                'address' => $email,
                'subscribed' => true,
                "name" =>  $name
            ]
        ];

        $endpoint = sprintf("%s/lists/%s/members", AWP_Mailgun::$url, $mailListAddress);

        $response  = wp_remote_post($endpoint,  $args );
        $args['headers']['Authorization']="Basic XXXXX";
        awp_add_to_log( $response, $endpoint, $args, $record );
    }
    return $response;
}



function awp_mailgun_resend_data( $log_id,$data,$integration ) {

    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];

    $platform_obj= new AWP_Platform_Shell_Table('mailgun');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_key=$temp->api_key;
    if(!$api_key ) {
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
    

    $api_key = 'Basic ' . base64_encode("api:".$api_key);

        $args = [
            'headers' => array(
                'Authorization' => $api_key,
            ),
            'body'=> $body,
        ];



        $res  = wp_remote_post($url,  $args );
    $args['headers']['Authorization']="Basic XXXXX";
        awp_add_to_log( $res, $url, $args, $integration );

    $response['success']=true;    
    return $response;
}
