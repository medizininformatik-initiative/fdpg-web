<?php
class AWP_Encharge extends appfactory
{

    const client_id = "automatehub";
    const client_secret = "Ilti10SRxkWHIy9aXjsnQkP7F5nXRYsaivK3sT2mWVqDAECwXN";

    protected function get_redirect_uri()
    {
        return 'https://sperse.io/scripts/authorization/auth.php';
    }

    public function init_actions()
    {
        // add_action('admin_post_awp_encharge_save_api_token', [$this, 'awp_save_encharge_api_token'], 10, 0);
        add_action("rest_api_init", [$this, "create_webhook_route"]);
    }

    public function init_filters()
    {
        add_filter('awp_platforms_connections', [$this, 'awp_encharge_platform_connection'], 10, 1);
    }

    public function get_callback()
    {
        return get_rest_url(null, 'automatehub/encharge');
    }

    public function getLoginURL(): string
    {
        $query = [
            'type' => "authorizationCode",
            'client_id' => self::client_id,
            'redirect_uri' => urlencode($this->get_redirect_uri()),
            'state' => $this->get_callback(),
        ];

        $authorization_endpoint = "https://api.encharge.io/v1/oauth/authorize?";

        return add_query_arg($query, $authorization_endpoint);
    }

    public function create_webhook_route()
    {
        register_rest_route('automatehub', '/encharge',
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

        $code = $params['code'] ? $params['code'] : "";

        if ($code) {
            $args = array(
                'headers' => array(
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Authorization' => 'Basic ' . base64_encode(self::client_id . ":" . self::client_secret),
                    'User-Agent' => 'sperse',
                ),
                'body' => array(
                    'grant_type' => 'authorization_code',
                    'code' => $code,
                    'redirect_uri' => $this->get_redirect_uri(),
                ),
            );

            $access_url = "https://api.encharge.io/v1/oauth/token";

            $return = wp_remote_post($access_url, $args);

            $body = json_decode($return['body'], true);

            if (isset($body['access_token'])) {

                $query = "select * from " . $wpdb->prefix . "awp_platform_settings where platform_name='encharge'";

                $data = $wpdb->get_results($query);

                $len = count($data) + 1;

                $platform_obj = new AWP_Platform_Shell_Table('encharge');

                $platform_obj->save_platform(['account_name' => 'Account ' . $len, 'api_key' => $body['access_token'], 'client_secret' => $body['refresh_token']]);
            }

        }

        wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=encharge'));
        exit();
    }

    public function action_provider($actions)
    {
        $actions['encharge'] = [
            'title' => esc_html__('Encharge', 'automate_hub'),
            'tasks' => array('subscribe' => esc_html__('Add Person', 'automate_hub')),
        ];
        return $actions;
    }

    public function settings_tab($tabs)
    {
        $tabs['encharge'] = array('name' => esc_html__('Encharge', 'automate_hub'), 'cat' => array('crm'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'encharge') {
            return;
        }
        $nonce = wp_create_nonce("awp_encharge_settings");
        $api_token = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/encharge" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/encharge.png"  alt="Encharge Logo"></a><br /><br />
                <?php
require_once AWP_INCLUDES . '/class_awp_updates_manager.php';
        $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
        $instruction_obj->prepare_instructions();
        ?>
        <br />
            <form name="encharge_save_form" action="<?=esc_url(admin_url('admin-post.php'));?>" method="post" class="container">
                <input type="hidden" name="action" value="awp_encharge_save_api_token">
                <input type="hidden" name="_nonce" value="<?=$nonce?>" />
                <input type="hidden" name="id" value="<?=$id?>" />
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"> </th>
                        <td>
                            <a href="<?=$this->getLoginURL()?>" class="button button-primary"> Sign In to Encharge </a>
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
            'table-cols' => ['account_name' => 'Display Name', 'api_key' => 'API Key',
                // 'client_secret' => 'Refresh Token',
                'active_status' => 'Active'],
        ];
        $platform_obj = new AWP_Platform_Shell_Table('encharge');
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
        wp_enqueue_script('awp-encharge-script', AWP_URL . '/apps/e/encharge/encharge.js', array('awp-vuejs'), '', 1);
    }

    public function action_fields()
    {
        ?>
    <script type="text/template" id="encharge-action-template">
                <?php

        $app_data = array(
            'app_slug' => 'encharge',
            'app_name' => 'Encharge',
            'app_icon_url' => AWP_ASSETS . '/images/icons/encharge.png',
            'app_icon_alter_text' => 'Encharge Icon',
            'account_select_onchange' => 'getEnchargeList',
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

$AWP_Encharge = new AWP_Encharge();

/*
 * Saves connection mapping
 */
function awp_encharge_save_integration()
{
    Appfactory::save_integration();
}

/*
 * Handles sending data to encharge API
 */
function awp_encharge_send_data($record, $posted_data)
{

    $temp = json_decode(($record["data"]), true);
    $temp = $temp["field_data"];
    $platform_obj = new AWP_Platform_Shell_Table('encharge');
    $temp = $platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_token = $temp->api_key;

    if (!$api_token) {
        return;
    }

    $decoded_data = AWP_Encharge::decode_data($record, $posted_data);
    $data = $decoded_data["data"];
    $list_id = $data["listId"];
    $task = $decoded_data["task"];

    if ($task == "subscribe") {
        $email = empty($data["email"]) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $firstName = empty($data["firstName"]) ? "" : awp_get_parsed_values($data["firstName"], $posted_data);
        $lastName = empty($data["lastName"]) ? "" : awp_get_parsed_values($data["lastName"], $posted_data);
        $name = empty($data["name"]) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $company = empty($data["company"]) ? "" : awp_get_parsed_values($data["company"], $posted_data);
        $website = empty($data["website"]) ? "" : awp_get_parsed_values($data["website"], $posted_data);
        $salutation = empty($data["salutation"]) ? "" : awp_get_parsed_values($data["salutation"], $posted_data);
        $title = empty($data["title"]) ? "" : awp_get_parsed_values($data["title"], $posted_data);
        $phone = empty($data["phone"]) ? "" : awp_get_parsed_values($data["phone"], $posted_data);
        $address = empty($data["address"]) ? "" : awp_get_parsed_values($data["address"], $posted_data);
        $country = empty($data["country"]) ? "" : awp_get_parsed_values($data["country"], $posted_data);
        $city = empty($data["city"]) ? "" : awp_get_parsed_values($data["city"], $posted_data);
        $region = empty($data["region"]) ? "" : awp_get_parsed_values($data["region"], $posted_data);
        $postCode = empty($data["postCode"]) ? "" : awp_get_parsed_values($data["postCode"], $posted_data);
        $cf = empty($data["customFields"]) ? "" : awp_get_parsed_values($data["customFields"], $posted_data);

        $data = array(
            'email' => $email,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'name' => $name,
            'company' => $company,
            'website' => $website,
            'salutation' => $salutation,
            'title' => $title,
            'phone' => $phone,
            'address' => $address,
            'country' => $country,
            'city' => $city,
            'region' => $region,
            'postCode' => $postCode,
        );

        if ($cf) {
            $cf = explode(",", $cf);
            $data["customFields"] = $cf;
        }

        $url = "https://api.encharge.io/v1/people";

        $args = array(
            'method' => 'POST',
            'headers' => array(
                'Content-Type' => 'application/json',
                'api_key' => 'Bearer ' . $api_token,
            ),
            'body' => json_encode($data),
        );

        error_log(print_r(json_encode($data), true), 0);

        $return = wp_remote_request($url, $args);
        $args['body'] = $data;
        $args['headers']['api_key'] = 'Bearer XXXXXXXXXXX';
        awp_add_to_log($return, $url, $args, $record);
    }
    return $return;
}

function awp_encharge_resend_data($log_id, $data, $integration)
{
    $temp = json_decode($integration["data"], true);
    $temp = $temp["field_data"];
    $platform_obj = new AWP_Platform_Shell_Table('encharge');
    $temp = $platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_token = $temp->api_key;

    if (!$api_token) {
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

    if ($task == "subscribe") {

        $args = array(

            'headers' => array(
                'Content-Type' => 'application/json',
                'api_key' => $api_token,
            ),
            'body' => json_encode($data['args']['body']),
        );

        $response = wp_remote_post($url, $args);
        $args['headers']['api_key'] = 'Bearer XXXXXXXXXXX';
        $args['body'] = $data['args']['body'];
        awp_add_to_log($response, $url, $args, $integration);
    }

    $response['success'] = true;
    return $response;
}
