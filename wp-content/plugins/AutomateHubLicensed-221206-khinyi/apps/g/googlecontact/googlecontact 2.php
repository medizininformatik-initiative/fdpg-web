<?php

class AWP_GoogleContact extends Appfactory
{

    const client_id = "119614386927-anu0t5adv9726umkv6i257578daqrtpe.apps.googleusercontent.com";

    protected function get_redirect_uri()
    {
        // return get_rest_url(null, 'automatehub/googlecontact');
        return "https://sperse.io/scripts/authorization/auth.php";
    }

    public function init_actions()
    {
        add_action('admin_post_awp_googlecontact_save_api_token', [$this, 'awp_refresh_googlecontact_api_token'], 10, 0);
        add_action("rest_api_init", [$this, "create_webhook_route"]);
    }

    public function init_filters()
    {
        add_filter('awp_platforms_connections', [$this, 'awp_googlecontact_platform_connection'], 10, 1);
    }

    public function get_callback()
    {
        return get_rest_url(null, 'automatehub/googlecontact');
    }

    public function create_webhook_route()
    {
        register_rest_route('automatehub', '/googlecontact',
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
        echo "<pre>";print_r($params);echo "</pre>";
        die();
        if ($code) {
            
            
            if (isset($params['access_token'])) {

                $query= $wpdb->prepare("select * from {$wpdb->prefix}awp_platform_settings where platform_name=%s",'googlecontact');
                $data=$wpdb->get_results($query);
                $len=count($data) + 1;
                $googlecontact_access_code = sanitize_text_field($params['access_token']);
                $platform_obj = new AWP_Platform_Shell_Table('googlecontact');
                
                $platform_obj->save_platform(['account_name'=>'Account Number '.$len, 'api_key' => $params['access_token'], 'client_secret' => $params['refresh_token']]); 
            }
            wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=googlecontact'));
            exit();
        }
    }

    public function action_provider($actions)
    {
        $actions['googlecontact'] = [
            'title' => esc_html__('Google Contact', 'automate_hub'),
            'tasks' => array('createcontact' => esc_html__('Create Contact', 'automate_hub')),
        ];
        return $actions;
    }

    public function settings_tab($tabs)
    {
        $tabs['googlecontact'] = array('name' => esc_html__('Google Contact', 'automate_hub'), 'cat' => array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'googlecontact') {
            return;
        }
        $nonce = wp_create_nonce("awp_googlecontact_settings");
        $api_token = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/googlecontact" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/googlecontact.png"  height="90" alt="Google Contact Logo"></a><br /><br />
            <?php
require_once AWP_INCLUDES . '/class_awp_updates_manager.php';
        $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
        $instruction_obj->prepare_instructions();

        ?><br />
            <form name="googlecontact_save_form" action="<?=esc_url(admin_url('admin-post.php'));?>" method="post" class="container">
                <input type="hidden" name="action" value="awp_googlecontact_save_api_token">
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
                        var win=window.open('https://accounts.google.com/o/oauth2/auth?access_type=offline&approval_prompt=force&client_id=<?=self::client_id;?>&redirect_uri=<?=$this->get_redirect_uri();?>&response_type=code&scope=https://www.googleapis.com/auth/contacts+https://www.googleapis.com/auth/userinfo.email&state=<?=$this->get_callback();?>!!page=automate_hub&tab=googlecontact','popup','width=600,height=600');
                        var id = setInterval(function() {
                        const queryString = win.location.search;
                        const urlParams = new URLSearchParams(queryString);
                        const page_type = urlParams.get('page');
                        //if(page_type=='automate_hub'){win.close(); clearInterval(id); location.reload();}
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
        $platform_obj = new AWP_Platform_Shell_Table('googlecontact');
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
        wp_enqueue_script('awp-googlecontact-script', AWP_URL . '/apps/g/googlecontact/googlecontact.js', array('awp-vuejs'), '', 1);
    }

    public function action_fields()
    {
        ?>
        <script type="text/template" id="googlecontact-action-template">
            <?php
$app_data = array(
            'app_slug' => 'googlecontact',
            'app_name' => 'Google Contact',
            'app_icon_url' => AWP_ASSETS . '/images/icons/googlecontact.png',
            'app_icon_alter_text' => 'Google Contact',
            'account_select_onchange' => '',
            'tasks' => array(
                'create_file' => array(
                    'task_assignments' => array(
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

$AWP_GoogleContact = new AWP_GoogleContact();

/*
 * Saves connection mapping
 */
function awp_googlecontact_save_integration()
{
    Appfactory::save_integration();
}

/*
 * Handles sending data to googlecontact API
 */
function awp_googlecontact_send_data($record, $posted_data)
{

    $temp = json_decode(($record["data"]), true);
    $temp = $temp["field_data"];
    $platform_obj = new AWP_Platform_Shell_Table('googlecontact');
    $temp = $platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);

    $access_token = $temp->api_key;
    $refresh_token = $temp->client_secret;

    if (!$access_token) {
        return;
    }

    $decoded_data = AWP_GoogleContact::decode_data($record, $posted_data);
    $data = $decoded_data["data"];
    $task = $decoded_data["task"];
    $file_extension = $data['list'];

    if ($task == "createcontact") {
        $familyName = empty($data["familyName"]) ? "" : awp_get_parsed_values($data["familyName"], $posted_data);
        $givenName = empty($data["givenName"]) ? "" : awp_get_parsed_values($data["givenName"], $posted_data);
        $middleName = empty($data["middleName"]) ? "" : awp_get_parsed_values($data["middleName"], $posted_data);
        $country = empty($data["country"]) ? "" : awp_get_parsed_values($data["country"], $posted_data);
        $city = empty($data["city"]) ? "" : awp_get_parsed_values($data["city"], $posted_data);
        $region = empty($data["region"]) ? "" : awp_get_parsed_values($data["region"], $posted_data);
        $streetAddress = empty($data["streetAddress"]) ? "" : awp_get_parsed_values($data["streetAddress"], $posted_data);
        $postalCode = empty($data["postalCode"]) ? "" : awp_get_parsed_values($data["postalCode"], $posted_data);
        $phoneNumber = empty($data["phoneNumber"]) ? "" : awp_get_parsed_values($data["phoneNumber"], $posted_data);


        $data = [
            "names" => [
              [
                "familyName" => $familyName,
                "givenName" => $givenName,
                "middleName" => $middleName
              ]
            ],
            "addresses" => [
              [
                "country" => $country,
                "city" => $city,
                "region" => $region,
                "streetAddress" => $streetAddress,
                "postalCode" => $postalCode
              ]
            ],
            "phoneNumbers" => [
              [
                "value" => $phoneNumber
              ]
            ]
        ];

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
            $new_accessToken = awp_refresh_googlecontact_token($refresh_token, $access_token);
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

function awp_refresh_googlecontact_token($refresh_token, $old_accessToken)
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

function awp_googlecontact_resend_data($log_id, $data, $integration)
{

    $temp = json_decode($integration["data"], true);
    $temp = $temp["field_data"];
    $platform_obj = new AWP_Platform_Shell_Table('googlecontact');
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
