<?php

add_filter( 'awp_form_providers', 'awp_formidable_add_provider' );

function awp_formidable_add_provider( $providers ) {

    if ( is_plugin_active( 'formidable/formidable.php' ) ) {
        $providers['formidable'] = esc_html__( 'Formidable', 'automate_hub' );
    }

    return $providers;
}

function awp_formidable_get_forms( $form_provider ) {

    if ( $form_provider != 'formidable' ) {
        return;
    }

    global $wpdb;

    $query  = "SELECT id, name FROM {$wpdb->prefix}frm_forms";
    $result = $wpdb->get_results( $query, ARRAY_A );
    $forms  = wp_list_pluck( $result, 'name', 'id' );

    return $forms;
}

function awp_formidable_get_form_fields( $form_provider, $form_id ) {

    if ( $form_provider != 'formidable' ) {
        return;
    }

    global $wpdb;

    $query                     = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}frm_fields WHERE form_id = %d",$form_id);
    $result                    = $wpdb->get_results( $query, ARRAY_A );
    $fields                    = wp_list_pluck( $result, 'name', 'id' );

    return $fields;
}

add_action( 'frm_after_create_entry', 'awp_formidable_submission', 30, 2 );

function awp_formidable_submission( $entry_id, $form_id ) {
    $item_meta =isset($_POST["item_meta"]) ? $_POST["item_meta"]:array();
    $item_meta = array_map( 'sanitize_text_field', $item_meta );
    $posted_data =$item_meta;
    $posted_data["submission_date"] = date( "Y-m-d" );
    $posted_data["user_ip"]         = awp_get_user_ip();
    //tracking info
    include AWP_INCLUDES.'/tracking_info_cookies.php';


    global $wpdb;

    $saved_records = $wpdb->get_results($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}awp_integration WHERE status = 1 AND form_provider = 'formidable' AND form_id =%d ",$form_id), ARRAY_A );

    foreach ( $saved_records as $record ) {
        $action_provider = isset($record['action_provider']) ? $record['action_provider']:'';
        awp_add_queue_form_submission("awp_{$action_provider}_send_data",$record,$posted_data);
    }
}

function awp_formidable_get_form_name( $form_provider, $form_id ) {

    if ( $form_provider != "formidable" ) {
        return;
    }

    global $wpdb;

    $form_name = $wpdb->get_var( $wpdb->prepare("SELECT name FROM {$wpdb->prefix}frm_forms WHERE id = %d",$form_id ) );

    return $form_name;
}
