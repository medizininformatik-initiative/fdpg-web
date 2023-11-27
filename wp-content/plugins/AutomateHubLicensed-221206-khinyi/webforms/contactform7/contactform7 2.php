<?php

add_filter( 'awp_form_providers', 'awp_contactform7_add_provider' );

function awp_contactform7_add_provider( $providers ) {

    if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
        $providers['contactform7'] = esc_html__( 'Contact Form 7', 'automate_hub' );
    }

    return $providers;
}

function awp_contactform7_get_forms( $form_provider ) {
    if( $form_provider != 'contactform7' ) {
        return;
    }
    $args     = array( 'post_type' => 'wpcf7_contact_form', 'posts_per_page' => -1 );
    $contactform7Forms = get_posts( $args );
    $forms    = wp_list_pluck( $contactform7Forms, 'post_title', 'ID' );
    return $forms;
}

function awp_contactform7_field_type_and_key_pair( $content ) {
    $regexp_type = '/(?<=\[)[^\s\*]*/';
    $regexp_name = '/(?<=\s)[^\s\]]*/';
    $Field_type  = [];
    $Field_key   = [];
    if ( preg_match( $regexp_type, $content, $Field_type ) == false ) {
        return false;
    }
    if ( !in_array($Field_type[0], ['text', 'email', 'url', 'tel', 'number', 'range', 'date', 'textarea', 'select', 'checkbox', 'radio', 'acceptance', 'quiz', 'file', 'hidden' ] ) ) {
        return false;
    }
    if ( preg_match($regexp_name, $content, $Field_key ) == false ) {
        return false;
    }
    return $Field_key[0];
}
function awp_contactform7_form_field_parser( $val ) {
    $array = [];
    if ( preg_match_all('/\[.*\]/', $val, $array) == false ) {
        return false;
    }
    return $array[0];
}
function awp_contactform7_get_form_fields( $form_provider, $form_id ) {
    if( $form_provider != 'contactform7' ) {
        return;
    }
    $meta         = get_post_meta( $form_id, '_form', true );
    $fields       = awp_contactform7_form_field_parser( $meta );
    $final_fields = array();
    if( $fields ) {
        foreach ( $fields as $field ) {
            $single = awp_contactform7_field_type_and_key_pair( $field );
            if ( $single ) {
                $final_fields[$single] = $single;
            }
        }
    }
    $final_fields["submission_date"] = esc_html__( "Submission Date", "automate_hub" );
    return $final_fields;
}

function awp_contactform7_get_form_name( $form_provider, $form_id ) {

    if ( $form_provider != "contactform7" ) {
        return;
    }

    $form = get_post( $form_id );

    return $form->post_title;
}

add_action( "wpcf7_before_send_mail", "awp_contactform7_submission", 10, 3 );

function awp_contactform7_submission( $WPcontactform7_ContactForm,$abort, $submission ) {

    $posted_data = $submission->get_posted_data();
    $form_id     = !empty($posted_data['_wpcontactform7']) ? $posted_data['_wpcontactform7'] :$WPcontactform7_ContactForm->id();
    $posted_data["submission_date"] = date( "Y-m-d" );
    //tracking info
    include AWP_INCLUDES.'/tracking_info_cookies.php';
    global $wpdb;
    $query=$wpdb->prepare("SELECT * FROM {$wpdb->prefix}awp_integration WHERE status = 1 AND form_provider = 'contactform7' AND form_id =%d", $form_id);
    $saved_records = $wpdb->get_results( $query , ARRAY_A );
    foreach ( $saved_records as $record ) {
        $action_provider = isset($record['action_provider']) ? $record['action_provider']:'';
         $return=awp_add_queue_form_submission("awp_{$action_provider}_send_data",$record,$posted_data);
    }
    
    return $WPcontactform7_ContactForm;
}
