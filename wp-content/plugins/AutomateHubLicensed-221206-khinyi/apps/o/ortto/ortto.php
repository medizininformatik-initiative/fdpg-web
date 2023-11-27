<?php
class AWP_Ortto extends appfactory
{

    public function init_actions()
    {
        add_action('admin_post_awp_ortto_save_api_token', [$this, 'awp_save_ortto_api_token'], 10, 0);
    }

    public function init_filters()
    {
        add_filter('awp_platforms_connections', [$this, 'awp_ortto_platform_connection'], 10, 1);
    }

    public function action_provider($actions)
    {
        $actions['ortto'] = [
            'title' => esc_html__('Ortto', 'automate_hub'),
            'tasks' => array('subscribe' => esc_html__('Add Person', 'automate_hub')),
        ];
        return $actions;
    }

    public function settings_tab($tabs)
    {
        $tabs['ortto'] = array('name' => esc_html__('Ortto', 'automate_hub'), 'cat' => array('crm'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'ortto') {
            return;
        }
        $nonce = wp_create_nonce("awp_ortto_settings");
        $api_key = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $display_name = isset($_GET['account_name']) ? $_GET['account_name'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/ortto" target="_blank"><img src="<?=AWP_ASSETS_REMOTE;?>/images/logos/ortto.png" height="50" alt="Ortto Logo"></a><br /><br />
            <?php
require_once AWP_INCLUDES . '/class_awp_updates_manager.php';
        $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
        $instruction_obj->prepare_instructions();

        ?>
                <br />
                <?php

        $form_fields = '';
        $app_name = 'ortto';
        $ortto_form = new AWP_Form_Fields($app_name);

        $form_fields = $ortto_form->awp_wp_text_input(
            array(
                'id' => "awp_ortto_display_name",
                'name' => "awp_ortto_display_name",
                'value' => $display_name,
                'placeholder' => esc_html__('Enter an identifier for this account', 'automate_hub'),
                'label' => esc_html__('Display Name', 'automate_hub'),
                'wrapper_class' => 'form-row',
                'show_copy_icon' => true,

            )
        );

        $form_fields .= $ortto_form->awp_wp_text_input(
            array(
                'id' => "awp_ortto_api_token",
                'name' => "awp_ortto_api_token",
                'value' => $api_key,
                'placeholder' => esc_html__('Enter your Ortto API Key', 'automate_hub'),
                'label' => esc_html__('Ortto API Key', 'automate_hub'),
                'wrapper_class' => 'form-row',
                'show_copy_icon' => true,

            )
        );

        $form_fields .= $ortto_form->awp_wp_hidden_input(
            array(
                'name' => "action",
                'value' => 'awp_ortto_save_api_token',
            )
        );

        $form_fields .= $ortto_form->awp_wp_hidden_input(
            array(
                'name' => "_nonce",
                'value' => $nonce,
            )
        );
        $form_fields .= $ortto_form->awp_wp_hidden_input(
            array(
                'name' => "id",
                'value' => wp_unslash($id),
            )
        );

        $ortto_form->render($form_fields);

        ?>
        </div>


        <div class="wrap">
                <form id="form-list" method="post">
                    <input type="hidden" name="page" value="automate_hub"/>

                    <?php
$data = [
            'table-cols' => ['account_name' => 'Display name', 'api_key' => 'API Key', 'spots' => 'Active Spots', 'active_status' => 'Active'],
        ];
        $platform_obj = new AWP_Platform_Shell_Table('ortto');
        $platform_obj->initiate_table($data);
        $platform_obj->prepare_items();
        $platform_obj->display_table();

        ?>
                </form>
        </div>
    <?php
}

    public function awp_save_ortto_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_ortto_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }
        $api_token = sanitize_text_field($_POST["awp_ortto_api_token"]);
        $display_name = sanitize_text_field($_POST["awp_ortto_display_name"]);
        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('ortto');
        $platform_obj->save_platform(['account_name' => $display_name, 'api_key' => $api_token]);

        AWP_redirect("admin.php?page=automate_hub&tab=ortto");
    }

    public function awp_ortto_js_fields($field_data)
    {
    }

    public function load_custom_script()
    {
        wp_enqueue_script('awp-ortto-script', AWP_URL . '/apps/o/ortto/ortto.js', array('awp-vuejs'), '', 1);
    }

    public function action_fields()
    {
        ?>
    <script type="text/template" id="ortto-action-template">
                <?php

        $app_data = array(
            'app_slug' => 'ortto',
            'app_name' => 'Ortto',
            'app_icon_url' => AWP_ASSETS . '/images/icons/ortto.png',
            'app_icon_alter_text' => 'Ortto Icon',
            'account_select_onchange' => 'getOrttoList',
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

$AWP_Ortto = new AWP_Ortto();

/*
 * Saves connection mapping
 */
function awp_ortto_save_integration()
{
    Appfactory::save_integration();
}

/*
 * Handles sending data to ortto API
 */
function awp_ortto_send_data($record, $posted_data)
{

    $temp = json_decode(($record["data"]), true);
    $temp = $temp["field_data"];
    $platform_obj = new AWP_Platform_Shell_Table('ortto');
    $temp = $platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_token = $temp->api_key;

    if (!$api_token) {
        return;
    }

    $decoded_data = AWP_Ortto::decode_data($record, $posted_data);
    $data = $decoded_data["data"];
    $list_id = $data["listId"];
    $task = $decoded_data["task"];

    if ($task == "subscribe") {
        $email = empty($data["email"]) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $firstName = empty($data["firstName"]) ? "" : awp_get_parsed_values($data["firstName"], $posted_data);
        $lastName = empty($data["lastName"]) ? "" : awp_get_parsed_values($data["lastName"], $posted_data);
        $cf = empty($data["customFields"]) ? "" : awp_get_parsed_values($data["customFields"], $posted_data);

        $data = [
            "people" => [
                [
                    "fields" => [
                        "str::first" => $firstName,
                        "str::last" => $lastName,
                        "str::email" => $email,
                    ],
                ],
            ],
            "async" => false,
        ];

        if ($cf) {
            $cf = explode(",", $cf);
            $data["customFields"] = $cf;
        }

        $url = "https://api.ap3api.com/v1/person/merge";

        $args = array(
            'method' => 'POST',
            'headers' => array(
                'Content-Type' => 'application/json',
                'X-Api-Key' => $api_token,
            ),
            'body' => json_encode($data),
        );

        $return = wp_remote_request($url, $args);
        $args['body'] = $data;
        $args['headers']['X-Api-Key'] = 'XXXXXXXXXXX';
        awp_add_to_log($return, $url, $args, $record);
    }
    return $return;
}

function awp_ortto_resend_data($log_id, $data, $integration)
{
    $temp = json_decode($integration["data"], true);
    $temp = $temp["field_data"];
    $platform_obj = new AWP_Platform_Shell_Table('ortto');
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
                'X-Api-Key' => $api_token,
            ),
            'body' => json_encode($data['args']['body']),
        );

        $return = wp_remote_request($url, $args);
        $args['headers']['X-Api-Key'] = 'XXXXXXXXXXX';
        $args['body'] = $data['args']['body'];
        awp_add_to_log($response, $url, $args, $integration);
    }

    $response['success'] = true;
    return $response;
}
