<?php

class AWP_Chargebee extends appfactory
{

   
    public function init_actions(){
       
        add_action('admin_post_awp_chargebee_save_api_token', [$this, 'awp_save_chargebee_api_token'], 10, 0);
        
        add_action( 'wp_ajax_awp_fetch_itemfamily_name', [$this, 'awp_fetch_itemfamily_name']);
      
    }

    public function init_filters(){

        add_filter('awp_platforms_connections', [$this, 'awp_chargebee_platform_connection'], 10, 1);
    }

    public function action_provider( $providers ) {
        $providers['chargebee'] = array(
            'title' => esc_html__( 'Chargebee', 'automate_hub' ),
            'tasks' => array(
                'createcustomer'   => esc_html__( 'Create Customer', 'automate_hub' ),
                'createitemfamily'   => esc_html__( 'Create Item Family', 'automate_hub' ),
                'createitem'   => esc_html__( 'Create Item', 'automate_hub' )
            )
        );
    
        return  $providers;
    }

    public function settings_tab( $tabs ) {
        $tabs['chargebee'] = array('name'=>esc_html__( 'Chargebee', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'chargebee') {
            return;
        }
        $nonce = wp_create_nonce("awp_chargebee_settings");
        $api_token = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/chargebee" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/chargebee.png" height="50" alt="Chargebee Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?>

                <br />
                <?php 

$form_fields = '';
$app_name= 'chargebee';
$chargebee_form = new AWP_Form_Fields($app_name);

$form_fields = $chargebee_form->awp_wp_text_input(
    array(
        'id'            => "awp_chargebee_display_name",
        'name'          => "awp_chargebee_display_name",
        'value'         => $display_name,
        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $chargebee_form->awp_wp_text_input(
    array(
        'id'            => "awp_chargebee_api_token",
        'name'          => "awp_chargebee_api_token",
        'value'         => $api_token,
        'placeholder'   => esc_html__( 'Enter your Chargebee API Key', 'automate_hub' ),
        'label'         =>  esc_html__( 'Chargebee API Key', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $chargebee_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_chargebee_save_api_token',
    )
);


$form_fields .= $chargebee_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $chargebee_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$chargebee_form->render($form_fields);

?>
        </div>

        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                 $data=[
                    'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('chargebee');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();

                ?>
                        </form>
                </div>
        <?php
    }

    public function awp_save_chargebee_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_chargebee_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_token = sanitize_text_field($_POST["awp_chargebee_api_token"]);
        $display_name     = sanitize_text_field( $_POST["awp_chargebee_display_name"] );

        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('chargebee');
        $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_token]);

        AWP_redirect("admin.php?page=automate_hub&tab=chargebee");
    } 
    
    public function load_custom_script() {
        wp_enqueue_script( 'awp-chargebee-script', AWP_URL . '/apps/c/chargebee/chargebee.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="chargebee-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'chargebee',
                           'app_name'=>'Chargebee ',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/chargebee.png',
                           'app_icon_alter_text'=>'Chargebee  Icon',
                           'account_select_onchange'=>'getplantype',
                           'tasks'=>array(
                                'createitem'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Item Plan Type',
                                            'type'=>'select',
                                            'name'=>"plan_id",
                                            'required' => 'required',
                                            'onchange' => 'getItemfamilyList',
                                            'option_for_loop'=>'(item) in data.plantypelist',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Item Plan Type...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': listLoading}",
                                                        )
                                        ),
                                        array(
                                            'label'=>'Select Item Family Name',
                                            'type'=>'select',
                                            'name'=>"family_id",
                                            'required' => 'required',
                                            'onchange' => 'selectedfamily',
                                            'option_for_loop'=>'(item) in data.itemfamilylist',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Item Family Name...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': itemfamilyloading}",
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

    
    public function awp_fetch_itemfamily_name() {
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
        $platform_obj= new AWP_Platform_Shell_Table('chargebee');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }
        $domain_name = $data->account_name;
        $api_key =$data->api_key;

        $url = "https://".$domain_name.".chargebee.com/api/v2/item_families";
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic '.base64_encode($api_key)
            )
        );
        
        $filteredarray = [];
        $response = wp_remote_get( $url, $args );

        $body = wp_remote_retrieve_body( $response );
        $body = json_decode( $body, true );

        foreach($body['list'] as $key){
            foreach($key as $data){
                array_push($filteredarray, $data);
            }
        }
        wp_send_json_success($filteredarray);
    }

   
};


$Awp_Chargebee = new AWP_Chargebee();

function awp_chargebee_save_integration() {
    Appfactory::save_integration();
}

function awp_chargebee_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('chargebee');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $domain_name = $temp->account_name;
    $task = $decoded_data["task"]; 

    $token = 'Basic '.base64_encode($api_key);

    if( $task == "createcustomer" ) {

        $first_name = empty( $data["first_name"] ) ? "" : awp_get_parsed_values($data["first_name"], $posted_data);
        $last_name = empty( $data["last_name"] ) ? "" : awp_get_parsed_values($data["last_name"], $posted_data);
        $email = empty( $data["email"] ) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $line = empty( $data["line"] ) ? "" : awp_get_parsed_values($data["line"], $posted_data);
        $city = empty( $data["city"] ) ? "" : awp_get_parsed_values($data["city"], $posted_data);
        $state = empty( $data["state"] ) ? "" : awp_get_parsed_values($data["state"], $posted_data);
        $zip = empty( $data["zip"] ) ? "" : awp_get_parsed_values($data["zip"], $posted_data);
        $country = empty( $data["country"] ) ? "" : awp_get_parsed_values($data["country"], $posted_data);

        $body = [
            "first_name"=>$first_name,
            "last_name"=>$last_name,
            "billing_address" => [
                'first_name'=>$first_name,
                'last_name'=>$last_name,
                'line1'=>$line,
                'city'=>$city,
                'state'=>$state,
                'zip'=>$zip,
                'country'=>$country
            ],
            "email"=>$email
        ];

        $url = "https://".$domain_name.".chargebee.com/api/v2/customers";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => $token,
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } else if($task == "createitem"){

        $name = empty( $data["itemname"] ) ? "" : awp_get_parsed_values($data["itemname"], $posted_data);
        $type = empty( $data["plan_id"] ) ? "" : awp_get_parsed_values($data["plan_id"], $posted_data);
        $item_family_id = empty( $data["family_id"] ) ? "" : awp_get_parsed_values($data["family_id"], $posted_data);
        $uniqueitemid = "PID".mt_rand(000210, 999999);

        if($type == "plan"){
            $body = [
                "name"=>$name,
                "id"=>$uniqueitemid,
                "type"=>$type,
                "item_applicability"=>"all",
                "item_family_id"=>$item_family_id
            ];
            
        } else{
            $body = [
                "name"=>$name,
                "id"=>$uniqueitemid,
                "type"=>$type,
                "item_family_id"=>$item_family_id
            ];
        }
        
        
        $url = "https://".$domain_name.".chargebee.com/api/v2/items";
        $args = [
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => $token
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    }else if($task == "createitemfamily"){

        $familyname = empty( $data["itemfamilyname"] ) ? "" : awp_get_parsed_values($data["itemfamilyname"], $posted_data);
        $description = empty( $data["description"] ) ? "" : awp_get_parsed_values($data["description"], $posted_data);
        $uniqueitemfamilyid = "PID".mt_rand(000210, 999999);

        $body = [
            "name"=>$familyname,
            "id"=>$uniqueitemfamilyid,
            "description"=>$description
        ];
        
        $url = "https://".$domain_name.".chargebee.com/api/v2/item_families";
        $args = [
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => $token
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    }
    return $response;
}


function awp_chargebee_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('chargebee');
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

        $token = 'Basic '.base64_encode($api_key);


        $args = [
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => $token
            ),

            'body'=> json_encode($body)
        ];


        $return  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="Basic XXXXXXXXXXXX";
        awp_add_to_log( $return, $url, $args, $integration );

    $response['success']=true;    
    return $response;
}
