<?php

class AWP_Gotomeeting extends Appfactory
{

    // replace with live key
    const client_id = "4f92db44-2239-425b-b586-4ea5145ca057";

    protected function get_redirect_uri()
    {        
        return 'https://sperse.io/scripts/authorization/auth.php';
    }

    public function init_actions()
    {
        add_action('admin_post_awp_gotomeeting_save_api_token', [$this, 'awp_save_gotomeeting_api_token'], 10, 0);
        add_action("rest_api_init", [$this, "create_webhook_route"]);
    }

    public function init_filters()
    {
        add_filter('awp_platforms_connections', [$this, 'awp_gotomeeting_platform_connection'], 10, 1);
    }

    public function get_callback()
    {
        return get_rest_url(null,'automatehub/gotomeeting');
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
        register_rest_route('automatehub', '/gotomeeting',
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
            $platform_obj = new AWP_Platform_Shell_Table('gotomeeting');

            $platform_obj->save_platform(['account_name' => $params['original_response']['principal'], 'api_key' => $params['access_token']]);
        }


            wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=gotomeeting'));
            exit();
    }

    public function action_provider($actions)
    {
        $actions['gotomeeting'] = [
            'title' => esc_html__('Gotomeeting', 'automate_hub'),
            'tasks' => array(
                'createmeeting' => esc_html__('Create Meeting', 'automate_hub')
            ),
        ];
        return $actions;
    }

    public function settings_tab($tabs)
    {
        $tabs['gotomeeting'] = array('name' => esc_html__('Gotomeeting', 'automate_hub'), 'cat' => array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'gotomeeting') {
            return;
        }
        $nonce = wp_create_nonce("awp_gotomeeting_settings");
        $api_token = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/gotomeeting" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/gotomeeting.png"  height="40" alt="gotomeeting Logo"></a><br /><br />
                <?php
        require_once AWP_INCLUDES . '/class_awp_updates_manager.php';
        $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
        $instruction_obj->prepare_instructions();

        ?><br />
            <form name="gotomeeting_save_form" action="<?=esc_url(admin_url('admin-post.php'));?>" method="post" class="container">
                <input type="hidden" name="action" value="awp_gotomeeting_save_api_token">
                <input type="hidden" name="_nonce" value="<?=$nonce?>" />
                <input type="hidden" name="id" value="<?=$id?>" />
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"> </th>
                        <td>
                            <a id="btngotomeetingauth" class="button button-primary"> Connect Your Gotomeeting Account </a>
                        </td>
                    </tr>
                    <script type="text/javascript">
                            document.getElementById("btngotomeetingauth").addEventListener("click", function(){
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
        $platform_obj = new AWP_Platform_Shell_Table('gotomeeting');
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
        wp_enqueue_script('awp-gotomeeting-script', AWP_URL . '/apps/g/gotomeeting/gotomeeting.js', array('awp-vuejs'), '', 1);
    }

    public function action_fields()
    {
        ?>
        <script type="text/template" id="gotomeeting-action-template">
            <?php

                $app_data=array(
                    'app_slug'=>'gotomeeting',
                    'app_name'=>'Gotomeeting',
                    'app_icon_url'=>AWP_ASSETS.'/images/icons/gotomeeting.png',
                    'app_icon_alter_text'=>'Gotomeeting  Icon',
                    'account_select_onchange'=>'getmeetingtype',
                    'tasks'=>array(
                        'createmeeting'=>array(
                            'task_assignments'=>array(
                                array(
                                    'label'=>'Select Meeting Type',
                                    'type'=>'select',
                                    'name'=>"role",
                                    'required' => 'required',
                                    'onchange' => 'passwordinfo',
                                    'option_for_loop'=>'(item) in data.meetingtypelist',
                                    'option_for_value'=>'item.id',
                                    'option_for_text'=>'{{item.name}}',
                                    'select_default'=>'Select Meeting Type...'
                                ),
                                array(
                                    'label'=>'Select Password Requirement Status',
                                    'type'=>'select',
                                    'name'=>"role",
                                    'required' => 'required',
                                    'onchange' => '',
                                    'option_for_loop'=>'(item) in data.passwordrequirementinfo',
                                    'option_for_value'=>'item.id',
                                    'option_for_text'=>'{{item.name}}',
                                    'select_default'=>'Select Password Requirement...'
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

$AWP_Gotomeeting = new AWP_Gotomeeting();

/*
 * Saves connection mapping
 */
function awp_gotomeeting_save_integration()
{
    Appfactory::save_integration();
}


function awp_gotomeeting_send_data( $record, $posted_data ) {
    $decoded_data = Appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('gotomeeting');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $task = $decoded_data["task"]; 
    $token = 'Bearer '.$api_key;

    if( $task == "createmeeting" ) {

        $subject = empty( $data["subject"] ) ? "" : awp_get_parsed_values($data["subject"], $posted_data);
        $starttime = empty( $data["starttime"] ) ? "" : awp_get_parsed_values($data["starttime"], $posted_data);
        $endtime = empty( $data["endtime"] ) ? "" : awp_get_parsed_values($data["endtime"], $posted_data);
        $passwordrequired = empty( $data["passwordrequirementstatus"] ) ? "" : awp_get_parsed_values($data["passwordrequirementstatus"], $posted_data);
        $meetingtype = empty( $data["meetingtype"] ) ? "" : awp_get_parsed_values($data["meetingtype"], $posted_data);
        $conferencecallinfo = empty( $data["callinfo"] ) ? "" : awp_get_parsed_values($data["callinfo"], $posted_data);


        $body = json_encode([
            "subject"=>$subject,
            "starttime"=>date('Y-m-d\TH:i:s\z', strtotime($starttime)),
            "endtime"=>date('Y-m-d\TH:i:s\z', strtotime($endtime)),
            "passwordrequired"=>$passwordrequired,
            "conferencecallinfo"=>"VoIP",
            "meetingtype"=>$meetingtype,
            "timezonekey"=>""
            
        ]);

        $url = "https://api.getgo.com/G2M/rest/meetings";

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


function awp_gotomeeting_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('gotomeeting');
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
                'Authorization' => $token
            ),

            'body'=> json_encode($body)
        ];


        $return  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="Bearer XXXXXXXXXXXX";
        awp_add_to_log( $return, $url, $args, $integration );

    $response['success']=true;    
    return $response;
}

