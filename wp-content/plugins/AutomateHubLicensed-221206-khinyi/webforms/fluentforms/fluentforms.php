<?php

add_filter( 'awp_form_providers', 'awp_fluentforms_add_provider' );

function awp_fluentforms_add_provider( $providers ) {

    if ( is_plugin_active( 'fluentform/fluentform.php' ) ) {
        $providers['fluentforms'] = __( 'WP Fluent Forms', 'automate_hub' );
    }

    return $providers;
}

function awp_fluentforms_get_forms( $form_provider ) {

    if ( $form_provider != 'fluentforms' ) {
        return;
    }

    global $wpdb;

    $query  = "SELECT id, title FROM {$wpdb->prefix}fluentform_forms";
    $result = $wpdb->get_results( $query, ARRAY_A );
    $forms  = wp_list_pluck( $result, 'title', 'id' );

    return $forms;
}

function awp_fluentforms_get_slingle_field( $single_field ) {
    $fields = array();
    $field_list = array(
        'input_email',
        'input_text',
        'textarea',
        'select_country',
        'input_number',
        'select',
        'input_radio',
        'input_checkbox',
        'input_url',
        'input_date',
        'input_image',
        'input_file',
        'phone'
    );

    if( 'address' == $single_field->element ) {
        $fields[$single_field->attributes->name . '_address_line_1'] = $single_field->settings->label . ' Address Line 1';
        $fields[$single_field->attributes->name . '_address_line_2'] = $single_field->settings->label . ' Address Line 2';
        $fields[$single_field->attributes->name . '_city'] = $single_field->settings->label . ' City';
        $fields[$single_field->attributes->name . '_state'] = $single_field->settings->label . ' State';
        $fields[$single_field->attributes->name . '_zip'] = $single_field->settings->label . ' Zip';
        $fields[$single_field->attributes->name . '_country'] = $single_field->settings->label . ' Country';
    }

    if( 'input_name' == $single_field->element ) {
        $fields['first_name'] = 'First Name';
        $fields['middle_name'] = 'Middle Name';
        $fields['last_name'] = 'Last Name';
    }

    if( in_array( $single_field->element, $field_list ) ) {
        $fields[$single_field->attributes->name] = $single_field->settings->label;
    }

    return $fields;
}

function awp_fluentforms_get_form_fields( $form_provider, $form_id ) {

    if ( $form_provider != 'fluentforms' ) {
        return;
    }

    global $wpdb;

    $query  = $wpdb->prepare("SELECT form_fields FROM {$wpdb->prefix}fluentform_forms WHERE id = %d",$form_id);
    $result = $wpdb->get_var( $query );
    $data   = json_decode( $result );
    $fields = array();

    foreach( $data->fields as $single_field ) {

        if( 'container' ==  $single_field->element ) {
            foreach( $single_field->columns as $single_column ) {
                foreach( $single_column->fields as $single_column_field ) {
                    $single_field_value = awp_fluentforms_get_slingle_field( $single_column_field );
                    $fields = $fields + $single_field_value;
                }
            }
            continue;
        }

        $single_field_value = awp_fluentforms_get_slingle_field( $single_field );
        $fields = $fields + $single_field_value;
    }

    return $fields;
}

function awp_fluentforms_transform_form_fields( $fields ) {
    $data = [];

    foreach ( $fields['fields'] as $field ) {
        if ( ! array_key_exists( 'name', $field['attributes'] ) ) {
            continue;
        }

        if ( awp_fluentforms_has_sub_fields( $field ) ) {
            $data = array_merge( $data, awp_fluentforms_get_sub_fields( $field ) );
            continue;
        }

        $data[] = [
            'id'    => $field['attributes']['name'],
            'label' => awp_fluentforms_get_label( $field['attributes']['name'] ),
        ];
    }

    return $data;
}

function awp_fluentforms_has_sub_fields( $field ) {
    return array_key_exists( 'fields', $field );
}

function awp_fluentforms_get_sub_fields( $field ) {
    $data = [];

    foreach ( $field['fields'] as $sub_field ) {
        if ( ! array_key_exists( 'name', $sub_field['attributes'] ) ) {
            continue;
        }

        $data[] = [
            'id' => $sub_field['attributes']['name'],
            'label' => awp_fluentforms_get_label( $sub_field['attributes']['name'] ),
        ];
    }

    return $data;
}

function awp_fluentforms_get_label( $label ) {
    return ucwords( str_replace( [ '-', '_' ], [ ' ', ' ' ], $label ) );
}

function awp_fluentforms_get_form_name( $form_provider, $form_id ) {

    if ( $form_provider != "fluentforms" ) {
        return;
    }

    $form = wpFluent()->table( 'fluentform_forms' )
    ->select( 'title' )
    ->where( 'id', $form_id )
    ->first();

    return $form->title;
}

add_action( "fluentform_before_insert_submission", "awp_fluentforms_submission", 99, 1 );

function awp_fluentforms_submission( $data ) {

    $form_id = isset($data['form_id']) ? $data['form_id'] :'' ;

    global $wpdb, $post;

    $saved_records = $wpdb->get_results($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}awp_integration WHERE status = 1 AND form_provider = 'fluentforms' AND form_id = %d",$form_id), ARRAY_A );

    if( empty( $saved_records ) ) {
        return;
    }

    $posted_data = array();

    if( isset( $data['response'] ) ) {
        $posted_data = json_decode( $data['response'], true );

        foreach( $posted_data as $key => $single ) {
            if( is_array( $single ) ) {
                if( substr( $key, 0, 8 ) == 'address_' ) {
                    foreach( $single as $key2 => $add_field ) {
                        $posted_data[$key . '_' . $key2] = $add_field;
                    }
                    continue;
                }

                if( 'names' == $key ) {
                    $posted_data = $posted_data + $single;
                }
                
            }
        }
    }

    $special_tag_values = awp_get_special_tags_values( $post );

    if( is_array( $special_tag_values ) ) {
        $posted_data = array_merge( $posted_data, $special_tag_values );
    }

    //tracking info
    include AWP_INCLUDES.'/tracking_info_cookies.php';

    foreach ( $saved_records as $record ) {
        $action_provider = isset($record['action_provider']) ? $record['action_provider']:'';
        awp_add_queue_form_submission("awp_{$action_provider}_send_data",$record,$posted_data);
    }

    return;
}
