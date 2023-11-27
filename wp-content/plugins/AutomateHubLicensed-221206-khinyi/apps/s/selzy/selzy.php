<?php
class AWP_Selzy extends appfactory
{

    public function init_actions()
    {
        add_action('admin_post_awp_selzy_save_api_token', [$this, 'awp_save_selzy_api_token'], 10, 0);
        add_action('wp_ajax_awp_get_selzy_list', [$this, 'awp_get_selzy_list'], 10, 0);
    }

    public function init_filters()
    {
        add_filter('awp_platforms_connections', [$this, 'awp_selzy_platform_connection'], 10, 1);
    }

    public function action_provider($actions)
    {
        $actions['selzy'] = [
            'title' => esc_html__('Selzy', 'automate_hub'),
            'tasks' => array('subscribe' => esc_html__('Subscribe To List', 'automate_hub')),
        ];
        return $actions;
    }

    public function settings_tab($tabs)
    {
        $tabs['selzy'] = array('name' => esc_html__('Selzy', 'automate_hub'), 'cat' => array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'selzy') {
            return;
        }
        $nonce = wp_create_nonce("awp_selzy_settings");
        $api_key = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $display_name = isset($_GET['account_name']) ? $_GET['account_name'] : "";

        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/selzy" target="_blank"><img src="<?=AWP_ASSETS_REMOTE;?>/images/logos/selzy.png"  height="50" alt="Selzy Logo"></a><br /><br />
            <?php
require_once AWP_INCLUDES . '/class_awp_updates_manager.php';
        $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
        $instruction_obj->prepare_instructions();

        ?>
                <br />
                <?php

        $form_fields = '';
        $app_name = 'selzy';
        $selzy_form = new AWP_Form_Fields($app_name);

        $form_fields = $selzy_form->awp_wp_text_input(
            array(
                'id' => "awp_selzy_display_name",
                'name' => "awp_selzy_display_name",
                'value' => $display_name,
                'placeholder' => esc_html__('Enter an identifier for this account', 'automate_hub'),
                'label' => esc_html__('Display Name', 'automate_hub'),
                'wrapper_class' => 'form-row',
                'show_copy_icon' => true,

            )
        );

        $form_fields .= $selzy_form->awp_wp_text_input(
            array(
                'id' => "awp_selzy_api_token",
                'name' => "awp_selzy_api_token",
                'value' => $api_key,
                'placeholder' => esc_html__('Enter your Selzy API Key', 'automate_hub'),
                'label' => esc_html__('Selzy API Key', 'automate_hub'),
                'wrapper_class' => 'form-row',
                'show_copy_icon' => true,

            )
        );

        $form_fields .= $selzy_form->awp_wp_hidden_input(
            array(
                'name' => "action",
                'value' => 'awp_selzy_save_api_token',
            )
        );

        $form_fields .= $selzy_form->awp_wp_hidden_input(
            array(
                'name' => "_nonce",
                'value' => $nonce,
            )
        );
        $form_fields .= $selzy_form->awp_wp_hidden_input(
            array(
                'name' => "id",
                'value' => wp_unslash($id),
            )
        );

        $selzy_form->render($form_fields);
        ?>
        </div>


        <div class="wrap">
                <form id="form-list" method="post">
                    <input type="hidden" name="page" value="automate_hub"/>

                    <?php
$data = [
            'table-cols' => ['account_name' => 'Display name', 'api_key' => 'API Key', 'spots' => 'Active Spots', 'active_status' => 'Active'],
        ];
        $platform_obj = new AWP_Platform_Shell_Table('selzy');
        $platform_obj->initiate_table($data);
        $platform_obj->prepare_items();
        $platform_obj->display_table();

        ?>
                </form>
        </div>
    <?php
}

    public function awp_save_selzy_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_selzy_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }
        $api_token = sanitize_text_field($_POST["awp_selzy_api_token"]);
        $display_name = sanitize_text_field($_POST["awp_selzy_display_name"]);
        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('selzy');
        $platform_obj->save_platform(['account_name' => $display_name, 'api_key' => $api_token]);

        AWP_redirect("admin.php?page=automate_hub&tab=selzy");
    }

    public function load_custom_script()
    {
        wp_enqueue_script('awp-selzy-script', AWP_URL . '/apps/s/selzy/selzy.js', array('awp-vuejs'), '', 1);
    }

    public function action_fields()
    {
        ?>
    <script type="text/template" id="selzy-action-template">
                <?php

        $app_data = array(
            'app_slug' => 'selzy',
            'app_name' => 'selzy',
            'app_icon_url' => AWP_ASSETS . '/images/icons/selzy.png',
            'app_icon_alter_text' => 'selzy Icon',
            'account_select_onchange' => 'getSelzyList',
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
            ),
        );

        require AWP_VIEWS . '/awp_app_integration_format.php';
        ?>
    </script>
<?php
}

    /*
     * Get Selzy contact lists
     */
    public function awp_get_selzy_list()
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
        $platform_obj = new AWP_Platform_Shell_Table('selzy');
        $data = $platform_obj->awp_get_platform_detail_by_id($id);
        if (!$data) {
            die(esc_html__('No Data Found', 'automate_hub'));
        }
        $api_token = $data->api_key;

        if (!$api_token) {
            return array();
        }

        $url = "https://api.selzy.com/en/api/getLists?format=json&api_key=" . $api_token;

        $data = wp_remote_request($url);
        if (is_wp_error($data)) {
            wp_send_json_error();
        }

        $body = json_decode($data["body"]);
        $lists = wp_list_pluck($body->result, 'title', 'id');
        wp_send_json_success($lists);
    }
}

$AWP_Selzy = new AWP_Selzy();

/*
 * Saves connection mapping
 */
function awp_selzy_save_integration()
{
    Appfactory::save_integration();
}

/*
 * Handles sending data to selzy API
 */
function awp_selzy_send_data($record, $posted_data)
{
    $temp = json_decode(($record["data"]), true);
    $temp = $temp["field_data"];
    $platform_obj = new AWP_Platform_Shell_Table('selzy');
    $temp = $platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_token = $temp->api_key;
    $app_id = $temp->client_id;

    if (!$api_token && !$app_id) {
        return;
    }

    $decoded_data = AWP_Selzy::decode_data($record, $posted_data);
    $data = $decoded_data["data"];
    $list_id = $data["listId"];
    $task = $decoded_data["task"];

    if ($task == "subscribe") {
        $email = empty($data["email"]) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $name = empty($data["name"]) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $phone = empty($data["phoneNumber"]) ? "" : awp_get_parsed_values($data["phoneNumber"], $posted_data);
        $cf = empty($data["customFields"]) ? "" : awp_get_parsed_values($data["customFields"], $posted_data);

        if ($cf) {
            $cf = explode(",", $cf);
            $data["customFields"] = $cf;
        }

        $url = "https://api.selzy.com/en/api/subscribe?format=json&api_key=" . $api_token . "&list_ids=" . $list_id . "&fields[email]=" . $email . "&fields[Name]=" . $name . "&fields[phone]=" . $phone . "&double_optin=0&overwrite=0";

        $return = wp_remote_request($url);
        awp_add_to_log($return, $url, $args, $record);
    }
    return $return;
}

function awp_selzy_resend_data($log_id, $data, $integration)
{
    $temp = json_decode($integration["data"], true);
    $temp = $temp["field_data"];
    $platform_obj = new AWP_Platform_Shell_Table('selzy');
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
        $response = wp_remote_post($url);
        awp_add_to_log($response, $url, $args, $integration);
    }

    $response['success'] = true;
    return $response;
}
