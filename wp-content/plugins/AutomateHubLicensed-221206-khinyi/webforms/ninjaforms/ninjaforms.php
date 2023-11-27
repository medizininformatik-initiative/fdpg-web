<?php

add_filter( 'awp_form_providers', 'awp_ninjaforms_add_provider' );

function awp_ninjaforms_add_provider( $providers ) {

    if ( is_plugin_active( 'ninja-forms/ninja-forms.php' ) ) {
        $providers['ninjaforms'] = esc_html__( 'Ninja Forms', 'automate_hub' );
    }

    return $providers;
}

function awp_ninjaforms_get_forms( $form_provider ) {

    if ( $form_provider != 'ninjaforms' ) {
        return;
    }

    $data  = Ninja_Forms()->form()->get_forms();
    $forms = [ ];

    foreach ( $data as $single ) {
        $forms[$single->get_id()] = $single->get_setting( "title" );
    }

    return $forms;
}

function awp_ninjaforms_get_form_fields( $form_provider, $form_id ) {

    if ( $form_provider != 'ninjaforms' ) {
        return;
    }

    $data   = Ninja_Forms()->form( $form_id )->get_fields();
    $fields = [ ];

    foreach ( $data as $single ) {
        $fields[$single->get_id()] = $single->get_setting( "label" );
    }

    $fields["form_id"]       = esc_html__( "Form ID", "automate_hub" );
    $fields["submission_id"] = esc_html__( "Submission ID", "automate_hub" );

    return $fields;
}

function awp_ninjaforms_get_form_name( $form_provider, $form_id ) {

    if ( $form_provider != "ninjaforms" ) {
        return;
    }

    $form      = Ninja_Forms()->form( $form_id )->get();
    $form_name = $form->get_setting( "title" );

    return $form_name;
}


function awp_ninjaforms_date_handling($posted_data){
    foreach ($posted_data as $key => $element) {
        if(is_array($element) && isset($element['date'])){
            
            $datetime=$element['date'].' '.$element['hour'].':'.$element['minute'].' '.$element['ampm'];
            $strtime =strtotime($datetime);
            $posted_data[$key]=date('Y-m-d H:i:s', $strtime);
        }
    }
    return $posted_data;
}
add_action( 'ninja_forms_after_submission', 'awp_ninjaforms_after_submission' );

function awp_ninjaforms_after_submission( $form_data ) {

    $posted_data                    = wp_list_pluck( $form_data["fields"], "value", "id" );
    $posted_data["submission_date"] = date( "Y-m-d" );
    $posted_data["form_id"]         = $form_data["form_id"];
    $posted_data["submission_id"]   = awp_ninjaforms_get_submission_id( $form_data["actions"]["save"]["sub_id"] );
    $posted_data["user_ip"]         = awp_get_user_ip();

    //handling for fields that have array in them e.g date and time is sent in array seperately
    $posted_data=awp_ninjaforms_date_handling($posted_data);
    //tracking info
    include AWP_INCLUDES.'/tracking_info_cookies.php';


    global $wpdb;

    $saved_records = $wpdb->get_results( $wpdb->prepare("SELECT * FROM {$wpdb->prefix}awp_integration WHERE status = 1 AND form_provider = 'ninjaforms' AND form_id =%d ", $form_data["form_id"]), ARRAY_A );

    foreach ( $saved_records as $record ) {
        $action_provider = isset($record['action_provider']) ? $record['action_provider']:'';
        awp_add_queue_form_submission("awp_{$action_provider}_send_data",$record,$posted_data);
    }
}

function awp_ninjaforms_get_submission_id( $post_id ) {
    $submission_id = 0;

    if( $post_id ) {
        $submission_id = get_post_meta( $post_id, '_seq_num', true );
    }

    return $submission_id;
}
