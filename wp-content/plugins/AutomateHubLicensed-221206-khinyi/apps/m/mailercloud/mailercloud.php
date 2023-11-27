<?php
class AWP_Mailercloud extends appfactory
{

    public function init_actions()
    {
        add_action('admin_post_awp_mailercloud_save_api_token', [$this, 'awp_save_mailercloud_api_token'], 10, 0);
        add_action('wp_ajax_awp_get_mailercloud_list', [$this, 'awp_get_mailercloud_list'], 10, 0);
    }

    public function init_filters()
    {
        add_filter('awp_platforms_connections', [$this, 'awp_mailercloud_platform_connection'], 10, 1);
    }

    public function action_provider($actions)
    {
        $actions['mailercloud'] = [
            'title' => esc_html__('MailerCloud', 'automate_hub'),
            'tasks' => array('subscribe' => esc_html__('Create Contact', 'automate_hub'), 'createList' => esc_html__('Create List', 'automate_hub')),
        ];
        return $actions;
    }

    public function settings_tab($tabs)
    {
        $tabs['mailercloud'] = array('name' => esc_html__('MailerCloud', 'automate_hub'), 'cat' => array('crm'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'mailercloud') {
            return;
        }
        $nonce = wp_create_nonce("awp_mailercloud_settings");
        $api_key = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $display_name = isset($_GET['account_name']) ? $_GET['account_name'] : "";

        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/mailercloud" target="_blank"><img src="<?=AWP_ASSETS_REMOTE;?>/images/logos/mailercloud.png" height="50" alt="Mailercloud Logo"></a><br /><br />
            <?php
require_once AWP_INCLUDES . '/class_awp_updates_manager.php';
        $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
        $instruction_obj->prepare_instructions();

        ?>
                <br />
                <?php

        $form_fields = '';
        $app_name = 'mailercloud';
        $mailercloud_form = new AWP_Form_Fields($app_name);

        $form_fields = $mailercloud_form->awp_wp_text_input(
            array(
                'id' => "awp_mailercloud_display_name",
                'name' => "awp_mailercloud_display_name",
                'value' => $display_name,
                'placeholder' => esc_html__('Enter an identifier for this account', 'automate_hub'),
                'label' => esc_html__('Display Name', 'automate_hub'),
                'wrapper_class' => 'form-row',
                'show_copy_icon' => true,

            )
        );

        $form_fields .= $mailercloud_form->awp_wp_text_input(
            array(
                'id' => "awp_mailercloud_api_token",
                'name' => "awp_mailercloud_api_token",
                'value' => $api_key,
                'placeholder' => esc_html__('Enter your Mailercloud API Key', 'automate_hub'),
                'label' => esc_html__('Mailercloud API Key', 'automate_hub'),
                'wrapper_class' => 'form-row',
                'show_copy_icon' => true,

            )
        );

        $form_fields .= $mailercloud_form->awp_wp_hidden_input(
            array(
                'name' => "action",
                'value' => 'awp_mailercloud_save_api_token',
            )
        );

        $form_fields .= $mailercloud_form->awp_wp_hidden_input(
            array(
                'name' => "_nonce",
                'value' => $nonce,
            )
        );
        $form_fields .= $mailercloud_form->awp_wp_hidden_input(
            array(
                'name' => "id",
                'value' => wp_unslash($id),
            )
        );

        $mailercloud_form->render($form_fields);

        ?>
        </div>


        <div class="wrap">
                <form id="form-list" method="post">
                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
$data = [
            'table-cols' => ['account_name' => 'Display name', 'api_key' => 'API Key', 'spots' => 'Active Spots', 'active_status' => 'Active'],
        ];
        $platform_obj = new AWP_Platform_Shell_Table('mailercloud');
        $platform_obj->initiate_table($data);
        $platform_obj->prepare_items();
        $platform_obj->display_table();

        ?>
                </form>
        </div>
    <?php
}

    public function awp_save_mailercloud_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_mailercloud_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }
        $api_token = sanitize_text_field($_POST["awp_mailercloud_api_token"]);
        $display_name = sanitize_text_field($_POST["awp_mailercloud_display_name"]);
        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('mailercloud');
        $platform_obj->save_platform(['account_name' => $display_name, 'api_key' => $api_token]);

        AWP_redirect("admin.php?page=automate_hub&tab=mailercloud");
    }

    public function awp_mailercloud_js_fields($field_data)
    {
    }

    public function load_custom_script()
    {
        wp_enqueue_script('awp-mailercloud-script', AWP_URL . '/apps/m/mailercloud/mailercloud.js', array('awp-vuejs'), '', 1);
    }

    public function action_fields()
    {
        ?>
    <script type="text/template" id="mailercloud-action-template">
                <?php
        $app_data = array(
            'app_slug' => 'mailercloud',
            'app_name' => 'MailerCloud',
            'app_icon_url' => AWP_ASSETS . '/images/icons/mailercloud.png',
            'app_icon_alter_text' => 'Mailercloud Icon',
            'account_select_onchange' => 'getMailercloudList',
            'tasks' => array(
                'subscribe' => array(
                    'task_assignments' => array(
                        array(
                            'label' => 'List',
                            'type' => 'select',
                            'name' => "list_id",
                            'model' => 'fielddata.listId',
                            'required' => 'required',
                            'onchange' => '',
                            'select_default' => 'Select List...',
                            'option_for_loop' => '(item, index) in fielddata.list',
                            'spinner' => array(
                                'bind-class' => "{'is-active': listLoading}",
                            ),
                        ),
                    ),
                ),
                'createList' => array(),
            ),
        );

        require AWP_VIEWS . '/awp_app_integration_format.php';
        ?>
    </script>
<?php
}

    /*
     * Get Mailercloud lists
     */
    public function awp_get_mailercloud_list()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'automate_hub')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        if (!isset($_POST['platformid'])) {
            die(esc_html__('Invalid Request', 'automate_hub'));
        }
        $id = sanitize_text_field($_POST['platformid']);
        $platform_obj = new AWP_Platform_Shell_Table('mailercloud');
        $data = $platform_obj->awp_get_platform_detail_by_id($id);
        if (!$data) {
            die(esc_html__('No Data Found', 'automate_hub'));
        }
        $api_token = $data->api_key;

        if (!$api_token) {
            return array();
        }

        $data = [
            "limit" => 10,
            "page" => 1,
        ];

        $args = array(
            'method' => 'POST',
            'headers' => [
                'Authorization' => $api_token,
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode($data),
        );

        $url = "https://cloudapi.mailercloud.com/v1/lists/search";

        $response = wp_remote_request($url, $args);

        if (is_wp_error($response)) {
            wp_send_json_error();
        }
        $body = json_decode($response["body"]);

        $lists = wp_list_pluck($body->data, 'name', 'id');
        wp_send_json_success($lists);
    }
}

$AWP_Mailercloud = new AWP_Mailercloud();

/*
 * Saves connection mapping
 */
function awp_mailercloud_save_integration()
{
    Appfactory::save_integration();
}

/*
 * Handles sending data to mailercloud API
 */
function awp_mailercloud_send_data($record, $posted_data)
{

    $temp = json_decode(($record["data"]), true);
    $temp = $temp["field_data"];
    $platform_obj = new AWP_Platform_Shell_Table('mailercloud');
    $temp = $platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_token = $temp->api_key;

    if (!$api_token) {
        return;
    }

    $decoded_data = AWP_Mailercloud::decode_data($record, $posted_data);
    $data = $decoded_data["data"];
    $list_id = $data["listId"];
    $task = $decoded_data["task"];

    if ($task == "subscribe") {
        $email = empty($data["email"]) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $name = empty($data["name"]) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $lastName = empty($data["lastName"]) ? "" : awp_get_parsed_values($data["lastName"], $posted_data);
        $organization = empty($data["organization"]) ? "" : awp_get_parsed_values($data["organization"], $posted_data);
        $phone = empty($data["phone"]) ? "" : awp_get_parsed_values($data["phone"], $posted_data);
        $state = empty($data["state"]) ? "" : awp_get_parsed_values($data["state"], $posted_data);
        $zip = empty($data["zip"]) ? "" : awp_get_parsed_values($data["zip"], $posted_data);
        $city = empty($data["city"]) ? "" : awp_get_parsed_values($data["city"], $posted_data);
        $country = empty($data["country"]) ? "" : awp_get_parsed_values($data["country"], $posted_data);
        $cf = empty($data["customFields"]) ? "" : awp_get_parsed_values($data["customFields"], $posted_data);

        $data = [
            'list_id' => $list_id,
            'email' => $email,
            'name' => $name,
            'last_name' => $lastName,
            'organization' => $organization,
            'phone' => $phone,
            'state' => $state,
            'zip' => $zip,
            'city' => $city,
            'country' => $country,
        ];

        if ($cf) {
            $cf = explode(",", $cf);
            $data["customFields"] = $cf;
        }

        $url = "https://cloudapi.mailercloud.com/v1/contacts";

        $args = array(
            'method' => 'POST',
            'headers' => array(
                'Authorization' => $api_token,
                'Content-Type' => 'application/json',
            ),
            'body' => json_encode($data),
        );

        $return = wp_remote_request($url, $args);
        $args['body'] = $data;
        $args['headers']['Authorization'] = 'XXXXXXXXXXX';
        awp_add_to_log($return, $url, $args, $record);
    }

    if ($task == "createList") {
        $name = empty($data["name"]) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $cf = empty($data["customFields"]) ? "" : awp_get_parsed_values($data["customFields"], $posted_data);

        $data = [
            'list_type' => 1,
            'name' => $name,
        ];

        if ($cf) {
            $cf = explode(",", $cf);
            $data["customFields"] = $cf;
        }

        $url = "https://cloudapi.mailercloud.com/v1/list";

        $args = array(
            'method' => 'POST',
            'headers' => array(
                'Authorization' => $api_token,
                'Content-Type' => 'application/json',
            ),
            'body' => json_encode($data),
        );

        $return = wp_remote_request($url, $args);
        $args['headers']['Authorization'] = 'XXXXXXXXXXX';
        awp_add_to_log($return, $url, $args, $record);
    }
    return $return;
}

function awp_mailercloud_resend_data($log_id, $data, $integration)
{
    $temp = json_decode($integration["data"], true);
    $temp = $temp["field_data"];
    $platform_obj = new AWP_Platform_Shell_Table('mailercloud');
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
            'method' => 'POST',
            'headers' => array(
                'Authorization' => $api_token,
                'Content-Type' => 'application/json',
            ),
            'body' => json_encode($data['args']['body']),
        );

        $response = wp_remote_post($url, $args);
        $args['headers']['Authorization'] = 'XXXXXXXXXXX';
        $args['body'] = $data['args']['body'];
        awp_add_to_log($response, $url, $args, $integration);
    }

    if ($task == "createList") {

        $args = array(
            'method' => 'POST',
            'headers' => array(
                'Authorization' => $api_token,
                'Content-Type' => 'application/json',
            ),
            'body' => json_encode($data['args']['body']),
        );

        $response = wp_remote_post($url, $args);
        $args['headers']['Authorization'] = 'XXXXXXXXXXX';
        $args['body'] = $data['args']['body'];
        awp_add_to_log($response, $url, $args, $integration);
    }

    $response['success'] = true;
    return $response;
}
