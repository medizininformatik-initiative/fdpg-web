<?php

class AWP_Ontraport extends Appfactory
{

    public function init_actions()
    {
        add_action('admin_post_awp_ontraport_save_api_token', [$this, 'awp_save_ontraport_api_token'], 10, 0);
        add_action('wp_ajax_awp_get_ontraport_list', [$this, 'awp_get_ontraport_list'], 10, 0);
    }

    public function init_filters()
    {
       
    }


    public function action_provider($actions)
    {
        $actions['ontraport'] = [
            'title' => esc_html__('Ontraport', 'automate_hub'),
            'tasks' => array('subscribe' => esc_html__('Subscribe To List', 'automate_hub')),
        ];
        return $actions;
    }

    public function settings_tab($tabs)
    {
        $tabs['ontraport'] = array('name' => esc_html__('Ontraport', 'automate_hub'), 'cat' => array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'ontraport') {
            return;
        }
        $nonce = wp_create_nonce("awp_ontraport_settings");
       
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $api_token = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $app_id     = isset($_GET['client_id']) ? $_GET['client_id'] : "";
        $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/ontraport" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/ontraport.png" width="292" height="50" alt="Ontraport Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?>
                <br />
                <?php 

$form_fields = '';
$app_name= 'ontraport';
$ontraport_form = new AWP_Form_Fields($app_name);

$form_fields = $ontraport_form->awp_wp_text_input(
    array(
        'id'            => "awp_ontraport_display_name",
        'name'          => "awp_ontraport_display_name",
        'value'         => $display_name,
        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $ontraport_form->awp_wp_text_input(
    array(
        'id'            => "awp_hubspot_api_token",
        'name'          => "awp_hubspot_api_token",
        'value'         => $api_token,
        'placeholder'   => esc_html__( 'Enter your API Key', 'automate_hub' ),
        'label'         =>  esc_html__( 'Ontraport API Key', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);
$form_fields .= $ontraport_form->awp_wp_text_input(
    array(
        'id'            => "awp_ontraport_app_id",
        'name'          => "awp_ontraport_app_id",
        'value'         => $app_id,
        'placeholder'   => esc_html__( 'Enter your API App ID', 'automate_hub' ),
        'label'         =>  esc_html__( 'Ontraport App ID', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $ontraport_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_ontraport_save_api_token',
    )
);


$form_fields .= $ontraport_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $ontraport_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$ontraport_form->render($form_fields);

?>


        </div>

        <div class="wrap">
            <form id="form-list" method="post">
                          
                  
                  <input type="hidden" name="page" value="automate_hub"/>

                  <?php
                  $data=[
                              'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','client_id'=>'APP ID','spots'=>'Active Spots','active_status'=>'Active']
                      ];
                  $platform_obj= new AWP_Platform_Shell_Table('ontraport');
                  $platform_obj->initiate_table($data);
                  $platform_obj->prepare_items();
                  $platform_obj->display_table();
                          
                  ?>
            </form>
        </div>
    <?php
}

    public function awp_save_ontraport_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_ontraport_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }
        $api_token = sanitize_text_field($_POST["awp_ontraport_api_token"]);
        $app_id = sanitize_text_field($_POST["awp_ontraport_app_id"]);
        $display_name     = sanitize_text_field( $_POST["awp_ontraport_display_name"] );
        // Save tokens
        $platform_obj= new AWP_Platform_Shell_Table('ontraport');
        $platform_obj->save_platform(['account_name'=>$display_name,'api_key'=>$api_token,'client_id'=>$app_id]);
        AWP_redirect("admin.php?page=automate_hub&tab=ontraport");
    }

    public function awp_ontraport_js_fields($field_data)
    {
    }

    public function load_custom_script()
    {
        wp_enqueue_script('awp-ontraport-script', AWP_URL . '/apps/o/ontraport/ontraport.js', array('awp-vuejs'), '', 1);
    }

    public function action_fields()
    {
        ?>
    <script type="text/template" id="ontraport-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'ontraport',
                           'app_name'=>'Ontraport',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/ontraport.png',
                           'app_icon_alter_text'=>'Ontraport Icon',
                           'account_select_onchange'=>'getOntraportList',
                           'tasks'=>array(
                                        'subscribe'=>array(
                                                            'task_assignments'=>array(

                                                                                    array(
                                                                                        'label'=>'List',
                                                                                        'type'=>'select',
                                                                                        'name'=>"list_id",
                                                                                        'model'=>'data.groupList.id',
                                                                                        'required'=>'required',
                                                                                        'onchange'=>'',
                                                                                        'select_default'=>'Select Contact Group...',
                                                                                        'option_for_loop'=>'(item, index) in data.groupList',
                                                                                        'spinner'=>array(
                                                                                                        'bind-class'=>"{'is-active': listLoading}",
                                                                                                    )
                                                                                    ),
                                                                                                                                                                        
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
     * Get Ontraport contact lists
     */
    public function awp_get_ontraport_list()
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
        $platform_obj= new AWP_Platform_Shell_Table('ontraport');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }
        $api_token =$data->api_key;
        $app_id =$data->client_id;

        if (!$api_token && !$app_id) {
            return array();
        }

        $args = array(
            'headers' => array(
                'Accept' => 'application/json',
                'Api-Key' => $api_token,
                'Api-Appid' => $app_id,
            ),
        );
        $url = "https://api.ontraport.com/1/Groups";
        $data = wp_remote_request($url, $args);
        if (is_wp_error($data)) {
            wp_send_json_error();
        }
        $body = json_decode($data["body"]);
        $lists = wp_list_pluck($body->data, 'name', 'id');
        return wp_send_json_success($lists);
    }
}

$AWP_Ontraport = new AWP_Ontraport();

/*
 * Saves connection mapping
 */
function awp_ontraport_save_integration()
{
    $params = array();
    parse_str(awp_sanitize_text_or_array_field($_POST['formData']), $params);

    $trigger_data = isset($_POST["triggerData"]) ? awp_sanitize_text_or_array_field($_POST["triggerData"]) : array();
    $action_data = isset($_POST["actionData"]) ? awp_sanitize_text_or_array_field($_POST["actionData"]) : array();
    $field_data = isset($_POST["fieldData"]) ? awp_sanitize_text_or_array_field($_POST["fieldData"]) : array();

    $integration_title = isset($trigger_data["integrationTitle"]) ? $trigger_data["integrationTitle"] : "";
    $form_provider_id = isset($trigger_data["formProviderId"]) ? $trigger_data["formProviderId"] : "";
    $form_id = isset($trigger_data["formId"]) ? $trigger_data["formId"] : "";
    $form_name = isset($trigger_data["formName"]) ? $trigger_data["formName"] : "";
    $action_provider = isset($action_data["actionProviderId"]) ? $action_data["actionProviderId"] : "";
    $task = isset($action_data["task"]) ? $action_data["task"] : "";
    $type = isset($params["type"]) ? $params["type"] : "";

    $all_data = array(
        'trigger_data' => $trigger_data,
        'action_data' => $action_data,
        'field_data' => $field_data,
    );

    global $wpdb;

    $integration_table = $wpdb->prefix . 'awp_integration';

    if ($type == 'new_integration') {

        $result = $wpdb->insert(
            $integration_table,
            array(
                'title' => $integration_title,
                'form_provider' => $form_provider_id,
                'form_id' => $form_id,
                'form_name' => $form_name,
                'action_provider' => $action_provider,
                'task' => $task,
                'data' => json_encode($all_data, true),
                'status' => 1,
            )
        );

        if($result){
            $platform_obj= new AWP_Platform_Shell_Table($action_provider);
            $platform_obj->awp_add_new_spot($wpdb->insert_id,$field_data['activePlatformId']);
        }

    }

    if ($type == 'update_integration') {

        $id = esc_sql(trim($params['edit_id']));

        if ($type != 'update_integration' && !empty($id)) {
            exit;
        }

        $result = $wpdb->update($integration_table,
            array(
                'title' => $integration_title,
                'form_provider' => $form_provider_id,
                'form_id' => $form_id,
                'form_name' => $form_name,
                'data' => json_encode($all_data, true),
            ),
            array(
                'id' => $id,
            )
        );
    }

    $return=array();
    $return['type']=$type;
    $return['result']=$result;
    $return['insertid']=$wpdb->insert_id;
    return $return;
}

/*
 * Handles sending data to ontraport API
 */
function awp_ontraport_send_data($record, $posted_data)
{
    $temp = json_decode(($record["data"]), true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('ontraport');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_token=$temp->api_key;
    $app_id=$temp->client_id;

    if (!$api_token && !$app_id) {
        return;
    }

    $decoded_data = AWP_Ontraport::decode_data($record, $posted_data);
    $data = $decoded_data["data"];
    $list_id = $data["listId"];
    $task = $decoded_data["task"];

    if ($task == "subscribe") {
        $email = empty($data["email"]) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $name = empty($data["name"]) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $cf = empty($data["customFields"]) ? "" : awp_get_parsed_values($data["customFields"], $posted_data);

        if ($cf) {
            $cf = explode(",", $cf);
            $data["customFields"] = $cf;
        }

        $url = "https://api.ontraport.com/1/Contacts";

        $args = array(
            'method' => 'POST',
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Api-Key' => $api_token,
                'Api-Appid' => $app_id,
            ),
            'body' => "firstname=".$name."&email=".$email."&use_utm_names=false",
        );

        $return = wp_remote_request($url, $args);
        $args['headers']['Api-Key']='XXXXXXXXX';
        $args['headers']['Api-Appid']='XXXXXXXXX';
        awp_add_to_log($return, $url, $args, $record);
    }
    return $return;
}

function awp_ontraport_resend_data($log_id, $data, $integration)
{

    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('ontraport');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_token=$temp->api_key;
    $app_id=$temp->client_id;

    if (!$api_token && !$app_id) {
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


       
        $args = array(
            'method' => 'POST',
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Api-Key' => $api_token,
                'Api-Appid' => $app_id,
            ),
            'body' => $body,
        );

        $return = wp_remote_request($url, $args);
        $args['headers']['Api-Key']='XXXXXXXXX';
        $args['headers']['Api-Appid']='XXXXXXXXX';
        awp_add_to_log($return, $url, $args, $integration);

    $response['success']=true;    
    return $response;
}
