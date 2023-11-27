<?php

add_filter( 'awp_form_providers', 'awp_buddyboss_add_provider' );

function awp_buddyboss_add_provider( $providers ) {


    if ( is_plugin_active( 'buddyboss-platform/bp-loader.php' ) ) {
        $providers['buddyboss'] = esc_html__( 'BuddyBoss', 'automate_hub' );
    }

    return $providers;
}

function awp_buddyboss_get_forms( $form_provider ) {

    if ( $form_provider != 'buddyboss' ) {
        return;
    }

    $filtered = array('registration'=>'Registration');

    return $filtered;
}


function awp_buddyboss_xprofile_fields(){

    global $wpdb;
    $bp_prefix = bp_core_get_table_prefix();

    $results  = $wpdb->get_results($wpdb->prepare( "SELECT * FROM {$bp_prefix}bp_xprofile_fields WHERE group_id = '2'"),ARRAY_A );
    $data = array();
    if(!empty( $results)){
        foreach($results as $field){
            $key = 'field_'.$field['id'];
            $data[$key] = $field['name'];
        }
    }
    return $data;


}



function awp_buddyboss_get_form_fields( $form_provider, $form_id ) {

    if ( $form_provider != 'buddyboss' ) {
        return;
    }

    $fields = bp_nouveau_get_signup_fields('account_details');
    $data = array();

    foreach ( $fields as $name => $attributes ) {

        $data[$name] = isset($attributes['label']) ? sanitize_text_field($attributes['label']) :'' ;
    }


    if(bp_is_active( 'xprofile' )){

      $extra_fields = awp_buddyboss_xprofile_fields();

      $data  = $data+$extra_fields;

    }


    return $data;
}

function awp_buddyboss_get_form_name( $form_provider, $form_id ) {

    if ( $form_provider != "buddyboss" ) {
        return;
    }

    return 'Registration';
}

add_action( "bp_complete_signup", "awp_buddybossforms_submission", 55 );

function awp_buddybossforms_submission() {

    $data = array();
    if(!empty($_POST['signup_submit'])){

       

        $posted_data                    = !empty($_POST) ? $_POST : array();
        $posted_data["submission_date"] = date( "Y-m-d" );
        $posted_data["user_ip"]         = awp_get_user_ip();
        $form_id                        = 'registration';
        //tracking info
        include AWP_INCLUDES.'/tracking_info_cookies.php';


        global $wpdb;

        $saved_records = $wpdb->get_results($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}awp_integration WHERE status = 1 AND form_provider = 'buddyboss' AND form_id = '%d'",$form_id), ARRAY_A);

        foreach ( $saved_records as $record ) {
            $action_provider = isset($record['action_provider']) ? $record['action_provider']:'';
            $return=awp_add_queue_form_submission("awp_{$action_provider}_send_data",$record,$posted_data);
        }

    }

    exit();


}
