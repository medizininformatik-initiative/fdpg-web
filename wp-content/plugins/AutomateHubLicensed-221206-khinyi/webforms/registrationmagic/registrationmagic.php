<?php

add_filter('awp_form_providers', 'awp_registrationmagic_add_provider');

function awp_registrationmagic_add_provider($providers)
{

    if (is_plugin_active('custom-registration-form-builder-with-submission-manager/registration_magic.php')) {
        $providers['registrationmagic'] = esc_html__('RegistrationMagic', 'automate_hub');
    }

    return $providers;
}

function awp_registrationmagic_get_forms($form_provider)
{

    if ($form_provider != 'registrationmagic') {
        return;
    }

    global $wpdb;

    $query = $wpdb->prepare("SELECT form_id, form_name FROM {$wpdb->prefix}rm_forms", array());
    $result = $wpdb->get_results($query, ARRAY_A);
    $forms = wp_list_pluck($result, 'form_name', 'form_id');

    return $forms;
}

function awp_registrationmagic_get_form_fields($form_provider, $form_id)
{

    if ($form_provider != 'registrationmagic') {
        return;
    }

    global $wpdb;

    $query = $wpdb->prepare("SELECT field_id, field_label, field_type FROM {$wpdb->prefix}rm_fields WHERE form_id = %d", $form_id);
    $result = $wpdb->get_results($query, ARRAY_A);
    $fields = wp_list_pluck($result, 'field_label', 'field_id');

    return $fields;
}

/*
 * Get Form name by form id
 */
function awp_registrationmagic_get_form_name($form_provider, $form_id)
{
    if ($form_provider != "registrationmagic") {
        return;
    }
    global $wpdb;
    $form_name = $wpdb->get_var($wpdb->prepare("SELECT form_name FROM {$wpdb->prefix}rm_forms WHERE form_id =%d", $form_id));
    return $form_name;
}

add_action('rm_submission_completed', 'awp_registrationmagic_submission', 10, 3);

function awp_registrationmagic_submission($form_id, $user_id, $submission_data)
{

    $posted_data = [];

    if (is_array($submission_data)) {
        foreach ($submission_data as $key => $value) {
            $posted_data[$key] = $value->value;
        }
    }

    $posted_data["submission_date"] = date("Y-m-d");
    $posted_data["user_ip"] = awp_get_user_ip();

    //tracking info
    include AWP_INCLUDES . '/tracking_info_cookies.php';

    global $wpdb;

    $saved_records = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}awp_integration WHERE status = 1 AND form_provider = 'registrationmagic' AND form_id = %d", $form_id), ARRAY_A);

    foreach ($saved_records as $record) {
        $action_provider = isset($record['action_provider']) ? $record['action_provider'] : '';
        awp_add_queue_form_submission("awp_{$action_provider}_send_data", $record, $posted_data);
    }
}
