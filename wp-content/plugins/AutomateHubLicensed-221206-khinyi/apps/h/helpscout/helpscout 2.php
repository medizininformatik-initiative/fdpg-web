<?php
class AWP_Helpscout extends appfactory
{
    public function init_actions(){
        add_action('admin_post_awp_helpscout_save_api_token', [$this, 'awp_save_helpscout_api_token'], 10, 0);
        add_action( 'wp_ajax_awp_fetch_category', [$this, 'awp_fetch_category']);
        add_action( 'wp_ajax_awp_fetch_collection', [$this, 'awp_fetch_collection']);
    }

    public function init_filters(){
        add_filter('awp_platforms_connections', [$this, 'awp_helpscout_platform_connection'], 10, 1);
    }

    public function action_provider( $providers ) {
        $providers['helpscout'] = array(
            'title' => esc_html__( 'Helpscout', 'automate_hub' ),
            'tasks' => array(
                'createarticle'   => esc_html__( 'Create Article', 'automate_hub' ),
                'createcategory'   => esc_html__( 'Create Category', 'automate_hub' )
            )
        );
        return  $providers;
    }

    public function settings_tab( $tabs ) {
        $tabs['helpscout'] = array('name'=>esc_html__( 'Helpscout', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'helpscout') {
            return;
        }
        $nonce = wp_create_nonce("awp_helpscout_settings");
        $api_token = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/helpscout" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/helpscout.png" width="260" height="50" alt="Helpscout Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?>

                <br />
                <?php 

$form_fields = '';
$app_name= 'helpscout';
$helpscout_form = new AWP_Form_Fields($app_name);

$form_fields = $helpscout_form->awp_wp_text_input(
    array(
        'id'            => "awp_helpscout_display_name",
        'name'          => "awp_helpscout_display_name",
        'value'         => $display_name,
        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $helpscout_form->awp_wp_text_input(
    array(
        'id'            => "awp_helpscout_profileid",
        'name'          => "awp_helpscout_profileid",
        'value'         => $client_id,
        'placeholder'   => esc_html__( 'Enter Profile ID', 'automate_hub' ),
        'label'         =>  esc_html__( 'Profile ID', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);
$form_fields .= $helpscout_form->awp_wp_text_input(
    array(
        'id'            => "awp_helpscout_api_token",
        'name'          => "awp_helpscout_api_token",
        'value'         => $api_token,
        'placeholder'   => esc_html__( 'Enter your Helpscout API Key', 'automate_hub' ),
        'label'         =>  esc_html__( 'Helpscout API Key', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $helpscout_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_helpscout_save_api_token',
    )
);


$form_fields .= $helpscout_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $helpscout_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$helpscout_form->render($form_fields);

?>
        </div>

        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                 $data=[
                    'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('helpscout');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();

                ?>
                        </form>
                </div>
        <?php
    }

    public function awp_save_helpscout_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_helpscout_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_token = sanitize_text_field($_POST["awp_helpscout_api_token"]);
        $display_name     = sanitize_text_field( $_POST["awp_helpscout_display_name"] );
        $profileid     = sanitize_text_field( $_POST["awp_helpscout_profileid"] );

        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('helpscout');
        $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_token, 'client_id' => $profileid ]);

        AWP_redirect("admin.php?page=automate_hub&tab=helpscout");
    } 
    
    public function load_custom_script() {
        wp_enqueue_script( 'awp-helpscout-script', AWP_URL . '/apps/h/helpscout/helpscout.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="helpscout-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'helpscout',
                           'app_name'=>'Helpscout ',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/helpscout.png',
                           'app_icon_alter_text'=>'Helpscout  Icon',
                           'account_select_onchange'=>'getcollection',
                           'tasks'=>array(
                                'createarticle'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Collection',
                                            'type'=>'select',
                                            'name'=>"collection_id",
                                            'required' => 'required',
                                            'onchange' => 'selectedcollection',
                                            'option_for_loop'=>'(item) in data.collectionlist',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Collection...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': collectionLoading}",
                                                        )
                                        )
                                    ),
                                ),
                                'createcategory'=>array(
                                    'task_assignments'=>array(
                                        
                                        array(
                                            'label'=>'Select Collection',
                                            'type'=>'select',
                                            'name'=>"collection_id",
                                            'required' => 'required',
                                            'onchange' => 'selectedcollection',
                                            'option_for_loop'=>'(item) in data.collectionlist',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Collection...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': collectionLoading}",
                                                        )
                                        )
                                    ),
                                )
                           )
                    ); 

                        require (AWP_VIEWS.'/awp_app_integration_format.php');
                ?>
            </script>
        <?php
    }

    
    public function awp_fetch_category() {
        if ( ! current_user_can('administrator') ){
            die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
        }
    
        if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
            die( esc_html__( 'Security check Failed', 'automate_hub' ) );
        }

        if (!isset( $_POST['platformid'] ) ) {
            die( esc_html__( 'Invalid Request', 'automate_hub' ) );
        }

        $id=sanitize_text_field($_POST['platformid']);
        $platform_obj= new AWP_Platform_Shell_Table('helpscout');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;

        $url = "https://docsapi.helpscout.net/v1/collections/".$_POST['collectionid']."/categories";
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic '.base64_encode($api_key.":".$api_key)
            )
        );

        $response = wp_remote_get( $url, $args );
        $body = wp_remote_retrieve_body( $response );
        $body = json_decode( $body, true );
        wp_send_json_success($body["categories"]['items']);
    }

    public function awp_fetch_collection() {
        if ( ! current_user_can('administrator') ){
            die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
        }
    
        if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
            die( esc_html__( 'Security check Failed', 'automate_hub' ) );
        }

        if (!isset( $_POST['platformid'] ) ) {
            die( esc_html__( 'Invalid Request', 'automate_hub' ) );
        }

        $id=sanitize_text_field($_POST['platformid']);
        $platform_obj= new AWP_Platform_Shell_Table('helpscout');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;

        $url = "https://docsapi.helpscout.net/v1/collections";
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic '.base64_encode($api_key.":".$api_key)
            )
        );

        $response = wp_remote_get( $url, $args );
        $body = wp_remote_retrieve_body( $response );
        $body = json_decode( $body, true );
        wp_send_json_success($body['collections']['items']);
    }

};


$Awp_Helpscout = new AWP_Helpscout();

function awp_helpscout_save_integration() {
    Appfactory::save_integration();
}

function awp_helpscout_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('helpscout');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $task = $decoded_data["task"]; 
    
    if( $task == "createarticle" ) {

        $name = empty( $data["name"] ) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $text = empty( $data["text"] ) ? "" : awp_get_parsed_values($data["text"], $posted_data);
        $collectionid = empty( $data["collectionid"] ) ? "" : awp_get_parsed_values($data["collectionid"], $posted_data);
      

        $body = json_encode([
            "name"=>$name,
            "text"=>$text,
            "collectionId"=>$collectionid
        ]);

        $url = "https://docsapi.helpscout.net/v1/articles";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic '.base64_encode($api_key.":".$api_key)
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } else if( $task == "createcategory" ) {

        $name = empty( $data["cname"] ) ? "" : awp_get_parsed_values($data["cname"], $posted_data);
        $slug = empty( $data["slug"] ) ? "" : awp_get_parsed_values($data["slug"], $posted_data);
        $collectionid = empty( $data["collectionid"] ) ? "" : awp_get_parsed_values($data["collectionid"], $posted_data);
      

        $body = json_encode([
            "name"=>$name,
            "slug"=>$slug,
            "collectionId"=>$collectionid,
            "visibility"=> "public"
        ]);

        $url = "https://docsapi.helpscout.net/v1/categories";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic '.base64_encode($api_key.":".$api_key)
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } 
    return $response;
}


function awp_helpscout_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('helpscout');
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

   
        $campfireUrl = $url;
        $token = 'Bearer '.$api_key;
        

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $token,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),

            'body'=> json_encode($body)
        ];


        $return  = wp_remote_post($campfireUrl,  $args );

        $args['headers']['Authorization']="Bearer XXXXXXXXXXXX";
        awp_add_to_log( $return, $campfireUrl, $args, $integration );

    $response['success']=true;    
    return $response;
}
