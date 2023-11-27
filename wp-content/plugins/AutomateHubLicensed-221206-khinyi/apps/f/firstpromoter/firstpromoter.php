<?php
class AWP_Firstpromoter extends appfactory
{
    public function init_actions(){
        add_action('admin_post_awp_firstpromoter_save_api_token', [$this, 'awp_save_firstpromoter_api_token'], 10, 0);
    }

    public function init_filters(){
        add_filter('awp_platforms_connections', [$this, 'awp_firstpromoter_platform_connection'], 10, 1);
    }

    public function action_provider( $providers ) {
        $providers['firstpromoter'] = array(
            'title' => esc_html__( 'FirstPromoter', 'automate_hub' ),
            'tasks' => array(
                'createpromoter'   => esc_html__( 'Create Promoter', 'automate_hub' )
            )
        );
        return  $providers;
    }

    public function settings_tab( $tabs ) {
        $tabs['firstpromoter'] = array('name'=>esc_html__( 'FirstPromoter', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'firstpromoter') {
            return;
        }
        $nonce = wp_create_nonce("awp_firstpromoter_settings");
        $api_token = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/firstpromoter" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/firstpromoter.png" width="280" height="50" alt="FirstPromoter Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();      
                ?>
                <br />
                <?php 

$form_fields = '';
$app_name= 'firstpromoter';
$firstpromoter_form = new AWP_Form_Fields($app_name);

$form_fields = $firstpromoter_form->awp_wp_text_input(
    array(
        'id'            => "awp_firstpromoter_display_name",
        'name'          => "awp_firstpromoter_display_name",
        'value'         => $display_name,
        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $firstpromoter_form->awp_wp_text_input(
    array(
        'id'            => "awp_firstpromoter_api_token",
        'name'          => "awp_firstpromoter_api_token",
        'value'         => $api_key,
        'placeholder'   => esc_html__( 'Enter your firstpromoter API Key', 'automate_hub' ),
        'label'         =>  esc_html__( 'Firstpromoter API Key', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $firstpromoter_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_firstpromoter_save_api_token',
    )
);


$form_fields .= $firstpromoter_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $firstpromoter_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$firstpromoter_form->render($form_fields);

?>
        </div>

        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                 $data=[
                    'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('firstpromoter');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();

                ?>
                        </form>
                </div>
        <?php
    }

    public function awp_save_firstpromoter_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_firstpromoter_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }
        $api_token = sanitize_text_field($_POST["awp_firstpromoter_api_token"]);
        $display_name     = sanitize_text_field( $_POST["awp_firstpromoter_display_name"] );

        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('firstpromoter');
        $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_token]);

        AWP_redirect("admin.php?page=automate_hub&tab=firstpromoter");
    } 
    
    public function load_custom_script() {
        wp_enqueue_script( 'awp-firstpromoter-script', AWP_URL . '/apps/f/firstpromoter/firstpromoter.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="firstpromoter-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'firstpromoter',
                           'app_name'=>'Firstpromoter ',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/firstpromoter.png',
                           'app_icon_alter_text'=>'Firstpromoter  Icon',
                           'account_select_onchange'=>'getsourcesid',
                           'tasks'=>array(
                                'createcustomer'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Source',
                                            'type'=>'select',
                                            'name'=>"source_id",
                                            'required' => 'required',
                                            'onchange' => 'selectedsource',
                                            'option_for_loop'=>'(item) in data.sourceList',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.provider}}',
                                            'select_default'=>'Select Source...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': accountLoading}",
                                                        )
                                        )
                                    ),
                                )
                            ),
                    ); 
                    require (AWP_VIEWS.'/awp_app_integration_format.php');
                ?>
            </script>
        <?php
    }
};

$Awp_Firstpromoter = new AWP_Firstpromoter();

function awp_Firstpromoter_save_integration() {
    Appfactory::save_integration();
}

function awp_Firstpromoter_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 
    $platform_obj= new AWP_Platform_Shell_Table('Firstpromoter');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $task = $decoded_data["task"]; 
    if( $task == "createpromoter" ) {
        $first_name = empty( $data["first_name"] ) ? "" : awp_get_parsed_values($data["first_name"], $posted_data);
        $last_name = empty( $data["last_name"] ) ? "" : awp_get_parsed_values($data["last_name"], $posted_data);
        $email = empty( $data["email"] ) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $cust_id = "CID".mt_rand(000210, 999999);
        $token = $api_key;
        $body = json_encode([
            "first_name"=>$first_name,
            "last_name"=>$last_name,
            "email"=>$email,
            "cust_id"=>$cust_id
        ]);
        $url = "https://firstpromoter.com/api/v1/promoters/create";
        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'x-api-key' => $token
            ),

            'body'=> $body
        ];
        $response  = wp_remote_post($url,  $args );
        $args['headers']['x-api-key']="XXXXXXXXXXXX";
        awp_add_to_log( $response, $url, $args, $record );
    } 
    return $response;
}

function awp_Firstpromoter_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('Firstpromoter');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_key=$temp->api_key;
    if( !$api_key ) {
        return;
    }
    $data=stripslashes($data);
    $data=preg_replace('/\s+/', '',$data); 
    $data=str_replace('"{', '{', $data);
    $data=str_replace('}"', '}', $data);        
    $data=json_decode($data,true);
    $body=$data['args']['body'];
    $url=$data['url'];
    if(!$url){
        $response['success']=false;
        $response['msg']="Syntax Error! Request is invalid";
        return $response;
    }
        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'x-api-key' => $api_key
            ),
            'body'=> json_encode($body)
        ];
        $return  = wp_remote_post($url,  $args );
        $args['headers']['x-api-key']="XXXXXXXXXXXX";
        awp_add_to_log( $return, $url, $args, $integration );
    $response['success']=true;    
    return $response;
}