<?php

class AWP_ContactsPlus extends Appfactory
{

    const client_id = "8LcJdoN5oAZ9ruL7TDfjnr7UVBKv";
    const client_secret = "OQ3RofScHh3LZ7hcqIv10V5CNCUy";

    protected function get_redirect_uri()
    {
        return 'https://sperse.io/scripts/authorization/auth.php';
    }

    public function init_actions()
    {
        add_action('admin_post_awp_contactsplus_save_api_token', [$this, 'awp_save_contactsplus_api_token'], 10, 0);
        add_action("rest_api_init", [$this, "create_webhook_route"]);
    }

    public function init_filters()
    {
        add_filter('awp_platforms_connections', [$this, 'awp_contactsplus_platform_connection'], 10, 1);
    }

    public function get_callback()
    {
        return get_rest_url(null,'automatehub/contactsplus');
    }

    public function getLoginURL(): string
    {
        $query = [
            'type' => "web_server",
            'client_id' => self::client_id,
            'redirect_uri' => urlencode($this->get_redirect_uri()),
            'scope' => "account.read,contacts.read,contacts.write,tags.read",
            'state' => $this->get_callback(),
        ];
        $authorization_endpoint = "https://app.contactsplus.com/oauth/authorize?";
        return add_query_arg($query, $authorization_endpoint);
    }

    public function create_webhook_route()
    {
        register_rest_route('automatehub', '/contactsplus',
            [
                'methods' => 'GET',
                'callback' => [$this, 'get_webhook_data'],
                'permission_callback' => function () {return '';},
            ]);
    }

    public function get_webhook_data($request)
    {
        $params = $request->get_params();
        

        
        if ( isset( $params['access_token'] ) ) {
            $client_accessToken = $params['access_token'];
            $client_refreshToken = $params['refresh_token'];
            if (isset($client_accessToken) && isset($client_refreshToken)) {

                $authorize_client_url = "https://api.contactsplus.com/api/v1/account.get";
                $authorize_args = array(
                    'method' => 'POST',
                    'headers' => array(
                        'Authorization' => "Bearer " . $client_accessToken,
                        'Content-Type' => "application/json",
                    ),
                );
                $return_client_data = wp_remote_request($authorize_client_url, $authorize_args);

                $return_client_data_body = json_decode($return_client_data['body']);

                $client_email = $return_client_data_body->account->profileData->emails[0]->value;

                $platform_obj = new AWP_Platform_Shell_Table('contactsplus');
                $platform_obj->save_platform(['api_key' => $client_accessToken, 'email' => $client_email, 'client_secret' => $client_refreshToken]);

            }
        }
            wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=contactsplus'));
            exit();
    }

    public function action_provider($actions)
    {
        $actions['contactsplus'] = [
            'title' => esc_html__('Contacts+', 'automate_hub'),
            'tasks' => array('subscribe' => esc_html__('Subscribe To List', 'automate_hub')),
        ];
        return $actions;
    }

    public function settings_tab($tabs)
    {
        $tabs['contactsplus'] = array('name' => esc_html__('Contacts Plus', 'automate_hub'), 'cat' => array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'contactsplus') {
            return;
        }
        $nonce = wp_create_nonce("awp_contactsplus_settings");
        $api_token = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/contactsplus" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/contactsplus.png"  alt="Contacts+ Logo"></a><br /><br />
                <?php
require_once AWP_INCLUDES . '/class_awp_updates_manager.php';
        $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
        $instruction_obj->prepare_instructions();

        ?><br />
            <form name="contactsplus_save_form" action="<?=esc_url(admin_url('admin-post.php'));?>" method="post" class="container">
                <input type="hidden" name="action" value="awp_contactsplus_save_api_token">
                <input type="hidden" name="_nonce" value="<?=$nonce?>" />
                <input type="hidden" name="id" value="<?=$id?>" />
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"> </th>
                        <td>
                            <a href="<?=$this->getLoginURL()?>" class="button button-primary"> Sign In to Contacts+ </a>
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
            'table-cols' => ['api_key' => 'API Key', 'email' => 'Email Address', 'active_status' => 'Active'],
        ];
        $platform_obj = new AWP_Platform_Shell_Table('contactsplus');
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
        wp_enqueue_script('awp-contactsplus-script', AWP_URL . '/apps/c/contactsplus/contactsplus.js', array('awp-vuejs'), '', 1);
    }

    public function action_fields()
    {
        ?>
        <script type="text/template" id="contactsplus-action-template">
            <?php

        $app_data = array(
            'app_slug' => 'contactsplus',
            'app_name' => 'Contacts+',
            'app_icon_url' => AWP_ASSETS . '/images/icons/contactsplus.png',
            'app_icon_alter_text' => 'contactsplus Icon',
            'account_select_onchange' => 'getContactsplusList',
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

$AWP_ContactsPlus = new AWP_ContactsPlus();

/*
 * Saves connection mapping
 */
function awp_contactsplus_save_integration()
{
    Appfactory::save_integration();
}

/*
 * Handles sending data to contactsplus API
 */
function awp_contactsplus_send_data($record, $posted_data)
{

    $temp = json_decode(($record["data"]), true);
    $temp = $temp["field_data"];
    $platform_obj = new AWP_Platform_Shell_Table('contactsplus');
    $temp = $platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $access_token = $temp->api_key;
    $refresh_token = $temp->client_secret;

    if (!$access_token) {
        exit;
    }

    $decoded_data = AWP_ContactsPlus::decode_data($record, $posted_data);
    $data = $decoded_data["data"];
    $task = $decoded_data["task"];

    if ($task == "subscribe") {
        $email = empty($data["email"]) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $name = empty($data["name"]) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $phoneNumber = empty($data["phoneNumber"]) ? "" : awp_get_parsed_values($data["phoneNumber"], $posted_data);

        $cf = empty($data["customFields"]) ? "" : awp_get_parsed_values($data["customFields"], $posted_data);

        $data = [
            'contact' => [
                'contactData' => array(
                    'emails' => [array(
                        'type' => 'Custom',
                        'value' => $email,
                    )],
                    'name' => ['givenName' => $name],
                    'phoneNumbers' => [array(
                        'type' => 'Mobile',
                        'value' => $phoneNumber,
                    )],
                ),
            ],
        ];

        if ($cf) {
            $cf = explode(",", $cf);
            $data["customFields"] = $cf;
        }

        $url = "https://api.contactsplus.com/api/v1/contacts.create";

        $args = array(
            'method' => 'POST',
            'headers' => array(
                'Content-Type' => 'application/json',
                'authorization' => 'Bearer ' . $access_token,
            ),
            'body' => json_encode($data),
        );

        $return = wp_remote_request($url, $args);
        
        $error_data = json_decode($return['body'])->status;

        if ($error_data == 401) {
            $new_accessToken = awp_refresh_contactsplus_token($refresh_token, $access_token);
            if (isset($new_accessToken)) {
                $args['headers']['authorization'] = 'Bearer ' . $new_accessToken;
                $returned_data = wp_remote_request($url, $args);
                $args['headers']['authorization']='Bearer XXXXXXXXXXX';
                $args['body'] = $data;
                awp_add_to_log($returned_data, $url, $args, $record);
            }
        } else {
            $args['headers']['authorization']='Bearer XXXXXXXXXXX';
            $args['body'] = $data;
            awp_add_to_log($return, $url, $args, $record);
        }
    }
    return $return;
}

function awp_refresh_contactsplus_token($refresh_token, $old_access_token)
{
    $refresh_token_endpoint = "https://api.contactsplus.com/v3/oauth.refreshToken";
    $args = array(
        'method' => 'POST',
        'headers' => array(
            'Content-Type' => 'application/x-www-form-urlencoded',
        ),
        'body' => "client_id=" . AWP_ContactsPlus::client_id . "&client_secret=" . AWP_ContactsPlus::client_secret . "&refresh_token=$refresh_token",
    );
    $returned = wp_remote_request($refresh_token_endpoint, $args);
    $decoded_data = json_decode($returned['body']);
    $new_accessToken = $decoded_data->access_token;
    if (isset($new_accessToken)) {
        global $wpdb;
        $res = $wpdb->update($wpdb->prefix . 'awp_platform_settings', ['api_key' => $new_accessToken], ['api_key' => $old_access_token]);
    }
    return $new_accessToken;
}

function awp_contactsplus_resend_data($log_id, $data, $integration)
{

    $temp = json_decode($integration["data"], true);
    $temp = $temp["field_data"];
    $platform_obj = new AWP_Platform_Shell_Table('contactsplus');
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

    if ($task == "subscribe") {

        $url = "https://api.contactsplus.com/api/v1/contacts.create";

        $args = array(
            'method' => 'POST',
            'headers' => array(
                'Content-Type' => 'application/json',
                'authorization' => 'Bearer ' . $access_token,
            ),
            'body' => json_encode($data['args']['body']),
        );

        $response = wp_remote_post($url, $args);
        $args['headers']['authorization']='Bearer  XXXXXXXXXXX';
        $args['body'] = $data['args']['body'];
        awp_add_to_log($response, $url, $args, $integration);
    }

    $response['success'] = true;
    return $response;
}
