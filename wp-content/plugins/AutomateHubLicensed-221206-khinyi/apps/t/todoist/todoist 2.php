<?php

class AWP_Todoist extends appfactory
{

    // replace with life keys
    const client_id = "aba6a28b5dcd4217aaa385eed0dbc76e";

    protected function get_redirect_uri()
    {
        return 'https://sperse.io/scripts/authorization/auth.php';
    }
   
    public function init_actions(){       
        add_action('admin_post_awp_todoist_save_api_token', [$this, 'awp_save_todoist_api_token'], 10, 0);
        add_action("rest_api_init", [$this, "create_webhook_route"]);

        add_action( 'wp_ajax_awp_fetch_projectlist', [$this, 'awp_fetch_projectlist']);
    }

    public function init_filters(){

        add_filter('awp_platforms_connections', [$this, 'awp_todoist_platform_connection'], 10, 1);
        
    }


    public function get_callback()
    {
        return get_rest_url(null,'automatehub/todoist');
    }

    public function getLoginURL(): string
    {
        $query = [
            'client_id' => self::client_id,
            'redirect_uri' => $this->get_redirect_uri(),
            'scope'=>'task:add,data:read,data:read_write,data:delete,project:delete',
            'state' => $this->get_callback(),
        ];
        $authorization_endpoint = "https://todoist.com/oauth/authorize?";

        return add_query_arg($query, $authorization_endpoint);
    }

    public function create_webhook_route()
    {
        register_rest_route('automatehub', '/todoist',
            [
                'methods' => 'GET',
                'callback' => [$this, 'get_webhook_data'],
                'permission_callback' => function () {return '';},
            ]);
    }

    public function get_webhook_data($request)
    {
        global $wpdb;

        $params = $request->get_params();

            $access_token = $params['access_token'];

            if (isset($access_token)) {
                $platform_obj = new AWP_Platform_Shell_Table('todoist');

                $query="select * from ".$wpdb->prefix."awp_platform_settings where platform_name='todoist'";

                $data=$wpdb->get_results($query);

                $len=count($data) + 1;

                $platform_obj= new AWP_Platform_Shell_Table('todoist');

                $platform_obj->save_platform(['account_name'=>'Account Number '.$len,'api_key'=>$access_token]);


            }
            wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=todoist'));
            exit();
    
    }

    public function action_provider( $providers ) {
        $providers['todoist'] = array(
            'title' => esc_html__( 'Todoist', 'automate_hub' ),
            'tasks' => array(
                'createproject'   => esc_html__( 'Create Project', 'automate_hub' ),
                'createtask'   => esc_html__( 'Create Task', 'automate_hub' ),
                'createsession'   => esc_html__( 'Create Session', 'automate_hub' )
            )
        );
    
        return  $providers;
    }

    public function settings_tab( $tabs ) {
        $tabs['todoist'] = array('name'=>esc_html__( 'Todoist', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'todoist') {
            return;
        }
        $nonce = wp_create_nonce("awp_todoist_settings");
        $api_token = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/todoist" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/todoist.png" width="191" height="50" alt="Todoist Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?>

                <br />
            <form name="todoist_save_form" action="<?=esc_url(admin_url('admin-post.php'));?>" method="post" class="container">
                <input type="hidden" name="action" value="awp_todoist_save_api_token">
                <input type="hidden" name="_nonce" value="<?=$nonce?>" />
                <input type="hidden" name="id" value="<?php echo $id ?>">
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"> </th>
                        <td>
                            <a href="<?=$this->getLoginURL()?>" class="button button-primary"> Connect Your todoist Account </a>
                        </td>
                    </tr>
                </table>

            </form>
        </div>

        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                 $data=[
                    'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('todoist');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();

                ?>
                        </form>
                </div>
        <?php
    }

    public function awp_save_todoist_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_todoist_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_token = sanitize_text_field($_POST["awp_todoist_api_token"]);
        $display_name     = sanitize_text_field( $_POST["awp_todoist_display_name"] );

        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('todoist');
        $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_token]);

        AWP_redirect("admin.php?page=automate_hub&tab=todoist");
    } 
    
    public function load_custom_script() {
        wp_enqueue_script( 'awp-todoist-script', AWP_URL . '/apps/t/todoist/todoist.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="todoist-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'todoist',
                           'app_name'=>'Todoist ',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/todoist.png',
                           'app_icon_alter_text'=>'Todoist  Icon',
                           'account_select_onchange'=>'getprojectlist',
                           'tasks'=>array(
                                'createtask'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Project',
                                            'type'=>'select',
                                            'name'=>"project_id",
                                            'required' => 'required',
                                            'onchange' => 'selectedproject',
                                            'option_for_loop'=>'(item) in data.projectlist',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Project...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': accountLoading}",
                                                        )
                                        )
                                    ),
                                ),
                                'createsession'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Project',
                                            'type'=>'select',
                                            'name'=>"project_id",
                                            'required' => 'required',
                                            'onchange' => 'selectedproject',
                                            'option_for_loop'=>'(item) in data.projectlist',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Project...',
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

    
    
    public function awp_fetch_projectlist() {
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
        $platform_obj= new AWP_Platform_Shell_Table('todoist');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;

        $url = "https://api.todoist.com/rest/v1/projects";
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
   
};


$Awp_Todoist = new AWP_Todoist();

function awp_todoist_save_integration() {
    Appfactory::save_integration();
}

function awp_todoist_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('todoist');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $task = $decoded_data["task"]; 


    if( $task == "createproject" ) {

        $name = empty( $data["name"] ) ? "" : awp_get_parsed_values($data["name"], $posted_data);


        $token = 'Bearer '.$api_key;

        $body = json_encode([
            "name"=>$name
        ]);

        $url = "https://api.todoist.com/rest/v1/projects";

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

    } else if($task == "createtask"){

     

        $content = empty( $data["content"] ) ? "" : awp_get_parsed_values($data["content"], $posted_data);
        $description = empty( $data["description"] ) ? "" : awp_get_parsed_values($data["description"], $posted_data);
        $projectid = $data["projectid"];

        $token = 'Bearer '.$api_key;

        

        $body = json_encode([
            "content"=>$content,
            "description"=>$description,
            "project_id"=>$projectid
        ]);

       
        
        $url = "https://api.todoist.com/rest/v1/tasks";


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

    }else if($task == "createsession"){

     

        $name = empty( $data["name"] ) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $projectid = $data["projectid"];

        $token = 'Bearer '.$api_key;

        

        $body = json_encode([
            "name"=>$name,
            "project_id"=>$projectid
        ]);

       
        
        $url = "https://api.todoist.com/rest/v1/sections";


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


function awp_todoist_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('todoist');
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

   

        $token = 'Bearer '.$api_key;
        

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $token,
            ),

            'body'=> json_encode($body)
        ];


        $return  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="Bearer XXXXXXXXXXXX";
        awp_add_to_log( $return, $url, $args, $integration );

    $response['success']=true;    
    return $response;
}
