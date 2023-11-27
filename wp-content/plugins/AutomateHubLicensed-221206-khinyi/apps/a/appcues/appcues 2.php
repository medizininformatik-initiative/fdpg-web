<?php

class AWP_Appcues extends appfactory
{

   
    public function init_actions(){
       
        add_action('admin_post_awp_appcues_save_api_token', [$this, 'awp_save_appcues_api_token'], 10, 0);
        
        add_action( 'wp_ajax_awp_fetch_source', [$this, 'awp_fetch_source']);
        
    }

    public function init_filters(){

        add_filter('awp_platforms_connections', [$this, 'awp_appcues_platform_connection'], 10, 1);
    }

    public function action_provider( $providers ) {
        $providers['appcues'] = array(
            'title' => esc_html__( 'Appcues', 'automate_hub' ),
            'tasks' => array(
                'createsegment'   => esc_html__( 'Create Segment', 'automate_hub' )
            )
        );
    
        return  $providers;
    }

    public function settings_tab( $tabs ) {
        $tabs['appcues'] = array('name'=>esc_html__( 'Appcues', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'appcues') {
            return;
        }
        $nonce = wp_create_nonce("awp_appcues_settings");
        $api_token = isset($_GET['api_key']) ? sanitize_text_field($_GET['api_key']) : "";
        $api_secret = isset($_GET['api_secret']) ? sanitize_text_field($_GET['api_secret']) : "";
        $account_id = isset($_GET['client_id']) ? sanitize_text_field($_GET['client_id']) : "";
        $id = isset($_GET['id']) ? intval( sanitize_text_field($_GET['id'] )) : "";
        $display_name     = isset($_GET['account_name']) ? sanitize_text_field($_GET['account_name']) : "";
        ?>
        <div class="platformheader">

            <a href="https://sperse.io/go/appcues" target="_blank"><img src="<?php echo esc_url(AWP_ASSETS.'/images/logos/appcues.png'); ?>"  height="50" alt="Appcues Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager(sanitize_text_field($_GET['tab']));
                    $instruction_obj->prepare_instructions();
                        
                ?>

                <br />

                <?php 

                $form_fields = '';
                $app_name= 'appcues';
                $appcues_form = new AWP_Form_Fields($app_name);

                $form_fields = $appcues_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_appcues_display_name",
                        'name'          => "awp_appcues_display_name",
                        'value'         => $display_name,
                        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
                        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $appcues_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_appcues_api_token",
                        'name'          => "awp_appcues_api_token",
                        'value'         => $api_token,
                        'placeholder'   => esc_html__( 'Enter your Appcues Live API Key', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Appcues API Key', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );
                $form_fields .= $appcues_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_appcues_api_secret",
                        'name'          => "awp_appcues_api_secret",
                        'value'         => $api_secret,
                        'placeholder'   => esc_html__( 'Enter your Appcues API Secret', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Appcues API Secret', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $appcues_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_appcues_account_id",
                        'name'          => "awp_appcues_account_id",
                        'value'         => $account_id,
                        'placeholder'   => esc_html__( 'Enter your Appcues Account ID', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Appcues Account ID', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $appcues_form->awp_wp_hidden_input(
                    array(
                        'name'          => "action",
                        'value'         => 'awp_appcues_save_api_token',
                    )
                );


                $form_fields .= $appcues_form->awp_wp_hidden_input(
                    array(
                        'name'          => "_nonce",
                        'value'         =>$nonce,
                    )
                );
                $form_fields .= $appcues_form->awp_wp_hidden_input(
                    array(
                        'name'          => "id",
                        'value'         =>wp_unslash($id),
                    )
                );


                $appcues_form->render($form_fields);

                ?>


        </div>

        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                 $data=[
                    'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('appcues');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();

                ?>
                        </form>
                </div>
        <?php
    }

    public function awp_save_appcues_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_appcues_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_token = sanitize_text_field($_POST["awp_appcues_api_token"]);
        $api_secret = sanitize_text_field($_POST["awp_appcues_api_secret"]);
        $account_id = sanitize_text_field($_POST["awp_appcues_account_id"]);
        $display_name     = sanitize_text_field( $_POST["awp_appcues_display_name"] );

        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('appcues');
        $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_token, 'client_secret' => $api_secret, 'client_id' => $account_id]);

        AWP_redirect("admin.php?page=automate_hub&tab=appcues");
    } 
    
    public function load_custom_script() {
        wp_enqueue_script( 'awp-appcues-script', AWP_URL . '/apps/a/appcues/appcues.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="appcues-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'appcues',
                           'app_name'=>'Appcues ',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/appcues.png',
                           'app_icon_alter_text'=>'Appcues  Icon',
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
        $platform_obj= new AWP_Platform_Shell_Table('appcues');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;

        $url = "https://api.appcues.com/v1/sources";
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


$Awp_Appcues = new AWP_Appcues();

function awp_appcues_save_integration() {
    Appfactory::save_integration();
}

function awp_appcues_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('appcues');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $api_secret=$temp->client_secret;
    $api_account_id=$temp->client_id;
    $task = $decoded_data["task"]; 

   

    if( $task == "createsegment" ) {

        $name = empty( $data["name"] ) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $description = empty( $data["description"] ) ? "" : awp_get_parsed_values($data["description"], $posted_data);
        

        $token = 'Basic '.base64_encode($api_key.":".$api_secret);

        $body = json_encode([
            "name"=>$name,
            "description"=>$description
        ]);

        $url = "https://api.appcues.com/v2/accounts/". $api_account_id."/segments";

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


function awp_appcues_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('appcues');
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
