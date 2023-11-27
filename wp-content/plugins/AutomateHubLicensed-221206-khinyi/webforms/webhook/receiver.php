<?php

add_filter( 'awp_form_providers', 'awp_webhooksinbound_add_provider' );

function awp_webhooksinbound_add_provider( $providers ) {

    $providers['webhooksinbound'] = __( 'Webhook Inbound Receiver', 'automate_hub' );

    return $providers;
}

function awp_webhooksinbound_get_forms( $form_provider ) {

    if ( $form_provider != 'webhooksinbound' ) {
        return;
    }
    $platform_obj= new AWP_Platform_Shell_Table('webhookin');
    $result=$platform_obj->fetch_active_platform();
    $triggers=array();
    if(count($result)){
        foreach ($result as $key => $value) {
            $triggers[$value['api_key']]=$value['account_name'];
        }
    }

    return $triggers;
}

function awp_webhooksinbound_get_form_fields( $form_provider, $form_id ) {

    if ( $form_provider != 'webhooksinbound' ) {
        return;
    }

    $rand         = md5( uniqid( rand(), true ) );
    $special_tags = awp_get_special_tags();


    return $special_tags;
}

add_action( "rest_api_init", "awp_webooksinbound_create_webhook_route" );

function awp_webooksinbound_create_webhook_route() {
    register_rest_route( 'automatehub', '/reciever/(?P<apikey>[a-zA-Z0-9-]+)', array(
        array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => 'awp_webhooksinbound_get_webhook_data',
            'permission_callback' => '__return_true'
        ),
    ) );
}

function awp_webhooksinbound_normalize_array( $input_array ) {
 
    foreach( $input_array as $key1 => &$value1 ) {
        if( is_array( $value1 ) ) {
            foreach( $value1 as $key2 => &$value2 ) {
                $input_array[$key1 . '__' . $key2] = $value2;
            }
        }
    }
    
    return $input_array;
}

function executeIntegration($params,$webhookid){
    global $wpdb, $post;
    $query=$wpdb->prepare('SELECT * FROM {$wpdb->prefix}awp_integration WHERE status = 1 AND form_provider = "webhooksinbound" AND form_id = %d',$webhookid);
    $saved_records=$wpdb->get_results( $query, ARRAY_A );
    if( count( $saved_records ) < 1 ) {
        return json_encode(["success"=>false,"message"=>"Invalid Webhook ID/No Active Integration found for default webhook"]);
    }
    
    $posted_data  = array();
    $posted_data = awp_webhooksinbound_normalize_array( $params );

    foreach( $posted_data as $key => $value ) {
        if( is_array( $value ) ) {
            unset( $posted_data[$key] );
        }
    }
    $special_tag_values = awp_get_special_tags_values( $post );

    if( is_array( $posted_data ) && is_array( $special_tag_values ) ) {
        $posted_data = $posted_data + $special_tag_values;
    }
    //tracking info
    include AWP_INCLUDES.'/tracking_info_cookies.php';
    foreach ( $saved_records as $record ) {
        $action_provider = $record['action_provider'];

        $resp[]=call_user_func( "awp_{$action_provider}_send_data", $record, $posted_data );
    }

    return $resp;

}

function awp_webhooksinbound_get_webhook_data( $request ) {
    global $wpdb, $post;

    $platform_obj= new AWP_Platform_Shell_Table('webhookin');
    $result=$platform_obj->fetch_active_platform();
    $activeurls=array();
    foreach ($result as $key => $value) {
        $activeurls[]=$value['api_key'];
    }

    $params        = $request->get_params();
    if(isset($params['apikey'])){
        $api=$params['apikey'];
        $api_key=$api;
        $webhookid="001";

        $lastpos=strrpos($api, '-');
        if($lastpos){

            if(is_numeric(substr($api, $lastpos+1))){
                $api_key=substr($api,0,$lastpos);
                $webhookid=substr($api, $lastpos+1);
            }            
        }
        

        if($api_key==get_option('awp_webhook_api_key')){
            if(in_array($webhookid, $activeurls)){
                return executeIntegration($params,$webhookid);
            }
            else{
                return json_encode(["success"=>false,"message"=>"URL Temporarily Blocked"]);
            }
            
        }
        else{
            return json_encode(["success"=>false,"message"=>"Invalid Api Key"]);
        }

        
    }
    else{
        return json_encode(["success"=>false,"message"=>"Authorization required"]);
    }
    
}
