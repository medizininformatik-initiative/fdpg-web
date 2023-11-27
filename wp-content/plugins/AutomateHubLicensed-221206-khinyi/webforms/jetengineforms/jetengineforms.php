<?php

add_filter( 'awp_form_providers', 'awp_jetengineforms_add_provider' );

function awp_jetengineforms_add_provider( $providers ) {


    if ( is_plugin_active( 'jet-engine/jet-engine.php' ) ) {
        $providers['jetengineforms'] = esc_html__( 'JetEngine Forms', 'automate_hub' );
    }

    return $providers;
}

function awp_jetengineforms_get_forms( $form_provider ) {

        if ( $form_provider != 'jetengineforms' ) {
            return;
        }

        global $wpdb;

        $forms = get_posts( array(
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'post_type'      => 'jet-engine-booking',
        ) );

        $forms =  wp_list_pluck( $forms, 'post_title', 'ID' );

       return $forms;
}

function jetengineforms_setup_fields( $form_id ) {

            $raw_fields = '';
            $raw_fields = get_post_meta( $form_id, '_form_data', true );
            $raw_fields = json_decode( wp_unslash( $raw_fields ), true );
        

            if ( empty( $raw_fields ) ) {
                return;
            }

            // Ensure fields sorted by rows
            usort( $raw_fields, function( $a, $b ) {

                if ( $a['y'] == $b['y'] ) {
                    return 0;
                }
                return ( $a['y'] < $b['y'] ) ? -1 : 1;

            } );

            $repeater_index = false;

            foreach ( $raw_fields as $index => $field ) {

                if (( ! empty( $field['settings']['type'] ) && 'repeater_start' === $field['settings']['type'] )) {
                    $repeater_index = $index;
                    continue;
                }

                if ( ( ! empty( $field['settings']['type'] ) && 'repeater_end' === $field['settings']['type'] )  ) {
                    $repeater_index = false;
                    unset( $raw_fields[ $index ] );
                    continue;
                }

                if ( false !== $repeater_index ) {

                    if ( empty( $raw_fields[ $repeater_index ]['settings']['repeater_fields'] ) ) {
                        $raw_fields[ $repeater_index ]['settings']['repeater_fields'] = array();
                    }

                    $raw_fields[ $repeater_index ]['settings']['repeater_fields'][] = $field;
                    unset( $raw_fields[ $index ] );
                }

            }

            $final_fields = array();

            if(!empty($raw_fields)){

                foreach ($raw_fields as $field) {

                    if(!empty($field['settings']['name']) ){
                         $final_fields[$field['settings']['name']] = !empty($field['settings']['label']) ? $field['settings']['label'] : $field['settings']['name'];
                    }
                }
            }
            return $final_fields; 
}


function awp_jetengineforms_get_form_fields( $form_provider, $form_id ) {

    if ( $form_provider != 'jetengineforms' ) {
        return;
    }

    if( !$form_id ) {
        return;
    }

   $fields    = jetengineforms_setup_fields($form_id);
    return $fields;
}

/*
 * Get Form name by form id
 */
function awp_jetengineforms_get_form_name( $form_provider, $form_id ) {

    if ( $form_provider != "jetengineforms" ) {
        return;
    }

    $form      = get_post( $form_id );
    $form_name = $form->post_title;

    return $form_name;
}

add_action( 'jet-engine/forms/handler/after-send', 'awp_jetengineforms_submission', 30, 2 );

function awp_jetengineforms_submission( $obj, $notification ) {

   $form_id                        = $obj->form;
   $posted_data                    = $obj->notifcations->data;
   $posted_data["submission_date"] = date( "Y-m-d" );
    $posted_data["user_ip"]         = awp_get_user_ip();
    //tracking info
    include AWP_INCLUDES.'/tracking_info_cookies.php';


    global $wpdb;
$saved_records = $wpdb->get_results( $wpdb->prepare("SELECT * FROM {$wpdb->prefix}awp_integration WHERE status = 1 AND form_provider = 'jetengineforms' AND form_id =%d ",$form_id), ARRAY_A );
    foreach ( $saved_records as $record ) {
        $action_provider = isset($record['action_provider']) ? $record['action_provider']:'';
        awp_add_queue_form_submission("awp_{$action_provider}_send_data",$record,$posted_data);
       
        if(isset($posted_data['Configurable list'])){
            //contains multiple dynamic values
            $dynamicfieldsarray=$posted_data['Configurable list'];
            if(count($dynamicfieldsarray)){
                foreach($dynamicfieldsarray as $key=>$fields){
                    $posted_data_dynamic=$fields;
                    $final_posted_data_dynamic=array();
                    //remove _copy from keys 
                    foreach ($posted_data_dynamic as $fieldname => $fieldvalue) {
                        if(strpos($fieldname, '_copy')){
                            $temp=str_replace('_copy', '', $fieldname);
                            $final_posted_data_dynamic[$temp]=$fieldvalue;
                        }
                        elseif(strpos($fieldname, ' (add)')){
                            $temp=str_replace(' (add)', '', $fieldname);
                            $final_posted_data_dynamic[$temp]=$fieldvalue;
                        }
                        else{
                            $final_posted_data_dynamic[$fieldname]=$fieldvalue;
                        }
                    }
                    //attaching all other fields that were in the main request
                    foreach ($posted_data as $orgkey => $orgval) {
                        if(!isset($final_posted_data_dynamic[$orgkey]) && $orgkey!='Configurable list'){
                            $final_posted_data_dynamic[$orgkey]=$orgval;
                        }
                    }
                    
                    
                    awp_add_queue_form_submission("awp_{$action_provider}_send_data",$record,$final_posted_data_dynamic);

                    
                }
            }
            

        }
        
    }
}
