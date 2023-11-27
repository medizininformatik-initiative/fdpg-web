<?php

add_filter( 'awp_form_providers', 'awp_happyforms_add_provider' );

function awp_happyforms_add_provider( $providers ) {

    if ( is_plugin_active( 'happyforms/happyforms.php' ) || function_exists('HappyForms') || is_plugin_active( 'happyforms-upgrade/happyforms-upgrade.php' ) ) {
        $providers['happyforms'] = esc_html__( 'Happy Forms', 'automate_hub' );
    }

    return $providers;
}

function awp_happyforms_get_forms( $form_provider ) {

    if ( $form_provider != 'happyforms' ) {
        return;
    }

    global $wpdb;

    $form_data = get_posts( array(
        'post_type'           => 'happyform',
        'post_status'         => 'publish',
        'numberposts'         => -1,
    ) );

    $forms = wp_list_pluck( $form_data, "post_title", "ID" );

    return $forms;
}

function awp_happyforms_get_form_fields( $form_provider, $form_id ) {

    if ( $form_provider != 'happyforms' ) {
        return;
    }

    if( !$form_id ) {
        return;
    }

    $form_data = happyforms_get_form_controller()->get( $form_id );
    $fields    = wp_list_pluck( $form_data["parts"], "label", "id" );

    return $fields;
}

/*
 * Get Form name by form id
 */
function awp_happyforms_get_form_name( $form_provider, $form_id ) {

    if ( $form_provider != "happyforms" ) {
        return;
    }

    $form      = get_post( $form_id );
    $form_name = $form->post_title;

    return $form_name;
}

add_action( 'happyforms_submission_success', 'awp_happyforms_submission', 30, 2 );

function awp_happyforms_submission( $submission, $form ) {

    $form_id                        = isset($form["ID"]) ? $form["ID"]:'' ;
    $posted_data                    = $submission;
    $posted_data["submission_date"] = date( "Y-m-d" );
    $posted_data["user_ip"]         = awp_get_user_ip();
    //tracking info
    include AWP_INCLUDES.'/tracking_info_cookies.php';


    global $wpdb;

    $saved_records = $wpdb->get_results($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}awp_integration WHERE status = 1 AND form_provider = 'happyforms' AND form_id =%d ",$form_id), ARRAY_A );

    foreach ( $saved_records as $record ) {
        $action_provider = isset($record['action_provider']) ? $record['action_provider']:'';
        awp_add_queue_form_submission("awp_{$action_provider}_send_data",$record,$posted_data);
    }
}
