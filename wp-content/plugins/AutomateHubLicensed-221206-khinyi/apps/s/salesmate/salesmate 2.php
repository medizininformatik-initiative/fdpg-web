<?php
class AWP_Salesmate extends appfactory
{
    public function init_actions(){
        add_action('admin_post_awp_salesmate_save_api_token', [$this, 'awp_save_salesmate_api_token'], 10, 0);
        add_action( 'wp_ajax_awp_fetch_activitytype', [$this, 'awp_fetch_activitytype']);
    }

    public function init_filters(){
        add_filter('awp_platforms_connections', [$this, 'awp_salesmate_platform_connection'], 10, 1);
    }

    public function action_provider( $providers ) {
        $providers['salesmate'] = array(
            'title' => esc_html__( 'Salesmate', 'automate_hub' ),
            'tasks' => array(
                'createactivity'  => esc_html__( 'Add Activity', 'automate_hub' ),
                'createcompany'   => esc_html__( 'Create Company', 'automate_hub' ),
                'createcontact'   => esc_html__( 'Create Contact', 'automate_hub' )
            )
        );
        return  $providers;
    }

    public function settings_tab( $tabs ) {
        $tabs['salesmate'] = array('name'=>esc_html__( 'Salesmate', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'salesmate') {
            return;
        }
        $nonce = wp_create_nonce("awp_salesmate_settings");
        $api_token = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/salesmate" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/salesmate.png" width="396" height="50" alt="Salesmate Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?>

                <br />
                <?php 

$form_fields = '';
$app_name= 'salesmate';
$salesmate_form = new AWP_Form_Fields($app_name);

$form_fields = $salesmate_form->awp_wp_text_input(
    array(
        'id'            => "awp_salesmate_display_name",
        'name'          => "awp_salesmate_display_name",
        'value'         => $display_name,
        'placeholder'   =>  esc_html__('examplecompany.salesmate.io', 'automate_hub' ),
        'label'         =>  esc_html__('Company Link Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $salesmate_form->awp_wp_text_input(
    array(
        'id'            => "awp_salesmate_api_token",
        'name'          => "awp_salesmate_api_token",
        'value'         => $api_token,
        'placeholder'   => esc_html__( 'Enter your Salesmate Session Key', 'automate_hub' ),
        'label'         =>  esc_html__( 'Salesmate Session Key', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $salesmate_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_salesmate_save_api_token',
    )
);


$form_fields .= $salesmate_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $salesmate_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$salesmate_form->render($form_fields);

?>
        </div>

        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                 $data=[
                    'table-cols'=>['account_name'=>'Company Name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('salesmate');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();

                ?>
                        </form>
                </div>
        <?php
    }

    public function awp_save_salesmate_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_salesmate_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_token = sanitize_text_field($_POST["awp_salesmate_api_token"]);
        $display_name     = sanitize_text_field( $_POST["awp_salesmate_display_name"] );

        if ($display_name != "") {
            $displayname = '/\A[a-zA-Z0-9][a-zA-Z0-9\-]*\.salesmate\.io\z/';
            if (preg_match($displayname, $display_name) == 1) {
                // Save tokens
                $platform_obj = new AWP_Platform_Shell_Table('salesmate');
                $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_token]);
                wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=salesmate'));
            } else {

                wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=salesmate'));
                exit();
            }
        } else {
            wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=salesmate'));
            exit();
        }

        
    } 
    
    public function load_custom_script() {
        wp_enqueue_script( 'awp-salesmate-script', AWP_URL . '/apps/s/salesmate/salesmate.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="salesmate-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'salesmate',
                           'app_name'=>'Salesmate ',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/salesmate.png',
                           'app_icon_alter_text'=>'Salesmate  Icon',
                           'account_select_onchange'=>'getactivitytype',
                           'tasks'=>array(
                                'createactivity'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Activity Type',
                                            'type'=>'select',
                                            'name'=>"activitytypeselected   ",
                                            'required' => 'required',
                                            'onchange' => 'selectedactivity',
                                            'option_for_loop'=>'(item) in data.activitytype',
                                            'option_for_value'=>'item.name',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Activity...',
                                            'spinner'=>array(
                                                    'bind-class'=>"{'is-active': accountLoading}",
                                                )
                                        ),

                                    ),
                                )
                                
                            ),
                    ); 

                        require (AWP_VIEWS.'/awp_app_integration_format.php');
                ?>
            </script>
        <?php
    }

    
   
    public function awp_fetch_activitytype() {
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
        $platform_obj= new AWP_Platform_Shell_Table('salesmate');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $linkname = $data->account_name;
        $api_key =$data->api_key;

        $url = "https://".$linkname."/apis/v1/activityTypes/";
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'accessToken' => $api_key,
                'x-linkname' => $linkname
            )
        );

        $response = wp_remote_get( $url, $args );
        $body = wp_remote_retrieve_body( $response );
        
        $body = json_decode( $body, true );
        wp_send_json_success($body['Data']['values']);
    }



};


$Awp_Salesmate = new AWP_Salesmate();

function awp_salesmate_save_integration() {
    Appfactory::save_integration();
}

function awp_salesmate_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 



    $platform_obj= new AWP_Platform_Shell_Table('salesmate');

    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $task = $decoded_data["task"]; 

    
    $linkname = $temp->account_name;
    $owner = 1;

    
   

    if( $task == "createactivity" ) {

        $title = empty( $data["title"] ) ? "" : awp_get_parsed_values($data["title"], $posted_data);
        $description = empty( $data["description"] ) ? "" : awp_get_parsed_values($data["description"], $posted_data);
        $type = empty( $data["activitytypeselected"] ) ? "" : awp_get_parsed_values($data["activitytypeselected"], $posted_data);

        $body = json_encode([
            "title"=>$title,
            "description"=>$description,
            "owner"=>$owner,
            "type"=>$type,
            "dueDate" => date("Y-m-d", time())
        ]);
        $url = "https://".$linkname."/apis/activity/v4";


        $args = [
            'headers' => array(
                'method'=>'POST',
                'Content-Type' => 'application/json',
                'accessToken' => $api_key,
                'x-linkname' => $linkname
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['sessionToken']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } else if($task == "createcompany"){

        $name = empty( $data["name"] ) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $phone = empty( $data["phone"] ) ? "" : awp_get_parsed_values($data["phone"], $posted_data);
        $description = empty( $data["description"] ) ? "" : awp_get_parsed_values($data["description"], $posted_data);


        $body = json_encode([
            "name"=>$name,
            "phone"=>$phone,
            "owner"=>$owner,
            "description"=>$description
        ]);
        $url = "https://".$linkname."/apis/company/v4";


        $args = [
            'headers' => array(
                'method'=>'POST',
                'Content-Type' => 'application/json',
                'accessToken' => $api_key,
                'x-linkname' => $linkname
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['sessionToken']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );



    } else if($task == "createcontact"){

        $lastName = empty( $data["lastName"] ) ? "" : awp_get_parsed_values($data["lastName"], $posted_data);
        $firstName = empty( $data["firstName"] ) ? "" : awp_get_parsed_values($data["firstName"], $posted_data);
        $email = empty( $data["email"] ) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $mobile = empty( $data["mobile"] ) ? "" : awp_get_parsed_values($data["mobile"], $posted_data);


        $body = json_encode([
            "lastName"=>$lastName,
            "firstName"=>$firstName,
            "email"=>$email,
            "mobile"=>$mobile
        ]);

        $url = "https://".$linkname."/apis/contact/v4";


        $args = [
            'headers' => array(
                'method'=>'POST',
                'Content-Type' => 'application/json',
                'accessToken' => $api_key,
                'x-linkname' => $linkname
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['sessionToken']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );



    }
    return $response;
}


function awp_salesmate_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];

    


    $platform_obj= new AWP_Platform_Shell_Table('salesmate');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_key=$temp->api_key;
    $linkname = $temp->account_name;
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

   
        

        $args = [
            'headers' => array(
                'method'=>'POST',
                'Content-Type' => 'application/json',
                'accessToken' => $api_key,
                'x-linkname' => $linkname
            ),

            'body'=> json_encode($body)
        ];


        $return  = wp_remote_post($url,  $args );

        $args['headers']['accessToken']="XXXXXXXXXXXX";

        awp_add_to_log( $return, $url, $args, $integration );


    $response['success']=true;    
    return $response;
}
