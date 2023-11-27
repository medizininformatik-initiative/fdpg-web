<?php

class AWP_Cleverreach extends Appfactory
{

    // replace with live key
    const client_id = "fjjfcB49rL";
    const client_secret = "WiMO171iD93MJIaXkTBLoH8gW1o0jwTp";

    protected function get_redirect_uri()
    {
        return 'https://sperse.io/scripts/authorization/auth.php';
    }

    public function init_actions()
    {
        add_action('admin_post_awp_cleverreach_save_api_token', [$this, 'awp_save_cleverreach_api_token'], 10, 0);
        add_action("rest_api_init", [$this, "create_webhook_route"]);
    }

    public function init_filters()
    {
        add_filter('awp_platforms_connections', [$this, 'awp_cleverreach_platform_connection'], 10, 1);
    }

    public function get_callback()
    {
        return get_rest_url(null,'automatehub/cleverreach');
    }

    public function getLoginURL(): string
    {
        $query = [
            'client_id' => self::client_id,
            'response_type' => "code",
            'grant' => "basic",
            'redirect_uri' => urlencode($this->get_redirect_uri()),
            'state' => $this->get_callback(),
        ];
        $authorization_endpoint = "https://rest.cleverreach.com/oauth/authorize.php?";

        return add_query_arg($query, $authorization_endpoint);
    }

    public function create_webhook_route()
    {
        register_rest_route('automatehub', '/cleverreach',
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

              $cleverreach_access_token = sanitize_text_field($params['access_token']);
              
              $query="select * from ".$wpdb->prefix."awp_platform_settings where platform_name='cleverreach'";

              $data=$wpdb->get_results($query);

              $len=count($data) + 1;

              $platform_obj= new AWP_Platform_Shell_Table('cleverreach');

              $platform_obj->save_platform(['account_name'=>'Account Number '.$len,'api_key'=>$cleverreach_access_token, 'client_secret'=>$params['refresh_token']]);
        
            }


        
        
        wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=cleverreach'));
        exit();
    }

    public function action_provider($actions)
    {
        $actions['cleverreach'] = [
            'title' => esc_html__('CleverReach', 'automate_hub'),
            'tasks' => array(
                'creategroup' => esc_html__('Create Group', 'automate_hub'),
                'createmailing' => esc_html__('Create Mailing', 'automate_hub')
            ),
        ];
        return $actions;
    }

    public function settings_tab($tabs)
    {
        $tabs['cleverreach'] = array('name' => esc_html__('CleverReach', 'automate_hub'), 'cat' => array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'cleverreach') {
            return;
        }
        $nonce = wp_create_nonce("awp_cleverreach_settings");
        $api_token = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/cleverreach" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/cleverreach.png" width="292" height="50" alt="cleverreach Logo"></a><br /><br />
                <?php
        require_once AWP_INCLUDES . '/class_awp_updates_manager.php';
        $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
        $instruction_obj->prepare_instructions();

        ?><br />
            <form name="cleverreach_save_form" action="<?=esc_url(admin_url('admin-post.php'));?>" method="post" class="container">
                <input type="hidden" name="action" value="awp_cleverreach_save_api_token">
                <input type="hidden" name="_nonce" value="<?=$nonce?>" />
                <input type="hidden" name="id" value="<?=$id?>" />
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"> </th>
                        <td>
                            <a href="<?=$this->getLoginURL()?>" class="button button-primary"> Connect Your Cleverreach Account </a>
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
        $platform_obj = new AWP_Platform_Shell_Table('cleverreach');
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
        wp_enqueue_script('awp-cleverreach-script', AWP_URL . '/apps/c/cleverreach/cleverreach.js', array('awp-vuejs'), '', 1);
    }

    public function action_fields()
    {
        ?>
        <script type="text/template" id="cleverreach-action-template">
            <?php

                $app_data=array(
                    'app_slug'=>'cleverreach',
                    'app_name'=>'Cleverreach',
                    'app_icon_url'=>AWP_ASSETS.'/images/icons/cleverreach.png',
                    'app_icon_alter_text'=>'Cleverreach  Icon',
                    'account_select_onchange'=>'getbackupdetails',
                    'tasks'=>array(
                        'creategroup'=>array(
                            'task_assignments'=>array(
                                array(
                                    'label'=>'Select Backup Status',
                                    'type'=>'select',
                                    'name'=>"backupstatusid",
                                    'required' => 'required',
                                    'onchange' => 'selectedstatus',
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

$AWP_Cleverreach = new AWP_Cleverreach();

/*
 * Saves connection mapping
 */
function awp_cleverreach_save_integration()
{
    Appfactory::save_integration();
}


function awp_refresh_cleverreach_token($refresh_token, $old_accessToken)
{


    $license_key  = get_option('sperse_license_key');
    $data['licenseKey']=$license_key;
    $data['refresh_token']=$refresh_token;
    $data['action']='cleverreach_refresh';
    $args = array(
        'method' => 'POST',
        'headers'  => array('Content-type: application/x-www-form-urlencoded'),
        'sslverify' => false,
        'body' => $data,
        'timeout'=>'45'
    );

    $returned = wp_remote_post('https://sperse.io/scripts/authorization/auth.php', $args );
        
    if (is_wp_error($returned)){
        echo "Unexpected Error! The query returned with an error.";
    }
    $decoded_data = json_decode($returned['body']);
    $new_accessToken = $decoded_data->access_token;
    if (isset($new_accessToken)) {
        global $wpdb;
        $res = $wpdb->update($wpdb->prefix . 'awp_platform_settings', ['api_key' => $new_accessToken], ['api_key' => $old_accessToken]);
    }
    return $new_accessToken;       

}

function awp_cleverreach_send_data( $record, $posted_data ) {
    $decoded_data = Appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('cleverreach');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $task = $decoded_data["task"]; 
    $token = 'Bearer '.$api_key;
    $refresh_token = $temp->client_secret;
    

    if( $task == "creategroup" ) {

        $name = empty( $data["name"] ) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $receiver_info = empty( $data["receiver_info"] ) ? "" : awp_get_parsed_values($data["receiver_info"], $posted_data);
        $backup = empty( $data["backupstatusid"] ) ? "" : awp_get_parsed_values($data["backupstatusid"], $posted_data);
        $locked = false;

        $body = json_encode([
            "name"=>$name,
            "receiver_info"=>$receiver_info,
            "backup"=>$backup,
            "locked"=>$locked
        ]);

        $url = "https://rest.cleverreach.com/v3/groups.json";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $token,
                'Accept' => 'application/json'
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );
        
        $error_data = json_decode($response['body'])->error;

        if ($error_data->code == 401) {
            $new_accessToken = awp_refresh_cleverreach_token($refresh_token, $token);
            if (isset($new_accessToken)) {
                $args['headers']['Authorization'] = 'Bearer ' . $new_accessToken;
                $returned_data = wp_remote_request($url, $args);
                $args['headers']['Authorization'] = 'Bearer XXXXXXXXXXX';
                $args['body'] = $body;
                awp_add_to_log($returned_data, $url, $args, $record);
            }
        } else {
            $args['headers']['Authorization'] = 'Bearer XXXXXXXXXXX';
            $args['body'] = $body;
            awp_add_to_log($response, $url, $args, $record);
        }

    } else if( $task == "createmailing" ) {

        $name = empty( $data["name"] ) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $subject = empty( $data["subject"] ) ? "" : awp_get_parsed_values($data["subject"], $posted_data);
        $sender_name = empty( $data["sender_name"] ) ? "" : awp_get_parsed_values($data["sender_name"], $posted_data);
        $sender_email = empty( $data["sender_email"] ) ? "" : awp_get_parsed_values($data["sender_email"], $posted_data);
        $content = empty( $data["content"] ) ? "" : awp_get_parsed_values($data["content"], $posted_data);

        $body = json_encode([
            "name"=>$name,
            "subject"=>$subject,
            "sender_name"=>$sender_name,
            "sender_email"=>$sender_email,
            "content"=> [
                'type' => 'html/text',    
                'subject' => '<html><body>'.$content.'</body></html>',
                'text'=>$content
            ]
        ]);

        $url = "https://rest.cleverreach.com/v3/mailings.json";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $token,
                'Accept' => 'application/json'
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );
        
        $error_data = json_decode($response['body'])->error;

        if ($error_data->code == 401) {
            $new_accessToken = awp_refresh_cleverreach_token($refresh_token, $token);
            if (isset($new_accessToken)) {
                $args['headers']['Authorization'] = 'Bearer ' . $new_accessToken;
                $returned_data = wp_remote_request($url, $args);
                $args['headers']['Authorization'] = 'Bearer XXXXXXXXXXX';
                $args['body'] = $body;
                awp_add_to_log($returned_data, $url, $args, $record);
            }
        } else {
            $args['headers']['Authorization'] = 'Bearer XXXXXXXXXXX';
            $args['body'] = $body;
            awp_add_to_log($response, $url, $args, $record);
        }

    }
    return $response;
}


function awp_cleverreach_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('cleverreach');
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

