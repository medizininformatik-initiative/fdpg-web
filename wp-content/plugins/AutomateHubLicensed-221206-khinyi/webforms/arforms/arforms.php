<?php

add_filter('awp_form_providers', 'awp_arforms_add_provider');

function awp_arforms_add_provider($providers)
{

    if (is_plugin_active('arforms-form-builder/arforms-form-builder.php')) {
        $providers['arforms'] = esc_html__('ARForms', 'automate_hub');
    }

    return $providers;
}

function awp_arforms_get_forms($form_provider)
{

    if ($form_provider != 'arforms') {
        return;
    }

    global $wpdb;

    $query = $wpdb->prepare("SELECT id, name FROM {$wpdb->prefix}arflite_forms", array());
    $result = $wpdb->get_results($query, ARRAY_A);
    $forms = wp_list_pluck($result, 'name', 'id');

    return $forms;
}

function awp_arforms_get_form_fields($form_provider, $form_id)
{

    if ($form_provider != 'arforms') {
        return;
    }

    global $wpdb;

    $query = $wpdb->prepare("SELECT id, name, type FROM {$wpdb->prefix}arflite_fields WHERE form_id = %d", $form_id);
    $result = $wpdb->get_results($query, ARRAY_A);
    $fields = wp_list_pluck($result, 'name', 'id');

    return $fields;
}

function awp_arforms_get_form_name($form_provider, $form_id)
{

    if ($form_provider != "arforms") {
        return;
    }
    global $wpdb;
    $form_name = $wpdb->get_var($wpdb->prepare("SELECT name FROM {$wpdb->prefix}arflite_forms WHERE id =%d", $form_id));
    return $form_name;
}

add_action("arfliteentryexecute", "awp_arforms_submission", 10, 3);


function awp_arforms_submission($submission_data)
{
    $form_id = $submission_data['posted_form_id'];

    global $wpdb;
    $id = $wpdb->get_var($wpdb->prepare("SELECT id FROM {$wpdb->prefix}arflite_entries WHERE form_id =%d", $form_id));

    $entry_values = $wpdb->get_results($wpdb->prepare("SELECT entry_value FROM {$wpdb->prefix}arflite_entry_values WHERE entry_id =%d", $id));

    // error_log(print_r($entry_values, true), 0);

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
