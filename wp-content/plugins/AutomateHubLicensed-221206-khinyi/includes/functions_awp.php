<?php

/* Redirect */
function AWP_redirect( $url ) {
    ?>
        <script type="text/javascript">
        window.location = "<?php echo esc_url_raw($url); ?>";
        </script>
    <?php 
    
}


function awp_get_active_integrations_count(){
    global $wpdb;
    $query = "Select * from {$wpdb->prefix}awp_integration";
    $result=$wpdb->get_results( $query, ARRAY_A );
    return count($result);
}

/* Get form providers */
function awp_get_form_providers() {
    include_once ABSPATH . 'wp-admin/includes/plugin.php';
    $providers = array();
    if ( is_plugin_active( 'contact-form-7/wp-contact-form-8.php' ) ) {
        $providers['contactform8'] = __( 'Contact Form 8', 'automate_hub' );
    }
    return apply_filters( 'awp_form_providers', $providers );
}

function getUserIpAddrForSperse(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        $ip = sanitize_text_field($_SERVER['HTTP_CLIENT_IP']);                //ip from share internet
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $ip = sanitize_text_field($_SERVER['HTTP_X_FORWARDED_FOR']);          //ip pass from proxy
        if(!empty($ip)){
            $ip=explode(',', $ip);
            $ip=$ip[0];
            if(!substr_count($ip,':') > 3){
                $ip= isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : '';
            }
            else if(!substr_count($ip,'.') > 3){
                $ip= isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : '';
            }
        }
    }else{
        $ip = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : '';
    }
    return $ip;
}
/* Get User IP */
function awp_get_user_ip() {

   $ip = getUserIpAddrForSperse();
   return $ip;
}

/* Get actions */
function awp_get_actions() {
    $actions = array();
    return apply_filters( 'awp_action_providers', $actions );
}

/* Get action providers */
function awp_get_action_providers() {
    $actions   = awp_get_actions();
    $providers = array();
    foreach( $actions as $key => $value ) {
        $providers[$key] = isset($value['title']) ? $value['title'] :'';
    }
    return $providers;
}

/* Get action tasks */
function awp_get_action_tasks( $provider = "" ) {
    $actions = awp_get_actions();
    $tasks   = array();
    if( $provider ) {
        foreach( $actions as $key => $value ) {
            if( $provider == $key ) {
                $tasks = isset($value['tasks']) ? $value['tasks']:'' ;
            }
        }
    }
    return $tasks;
}

function awp_get_platform_connections(){
    $connections = array();
    return apply_filters( 'awp_platform_connections', $connections );
}
function awp_is_platform_connected($provider = ""){
    $connections = awp_get_platform_connections();
    $connected   = false;
    if( $provider ) {
        foreach( $connections as $key => $value ) {
            if( $provider == $key ) {
                $connected = isset($value['isConnected']) ? $value['isConnected'] :'' ;
            }
        }
    }
    return $connected;
}

function usage_error($head='',$msg=''){

    $response['success']=false;
    $response['head']=$head;
    $response['msg']=$msg;
    $response=$response;
    return $response;
   
}

function error_messages(){
    $temp['integration']=array('Integration Limit Reached','You have reached the active integration limit.  Please upgrade your license to access more integrations at a time.');
    $temp['forms']=array('Forms Limit Reached','You have reached the active forms limit.  Please upgrade your license to access more forms at a time.');
    $temp['platforms']=array('Apps Limit Reached','You have reached the active Apps limit.  Please upgrade your license to add more Apps at a time.');
    $temp['accounts']=array('Account Limit Reached','You have reached the App accounts limit.  Please upgrade your license to add more accounts.');
    $temp['logs']=array('Logs Limit Reached','You have reached monthly log limit.  Please upgrade your license');
    $temp['FS']=array('Form Sources Limit Reached','You have reached Form Sources Limit.  Please upgrade your license to add more forms');
    return $temp;
}

function awp_usage_controller($type,$action_provider_id,$action){
    
    $key=get_option('sperse_license_key');
    $temp=version_type($key);
    $status=plugin_status();
    $response['success']=true;
    $accounts_created=$status['Y'];

  
    

    switch ($type) {
        case 'A':
            if($key){
                if($temp == 3){
                    //basic version
                    if($status['W'] >= 20 && in_array("W", $action) ){
                            $msg=error_messages()['integration'];
                            $response=usage_error($msg[0],$msg[1]);
                            $response['error_code']='W';
                           
                    }
                    if($accounts_created >= 10 && in_array("X", $action)){
                            $msg=error_messages()['accounts'];
                            $response= usage_error($msg[0],$msg[1]);
                            $response['error_code']='X';
                            
                    }
                    if($status['Y'] >= 10 && in_array("Y", $action)){
                            $msg=error_messages()['platforms'];
                            $response= usage_error($msg[0],$msg[1]);
                            $response['error_code']='Y';
                            
                    }
                    if($status['Z'] >= 10000 && in_array("Z", $action)){
                            $msg=error_messages()['logs'];
                            $response= usage_error($msg[0],$msg[1]);
                            $response['error_code']='Z';
                            
                    }
                    
                }
                elseif($temp == 5){
                    if($status['W'] <= -1 && in_array("W", $action)){
                            $msg=error_messages()['integration'];
                            $response= usage_error($msg[0],$msg[1]);
                            $response['error_code']='W';
                            
                    }
                    if($accounts_created >= 1 && in_array("X", $action)){
                            $msg=error_messages()['accounts'];
                            $response= usage_error($msg[0],$msg[1]);
                            $response['error_code']='X';
                            
                    }
                    if($status['Y'] <= -1 && in_array("Y", $action)){
                            $msg=error_messages()['platforms'];
                            $response= usage_error($msg[0],$msg[1]);
                            $response['error_code']='Y';
                            
                    }
                    if($status['Z'] >= 10000 && in_array("Z", $action)){
                            $msg=error_messages()['logs'];
                            $response= usage_error($msg[0],$msg[1]);
                            $response['error_code']='Z';
                            
                    }
                }
                elseif($temp == 6){
                    //premium
                    if($status['W'] >= 100 && in_array("W", $action)){
                            $msg=error_messages()['integration'];
                            $response= usage_error($msg[0],$msg[1]);
                            $response['error_code']='W';
                            
                    }
                    if($accounts_created >= 50 && in_array("X", $action)){
                            $msg=error_messages()['accounts'];
                            $response= usage_error($msg[0],$msg[1]);
                            $response['error_code']='X';
                            
                    }
                    if($status['Y'] >= 50 && in_array("Y", $action)){
                            $msg=error_messages()['platforms'];
                            $response= usage_error($msg[0],$msg[1]);
                            $response['error_code']='Y';
                            
                    }
                    if($status['Z'] >= 100000 && in_array("Z", $action)){
                            $msg=error_messages()['logs'];
                            $response= usage_error($msg[0],$msg[1]);
                            $response['error_code']='Z';
                            
                    }
                }
                elseif($temp == 9){
                

                }
                elseif($temp == 10){
                    //premium
                    if($status['W'] >= 5 && in_array("W", $action)){
                            $msg=error_messages()['integration'];
                            $response= usage_error($msg[0],$msg[1]);
                            $response['error_code']='W';
                            
                    }
                    if($accounts_created >= 5 && in_array("Y", $action)){
                            $msg=error_messages()['accounts'];
                            $response= usage_error($msg[0],$msg[1]);
                            $response['error_code']='Y';
                            
                    }
                    if($status['FS'] >= 5 && in_array("FS", $action)){
                            $msg=error_messages()['FS'];
                            $response= usage_error($msg[0],$msg[1]);
                            $response['error_code']='FS';
                            
                    }
                    if($status['Z'] >= 10000 && in_array("Z", $action)){
                            $msg=error_messages()['logs'];
                            $response= usage_error($msg[0],$msg[1]);
                            $response['error_code']='Z';
                            
                    }
                }
                elseif($temp == 11){
                    //premium
                    if($status['W'] >= 25 && in_array("W", $action)){
                            $msg=error_messages()['integration'];
                            $response= usage_error($msg[0],$msg[1]);
                            $response['error_code']='W';
                            
                    }
                    if($accounts_created >= 10000 && in_array("Y", $action)){
                            $msg=error_messages()['accounts'];
                            $response= usage_error($msg[0],$msg[1]);
                            $response['error_code']='Y';
                            
                    }
                    if($status['FS'] >= 10000 && in_array("FS", $action)){
                            $msg=error_messages()['FS'];
                            $response= usage_error($msg[0],$msg[1]);
                            $response['error_code']='Y';
                            
                    }
                    if($status['Z'] >= 25000 && in_array("Z", $action)){
                            $msg=error_messages()['logs'];
                            $response= usage_error($msg[0],$msg[1]);
                            $response['error_code']='Z';
                            
                    }
                }
                elseif($temp == 12){
                    //premium
                    if($status['W'] >= 100000 && in_array("W", $action)){
                            $msg=error_messages()['integration'];
                            $response= usage_error($msg[0],$msg[1]);
                            $response['error_code']='W';
                            
                    }
                    if($accounts_created >= 100000 && in_array("X", $action)){
                            $msg=error_messages()['accounts'];
                            $response= usage_error($msg[0],$msg[1]);
                            $response['error_code']='X';
                            
                    }
                    if($status['Y'] >= 100000 && in_array("Y", $action)){
                            $msg=error_messages()['platforms'];
                            $response= usage_error($msg[0],$msg[1]);
                            $response['error_code']='Y';
                            
                    }
                    if($status['Z'] >= 100000 && in_array("Z", $action)){
                            $msg=error_messages()['logs'];
                            $response= usage_error($msg[0],$msg[1]);
                            $response['error_code']='Z';
                            
                    }
                }
                elseif($temp == 13){
                    //premium
                    if($status['W'] >= 100000 && in_array("W", $action)){
                            $msg=error_messages()['integration'];
                            $response= usage_error($msg[0],$msg[1]);
                            $response['error_code']='W';
                            
                    }
                    if($accounts_created >= 100000 && in_array("X", $action)){
                            $msg=error_messages()['accounts'];
                            $response= usage_error($msg[0],$msg[1]);
                            $response['error_code']='X';
                            
                    }
                    if($status['Y'] >= 100000 && in_array("Y", $action)){
                            $msg=error_messages()['platforms'];
                            $response= usage_error($msg[0],$msg[1]);
                            $response['error_code']='Y';
                            
                    }
                    if($status['Z'] >= 10000000 && in_array("Z", $action)){
                            $msg=error_messages()['logs'];
                            $response= usage_error($msg[0],$msg[1]);
                            $response['error_code']='Z';
                            
                    }
                }   
                else{
                    break;
                }

                return json_encode($response);
                // if($caller){
                //     $result=call_user_func( "awp_{$action_provider_id}_save_integration" );
                //     $response['success']=true;
                //     $response['result']=$result;
                //     return json_encode($response);
                // }  
                // else{
                //     $response['success']=true;
                //     return json_encode($response);
                // }           
            }
            break;
    }
}

function plugin_status(){
    global $wpdb;
    $query="select * from {$wpdb->prefix}awp_integration where status=1";
    $result=$wpdb->get_results( $query, ARRAY_A );
    $temp['W']=count($result);


    $query= "SELECT platform_name,COUNT(platform_name) as total FROM {$wpdb->prefix}awp_platform_settings GROUP BY platform_name";
    $result=$wpdb->get_results( $query, ARRAY_A );
    $temp['X']=$result;

    // $query="select * from {$wpdb->prefix}awp_integration";
    // $result=$wpdb->get_results( $query, ARRAY_A );
    // $validiapps=array();
    // foreach ($result as $key => $row) {
    //     if(!empty($row['extra_data'])){
    //         $extra_data=$row['extra_data'];
    //         $extra_data=json_decode($extra_data,true);
    //         if(!isset($extra_data['soft_delete'])){
    //             array_push($validiapps, $row);
    //         }
    //     }
    //     else{
    //         array_push($validiapps, $row);
    //     }
    // }
    // $groupbyapps=array();
    // foreach ($validiapps as $key => $row) {
    //     if(!in_array($row['action_provider'], $groupbyapps)){
    //         array_push($groupbyapps, $row['action_provider']);
    //     }
    // }

    $query= "SELECT *  FROM {$wpdb->prefix}awp_platform_settings WHERE active_status='true'";
    $result=$wpdb->get_results( $query, ARRAY_A );
    $temp['Y']=count($result);
    


    $query="SELECT * FROM {$wpdb->prefix}automate_log WHERE MONTH(time) = MONTH(CURRENT_DATE()) AND YEAR(time) = YEAR(CURRENT_DATE())";
    $result=$wpdb->get_results( $query, ARRAY_A );
    $temp['Z']=count($result);
    $query="select * from {$wpdb->prefix}automate_log WHERE response_code = '200' OR response_code = '201' OR response_code = '202' OR response_code = '203' OR response_code = '204'";
    $result=$wpdb->get_results( $query, ARRAY_A );
    $temp['S']=count($result);
    $query="select * from {$wpdb->prefix}automate_log WHERE response_code != '200' AND response_code != '201' AND response_code != '202' AND response_code != '203' AND response_code != '204'";
    $result=$wpdb->get_results( $query, ARRAY_A );
    $temp['U']=count($result);

    $query="select * from {$wpdb->prefix}awp_integration GROUP BY form_id,form_name";
    $result=$wpdb->get_results( $query, ARRAY_A );
    $temp['FS']=count($result);

    return $temp;
}

function version_type($key){
    $temp=['T','F','X','B','W','S','P','K','Z','U','A','C','D','E'];

    if(isset($key[((int)($key==$key))]) && in_array($key[((int)($key==$key))], $temp)){
        return array_search($key[((int)($key==$key))], $temp);
    }    
}


function awp_actions_status(){
    global $wpdb;
    $temp=array();
    $vtype=version_type(get_option('sperse_license_key'));
    

    if($vtype==3){
        $temp['A']='10,000';
    }
    elseif ($vtype==5) {
        $temp['A']='Unlimited';
    }
    elseif ($vtype==6) {
        $temp['A']='100,000';
    }
    elseif ($vtype==9) {
        $temp['A']='Unlimited';
    }
    elseif ($vtype==10) {
        $temp['A']='10,000';
    }
    elseif ($vtype==11) {
        $temp['A']='25,000';
    }
    elseif ($vtype==12) {
        $temp['A']='100,000';
    }
    elseif ($vtype==13) {
        $temp['A']='Unlimited';
    }
    else{
        $temp['A']='';
    }

    $query="SELECT * FROM {$wpdb->prefix}automate_log WHERE MONTH(time) = MONTH(CURRENT_DATE()) AND YEAR(time) = YEAR(CURRENT_DATE())";
    $result=$wpdb->get_results( $query, ARRAY_A );
    $temp['M']=$result;


    $query="SELECT * FROM {$wpdb->prefix}automate_log WHERE DATE('time') = CURDATE()";
    $result=$wpdb->get_results( $query, ARRAY_A );
    $temp['T']=$result;

    return $temp;

}

function awp_get_cl_conditions() {
    return array(
        "equal_to"         => esc_html__( 'Equal to', 'automate_hub' ),
        "not_equal_to"     => esc_html__( 'Not equal to', 'automate_hub' ),
        "contains"         => esc_html__( 'Contains', 'automate_hub' ),
        "does_not_contain" => esc_html__( 'Does not Contain', 'automate_hub' ),
        "starts_with"      => esc_html__( 'Starts with', 'automate_hub' ),
        "ends_with"        => esc_html__( 'Ends with', 'automate_hub' )
    );
}

function awp_match_conditional_logic( $cl, $posted_data ) {
    if( $cl["active"] != "yes" ) {
        return true;
    }
    $match  = 0;
    $length = isset($cl["conditions"]) ? count( $cl["conditions"] ) :0;

    foreach( $cl["conditions"] as $condition ) {
        if( !$condition["field"] ) {
            continue;
        }
        foreach( $posted_data as $key => $data ) {
            if( is_array( $data ) ) {
                $data = implode( ",", $data );
            }
            if( $condition["field"] == $key ) {
                if( awp_match_single_logic( $data, $condition["operator"], $condition["value"] ) ) {
                    $match++;
                }
            }
        }
    }
    if( $cl["match"] == "any" && $match > 0 ) {
        return true;
    }
    if( $cl["match"] == "all" && $match == $length ) {
        return true;
    }
    return false;
}

function awp_match_single_logic($data, $operator, $value ) {
    $result = false;
    switch( $operator ) {
        case 'equal_to':
            if( $data == $value ) {
                $result = true;
            }
            break;
        case 'not_equal_to':
            if( $data != $value ) {
                return true;
            }
            break;
        case 'contains':
            if ( strpos($data, $value ) !== false ) {
                return true;
            }
            break;
        case 'does_not_contains':
            if ( strpos($data, $value ) === false ) {
                return true;
            }
            break;
        case 'starts_with':
            $length = strlen( $value );
            return ( substr( $data, 0, $length ) === $value );
            break;
        case 'ends_with':
            $length = strlen( $value );
            if( $length == 0 ) {
                return true;
            }
            if( substr( $data, -$length ) === $value ) {
                return true;
            }
        default: return false;
    }
    return $result;
}
function awp_get_inbound_url($key){
    $apikey=get_option('awp_webhook_api_key');
    return get_rest_url(null,'automatehub/reciever/'.$apikey.'-'.$key);
}

function awp_create_default_webhook(){
    global $wpdb;
    $platform_obj= new AWP_Platform_Shell_Table('webhookin');
    $query="select * from {$wpdb->prefix}awp_platform_settings where api_key = '001'";
    $result=$wpdb->get_results($query);
    if(!count($result)){
        $url=awp_get_inbound_url('001');
        $platform_obj->save_platform(['url'=>$url,'account_name'=>"Default Inbound Webhook",'api_key'=>'001']);
    }
}
function awp_get_special_tags() {
    $special_tags = array(
        'contact_number'   => esc_html__( 'Contact Number'  , 'automate_hub' ),
        'email_address'              => esc_html__( 'Email Address'             , 'automate_hub' ),
        'full_name'              => esc_html__( 'Full Name'             , 'automate_hub' ),
        'first_name'           => esc_html__( 'First Name'          , 'automate_hub' ),
        'last_name'           => esc_html__( 'Last Name'          , 'automate_hub' ),
        'company'        => esc_html__( 'Company'       , 'automate_hub' ),
        'job_title'        => esc_html__( 'Job Title'       , 'automate_hub' ),
        'industry'  => esc_html__( 'Industry' , 'automate_hub' ),
        'website_url'          => esc_html__( 'Website URL'         , 'automate_hub' ),
        'street_address'  => esc_html__( 'Street Address' , 'automate_hub' ),
        'city'           => esc_html__( 'City'          , 'automate_hub' ),
        'zipcode'         => esc_html__( 'Zipcode'        , 'automate_hub' ),
        'state'        => esc_html__( 'State'       , 'automate_hub' ),
        '2_letter_state_code'          => esc_html__( '2 Letter State Code'         , 'automate_hub' ),
        '2_letter_country_code'           => esc_html__( '2 Letter Country Code'          , 'automate_hub' ),
        'phone'   => esc_html__( 'Phone'  , 'automate_hub' ),
        'bankcode'    => esc_html__( 'BANKCODE'   , 'automate_hub' ),
        'subject' => esc_html__( 'Subject', 'automate_hub' ),
        'comments'        => esc_html__( 'Comments'       , 'automate_hub' ),
        'answer_question_1'        => esc_html__( 'AnswerQuestion1'       , 'automate_hub' ),
        'answer_question_2'        => esc_html__( 'AnswerQuestion2'       , 'automate_hub' ),
        'answer_question_3'        => esc_html__( 'AnswerQuestion3'       , 'automate_hub' ),
        'order_id'        => esc_html__( 'Order ID'       , 'automate_hub' ),
        'parent_id'        => esc_html__( 'Parent ID'       , 'automate_hub' ),
        'user_id'        => esc_html__( 'User ID'       , 'automate_hub' ),
        'billing_first_name'        => esc_html__( 'Billing First Name'       , 'automate_hub' ),
        'billing_last_name'        => esc_html__( 'Billing Last Name'       , 'automate_hub' ),
        'formatted_billing_full_name'        => esc_html__( 'Formatted Billing Full Name'       , 'automate_hub' ),
        'billing_company'        => esc_html__( 'Billing Company'       , 'automate_hub' ),
        'billing_address_1'        => esc_html__( 'Billing Address 1'       , 'automate_hub' ),
        'billing_address_2'        => esc_html__( 'Billing Address 2'       , 'automate_hub' ),
        'billing_city'        => esc_html__( 'Billing City'       , 'automate_hub' ),
        'billing_state'        => esc_html__( 'Billing State'       , 'automate_hub' ),
        'billing_postcode'        => esc_html__( 'Billing Postcode'       , 'automate_hub' ),
        'billing_country'        => esc_html__( 'Billing Country'       , 'automate_hub' ),
        'billing_email'        => esc_html__( 'Billing Email'       , 'automate_hub' ),
        'billing_phone'        => esc_html__( 'Billing Phone'       , 'automate_hub' ),
        'formatted_billing_phone'        => esc_html__( 'Formatted Billing Phone'       , 'automate_hub' ),
        'shipping_first_name'        => esc_html__( 'Shipping First Name'       , 'automate_hub' ),
        'shipping_last_name'        => esc_html__( 'Shipping Last Name'       , 'automate_hub' ),
        'shipping_full_name'        => esc_html__( 'Shipping Full Name'       , 'automate_hub' ),
        'shipping_company'        => esc_html__( 'Shipping Company'       , 'automate_hub' ),
        'shipping_address_1'        => esc_html__( 'Shipping Address 1'       , 'automate_hub' ),
        'shipping_address_2'        => esc_html__( 'Shipping Address 2'       , 'automate_hub' ),
        'shipping_city'        => esc_html__( 'Shipping City'       , 'automate_hub' ),
        'shipping_state'        => esc_html__( 'Shipping State'       , 'automate_hub' ),
        'shipping_postcode'        => esc_html__( 'Shipping Postcode'       , 'automate_hub' ),
        'shipping_country'        => esc_html__( 'Shipping Country'       , 'automate_hub' ),
        'shipping_email'        => esc_html__( 'Shipping Email'       , 'automate_hub' ),
        'shipping_phone'        => esc_html__( 'Shipping Phone'       , 'automate_hub' ),
        'formated_shipping_phone'        => esc_html__( 'Formatted Shipping Phone'       , 'automate_hub' ),
        'shipping_address_map_url'        => esc_html__( 'Shipping Address Map Url'       , 'automate_hub' ),

        'payment_method'        => esc_html__( 'Payment Method'       , 'automate_hub' ),
        'payment_method_title'        => esc_html__( 'Payment Method Title'       , 'automate_hub' ),
        'transaction_id'        => esc_html__( 'Transaction ID'       , 'automate_hub' ),
        'oreder_created_via'        => esc_html__( 'Order Created Via'       , 'automate_hub' ),
        'date_completed'        => esc_html__( 'Date Completed'       , 'automate_hub' ),
        'date_created'        => esc_html__( 'Date Created'       , 'automate_hub' ),
        'date_modified'        => esc_html__( 'Date Modified'       , 'automate_hub' ),
        'date_paid'        => esc_html__( 'Date PaID'       , 'automate_hub' ),
        'cart_hash'        => esc_html__( 'Cart Hash'       , 'automate_hub' ),
        'currency'        => esc_html__( 'Currency'       , 'automate_hub' ),
        'customer_id'        => esc_html__( 'Customer ID'       , 'automate_hub' ),
        'customer_ip_address'        => esc_html__( 'Customer Ip Address'       , 'automate_hub' ),
        'customer_user_agent'        => esc_html__( 'Customer User Agent'       , 'automate_hub' ),
        'customer_note'        => esc_html__( 'Customer Note'       , 'automate_hub' ),
        'total'        => esc_html__( 'Total'       , 'automate_hub' ),
        'formatted_order_total'        => esc_html__( 'Formatted Order Total'       , 'automate_hub' ),
        'order_item_total'        => esc_html__( 'Order Item Total'       , 'automate_hub' ),
        'prices_include_tax'        => esc_html__( 'Prices Include Tax'       , 'automate_hub' ),
        'discount_total'        => esc_html__( 'Discount Total'       , 'automate_hub' ),
        'discount_tax'        => esc_html__( 'Discount Tax'       , 'automate_hub' ),
        'shipping_total'        => esc_html__( 'Shipping Total'       , 'automate_hub' ),
        'shipping_tax'        => esc_html__( 'Shipping Tax'       , 'automate_hub' ),
        'cart_tax'        => esc_html__( 'Cart Tax'       , 'automate_hub' ),
        'total_tax'        => esc_html__( 'Total Tax'       , 'automate_hub' ),
        'total_discount'        => esc_html__( 'Total Discount'       , 'automate_hub' ),
        'subtotal'        => esc_html__( 'Subtotal'       , 'automate_hub' ),
        'tax_totals'        => esc_html__( 'Tax Totals'       , 'automate_hub' ),
        'items_full_json'        => esc_html__( 'Items Full Json'       , 'automate_hub' ),
        'items_id'        => esc_html__( 'Item(S) ID'       , 'automate_hub' ),
        'items_name'        => esc_html__( 'Item(S) Name'       , 'automate_hub' ),
        'items_quantity'        => esc_html__( 'Item(S) Quantity'       , 'automate_hub' ),
        'items_total'        => esc_html__( 'Item(S) Total'       , 'automate_hub' ),
        'fees'        => esc_html__( 'Fees'       , 'automate_hub' ),
        'taxes'        => esc_html__( 'Taxes'       , 'automate_hub' ),
        'shipping_methods'        => esc_html__( 'Shipping Methods'       , 'automate_hub' ),
        'form_id'        => esc_html__( 'Form ID'       , 'automate_hub' ),
        'submission_id'        => esc_html__( 'Submission ID'       , 'automate_hub' ),
        'submission_date'        => esc_html__( 'Submission Date'       , 'automate_hub' ),
        'current_date'        => esc_html__( 'CurrentDate'       , 'automate_hub' ),
        'current_time'        => esc_html__( 'CurrentTime'       , 'automate_hub' ),
        'user_ip'        => esc_html__( 'User IP'       , 'automate_hub' ),
        '_user_agent'        => esc_html__( 'User Agent'       , 'automate_hub' ),
        '_site_title'        => esc_html__( 'Site Title'       , 'automate_hub' ),
        '_site_description'        => esc_html__( 'Site Description'       , 'automate_hub' ),
        '_site_title'        => esc_html__( 'Site URL'       , 'automate_hub' ),
        '_site_admin_email'        => esc_html__( 'Site Admin Email'       , 'automate_hub' ),
        '_post_id'        => esc_html__( 'Post ID'       , 'automate_hub' ),
        '_post_name'        => esc_html__( 'Post Name'       , 'automate_hub' ),
        '_post_title'        => esc_html__( 'Post Title'       , 'automate_hub' ),
        '_post_url'        => esc_html__( 'Post Url'       , 'automate_hub' ),
        '_user_id'        => esc_html__( 'User ID'       , 'automate_hub' ),
        '_user_first_name'        => esc_html__( 'User First_Name'       , 'automate_hub' ),
        '_user_last_name'        => esc_html__( 'User Last_Name'       , 'automate_hub' ),
        '_user_display_name'        => esc_html__( 'User Display_Name'       , 'automate_hub' ),
        '_user_email'        => esc_html__( 'User Email'       , 'automate_hub' ),
    );
    return $special_tags;
}

function awp_get_special_tags_values( $post ) {
    if(!function_exists('wp_get_current_user')) {
        include(ABSPATH . "wp-includes/pluggable.php");
    }
    global $current_user;
    wp_get_current_user();
    $special_tags = awp_get_special_tags();
    $values       = array();
    if( !empty( $special_tags ) ) {
        foreach( $special_tags as $key => $value ) {
            $values[$key] = awp_get_single_special_tag_value( $key, $current_user, $post );
        }
    }
    return $values;
}

function awp_get_single_special_tag_value( $tag, $current_user, $post ) {
    switch( $tag ) {
        case "submission_date"   : return date   ("Y-m-d H:i:s");                                           break;
        case "_submission_date"  : return wp_date('Y-m-d H:i:s');                                           break;
        case "_date"             : return wp_date(get_option('date_format'));                               break;
        case "_time"             : return wp_date(get_option('time_format'));                               break;
        case "_weekday"          : return wp_date('l');                                                     break;
        case "user_ip"           : return awp_get_client_ip();                                            break;
        case "_user_ip"          : return awp_get_client_ip();                                            break;
        case "_user_agent"       : return isset($_SERVER['HTTP_USER_AGENT'])? substr(sanitize_text_field($_SERVER['HTTP_USER_AGENT']), 0, 254 ): ''; break;
        case "_site_title"       : return get_bloginfo('name'         );                                    break;
        case "_site_description" : return get_bloginfo('description'  );                                    break;
        case "_site_url"         : return  esc_url( home_url() );                                    break;
        case "_site_admin_email" : return get_bloginfo('admin_email'  );                                    break;
        case "_post_id"          : return is_object ( $post ) ? $post->ID : "";                             break;
        case "_post_name"        : return is_object ( $post ) ? $post->post_name : "";                      break;
        case "_post_title"       : return is_object ( $post ) ? $post->post_title : "";                     break;
        case "_post_url"         : return is_object ( $post ) ? get_permalink( $post->ID ) : "";            break;
        case "_user_id"          : return isset( $current_user->ID ) ? $current_user->ID : "";              break;
        case "_user_first_name"  : return isset( $current_user->ID ) ? $current_user->user_firstname : "";  break;
        case "_user_last_name"   : return isset( $current_user->ID ) ? $current_user->user_lastname : "";   break;
        case "_user_display_name": return isset( $current_user->ID ) ? $current_user->display_name : "";    break;
        case "_user_email"       : return isset( $current_user->ID ) ? $current_user->user_email: "";       break;
    }
    return true;
}

/* Settings Tabs List */
function awp_get_settings_tabs() {
    $tabs = array(
        'general' => array(
            'name'=>esc_html__( 'General', 'automate_hub' ),
            'cat'=>array('crm','sms')
        ) 
    );
    
    $tabs=apply_filters( 'awp_settings_tabs', $tabs );
    //inject favourties
    $favourites=awp_get_favourite();
    $connectedapps=awp_get_connected_apps();
    foreach ($favourites as $key => $obj) {
        if(isset($tabs[$obj->platform])){
            if(isset($tabs[$obj->platform]['cat'])){
                array_push($tabs[$obj->platform]['cat'], 'favorites');
            }
            elseif(isset($tabs[$obj->platform])){
                $tabs[$obj->platform]=['cat'=>'','name'=>$obj->platform];
                $tabs[$obj->platform]['cat']=array('favorites');
            }
            
            
        }
    }

    foreach ($connectedapps as $key => $obj) {
        if(isset($tabs[$obj->platform_name])){
            if(isset($tabs[$obj->platform_name]['cat'])){
                array_push($tabs[$obj->platform_name]['cat'], 'connectedapps');
            }
            elseif(isset($tabs[$obj->platform_name])){
                $tabs[$obj->platform_name]=['cat'=>'','name'=>$obj->platform_name];
                $tabs[$obj->platform_name]['cat']=array('connectedapps');
            }
            
            
        }
    }

    return $tabs;
}

/* Sanitize text or array field */
function awp_sanitize_text_or_array_field($array_or_string) {
    if( is_string($array_or_string) ){
        $array_or_string = sanitize_text_field($array_or_string);
    }elseif( is_array($array_or_string) ){
        foreach ( $array_or_string as $key => &$value ) {
            if ( is_array( $value ) ) {
                $value = awp_sanitize_text_or_array_field($value);
            }
            else {
                $value = sanitize_text_field( $value );
            }
        }
    }
    return $array_or_string;
}

/* Get parsed value */
function awp_get_parsed_values($field, $posted_data) {

    // while (strpos($field, 'static_') !== false) {
        
    // }
    //special handling for copied fields
    if(!is_array($field)){
        while (strpos($field, 'copied') !== false) {
        
        
            //get start point
            $start=strpos($field, 'copied');
            //get the second occurence of _
            $stop=strpos($field, '_', $start+7) + 1; // +7 for _ after 'copied_' word and another +1 for to include _ position as well in net step which is substr
            $len=$stop-$start;
            $len=$len;//preceeding _
            $chunk=substr($field, $start,$len);
            
            
            $field=str_replace($chunk, '', $field);
            
            
        }
    }
    
    

    if(!empty($field) && is_string($field)  ){
        $field_array = explode("}}", $field);
        $field_array = array_filter($field_array);

        if(count($field_array)>1){
            $field_string_back = '';

            foreach ($field_array as $key1 =>$f_field) {
                 if($key1==0){
                    $field_string_back .=   $f_field.'}}';
                 }else{
                  $field_string_back .= ' '.$f_field.'}} ';
                }
            }
            $field=$field_string_back;
            
        }
    }
    foreach( $posted_data as $key => $value ) {
        if(is_array($field) && !empty($field[1]) ){
            $field[1] = str_replace( $key, $value, $field[1] );
        }else{
            if(!is_array($value)){
                $field = str_replace( "{{" . $key . "}}", $value, $field,$i );

            }
        }
    }
    if(!empty($field) && !(is_array($field)))
        $field = trim($field);

    // special handling for static fields
    if(!is_array($field)){
        while (strpos($field, 'static_') !== false) {
            $start=strpos($field, '{{static_');
            $end=strpos($field,'}}');
            $len=$end-$start;
            $len=$len+2;//two letters }}
            $chunk=substr($field, $start,$len);
            //making final value
            $value=str_replace('{{static_', '', $chunk);
            $value=str_replace('}}', '', $value);
            $value=str_replace('_', ' ', $value);
        
            //merging final value to field
            $field=str_replace($chunk, $value, $field);

        }
    }
    

    return $field;
}

function awp_get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))           $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR')) $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))     $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))   $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))          $ipaddress = getenv('REMOTE_ADDR');
    else                                    $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

/* Insert data to log */
function awp_add_to_log( $return, $url, $args, $record,$start_time='' ) {
    global $wpdb;
    if(empty($record)) {
        $integration_id = 0;
    } else {
        $integration_id = $record["id"];
    }
    $log_table = $wpdb->prefix . 'automate_log';
    $request_data = json_encode(array('url'  => $url, 'args' => $args));
    $return = (array) $return;
    $ip = awp_get_client_ip();
    if( !empty($return["response"]["code"]) && $return["response"]["code"]==201){
        $return["response"]["code"]=200;
    }

    if(!empty($return["response"]["code"])){
        $result = $wpdb->insert(
            $log_table,
            array(
                'response_code'    => $return["response"]["code"],
                'response_message' => $return["response"]["message"],
                'integration_id'   => $integration_id,
                'request_data'     => $request_data,
                'response_data'    => !empty($return["body"]) ? $return["body"] : '',
                'start_time'        =>$start_time,
                'ip'                =>$ip
            )
        );
    }

    return;
}


function get_integration_by_id($id){
    global $wpdb;
    $table        = $wpdb->prefix . 'awp_integration';
    $result       = $wpdb->get_row( $wpdb->prepare("SELECT * FROM {$table} WHERE id =%d",$id), ARRAY_A );
    return $result;
}

function isJson($string) {
   json_decode($string);
   return json_last_error() === JSON_ERROR_NONE;
}

function awp_add_favourite($platform){
    global $wpdb;
    $table        = $wpdb->prefix . 'awp_apps';
    $query= $wpdb->prepare("SELECT * FROM {$table} WHERE platform =%s",$platform);
    $result       = $wpdb->get_row( $query, ARRAY_A );
    if(empty($result) || !count($result)){

        $query= $wpdb->prepare("Insert into {$table}(platform) VALUES ('%s')",$platform);
        $result=$wpdb->get_results($query);
    }
    
    return $result;
}


function awp_remove_favourite($platform){
    global $wpdb;
    $table        = $wpdb->prefix . 'awp_apps';

    $query = $wpdb->prepare("DELETE FROM {$table} WHERE platform =%s", $platform);
    $result=$wpdb->get_results($query);

    
    return $result;
}

function awp_get_favourite(){
    global $wpdb;
    $table        = $wpdb->prefix . 'awp_apps';
    $query =  "SELECT * FROM {$table}";
    $result       = $wpdb->get_results($query);
    return $result;
}

function awp_get_connected_apps(){
    global $wpdb;
    $table        = $wpdb->prefix . 'awp_platform_settings';
    $query =  "SELECT * FROM {$table}";
    $result       = $wpdb->get_results($query);
    return $result;
}




function awp_get_form_submission_queue(){
    $queue=get_option('awp_form_submission_queue',true);
    if(empty($queue)){
        return array();
    }
    else{
        $list=unserialize($queue);
        return $list;
    }

}



function awp_add_queue_form_submission($function_name,$record,$posted_data){
    //awp_write_log("==================================================================================");
    $posted_data_back=$posted_data;
    // for limited version start
    $notice=awp_usage_controller("A","sperse",["Z"]);
    $notice=json_decode($notice,true);
    if($notice['success']==false){
        return;
    }
    // for limited version end

    $queue=awp_get_form_submission_queue();
    //awp_write_log("this is already queue");
    //awp_write_log($queue);
    if(!is_array($queue)){
        return 'Invalid Call';
    }

    //check if its soft deleted or not
    if(isset($record['extra_data']) && $record['extra_data']!=''){
        $obj=json_decode($record['extra_data'],true);
        if(isset($obj['soft_delete']) && $obj['soft_delete'] == true){
            return;
        }
    }
    //
    //check if its a double submit request
    foreach ($queue as $key => $list_item) {
        if(is_array($list_item['posted_data']) && is_array($posted_data) && isset($list_item['created_at']) && is_array($list_item['record']) && is_array($record)){
            array_multisort($list_item['posted_data']);
            array_multisort($posted_data);
            array_multisort($list_item['record']);
            array_multisort($record);
            if(serialize($list_item['posted_data']) === serialize($posted_data) && serialize($list_item['record']) === serialize($record) && time()-$list_item['created_at'] <=2){
                //awp_write_log('This item was found to be already inserted so skipping this one');
                return;
            }
        }
    }
    //awp_write_log('item was found to be new so adding inside the queue');    
    $item=array(
        'callable'=>$function_name,
        'record'  =>$record,
        'posted_data'=>$posted_data_back,
        'created_at'=>time(),
    );
    
    array_push($queue, $item);
    //awp_write_log('queue adter the push');    
    //awp_write_log($queue);    
    
    $serialzed=serialize($queue);
    update_option('awp_form_submission_queue',$serialzed);
    return $serialzed;
}
function awp_get_app_edit_data($id){

    if(empty($id))
    return array();

    global $wpdb;
    $table_name= $wpdb->prefix.'awp_platform_settings';
    $query=$wpdb->prepare("SELECT * from `$table_name` where `id`=%d",$id);
    $result=$wpdb->get_results($query);
    $edit_data = (!empty($result) && !empty($result['0'])) ? (array) $result['0'] : array();
    return $edit_data;
}


if ( ! function_exists( 'awp_write_log' ) ) {
  function awp_write_log( $log = null, $before = '', $after = '', $clear = false ) {
    if (( defined( 'AWP_PATH' ) || defined( 'ABSPATH' ) ) ) {
      // Destination File
      $debug_log_file = ( ( defined( 'AWP_PATH' ) ) ? AWP_PATH : ABSPATH ) . '/assets/debug.txt';
      // Clear debug.log if 'true' or the file is more than 10Mb (10485760 bytes)
      if ( true === $clear || ! file_exists( $debug_log_file ) ) {
        file_put_contents( $debug_log_file, '' );
      }
      if ( is_writable( $debug_log_file ) ) {
        $content = '';
        // Only string can be in the wrapper role
        $before = is_string( $before ) ? $before : '';
        $after  = is_string( $after ) ? $after : '';
        // Build content ('-' & 'hr' define a separate line before the content)
        $hr      = in_array( $clear, [ '-', 'hr' ], true ) ? str_repeat( '-', 80 ) . PHP_EOL : '';
        $content .= date( 'Y-m-d H:i:s' ) . ': ' . $before . var_export( $log, true ) . $after . PHP_EOL;
        // Write log
        if ( defined( 'DEBUG_LOG_INVERTED' ) && false === DEBUG_LOG_INVERTED ) {
          $content = $hr . $content;
          file_put_contents( $debug_log_file, $content, FILE_APPEND ); // was (temp): error_log( $content, 3, $debug_log_file );
        } else {
          $content = $content . $hr . file_get_contents( $debug_log_file );
          file_put_contents( $debug_log_file, $content );
        }
      }
    }
  }
}

