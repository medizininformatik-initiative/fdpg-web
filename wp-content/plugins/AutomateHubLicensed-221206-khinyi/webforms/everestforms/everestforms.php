<?php

add_filter( 'awp_form_providers', 'awp_everestforms_add_provider' );

function awp_everestforms_add_provider( $providers ) {


    if ( is_plugin_active( 'everest-forms/everest-forms.php' ) ) {
        $providers['everestforms'] = esc_html__( 'Everest Forms', 'automate_hub' );
    }
    return $providers;
}

function awp_everestforms_get_forms( $form_provider ) {

    if ( $form_provider != 'everestforms' ) {
        return;
    }

    global $wpdb;

    $form_data = get_posts( array(
        'post_type'           => 'everest_form',
        'ignore_sticky_posts' => true,
        'nopaging'            => true,
        'post_status'         => 'publish'
    ) );


    $forms = wp_list_pluck( $form_data, "post_title", "ID" );

    return $forms;
}

function awp_everestforms_get_form_fields( $form_provider, $form_id ) {

    if ( $form_provider != 'everestforms' ) {
        return;
    }

    if( !$form_id ) {
        return;
    }

    $form       = get_post( $form_id );
    $form_data  = json_decode( $form->post_content );
    $field_data = $form_data->form_fields;
    $fields     = wp_list_pluck( $field_data, "label", "id" );

    return $fields;
}

/*
 * Get Form name by form id
 */
function awp_everestforms_get_form_name( $form_provider, $form_id ) {

    if ( $form_provider != "everestforms" ) {
        return;
    }

    $form      = get_post( $form_id );
    $form_name = $form->post_title;

    return $form_name;
}

add_action( 'everest_forms_process_complete', 'awp_everestforms_submission', 30, 4 );

function awp_everestforms_submission( $form_fields, $entry, $form_data, $entry_id ) {

    $form_id                        = isset($form_data["id"]) ? $form_data["id"] :'' ;
    $posted_data                    = isset($entry["form_fields"]) ? $entry["form_fields"]:'' ;
    $posted_data["submission_date"] = date( "Y-m-d" );
    $posted_data["user_ip"]         = awp_get_user_ip();
    //tracking info
    include AWP_INCLUDES.'/tracking_info_cookies.php';


    global $wpdb;

    $saved_records = $wpdb->get_results($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}awp_integration WHERE status = 1 AND form_provider = 'everestforms' AND form_id = %d",$form_id ), ARRAY_A );

    foreach ( $saved_records as $record ) {
        $action_provider = isset($record['action_provider']) ? $record['action_provider']:'';
        awp_add_queue_form_submission("awp_{$action_provider}_send_data",$record,$posted_data);
    }
}
