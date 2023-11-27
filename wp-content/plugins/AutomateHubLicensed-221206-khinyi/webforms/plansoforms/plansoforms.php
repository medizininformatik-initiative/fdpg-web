<?php

add_filter( 'awp_form_providers', 'awp_plansoforms_add_provider' );

function awp_plansoforms_add_provider( $providers ) {

    if ( is_plugin_active( 'planso-forms/index.php' ) ) {
        $providers['plansoforms'] = esc_html__( 'PlanSo Forms', 'automate_hub' );
    }

    return $providers;
}

function awp_plansoforms_get_forms( $form_provider ) {

    if ( $form_provider != 'plansoforms' ) {
        return;
    }

    global $wpdb;

    $form_data = get_posts( array(
        'post_type'           => 'psfb',
        'post_status'         => -1
    ) );

    $forms = wp_list_pluck( $form_data, "post_title", "ID" );

    return $forms;
}

function awp_plansoforms_get_form_fields( $form_provider, $form_id ) {

    if ( $form_provider != 'plansoforms' ) {
        return;
    }

    if( !$form_id ) {
        return;
    }

    $form_data  = get_post( $form_id );
    $data       = json_decode( $form_data->post_content );
    $field_data = array();
    $converted  = (array) $data->fields;

    foreach( $data->fields as $field ) {
        foreach( $field as $single ) {
            array_push( $field_data, $single );
        }
    }

    $fields    = wp_list_pluck( $field_data, "label", "id" );

    return $fields;
}

/*
 * Get Form name by form id
 */
function awp_plansoforms_get_form_name( $form_provider, $form_id ) {

    if ( $form_provider != "plansoforms" ) {
        return;
    }

    $form      = get_post( $form_id );
    $form_name = $form->post_title;

    return $form_name;
}

add_action( 'psfb_submit_after_error_check_success', 'awp_plansoforms_submission',10,1 );

function awp_plansoforms_submission($atts) {
    $posted_data = array();

    if(!empty($_POST)){

           foreach( $_POST as $key => $value ) {
                $posted_data[$key] =  isset($value) ? sanitize_text_field( $value ) :'';
            }

    }

    $form_id                        = isset($posted_data["psfb_form_id"]) ?  $posted_data["psfb_form_id"]:'';
    $posted_data["submission_date"] = date( "Y-m-d" );
    $posted_data["user_ip"]         = awp_get_user_ip();
    //tracking info
    include AWP_INCLUDES.'/tracking_info_cookies.php';
    global $wpdb;

    

    $saved_records = $wpdb->get_results( $wpdb->prepare("SELECT * FROM {$wpdb->prefix}awp_integration WHERE status = 1 AND form_provider = 'plansoforms' AND form_id =%d ",$form_id ), ARRAY_A );

    foreach ( $saved_records as $record ) {
        $action_provider = isset($record['action_provider']) ? $record['action_provider']:'';
       
        if(!empty($record["data"])){
            $data    = json_decode( $record["data"], true );
            $data    = $data["field_data"];
            foreach ($data as $key => $data_set) {

                if(strripos($data_set,'{{')===0){
                    $data_set = str_replace("{{", '', $data_set);
                    $data_set = str_replace("}}", '', $data_set);
                    $posted_data[$data_set] = $posted_data[ucfirst($key)];
                }

            }
        }

        awp_add_queue_form_submission("awp_{$action_provider}_send_data",$record,$posted_data);
    }
}
