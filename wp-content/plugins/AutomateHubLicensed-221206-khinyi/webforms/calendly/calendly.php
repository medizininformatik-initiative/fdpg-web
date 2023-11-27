<?php

class AWP_CalendlyForm
{
    public function __construct()
    {
        add_filter('awp_form_providers', [$this, 'awp_calendly_add_provider']);
        add_action("rest_api_init", [$this, "create_webhook_route"]);
    }

    public function awp_calendly_add_provider($providers)
    {
        $providers['calendly'] = esc_html__('Calendly', 'automate_hub');
        return $providers;
    }

    public function create_webhook_route()
    {
        register_rest_route('automatehub', '/calendly',
            [
                'methods' => 'GET',
                'callback' => [$this, 'get_webhook_data'],
                'permission_callback' => function () {return '';},
            ]);
    }

    public function get_webhook_data($request)
    {
        $params = $request->get_params();
        $event_uuid = isset($params['event_uuid']) ? trim($params['event_uuid']) : '';
        $invitee_uuid = isset($params['invitee_uuid']) ? trim($params['invitee_uuid']) : '';

        $posted_data = [];
        $id = sanitize_text_field('calendly');
        $platform_db_name = 'awp_platform_settings';
        global $wpdb;
        $query = $wpdb->prepare("SELECT* FROM {$wpdb->prefix}$platform_db_name where platform_name='%d' AND active_status ='true'",$id);
        $results = $wpdb->get_results($query, OBJECT);
        $results = (count($results) ? $results[0] : false);
    
        $api_token = $results->api_key;
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $api_token,
            ),
        );
        $get_invitee_url = "https://api.calendly.com/scheduled_events/" . $event_uuid . "/invitees/" . $invitee_uuid . "";

        $data = wp_remote_request($get_invitee_url, $args);
        $body = json_decode($data["body"]);
        $resources = $body->resource;

        $posted_data['email'] = $resources->email;
        $posted_data['name'] = $resources->name;
        $posted_data['created_at'] = $resources->created_at;
        $posted_data['status'] = $resources->status;
        $event_answers = $resources->questions_and_answers;
        foreach ($event_answers as $key => $event_answer) {
            $posted_data['answer' . ($key + 1)] = $event_answer->answer;
        }
        $posted_data['timezone'] = $resources->timezone;
        $posted_data["submission_date"] = date("Y-m-d");
        $posted_data["user_ip"] = awp_get_user_ip();
        
        //tracking info
        include AWP_INCLUDES.'/tracking_info_cookies.php';

        $form_id = $event_uuid;

        global $wpdb;

        $saved_records = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}awp_integration WHERE status = 1 AND form_provider='calendly'"), ARRAY_A);

        foreach ($saved_records as $record) {
            $action_provider = $record['action_provider'];
            awp_add_queue_form_submission("awp_{$action_provider}_send_data",$record,$posted_data);
        }

        if (isset($event_uuid) && isset($invitee_uuid)) {
            wp_safe_redirect(home_url('/'));
            exit();
        }
    }

}

$AWP_CalendlyForm = new AWP_CalendlyForm();

function awp_calendly_get_forms($form_provider)
{
    if ($form_provider != 'calendly') {
        return;
    }

    if (!isset($_POST['formProviderId'])) {
        die(esc_html__('Invalid Request', 'automate_hub'));
    }
    $id = isset($_POST['formProviderId']) ? sanitize_text_field($_POST['formProviderId']):'';
    $platform_db_name = 'awp_platform_settings';

    global $wpdb;
    $query = $wpdb->prepare("SELECT* FROM {$wpdb->prefix}$platform_db_name where platform_name='%d' AND active_status ='true'",$id);
    $results = $wpdb->get_results($query, OBJECT);
    $results = (count($results) ? $results[0] : false);

    $api_token = $results->api_key;
    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $api_token,
        ),
    );

    $user_details_url = "https://api.calendly.com/users/me";
    $data = wp_remote_request($user_details_url, $args);
    $body = json_decode($data["body"]);
    $user_organization = $body->resource->current_organization;

    if (is_wp_error($data)) {
        wp_send_json_error();
    }

    $event_url = "https://api.calendly.com/event_types?organization=" . $user_organization;
    $events_data = wp_remote_request($event_url, $args);
    $events_body = json_decode($events_data["body"]);
    $events_collection = $events_body->collection;

    $events = wp_list_pluck($events_collection, 'name', 'uri');
    $new_events = json_encode($events);
    $string = str_replace('https:\/\/api.calendly.com\/event_types\/', "", $new_events);
    $reconverted_string = json_decode($string);
    return $reconverted_string;
}

function awp_calendly_get_form_fields($form_provider, $form_id)
{
    if ($form_provider != 'calendly') {
        return;
    }
    $unique_form_id = substr($form_id, -36);
    if (!isset($_POST['formProviderId'])) {
        die(esc_html__('Invalid Request', 'automate_hub'));
    }
    $id = isset($_POST['formProviderId']) ? sanitize_text_field($_POST['formProviderId']) :'';
    $platform_db_name = 'awp_platform_settings';

    global $wpdb;
    $query = $wpdb->prepare("SELECT* FROM {$wpdb->prefix}$platform_db_name where platform_name='%d' AND active_status ='true'",$id);
    $results = $wpdb->get_results($query, OBJECT);
    $results = (count($results) ? $results[0] : false);
    $api_token = $results->api_key;
    $args = array(
        'headers' => array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $api_token,
        ),
    );
    $user_details_url = "https://api.calendly.com/event_types/" . $unique_form_id;
    $data = wp_remote_request($user_details_url, $args);
    $body = json_decode($data["body"]);
    $resources = $body->resource;
    $custom_questions = $body->resource->custom_questions;
    $picked = wp_list_pluck($body->resource->custom_questions, "name", "type");
    $picked['name'] = "Name";
    $picked['email'] = "Email";
    $picked['event_name'] = "Event Name: " . $resources->name;
    $picked['event_duration'] = "Event Duration: " . $resources->duration;
    return $picked;
}
