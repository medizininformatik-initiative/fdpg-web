<?php

add_filter( 'awp_form_providers', 'awp_forminator_add_provider' );

function awp_forminator_add_provider( $providers ) {

    if ( is_plugin_active( 'forminator/forminator.php' ) ) {
        $providers['forminator'] = esc_html__( 'Forminator', 'automate_hub' );
    }

    return $providers;
}

function awp_forminator_get_forms( $form_provider ) {

    if ( $form_provider != 'forminator' ) {
        return;
    }

    global $wpdb;

    $form_data = get_posts( array(
        'post_type'           => 'forminator_forms',
        'post_status'         => 'publish'
    ) );

    $forms = wp_list_pluck( $form_data, "post_title", "ID" );

    return $forms;
}

function awp_forminator_get_form_fields( $form_provider, $form_id ) {

    if ( $form_provider != 'forminator' ) {
        return;
    }

    if( !$form_id ) {
        return;
    }

    $form_data = get_post_meta( $form_id );
    $data      = maybe_unserialize( $form_data["forminator_form_meta"][0] );
    $fields    = wp_list_pluck( $data["fields"], "field_label", "id" );

    return $fields;
}

/*
 * Get Form name by form id
 */
function awp_forminator_get_form_name( $form_provider, $form_id ) {

    if ( $form_provider != "forminator" ) {
        return;
    }

    $form      = get_post( $form_id );
    $form_name = $form->post_title;

    return $form_name;
}

add_action( 'forminator_custom_form_after_save_entry', 'awp_forminator_submission', 30, 2 );

function awp_forminator_submission( $form_id, $response ) {

    $form_id     = $form_id;
    $posted_data = array();

    if(!empty($_POST)){
      foreach( $_POST as $key => $value ) {
            $posted_data[$key] = isset($value) ? sanitize_text_field( $value ):'';
        }

    }
    $posted_data["submission_date"] = date( "Y-m-d" );
    $posted_data["user_ip"]         = awp_get_user_ip();
    //tracking info
    include AWP_INCLUDES.'/tracking_info_cookies.php';

    global $wpdb;

    $saved_records = $wpdb->get_results($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}awp_integration WHERE status = 1 AND form_provider = 'forminator' AND form_id = %d", $form_id), ARRAY_A );

    foreach ( $saved_records as $record ) {
        $action_provider = isset($record['action_provider']) ? $record['action_provider']:'';
        awp_add_queue_form_submission("awp_{$action_provider}_send_data",$record,$posted_data);
    }
}
