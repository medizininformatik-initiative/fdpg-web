<?php

class AWP_Monday extends Appfactory
{
    public static $url = "https://api.monday.com/v2";

    public function init_actions()
    {
        add_action('admin_post_awp_monday_save_api_key', [$this, 'save_api_key'], 10, 0);
        add_action('wp_ajax_awp_fetch_boards', [$this, 'fetch_boards']);
        add_action('wp_ajax_awp_fetch_groups', [$this, 'fetch_groups']);
    }

    public function init_filters()
    {
    }

    public function settings_tab($tabs)
    {
        $tabs['monday'] = array('name' => esc_html__('Monday', 'automate_hub'), 'cat' => array('crm'));
        return $tabs;
    }

    public function load_custom_script()
    {
        wp_enqueue_script('awp-monday-script', AWP_URL . '/apps/m/monday/monday.js', array('awp-vuejs'), '', 1);
    }


    public function action_provider($providers)
    {
        $providers['monday'] = [
            'title' => __('Monday', 'automate_hub'),
            'tasks' => array(
                'create_board_item'   => __('Add item to group ', 'automate_hub')
            )
        ];

        return  $providers;
    }


    public function save_api_key()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }

        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_monday_api_key')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_key = sanitize_text_field($_POST["awp_monday_api_key"]);
        $account_name = sanitize_text_field($_POST["awp_monday_identifier"]);

        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('monday');
        $platform_obj->save_platform(['api_key' => $api_key, 'account_name' => $account_name]);
        AWP_redirect("admin.php?page=automate_hub&tab=monday");
    }

    public function fetch_boards()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('Action not allowed!', 'automate_hub'));
        }

        if (!wp_verify_nonce($_POST['_nonce'], 'automate_hub')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        if (!isset($_POST['platformid'])) {
            die(esc_html__('Invalid Request', 'automate_hub'));
        }

        $id = sanitize_text_field($_POST['platformid']);
        $platform_obj = new AWP_Platform_Shell_Table('monday');
        $data = $platform_obj->awp_get_platform_detail_by_id($id);
        if (!$data) {
            die(esc_html__('No Data Found', 'automate_hub'));
        }
        $api_key = $data->api_key;

        $query = "query { boards { id name }}";

        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $api_key,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),
            'body' => json_encode([
                'query' => $query
            ])
        );

        $response = wp_remote_post(self::$url, $args);
        $body = wp_remote_retrieve_body($response);
        $body = json_decode($body, true);
        wp_send_json_success($body);
    }


    public function fetch_groups()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('Action not allowed!', 'automate_hub'));
        }

        if (!wp_verify_nonce($_POST['_nonce'], 'automate_hub')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        if (!isset($_POST['platformid'])) {
            die(esc_html__('Invalid Request', 'automate_hub'));
        }

        if (!isset($_POST['boardId'])) {
            die(esc_html__('Invalid Request', 'automate_hub'));
        }

        $id = sanitize_text_field($_POST['platformid']);
        $platform_obj = new AWP_Platform_Shell_Table('monday');
        $data = $platform_obj->awp_get_platform_detail_by_id($id);
        if (!$data) {
            die(esc_html__('No Data Found', 'automate_hub'));
        }
        $api_key = $data->api_key;
        $board_id = sanitize_text_field($_POST['boardId']);

        $query = "query { boards(ids: {$board_id}) { groups { id title } } }";

        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $api_key,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),
            'body' => json_encode([
                'query' => $query
            ])
        );

        $response = wp_remote_post(self::$url, $args);
        $body = wp_remote_retrieve_body($response);
        $body = json_decode($body, true);
        wp_send_json_success(array_merge($body, ['boardId' => (int)$board_id]));
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'monday') {
            return;
        }

        $nonce = wp_create_nonce("awp_monday_api_key");
        $api_key = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $account_name = isset($_GET['account_name']) ? $_GET['account_name'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";


?>
        <div class="no-platformheader">
            <a href="https://sperse.io/go/monday" target="_blank"><img src="<?php echo AWP_ASSETS; ?>/images/logos/monday.png" width="180" height="50" alt="Monday Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
            ?>
            <br />
            <?php 

            $form_fields = '';
            $app_name= 'monday';
            $monday_form = new AWP_Form_Fields($app_name);

            $form_fields = $monday_form->awp_wp_text_input(
                array(
                    'id'            => "awp_monday_identifier",
                    'name'          => "awp_monday_identifier",
                    'value'         => $account_name,
                    'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
                    'label'         =>  esc_html__('Display Name', 'automate_hub' ),
                    'wrapper_class' => 'form-row',
                    'show_copy_icon'=>true
                    
                )
            );

            $form_fields .= $monday_form->awp_wp_text_input(
                array(
                    'id'            => "awp_monday_api_key",
                    'name'          => "awp_monday_api_key",
                    'value'         => $api_key,
                    'placeholder'   => esc_html__( 'Enter API Token', 'automate_hub' ),
                    'label'         =>  esc_html__( 'API Token', 'automate_hub' ),
                    'wrapper_class' => 'form-row',
                    'show_copy_icon'=>true
                    
                )
            );

            $form_fields .= $monday_form->awp_wp_hidden_input(
                array(
                    'name'          => "action",
                    'value'         => 'awp_monday_save_api_key',
                )
            );


            $form_fields .= $monday_form->awp_wp_hidden_input(
                array(
                    'name'          => "_nonce",
                    'value'         =>$nonce,
                )
            );
            $form_fields .= $monday_form->awp_wp_hidden_input(
                array(
                    'name'          => "id",
                    'value'         =>wp_unslash($id),
                )
            );


            $monday_form->render($form_fields);

            ?>
        </div>

        <div class="wrap">
            <form id="form-list" method="post">
                <input type="hidden" name="page" value="automate_hub" />
                <?php
                $data = [
                    'table-cols' => ['account_name' => 'Account Identifier', 'api_key' => 'API Key', 'active_status' => 'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('monday');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();
                ?>
            </form>
        </div>
    <?php
    }

    public function action_fields()
    {
    ?>
        <script type="text/template" id="monday-action-template">
            <?php

                    $app_data=array(
                            'app_slug'=>'monday',
                           'app_name'=>'Monday.com',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/monday.png',
                           'app_icon_alter_text'=>'Monday.com Icon',
                           'account_select_onchange'=>'getBoards',
                           'tasks'=>array(
                                        'create_board_item'=>array(
                                                            'task_assignments'=>array(

                                                                                    array(
                                                                                        'label'=>'Select Board',
                                                                                        'type'=>'select',
                                                                                        'name'=>"account_id",
                                                                                        'model'=>'fielddata.boardId',
                                                                                        'required'=>'required',
                                                                                        'onchange'=>'getGroups',
                                                                                        'select_default'=>'Select Board...',
                                                                                        'option_for_loop'=>'(item) in mondayFormData.boards',
                                                                                        'option_for_value'=>'item.id',
                                                                                        'option_for_text'=>'{{item.name}}',
                                                                                        'spinner'=>array(
                                                                                                        'bind-class'=>"{'is-active': loading}",
                                                                                                    )
                                                                                    ),
                                                                                    array(
                                                                                        'label'=>'Select Group',
                                                                                        'type'=>'select',
                                                                                        'name'=>"account_id",
                                                                                        'model'=>'fielddata.groupId',
                                                                                        'required'=>'required',
                                                                                        'onchange'=>'',
                                                                                        'select_default'=>'Select Group...',
                                                                                        'option_for_loop'=>'(item) in mondayFormData.groups',
                                                                                        'option_for_value'=>'item.id',
                                                                                        'option_for_text'=>'{{item.title}}',
                                                                                        'spinner'=>array(
                                                                                                        'bind-class'=>"{'is-active': loading}",
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
}

$Awp_Monday = AWP_Monday::get_instance();

function awp_monday_save_integration()
{
    AWP_Monday::save_integration();
}

function awp_monday_send_data($record, $posted_data)
{
    $temp = json_decode(($record["data"]), true);
    $temp = $temp["field_data"];

    $platform_obj = new AWP_Platform_Shell_Table('monday');
    $temp = $platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_key = $temp->api_key;

    $decoded_data = AWP_Monday::decode_data($record, $posted_data);

    $task = $decoded_data["task"];
    $data = $decoded_data["data"];

    if ($task == "create_board_item") {
        $item_name = empty($data["name"]) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $board_id = $data['boardId'];
        $group_id = $data['groupId'];

        $query = "mutation { create_item ( item_name: \"{$item_name}\", board_id: {$board_id}, group_id: \"{$group_id}\" ) { id name } }";
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $api_key,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),
            'body' => json_encode([
                'query' => $query
            ])
        );

        $response = wp_remote_post(AWP_Monday::$url, $args);
        $args['headers']['Authorization'] = 'XXXXXXXXXXX';
        awp_add_to_log($response, AWP_Monday::$url, $args, $record);
    }

    return $response;
}


function awp_monday_resend_data($log_id, $data, $integration)
{
    $temp = json_decode($integration["data"], true);
    $temp = $temp["field_data"];
    $platform_obj = new AWP_Platform_Shell_Table('monday');
    $temp = $platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_key = $temp->api_key;

    if (!$api_key) {
        return;
    }

    $temp = json_decode($integration["data"], true);
    $temp = $temp["field_data"];
    $list_id = $temp["listId"];

    $task = $integration['task'];
    $data = stripslashes($data);
    $data = preg_replace('/\s+/', '', $data);
    $data = json_decode($data, true);

    if ($task == "create_board_item") {

        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $api_key,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),
            'body' => json_encode($data['args']['body']),
        );

        $response = wp_remote_post(AWP_Monday::$url, $args);
        $args['headers']['Authorization'] = 'XXXXXXXXXXX';
        awp_add_to_log($response, AWP_Monday::$url, $args, $record);
    }

    $response['success'] = true;
    return $response;
}
