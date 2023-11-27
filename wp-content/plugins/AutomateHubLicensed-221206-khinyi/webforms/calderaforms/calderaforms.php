<?php

add_filter( 'awp_form_providers', 'awp_calderaforms_add_provider' );

function awp_calderaforms_add_provider( $providers ) {

    if ( is_plugin_active( 'caldera-forms/caldera-core.php' ) ) {
        $providers['calderaforms'] = esc_html__( 'Caldera Forms', 'automate_hub' );
    }

    return $providers;
}

function awp_calderaforms_get_forms( $form_provider ) {

    if ( $form_provider != 'calderaforms' ) {
        return;
    }

    $forms = Caldera_Forms_Forms::get_forms();
    $data  = [ ];

    foreach ( $forms as $form ) {
        $data[] = Caldera_Forms_Forms::get_form( $form );
    }

    $filtered = wp_list_pluck( $data, "name", "ID" );

    return $filtered;
}

function awp_calderaforms_get_form_fields( $form_provider, $form_id ) {

    if ( $form_provider != 'calderaforms' ) {
        return;
    }

    $data                      = Caldera_Forms_Forms::get_form( $form_id );
    $fields                    = wp_list_pluck( $data["fields"], "label", "ID" );
    return $fields;
}

function awp_calderaforms_get_form_name( $form_provider, $form_id ) {

    if ( $form_provider != "calderaforms" ) {
        return;
    }

    $data      = Caldera_Forms_Forms::get_form( $form_id );
    $form_name = $data["name"];

    return $form_name;
}

add_action( "caldera_forms_submit_complete", "awp_calderaforms_submission", 55 );

function awp_calderaforms_submission( $form ) {

    $data = array();

    foreach ( $form['fields'] as $field_id => $field ) {
        $data[$field_id] = Caldera_Forms::get_field_data( $field_id, $form );
    }

    $posted_data                    = $data;
    $posted_data["submission_date"] = date( "Y-m-d" );
    $posted_data["user_ip"]         = awp_get_user_ip();
    
    //tracking info
    include AWP_INCLUDES.'/tracking_info_cookies.php';

    $form_id                        = isset($form["ID"]) ? $form["ID"] :'' ;

    global $wpdb;

    $saved_records = $wpdb->get_results($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}awp_integration WHERE status = 1 AND form_provider = 'calderaforms' AND form_id = ",$form_id), ARRAY_A );

    foreach ( $saved_records as $record ) {
        $action_provider = isset($record['action_provider']) ? $record['action_provider']:'';
        $return=awp_add_queue_form_submission("awp_{$action_provider}_send_data",$record,$posted_data);
    }
}
