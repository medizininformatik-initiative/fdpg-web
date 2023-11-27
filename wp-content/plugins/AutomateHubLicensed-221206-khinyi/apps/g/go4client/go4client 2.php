<?php

class AWP_Go4client extends appfactory
{

    public function init_actions()
    {
        add_action('admin_post_awp_go4client_save_api_token', [$this, 'awp_save_go4client_api_token'], 10, 0);
    }

    public function init_filters()
    {
        add_filter('awp_platforms_connections', [$this, 'awp_go4client_platform_connection'], 10, 1);
    }

    public function action_provider($providers)
    {
        $providers['go4client'] = array(
            'title' => esc_html__('Go4client', 'automate_hub'),
            'tasks' => array(
                'createcontact' => esc_html__('Create Contact', 'automate_hub'),
                'createvoicecampaign' => esc_html__('Create Voice Campaign', 'automate_hub'),
            ),
        );
        return $providers;
    }

    public function settings_tab($tabs)
    {
        $tabs['go4client'] = array('name' => esc_html__('Go4client', 'automate_hub'), 'cat' => array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'go4client') {
            return;
        }
        $nonce = wp_create_nonce("awp_go4client_settings");
        $api_token = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $display_name = isset($_GET['account_name']) ? $_GET['account_name'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/go4client" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/go4client.png" width="192" height="30" alt="Go4clients Logo"></a><br /><br />
            <?php
require_once AWP_INCLUDES . '/class_awp_updates_manager.php';
        $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
        $instruction_obj->prepare_instructions();
        ?>

                <br />
                <?php 

$form_fields = '';
$app_name= 'go4client';
$go4client_form = new AWP_Form_Fields($app_name);

$form_fields = $go4client_form->awp_wp_text_input(
    array(
        'id'            => "awp_go4client_display_name",
        'name'          => "awp_go4client_display_name",
        'value'         => $display_name,
        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $go4client_form->awp_wp_text_input(
    array(
        'id'            => "awp_go4client_api_token",
        'name'          => "awp_go4client_api_token",
        'value'         => $api_token,
        'placeholder'   => esc_html__( 'Enter your Go 4 client API Key', 'automate_hub' ),
        'label'         =>  esc_html__( 'Go 4 client API Key', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $go4client_form->awp_wp_text_input(
    array(
        'id'            => "awp_go4client_client_secret",
        'name'          => "awp_go4client_client_secret",
        'value'         => $client_secret,
        'placeholder'   => esc_html__( ' Secret Key', 'automate_hub' ),
        'label'         =>  esc_html__( 'Enter You Go4client Secret Code', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $go4client_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_go4client_save_api_token',
    )
);


$form_fields .= $go4client_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $go4client_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$go4client_form->render($form_fields);

?>

        </div>

        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
$data = [
            'table-cols' => ['account_name' => 'Display name', 'api_key' => 'API Key', 'spots' => 'Active Spots', 'active_status' => 'Active'],
        ];
        $platform_obj = new AWP_Platform_Shell_Table('go4client');
        $platform_obj->initiate_table($data);
        $platform_obj->prepare_items();
        $platform_obj->display_table();

        ?>
                        </form>
                </div>
        <?php
}

    public function action_fields()
    {
        ?>
            <script type="text/template" id="go4client-action-template">
                <?php

        $app_data = array(
            'app_slug' => 'go4client',
            'app_name' => 'Go4client ',
            'app_icon_url' => AWP_ASSETS . '/images/icons/go4client.png',
            'app_icon_alter_text' => 'Go4client  Icon',
            'account_select_onchange' => '',
            'tasks' => array(
                'createcustomer' => array(
                    'task_assignments' => array(
                        array(
                            'label' => 'Select Source',
                            'type' => 'select',
                            'name' => "source_id",
                            'required' => 'required',
                            'onchange' => 'selectedsource',
                            'option_for_loop' => '(item) in data.sourceList',
                            'option_for_value' => 'item.id',
                            'option_for_text' => '{{item.provider}}',
                            'select_default' => 'Select Source...',
                            'spinner' => array(
                                'bind-class' => "{'is-active': accountLoading}",
                            ),
                        ),
                    ),
                ),
                'createplan' => array(
                    'task_assignments' => array(

                        array(
                            'label' => 'Select Source',
                            'type' => 'select',
                            'name' => "source_id",
                            'required' => 'required',
                            'onchange' => 'selectedsource',
                            'option_for_loop' => '(item) in data.sourceList',
                            'option_for_value' => 'item.id',
                            'option_for_text' => '{{item.provider}}',
                            'select_default' => 'Select Source...',
                            'spinner' => array(
                                'bind-class' => "{'is-active': accountLoading}",
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

    public function awp_save_go4client_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_go4client_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_token = sanitize_text_field($_POST["awp_go4client_api_token"]);
        $display_name = sanitize_text_field($_POST["awp_go4client_display_name"]);
        $client_secret = sanitize_text_field($_POST["awp_go4client_client_secret"]);

        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('go4client');
        $platform_obj->save_platform(['account_name' => $display_name, 'api_key' => $api_token, 'client_secret' => $client_secret]);

        AWP_redirect("admin.php?page=automate_hub&tab=go4client");
    }

    public function load_custom_script()
    {
        wp_enqueue_script('awp-go4client-script', AWP_URL . '/apps/g/go4client/go4client.js', array('awp-vuejs'), '', 1);
    }

};

$Awp_Go4client = new AWP_Go4client();

function awp_go4client_save_integration()
{
    Appfactory::save_integration();
}

function awp_go4client_send_data($record, $posted_data)
{
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"];

    $platform_obj = new AWP_Platform_Shell_Table('go4client');
    $temp = $platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key = $temp->api_key;
    $task = $decoded_data["task"];

    if ($task == "createcontact") {

        $name = empty($data["name"]) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $phonenumber = empty($data["phonenumber"]) ? "" : awp_get_parsed_values($data["phonenumber"], $posted_data);
        $gender = empty($data["gender"]) ? "" : awp_get_parsed_values($data["gender"], $posted_data);
        $client_secret = $temp->client_secret;

        $body = json_encode([
            "name" => $name,
            "mobileNumber" => $phonenumber,
            "gender" => $gender,
        ]);

        $url = "https://cloud.go4clients.com:8580/api/groupscontacts/contacts/v1.0";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'apiKey' => $api_key,
                'apiSecret' => $client_secret,
            ),

            'body' => $body,
        ];

        $response = wp_remote_post($url, $args);

        $args['headers']['apiKey'] = "XXXXXXXXXXXX";
        $args['headers']['apiSecret'] = "XXXXXXXXXXXX";

        awp_add_to_log($response, $url, $args, $record);

    } else if ($task == "createvoicecampaign") {

        $name = empty($data["name"]) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $sender = empty($data["csender"]) ? "" : awp_get_parsed_values($data["csender"], $posted_data);
        $earliesttimetocall = empty($data["earliestTimeToCall"]) ? "" : awp_get_parsed_values($data["earliestTimeToCall"], $posted_data);
        $client_secret = $temp->client_secret;

        $body = json_encode([
            "name" => $name,
            "sender" => $sender,
            "earliestTimeToCall" => $earliesttimetocall,
        ]);

        $url = "https://cloud.go4clients.com:8580/api/campaigns/voice/v1.0";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'apiKey' => $api_key,
                'apiSecret' => $client_secret,
            ),

            'body' => $body,
        ];

        $response = wp_remote_post($url, $args);

        $args['headers']['apiKey'] = "XXXXXXXXXXXX";
        $args['headers']['apiSecret'] = "XXXXXXXXXXXX";

        awp_add_to_log($response, $url, $args, $record);

    }
    return $response;
}

function awp_go4client_resend_data($log_id, $data, $integration)
{
    $temp = json_decode($integration["data"], true);
    $temp = $temp["field_data"];

    $platform_obj = new AWP_Platform_Shell_Table('go4client');
    $temp = $platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_key = $temp->api_key;
    if (!$api_key) {
        return;
    }
    $data = stripslashes($data);
    $data = preg_replace('/\s+/', '', $data);
    $data = str_replace('"{', '{', $data);
    $data = str_replace('}"', '}', $data);
    $data = json_decode($data, true);
    $body = $data['args']['body'];
    $url = $data['url'];
    if (!$url) {
        $response['success'] = false;
        $response['msg'] = "Syntax Error! Request is invalid";
        return $response;
    }

    $campfireUrl = $url;
    $token = 'Bearer ' . $api_key;

    $args = [
        'headers' => array(
            'Content-Type' => 'application/json',
            'Authorization' => $token,
            'User-Agent' => "Sperse (https://www.sperse.com/)",
        ),

        'body' => json_encode($body),
    ];

    $return = wp_remote_post($campfireUrl, $args);

    $args['headers']['Authorization'] = "Bearer XXXXXXXXXXXX";
    awp_add_to_log($return, $campfireUrl, $args, $integration);

    $response['success'] = true;
    return $response;
}
