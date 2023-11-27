<?php

add_filter( 'awp_form_providers', 'awp_piotnetforms_add_provider' );

function awp_piotnetforms_add_provider( $providers ) {
    if ( is_plugin_active( 'piotnetforms-pro/piotnetforms-pro.php' ) ) {
        $providers['piotnetforms'] = esc_html__( 'Piotnet Pro Forms', 'automate_hub' );
    }
    return $providers;
}

function awp_piotnetforms_get_forms( $form_provider ) {

    if ( $form_provider != 'piotnetforms' ) {
        return;
    }

    global $wpdb;

    $form_data = get_posts( array(
        'post_type'           => 'piotnetforms',
        'ignore_sticky_posts' => true,
        'nopaging'            => true,
        'post_status'         => 'publish'
    ) );


    $forms = wp_list_pluck( $form_data, "post_title", "ID" );

    return $forms;
}

function awp_piotnetforms_get_form_fields( $form_provider, $form_id ) {

    if ( $form_provider != 'piotnetforms' ) {
        return;
    }

    if( !$form_id ) {
        return;
    }

    $data     = json_decode( get_post_meta( $form_id, '_piotnetforms_data', true ), true );
    $widget = $data['widgets'];
    $fields = array();
    foreach($widget as $key=>$field_settings){

        if($field_settings['type']='field'){
            $fields[$field_settings['settings']['field_id']] = $field_settings['settings']['field_label'];
        }

    }

    return $fields;
}

/*
 * Get Form name by form id
 */
function awp_piotnetforms_get_form_name( $form_provider, $form_id ) {

    if ( $form_provider != "piotnetforms" ) {
        return;
    }

    $form      = get_post( $form_id );
    $form_name = $form->post_title;

    return $form_name;
}

add_action( 'piotnetforms/form_builder/remote_request_response', 'awp_piotnetforms_submission', 30, 4 );

function awp_piotnetforms_submission( $form_submission, $remote_request_response, $webhook_response) {


     $form_id                        = isset($form_submission['form']["id"]) ? $form_submission['form']["id"] :'' ;
     $posted_data                    = wp_list_pluck( $form_submission["fields"], "value", "id" ) ;
     $posted_data["submission_date"] = date( "Y-m-d" );
     $posted_data["user_ip"]         = awp_get_user_ip();
    // tracking info
     include AWP_INCLUDES.'/tracking_info_cookies.php';

     global $wpdb;

     $saved_records = $wpdb->get_results($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}awp_integration WHERE status = 1 AND form_provider = 'piotnetforms' AND form_id = %d",$form_id ), ARRAY_A );

    foreach ( $saved_records as $record ) {
        $action_provider = isset($record['action_provider']) ? $record['action_provider']:'';
        awp_add_queue_form_submission("awp_{$action_provider}_send_data",$record,$posted_data);
    }
}
