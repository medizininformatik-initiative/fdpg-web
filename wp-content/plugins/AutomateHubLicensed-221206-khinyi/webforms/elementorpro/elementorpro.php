<?php


class ElementorProForms 
{

    public $eform = array();
    
function awp_elementorpro_find_element_recursive( $elements, $post_id ) {
    foreach ( $elements as $element ) {
        if( isset( $element["widgetType"] ) ) {
            if ( "form" === $element["widgetType"] ) {
                $element["post_id"] = $post_id;
                $this->eform[] = $element;
                //return $element;
            }
        }

        if ( ! empty( $element['elements'] ) ) {
            $element = $this->awp_elementorpro_find_element_recursive( $element['elements'], $post_id );

            if ( $element ) {
                 $this->eform[] = $element;
                //return $element;
            }
        }
    }

    return false;
}





}

add_filter( 'awp_form_providers', 'awp_elementorpro_add_provider' );

function awp_elementorpro_add_provider( $providers ) {

    if ( is_plugin_active( 'elementor-pro/elementor-pro.php' ) ) {
        $providers['elementorpro'] = esc_html__( 'Elementor Pro Form', 'automate_hub' );
    }

    return $providers;
}

function awp_elementorpro_get_forms( $form_provider ) {

    if ( $form_provider != 'elementorpro' ) {
        return;
    }

    global $wpdb;

    $result     = $wpdb->get_results( $wpdb->prepare("SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key = '_elementor_data' AND  meta_value LIKE '%%s%'",'form_name'), ARRAY_A);

    $only_forms = array();
    $obj_fornorm = new ElementorProForms();


    foreach( $result as $single_post ) {
        if( wp_is_post_revision( $single_post["post_id"] ) ) {
            continue;
        }

        $elementor_data = json_decode( $single_post["meta_value"], true );
         $obj_fornorm->awp_elementorpro_find_element_recursive( $elementor_data, $single_post["post_id"] );

       $all_forms   = $obj_fornorm->eform;

        if(count($all_forms)>0){

            foreach ($all_forms as $singleform) {
                # code...
                $only_forms[] = $singleform;
            }

        }

    }

    $form_list = array();

    if( $only_forms ) {
        foreach( $only_forms as $single ) {
            $form_list[$single["post_id"] . '_' . $single["id"]] = $single["post_id"] . " " . $single["settings"]["form_name"];
        }
    }

    return $form_list;
}

function awp_elementorpro_find_element_recursive( $elements, $post_id ) {
    foreach ( $elements as $element ) {
        if( isset( $element["widgetType"] ) ) {
            if ( "form" === $element["widgetType"] ) {
                $element["post_id"] = $post_id;

                return $element;
            }
        }

        if ( ! empty( $element['elements'] ) ) {
            $element = awp_elementorpro_find_element_recursive( $element['elements'], $post_id );

            if ( $element ) {
                return $element;
            }
        }
    }

    return false;
}

function awp_elementorpro_get_form_fields( $form_provider, $form_id ) {

    if ( $form_provider != 'elementorpro' ) {
        return;
    }

    $ids          = explode( "_", $form_id );
    $elementor    = \ElementorPro\Plugin::elementor();
    $meta         = $elementor->documents->get( $ids[0] )->get_elements_data();
    
    $form         = \ElementorPro\Modules\Forms\Module::find_element_recursive( $meta, $ids[1] );
    $form_fields  = isset($form["settings"]["form_fields"]) ? $form["settings"]["form_fields"] :'' ;    
    $fields       = wp_list_pluck( $form_fields, "field_label", "custom_id" );

    $special_tags = awp_get_special_tags();
    if(!empty($fields) && is_array($fields)){
        foreach($fields as $key=>$field){
            if(empty($field)){
                $fields[$key]=$key;
            }
        }
    }

    // $user_ip = array(
    //     'user_ip'        => esc_html__( 'User IP'       , 'automate_hub' ),

    // );

    // echo "<pre>";print_r($ids);echo "</pre>";
    // echo "<pre>";print_r($meta);echo "</pre>";
    // echo "<pre>";print_r($form);echo "</pre>";
    // echo "<pre>";print_r($form_fields);echo "</pre>";
     //echo "<pre>";print_r($fields);echo "</pre>";
    // echo "<pre>";print_r($special_tags);echo "</pre>";
    
    if( is_array( $fields ) && is_array( $special_tags ) ) {
        $fields = $fields + $special_tags;
    }

    return $fields;
}

function awp_elementorpro_get_form_name( $form_provider, $form_id ) {

    if ( $form_provider != "elementorpro" ) {
        return;
    }

    $ids = explode( "_", $form_id );

    $elementor = \ElementorPro\Plugin::elementor();
    $meta      = $elementor->db->get_plain_editor( $ids[0] );
    $form      = \ElementorPro\Modules\Forms\Module::find_element_recursive( $meta, $ids[1] );

    return $form["settings"]["form_name"];
}

add_action( 'elementor_pro/forms/new_record', 'awp_elementorpro_submission', 10, 10 );

function awp_elementorpro_submission( $record, $form ) {
    if ( !isset($_POST['post_id'] ) || !isset( $_POST['form_id'] ) ) {
        return;
    }

    $post_id = isset( $_POST['post_id'] ) ? sanitize_text_field( $_POST['post_id'] ) : "";
    $form_id = isset( $_POST['form_id'] ) ? sanitize_text_field( $_POST['form_id'] ) : "";

    $elementor = \ElementorPro\Plugin::elementor();
    $meta      = $elementor->documents->get( $post_id )->get_elements_data();
    $form      = \ElementorPro\Modules\Forms\Module::find_element_recursive( $meta, $form_id );

    $posted_data = array();

    if(!empty($_POST['form_fields'])){

        foreach( $_POST['form_fields'] as $key => $value ) {
            $posted_data[$key] = !empty($value) ? awp_sanitize_text_or_array_field( $value ) : '';
        }
    }

    $post               = get_post( $post_id, 'OBJECT' );
    // $special_tag_values = awp_get_special_tags_values( $post );

    // if( is_array( $posted_data ) && is_array( $special_tag_values ) ) {
    //     $posted_data = $posted_data + $special_tag_values;
    // }

    $posted_data["submission_date"] = date( "Y-m-d H:i:s" );
    $posted_data["user_ip"]         = awp_get_user_ip();
    $posted_data['form_id']         = $form_id;
    $posted_data['post_id']         = $post_id;
    //tracking info
    include AWP_INCLUDES.'/tracking_info_cookies.php';


    global $wpdb;

    $saved_records = $wpdb->get_results($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}awp_integration WHERE status = 1 AND form_provider = 'elementorpro' AND form_id =%d",$post_id . "_" . $form_id ), ARRAY_A );

    foreach ( $saved_records as $record ) {
        $action_provider = isset($record['action_provider']) ? $record['action_provider']:'';
        awp_add_queue_form_submission("awp_{$action_provider}_send_data",$record,$posted_data);
    }
}