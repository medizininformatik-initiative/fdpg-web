<?php

class AWP_GoogleDrive extends Appfactory
{

    const client_id = "119614386927-anu0t5adv9726umkv6i257578daqrtpe.apps.googleusercontent.com";

    protected function get_redirect_uri()
    {
        // return get_rest_url(null, 'automatehub/googledrive');
        return "https://sperse.io/scripts/authorization/auth.php";
    }

    public function init_actions()
    {
        add_action('admin_post_awp_googledrive_save_api_token', [$this, 'awp_refresh_googledrive_api_token'], 10, 0);
        add_action("rest_api_init", [$this, "create_webhook_route"]);
    }

    public function init_filters()
    {
        add_filter('awp_platforms_connections', [$this, 'awp_googledrive_platform_connection'], 10, 1);
    }

    public function get_callback()
    {
        return get_rest_url(null, 'automatehub/googledrive');
    }

    public function create_webhook_route()
    {
        register_rest_route('automatehub', '/googledrive',
            [
                'methods' => 'GET',
                'callback' => [$this, 'get_webhook_data'],
                'permission_callback' => function () {return '';},
            ]);
    }

    public function get_webhook_data($request)
    {
        $params = $request->get_params();
        $code = isset($params['code']) ? trim($params['code']) : '';

        if ($code) {
            $get_profile_endpoint = "https://www.googleapis.com/oauth2/v1/userinfo?access_token=" . $params['access_token'];
            $args = array(
                'headers' => array(
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ),
            );

            $return = wp_remote_request($get_profile_endpoint, $args);
            $user_email = json_decode($return['body'])->email;

            if (isset($params['access_token'])) {
                $platform_obj = new AWP_Platform_Shell_Table('googleauth');
                $platform_obj->save_platform(['email' => $user_email, 'api_key' => $params['access_token'], 'client_secret' => $params['refresh_token']]); 
            }
            wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=googledrive'));
            exit();
        }
    }

    public function action_provider($actions)
    {
        $actions['googledrive'] = [
            'title' => esc_html__('Google Drive', 'automate_hub'),
            'tasks' => array('create_file' => esc_html__('Create File from Text', 'automate_hub')),
        ];
        return $actions;
    }

    public function settings_tab($tabs)
    {
        $tabs['googledrive'] = array('name' => esc_html__('Google Drive', 'automate_hub'), 'cat' => array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'googledrive') {
            return;
        }
        $nonce = wp_create_nonce("awp_googledrive_settings");
        $api_token = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/googledrive" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/googledrive.png" width="292" height="50" alt="Google Drive Logo"></a><br /><br />
            <?php
require_once AWP_INCLUDES . '/class_awp_updates_manager.php';
        $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
        $instruction_obj->prepare_instructions();

        ?><br />
            <form name="googledrive_save_form" action="<?=esc_url(admin_url('admin-post.php'));?>" method="post" class="container">
                <input type="hidden" name="action" value="awp_googledrive_save_api_token">
                <input type="hidden" name="_nonce" value="<?=$nonce?>" />
                <input type="hidden" name="id" value="<?=$id?>" />
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"> </th>
                        <td>
                            <a style="cursor:pointer;" id="googleauthbtn" target="popup"><img src="<?php echo AWP_ASSETS . '/images/buttons/btn_google_signin_dark_normal_web.png' ?>">
                                <div class="googletest"></div>
                            </a>

                            <script type="text/javascript">
                        document.getElementById("googleauthbtn").addEventListener("click", function(){
                        var win=window.open('https://accounts.google.com/o/oauth2/auth?access_type=offline&approval_prompt=force&client_id=<?=self::client_id;?>&redirect_uri=<?=$this->get_redirect_uri();?>&response_type=code&scope=https://www.googleapis.com/auth/drive+https://www.googleapis.com/auth/userinfo.email&state=<?=$this->get_callback();?>!!page=automate_hub&tab=googledrive','popup','width=600,height=600');
                        var id = setInterval(function() {
                        const queryString = win.location.search;
                        const urlParams = new URLSearchParams(queryString);
                        const page_type = urlParams.get('page');
                        if(page_type=='automate_hub'){win.close(); clearInterval(id); location.reload();}
                        }, 1000);
                    });
                    </script>
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
            'table-cols' => ['email' => 'Email Address', 'active_status' => 'Active'],
        ];
        $platform_obj = new AWP_Platform_Shell_Table('googleauth');
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
        wp_enqueue_script('awp-googledrive-script', AWP_URL . '/apps/g/googledrive/googledrive.js', array('awp-vuejs'), '', 1);
    }

    public function action_fields()
    {
        ?>
        <script type="text/template" id="googledrive-action-template">
            <?php
$app_data = array(
            'app_slug' => 'googledrive',
            'app_name' => 'Google Drive',
            'app_icon_url' => AWP_ASSETS . '/images/icons/googledrive.png',
            'app_icon_alter_text' => 'Google Drive',
            'account_select_onchange' => '',
            'tasks' => array(
                'create_file' => array(
                    'task_assignments' => array(

                        array(
                            'label' => 'Select File Type',
                            'type' => 'select',
                            'name' => "list_id",
                            'required' => 'required',
                            'model' => 'fielddata.list',
                            'option_for_loop' => '(item, index) in fielddata.fileList',
                            'select_default' => 'Select File Extension...',
                            'spinner' => array(
                                'bind-class' => "{'is-active': listLoading}",
                            ),
                        ),

                    ),

                ),

            ),
        );

        require AWP_VIEWS . '/awp_app_integration_format.php';
        ?>
    </script>
<?php
}

}

$AWP_GoogleDrive = new AWP_GoogleDrive();

/*
 * Saves connection mapping
 */
function awp_googledrive_save_integration()
{
    Appfactory::save_integration();
}

/*
 * Handles sending data to googledrive API
 */
function awp_googledrive_send_data($record, $posted_data)
{

    $temp = json_decode(($record["data"]), true);
    $temp = $temp["field_data"];
    $platform_obj = new AWP_Platform_Shell_Table('googledrive');
    $temp = $platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);

    $access_token = $temp->api_key;
    $refresh_token = $temp->client_secret;

    if (!$access_token) {
        return;
    }

    $decoded_data = AWP_GoogleDrive::decode_data($record, $posted_data);
    $data = $decoded_data["data"];
    $task = $decoded_data["task"];
    $file_extension = $data['list'];

    if ($task == "create_file") {
        $title = empty($data["title"]) ? "" : awp_get_parsed_values($data["title"], $posted_data);

        $cf = empty($data["customFields"]) ? "" : awp_get_parsed_values($data["customFields"], $posted_data);

        $data = [
            'name' => $title . $file_extension,
        ];

        if ($cf) {
            $cf = explode(",", $cf);
            $data["customFields"] = $cf;
        }

        $url = "https://www.googleapis.com/drive/v3/files";

        $args = array(
            'method' => 'POST',
            'headers' => array(
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $access_token,
                "Content-Type" => "application/json",
            ),
            'body' => json_encode($data),
        );


        $return = wp_remote_request($url, $args);

        $error_data = isset(json_decode($return['body'])->error)? json_decode($return['body'])->error:'';
        if (isset($error_data->code) && $error_data->code == 401) {
            $new_accessToken = awp_refresh_googledrive_token($refresh_token, $access_token);
            if (isset($new_accessToken)) {
                $args['headers']['Authorization'] = 'Bearer ' . $new_accessToken;
                $returned_data = wp_remote_request($url, $args);
                $args['headers']['Authorization'] = 'Bearer XXXXXXXXXXX';
                $args['body'] = $data;
                awp_add_to_log($returned_data, $url, $args, $record);
            }
        } else {
            $args['headers']['Authorization'] = 'Bearer XXXXXXXXXXX';
            $args['body'] = $data;
            awp_add_to_log($return, $url, $args, $record);
        }
    }
    return $return;
}

function awp_refresh_googledrive_token($refresh_token, $old_accessToken)
{   
        $license_key  = get_option('sperse_license_key');
        $data['licenseKey']=$license_key;
        $data['refresh_token']=$refresh_token;
        $data['action']='gdrive_refresh';
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
        // echo "<pre>";print_r($decoded_data);echo "</pre>";
        $new_accessToken = $decoded_data->access_token;
        if (isset($new_accessToken)) {
            global $wpdb;
            $res = $wpdb->update($wpdb->prefix . 'awp_platform_settings', ['api_key' => $new_accessToken], ['api_key' => $old_accessToken]);
        }
        return $new_accessToken;                                                                       
        

}

function awp_googledrive_resend_data($log_id, $data, $integration)
{

    $temp = json_decode($integration["data"], true);
    $temp = $temp["field_data"];
    $platform_obj = new AWP_Platform_Shell_Table('googledrive');
    $temp = $platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $access_token = $temp->api_key;

    if (!$access_token) {
        return;
    }

    $temp = json_decode($integration["data"], true);
    $temp = $temp["field_data"];
    $list_id = $temp["listId"];

    $task = $integration['task'];
    $data = stripslashes($data);
    $data = preg_replace('/\s+/', '', $data);
    $data = json_decode($data, true);
    $url = $data['url'];
    if (!$url) {
        $response['success'] = false;
        $response['msg'] = "Syntax Error! Request is invalid";
        return $response;
    }

    if ($task == "create_file") {



        $args = array(
            'method' => 'POST',
            'headers' => array(
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $access_token,
                "Content-Type" => "application/json",
            ),
            'body' => json_encode($data['args']['body']),
        );

        $response = wp_remote_post($url, $args);
        $args['headers']['Authorization'] = 'Bearer XXXXXXXXXXX';
        $args['body'] = $data['args']['body'];
        awp_add_to_log($response, $url, $args, $integration);
    }

    $response['success'] = true;
    return $response;
}
