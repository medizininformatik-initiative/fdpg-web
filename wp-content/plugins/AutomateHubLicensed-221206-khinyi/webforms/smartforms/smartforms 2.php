<?php

add_filter( 'awp_form_providers', 'awp_smartforms_add_provider' );

function awp_smartforms_add_provider( $providers ) {

    if ( is_plugin_active( 'smart-forms/smartforms.php' ) ) {
        $providers['smartforms'] = esc_html__( 'Smart Forms', 'automate_hub' );
    }

    return $providers;
}

function awp_smartforms_get_forms( $form_provider ) {

    if ( $form_provider != 'smartforms' ) {
        return;
    }

    global $wpdb;

    $query  = $wpdb->prepare("SELECT form_id, form_name FROM {$wpdb->prefix}rednao_smart_forms_table_name",array());
    $result = $wpdb->get_results( $query, ARRAY_A );
    $forms  = wp_list_pluck( $result, 'form_name', 'form_id' );

    return $forms;
}

function awp_smartforms_get_form_fields( $form_provider, $form_id ) {

    if ( $form_provider != 'smartforms' ) {
        return;
    }

    global $wpdb;

    $query                     = $wpdb->prepare("SELECT element_options FROM {$wpdb->prefix}rednao_smart_forms_table_name WHERE form_id = %d",$form_id);
    $result                    = $wpdb->get_results( $query, ARRAY_A );
    $decoded                   = json_decode( $result[0]["element_options"] );
    $fields                    = wp_list_pluck( $decoded, 'Label', 'Id' );


    return $fields;
}

/*
 * Get Form name by form id
 */
function awp_smartforms_get_form_name( $form_provider, $form_id ) {

    if ( $form_provider != "smartforms" ) {
        return;
    }

    global $wpdb;

    $form_name = $wpdb->get_var($wpdb->prepare( "SELECT form_name FROM {$wpdb->prefix}rednao_smart_forms_table_name WHERE form_id =%d", $form_id ));

    return $form_name;
}

add_action( 'sf_after_saving_form', 'awp_smartforms_submission' );

function awp_smartforms_submission( $data ) {

    $form_id     = $data->FormId;
    $posted_data = array();

    if( is_array( $data->FormEntryData ) ) {
        foreach( $data->FormEntryData as $key => $value ) {
            $posted_data[$key] = $value["value"];
        }
    }

    $posted_data["submission_date"] = date( "Y-m-d" );
    $posted_data["user_ip"]         = awp_get_user_ip();
    //tracking info
    include AWP_INCLUDES.'/tracking_info_cookies.php';

    global $wpdb;

    $saved_records = $wpdb->get_results($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}awp_integration WHERE status = 1 AND form_provider = 'smartforms' AND form_id = %d",$form_id), ARRAY_A );

    foreach ( $saved_records as $record ) {
        $action_provider = isset($record['action_provider']) ? $record['action_provider']:'';
        awp_add_queue_form_submission("awp_{$action_provider}_send_data",$record,$posted_data);
    }
}
