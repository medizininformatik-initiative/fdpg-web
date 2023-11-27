<?php

class AWP_Clickup extends Appfactory
{

    // replace with live key
    const client_id = "21WIXK5KJ1J78Y9U29E3TYW3EW3X4HA1";

    protected function get_redirect_uri()
    {
        return 'https://sperse.io/scripts/authorization/auth.php';
    }

    public function init_actions()
    {
        add_action( 'wp_ajax_awp_fetch_team', [$this, 'awp_fetch_team']);
        add_action( 'wp_ajax_awp_fetch_space', [$this, 'awp_fetch_space']);
        add_action( 'wp_ajax_awp_fetch_folder', [$this, 'awp_fetch_folder']);
        add_action( 'wp_ajax_awp_fetch_lists', [$this, 'awp_fetch_lists']);

        add_action("rest_api_init", [$this, "create_webhook_route"]);
    }

    public function init_filters()
    {
        add_filter('awp_platforms_connections', [$this, 'awp_clickup_platform_connection'], 10, 1);
    }

    public function get_callback()
    {
        return get_rest_url(null,'automatehub/clickup');
    }

    public function getLoginURL(): string
    {
        $query = [
            'type' => "web_server",
            'client_id' => self::client_id,
            'redirect_uri' => urlencode($this->get_redirect_uri()),
            'state' => $this->get_callback(),
        ];
        $authorization_endpoint = "https://app.clickup.com/api?";

        return add_query_arg($query, $authorization_endpoint);
    }

    public function create_webhook_route()
    {
        register_rest_route('automatehub', '/clickup',
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

         if ( isset( $params['access_token'] ) ) {
            
            $clickup_access_token = sanitize_text_field($params['access_token']);

            $authorized_user_url = "https://api.clickup.com/api/v2/user";

            $data = ["headers"=>["Authorization"=> $clickup_access_token]];

            $user_detail =  wp_remote_get($authorized_user_url, $data);

            $decoded_user_data = json_decode($user_detail['body'])->user;

            $platform_obj= new AWP_Platform_Shell_Table('clickup');

            $platform_obj->save_platform(['account_name'=>$decoded_user_data->username,'api_key'=>$clickup_access_token, 'email'=>$decoded_user_data->email]);


        }
        
        wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=clickup'));
        exit();
    }

    public function action_provider($actions)
    {
        $actions['clickup'] = [
            'title' => esc_html__('Clickup', 'automate_hub'),
            'tasks' => array(
                'creategoal' => esc_html__('Create Goal', 'automate_hub'),
                'createspace' => esc_html__('Create Space', 'automate_hub'),
                'inviteuser' => esc_html__('Invite User', 'automate_hub'),
                'inviteguest' => esc_html__('Invite Guest', 'automate_hub'),
                'createfolder' => esc_html__('Create Folder', 'automate_hub'),
                'createlist' => esc_html__('Create List', 'automate_hub'),
                'createtask' => esc_html__('Create Task', 'automate_hub')
                
            ),
        ];
        return $actions;
    }

    public function settings_tab($tabs)
    {
        $tabs['clickup'] = array('name' => esc_html__('Clickup', 'automate_hub'), 'cat' => array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'clickup') {
            return;
        }
        $nonce = wp_create_nonce("awp_clickup_settings");
        $api_token = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/clickup" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/clickup.png" height="50" alt="clickup Logo"></a><br /><br />
                <?php
        require_once AWP_INCLUDES . '/class_awp_updates_manager.php';
        $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
        $instruction_obj->prepare_instructions();

        ?><br />
            <form name="clickup_save_form" action="<?=esc_url(admin_url('admin-post.php'));?>" method="post" class="container">
                <input type="hidden" name="action" value="awp_clickup_save_api_token">
                <input type="hidden" name="_nonce" value="<?=$nonce?>" />
                <input type="hidden" name="id" value="<?=$id?>" />
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"> </th>
                        <td>
                            <a href="<?=$this->getLoginURL()?>" class="button button-primary"> Connect Your Clickup Account </a>
                        </td>
                    </tr>
                </table>
            </form>
        </div>

        <div class="wrap">
            <form id="form-list" method="post">

                  <input type="hidden" name="page" value="automate_hub"/>

                  <?php
        $data = [
            'table-cols' => ['api_key' => 'API Key', 'account_name' => 'Display Name', 'active_status' => 'Active'],
        ];
        $platform_obj = new AWP_Platform_Shell_Table('clickup');
        $platform_obj->initiate_table($data);
        $platform_obj->prepare_items();
        $platform_obj->display_table();

        ?>
            </form>
        </div>
        <?php
    }

    public function load_custom_script()
    {
        wp_enqueue_script('awp-clickup-script', AWP_URL . '/apps/c/clickup/clickup.js', array('awp-vuejs'), '', 1);
    }

    public function action_fields()
    {
        ?>
        <script type="text/template" id="clickup-action-template">
            <?php

                $app_data=array(
                    'app_slug'=>'clickup',
                    'app_name'=>'Clickup',
                    'app_icon_url'=>AWP_ASSETS.'/images/icons/clickup.png',
                    'app_icon_alter_text'=>'Clickup  Icon',
                    'account_select_onchange'=>'getTeam',
                    'tasks'=>array(
                        'creategoal'=>array(
                            'task_assignments'=>array(
                                array(
                                    'label'=>'Select Team',
                                    'type'=>'select',
                                    'name'=>"teamid",
                                    'required' => 'required',
                                    'onchange' => 'getColour',
                                    'option_for_loop'=>'(item) in data.teamList',
                                    'option_for_value'=>'item.id',
                                    'option_for_text'=>'{{item.name}}',
                                    'select_default'=>'Select Team...',
                                    'spinner'=>array(
                                                    'bind-class'=>"{'is-active': teamLoading}",
                                                )
                                ),
                                array(
                                    'label'=>'Select Colour',
                                    'type'=>'select',
                                    'name'=>"colour",
                                    'required' => 'required',
                                    'onchange' => 'selectedcolour',
                                    'option_for_loop'=>'(item) in data.colourList',
                                    'option_for_value'=>'item.id',
                                    'option_for_text'=>'{{item.name}}',
                                    'select_default'=>'Select Colour...',
                                    'spinner'=>array(
                                                    'bind-class'=>"{'is-active': teamLoading}",
                                                )
                                )
                            ),
                        ),
                        'createspace'=>array(
                            'task_assignments'=>array(
                                array(
                                    'label'=>'Select Team',
                                    'type'=>'select',
                                    'name'=>"teamid",
                                    'required' => 'required',
                                    'onchange' => 'selectedTeam',
                                    'option_for_loop'=>'(item) in data.teamList',
                                    'option_for_value'=>'item.id',
                                    'option_for_text'=>'{{item.name}}',
                                    'select_default'=>'Select Team...',
                                    'spinner'=>array(
                                                    'bind-class'=>"{'is-active': teamLoading}",
                                                )
                                )
                            ),
                        ),
                        'createteam'=>array(
                            'task_assignments'=>array(
                                array(
                                    'label'=>'Select Team',
                                    'type'=>'select',
                                    'name'=>"teamid",
                                    'required' => 'required',
                                    'onchange' => 'selectedTeam',
                                    'option_for_loop'=>'(item) in data.teamList',
                                    'option_for_value'=>'item.id',
                                    'option_for_text'=>'{{item.name}}',
                                    'select_default'=>'Select Team...',
                                    'spinner'=>array(
                                                    'bind-class'=>"{'is-active': teamLoading}",
                                                )
                                )
                            ),
                        ),
                        'inviteuser'=>array(
                            'task_assignments'=>array(
                                array(
                                    'label'=>'Select Team',
                                    'type'=>'select',
                                    'name'=>"teamid",
                                    'required' => 'required',
                                    'onchange' => 'getUserType',
                                    'option_for_loop'=>'(item) in data.teamList',
                                    'option_for_value'=>'item.id',
                                    'option_for_text'=>'{{item.name}}',
                                    'select_default'=>'Select Team...',
                                    'spinner'=>array(
                                                    'bind-class'=>"{'is-active': teamLoading}",
                                                )
                                ),
                                array(
                                    'label'=>'Select Admin Status',
                                    'type'=>'select',
                                    'name'=>"adminstatus",
                                    'required' => 'required',
                                    'onchange' => 'selectedUserType',
                                    'option_for_loop'=>'(item) in data.adminStatusList',
                                    'option_for_value'=>'item.id',
                                    'option_for_text'=>'{{item.name}}',
                                    'select_default'=>'Select Admin Status...',
                                    'spinner'=>array(
                                                    'bind-class'=>"{'is-active': teamLoading}",
                                                )
                                )
                            ),
                        ),
                        'createfolder'=>array(
                            'task_assignments'=>array(
                                array(
                                    'label'=>'Select Team',
                                    'type'=>'select',
                                    'name'=>"team_id",
                                    'required' => 'required',
                                    'onchange' => 'getSpace',
                                    'option_for_loop'=>'(item) in data.teamList',
                                    'option_for_value'=>'item.id',
                                    'option_for_text'=>'{{item.name}}',
                                    'select_default'=>'Select Team...',
                                    'spinner'=>array(
                                                    'bind-class'=>"{'is-active': teamLoading}",
                                                )
                                ),
                                array(
                                    'label'=>'Select Space',
                                    'type'=>'select',
                                    'name'=>"spaceid",
                                    'required' => 'required',
                                    'onchange' => 'selectedSpace',
                                    'option_for_loop'=>'(item) in data.spaceList',
                                    'option_for_value'=>'item.id',
                                    'option_for_text'=>'{{item.name}}',
                                    'select_default'=>'Select Space...',
                                    'spinner'=>array(
                                                    'bind-class'=>"{'is-active': spaceLoading}",
                                                )
                                )
                            ),
                        ),
                        'createtask'=>array(
                            'task_assignments'=>array(
                                array(
                                    'label'=>'Select Team',
                                    'type'=>'select',
                                    'name'=>"team_id",
                                    'required' => 'required',
                                    'onchange' => 'getSpace',
                                    'option_for_loop'=>'(item) in data.teamList',
                                    'option_for_value'=>'item.id',
                                    'option_for_text'=>'{{item.name}}',
                                    'select_default'=>'Select Team...',
                                    'spinner'=>array(
                                                    'bind-class'=>"{'is-active': teamLoading}",
                                                )
                                ),
                                array(
                                    'label'=>'Select Space',
                                    'type'=>'select',
                                    'name'=>"spaceid",
                                    'required' => 'required',
                                    'onchange' => 'getFolder',
                                    'option_for_loop'=>'(item) in data.spaceList',
                                    'option_for_value'=>'item.id',
                                    'option_for_text'=>'{{item.name}}',
                                    'select_default'=>'Select Space...',
                                    'spinner'=>array(
                                                    'bind-class'=>"{'is-active': spaceLoading}",
                                                )
                                ),
                                array(
                                    'label'=>'Select Folder',
                                    'type'=>'select',
                                    'name'=>"folderid",
                                    'required' => 'required',
                                    'onchange' => 'getList',
                                    'option_for_loop'=>'(item) in data.folderList',
                                    'option_for_value'=>'item.id',
                                    'option_for_text'=>'{{item.name}}',
                                    'select_default'=>'Select Folder...',
                                    'spinner'=>array(
                                                    'bind-class'=>"{'is-active': folderLoading}",
                                                )
                                ),
                                array(
                                    'label'=>'Select Lists',
                                    'type'=>'select',
                                    'name'=>"list_id",
                                    'required' => 'required',
                                    'onchange' => 'gettaskPriority',
                                    'option_for_loop'=>'(item) in data.listList',
                                    'option_for_value'=>'item.id',
                                    'option_for_text'=>'{{item.name}}',
                                    'select_default'=>'Select Lists...',
                                    'spinner'=>array(
                                                    'bind-class'=>"{'is-active': listLoading}",
                                                )
                                ),
                                array(
                                    'label'=>'Select Task Priority',
                                    'type'=>'select',
                                    'name'=>"priorityid",
                                    'required' => 'required',
                                    'onchange' => 'selectedtaskPriority',
                                    'option_for_loop'=>'(item) in data.taskpriorityList',
                                    'option_for_value'=>'item.id',
                                    'option_for_text'=>'{{item.name}}',
                                    'select_default'=>'Select Task Priority...',
                                    'spinner'=>array(
                                                    'bind-class'=>"{'is-active': priorityLoading}",
                                                )
                                )
                            ),
                        ),
                        'createlist'=>array(
                            'task_assignments'=>array(
                                array(
                                    'label'=>'Select Team',
                                    'type'=>'select',
                                    'name'=>"team_id",
                                    'required' => 'required',
                                    'onchange' => 'getSpace',
                                    'option_for_loop'=>'(item) in data.teamList',
                                    'option_for_value'=>'item.id',
                                    'option_for_text'=>'{{item.name}}',
                                    'select_default'=>'Select Team...',
                                    'spinner'=>array(
                                                    'bind-class'=>"{'is-active': teamLoading}",
                                                )
                                ),
                                array(
                                    'label'=>'Select Space',
                                    'type'=>'select',
                                    'name'=>"spaceid",
                                    'required' => 'required',
                                    'onchange' => 'getFolder',
                                    'option_for_loop'=>'(item) in data.spaceList',
                                    'option_for_value'=>'item.id',
                                    'option_for_text'=>'{{item.name}}',
                                    'select_default'=>'Select Space...',
                                    'spinner'=>array(
                                                    'bind-class'=>"{'is-active': spaceLoading}",
                                                )
                                ),
                                array(
                                    'label'=>'Select Folder',
                                    'type'=>'select',
                                    'name'=>"folderid",
                                    'required' => 'required',
                                    'onchange' => 'getPriority',
                                    'option_for_loop'=>'(item) in data.folderList',
                                    'option_for_value'=>'item.id',
                                    'option_for_text'=>'{{item.name}}',
                                    'select_default'=>'Select Folder...',
                                    'spinner'=>array(
                                                    'bind-class'=>"{'is-active': folderLoading}",
                                                )
                                ),
                                array(
                                    'label'=>'Select Priority',
                                    'type'=>'select',
                                    'name'=>"priorityid",
                                    'required' => 'required',
                                    'onchange' => 'selectedPriority',
                                    'option_for_loop'=>'(item) in data.priorityList',
                                    'option_for_value'=>'item.id',
                                    'option_for_text'=>'{{item.name}}',
                                    'select_default'=>'Select Priority...',
                                    'spinner'=>array(
                                                    'bind-class'=>"{'is-active': priorityLoading}",
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

    public function awp_fetch_team(){
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
        $platform_obj= new AWP_Platform_Shell_Table('clickup');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;

        $url = "https://api.clickup.com/api/v2/team";
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $api_key
            )
        );

        $response = wp_remote_get( $url, $args );
        $body = wp_remote_retrieve_body( $response );
        $body = json_decode( $body, true );
        wp_send_json_success($body["teams"]);
    }

    public function awp_fetch_space(){
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
        $team_id=sanitize_text_field($_POST['team_id']);

        $platform_obj= new AWP_Platform_Shell_Table('clickup');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;

        $url = "https://api.clickup.com/api/v2/team/".$team_id."/space?archived=false";
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $api_key
            )
        );

        $response = wp_remote_get( $url, $args );
        $body = wp_remote_retrieve_body( $response );
        $body = json_decode( $body, true );
        wp_send_json_success($body["spaces"]);
    }

    public function awp_fetch_folder(){
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
        $space_id=sanitize_text_field($_POST['space_id']);

        $platform_obj= new AWP_Platform_Shell_Table('clickup');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;

        $url = "https://api.clickup.com/api/v2/space/". $space_id ."/folder?archived=false";
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $api_key
            )
        );

        $response = wp_remote_get( $url, $args );
        $body = wp_remote_retrieve_body( $response );
        $body = json_decode( $body, true );
        wp_send_json_success($body['folders']);
    }

    public function awp_fetch_lists(){
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
        $folder_id=sanitize_text_field($_POST['folder_id']);

        $platform_obj= new AWP_Platform_Shell_Table('clickup');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;

        $url = "https://api.clickup.com/api/v2/folder/".$folder_id ."/list?archived=false";
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $api_key
            )
        );

        $response = wp_remote_get( $url, $args );
        $body = wp_remote_retrieve_body( $response );
        $body = json_decode( $body, true );
        wp_send_json_success($body['lists']);
    }
    
}

$AWP_Clickup = new AWP_Clickup();

/*
 * Saves connection mapping
 */
function awp_clickup_save_integration()
{
    Appfactory::save_integration();
}


function awp_clickup_send_data( $record, $posted_data ) {
    $decoded_data = Appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('clickup');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $task = $decoded_data["task"]; 
    

    if( $task == "creategoal" ) {

        $name = empty( $data["name"] ) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $description = empty( $data["description"] ) ? "" : awp_get_parsed_values($data["description"], $posted_data);
        $due_date = empty( $data["due_date"] ) ? "" : awp_get_parsed_values($data["due_date"], $posted_data);
        $teamid = empty( $data["teamid"] ) ? "" : awp_get_parsed_values($data["teamid"], $posted_data);
        $colour = empty( $data["colour"] ) ? "" : awp_get_parsed_values($data["colour"], $posted_data);
        

        $body = json_encode([
            "name"=>$name,
            "description"=>$description,
            "due_date"=>strtotime($due_date),
            "start_date"=>strtotime(date('Y-m-d',time())),
            "color"=>$colour
        ]);

        $url = "https://api.clickup.com/api/v2/team/".$teamid."/goal";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $api_key
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } else  if( $task == "createspace" ) {

        $name = empty( $data["spacename"] ) ? "" : awp_get_parsed_values($data["spacename"], $posted_data);
        $teamid = empty( $data["teamid"] ) ? "" : awp_get_parsed_values($data["teamid"], $posted_data);
        

        $body = json_encode([
            "name"=> $name,
            "multiple_assignees"=> true,
            "features"=> [
                "due_dates"=> [
                    "enabled"=> true,
                    "start_date"=> false,
                    "remap_due_dates"=> true,
                    "remap_closed_due_date"=> false
                ],
                "time_tracking"=> [
                    "enabled"=> false
                ],
                "tags"=> [
                    "enabled"=> true
                ],
                "time_estimates"=> [
                    "enabled"=> true
                ],
                "checklists"=> [
                    "enabled"=> true
                ],
                "custom_fields"=> [
                    "enabled"=> true
                ],
                "remap_dependencies"=> [
                    "enabled"=> true
                ],
                "dependency_warning"=> [
                    "enabled"=> true
                ],
                "portfolios"=> [
                    "enabled"=> true
                ]
            ]
        ]);

        $url = "https://api.clickup.com/api/v2/team/".$teamid."/space";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $api_key
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } else  if( $task == "inviteuser" ) {

        $email = empty( $data["email"] ) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $teamid = empty( $data["teamid"] ) ? "" : awp_get_parsed_values($data["teamid"], $posted_data);
        $adminstatus = empty( $data["adminstatus"] ) ? "" : awp_get_parsed_values($data["adminstatus"], $posted_data);

        

        $body = json_encode([
            "email"=> $email,
            "admin"=> $adminstatus
        ]);

        $url = "https://api.clickup.com/api/v2/team/".$teamid."/user";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $api_key
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    }  else  if( $task == "inviteguest" ) {

        $email = empty( $data["email"] ) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $teamid = empty( $data["teamid"] ) ? "" : awp_get_parsed_values($data["teamid"], $posted_data);
        $can_edit_tags = empty( $data["can_edit_tags"] ) ? "" : awp_get_parsed_values($data["can_edit_tags"], $posted_data);
        $can_see_time_spent= empty( $data["can_see_time_spent"] ) ? "" : awp_get_parsed_values($data["can_see_time_spent"], $posted_data);
        $can_see_time_estimated= empty( $data["can_see_time_estimated"] ) ? "" : awp_get_parsed_values($data["can_see_time_estimated"], $posted_data);
        $can_create_views= empty( $data["can_create_views"] ) ? "" : awp_get_parsed_values($data["can_create_views"], $posted_data);
        

        $body = json_encode([
            "email"=> $email,
            "can_edit_tags" => $can_edit_tags,
            "can_see_time_spent" => $can_see_time_spent,
            "can_see_time_estimated" => $can_see_time_estimated,
            "can_create_views" => $can_create_views,
        ]);

        $url = "https://api.clickup.com/api/v2/team/".$teamid."/guest";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $api_key
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } else  if( $task == "createfolder" ) {

        $spaceid = empty( $data["spaceid"] ) ? "" : awp_get_parsed_values($data["spaceid"], $posted_data);
        $name = empty( $data["foldername"] ) ? "" : awp_get_parsed_values($data["foldername"], $posted_data);
        

        $body = json_encode([
            "name"=> $name
        ]);
        
        $url = "https://api.clickup.com/api/v2/space/".$spaceid."/folder";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $api_key
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } else  if( $task == "createlist" ) {

        $folder_id = empty( $data["folderid"] ) ? "" : awp_get_parsed_values($data["folderid"], $posted_data);
        $name = empty( $data["listname"] ) ? "" : awp_get_parsed_values($data["listname"], $posted_data);
        $content = empty( $data["content"] ) ? "" : awp_get_parsed_values($data["content"], $posted_data);
        $due_date = empty( $data["due_date"] ) ? "" : awp_get_parsed_values($data["due_date"], $posted_data);
        $due_date_time = false;
        $priority = empty( $data["priorityid"] ) ? "" : awp_get_parsed_values($data["priorityid"], $posted_data);
        $status = "red";
        

        $body = json_encode([
            "name"=> $name,
            "content" => $content,
            "due_date" => strtotime(date('Y-m-d',$due_date)),
            "due_date_time" => $due_date_time,
            "priority" => $priority,
            //   "assignee": 183,
            "status" => "red"
        ]);
        
        $url = "https://api.clickup.com/api/v2/folder/".$folder_id."/list";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $api_key
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } else  if( $task == "createtask" ) {

        $list_id = empty( $data["list_id"] ) ? "" : awp_get_parsed_values($data["list_id"], $posted_data);
        $name = empty( $data["taskname"] ) ? "" : awp_get_parsed_values($data["taskname"], $posted_data);
        $description = empty( $data["taskdescription"] ) ? "" : awp_get_parsed_values($data["taskdescription"], $posted_data);
        $priority = empty( $data["taskpriorityid"] ) ? "" : awp_get_parsed_values($data["taskpriorityid"], $posted_data);
        $due_date = empty( $data["task_due_date"] ) ? "" : awp_get_parsed_values($data["task_due_date"], $posted_data);
        $time_estimate = empty( $data["time_estimate"] ) ? "" : awp_get_parsed_values($data["time_estimate"], $posted_data);
        $start_date = empty( $data["start_date"] ) ? "" : awp_get_parsed_values($data["start_date"], $posted_data);
        $due_date_time = false;
        $start_date_time = false;
        $notify_all = true;


        $body = json_encode([
            "name"=> $name,
            "description" => $description,
            "due_date" => strtotime($due_date),
            "time_estimate" => strtotime($time_estimate) * 100,
            "due_date_time" => $due_date_time,
            "start_date" => strtotime($start_date),
            "start_date_time" => $start_date_time,
            "priority" => $priority,
            "Status" => 'Open',
            "notify_all" => $notify_all,
        ]);
        
        $url = "https://api.clickup.com/api/v2/list/". $list_id ."/task";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $api_key
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } 
    return $response;
}


function awp_clickup_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('clickup');
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
                'Authorization' => $api_key
            ),

            'body'=> json_encode($body)
        ];


        $return  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="XXXXXXXXXXXX";
        awp_add_to_log( $return, $url, $args, $integration );

    $response['success']=true;    
    return $response;
}
