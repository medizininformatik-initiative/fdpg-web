<?php

add_action('admin_init','awp_webhookin_permalinks_maintainer');

function awp_webhookin_permalinks_maintainer(){

    global $wpdb;
    $table_name=$wpdb->prefix.'awp_platform_settings';
    $query="select * from ".$table_name.' where platform_name="webhookin"';
    $data=$wpdb->get_results($query);
    
    if ( get_option('permalink_structure') ) {         
        foreach ($data as $key => $value) {
            if(awp_get_url_type($value->url) ==  'plain'){
                $url=awp_get_inbound_url($value->api_key);
                $wpdb->update($table_name,['url'=>$url],['id'=>$value->id]);
            }
        }
    }
    else{
        foreach ($data as $key => $value) {
            if(awp_get_url_type($value->url) ==  'permalink'){
                $url=awp_get_inbound_url($value->api_key);
                $wpdb->update($table_name,['url'=>$url],['id'=>$value->id]);
            }
        }
    }
}

function awp_get_url_type($url){
    $parts = parse_url($url);
    if(isset($parts['query'])){
        parse_str($parts['query'], $query);
        if(isset($query['rest_route'])){
            return 'plain';
        }
    }
    return 'permalink';
}



add_filter( 'awp_settings_tabs', 'awp_webhookin_settings_tab', 10, 1 );

function awp_webhookin_settings_tab( $providers ) {
    $providers['webhookin'] = 
    array('name'=>esc_html__( 'Webhook Inbound', 'automate_hub'), 'cat'=>array('connector'));
    return $providers;
}


add_action( 'awp_settings_view', 'awp_webhookin_settings_view', 10, 1 );

function awp_webhookin_settings_view( $current_tab ) {
    if( $current_tab != 'webhookin' ) { return; }
        global $wpdb;
        
        $apikey=get_option('awp_webhook_api_key');
        $platform_obj= new AWP_Platform_Shell_Table('webhookin');


        awp_create_default_webhook();

        $result=$platform_obj->fetch_table_data();
        $key=count($result);
        $key=strlen($key)==2?'0':'00';
        $key=$key.(count($result)+1);
        $nonce        = wp_create_nonce( "awp_webhookin_settings" );
        $account_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";
        $key     = isset($_GET['api_key']) ? $_GET['api_key'] : $key;
        $url     = isset($_GET['url']) ? $_GET['url'] :awp_get_inbound_url($key);
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        ?>
    <div class="platformheader">
    <a href="https://sperse.io/go/webhookin" target="_blank"><img src="<?php echo AWP_ASSETS; ?>/images/logos/webhookin.png" width="170" height="50" alt="Webhook Logo"></a><br/><br/>
    <?php 

$form_fields = '';
$app_name= 'webhookin';
$webhookin_form = new AWP_Form_Fields($app_name);

$form_fields = $webhookin_form->awp_wp_text_input(
    array(
        'id'            => "awp_webhookin_account_name",
        'name'          => "awp_webhookin_account_name",
        'value'         => $display_name,
        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $webhookin_form->awp_wp_text_input(
    array(
        'id'            => "awp_webhookin_url",
        'name'          => "awp_webhookin_url",
        'value'         => $url,
        'label'         =>  esc_html__( 'Webhook URL', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'data-type'=>'url',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $webhookin_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_save_webhookin_keys',
    )
);
$form_fields .= $webhookin_form->awp_wp_hidden_input(
    array(
        'name'          => "awp_webhookin_key",
        'value'         => $key,
    )
);


$form_fields .= $webhookin_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $webhookin_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$webhookin_form->render($form_fields);

?>
    </div>

    <div class="wrap">
        <form id="form-list" method="post">
                    
            
            <input type="hidden" name="page" value="automate_hub"/>

            <?php
            $data=[
                        'table-cols'=>['account_name'=>'Display Name','url'=>'API URL','spots'=>'Active Spots','active_status'=>'Active']
                ];
            $platform_obj= new AWP_Platform_Shell_Table('webhookin');
            $platform_obj->initiate_table($data);
            $platform_obj->prepare_items();
            $platform_obj->display_table();
                    
            ?>
        </form>
    </div>
    
<?php
}

add_action( 'admin_post_awp_save_webhookin_keys', 'awp_save_webhookin_keys', 10, 0 );
function awp_save_webhookin_keys(){

        if ( ! current_user_can('administrator') ){
            die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
        }

        // Security Check
        if (! wp_verify_nonce( $_POST['_nonce'], 'awp_webhookin_settings' ) ) {
            die( esc_html__( 'Security check Failed', 'automate_hub' ) );
        }

        $account_name = isset( $_POST["awp_webhookin_account_name"] ) ? sanitize_text_field( $_POST["awp_webhookin_account_name"] ) : "";
        $url = isset( $_POST["awp_webhookin_url"] ) ? sanitize_text_field( $_POST["awp_webhookin_url"] ) : "";
        $key = isset( $_POST["awp_webhookin_key"] ) ? sanitize_text_field( $_POST["awp_webhookin_key"] ) : "";
        if($key!="001"){
            //does not save any api_key that is 001 (reserved) because it is created and handled programitically
            $platform_obj= new AWP_Platform_Shell_Table('webhookin');
            $platform_obj->save_platform(['url'=>$url,'account_name'=>$account_name,'api_key'=>$key]);    
        }
        
        AWP_redirect( "admin.php?page=automate_hub&tab=webhookin" );
}



