<?php

class AWP_Wealthbox extends appfactory
{
    public function init_actions(){
        add_action('admin_post_awp_wealthbox_save_api_token', [$this, 'awp_save_wealthbox_api_token'], 10, 0);        
        add_action( 'wp_ajax_awp_fetch_source', [$this, 'awp_fetch_source']);
    }

    public function init_filters(){
        add_filter('awp_platforms_connections', [$this, 'awp_wealthbox_platform_connection'], 10, 1);
    }

    public function action_provider( $providers ) {
        $providers['wealthbox'] = array(
            'title' => esc_html__( 'Wealthbox', 'automate_hub' ),
            'tasks' => array(
                'createcontact'   => esc_html__( 'Create Contact', 'automate_hub' ),
                'createtask'   => esc_html__( 'Create Task', 'automate_hub' )
            )
        );
        return  $providers;
    }

    public function settings_tab( $tabs ) {
        $tabs['wealthbox'] = array('name'=>esc_html__( 'Wealthbox', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'wealthbox') {
            return;
        }
        $nonce = wp_create_nonce("awp_wealthbox_settings");
        $api_token = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/wealthbox" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/wealthbox.png" width="210" height="50" alt="Wealthbox CRM Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                ?>
                <br />
                <?php 

$form_fields = '';
$app_name= 'wealthbox';
$wealthbox_form = new AWP_Form_Fields($app_name);

$form_fields = $wealthbox_form->awp_wp_text_input(
    array(
        'id'            => "awp_wealthbox_display_name",
        'name'          => "awp_wealthbox_display_name",
        'value'         => $display_name,
        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $wealthbox_form->awp_wp_text_input(
    array(
        'id'            => "awp_wealthbox_api_token",
        'name'          => "awp_wealthbox_api_token",
        'value'         => $api_token,
        'placeholder'   => esc_html__( 'Enter your Wealthbox API Key', 'automate_hub' ),
        'label'         =>  esc_html__( 'Wealthbox API Key', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $wealthbox_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_wealthbox_save_api_token',
    )
);


$form_fields .= $wealthbox_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $wealthbox_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$wealthbox_form->render($form_fields);

?>
        </div>
        <div class="wrap">
                <form id="form-list" method="post">
                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                 $data=[
                    'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('wealthbox');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();

                ?>
                        </form>
                </div>
        <?php
    }

    public function awp_save_wealthbox_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_wealthbox_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_token = sanitize_text_field($_POST["awp_wealthbox_api_token"]);
        $display_name     = sanitize_text_field( $_POST["awp_wealthbox_display_name"] );

        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('wealthbox');
        $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_token]);

        AWP_redirect("admin.php?page=automate_hub&tab=wealthbox");
    } 
    
    public function load_custom_script() {
        wp_enqueue_script( 'awp-wealthbox-script', AWP_URL . '/apps/w/wealthbox/wealthbox.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="wealthbox-action-template">
                <?php

                    $app_data=array(
                          'app_slug'=>'wealthbox',
                          'app_name'=>'Wealthbox ',
                          'app_icon_url'=>AWP_ASSETS.'/images/icons/wealthbox.png',
                          'app_icon_alter_text'=>'Wealthbox  Icon',
                          'account_select_onchange'=>'gettypelist',
                          'tasks'=>array(
                                'createcontact'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Type',
                                            'type'=>'select',
                                            'name'=>"typeid",
                                            'required' => 'required',
                                            'onchange' => 'selectedtypeid',
                                            'option_for_loop'=>'(item) in data.typelist',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Type...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': typeLoading}",
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
        $platform_obj= new AWP_Platform_Shell_Table('wealthbox');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;

        $url = "https://api.wealthbox.com/v1/sources";
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


$Awp_Wealthbox = new AWP_Wealthbox();

function awp_wealthbox_save_integration() {
    Appfactory::save_integration();
}

function awp_wealthbox_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('wealthbox');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $task = $decoded_data["task"]; 

   

    if( $task == "createcontact" ) {

        $prefix = empty( $data["salutation"] ) ? "" : awp_get_parsed_values($data["salutation"], $posted_data);
        $first_name = empty( $data["first_name"] ) ? "" : awp_get_parsed_values($data["first_name"], $posted_data);
        $last_name = empty( $data["last_name"] ) ? "" : awp_get_parsed_values($data["last_name"], $posted_data);
        $birth_date = empty( $data["birth_date"] ) ? "" : awp_get_parsed_values($data["birth_date"], $posted_data);
        $job_title = empty( $data["job_title"] ) ? "" : awp_get_parsed_values($data["job_title"], $posted_data);
        $type = empty( $data["typeid"] ) ? "" : awp_get_parsed_values($data["typeid"], $posted_data);

        $body = json_encode([
            "prefix"=>$prefix,
            "first_name"=>$first_name,
            "last_name"=>$last_name,
            "birth_date"=>date("Y-m-d", $birth_date),
            "job_title"=>$job_title,
            "type"=>$type
        ]);

        $url = "https://api.crmworkspace.com/v1/contacts";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'ACCESS_TOKEN' => $api_key
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['ACCESS_TOKEN']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    }else if( $task == "createtask" ) {

        $name = empty( $data["name"] ) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $due_date = empty( $data["due_date"] ) ? "" : awp_get_parsed_values($data["due_date"], $posted_data);
        $description = empty( $data["description"] ) ? "" : awp_get_parsed_values($data["description"], $posted_data);
        
        $body = json_encode([
            "name"=>$name,
            "due_date"=>date("Y-m-d", $due_date),
            "description"=>$description,
            "complete"=>false
        ]);

        $url = "https://api.crmworkspace.com/v1/tasks";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'ACCESS_TOKEN' => $api_key
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['ACCESS_TOKEN']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    }
    return $response;
}


function awp_wealthbox_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('wealthbox');
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

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'ACCESS_TOKEN' => $api_key
            ),

            'body'=> json_encode($body)
        ];


        $return  = wp_remote_post($url,  $args );

        $args['headers']['ACCESS_TOKEN']="XXXXXXXXXXXX";
        awp_add_to_log( $return, $url, $args, $integration );

    $response['success']=true;    
    return $response;
}
