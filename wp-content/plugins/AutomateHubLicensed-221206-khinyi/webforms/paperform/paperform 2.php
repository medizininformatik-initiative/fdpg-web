<?php

class AWP_PaperWebForm
{
    public function __construct()
    {
        add_filter('awp_form_providers', [$this, 'awp_paperform_add_provider']);
        add_action("rest_api_init", [$this, "create_webhook_route"]);
    }

    public function awp_paperform_add_provider($providers)
    {
        $providers['paperform'] = esc_html__('PaperForm', 'automate_hub');
        return $providers;
    }

    public function create_webhook_route()
    {
        register_rest_route('automatehub', '/paperform',
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
        $submission_id = isset($params['submission_id']) ? trim($params['submission_id']) : '';

        $posted_data = [];
        $id = sanitize_text_field('paperform');
        $platform_db_name = 'awp_platform_settings';
        global $wpdb;
        $query = $wpdb->prepare("SELECT* FROM {$wpdb->prefix}$platform_db_name where platform_name='paperform' AND active_status ='true'", $id);
        $results = $wpdb->get_results($query, OBJECT);
        $results = (count($results) ? $results[0] : false);

        $api_token = $results->api_key;
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $api_token,
            ),
        );
        $get_submission_uri = "https://api.paperform.co/v1/forms/" . $form_id . "/submissions/" . $submission_id . "/";

        $data = wp_remote_request($get_submission_uri, $args);
        $body = json_decode($data["body"]);
        $resources = $body->results->submission->data;

        foreach ($resources as $key => $answer) {
            $posted_data[$key] = $answer;
        }

        //tracking info
        include AWP_INCLUDES . '/tracking_info_cookies.php';

        global $wpdb;

        $saved_records = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}awp_integration WHERE status = 1 AND form_provider='paperform'"), ARRAY_A);

        foreach ($saved_records as $record) {
            $action_provider = $record['action_provider'];
            awp_add_queue_form_submission("awp_{$action_provider}_send_data", $record, $posted_data);
        }

        if (isset($form_id) && isset($submission_id)) {
            wp_safe_redirect(home_url('/'));
            exit();
        }
    }

}

$AWP_PaperWebForm = new AWP_PaperWebForm();

function awp_paperform_get_forms($form_provider)
{
    if ($form_provider != 'paperform') {
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

    $api_token = $results->api_key;
    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $api_token,
        ),
    );

    $admin_forms_uri = "https://api.paperform.co/v1/forms";
    $data = wp_remote_request($admin_forms_uri, $args);
    $body = json_decode($data["body"]);

    $admin_forms = $body->results->forms;

    if (is_wp_error($data)) {
        wp_send_json_error();
    }

    $plucked_forms = wp_list_pluck($admin_forms, 'title', 'id');
    return $plucked_forms;
}

function awp_paperform_get_form_fields($form_provider, $form_id)
{
    if ($form_provider != 'paperform') {
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
    $api_token = $results->api_key;
    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $api_token,
        ),
    );
    $user_details_url = "https://api.paperform.co/v1/forms/" . $form_id . "/fields";
    $data = wp_remote_request($user_details_url, $args);
    $body = json_decode($data["body"]);
    $resources = $body->results->fields;
    $picked = wp_list_pluck($resources, "title", "key");
    return $picked;
}
