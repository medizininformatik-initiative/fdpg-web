<?php
class AWP_Companyhub extends appfactory
{
    public function init_actions(){      
        add_action('admin_post_awp_companyhub_save_api_token', [$this, 'awp_save_companyhub_api_token'], 10, 0);
        add_action( 'wp_ajax_awp_fetch_company', [$this, 'awp_fetch_company']);
    }

    public function init_filters(){
        add_filter('awp_platforms_connections', [$this, 'awp_companyhub_platform_connection'], 10, 1);
    }

    public function action_provider( $providers ) {
        $providers['companyhub'] = array(
            'title' => esc_html__( 'CompanyHub', 'automate_hub' ),
            'tasks' => array(
                'createcontact'   => esc_html__( 'Create Contact', 'automate_hub' ),
                'createcompany'   => esc_html__( 'Create Company', 'automate_hub' )
            )
        );
        return  $providers;
    }

    public function settings_tab( $tabs ) {
        $tabs['companyhub'] = array('name'=>esc_html__( 'CompanyHub', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'companyhub') {
            return;
        }
        $nonce = wp_create_nonce("awp_companyhub_settings");
        $api_token = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/companyhub" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/companyhub.png" width="203" height="50" alt="CompanyHub Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?>

                <br />
                <?php 

$form_fields = '';
$app_name= 'companyhub';
$companyhub_form = new AWP_Form_Fields($app_name);

$form_fields = $companyhub_form->awp_wp_text_input(
    array(
        'id'            => "awp_companyhub_display_name",
        'name'          => "awp_companyhub_display_name",
        'value'         => $display_name,
        'placeholder'   =>  esc_html__('Enter Subdomain Name', 'automate_hub' ),
        'label'         =>  esc_html__('Subdomain Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $companyhub_form->awp_wp_text_input(
    array(
        'id'            => "awp_companyhub_api_token",
        'name'          => "awp_companyhub_api_token",
        'value'         => $api_token,
        'placeholder'   => esc_html__( 'Enter your Companyhub API Key', 'automate_hub' ),
        'label'         =>  esc_html__( 'Companyhub API Key', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $companyhub_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_companyhub_save_api_token',
    )
);


$form_fields .= $companyhub_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $companyhub_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$companyhub_form->render($form_fields);

?>

        </div>

        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                 $data=[
                    'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('companyhub');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();

                ?>
                        </form>
                </div>
        <?php
    }

    public function awp_save_companyhub_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_companyhub_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_token = sanitize_text_field($_POST["awp_companyhub_api_token"]);
        $display_name     = sanitize_text_field( $_POST["awp_companyhub_display_name"] );

        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('companyhub');
        $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_token]);

        AWP_redirect("admin.php?page=automate_hub&tab=companyhub");
    } 
    
    public function load_custom_script() {
        wp_enqueue_script( 'awp-companyhub-script', AWP_URL . '/apps/c/companyhub/companyhub.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="companyhub-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'companyhub',
                           'app_name'=>'Companyhub ',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/companyhub.png',
                           'app_icon_alter_text'=>'Companyhub  Icon',
                           'account_select_onchange'=>'getCompany',
                           'tasks'=>array(
                                'createcontact'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Company',
                                            'type'=>'select',
                                            'name'=>"source_id",
                                            'required' => 'required',
                                            'onchange' => 'selectedcompany',
                                            'option_for_loop'=>'(item) in data.companyList',
                                            'option_for_value'=>'item.ID',
                                            'option_for_text'=>'{{item.Name}}',
                                            'select_default'=>'Select Company...',
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
    
    public function awp_fetch_company() {
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
        $platform_obj= new AWP_Platform_Shell_Table('companyhub');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }
        $api_key =$data->api_key;
        $domainname = $data->account_name;
        $url = "https://api.companyhub.com/v1/tables/company";
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $domainname.' '.$api_key
            )
        );
        $response = wp_remote_get( $url, $args );
        $body = wp_remote_retrieve_body( $response );
        $body = json_decode( $body, true );
        wp_send_json_success($body["Data"]);
    }
};

$Awp_Companyhub = new AWP_Companyhub();
function awp_companyhub_save_integration() {
    Appfactory::save_integration();
}

function awp_companyhub_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 
    $platform_obj= new AWP_Platform_Shell_Table('companyhub');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $task = $decoded_data["task"]; 
    $domainname = $temp->account_name;
    if( $task == "createcontact" ) {
        $FirstName = empty( $data["FirstName"] ) ? "" : awp_get_parsed_values($data["FirstName"], $posted_data);
        $LastName = empty( $data["LastName"] ) ? "" : awp_get_parsed_values($data["LastName"], $posted_data);
        $email = empty( $data["Email"] ) ? "" : awp_get_parsed_values($data["Email"], $posted_data);
        $companyid = empty( $data["companyid"] ) ? "" : awp_get_parsed_values($data["companyid"], $posted_data);
        $token = $domainname.' '.$api_key;
        $body = json_encode([
            "FirstName"=>$FirstName,
            "LastName"=>$LastName,
            "Email"=>$email,
            "Company"=>$companyid
        ]);
        $url = "https://api.companyhub.com/v1/tables/contact";
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
    } else if($task == "createcompany"){
        $Name = empty( $data["Name"] ) ? "" : awp_get_parsed_values($data["Name"], $posted_data);
        $Phone = empty( $data["Phone"] ) ? "" : awp_get_parsed_values($data["Phone"], $posted_data);
        $Description = empty( $data["Description"] ) ? "" : awp_get_parsed_values($data["Description"], $posted_data);
        $token = $domainname.' '.$api_key;
        $body = json_encode([
            "Name"=>$Name,
            "Phone"=>$Phone,
            "Description"=>$Description,
        ]);
        $url = "https://api.companyhub.com/v1/tables/company";
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

function awp_companyhub_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('companyhub');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_key=$temp->api_key;
    $domainname = $temp->account_name;
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
                'Authorization' => $domainname.' '.$token
            ),
            'body'=> json_encode($body)
        ];
        $return  = wp_remote_post($campfireUrl,  $args );
        $args['headers']['Authorization']="XXXXXXXXXXXX";
        awp_add_to_log( $return, $campfireUrl, $args, $integration );
    $response['success']=true;    
    return $response;
}