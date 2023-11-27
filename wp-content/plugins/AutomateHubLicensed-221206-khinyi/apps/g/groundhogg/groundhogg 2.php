<?php
class AWP_Groundhogg extends appfactory
{
    public function init_actions()
    {
        add_action('admin_post_awp_groundhogg_save_api_token', [$this, 'awp_save_groundhogg_api_token'], 10, 0);
    }

    public function init_filters()
    {
        add_filter('awp_platforms_connections', [$this, 'awp_groundhogg_platform_connection'], 10, 1);
    }

    public function action_provider($actions)
    {
        $actions['groundhogg'] = [
            'title' => esc_html__('Groundhogg', 'automate_hub'),
            'tasks' => array('subscribe' => esc_html__('Add Contact', 'automate_hub'), 'tag' => esc_html__('Create Tag', 'automate_hub')),
        ];
        return $actions;
    }

    public function settings_tab($tabs)
    {
        $tabs['groundhogg'] = array('name' => esc_html__('Groundhogg', 'automate_hub'), 'cat' => array('crm'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'groundhogg') {
            return;
        }
        $nonce = wp_create_nonce("awp_groundhogg_settings");
        $api_key = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $token = isset($_GET['client_secret']) ? $_GET['client_secret'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $display_name = isset($_GET['account_name']) ? $_GET['account_name'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/groundhogg" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/groundhogg.png" width="275" height="50" alt="groundhogg Logo"></a><br /><br />
            <?php
require_once AWP_INCLUDES . '/class_awp_updates_manager.php';
        $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
        $instruction_obj->prepare_instructions();

        ?>
                <br />
                <?php

        $form_fields = '';
        $app_name = 'groundhogg';
        $groundhogg_form = new AWP_Form_Fields($app_name);

        $form_fields = $groundhogg_form->awp_wp_text_input(
            array(
                'id' => "awp_groundhogg_display_name",
                'name' => "awp_groundhogg_display_name",
                'value' => $display_name,
                'placeholder' => esc_html__('Enter an identifier for this account', 'automate_hub'),
                'label' => esc_html__('Display Name', 'automate_hub'),
                'wrapper_class' => 'form-row',
                'show_copy_icon' => true,

            )
        );

        $form_fields .= $groundhogg_form->awp_wp_text_input(
            array(
                'id' => "awp_groundhogg_api_key",
                'name' => "awp_groundhogg_api_key",
                'value' => $api_key,
                'placeholder' => esc_html__('Enter your Groundhogg API Key', 'automate_hub'),
                'label' => esc_html__('Groundhogg Public API Key', 'automate_hub'),
                'wrapper_class' => 'form-row',
                'show_copy_icon' => true,

            )
        );

        $form_fields .= $groundhogg_form->awp_wp_text_input(
            array(
                'id' => "awp_groundhogg_token",
                'name' => "awp_groundhogg_token",
                'value' => $token,
                'placeholder' => esc_html__('Enter your Groundhogg Token', 'automate_hub'),
                'label' => esc_html__('Groundhogg Token', 'automate_hub'),
                'wrapper_class' => 'form-row',
                'show_copy_icon' => true,

            )
        );

        $form_fields .= $groundhogg_form->awp_wp_hidden_input(
            array(
                'name' => "action",
                'value' => 'awp_groundhogg_save_api_token',
            )
        );

        $form_fields .= $groundhogg_form->awp_wp_hidden_input(
            array(
                'name' => "_nonce",
                'value' => $nonce,
            )
        );
        $form_fields .= $groundhogg_form->awp_wp_hidden_input(
            array(
                'name' => "id",
                'value' => wp_unslash($id),
            )
        );

        $groundhogg_form->render($form_fields);
        ?>
        </div>


        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>

                    <?php
$data = [
            'table-cols' => ['account_name' => 'Display name', 'api_key' => 'Public API Key', 'client_secret' => 'Token', 'spots' => 'Active Spots', 'active_status' => 'Active'],
        ];
        $platform_obj = new AWP_Platform_Shell_Table('groundhogg');
        $platform_obj->initiate_table($data);
        $platform_obj->prepare_items();
        $platform_obj->display_table();

        ?>
                </form>
        </div>
    <?php
}

    public function awp_save_groundhogg_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_groundhogg_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }
        $api_key = sanitize_text_field($_POST["awp_groundhogg_api_key"]);
        $token = sanitize_text_field($_POST["awp_groundhogg_token"]);
        $display_name = sanitize_text_field($_POST["awp_groundhogg_display_name"]);
        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('groundhogg');
        $platform_obj->save_platform(['account_name' => $display_name, 'api_key' => $api_key, 'client_secret' => $token]);

        AWP_redirect("admin.php?page=automate_hub&tab=groundhogg");
    }

    public function awp_groundhogg_js_fields($field_data)
    {
    }

    public function load_custom_script()
    {
        wp_enqueue_script('awp-groundhogg-script', AWP_URL . '/apps/g/groundhogg/groundhogg.js', array('awp-vuejs'), '', 1);
    }

    public function action_fields()
    {
        ?>
    <script type="text/template" id="groundhogg-action-template">
    <?php

        $app_data = array(
            'app_slug' => 'groundhogg',
            'app_name' => 'Groundhogg',
            'app_icon_url' => AWP_ASSETS . '/images/icons/groundhogg.png',
            'app_icon_alter_text' => 'groundhogg Icon',
            'account_select_onchange' => '',
            'tasks' => array(
                'subscribe' => array(

                ),
                'tag' => array(

                ),
            ),
        );

        require AWP_VIEWS . '/awp_app_integration_format.php';
        ?>
    </script>
<?php
}
}

$AWP_Groundhogg = new AWP_Groundhogg();

/*
 * Saves connection mapping
 */
function awp_groundhogg_save_integration()
{
    Appfactory::save_integration();
}

/*
 * Handles sending data to groundhogg API
 */
function awp_groundhogg_send_data($record, $posted_data)
{

    $temp = json_decode(($record["data"]), true);
    $temp = $temp["field_data"];
    $platform_obj = new AWP_Platform_Shell_Table('groundhogg');
    $temp = $platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_key = $temp->api_key;
    $token = $temp->client_secret;

    if (!$api_key && !$token) {
        return;
    }

    $decoded_data = AWP_Groundhogg::decode_data($record, $posted_data);
    $data = $decoded_data["data"];

    $task = $decoded_data["task"];

    if ($task == "subscribe") {
        $email = empty($data["email"]) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $firstName = empty($data["firstName"]) ? "" : awp_get_parsed_values($data["firstName"], $posted_data);
        $lastName = empty($data["lastName"]) ? "" : awp_get_parsed_values($data["lastName"], $posted_data);
        $primaryPhone = empty($data["primaryPhone"]) ? "" : awp_get_parsed_values($data["primaryPhone"], $posted_data);
        $primaryPhoneExtension = empty($data["primaryPhoneExtension"]) ? "" : awp_get_parsed_values($data["primaryPhoneExtension"], $posted_data);
        $streetAddressOne = empty($data["streetAddressOne"]) ? "" : awp_get_parsed_values($data["streetAddressOne"], $posted_data);
        $streetAddressTwo = empty($data["streetAddressTwo"]) ? "" : awp_get_parsed_values($data["streetAddressTwo"], $posted_data);
        $city = empty($data["city"]) ? "" : awp_get_parsed_values($data["city"], $posted_data);
        $postalZip = empty($data["postalZip"]) ? "" : awp_get_parsed_values($data["postalZip"], $posted_data);
        $country = empty($data["country"]) ? "" : awp_get_parsed_values($data["country"], $posted_data);

        $cf = empty($data["customFields"]) ? "" : awp_get_parsed_values($data["customFields"], $posted_data);

        $data = [
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'meta' => [
                'primary_phone' => $primaryPhone,
                'primary_phone_extension' => $primaryPhoneExtension,
                'street_address_1' => $streetAddressOne,
                'street_address_2' => $streetAddressTwo,
                'city' => $city,
                'postal_zip' => $postalZip,
                'country' => $country,
            ],
        ];

        if ($cf) {
            $cf = explode(",", $cf);
            $data["customFields"] = $cf;
        }

        $url = rest_url('/gh/v3/contacts');

        $args = [
            'headers' => [
                'Gh-Token' => $token,
                'Gh-Public-Key' => $api_key,
            ],
            'body' => $data,
        ];

        $return = wp_remote_post($url, $args);
        $args['body'] = $data;
        $args['headers']['Gh-Token'] = 'XXXXXXXXXXX';
        $args['headers']['Gh-Public-Key'] = 'XXXXXXXXXXX';
        awp_add_to_log($return, $url, $args, $record);
    }

    if ($task == "tag") {
        $tag = empty($data["tag"]) ? "" : awp_get_parsed_values($data["tag"], $posted_data);
        $cf = empty($data["customFields"]) ? "" : awp_get_parsed_values($data["customFields"], $posted_data);

        $data = [
            "tags" => [
                $tag,
            ],
        ];

        if ($cf) {
            $cf = explode(",", $cf);
            $data["customFields"] = $cf;
        }

        $url = rest_url('/gh/v3/tags');
        $args = [
            'headers' => [
                'Gh-Token' => $token,
                'Gh-Public-Key' => $api_key,
            ],
            'body' => $data,
        ];

        $return = wp_remote_post($url, $args);
        $args['body'] = $data;
        $args['headers']['Gh-Token'] = 'XXXXXXXXXXX';
        $args['headers']['Gh-Public-Key'] = 'XXXXXXXXXXX';
        awp_add_to_log($return, $url, $args, $record);
    }

    return $return;
}

function awp_groundhogg_resend_data($log_id, $data, $integration)
{
    $temp = json_decode($integration["data"], true);
    $temp = $temp["field_data"];
    $platform_obj = new AWP_Platform_Shell_Table('groundhogg');
    $temp = $platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_token = $temp->api_key;

    if (!$api_token) {
        return;
    }

    $temp = json_decode($integration["data"], true);
    $temp = $temp["field_data"];

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
            'headers' => [
                'Gh-Token' => $token,
                'Gh-Public-Key' => $api_key,
            ],
            'body' => $data['args']['body'],
        ];

        $return = wp_remote_post($url, $args);
        $args['headers']['Gh-Token'] = 'XXXXXXXXXXX';
        $args['headers']['Gh-Public-Key'] = 'XXXXXXXXXXX';
        $args['body'] = $data['args']['body'];
        awp_add_to_log($response, $url, $args, $integration);
    }

    if ($task == "tag") {
        $args = [
            'headers' => [
                'Gh-Token' => $token,
                'Gh-Public-Key' => $api_key,
            ],
            'body' => $data,
        ];

        $return = wp_remote_post($url, $args);
        $args['headers']['Gh-Token'] = 'XXXXXXXXXXX';
        $args['headers']['Gh-Public-Key'] = 'XXXXXXXXXXX';
        $args['body'] = $data['args']['body'];
        awp_add_to_log($response, $url, $args, $integration);
    }

    $response['success'] = true;
    return $response;
}
