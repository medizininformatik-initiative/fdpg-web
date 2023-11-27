<?php

class AWP_Drift extends Appfactory
{

    const client_id = "8lPqiFQJIPuMw1FlaNtOG52PSt4e1xre";

    protected function get_redirect_uri()
    {
        return 'https://sperse.io/scripts/authorization/auth.php';
    }

    public function init_actions()
    {
        add_action('admin_post_awp_drift_save_api_token', [$this, 'awp_save_drift_api_token'], 10, 0);
        add_action("rest_api_init", [$this, "create_webhook_route"]);
    }

    public function init_filters()
    {
        add_filter('awp_platforms_connections', [$this, 'awp_drift_platform_connection'], 10, 1);
    }

    public function get_callback()
    {
        return get_rest_url(null, 'automatehub/drift');
    }

    public function getLoginURL(): string
    {
        $query = [
            'response_type' => "code",
            'client_id' => self::client_id,
            'redirect_uri' => urlencode($this->get_redirect_uri()),
            'state' => $this->get_callback(),
        ];
        $authorization_endpoint = "https://dev.drift.com/authorize?";
        return add_query_arg($query, $authorization_endpoint);
    }

    public function create_webhook_route()
    {
        register_rest_route('automatehub', '/drift',
            [
                'methods' => 'GET',
                'callback' => [$this, 'get_webhook_data'],
                'permission_callback' => function () {return '';},
            ]);
    }

    public function get_webhook_data($request)
    {
        $params = $request->get_params();
        $code = $params['code'] ? $params['code'] : "";
        
        if ($code != "") {

            $client_accessToken = $params['access_token'];
            $client_refreshToken = $params['refresh_token'];

            if (isset($client_accessToken) && isset($client_refreshToken)) {
                global $wpdb;
                $query_drift_db = "select * from " . $wpdb->prefix . "awp_platform_settings where platform_name='drift'";
                $data = $wpdb->get_results($query_drift_db);
                $len = count($data) + 1;
                $platform_obj = new AWP_Platform_Shell_Table('drift');
                $platform_obj->save_platform(['account_name' => 'Account Number ' . $len, 'api_key' => $client_accessToken, 'client_secret' => $client_refreshToken]);
            }
            wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=drift'));
            exit();
        }
    }

    public function action_provider($actions)
    {
        $actions['drift'] = [
            'title' => esc_html__('Drift', 'automate_hub'),
            'tasks' => array('subscribe' => esc_html__('Create Contact', 'automate_hub')),
        ];
        return $actions;
    }

    public function settings_tab($tabs)
    {
        $tabs['drift'] = array('name' => esc_html__('Drift', 'automate_hub'), 'cat' => array('crm'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'drift') {
            return;
        }
        $nonce = wp_create_nonce("awp_drift_settings");
        $access_token = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/drift" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/drift.png"  alt="Drift Logo"></a><br /><br />
                <?php
require_once AWP_INCLUDES . '/class_awp_updates_manager.php';
        $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
        $instruction_obj->prepare_instructions();

        ?><br />
            <form name="drift_save_form" action="<?=esc_url(admin_url('admin-post.php'));?>" method="post" class="container">
                <input type="hidden" name="action" value="awp_drifccesssave_api_token">
                <input type="hidden" name="_nonce" value="<?=$nonce?>" />
                <input type="hidden" name="id" value="<?=$id?>" />
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"> </th>
                        <td>
                            <a href="<?=$this->getLoginURL()?>" class="button button-primary"> Sign In to Drift </a>
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
            'table-cols' => ['account_name' => 'Display Name', 'api_key' => 'API Key', 'active_status' => 'Active'],
        ];
        $platform_obj = new AWP_Platform_Shell_Table('drift');
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
        wp_enqueue_script('awp-drift-script', AWP_URL . '/apps/d/drift/drift.js', array('awp-vuejs'), '', 1);
    }

    public function action_fields()
    {
        ?>
        <script type="text/template" id="drift-action-template">
            <?php

        $app_data = array(
            'app_slug' => 'drift',
            'app_name' => 'Drift',
            'app_icon_url' => AWP_ASSETS . '/images/icons/drift.png',
            'app_icon_alter_text' => 'Drift Icon',
            'account_select_onchange' => 'getDriftList',
            'tasks' => array(
                'subscribe' => array(

                ),

            ),
        );

        require AWP_VIEWS . '/awp_app_integration_format.php';
        ?>
    </script>
<?php
}

}

$AWP_Drift = new AWP_Drift();

/*
 * Saves connection mapping
 */
function awp_drift_save_integration()
{
    Appfactory::save_integration();
}

/*
 * Handles sending data to drift API
 */
function awp_drift_send_data($record, $posted_data)
{

    $temp = json_decode(($record["data"]), true);
    $temp = $temp["field_data"];
    $platform_obj = new AWP_Platform_Shell_Table('drift');
    $temp = $platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $access_token = $temp->api_key;
    $refresh_token = $temp->client_secret;

    if (!$access_token) {
        exit;
    }


    $decoded_data = AWP_Drift::decode_data($record, $posted_data);
    $data = $decoded_data["data"];
    $task = $decoded_data["task"];

    if ($task == "subscribe") {
        $email = empty($data["email"]) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $name = empty($data["name"]) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $cf = empty($data["customFields"]) ? "" : awp_get_parsed_values($data["customFields"], $posted_data);

        $data = [
            'attributes' => [
                'email' => $email,
                'first_name' => $name,
            ],
        ];

        if ($cf) {
            $cf = explode(",", $cf);
            $data["customFields"] = $cf;
        }

        $create_contact_url = "https://driftapi.com/contacts";

        $args = array(
            'method' => 'POST',
            'headers' => array(
                'Content-Type' => 'application/json',
                'authorization' => 'Bearer ' . $access_token,
            ),
            'body' => json_encode($data),
        );
        $return = wp_remote_request($create_contact_url, $args);

        $error_data = json_decode($return['body'])->code;

        if ($error_data == 'CONTACT-6') {
            $new_accessToken = awp_refresh_drift_token($refresh_token, $access_token);
            if ($new_accessToken != "") {
                $args['headers']['authorization'] = 'Bearer ' . $new_accessToken;
                $returned_data = wp_remote_request($create_contact_url, $args);
                $args['headers']['authorization'] = 'Bearer XXXXXXXXXXX';
                $args['body'] = $data;
                awp_add_to_log($returned_data, $create_contact_url, $args, $record);
            }
        } else {
            $args['headers']['authorization'] = 'Bearer XXXXXXXXXXX';
            $args['body'] = $data;
            awp_add_to_log($return, $create_contact_url, $args, $record);
        }
    }
    return $return;
}

function awp_refresh_drift_token($refresh_token, $old_access_token)
{   
    $license_key  = get_option('sperse_license_key');
    $data['licenseKey']=$license_key;
    $data['refresh_token']=$refresh_token;
    $data['action']='drift_refresh';
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
        $res = $wpdb->update($wpdb->prefix . 'awp_platform_settings', ['api_key' => $new_accessToken], ['api_key' => $old_access_token]);
    }
    return $new_accessToken;
}

function awp_drift_resend_data($log_id, $data, $integration)
{

    $temp = json_decode($integration["data"], true);
    $temp = $temp["field_data"];
    $platform_obj = new AWP_Platform_Shell_Table('drift');
    $temp = $platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $access_token = $temp->api_key;

    if (!$access_token) {
        exit;
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

    if ($task == "subscribe") {
        $args = array(
            'method' => 'POST',
            'headers' => array(
                'Content-Type' => 'application/json',
                'authorization' => 'Bearer ' . $access_token,
            ),
            'body' => json_encode($data['args']['body']),
        );
        $response = wp_remote_request($url, $args);
        $args['headers']['authorization'] = 'Bearer XXXXXXXXXXX';
        $args['body'] = $data['args']['body'];
        awp_add_to_log($response, $url, $args, $integration);
    }

    $response['success'] = true;
    return $response;
}
