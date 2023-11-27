<?php

class AWP_Vbout extends appfactory
{

   
    public function init_actions(){
        
        add_action( 'wp_ajax_awp_fetch_list', [$this, 'awp_fetch_list']);
        add_action('admin_post_awp_vbout_save_api_token', [$this, 'awp_save_vbout_api_token'], 10, 0);
        
    }

    public function init_filters(){

        add_filter('awp_platforms_connections', [$this, 'awp_vbout_platform_connection'], 10, 1);
    }

    public function action_provider( $providers ) {
        $providers['vbout'] = array(
            'title' => esc_html__( 'Vbout', 'automate_hub' ),
            'tasks' => array(
                'addcampaign'   => esc_html__( 'Add Campaign', 'automate_hub' ),
                'addcontact'   => esc_html__( 'Add Contact', 'automate_hub' )
            )
        );
    
        return  $providers;
    }

    public function settings_tab( $tabs ) {
        $tabs['vbout'] = array('name'=>esc_html__( 'Vbout', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'vbout') {
            return;
        }
        $nonce = wp_create_nonce("awp_vbout_settings");
        $api_token = isset($_GET['api_key']) ? sanitize_text_field($_GET['api_key']) : "";
        $id = isset($_GET['id']) ? intval( sanitize_text_field($_GET['id'] )) : "";
        $display_name     = isset($_GET['account_name']) ? sanitize_text_field($_GET['account_name']) : "";
        ?>
        <div class="platformheader">

            <a href="https://sperse.io/go/vbout" target="_blank"><img src="<?php echo esc_url(AWP_ASSETS_REMOTE.'/images/logos/vbout.png'); ?>" height="50" alt="Vbout Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager(sanitize_text_field($_GET['tab']));
                    $instruction_obj->prepare_instructions();
                        
                ?>

                <br />

                <?php 

                $form_fields = '';
                $app_name= 'vbout';
                $vbout_form = new AWP_Form_Fields($app_name);

                $form_fields = $vbout_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_vbout_display_name",
                        'name'          => "awp_vbout_display_name",
                        'value'         => $display_name,
                        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
                        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $vbout_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_vbout_api_token",
                        'name'          => "awp_vbout_api_token",
                        'value'         => $api_token,
                        'placeholder'   => esc_html__( 'Enter your Vbout Live API Key', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Vbout API Key', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $vbout_form->awp_wp_hidden_input(
                    array(
                        'name'          => "action",
                        'value'         => 'awp_vbout_save_api_token',
                    )
                );


                $form_fields .= $vbout_form->awp_wp_hidden_input(
                    array(
                        'name'          => "_nonce",
                        'value'         =>$nonce,
                    )
                );
                $form_fields .= $vbout_form->awp_wp_hidden_input(
                    array(
                        'name'          => "id",
                        'value'         =>wp_unslash($id),
                    )
                );


                $vbout_form->render($form_fields);

                ?>


        </div>

        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                 $data=[
                    'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('vbout');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();

                ?>
                        </form>
                </div>
        <?php
    }

    public function awp_save_vbout_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_vbout_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_token = sanitize_text_field($_POST["awp_vbout_api_token"]);
        $display_name     = sanitize_text_field( $_POST["awp_vbout_display_name"] );

        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('vbout');
        $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_token]);

        AWP_redirect("admin.php?page=automate_hub&tab=vbout");
    } 
    
    public function load_custom_script() {
        wp_enqueue_script( 'awp-vbout-script', AWP_URL . '/apps/v/vbout/vbout.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="vbout-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'vbout',
                           'app_name'=>'Vbout ',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/vbout.png',
                           'app_icon_alter_text'=>'Vbout  Icon',
                           'account_select_onchange'=>'gettype',
                           'tasks'=>array(
                                'addcampaign'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Campaign Type',
                                            'type'=>'select',
                                            'name'=>"campaigntype",
                                            'required' => 'required',
                                            'onchange' => 'selectedtype',
                                            'option_for_loop'=>'(item) in data.campaignList',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Campaign Type...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': campaignLoading}",
                                                        )
                                        )
                                    ),
                                ),
                                'addcontact'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Status',
                                            'type'=>'select',
                                            'name'=>"statusid",
                                            'required' => 'required',
                                            'onchange' => 'getContactList',
                                            'option_for_loop'=>'(item) in data.statusList',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Status...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': statusLoading}",
                                                        )
                                        ),
                                        array(
                                            'label'=>'Select List',
                                            'type'=>'select',
                                            'name'=>"listid",
                                            'required' => 'required',
                                            'onchange' => 'selectedList',
                                            'option_for_loop'=>'(item) in data.contactList',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select List...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': listsLoading}",
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

    public function awp_fetch_list() {
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
        $platform_obj= new AWP_Platform_Shell_Table('vbout');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;

        $url = "https://api.vbout.com/1/emailmarketing/getlists.json?key=".$api_key;
        
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            )
        );

        $response = wp_remote_get( $url, $args );
        $body = wp_remote_retrieve_body( $response );
        $body = json_decode( $body, true );
        wp_send_json_success($body["response"]["data"]["lists"]["items"]);
    }
   
};


$Awp_Vbout = new AWP_Vbout();

function awp_vbout_save_integration() {
    Appfactory::save_integration();
}

function awp_vbout_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('vbout');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $task = $decoded_data["task"]; 

   

    if( $task == "addcampaign" ) {

        $name = empty( $data["name"] ) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $Subject = empty( $data["Subject"] ) ? "" : awp_get_parsed_values($data["Subject"], $posted_data);
        $fromemail = empty( $data["fromemail"] ) ? "" : awp_get_parsed_values($data["fromemail"], $posted_data);
        $from_name = empty( $data["from_name"] ) ? "" : awp_get_parsed_values($data["from_name"], $posted_data);
        $reply_to = empty( $data["reply_to"] ) ? "" : awp_get_parsed_values($data["reply_to"], $posted_data);
        $body = empty( $data["body"] ) ? "" : awp_get_parsed_values($data["body"], $posted_data);
        $campaigntype = empty( $data["campaigntype"] ) ? "" : awp_get_parsed_values($data["campaigntype"], $posted_data);
        
        $body = json_encode([
            "name"=>$name,
            "subject"=>$Subject,
            "fromemail"=>$fromemail,
            "from_name"=>$from_name,
            "reply_to"=>$reply_to,
            "body"=>$body,
            "type"=>$campaigntype,
            "isdraft" => (integer) true
        ]);

        $url = "https://api.vbout.com/1/emailmarketing/addcampaign.json?key=".$api_key;

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $url = "https://api.vbout.com/1/emailmarketing/addcampaign.json?key=XXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } else if( $task == "addcontact" ) {

        $email = empty( $data["cemail"] ) ? "" : awp_get_parsed_values($data["cemail"], $posted_data);
        $firstname = empty( $data["firstname"] ) ? "" : awp_get_parsed_values($data["firstname"], $posted_data);
        $lastname = empty( $data["lastname"] ) ? "" : awp_get_parsed_values($data["lastname"], $posted_data);
        $city = empty( $data["city"] ) ? "" : awp_get_parsed_values($data["city"], $posted_data);
        $state = empty( $data["state"] ) ? "" : awp_get_parsed_values($data["state"], $posted_data);
        $address = empty( $data["address"] ) ? "" : awp_get_parsed_values($data["address"], $posted_data);
        $phonenumber = empty( $data["phonenumber"] ) ? "" : awp_get_parsed_values($data["phonenumber"], $posted_data);
        $listid = empty( $data["listid"] ) ? "" : awp_get_parsed_values($data["listid"], $posted_data);
        $status = empty( $data["status"] ) ? "" : awp_get_parsed_values($data["lastname"], $posted_data);
         
        $body = json_encode([
            "listid"=>$listid,
            "status"=>$status,
            "email"=>$email,
            "fields" => array(
                "firstname" => $firstname,
                "lastname" => $lastname,
                "city" => $city,
                "state" => $state,
                "address" => $address,
                "phonenumber" => $phonenumber,
            )
        ]);

        $url = "https://api.vbout.com/1/emailmarketing/addcontact.json?key=".$api_key;

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $url = "https://api.vbout.com/1/emailmarketing/addcampaign.json?key=XXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    }
    return $response;
}


function awp_vbout_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('vbout');
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
