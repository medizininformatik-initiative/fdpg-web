<?php

class AWP_Basecamp3 {
    const appID = "ed1129f7cb70a43fa362f8f39c4b8aed2fffdbbf";

    const redirect_uri = "https://sperse.io/scripts/authorization/auth.php";
    // secret should live in sperse.io server
    const token_endpoint = 'https://launchpad.37signals.com/authorization/token?';
    // const url = "https://3.basecampapi.com/";

    private static $instance = null;
   
    public static function get_instance() {
        if ( empty( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {   
        $this->bscp3_access_token = get_option( 'bscp3_access_token' );
        $this->init_filters();                  // Initialize the filter hooks
        $this->init_actions();                  // Initialize the action hooks 
    }

    public function init_actions(){
        add_action( 'admin_post_awp_unlink_basecamp'  , [ $this, 'unlink_basecamp'], 10, 0 );
        add_action( 'admin_post_awp_edit_basecamp'  , [ $this, 'awp_edit_basecamp'], 10, 0 );
        
        add_action( 'awp_custom_script', [$this, 'load_custom_script']);
        add_action( 'awp_settings_view', [$this, 'add_settings_view'], 10, 1 );
        add_action( "rest_api_init", [$this, "create_webhook_route"]);
        add_action( 'awp_action_fields', [$this, 'add_action_fields']);
        add_action( 'wp_ajax_awp_fetch_accounts', [$this, 'fetch_accounts']);
        add_action( 'wp_ajax_awp_fetch_account_campfires', [$this, 'fetch_account_campfires']);
        add_action( 'wp_ajax_awp_fetch_todoset', [$this, 'fetch_todoset']);
        add_action( 'wp_ajax_awp_fetch_todolist', [$this, 'fetch_todolist']);
        add_action( 'wp_ajax_awp_fetch_projects', [$this, 'fetch_projects']);
        add_action( 'wp_ajax_awp_fetch_peoples_list', [$this, 'fetch_peoples_list']);
    }

    public function init_filters(){
        add_filter( 'awp_action_providers', [$this, 'add_action_provider'], 10, 1 );

        add_filter( 'awp_settings_tabs', [$this, 'add_settings_tab'], 10, 1 );
    }

    protected function get_callback() {
        return get_rest_url(null,'automatehub/basecamp3');
    }

    public function getLoginURL():string {
        $query = [
            'type' => "web_server",
            'client_id'=> self::appID,
            'redirect_uri' => self::redirect_uri,
            'state'=> $this->get_callback(), 
        ];
    
        $authorization_endpoint = "https://launchpad.37signals.com/authorization/new?";
    
        return add_query_arg( $query , $authorization_endpoint);
    }

    public function create_webhook_route() {
        register_rest_route( 'automatehub', '/basecamp3',
        [
            'methods' => 'GET', 
            'callback' => [$this, 'get_webhook_data'], 
            'permission_callback' => function() { return ''; }
        ]);
    }

    public function get_webhook_data( $request ) {
        global $wpdb;
        $params = $request->get_params();
        if ( isset( $params['access_token'] ) ) {
            $query= $wpdb->prepare("select * from {$wpdb->prefix}awp_platform_settings where platform_name=%s",'basecamp3');
            $data=$wpdb->get_results($query);
            $len=count($data) + 1;
            $basecamp3_access_code = sanitize_text_field($params['access_token']);
            $platform_obj= new AWP_Platform_Shell_Table('basecamp3');

            $platform_obj->save_platform(['account_name'=>'Account Number '.$len,'api_key'=>$basecamp3_access_code]);
      
        }

        wp_safe_redirect( admin_url( 'admin.php?page=automate_hub&tab=basecamp3' ) );
        exit();

       
    }



    public function unlink_basecamp() {
        if ( ! current_user_can('administrator') ){
            die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
        }

        // Security Check
        if (! wp_verify_nonce( $_POST['_nonce'], 'awp_basecamp3_settings' ) ) {
            die( esc_html__( 'Security check Failed', 'automate_hub' ) );
        }

        if(!empty($_POST['reset']) && ($_POST['reset']=='Deactivate')){
            delete_option('bscp3_access_token');
            AWP_redirect( "admin.php?page=automate_hub&tab=basecamp3" );
        }
    }

    public function awp_edit_basecamp(){
        if ( ! current_user_can('administrator') ){
            die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
        }

        // Security Check
        if (! wp_verify_nonce( $_POST['_nonce'], 'awp_basecamp3_settings' ) ) {
            die( esc_html__( 'Security check Failed', 'automate_hub' ) );
        }
        $display_name     = sanitize_text_field( $_POST["awp_basecamp_display_name"] );
        $api_key = sanitize_text_field( $_POST["awp_basecamp_api_key"] );
        $platform_obj= new AWP_Platform_Shell_Table('basecamp3');
        $platform_obj->save_platform(['account_name'=>$display_name,'api_key'=>$api_key]);
        AWP_redirect( "admin.php?page=automate_hub&tab=basecamp3" ); 
    }


    public function add_action_provider( $providers ) {
        $providers['basecamp3'] = array(
            'title' => __( 'Basecamp 3', 'automate_hub' ),
            'tasks' => array(
                'sendmsg'   => __( 'Send Campfire Line', 'automate_hub' ),
                'addtodo'   => __( 'Add todo list', 'automate_hub' )
            )
        );
    
        return  $providers;
    }

    public function load_custom_script() {
        wp_enqueue_script( 'awp-basecamp3-script', AWP_URL . '/apps/b/basecamp3/basecamp3.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function add_settings_tab( $tabs ) {
        $tabs['basecamp3'] = array('name'=>esc_html__( 'Basecamp 3', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function add_settings_view( $current_tab ) {
        if( $current_tab != 'basecamp3' ) { return; }
        $nonce     = wp_create_nonce( "awp_basecamp3_settings" );
        $id = isset($_GET['id']) ? intval( sanitize_text_field($_GET['id'] )) : "";
        $display_name     = isset($_GET['account_name']) ? sanitize_text_field($_GET['account_name']) : "";
        $api_key     = isset($_GET['api_key']) ? sanitize_text_field( $_GET['api_key']) : "";

        ?>
            <div class="platformheader" id="setting_testtt">

            <a href="https://sperse.io/go/basecamp3" target="_blank"><img src="<?php echo esc_url(AWP_ASSETS.'/images/logos/basecamp3.png'); ?>" width="228" height="50" alt="Basecamp Logo"></a><br/><br/>
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager(sanitize_text_field($_GET['tab']));
                    $instruction_obj->prepare_instructions();
                        
                ?>
                <br/>
                
                <form action='admin-post.php' method="post"  class="container" >
                    <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>
                    <input type="hidden" name="id" value="<?php echo esc_attr( wp_unslash($id)); ?>">
                    <input type="hidden" name="action" value="awp_edit_basecamp">
                    <input type="hidden" name="awp_basecamp_api_key" value="<?php echo $api_key ?>">
                    
                <table class="form-table">
                    <?php 
                        if(!empty($display_name)){
                    ?>
                        <tr valign="top">
                            <th scope="row"> <?php esc_html_e( 'Display name', 'automate_hub' ); ?></th>
                            <td>
                                <div class="form-table__input-wrap">
                                <input type="text" name="awp_basecamp_display_name" id="awp_basecamp_display_name" value="<?php echo $display_name ?>" placeholder="<?php esc_html_e( 'Enter Display name', 'automate_hub' ); ?>" class="basic-text"/>
                                <span class="spci_btn form-table__input-btn" data-clipboard-action="copy" data-clipboard-target="#awp_basecamp_display_name"><img src="<?php echo AWP_ASSETS; ?>/images/copy.png" alt="Copy to clipboard"></span>
                                </div>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row"></th>
                            <td>
                      
                                    <div class="submit-button-plugin"><?php submit_button(); ?></div>
                              
                            </td>
                        </tr>
                        

                    <?php
                        }
                        else{
                    ?>

                        <tr valign="top">
                            <th scope="row"> </th>
                            <td>
                                <a onclick="basecamp3authbtn()" id="basecamp3authbtn" target="_blank" class="button button-primary"> Link Basecamp 3 Account </a>
                            </td>
                        </tr>

                        <script type="text/javascript">
                            function basecamp3authbtn(){
                                var win=window.open('<?php echo $this->getLoginURL()?>','popup','width=600,height=600');
                                var id = setInterval(function() {
                                const queryString = win.location.search;
                                const urlParams = new URLSearchParams(queryString);
                                const page_type = urlParams.get('page');
                                if(page_type=='automate_hub'){win.close(); clearInterval(id); location.reload();}
                                }, 1000);

                            }
                            
                        </script>
                    <?php
                        }
                    ?>


                    
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
                        $platform_obj= new AWP_Platform_Shell_Table('basecamp3');
                        $platform_obj->initiate_table($data);
                        $platform_obj->prepare_items();
                        $platform_obj->display_table();
                        ?>
                    </form>
            </div>
        <?php
    }

    public function add_action_fields() {
        ?>
            <script type="text/template" id="basecamp3-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'basecamp3',
                           'app_name'=>'Basecamp 3',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/basecamp3.png',
                           'app_icon_alter_text'=>'Basecamp 3 Icon',
                           'account_select_onchange'=>'getAccountsList',
                           'tasks'=>array(
                                'sendmsg'=>array(
                                    'task_assignments'=>array(

                                        array(
                                            'label'=>'Select Account',
                                            'type'=>'select',
                                            'name'=>"account_id",
                                            'onchange' => 'getCampfiresList',
                                            'option_for_loop'=>'(item) in data.accountsList',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Account...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': accountLoading}",
                                                        )
                                        ),
                                        array(
                                            'label'=>'Select Campfire',
                                            'type'=>'select',
                                            'name'=>"list_id",
                                            'required' => 'required',
                                            'onchange' => 'onselect',
                                            'option_for_loop'=>'{bucket, lines_url } of data.campfiresList',
                                            'option_for_value'=>'lines_url',
                                            'option_for_text'=>'{{bucket.name}}',
                                            'select_default'=>'Select Campfire...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': campfireLoading}",
                                                        )
                                        ),
                                    ),
                                ),
                                'addtodo'=>array(
                                    'task_assignments'=>array(

                                        array(
                                            'label'=>'Select Account',
                                            'type'=>'select',
                                            'name'=>"account_id",
                                            'onchange' => 'getProjects',
                                            'option_for_loop'=>'(item) in data.accountsList',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Account...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': accountLoading}",
                                                        )
                                        ),
                                        array(
                                            'label'=>'Select Project',
                                            'type'=>'select',
                                            'name'=>"projectid",
                                            'onchange' => 'getTodoSet',
                                            'option_for_loop'=>'(item) in data.projectsList',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Project...'
                                        ),
                                        array(
                                            'label'=>'Select Todo Set',
                                            'type'=>'select',
                                            'name'=>"todoset",
                                            'onchange' => 'getAssigneeList',
                                            'option_for_loop'=>'(item) in data.todoset',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.title}}',
                                            'select_default'=>'Select Todo Set...'
                                        ),
                                       
                                        array(
                                            'label'=>'Select Assignee',
                                            'type'=>'select',
                                            'name'=>"AssignTo",
                                            'onchange' => 'selectedassignee',
                                            'option_for_loop'=>'(item) in data.assigneelist',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Assignees...'
                                        ),
                                        array(
                                            'label'=>'Notify When Done',
                                            'type'=>'select',
                                            'name'=>"NotifyWhenDon",
                                            'onchange' => 'selectedCompletionSubscriber',
                                            'option_for_loop'=>'(item) in data.assigneelist',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Notify When Done...'
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

    public function fetch_accounts() {
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
        $platform_obj= new AWP_Platform_Shell_Table('basecamp3');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;

        $url = "https://launchpad.37signals.com/authorization.json";
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$api_key,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            )
        );

        $response = wp_remote_get( $url, $args );
        $body = wp_remote_retrieve_body( $response );
        $body = json_decode( $body, true );
        wp_send_json_success($body["accounts"]);
    }

    public function fetch_account_campfires() {
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
        $platform_obj= new AWP_Platform_Shell_Table('basecamp3');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;

        $url = "https://3.basecampapi.com/".$_POST['accountId']."/chats.json";

        $args = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$api_key,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ]
            ];

        $response = wp_remote_get( $url, $args );

        $body = wp_remote_retrieve_body( $response );
        $body = json_decode( $body, true );
        wp_send_json_success($body);
    }

    public function fetch_projects() {
        
        if ( ! current_user_can('administrator') ){
            die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
        }
    
        if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
            die( esc_html__( 'Security check Failed', 'automate_hub' ) );
        }

        if (!isset( $_POST['platformid'] ) ) {
            die( esc_html__( 'Invalid Request', 'automate_hub' ) );
        }

        $id = sanitize_text_field($_POST['platformid']);
        $platform_obj = new AWP_Platform_Shell_Table('basecamp3');

        $data = $platform_obj->awp_get_platform_detail_by_id($id);

        
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;

        $url = "https://3.basecampapi.com/".$_POST['accountId']."/projects.json";

        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$api_key,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            )
        );

        $response = wp_remote_get( $url, $args );
        $body = wp_remote_retrieve_body( $response );
        $body = json_decode( $body, true );
        wp_send_json_success($body);
    }

    public function fetch_todoset() {
        
        if ( ! current_user_can('administrator') ){
            die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
        }
    
        if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
            die( esc_html__( 'Security check Failed', 'automate_hub' ) );
        }

        if (!isset( $_POST['platformid'] ) ) {
            die( esc_html__( 'Invalid Request', 'automate_hub' ) );
        }

        $id = sanitize_text_field($_POST['platformid']);
        $platform_obj = new AWP_Platform_Shell_Table('basecamp3');

        $data = $platform_obj->awp_get_platform_detail_by_id($id);

  

        
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;
        
        

        // get a project selected project detail
        $response = $this->get_todo_set_by_project($_POST['projectid'], $api_key, $_POST['accountId']);

        

        $url = "https://3.basecampapi.com/".$_POST['accountId']."/buckets/".$_POST['projectid']."/todosets/".$response['id']."/todolists.json";
        
        

        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$api_key,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            )
        );

        $response = wp_remote_get( $url, $args );
        $body = wp_remote_retrieve_body( $response );
        
        $body = json_decode( $body, true );

        wp_send_json_success($body);
    }

    public function fetch_todolist() {

        $id = sanitize_text_field($_POST['platformid']);
        $platform_obj = new AWP_Platform_Shell_Table('basecamp3');

        $data = $platform_obj->awp_get_platform_detail_by_id($id);
        
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;
        
        
        
        $url = "https://3.basecampapi.com/".$_POST['accountId']."/buckets/".$_POST['projectid']."/todolists/".$_POST['todosetid']."/todos.json";

        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$api_key,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),
            'body'=>array('status'=>true)

        );


        $response = wp_remote_get( $url, $args );

        
        $retrievebody = wp_remote_retrieve_body( $response);
        
        $body = json_decode( $retrievebody, true );
       
        wp_send_json_success($body);
    }

    public function get_todo_set_by_project($projectid, $key, $accountid){
        
        $url = "https://3.basecampapi.com/".$accountid."/projects/".$projectid.".json";

        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$key,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            )
        );

        $response = wp_remote_get( $url, $args );
        
        $retrievebody = wp_remote_retrieve_body( $response);
        
        $body = json_decode( $retrievebody, true );

        $filteredArray = [];
        $dock = [];

        $dock = $body['dock'];

        

        for($i=0; $i < count($dock) - 1; $i++ ){
            for($j = 0; $j <= $i; $j++){
                if($dock[$j]['name'] == 'todoset'){
                    $filteredArray = array(
                        'id' => $dock[$j]['id'], 
                        'title' => $dock[$j]['title'], 
                        'name' => $dock[$j]['name'], 
                        'enabled' => $dock[$j]['enabled'], 
                        'position' => $dock[$j]['position'], 
                        'url' => $dock[$j]['url'], 
                        'app_url' => $dock[$j]['app_url']
                    );
                }
                
            }
            
        }
        return($filteredArray);
        
    }

    public function fetch_peoples_list() {
        
        if ( ! current_user_can('administrator') ){
            die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
        }
    
        if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
            die( esc_html__( 'Security check Failed', 'automate_hub' ) );
        }

        if (!isset( $_POST['platformid'] ) ) {
            die( esc_html__( 'Invalid Request', 'automate_hub' ) );
        }

        

        $id = sanitize_text_field($_POST['platformid']);
        $platform_obj = new AWP_Platform_Shell_Table('basecamp3');
        $data = $platform_obj->awp_get_platform_detail_by_id($id);


        $api_key =$data->api_key;

        $url = "https://3.basecampapi.com/".$_POST['accountId']."/projects/".$_POST['projectid']."/people.json";

        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$api_key,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            )
        );

        $response = wp_remote_get( $url, $args );
        $body = wp_remote_retrieve_body( $response );
        $body = json_decode( $body, true );
        wp_send_json_success($body);
    }

};


$Awp_Basecamp3 = AWP_Basecamp3::get_instance();

function awp_basecamp3_save_integration() {
    Appfactory::save_integration();
}

function awp_basecamp3_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 
    $platform_obj= new AWP_Platform_Shell_Table('basecamp3');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $task = $decoded_data["task"]; 

    $name = empty( $data["name"] ) ? "" : awp_get_parsed_values($data["name"], $posted_data);
    $message = empty( $data["message"] ) ? "" : awp_get_parsed_values($data["message"], $posted_data);

    if( $task == "sendmsg" ) {
        $campfireUrl = $data["campfireUrl"];
        $token = 'Bearer '.$api_key;

        $name = empty($name) ? "Unknown" : $name;

        $body = json_encode([
            "content" => "Message from: ".$name.", \r\n \r\n".$message
        ]);

        $request = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $token,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($campfireUrl,  $request );

        $request['headers']['Authorization']="XXXXXXXXXXXX";
        awp_add_to_log( $response, $campfireUrl, $request, $record );
    }else if( $task == "addtodo" ) {
        
        $token = 'Bearer '.$api_key;

        $create_to_url = "https://3.basecampapi.com/".$data['accountId']."/buckets/".$data['projectid']."/todolists/".$data['todoset']."/todos.json";

        
        $body = json_encode([
            "content" => (empty( $data["Title"] ) ? "" : awp_get_parsed_values($data["Title"], $posted_data)),
            "description" => "<div><em>".(empty( $data["Notes"] ) ? "" : awp_get_parsed_values($data["Notes"], $posted_data))."</em></div>",
            "assignee_ids" =>  (empty( $data["AssignedTo"] ) ? "" : awp_get_parsed_values($data["AssignedTo"], $posted_data)),
            "completion_subscriber_ids" =>  (empty( $data["NotifyWhenDone"] ) ? "" : awp_get_parsed_values($data["NotifyWhenDone"], $posted_data)),
            "notify" => true,
            "due_on" => (empty( $data["DueOn"] ) ? "" : awp_get_parsed_values($data["DueOn"], $posted_data)),
            "starts_on" => date("Y-m-d", time())
        ]);



        $request = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $token,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($create_to_url,  $request );
        $request['headers']['Authorization']="Bearer XXXXXXXXXXXX";
        awp_add_to_log( $response, $create_to_url, $request, $record );
    }

    return $response;
}


function awp_basecamp3_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('basecamp3');
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
        

        $request = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $token,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),

            'body'=> json_encode($body)
        ];

        $return  = wp_remote_post($campfireUrl,  $request );

        $request['headers']['Authorization']="Bearer XXXXXXXXXXXX";
        awp_add_to_log( $return, $campfireUrl, $request, $integration );

    $response['success']=true;    
    return $response;
}
