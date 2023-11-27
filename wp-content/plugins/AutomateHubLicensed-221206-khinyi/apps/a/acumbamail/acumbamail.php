<?php
class AWP_Acumbamail extends appfactory
{

    public function init_actions()
    {
        add_action('admin_post_awp_acumbamail_save_api_token', [$this, 'awp_save_acumbamail_api_token'], 10, 0);
        add_action('wp_ajax_awp_get_acumbamail_list', [$this, 'awp_get_acumbamail_list'], 10, 0);
    }

    public function init_filters()
    {
        add_filter('awp_platforms_connections', [$this, 'awp_acumbamail_platform_connection'], 10, 1);
    }

    public function action_provider($actions)
    {
        $actions['acumbamail'] = [
            'title' => esc_html__('Acumbamail', 'automate_hub'),
            'tasks' => array('subscribe' => esc_html__('Subscribe To List', 'automate_hub'), 'createList' => esc_html__('Create List', 'automate_hub')),
        ];
        return $actions;
    }

    public function settings_tab($tabs)
    {
        $tabs['acumbamail'] = array('name' => esc_html__('Acumbamail', 'automate_hub'), 'cat' => array('crm'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'acumbamail') {
            return;
        }
        $nonce = wp_create_nonce("awp_acumbamail_settings");
        $api_key = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $display_name = isset($_GET['account_name']) ? $_GET['account_name'] : "";

        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/acumbamail" target="_blank"><img src="<?=AWP_ASSETS_REMOTE;?>/images/logos/acumbamail.png" height="50" alt="Acumbamail Logo"></a><br /><br />
            <?php
require_once AWP_INCLUDES . '/class_awp_updates_manager.php';
        $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
        $instruction_obj->prepare_instructions();

        ?>
                <br />
                <?php

        $form_fields = '';
        $app_name = 'acumbamail';
        $acumbamail_form = new AWP_Form_Fields($app_name);

        $form_fields = $acumbamail_form->awp_wp_text_input(
            array(
                'id' => "awp_acumbamail_display_name",
                'name' => "awp_acumbamail_display_name",
                'value' => $display_name,
                'placeholder' => esc_html__('Enter an identifier for this account', 'automate_hub'),
                'label' => esc_html__('Display Name', 'automate_hub'),
                'wrapper_class' => 'form-row',
                'show_copy_icon' => true,

            )
        );

        $form_fields .= $acumbamail_form->awp_wp_text_input(
            array(
                'id' => "awp_acumbamail_api_token",
                'name' => "awp_acumbamail_api_token",
                'value' => $api_key,
                'placeholder' => esc_html__('Enter your Acumbamail API Key', 'automate_hub'),
                'label' => esc_html__('Acumbamail API Key', 'automate_hub'),
                'wrapper_class' => 'form-row',
                'show_copy_icon' => true,

            )
        );

        $form_fields .= $acumbamail_form->awp_wp_hidden_input(
            array(
                'name' => "action",
                'value' => 'awp_acumbamail_save_api_token',
            )
        );

        $form_fields .= $acumbamail_form->awp_wp_hidden_input(
            array(
                'name' => "_nonce",
                'value' => $nonce,
            )
        );
        $form_fields .= $acumbamail_form->awp_wp_hidden_input(
            array(
                'name' => "id",
                'value' => wp_unslash($id),
            )
        );

        $acumbamail_form->render($form_fields);

        ?>
        </div>


        <div class="wrap">
                <form id="form-list" method="post">
                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
$data = [
            'table-cols' => ['account_name' => 'Display name', 'api_key' => 'API Key', 'spots' => 'Active Spots', 'active_status' => 'Active'],
        ];
        $platform_obj = new AWP_Platform_Shell_Table('acumbamail');
        $platform_obj->initiate_table($data);
        $platform_obj->prepare_items();
        $platform_obj->display_table();

        ?>
                </form>
        </div>
    <?php
}

    public function awp_save_acumbamail_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_acumbamail_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }
        $api_token = sanitize_text_field($_POST["awp_acumbamail_api_token"]);
        $display_name = sanitize_text_field($_POST["awp_acumbamail_display_name"]);
        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('acumbamail');
        $platform_obj->save_platform(['account_name' => $display_name, 'api_key' => $api_token]);

        AWP_redirect("admin.php?page=automate_hub&tab=acumbamail");
    }

    public function awp_acumbamail_js_fields($field_data)
    {
    }

    public function load_custom_script()
    {
        wp_enqueue_script('awp-acumbamail-script', AWP_URL . '/apps/a/acumbamail/acumbamail.js', array('awp-vuejs'), '', 1);
    }

    public function action_fields()
    {
        ?>
    <script type="text/template" id="acumbamail-action-template">
                <?php

        $app_data = array(
            'app_slug' => 'acumbamail',
            'app_name' => 'Acumbamail',
            'app_icon_url' => AWP_ASSETS . '/images/icons/acumbamail.png',
            'app_icon_alter_text' => 'Acumbamail Icon',
            'account_select_onchange' => 'getAcumbamailList',
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
     * Get Acumbamail contact lists
     */
    public function awp_get_acumbamail_list()
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
        $platform_obj = new AWP_Platform_Shell_Table('acumbamail');
        $data = $platform_obj->awp_get_platform_detail_by_id($id);
        if (!$data) {
            die(esc_html__('No Data Found', 'automate_hub'));
        }
        $api_token = $data->api_key;

        if (!$api_token) {
            return array();
        }

        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
            ),
        );

        $url = "https://acumbamail.com/api/1/getLists/?auth_token=" . $api_token;

        $data = wp_remote_request($url, $args);
        if (is_wp_error($data)) {
            wp_send_json_error();
        }

        $body = json_decode($data["body"]);
        $subscribersList = [];
        $allKeys = array_keys((array) $body);

        foreach ($allKeys as $key) {
            array_push($subscribersList, array('key' => $key, 'name' => $body->$key->name));
        }
        $lists = wp_list_pluck($subscribersList, 'name', 'key');
        // error_log(print_r($lists, true), 0);

        wp_send_json_success($lists);
    }
}

$AWP_Acumbamail = new AWP_Acumbamail();

/*
 * Saves connection mapping
 */
function awp_acumbamail_save_integration()
{
    Appfactory::save_integration();
}

/*
 * Handles sending data to acumbamail API
 */
function awp_acumbamail_send_data($record, $posted_data)
{

    $temp = json_decode(($record["data"]), true);
    $temp = $temp["field_data"];
    $platform_obj = new AWP_Platform_Shell_Table('acumbamail');
    $temp = $platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_token = $temp->api_key;

    if (!$api_token) {
        return;
    }

    $decoded_data = AWP_Acumbamail::decode_data($record, $posted_data);
    $data = $decoded_data["data"];
    $list_id = $data["listId"];
    $task = $decoded_data["task"];

    if ($task == "subscribe") {
        $email = empty($data["email"]) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $cf = empty($data["customFields"]) ? "" : awp_get_parsed_values($data["customFields"], $posted_data);

        $data = array(
            'list_id' => $list_id,
            'merge_fields' => [
                'email' => $email,
            ],

        );

        if ($cf) {
            $cf = explode(",", $cf);
            $data["customFields"] = $cf;
        }

        $url = "https://acumbamail.com/api/1/addSubscriber/?auth_token=" . $api_token;

        $args = array(
            'method' => 'POST',
            'headers' => array(
                'Content-Type' => 'application/json',
            ),
            'body' => json_encode($data),
        );

        $return = wp_remote_request($url, $args);
        $args['body'] = $data;
        $args['headers']['auth_token'] = 'XXXXXXXXXXX';
        awp_add_to_log($return, $url, $args, $record);
    }

    if ($task == "createList") {
        $name = empty($data["name"]) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $company = empty($data["company"]) ? "" : awp_get_parsed_values($data["company"], $posted_data);
        $country = empty($data["country"]) ? "" : awp_get_parsed_values($data["country"], $posted_data);
        $city = empty($data["city"]) ? "" : awp_get_parsed_values($data["city"], $posted_data);
        $address = empty($data["address"]) ? "" : awp_get_parsed_values($data["address"], $posted_data);
        $phone = empty($data["phone"]) ? "" : awp_get_parsed_values($data["phone"], $posted_data);
        $cf = empty($data["customFields"]) ? "" : awp_get_parsed_values($data["customFields"], $posted_data);

        if ($cf) {
            $cf = explode(",", $cf);
            $data["customFields"] = $cf;
        }

        $url = "https://acumbamail.com/api/1/createList/?auth_token=" . $api_token . "&name=" . $name . "&company=" . $company . "&country=" . $country . "&city=" . $city . "&address=" . $address . "&phone=" . $phone;

        $args = array(
            'method' => 'POST',
            'headers' => array(
                'Content-Type' => 'application/json',
            ),
        );

        $return = wp_remote_request($url, $args);
        $args['headers']['auth_token'] = 'XXXXXXXXXXX';
        awp_add_to_log($return, $url, $args, $record);
    }
    return $return;
}

function awp_acumbamail_resend_data($log_id, $data, $integration)
{
    $temp = json_decode($integration["data"], true);
    $temp = $temp["field_data"];
    $platform_obj = new AWP_Platform_Shell_Table('acumbamail');
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
                'Content-Type' => 'application/json',
            ),
            'body' => json_encode($data['args']['body']),
        );

        $response = wp_remote_post($url, $args);
        $args['headers']['auth_token'] = 'XXXXXXXXXXX';
        $args['body'] = $data['args']['body'];
        awp_add_to_log($response, $url, $args, $integration);
    }

    if ($task == "createList") {

        $args = array(
            'method' => 'POST',
            'headers' => array(
                'Content-Type' => 'application/json',
            ),
        );

        $response = wp_remote_post($url, $args);
        $args['headers']['auth_token'] = 'XXXXXXXXXXX';
        $args['body'] = $data['args']['body'];
        awp_add_to_log($response, $url, $args, $integration);
    }

    $response['success'] = true;
    return $response;
}
