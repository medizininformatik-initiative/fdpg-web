<?php

class AWP_Teamwork extends Appfactory
{

    // replace with live key
    const client_id = "b220e93f1e2d989199c9210aef4eb72d148641a9";
    const client_secret = "07dc84bb92d65839488ba74625dffab928457133";

    protected function get_redirect_uri()
    {
        // return 'https://sperse.io/scripts/authorization/auth.php';
        return 'http://localhost/wp/wp-json/automatehub/teamwork';
    }

    public function init_actions()
    {
        add_action('admin_post_awp_teamwork_save_api_token', [$this, 'awp_save_teamwork_api_token'], 10, 0);
        add_action("rest_api_init", [$this, "create_webhook_route"]);
    }

    public function init_filters()
    {
        add_filter('awp_platforms_connections', [$this, 'awp_teamwork_platform_connection'], 10, 1);
    }

    public function get_callback()
    {
        return get_rest_url(null,'automatehub/teamwork');
    }

    public function getLoginURL(): string
    {
        $query = [
            'client_id' => self::client_id,
            'redirect_uri' => $this->get_redirect_uri(),
        ];
        $authorization_endpoint = "https://www.teamwork.com/launchpad/login?";

        return add_query_arg($query, $authorization_endpoint);
    }

    public function create_webhook_route()
    {
        register_rest_route('automatehub', '/teamwork',
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

        if ( isset( $params['code'] ) ) {

            $url = "https://www.teamwork.com/launchpad/v1/token.json";

            $teamwork_code = sanitize_text_field($params['code']);

            
            $args = [
                'headers' => [
                    'content-type' => 'application/json'
                ],
                'body' => json_encode([
                    "code" => $teamwork_code,
                    "client_secret" => self::client_secret,
                    "redirect_uri" => $this->get_redirect_uri(),
                    "client_id" => self::client_id
                ])
            ];


            $response = wp_remote_post($url, $args);
            $response_decode = json_decode($response['body'], true);
            
            if(isset($teamwork_code)){

                $platform_obj= new AWP_Platform_Shell_Table('teamwork');

                $platform_obj->save_platform(['account_name'=> $response_decode['installation']['name'],'api_key'=>$response_decode['access_token'], 'url'=> $response_decode['installation']['url']]);
            }
            
        }
        
        wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=teamwork'));
        exit();
    }

    public function action_provider($actions)
    {
        $actions['teamwork'] = [
            'title' => esc_html__('Teamwork', 'automate_hub'),
            'tasks' => array(
                'createproject' => esc_html__('Create Project', 'automate_hub'),
                
            ),
        ];
        return $actions;
    }

    public function settings_tab($tabs)
    {
        $tabs['teamwork'] = array('name' => esc_html__('Teamwork', 'automate_hub'), 'cat' => array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'teamwork') {
            return;
        }
        $nonce = wp_create_nonce("awp_teamwork_settings");
        $api_token = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/teamwork" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/teamwork.png" height="50" alt="teamwork Logo"></a><br /><br />
                <?php
        require_once AWP_INCLUDES . '/class_awp_updates_manager.php';
        $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
        $instruction_obj->prepare_instructions();

        ?><br />
            <form name="teamwork_save_form" action="<?=esc_url(admin_url('admin-post.php'));?>" method="post" class="container">
                <input type="hidden" name="action" value="awp_teamwork_save_api_token">
                <input type="hidden" name="_nonce" value="<?=$nonce?>" />
                <input type="hidden" name="id" value="<?=$id?>" />
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"> </th>
                        <td>
                            <a onclick="teamworkauthbtn()" target="_blank" id="teamworkauthbtn" class="button button-primary"> Connect Your Teamwork Account </a>
                        </td>
                    </tr>
                    <script type="text/javascript">
                            function teamworkauthbtn(){
                                var win=window.open('<?php echo $this->getLoginURL()?>','popup','width=600,height=600');
                                var id = setInterval(function() {
                                const queryString = win.location.search;
                                const urlParams = new URLSearchParams(queryString);
                                const page_type = urlParams.get('page');
                                if(page_type=='automate_hub'){win.close(); clearInterval(id); location.reload();}
                                }, 1000);

                            }
                            
                        </script>
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
        $platform_obj = new AWP_Platform_Shell_Table('teamwork');
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
        wp_enqueue_script('awp-teamwork-script', AWP_URL . '/apps/t/teamwork/teamwork.js', array('awp-vuejs'), '', 1);
    }

    public function action_fields()
    {
        ?>
        <script type="text/template" id="teamwork-action-template">
            <?php

                $app_data=array(
                    'app_slug'=>'teamwork',
                    'app_name'=>'Teamwork',
                    'app_icon_url'=>AWP_ASSETS.'/images/icons/teamwork.png',
                    'app_icon_alter_text'=>'Teamwork  Icon',
                    'account_select_onchange'=>'getsourcesid',
                    'tasks'=>array(
                        'creategroup'=>array(
                            'task_assignments'=>array(
                                array(
                                    'label'=>'Select Backup Status',
                                    'type'=>'select',
                                    'name'=>"backupstatusid",
                                    'required' => 'required',
                                    'onchange' => 'selectedsource',
                                    'option_for_loop'=>'(item) in data.backupstatus',
                                    'option_for_value'=>'item.id',
                                    'option_for_text'=>'{{item.name}}',
                                    'select_default'=>'Select Backup Status...'
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

}

$AWP_Teamwork = new AWP_Teamwork();

/*
 * Saves connection mapping
 */
function awp_teamwork_save_integration()
{
    Appfactory::save_integration();
}


function awp_teamwork_send_data( $record, $posted_data ) {
    
    $decoded_data = Appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 

    

    $platform_obj= new AWP_Platform_Shell_Table('teamwork');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $url=$temp->url;
    $task = $decoded_data["task"]; 

    $token = 'Bearer '.$api_key;


    if($task == "createproject"){

        $name = empty( $data["name"] ) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $description = empty( $data["description"] ) ? "" : awp_get_parsed_values($data["description"], $posted_data);
        $startdate = empty( $data["startdate"] ) ? "" : awp_get_parsed_values($data["startdate"], $posted_data);
        $enddate = empty( $data["enddate"] ) ? "" : awp_get_parsed_values($data["enddate"], $posted_data);
    
        $body = json_encode([
            "project" => [
                "name" => $name,
                "description" => $description,
                "use-tasks" => (integer) true,
                "use-milestones" => (integer) true,
                "use-messages" => (integer) true,
                "use-files" => (integer) true,
                "use-time" => (integer) true,
                "use-notebook" => (integer) true,
                "use-riskregister" => (integer) true,
                "use-links" => (integer) true,
                "use-billing" => (integer) true,
                "use-comments" => (integer) true,
                "start-date" => $startdate,
                "end-date" => $enddate,
                "onboarding" => (boolean) true,
                "projectOwnerId" => (integer) 67662,
                "companyId" => (integer) 18564
            ]
        ]);
    
        $url = $url."teamwork/projects.json";
    
        $args = [
            'headers' => array(
                "Content-Type" => "application/json",
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


function awp_teamwork_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('teamwork');
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

