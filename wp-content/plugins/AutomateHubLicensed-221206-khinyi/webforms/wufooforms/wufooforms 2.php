<?php

class AWP_WufooWebForms
{
    public function __construct()
    {
        add_filter('awp_form_providers', [$this, 'awp_wufooforms_add_provider']);
        add_action("rest_api_init", [$this, "create_webhook_route"]);
    }

    public function awp_wufooforms_add_provider($providers)
    {
        $providers['wufooforms'] = esc_html__('WufooForms', 'automate_hub');
        return $providers;
    }

    public function create_webhook_route()
    {
        register_rest_route('automatehub', '/wufooforms',
            [
                'methods' => 'GET',
                'callback' => [$this, 'get_webhook_data'],
                'permission_callback' => function () {return '';},
            ]);
    }

    public function get_webhook_data($request)
    {
        $params = $request->get_params();
        $form_id = isset($params['form_id']) ? trim($params['form_id']) : '';

        $posted_data = [];
        $id = sanitize_text_field('wufooforms');
        $platform_db_name = 'awp_platform_settings';
        global $wpdb;
        $query = $wpdb->prepare("SELECT* FROM {$wpdb->prefix}$platform_db_name where platform_name='wufooforms' AND active_status ='true'", $id);
        $results = $wpdb->get_results($query, OBJECT);
        $results = (count($results) ? $results[0] : false);

        $account_name = $results->account_name;
        $api_token = $results->api_key;

        $args = [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($api_token . ':sperse'),
            ],
        ];

        $submitted_entries_uri = "https://" . $account_name . ".wufoo.com/api/v3/forms/" . $form_id . "/entries.json?sort=EntryId&sortDirection=DESC";

        $data = wp_remote_request($submitted_entries_uri, $args);
        $body = json_decode($data["body"]);

        error_log(print_r($form_id, true), 0);

        $resources = $body->Entries;

        foreach ($resources[0] as $key => $answer) {
            $posted_data[$key] = $answer;
        }

        //tracking info
        include AWP_INCLUDES . '/tracking_info_cookies.php';

        global $wpdb;

        $saved_records = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}awp_integration WHERE status = 1 AND form_provider='wufooforms'"), ARRAY_A);

        foreach ($saved_records as $record) {
            $action_provider = $record['action_provider'];
            awp_add_queue_form_submission("awp_{$action_provider}_send_data", $record, $posted_data);
        }

        if (isset($form_id)) {
            wp_safe_redirect(home_url('/'));
            exit();
        }
    }

}

$AWP_WufooWebForms = new AWP_WufooWebForms();

function awp_wufooforms_get_forms($form_provider)
{
    if ($form_provider != 'wufooforms') {
        return;
    }

    if (!isset($_POST['formProviderId'])) {
        die(esc_html__('Invalid Request', 'automate_hub'));
    }
    $id = isset($_POST['formProviderId']) ? sanitize_text_field($_POST['formProviderId']) : '';
    $platform_db_name = 'awp_platform_settings';

    global $wpdb;
    $query = $wpdb->prepare("SELECT* FROM {$wpdb->prefix}$platform_db_name where platform_name='$form_provider' AND active_status ='true'", $id);
    $results = $wpdb->get_results($query, OBJECT);
    $results = (count($results) ? $results[0] : false);

    $account_name = $results->account_name;
    $api_token = $results->api_key;

    $args = [
        'headers' => [
            'Authorization' => 'Basic ' . base64_encode($api_token . ':sperse'),
        ],
    ];

    $admin_forms_uri = "https://" . $account_name . ".wufoo.com/api/v3/forms.json";
    $data = wp_remote_request($admin_forms_uri, $args);
    $body = json_decode($data["body"]);

    $admin_forms = $body->Forms;

    if (is_wp_error($data)) {
        wp_send_json_error();
    }

    $plucked_forms = wp_list_pluck($admin_forms, 'Name', 'Hash');
    return $plucked_forms;
}

function awp_wufooforms_get_form_fields($form_provider, $form_id)
{
    if ($form_provider != 'wufooforms') {
        return;
    }
    if (!isset($_POST['formProviderId'])) {
        die(esc_html__('Invalid Request', 'automate_hub'));
    }
    $id = isset($_POST['formProviderId']) ? sanitize_text_field($_POST['formProviderId']) : '';
    $platform_db_name = 'awp_platform_settings';

    global $wpdb;
    $query = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}$platform_db_name where platform_name='$form_provider' AND active_status ='true'", $id);
    $results = $wpdb->get_results($query, OBJECT);
    $results = (count($results) ? $results[0] : false);

    $account_name = $results->account_name;
    $api_token = $results->api_key;

    $args = [
        'headers' => [
            'Authorization' => 'Basic ' . base64_encode($api_token . ':sperse'),
        ],
    ];

    $url = "http://" . $account_name . ".wufoo.com/api/v3/forms/" . $form_id . "/fields.json";
    $data = wp_remote_request($url, $args);
    $body = json_decode($data["body"]);

    $resources = $body->Fields;
    $picked = wp_list_pluck($resources, "Title", "ID");
    return $picked;
}
