<?php

add_filter( 'awp_form_providers', 'awp_weforms_add_provider' );

function awp_weforms_add_provider( $providers ) {

    if ( is_plugin_active( 'weforms/weforms.php' ) ) {
        $providers['weforms'] = esc_html__( 'weForms', 'automate_hub' );
    }

    return $providers;
}

function awp_weforms_get_forms( $form_provider ) {

    if ( $form_provider != 'weforms' ) {
        return;
    }

    $forms    = weforms()->form->get_forms();
    $filtered = wp_list_pluck( $forms["forms"], "name", "id" );

    return $filtered;
}

function awp_weforms_get_form_fields( $form_provider, $form_id ) {

    if ( $form_provider != 'weforms' ) {
        return;
    }

    $form                        = weforms()->form->get( $form_id );
    $form_fields                 = $form->get_fields();
    $filtered                    = wp_list_pluck( $form_fields, "label", "name" );

    return $filtered;
}

function awp_weforms_get_form_name( $form_provider, $form_id ) {

    if ( $form_provider != "weforms" ) {
        return;
    }

    $form      = weforms()->form->get( $form_id );
    $form_name = $form->get_name();

    return $form_name;
}

add_action( "weforms_entry_submission", "awp_weforms_submission", 10, 2 );

function awp_weforms_submission( $entry_id, $form_id ) {

    $entry                          = weforms_get_entry_data( $entry_id );
    $posted_data                    = $entry["data"];
    $posted_data["submission_date"] = date( "Y-m-d" );
    $posted_data["user_ip"]         = awp_get_user_ip();
    //tracking info
    include AWP_INCLUDES.'/tracking_info_cookies.php';

    global $wpdb;
   
    $saved_records = $wpdb->get_results($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}awp_integration WHERE status = 1 AND form_provider = 'weforms' AND form_id = '%d'",$form_id), ARRAY_A );

    foreach ( $saved_records as $record ) {
        $action_provider = isset($record['action_provider']) ? $record['action_provider']:'';
        awp_add_queue_form_submission("awp_{$action_provider}_send_data",$record,$posted_data);
    }
}
