<?php

class AWP_Kajabi extends appfactory
{

    public function init_actions()
    {
        add_action('admin_post_awp_kajabi_save_api_token', [$this, 'awp_save_kajabi_api_token'], 10, 0);
        //add_action('wp_ajax_awp_get_kajabi_list', [$this, 'awp_get_kajabi_list'], 10, 0);
    }

    public function init_filters()
    {
    }


    public function action_provider($actions)
    {
        $actions['kajabi'] = [
            'title' => esc_html__('Kajabi', 'automate_hub'),
            'tasks' => array('create_record' => esc_html__('Purchase Offer', 'automate_hub')),
        ];
        return $actions;
    }

    public function settings_tab($tabs)
    {
        $tabs['kajabi'] = array('name' => esc_html__('Kajabi', 'automate_hub'), 'cat' => array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'kajabi') {
            return;
        }
        $nonce = wp_create_nonce("awp_kajabi_settings");
        $api_key = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";


        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/kajabi" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/kajabi.png" width="275" height="50" alt="Kajabi Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?>
                <br />

            <?php 

                $form_fields = '';
                $app_name= 'kajabi';
                $kajabi_form = new AWP_Form_Fields($app_name);

                $form_fields = $kajabi_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_kajabi_display_name",
                        'name'          => "awp_kajabi_display_name",
                        'value'         => $display_name,
                        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
                        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $kajabi_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_kajabi_act_url",
                        'name'          => "awp_kajabi_act_url",
                        'value'         => $api_key,
                        'placeholder'   => esc_html__( 'Enter your kajabi API Key', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Kajabi Activation URL', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'data_type'=>'url',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $kajabi_form->awp_wp_hidden_input(
                    array(
                        'name'          => "action",
                        'value'         => 'awp_kajabi_save_api_token',
                    )
                );


                $form_fields .= $kajabi_form->awp_wp_hidden_input(
                    array(
                        'name'          => "_nonce",
                        'value'         =>$nonce,
                    )
                );
                $form_fields .= $kajabi_form->awp_wp_hidden_input(
                    array(
                        'name'          => "id",
                        'value'         =>wp_unslash($id),
                    )
                );


                $kajabi_form->render($form_fields);

                ?>


        </div>


        <div class="wrap">
                <form id="form-list" method="post">
                    
         
                    <input type="hidden" name="page" value="automate_hub"/>

                    <?php
                    $data=[
                        'table-cols'=>['account_name'=>'Display name','api_key'=>'Activation Webhook URL','spots'=>'Active Spots','active_status'=>'Active']
                    ];
                    $platform_obj= new AWP_Platform_Shell_Table('kajabi');
                    $platform_obj->initiate_table($data);
                    $platform_obj->prepare_items();
                    $platform_obj->display_table();
                    
                    ?>
                </form>
        </div>
    <?php
}

    public function awp_save_kajabi_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_kajabi_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }
        $api_token = sanitize_text_field($_POST["awp_kajabi_act_url"]);
        $display_name     = sanitize_text_field( $_POST["awp_kajabi_display_name"] );
        // Save tokens
        $platform_obj= new AWP_Platform_Shell_Table('kajabi');
        $platform_obj->save_platform(['account_name'=>$display_name,'api_key'=>$api_token]);

        AWP_redirect("admin.php?page=automate_hub&tab=kajabi");
    }

    public function awp_kajabi_js_fields($field_data)
    {
    }

    public function load_custom_script()
    {
        wp_enqueue_script('awp-kajabi-script', AWP_URL . '/apps/k/kajabi/kajabi.js', array('awp-vuejs'), '', 1);
    }

    public function action_fields()
    {
        ?>
    <script type="text/template" id="kajabi-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'kajabi',
                           'app_name'=>'Kajabi',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/kajabi.png',
                           'app_icon_alter_text'=>'Kajabi Icon',
                           'account_select_onchange'=>'',
                           'tasks'=>array(
				                'create_record'=>array(
				                                    'task_assignments'=>array(

				                                                            
				                                                        ),

				                                ),

				            ),
                        ); 

                        require (AWP_VIEWS.'/awp_app_integration_format.php');
                ?>
    </script>
<?php
}

    /*
     * Get kajabi contact lists
     */
    public function awp_get_kajabi_list()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'automate_hub')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        if (!isset( $_POST['platformid'] ) ) {
            die( esc_html__( 'Invalid Request', 'automate_hub' ) );
        }
        $id=sanitize_text_field($_POST['platformid']);
        $platform_obj= new AWP_Platform_Shell_Table('kajabi');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;

        if (!$api_key) {
            return array();
        }

        //krishna code

     //    $request_data = array(
	    //     "api_action" => "list_list",
	    //     "api_key"    => $api_key,
	    //     "api_output" => "json",
	    //     "ids"        => "all"
	    // );
	    // $query = http_build_query( $request_data );
	    // $url   = "{$url}/admin/api.php?{$query}";
	    //   $headers = array(
	    //     'Content-Type:application/json',
	    //     );

	    // $curl_response = awp_remote_post($url,$headers,$query);
	    // $response = !empty($curl_response['response']) ? $curl_response['response'] : array();
	    // $response3 = json_decode($response,true);
	    // $lists = array();
	    // if(!empty($response3)){
	    //     foreach ($response3 as $key => $list) {
	    //         if(!empty($list['id'])){
	    //             $lists[$list['id']] = $list['name'];
	    //         }
	    //     }
	    // }

	    // echo json_encode($lists);




        // sample request in wordpress

        // $args = array(
        //     'headers' => array(
        //         'Content-Type' => 'application/json',
        //     ),
        // );

        // $url = "https://api.kajabi.com/v3/marketing/lists";

        // $data = wp_remote_request($url, $args);
        // if (is_wp_error($data)) {
        //     wp_send_json_error();
        // }

        // $body = json_decode($data["body"]);
        // $lists = wp_list_pluck($body->result, 'name', 'id');

        // wp_send_json_success($lists);

        /* 
        ** could not find the use of this function so this is pending
        */
    }
}

$AWP_kajabi = new AWP_kajabi();

/*
 * Saves connection mapping
 */
function awp_kajabi_save_integration() {
    Appfactory::save_integration();
}

/*
 * Handles sending data to kajabi API
 */
function awp_kajabi_send_data($record, $posted_data)
{

    $temp = json_decode(($record["data"]), true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('kajabi');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $act_url=$temp->api_key;

    if (!$act_url) {
        return;
    }

    $data    = json_decode( $record["data"], true );
    $data    = $data["field_data"];
    $task    = $record["task"];
    $field_data = $record_data["field_data"];


    if( $task == "create_record" ) {
            
            $first_name = empty( $data["name"] ) ? "" : awp_get_parsed_values($data["name"], $posted_data);
            $email = empty( $data["email"] ) ? "" : awp_get_parsed_values($data["email"], $posted_data);
            $external_user_id = empty( $data["external_user_id"] ) ? "" : awp_get_parsed_values($data["external_user_id"], $posted_data);


            $data = array(
                'name'=>$first_name,
                'email'=>$email,
                'external_user_id'=>$external_user_id,
            );

            $queryString =http_build_query($data);
            $endpoint =$act_url.'?'.$queryString;

	        $return = wp_remote_request($endpoint);
	        $args['body'] = $data;
	        awp_add_to_log($return, $act_url, $args, $record);
        
    }

    return $return;
}

// function awp_kajabi_resend_data($log_id, $data, $integration)
// {

//     $temp    = json_decode( $integration["data"], true );
//     $temp    = $temp["field_data"];
//     $platform_obj= new AWP_Platform_Shell_Table('kajabi');
//     $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
//     $api_token=$temp->api_key;
    
//     if (!$api_token) {
//         return;
//     }

//     $temp = json_decode($integration["data"], true);
//     $temp = $temp["field_data"];
//     $list_id = $temp["listId"];

//     $task = $integration['task'];
//     $data = stripslashes($data);
//     $data = preg_replace('/\s+/', '', $data);
//     $data = json_decode($data, true);
//     $url = $data['url'];
//     if (!$url) {
//         $response['success'] = false;
//         $response['msg'] = "Syntax Error! Request is invalid";
//         return $response;
//     }

//     if ($task == "subscribe") {

//         $url = "https://api.kajabi.com/v3/marketing/contacts";

//         $args = array(

//             'headers' => array(
//                 'Content-Type' => 'application/json',
//                 'authorization' => 'Bearer ' . $api_token,
//             ),
//             'body' => json_encode($data['args']['body']),
//         );

//         $return = wp_remote_post($url, $args);
//         $args['body'] = $data['args']['body'];
//         $args['headers']['authorization']='Bearer  XXXXXXXXXXX';
//         awp_add_to_log($return, $url, $args, $integration);
//     }

//     $response['success'] = true;
//     return $response;
// }
