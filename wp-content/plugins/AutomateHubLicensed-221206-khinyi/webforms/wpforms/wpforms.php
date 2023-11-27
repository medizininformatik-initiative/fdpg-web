<?php

add_filter( 'awp_form_providers', 'awp_wpforms_add_provider' );

function awp_wpforms_add_provider( $providers ) {

    if ( is_plugin_active( 'wpforms-lite/wpforms.php' ) ) {
        $providers['wpforms'] = esc_html__( 'WPForms', 'automate_hub' );
    }

    if ( is_plugin_active( 'wpforms/wpforms.php' ) ) {
        $providers['wpforms'] = esc_html__( 'WPForms', 'automate_hub' );
    }

    return $providers;
}

function awp_wpforms_get_forms( $form_provider ) {

    if ( $form_provider != 'wpforms' ) {
        return;
    }

    $args  = [ 'post_type' => 'wpforms', 'posts_per_page' => -1 ];
    $data  = get_posts( $args );
    $forms = wp_list_pluck( $data, 'post_title', 'ID' );

    return $forms;
}

function awp_wpforms_get_form_fields( $form_provider, $form_id ) {

    if ( $form_provider != 'wpforms' ) {
        return;
    }

    $form                      = get_post( $form_id );
    $data                      = json_decode( $form->post_content );
    $fields                    = (array) $data->fields;
    $picked                    = wp_list_pluck( $fields, "label", "id" );
    return $picked;
}

function awp_wpforms_get_form_name( $form_provider, $form_id ) {

    if ( $form_provider != "wpforms" ) {
        return;
    }

    $form = get_post( $form_id );

    return $form->post_title;
}

add_action( "wpforms_process", "awp_wpforms_submission", 10, 3 );

function awp_wpforms_submission( $fields, $entry, $form_data ) {

    global $wpdb;

    $posted_data                    = wp_list_pluck( $fields, 'value', 'id' );
    $posted_data["submission_date"] = date( "Y-m-d" );
    $posted_data["user_ip"]         = awp_get_user_ip();
    $form_id                        = isset($form_data["id"]) ? $form_data["id"] :'';
    $query= $wpdb->prepare("SELECT * FROM {$wpdb->prefix}awp_integration WHERE status = 1 AND form_provider = 'wpforms' AND form_id =%d ",$form_id);
    $saved_records                  = $wpdb->get_results( $query, ARRAY_A );
    
    //tracking info
    include AWP_INCLUDES.'/tracking_info_cookies.php';
    foreach ( $saved_records as $record ) {

        $action_provider = isset($record['action_provider']) ? $record['action_provider']:'';
        awp_add_queue_form_submission("awp_{$action_provider}_send_data",$record,$posted_data);
    }
}
