<?php
class AWP_EasySendy extends appfactory
{

    public function init_actions()
    {
        add_action('admin_post_awp_easysendy_save_api_token', [$this, 'awp_save_easysendy_api_token'], 10, 0);
        add_action('wp_ajax_awp_get_easysendy_list', [$this, 'awp_get_easysendy_list'], 10, 0);
    }

    public function init_filters()
    {
        add_filter('awp_platforms_connections', [$this, 'awp_easysendy_platform_connection'], 10, 1);
    }

    public function action_provider($actions)
    {
        $actions['easysendy'] = [
            'title' => esc_html__('EasySendy', 'automate_hub'),
            'tasks' => array('subscribe' => esc_html__('Subscribe To List', 'automate_hub'), 'create_list' => esc_html__('Create List', 'automate_hub')),
        ];
        return $actions;
    }

    public function settings_tab($tabs)
    {
        $tabs['easysendy'] = array('name' => esc_html__('EasySendy', 'automate_hub'), 'cat' => array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'easysendy') {
            return;
        }
        $nonce = wp_create_nonce("awp_easysendy_settings");
        $api_key = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $display_name = isset($_GET['account_name']) ? $_GET['account_name'] : "";

        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/easysendy" target="_blank"><img src="<?=AWP_ASSETS_REMOTE;?>/images/logos/easysendy.png" width="275" alt="EasySendy Logo"></a><br /><br />
            <?php
require_once AWP_INCLUDES . '/class_awp_updates_manager.php';
        $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
        $instruction_obj->prepare_instructions();

        ?>
                <br />
                <?php

        $form_fields = '';
        $app_name = 'easysendy';
        $easysendy_form = new AWP_Form_Fields($app_name);

        $form_fields = $easysendy_form->awp_wp_text_input(
            array(
                'id' => "awp_easysendy_display_name",
                'name' => "awp_easysendy_display_name",
                'value' => $display_name,
                'placeholder' => esc_html__('Enter an identifier for this account', 'automate_hub'),
                'label' => esc_html__('Display Name', 'automate_hub'),
                'wrapper_class' => 'form-row',
                'show_copy_icon' => true,

            )
        );

        $form_fields .= $easysendy_form->awp_wp_text_input(
            array(
                'id' => "awp_easysendy_api_token",
                'name' => "awp_easysendy_api_token",
                'value' => $api_key,
                'placeholder' => esc_html__('Enter your EasySendy API Key', 'automate_hub'),
                'label' => esc_html__('EasySendy API Key', 'automate_hub'),
                'wrapper_class' => 'form-row',
                'show_copy_icon' => true,

            )
        );

        $form_fields .= $easysendy_form->awp_wp_hidden_input(
            array(
                'name' => "action",
                'value' => 'awp_easysendy_save_api_token',
            )
        );

        $form_fields .= $easysendy_form->awp_wp_hidden_input(
            array(
                'name' => "_nonce",
                'value' => $nonce,
            )
        );
        $form_fields .= $easysendy_form->awp_wp_hidden_input(
            array(
                'name' => "id",
                'value' => wp_unslash($id),
            )
        );

        $easysendy_form->render($form_fields);

        ?>
        </div>


        <div class="wrap">
                <form id="form-list" method="post">


                    <input type="hidden" name="page" value="automate_hub"/>

                    <?php
$data = [
            'table-cols' => ['account_name' => 'Display name', 'api_key' => 'API Key', 'spots' => 'Active Spots', 'active_status' => 'Active'],
        ];
        $platform_obj = new AWP_Platform_Shell_Table('easysendy');
        $platform_obj->initiate_table($data);
        $platform_obj->prepare_items();
        $platform_obj->display_table();

        ?>
                </form>
        </div>
    <?php
}

    public function awp_save_easysendy_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_easysendy_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }
        $api_token = sanitize_text_field($_POST["awp_easysendy_api_token"]);
        $display_name = sanitize_text_field($_POST["awp_easysendy_display_name"]);
        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('easysendy');
        $platform_obj->save_platform(['account_name' => $display_name, 'api_key' => $api_token]);

        AWP_redirect("admin.php?page=automate_hub&tab=easysendy");
    }

    public function awp_easysendy_js_fields($field_data)
    {
    }

    public function load_custom_script()
    {
        wp_enqueue_script('awp-easysendy-script', AWP_URL . '/apps/e/easysendy/easysendy.js', array('awp-vuejs'), '', 1);
    }

    public function action_fields()
    {
        ?>
    <script type="text/template" id="easysendy-action-template">
                <?php

        $app_data = array(
            'app_slug' => 'easysendy',
            'app_name' => 'EasySendy',
            'app_icon_url' => AWP_ASSETS . '/images/icons/easysendy.png',
            'app_icon_alter_text' => 'EasySendy Icon',
            'account_select_onchange' => 'getEasySendyList',
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
                'create_list' => array(
                ),
            ),
        );

        require AWP_VIEWS . '/awp_app_integration_format.php';
        ?>
    </script>
<?php
}

    /*
     * Get easysendy subscribers lists
     */
    public function awp_get_easysendy_list()
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
        $platform_obj = new AWP_Platform_Shell_Table('easysendy');
        $data = $platform_obj->awp_get_platform_detail_by_id($id);
        if (!$data) {
            die(esc_html__('No Data Found', 'automate_hub'));
        }
        $api_token = $data->api_key;

        if (!$api_token) {
            return array();
        }

        $url = "http://api.easysendy.com/ver1/listAPI?api_key=" . $api_token . "&req_type=allLists";

        $data = wp_remote_request($url);
        if (is_wp_error($data)) {
            wp_send_json_error();
        }

        $body = json_decode($data["body"]);

        $lists = wp_list_pluck($body->listsData, 'name', 'list_uid');

        wp_send_json_success($lists);
    }
}

$AWP_EasySendy = new AWP_EasySendy();

/*
 * Saves connection mapping
 */
function awp_easysendy_save_integration()
{
    Appfactory::save_integration();
}

/*
 * Handles sending data to easysendy API
 */
function awp_easysendy_send_data($record, $posted_data)
{

    $temp = json_decode(($record["data"]), true);
    $temp = $temp["field_data"];
    $platform_obj = new AWP_Platform_Shell_Table('easysendy');
    $temp = $platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_token = $temp->api_key;

    if (!$api_token) {
        return;
    }

    $decoded_data = AWP_EasySendy::decode_data($record, $posted_data);
    $data = $decoded_data["data"];
    $list_id = $data["listId"];
    $task = $decoded_data["task"];

    if ($task == "subscribe") {
        $email = empty($data["email"]) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $fname = empty($data["fname"]) ? "" : awp_get_parsed_values($data["fname"], $posted_data);
        $lname = empty($data["lname"]) ? "" : awp_get_parsed_values($data["lname"], $posted_data);
        $cf = empty($data["customFields"]) ? "" : awp_get_parsed_values($data["customFields"], $posted_data);

        $data = array(
            'list' => $list_id,
            'EMAIL' => $email,
            'FNAME' => $fname,
            'LNAME' => $lname,
            'api_key' => $api_token,
        );

        if ($cf) {
            $cf = explode(",", $cf);
            $data["customFields"] = $cf;
        }

        $url = "http://api.easysendy.com/rest/subscriber/add";

        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
            ),
            'body' => json_encode($data),
        );

        $return = wp_remote_post($url, $args);
        $args['body'] = $data;
        $args['body']['api_key'] = 'XXXXXXXXXXX';
        awp_add_to_log($return, $url, $args, $record);
    }

    if ($task == 'create_list') {
        $title = empty($data["title"]) ? "" : awp_get_parsed_values($data["title"], $posted_data);
        $description = empty($data["description"]) ? "" : awp_get_parsed_values($data["description"], $posted_data);
        $cf = empty($data["customFields"]) ? "" : awp_get_parsed_values($data["customFields"], $posted_data);

        $data = array(
            'name' => $title,
            'description' => $description,
            'api_key' => $api_token,
        );

        if ($cf) {
            $cf = explode(",", $cf);
            $data["customFields"] = $cf;
        }

        $url = "http://api.easysendy.com/rest/subscribers_list/create";

        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
            ),
            'body' => json_encode($data),
        );

        $return = wp_remote_post($url, $args);
        $args['body'] = $data;
        $args['body']['api_key'] = 'XXXXXXXXXXX';
        awp_add_to_log($return, $url, $args, $record);
    }

    return $return;
}

function awp_easysendy_resend_data($log_id, $data, $integration)
{
    $temp = json_decode($integration["data"], true);
    $temp = $temp["field_data"];
    $platform_obj = new AWP_Platform_Shell_Table('easysendy');
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

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
            ),
            'body' => json_encode($data),
        ];

        $response = wp_remote_post($url, $args);
        $args['body'] = $data['args']['body'];
        $args['body']['api_key'] = 'XXXXXXXXXXX';
        awp_add_to_log($response, $url, $args, $integration);
    }

    if ($task == "create_list") {

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
            ),
            'body' => json_encode($data),
        ];

        $response = wp_remote_post($url, $args);
        $args['body'] = $data['args']['body'];
        $args['body']['api_key'] = 'XXXXXXXXXXX';
        awp_add_to_log($response, $url, $args, $integration);
    }

    $response['success'] = true;
    return $response;
}
