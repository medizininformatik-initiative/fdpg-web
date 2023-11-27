<?php

add_filter('awp_form_providers', 'awp_formmaker_add_provider');

function awp_formmaker_add_provider($providers)
{

    if (is_plugin_active('form-maker/form-maker.php')) {
        $providers['formmaker'] = esc_html__('Form Maker', 'automate_hub');
    }

    return $providers;
}

function awp_formmaker_get_forms($form_provider)
{

    if ($form_provider != 'formmaker') {
        return;
    }

    global $wpdb;

    $query = $wpdb->prepare("SELECT id, title FROM {$wpdb->prefix}formmaker", array());
    $result = $wpdb->get_results($query, ARRAY_A);
    $forms = wp_list_pluck($result, 'title', 'id');

    return $forms;
}

function awp_formmaker_get_form_fields($form_provider, $form_id)
{

    if ($form_provider != 'formmaker') {
        return;
    }

    global $wpdb;

    $query = $wpdb->prepare("SELECT form_fields FROM {$wpdb->prefix}formmaker WHERE id = %d", $form_id);
    $result = $wpdb->get_results($query, ARRAY_A);

    $wat = $result[0]['form_fields'];

    $pr = explode("*type*:", $wat);

    $matched = [];
    foreach ($pr as $key => $value) {
        preg_match('/\*(.*?)\*/', $value, $matches, PREG_OFFSET_CAPTURE);
        array_push($matched, $matches[1][0]);
    }

    return $matched;
}

function awp_formmaker_get_form_name($form_provider, $form_id)
{

    if ($form_provider != "formmaker") {
        return;
    }
    global $wpdb;
    $form_name = $wpdb->get_var($wpdb->prepare("SELECT title FROM {$wpdb->prefix}formmaker WHERE id =%d", $form_id));
    return $form_name;
}

add_action("registered_post_type_form-maker	", "awp_formmaker_submission", 10, 3);


function awp_formmaker_submission($submission_data)
{
    $form_id = $submission_data['posted_form_id'];

    global $wpdb;
    $id = $wpdb->get_var($wpdb->prepare("SELECT id FROM {$wpdb->prefix}formmaker WHERE form_id =%d", $form_id));

    $entry_values = $wpdb->get_results($wpdb->prepare("SELECT entry_value FROM {$wpdb->prefix}formmaker_submits WHERE group_id =%d", $id));

    error_log(print_r($submission_data, true), 0);

    $posted_data = [];

    if (is_array($entry_values)) {
        $cnt = 0;
        foreach ($entry_values as $value) {
            $posted_data[$cnt++] = $value->entry_value;
        }

    }

    $posted_data["submission_date"] = date("Y-m-d");
    $posted_data["user_ip"] = awp_get_user_ip();

    array_shift($posted_data);

    //tracking info
    include AWP_INCLUDES . '/tracking_info_cookies.php';

    global $wpdb;

    $saved_records = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}awp_integration WHERE status = 1 AND form_provider = 'registrationmagic' AND form_id = %d", $form_id), ARRAY_A);

    foreach ($saved_records as $record) {
        $action_provider = isset($record['action_provider']) ? $record['action_provider'] : '';
        awp_add_queue_form_submission("awp_{$action_provider}_send_data", $record, $posted_data);
    }
}
