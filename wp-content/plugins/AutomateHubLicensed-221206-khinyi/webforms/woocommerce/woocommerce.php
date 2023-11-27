<?php
add_filter( 'awp_form_providers', 'awp_woocommerce_add_provider' );

function awp_woocommerce_add_provider( $providers ) {
    if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {$providers['woocommerce'] = esc_html__( 'WooCommerce', 'automate_hub' );}
    return $providers;
}

function awp_woocommerce_get_forms( $form_provider ) {
    if ( $form_provider != 'woocommerce' ) { return;}
    $triggers = array('1' => esc_html__( 'New order', 'automate_hub'));
    return $triggers;
}

function awp_woocommerce_get_form_fields( $form_provider, $form_id ) {
    if ( $form_provider != 'woocommerce' ) { return; }
    $fields = array();
    if( $form_id == "1" ) { $fields = awp_get_woocommerce_order_fields();}
    return $fields;
}

function awp_get_woocommerce_order_fields() {
    $fields = array(
        // order fields
        "id"                          => esc_html__( "Order ID", "automate_hub" ),
        "parent_id"                   => esc_html__( "Parent ID", "automate_hub" ),
        "user_id"                     => esc_html__( "User ID", "automate_hub" ),
        "billing_first_name"          => esc_html__( "Billing First Name", "automate_hub" ),
        "billing_last_name"           => esc_html__( "Billing Last Name", "automate_hub" ),
        "formatted_billing_full_name" => esc_html__( "Formatted Billing Full Name", "automate_hub" ),
        "billing_company"             => esc_html__( "Billing Company", "automate_hub" ),
        "billing_address_1"           => esc_html__( "Billing Address 1", "automate_hub" ),
        "billing_address_2"           => esc_html__( "Billing Address 2", "automate_hub" ),
        "billing_city"                => esc_html__( "Billing City", "automate_hub" ),
        "billing_state"               => esc_html__( "Billing State", "automate_hub" ),
        "billing_postcode"            => esc_html__( "Billing Postcode", "automate_hub" ),
        "billing_country"             => esc_html__( "Billing Country", "automate_hub" ),
        "billing_email"               => esc_html__( "Billing Email", "automate_hub" ),
        "billing_phone"               => esc_html__( "Billing Phone", "automate_hub" ),
        "formatted_billing_address"   => esc_html__( "Formatted Billing Address", "automate_hub" ),
        "shipping_first_name"         => esc_html__( "Shipping First Name", "automate_hub" ),
        "shipping_last_name"          => esc_html__( "Shipping Last Name", "automate_hub" ),
        "shipping_full_name"          => esc_html__( "Shipping Full Name", "automate_hub" ),
        "shipping_company"            => esc_html__( "Shipping Company", "automate_hub" ),
        "shipping_address_1"          => esc_html__( "Shipping Address 1", "automate_hub" ),
        "shipping_address_2"          => esc_html__( "Shipping Address 2", "automate_hub" ),
        "shipping_city"               => esc_html__( "Shipping City", "automate_hub" ),
        "shipping_state"              => esc_html__( "Shipping State", "automate_hub" ),
        "shipping_postcode"           => esc_html__( "Shipping Postcode", "automate_hub" ),
        "shipping_country"            => esc_html__( "Shipping Country", "automate_hub" ),
        "shipping_email"              => esc_html__( "Shipping Email", "automate_hub" ),
        "shipping_phone"              => esc_html__( "Shipping Phone", "automate_hub" ),
        "formatted_shipping_address"  => esc_html__( "Formatted Shipping Address", "automate_hub" ),
        "shipping_address_map_url"    => esc_html__( "Shipping Address Map URL", "automate_hub" ),
        "payment_method"              => esc_html__( "Payment Method", "automate_hub" ),
        "payment_method_title"        => esc_html__( "Payment Method Title", "automate_hub" ),
        "transaction_id"              => esc_html__( "Transaction ID", "automate_hub" ),
        "created_via"                 => esc_html__( "Order Created Via", "automate_hub" ),
        "date_completed"              => esc_html__( "Date Completed", "automate_hub" ),
        "date_created"                => esc_html__( "Date Created", "automate_hub" ),
        "date_modified"               => esc_html__( "Date Modified", "automate_hub" ),
        "date_paid"                   => esc_html__( "Date Paid", "automate_hub" ),
        "cart_hash"                   => esc_html__( "Cart Hash", "automate_hub" ),
        "currency"                    => esc_html__( "Currency", "automate_hub" ),
        //customer fields
        "customer_id"                 => esc_html__( "Customer ID", "automate_hub" ),
        "customer_ip_address"         => esc_html__( "Customer IP Address", "automate_hub" ),
        "customer_user_agent"         => esc_html__( "Customer User Agent", "automate_hub" ),
        "customer_note"               => esc_html__( "Customer Note", "automate_hub" ),
        //item fields
        "total"                       => esc_html__( "Total", "automate_hub" ),
        "formatted_order_total"       => esc_html__( "Formatted Order Total", "automate_hub" ),
        "order_item_total"            => esc_html__( "Order Item Total", "automate_hub" ),
        "prices_include_tax"          => esc_html__( "Prices Include Tax", "automate_hub" ),
        "discount_total"              => esc_html__( "Discount Total", "automate_hub" ),
        "discount_tax"                => esc_html__( "Discount Tax", "automate_hub" ),
        "shipping_total"              => esc_html__( "Shipping Total", "automate_hub" ),
        "shipping_tax"                => esc_html__( "Shipping Tax", "automate_hub" ),
        "cart_tax"                    => esc_html__( "Cart Tax", "automate_hub" ),
        "total_tax"                   => esc_html__( "Total Tax", "automate_hub" ),
        "total_discount"              => esc_html__( "Total Discount", "automate_hub" ),
        "subtotal"                    => esc_html__( "Subtotal", "automate_hub" ),
        "tax_totals"                  => esc_html__( "Tax Totals", "automate_hub" ),
        "items"                       => esc_html__( "Items Full JSON", "automate_hub" ),
        "items_id"                    => esc_html__( "Item(s) ID", "automate_hub" ),
        "items_name"                  => esc_html__( "Item(s) Name", "automate_hub" ),
        "items_quantity"              => esc_html__( "Item(s) Quantity", "automate_hub" ),
        "items_total"                 => esc_html__( "Item(s) Total", "automate_hub" ),
        "fees"                        => esc_html__( "Fees", "automate_hub" ),
        "taxes"                       => esc_html__( "Taxes", "automate_hub" ),
        "shipping_methods"            => esc_html__( "Shipping Methods", "automate_hub" ),
        "shipping_method"             => esc_html__( "Shipping Method", "automate_hub" ),
    );
    return $fields;
}

function awp_woocommerce_get_form_name( $form_provider, $form_id ) {
    if ( $form_provider != "woocommerce" ) {return; }
    $triggers = array('1' => esc_html__( 'New order', 'automate_hub'));
    if( $form_id ) {return $triggers[$form_id];}
    return false;
}

// Save Stripe Card Details 
add_filter('wc_stripe_generate_payment_request','awp_stripe_get_payment_Request',10,3);
$card_details = '';
function awp_stripe_get_payment_Request($post_data, $order, $prepared_payment_method){
    $order_id =   $order->get_id();
    add_post_meta( $order_id, 'wc_stripe_generate_payment_request', (array)$prepared_payment_method );
    return $post_data;
}

// Save Paytrace Card Details

add_filter('wc_paytrace_transaction_request','sperse_wc_paytrace_transaction_request',10,5);
function sperse_wc_paytrace_transaction_request($request,$order,$amount,$is_subscription,$is_paid_with_profile){
    $order_tokens = new \WC_Paytrace_Order( $order );
    $customer_id  = $order_tokens->get_customer_id();
    $customer_tokens = new \WC_Paytrace_Customer_Tokens( $order->get_user_id() );
    $token           = $customer_tokens->get_token_by_customer_id( $customer_id );
    $last4 = $token->get_last4();
    $year = $token->get_expiry_year();
    $month = $token->get_expiry_month();
    $order_id = $order->get_id();
    add_post_meta( $order_id, 'wc_paytrace_last4', $last4 );
    add_post_meta( $order_id, 'wc_paytrace_exp_month', $month );
    add_post_meta( $order_id, 'wc_paytrace_exp_year', $year );
    return $request;
}  

add_action( 'woocommerce_order_status_changed', 'action_woocommerce_order_status_changed', 10, 3 ); 
add_action( 'woocommerce_subscription_status_updated', 'woocommerce_subscription_status_updated', 10, 3 ); 
add_action( 'woocommerce_subscription_status_cancelled', 'woocommerce_subscription_status_cancelled', 10);
add_action( 'woocommerce_order_refunded', 'woocommerce_order_refunded', 10, 2 );


// New Addition

function action_woocommerce_order_status_changed ( $order_id, $old_status, $new_status ) {

    if( !$order_id ) { return; }
    $order = wc_get_order( $order_id );
    $via   = $order->get_created_via();
    // if( $via != "admin" ) { return; }
    awp_woocommerce_after_submission( $order , $new_status);

}

function woocommerce_subscription_status_updated ( $subscription, $old_status, $new_status ) {

    if( !$subscription ) { return; }
    $order_id = $subscription->get_id();
    $order    = wc_get_order( $order_id );
    $via      = $order->get_created_via();
    // if( $via != "admin" ) { return; }
    awp_woocommerce_after_submission( $order , '',$new_status);

}

function woocommerce_subscription_status_cancelled ( $subscription ) {

    if( !$subscription ) { return; }
    $order_id = $subscription->get_id();
    $order    = wc_get_order( $order_id );
    $via      = $order->get_created_via();
    // if( $via != "admin" ) { return; }
    awp_woocommerce_after_submission( $order , 'cancelled');

}

function woocommerce_order_refunded( $order_id, $refund_id ){

    if( !$order_id ) { return; }
    $order = wc_get_order( $order_id );
    $via   = $order->get_created_via();
    // if( $via != "admin" ) { return; }
    awp_woocommerce_after_submission( $order , 'partiallyOrFullyRefunded','', $refund_id);

}



function awp_woocommerce_after_admin_order( $order_id ) {
    if( !$order_id ) { return; }
    $order = wc_get_order( $order_id );
    $via   = $order->get_created_via();
    if( $via != "admin" ) { return; }
    awp_woocommerce_after_submission( $order );
}

function awp_woocommerce_after_checkout_order( $order_id ) {
    if( !$order_id ) { return; }
    $order = wc_get_order( $order_id );
    $via   = $order->get_created_via();
    if( $via != "checkout" ) { return; }
    awp_woocommerce_after_submission( $order );
}

function awp_woocommerce_after_submission( $order, $orderStatus='', $subscriptionStatus='' , $refundId = null) {
    $trigger_id  = 1;
    $posted_data = array();
    $fields = awp_get_woocommerce_order_fields();
    $field_keys = array_keys( $fields );
    foreach ( $field_keys as $key ) {
        if( method_exists( $order, "get_" . $key ) ) {
            
            $posted_data[$key] = call_user_func( array( $order, "get_" . $key ) );
            if( "items" == $key ) {
                $items_id = $items_name = $items_quantity = $items_total = $items_variation_id =array();
                $items = $order->get_items();
                if( is_array( $items ) ) {
                    foreach ( $items as $item ) {
                        $items_id[]         = $item->get_product_id();
                        $items_name[]       = $item->get_name();
                        $items_quantity[]   = $item->get_quantity();
                        $items_total[]      = $item->get_total();
                        $items_variation_id[] = ($item->get_variation_id()) ? $item->get_variation_id() : 0;
                    }
                    $posted_data['items_id']              = implode( ",", $items_id );
                    $posted_data['items_name']            = implode( ",", $items_name );
                    $posted_data['items_quantity']        = implode( ",", $items_quantity );
                    $posted_data['items_total']           = implode( ",", $items_total );
                    $posted_data['items_variation_id']    = implode( ",", $items_variation_id );
                }
            }
        }
    }
    //tracking info
    include AWP_INCLUDES.'/tracking_info_cookies.php';
    $posted_data['wcOrderPlaced']    = 'wcOrderPlaced';
    $posted_data['orderNewStatus']         = $orderStatus;
    $posted_data['subscriptionStatus']     = $subscriptionStatus;
    $posted_data['refundId'] = $refundId;
    $order_id = $order->get_id(); 
    $card_details= get_post_meta( $order_id, 'wc_stripe_generate_payment_request' );
    $posted_data['cardDetails'] = $card_details;

    global $wpdb;
    $saved_records = $wpdb->get_results($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}awp_integration WHERE status = 1 AND form_provider = 'woocommerce' AND form_id = %d", $trigger_id), ARRAY_A );
    foreach ( $saved_records as $record ) {
        $action_provider = isset($record['action_provider']) ? $record['action_provider']:'';
       
        if( isset($action_provider) && function_exists( "awp_{$action_provider}_send_data" ) ) {
            awp_add_queue_form_submission("awp_{$action_provider}_send_data",$record,$posted_data);
        }
    }


}


