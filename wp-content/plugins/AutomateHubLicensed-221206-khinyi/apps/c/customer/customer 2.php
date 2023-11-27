<?php

class AWP_Customer extends appfactory
{

    public function init_actions()
    {
        add_action('admin_post_awp_customer_save_api_token', [$this, 'awp_save_customer_api_token'], 10, 0);
    }

    public function init_filters()
    {
        add_filter('awp_platforms_connections', [$this, 'awp_customer_platform_connection'], 10, 1);
    }

    public function action_provider($actions)
    {
        $actions['customer'] = [
            'title' => esc_html__('Customer.io', 'automate_hub'),
            'tasks' => array('subscribe' => esc_html__('Add a Person', 'automate_hub')),
        ];
        return $actions;
    }

    public function settings_tab($tabs)
    {
        $tabs['customer'] = array('name' => esc_html__('Customer', 'automate_hub'), 'cat' => array('crm'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'customer') {
            return;
        }
        $nonce = wp_create_nonce("awp_customer_settings");
        $api_key = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $site_id = isset($_GET['client_id']) ? $_GET['client_id'] : "";
        $account_name = isset($_GET['account_name']) ? $_GET['account_name'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/customer.io" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/customer.png" width="271" height="50" alt="Customer.io Logo"></a><br /><br />
            <?php
require_once AWP_INCLUDES . '/class_awp_updates_manager.php';
        $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
        $instruction_obj->prepare_instructions();

        ?><br />
         <?php 

$form_fields = '';
$app_name= 'customerio';
$customerio_form = new AWP_Form_Fields($app_name);

$form_fields = $customerio_form->awp_wp_text_input(
    array(
        'id'            => "awp_customer_account_name",
        'name'          => "awp_customer_account_name",
        'value'         => $display_name,
        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $customerio_form->awp_wp_text_input(
    array(
        'id'            => "awp_customer_site_id",
        'name'          => "awp_customer_site_id",
        'value'         => $site_id,
        'placeholder'   => esc_html__( 'Enter Site ID', 'automate_hub' ),
        'label'         =>  esc_html__( 'Site ID', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);
$form_fields .= $customerio_form->awp_wp_text_input(
    array(
        'id'            => "awp_customer_api_key",
        'name'          => "awp_customer_api_key",
        'value'         => $api_key,
        'placeholder'   => esc_html__( 'Enter API Key', 'automate_hub' ),
        'label'         =>  esc_html__( 'API Key', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $customerio_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_customer_save_api_token',
    )
);


$form_fields .= $customerio_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $customerio_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$customerio_form->render($form_fields);

?>


        </div>


        <div class="wrap">
                <form id="form-list" method="post">
                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
$data = [
            'table-cols' => ['account_name' => 'Account Name', 'client_id' => 'Site ID', 'active_status' => 'Active'],
        ];
        $platform_obj = new AWP_Platform_Shell_Table('customer');
        $platform_obj->initiate_table($data);
        $platform_obj->prepare_items();
        $platform_obj->display_table();

        ?>
                </form>
        </div>
    <?php
}

    public function awp_save_customer_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_customer_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }
        $account_name = sanitize_text_field($_POST["awp_customer_account_name"]);
        $site_id = sanitize_text_field($_POST["awp_customer_site_id"]);
        $api_key = sanitize_text_field($_POST["awp_customer_api_key"]);
        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('customer');
        $platform_obj->save_platform(['account_name' => $account_name, 'client_id' => $site_id, 'api_key' => $api_key]);

        AWP_redirect("admin.php?page=automate_hub&tab=customer");
    }

    public function awp_customer_js_fields($field_data)
    {
    }

    public function load_custom_script()
    {
        wp_enqueue_script('awp-customer-script', AWP_URL . '/apps/c/customer/customer.js', array('awp-vuejs'), '', 1);
    }

    public function action_fields()
    {
        ?>
        <script type="text/template" id="customer-action-template">
            <?php
$app_data = array(
            'app_slug' => 'customer',
            'app_name' => 'Customer',
            'app_icon_url' => AWP_ASSETS . '/images/icons/customer.png',
            'app_icon_alter_text' => 'Customer Icon',
            'account_select_onchange' => '',
            'tasks' => array(
                'subscribe' => array(
                    'task_assignments' => array(
                        array(
                            'label' => 'Select Account Region <a href="https://fly.customer.io/settings/privacy" target="_blank">Find Here</b>',
                            'type' => 'select',
                            'name' => "list_id",
                            'required' => 'required',
                            'model' => 'fielddata.list',
                            'option_for_loop' => '(item, index) in fielddata.fileList',
                            'select_default' => 'Select Data Center...',
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

}

$AWP_Customer = new AWP_Customer();

/*
 * Saves connection mapping
 */
function awp_customer_save_integration()
{
    Appfactory::save_integration();
}

/*
 * Handles sending data to Customer.io API
 */
function awp_customer_send_data($record, $posted_data)
{

    $temp = json_decode(($record["data"]), true);
    $temp = $temp["field_data"];
    $platform_obj = new AWP_Platform_Shell_Table('customer');
    $temp = $platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_key = $temp->api_key;
    $site_id = $temp->client_id;

    if (!$api_key) {
        return;
    }

    $decoded_data = AWP_Customer::decode_data($record, $posted_data);
    $data = $decoded_data["data"];
    $task = $decoded_data["task"];
    $data_center = $data['list'];

    if ($task == "subscribe") {
        $unique_id = "CIO" . mt_rand(00206, 99989);
        $email = empty($data["email"]) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $name = empty($data["name"]) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $cf = empty($data["customFields"]) ? "" : awp_get_parsed_values($data["customFields"], $posted_data);

        $data = array(
            'id' => $unique_id,
            'email' => $email,
            'name' => $name,
        );

        if ($cf) {
            $cf = explode(",", $cf);
            $data["customFields"] = $cf;
        }

        if ($data_center == "us") {
            $url = "https://track.customer.io/api/v1/customers/" . $unique_id;
        } else {
            $url = "https://track-eu.customer.io/api/v1/customers/" . $unique_id;
        }

        $args = array(
            'method' => 'PUT',
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($site_id . ':' . $api_key),
            ),
            'body' => json_encode($data),
        );

        $return = wp_remote_request($url, $args);
        $args['headers']['Authorization'] = 'Basic XXXXXXXXXXX';
        $args['body'] = $data;
        awp_add_to_log($return, $url, $args, $record);
    }
    return $return;
}

function awp_customer_resend_data($log_id, $data, $integration)
{
    $temp = json_decode($integration["data"], true);
    $temp = $temp["field_data"];
    $platform_obj = new AWP_Platform_Shell_Table('customer');
    $temp = $platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_key = $temp->api_key;
    $site_id = $temp->client_id;

    if (!$api_key) {
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
            'method' => 'PUT',
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($site_id . ':' . $api_key),
            ),
            'body' => json_encode($data['args']['body']),
        );

        $response = wp_remote_post($url, $args);
        $args['headers']['Authorization'] = 'Basic XXXXXXXXXX';
        $args['body'] = $data['args']['body'];
        awp_add_to_log($response, $url, $args, $integration);
    }
    $response['success'] = true;
    return $response;
}
