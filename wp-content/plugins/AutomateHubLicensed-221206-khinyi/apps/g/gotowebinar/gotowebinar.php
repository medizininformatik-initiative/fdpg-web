<?php

class AWP_Gotowebinar extends Appfactory
{

    // replace with live key
    const client_id = "4f92db44-2239-425b-b586-4ea5145ca057";

    protected function get_redirect_uri()
    {        
        return 'https://sperse.io/scripts/authorization/auth.php';
    }

    public function init_actions()
    {
        add_action("rest_api_init", [$this, "create_webhook_route"]);
        add_action( 'wp_ajax_awp_get_accounts', [$this, 'awp_get_accounts']);
    }

    public function init_filters()
    {
        add_filter('awp_platforms_connections', [$this, 'awp_gotowebinar_platform_connection'], 10, 1);
    }

    public function get_callback()
    {
        return get_rest_url(null,'automatehub/gotowebinar');
    }

    public function getLoginURL(): string
    {
        $query = [
            'client_id' => self::client_id,
            'response_type'=>'code',
            'redirect_uri' => urlencode($this->get_redirect_uri()),
            'state' => $this->get_callback(),
        ];
        $authorization_endpoint = "https://authentication.logmeininc.com/oauth/authorize?";

        return add_query_arg($query, $authorization_endpoint);
    }

    public function create_webhook_route()
    {
        register_rest_route('automatehub', '/gotowebinar',
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
        if (isset( $params['access_token'] )) {
            $platform_obj = new AWP_Platform_Shell_Table('gotowebinar');

            $platform_obj->save_platform(['account_name' => $params['original_response']['principal'], 'api_key' => $params['access_token']]);
        }
            wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=gotowebinar'));
            exit();
    }

    public function action_provider($actions)
    {
        $actions['gotowebinar'] = [
            'title' => esc_html__('Gotowebinar', 'automate_hub'),
            'tasks' => array(
                'createwebinar' => esc_html__('Create webinar', 'automate_hub')
            ),
        ];
        return $actions;
    }

    public function settings_tab($tabs)
    {
        $tabs['gotowebinar'] = array('name' => esc_html__('Gotowebinar', 'automate_hub'), 'cat' => array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'gotowebinar') {
            return;
        }
        $nonce = wp_create_nonce("awp_gotowebinar_settings");
        $api_token = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/gotowebinar" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/gotowebinar.png"  height="40" alt="gotowebinar Logo"></a><br /><br />
                <?php
        require_once AWP_INCLUDES . '/class_awp_updates_manager.php';
        $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
        $instruction_obj->prepare_instructions();

        ?><br />
            <form name="gotowebinar_save_form" action="<?=esc_url(admin_url('admin-post.php'));?>" method="post" class="container">
                <input type="hidden" name="action" value="awp_gotowebinar_save_api_token">
                <input type="hidden" name="_nonce" value="<?=$nonce?>" />
                <input type="hidden" name="id" value="<?=$id?>" />
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"> </th>
                        <td>
                            <a id="btngotowebinarauth" class="button button-primary"> Connect Your Gotowebinar Account </a>
                        </td>
                    </tr>
                    <script type="text/javascript">
                            document.getElementById("btngotowebinarauth").addEventListener("click", function(){
                                var win=window.open('<?php echo $this->getLoginURL()?>','popup','width=600,height=600');
                                var id = setInterval(function() {
                                const queryString = win.location.search;
                                const urlParams = new URLSearchParams(queryString);
                                const page_type = urlParams.get('page');
                                if(page_type=='automate_hub'){win.close(); clearInterval(id); location.reload();}
                                }, 1000);
                            });
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
        $platform_obj = new AWP_Platform_Shell_Table('gotowebinar');
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
        wp_enqueue_script('awp-gotowebinar-script', AWP_URL . '/apps/g/gotowebinar/gotowebinar.js', array('awp-vuejs'), '', 1);
    }

    public function action_fields()
    {
        ?>
        <script type="text/template" id="gotowebinar-action-template">
            <?php

                $app_data=array(
                    'app_slug'=>'gotowebinar',
                    'app_name'=>'Gotowebinar',
                    'app_icon_url'=>AWP_ASSETS.'/images/icons/gotowebinar.png',
                    'app_icon_alter_text'=>'Gotowebinar  Icon',
                    'account_select_onchange'=>'getwebinartype',
                    'tasks'=>array(
                        'createwebinar'=>array(
                            'task_assignments'=>array(
                                array(
                                    'label'=>'Select webinar Type',
                                    'type'=>'select',
                                    'name'=>"webinartype",
                                    'required' => 'required',
                                    'onchange' => 'getdemandstatus',
                                    'option_for_loop'=>'(item) in data.webinartypelist',
                                    'option_for_value'=>'item.id',
                                    'option_for_text'=>'{{item.name}}',
                                    'select_default'=>'Select webnier Type...'
                                ),
                                array(
                                    'label'=>'Select Demand Status',
                                    'type'=>'select',
                                    'name'=>"isOndemand",
                                    'required' => 'required',
                                    'onchange' => 'getexperiencetype',
                                    'option_for_loop'=>'(item) in data.demandstatuslist',
                                    'option_for_value'=>'item.id',
                                    'option_for_text'=>'{{item.name}}',
                                    'select_default'=>'Select Demand...'
                                ),
                                array(
                                    'label'=>'Select Webinar Experience Type',
                                    'type'=>'select',
                                    'name'=>"experienceType",
                                    'required' => 'required',
                                    'onchange' => 'getpasswordprotectionstatus',
                                    'option_for_loop'=>'(item) in data.experiencetypelist',
                                    'option_for_value'=>'item.id',
                                    'option_for_text'=>'{{item.name}}',
                                    'select_default'=>'Select Experience type...'
                                ),
                                array(
                                    'label'=>'Select Password Requirement Status',
                                    'type'=>'select',
                                    'name'=>"passwordstatus",
                                    'required' => 'required',
                                    'onchange' => 'selectedpassword',
                                    'option_for_loop'=>'(item) in data.passwordstatuslist',
                                    'option_for_value'=>'item.id',
                                    'option_for_text'=>'{{item.name}}',
                                    'select_default'=>'Select Password Requirement Status...'
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

    public static function awp_get_accounts($key){
        $url = "https://api.getgo.com/admin/rest/v1/me?includeAdmins=true&includeInvitation=true";

        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $key
            )
        );

        $response = wp_remote_get( $url, $args );
        $retrievebody = wp_remote_retrieve_body( $response);
        $body = json_decode( $retrievebody, true );
        return($body['key']);
        
    }

}

$AWP_Gotowebinar = new AWP_Gotowebinar();

/*
 * Saves connection mapping
 */
function awp_gotowebinar_save_integration()
{
    Appfactory::save_integration();
}


function awp_gotowebinar_send_data( $record, $posted_data ) {
    $decoded_data = Appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('gotowebinar');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $task = $decoded_data["task"]; 
    $token = 'Bearer '.$api_key;
    

    $organizers_key = AWP_Gotowebinar::awp_get_accounts($token);

    if( $task == "createwebinar" ) {

        $subject = empty( $data["subject"] ) ? "" : awp_get_parsed_values($data["subject"], $posted_data);
        $description = empty( $data["description"] ) ? "" : awp_get_parsed_values($data["description"], $posted_data);
        $starttime = empty( $data["starttime"] ) ? "" : awp_get_parsed_values($data["starttime"], $posted_data);
        $endtime = empty( $data["endtime"] ) ? "" : awp_get_parsed_values($data["endtime"], $posted_data);
        $type = empty( $data["webinartype"] ) ? "" : awp_get_parsed_values($data["webinartype"], $posted_data);
        $passwordprotectionstatus = empty( $data["passwordstatus"] ) ? "" : awp_get_parsed_values($data["passwordstatus"], $posted_data);
        $isOndemand = empty( $data["isOndemand"] ) ? "" : awp_get_parsed_values($data["isOndemand"], $posted_data);
        $experienceType = empty( $data["experienceType"] ) ? "" : awp_get_parsed_values($data["experienceType"], $posted_data);
        

        $body = json_encode([
            "subject"=>$subject,
            "times"=> [
                [
                    "startTime"=>date('Y-m-d\TH:i:s\z', strtotime($starttime)),
                    "endTime"=>date('Y-m-d\TH:i:s\z', strtotime($endtime)),
                ]
            ],
            "description"=>$description,
           
            "type"=>$type,
            "isPasswordProtected"=>$passwordprotectionstatus,
            "isOndemand"=>$isOndemand,
            "experienceType"=>$experienceType,
            "emailSettings"=> [
                "confirmationEmail"=> [
                    "enabled" => "true"
                ],
                "reminderEmail"=> [
                    "enabled" => "true"
                ],
                "absenteeFollowUpEmail"=> [
                    "enabled" => "true"
                ],
                "attendeeFollowUpEmail"=> [
                    "enabled" => "true"
                ],
            ]
            
        ]);

        $url = "https://api.getgo.com/G2W/rest/v2/organizers/".$organizers_key."/webinars";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $token,
                'Accept' => 'application/json'
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } 
    return $response;
}


function awp_gotowebinar_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('gotowebinar');
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
                'Authorization' => $token
            ),

            'body'=> json_encode($body)
        ];


        $return  = wp_remote_post($campfireUrl,  $args );

        $args['headers']['Authorization']="Bearer XXXXXXXXXXXX";
        awp_add_to_log( $return, $campfireUrl, $args, $integration );

    $response['success']=true;    
    return $response;
}

