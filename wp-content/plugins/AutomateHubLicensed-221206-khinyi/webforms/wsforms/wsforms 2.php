<?php

add_filter( 'awp_form_providers', 'awp_wsforms_add_provider' );

function awp_wsforms_add_provider( $providers ) {

    if ( is_plugin_active('ws-form/ws-form.php') ) {
        $providers['wsforms'] = esc_html__( 'WSForms', 'automate_hub' );
    }
    if ( is_plugin_active('ws-form-pro/ws-form.php') ) {
        $providers['wsforms'] = esc_html__( 'WSForms', 'automate_hub' );
    }
    return $providers;
}

function awp_wsforms_get_forms( $form_provider ) {

    if ( $form_provider != 'wsforms' ) {
        return;
    }
    
    
    $join='';
    $where= " (status = 'publish')";
    $order_by='label ASC';
    $ws_form_form = New WS_Form_Form();
    $result = $ws_form_form->db_read_all($join, $where, $order_by);
    
    $filtered = wp_list_pluck( $result, "label", "id" );
    return $filtered;
}

function awp_wsforms_get_form_fields( $form_provider, $form_id ) {

    if ( $form_provider != 'wsforms' ) {
        return;
    }

    $ws_form_form = New WS_Form_Form();
    $ws_form_form->id = $form_id;
    $form_object = $ws_form_form->db_read_published();
    $fields = WS_Form_Common::get_fields_from_form($form_object, true);
    $fields = wp_list_pluck( $fields, "label", "id" );

    return $fields;
}

function awp_wsforms_get_form_name( $form_provider, $form_id ) {

    if ( $form_provider != "wsforms" ) {
        return;
    }

    $ws_form_form = New WS_Form_Form();
    $ws_form_form->id = $form_id ;
    $form_name =$ws_form_form->db_get_label();

    return $form_name;
}


add_action( "wsf_actions_post", "awp_wsforms_submission", 10,2 );

function awp_wsforms_submission( $form, $submit) {

    $temp=$submit->meta;

    $data=array();
    foreach ($temp as $key => $value) {
       if(isset($value['id'])){

            if(is_array($value['value'])){
                $str=implode(',', $value['value']);
                $data[$value['id']]=$str;
            }
            else{
                $data[$value['id']]=$value['value'];
            }
        
       }
    }

    $posted_data                    = $data;
    $posted_data["submission_date"] = date( "Y-m-d" );
    $posted_data["user_ip"]         = awp_get_user_ip();
    $form_id                        = $form->id;
    
    //tracking info
    include AWP_INCLUDES.'/tracking_info_cookies.php';
    
    global $wpdb;

    $saved_records = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}awp_integration WHERE status = 1 AND form_provider = 'wsforms' AND form_id = '{$form_id}'", ARRAY_A );

    foreach ( $saved_records as $record ) {
        $action_provider = isset($record['action_provider']) ? $record['action_provider']:'';

        awp_add_queue_form_submission("awp_{$action_provider}_send_data",$record,$posted_data);
    }
}
