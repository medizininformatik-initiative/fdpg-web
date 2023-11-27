<?php

add_filter( 'awp_action_providers', 'awp_webhookout_actions', 10, 1 );
function awp_webhookout_actions( $actions ) {
    $actions['webhookout'] = array(
        'title' => esc_html__( 'Webhooks: Outbound', 'automate_hub'),
        'tasks' => array('send_to_webhookout'   => esc_html__( 'Send Data To Outbound Webhooks', 'automate_hub'))
    );
    return $actions;
}

add_filter( 'awp_settings_tabs', 'awp_webhookout_settings_tab', 10, 1 );

function awp_webhookout_settings_tab( $providers ) {
    $providers['webhookout'] = 
    array('name'=>esc_html__( 'Webhook Outbound', 'automate_hub'), 'cat'=>array('connector'));
    return $providers;
}

add_action( 'awp_settings_view', 'awp_webhookout_settings_view', 10, 1 );

function awp_webhookout_settings_view( $current_tab ) {
    if( $current_tab != 'webhookout' ) {return; } ?>
    <div class="platformheader">
    <a href="https://sperse.io/go/webhookout" target="_blank"><img src="<?php echo AWP_ASSETS; ?>/images/logos/webhookout.png" width="170" height="50" alt="Outbound Webhook Logo"></a><br/><br/>
    <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?><br/>    
    
<?php
}

add_action( 'awp_add_js_fields', 'awp_webhookout_js_fields', 10, 1 );

function awp_webhookout_js_fields( $field_data ) {}
add_action( 'awp_action_fields', 'awp_webhookout_action_fields' );

function awp_webhookout_action_fields() {
?>
    <script type="text/template" id="webhookout-action-template">
        <table class="form-table">
                <tr valign="top" v-if="action.task == 'send_to_webhookout'">
                    <th scope="row"><?php esc_html_e( 'Webhookout Fields', 'automate_hub' ); ?></th>
                    <td scope="row"></td>
                </tr>
                <tr class="alternate" v-if="action.task == 'send_to_webhookout'">
                    <td><label for="tablecell"><?php esc_html_e( 'Webhookout URL', 'automate_hub' ); ?></label></td>
                    <td>
                    <div class="form-table__input-wrap">
                    <input type="text" class="basic-text" v-model="fielddata.webhookoutUrl" name="webhookout_url" id="webhookout_url" placeholder="<?php esc_html_e( 'Enter URL here', 'automate_hub'); ?>" required="required">
                    <span class="spci_btn" data-clipboard-action="copy" data-clipboard-target="#webhookout_url"><img src="<?php echo AWP_ASSETS; ?>/images/copy.png" alt="Copy to clipboard"></span>                         </div>                                                                                                                                                                                                      
                    </td>
                </tr>
            </table>
    </script>
    <?php
}

/* Saves connection mapping */
function awp_webhookout_save_integration() {
    $params = array();
    parse_str( awp_sanitize_text_or_array_field( $_POST['formData'] ), $params );
    $trigger_data = isset( $_POST["triggerData"] ) ? awp_sanitize_text_or_array_field( $_POST["triggerData"] ) : array();
    $action_data  = isset( $_POST["actionData"] ) ? awp_sanitize_text_or_array_field( $_POST["actionData"] ) : array();
    $field_data   = isset( $_POST["fieldData"] ) ? awp_sanitize_text_or_array_field( $_POST["fieldData"] ) : array();
    $integration_title = isset( $trigger_data["integrationTitle"] ) ? $trigger_data["integrationTitle"] : "";
    $form_provider_id  = isset( $trigger_data["formProviderId"] ) ? $trigger_data["formProviderId"] : "";
    $form_id           = isset( $trigger_data["formId"] ) ? $trigger_data["formId"] : "";
    $form_name         = isset( $trigger_data["formName"] ) ? $trigger_data["formName"] : "";
    $action_provider   = isset( $action_data["actionProviderId"] ) ? $action_data["actionProviderId"] : "";
    $task              = isset( $action_data["task"] ) ? $action_data["task"] : "";
    $type              = isset( $params["type"] ) ? $params["type"] : "";
    $all_data = array(
        'trigger_data' => $trigger_data,
        'action_data'  => $action_data,
        'field_data'   => $field_data
    );
    global $wpdb;
    $integration_table = $wpdb->prefix . 'awp_integration';
    if ( $type == 'new_integration' ) {
        $result = $wpdb->insert(
            $integration_table,
            array(
                'title'           => $integration_title,
                'form_provider'   => $form_provider_id,
                'form_id'         => $form_id,
                'form_name'       => $form_name,
                'action_provider' => $action_provider,
                'task'            => $task,
                'data'            => json_encode( $all_data, true ),
                'status'          => 1
            )
        );
        if($result){
            $platform_obj= new AWP_Platform_Shell_Table($action_provider);
            $platform_obj->awp_add_new_spot($wpdb->insert_id,$field_data['activePlatformId']);
        }
    }
    if ( $type == 'update_integration' ) {
        $id = esc_sql( trim( $params['edit_id'] ) );
        if ( $type != 'update_integration' &&  !empty( $id ) ) { exit; }
        $result = $wpdb->update( $integration_table,
            array(
                'title'           => $integration_title,
                'form_provider'   => $form_provider_id,
                'form_id'         => $form_id,
                'form_name'       => $form_name,
                'data'            => json_encode( $all_data, true ),
            ),
            array('id' => $id)
        );
    }

    $return=array();
    $return['type']=$type;
    $return['result']=$result;
    $return['insertid']=$wpdb->insert_id;
    return $return;
}

/* Handles sending data to webhookout API */
function awp_webhookout_send_data( $record, $posted_data ) {

    $data    = json_decode( $record["data"], true );
    $webhookout_url = $data['field_data']["webhookoutUrl"];
    $parts = parse_url($webhookout_url);
    if(!isset($parts['query'])){
        return json_encode(["error"=>"Invalid Outbound URL"]);
    }
    parse_str($parts['query'], $query);
    $apiKey = $query['apikey'];

    $user_pass = '';
    if(!empty($posted_data['billing_first_name']) && !empty($posted_data['billing_last_name']) ){
     $user_pass = trim($posted_data['billing_first_name']).trim($posted_data['billing_last_name']);
    }else{

        if(!empty($posted_data['billing_first_name'])){
            $user_pass = trim($posted_data['billing_first_name']);
        }
    }

        if(empty($user_pass)){

            $user_pass = $posted_data['billing_email'];
        }



    $dataArr = array(
        'full_name' => $posted_data['formatted_billing_full_name'],
        'first_name' => $posted_data['billing_first_name'],
        'last_name'  => $posted_data['billing_last_name'],
        'email'       => $posted_data['billing_email'],
        'street_address' => $posted_data['formatted_billing_address'],
        'city' => $posted_data['billing_city'],
        'zipcode' => $posted_data['billing_postcode'],
        'state_code' => $posted_data['billing_state'],
        'country_code' => $posted_data['billing_country'],
        'phone' => $posted_data['billing_phone'],
        'order_id' => $posted_data['id'],
        'parent_id' => $posted_data['parent_id'],
        'user_id' => $posted_data['user_id'],
        'password'=> $user_pass,
        'billing_first_name' => $posted_data['billing_first_name'],
        'billing_last_name' => $posted_data['billing_last_name'],
        'billing_full_name' => $posted_data['formatted_billing_full_name'],
        'billing_company' => $posted_data['billing_company'],
        'billing_address1' => $posted_data['billing_address_1'],
        'billing_address2' =>$posted_data['billing_address_2'],
        'billing_city'=>  $posted_data['billing_city'],
        'billing_state'=>$posted_data['billing_state'],
        'billing_postcode'=> $posted_data['billing_postcode'],
        'billing_country'=>$posted_data['billing_country'],
        'billing_email'=>$posted_data['billing_email'],
        'billing_phone'=>$posted_data['billing_phone'],
        'payment_method'=>$posted_data['payment_method'],
        'payment_method_title'=>$posted_data['payment_method_title'], 
        'transaction_id'=>$posted_data['transaction_id'],
        'Order_Created_Via'=>$posted_data['created_via'],
        'date_completed'=>$posted_data['date_completed'],
        'date_created'=>$posted_data['date_created']->date,
        'date_paid'=>$posted_data['date_paid']->date,
        'cart_hash'=>$posted_data['cart_hash'],
        'currency'=>$posted_data['currency'],
        'customer_id'=>$posted_data['customer_id'],
        'customer_ip_address'=>$posted_data['customer_ip_address'],
        'customer_user_agent'=>$posted_data['customer_user_agent'],
        'customer_note'=>$posted_data['customer_note'],
        'total'=>$posted_data['total'],
        'formatted_order_total'=>$posted_data['formatted_order_total'],
        'order_item_total'=>$posted_data['items_total'],
        'prices_include_tax'=>$posted_data['prices_include_tax'],
        'discount_total'=>$posted_data['discount_total'],
        'discount_tax'=>$posted_data['discount_tax'],
        'shipping_total'=>$posted_data['shipping_total'],
        'shipping_tax'=>$posted_data['shipping_tax'],
        'cart_tax'=>$posted_data['cart_tax'],
        'total_tax'=>$posted_data['total_tax'],
        'total_discount'=>$posted_data['total_discount'],
        'subtotal'=>$posted_data['subtotal'],
        'items_id'=>$posted_data['items_id'],
        'items_name'=>$posted_data['items_name'],
        'items_quantity'=>$posted_data['items_quantity'],
        'items_total'=>$posted_data['items_total'],
        'is_create_user'=>true
    );

    
    $data    = $data["field_data"];
    $task    = $record["task"];
        $record_data = json_decode( $record["data"], true );
    if( array_key_exists( "cl", $record_data["action_data"]) ) {
        if( $record_data["action_data"]["cl"]["active"] == "yes" ) {
            $chk = awp_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data );
            if( !awp_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data ) ) {
                return;
            }
        }
    }
    if( $task == "send_to_webhookout" ) {
        // $webhookout_url =$data['field_data']["webhookoutUrl"];
        $webhookout_url = ($data['field_data']["webhookoutUrl"]) ? $data['field_data']["webhookoutUrl"] : $data['webhookoutUrl'];
        if( !$webhookout_url ) { return; }
//         $args = array(
//             'headers' => array('Content-Type' => 'application/json',),
//             'body' => json_encode( $posted_data));
//         $return = wp_remote_post( $webhookout_url, $args );
//         
        $query = http_build_query($dataArr);
        $url = $webhookout_url . '&' . $query;
        $args = [
          'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
          'method'  => 'POST',
        ];

        // Post the request.
        $return = wp_remote_request($url, $args);
        $args['body']=$dataArr;
//      $url = urldecode($url);
        awp_add_to_log( $return, $webhookout_url, $args, $record );


    }
    return $return;
}

/* Handles sending data to webhookout API */
function awp_webhookout_resend_data( $log_id,$data,$integration ) {

    $task=$integration['task'];
    $data=stripslashes($data);
    $data=preg_replace('/\s+/', '',$data); 
    $data=json_decode($data,true);
    $url=$data['url'];
    if(!$url){
        $response['success']=false;
        $response['msg']="Syntax Error! Request is invalid";
        return $response;
    }

    if( $task == "send_to_webhookout" ) {
        
        
        $args = array(
            'headers' => array('Content-Type' => 'application/json',),
            'body' => json_encode( $data['args']['body'])
        );
        $return = wp_remote_post( $url, $args );



        $args['body']=$data['args']['body'];
        awp_add_to_log( $return, $url, $args, $integration );

    }
    $response['success']=true;
    return $response;
}
