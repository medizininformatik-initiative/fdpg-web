<?php

class AWP_Postmark extends Appfactory
{
    public static $postmark_url = "https://api.postmarkapp.com/email";

    public function init_actions()
    {
        add_action('admin_post_awp_postmark_save_api_key', [$this, 'save_api_key'], 10, 0);
    }

    public function init_filters()
    {
    }

    public function settings_tab($tabs)
    {
        $tabs['postmark'] = array('name' => esc_html__('Postmark', 'automate_hub'), 'cat' => array('esp'));
        return $tabs;
    }

    public function load_custom_script()
    {
        wp_enqueue_script('awp-postmark-script', AWP_URL . '/apps/p/postmark/postmark.js', array('awp-vuejs'), '', 1);
    }

    public function save_api_key()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }

        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_postmark_api_key')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_key = sanitize_text_field($_POST["awp_postmark_api_key"]);
        $sender_signature = sanitize_text_field($_POST["awp_postmark_sender_signature"]);

        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('postmark');
        $platform_obj->save_platform(['api_key' => $api_key, "email" => $sender_signature]);
        AWP_redirect("admin.php?page=automate_hub&tab=postmark");
    }

    public function action_provider($providers)
    {
        $providers['postmark'] = [
            'title' => __('Postmark', 'automate_hub'),
            'tasks' => array(
                'send_email'   => __('Send an email', 'automate_hub')
            )
        ];

        return  $providers;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'postmark') {
            return;
        }

        $nonce = wp_create_nonce("awp_postmark_api_key");
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $awp_postmark_api_key = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $awp_postmark_sender_signature = isset($_GET['email']) ? $_GET['email'] : "";

?>
        <div class="no-platformheader">
            <a href="https://sperse.io/go/postmark" target="_blank"><img src="<?php echo AWP_ASSETS; ?>/images/logos/postmark.png" width="258" height="50" alt="postmark"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
            ?>
            <br />
            <?php 

$form_fields = '';
$app_name= 'postmark';
$postmark_form = new AWP_Form_Fields($app_name);

$form_fields = $postmark_form->awp_wp_text_input(
    array(
        'id'            => "awp_postmark_sender_signature",
        'name'          => "awp_postmark_sender_signature",
        'value'         => $awp_postmark_sender_signature,
        'placeholder'   =>  esc_html__('Provide Approved Sender Email', 'automate_hub' ),
        'label'         =>  esc_html__('Approved Sender Email Address', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $postmark_form->awp_wp_text_input(
    array(
        'id'            => "awp_postmark_api_key",
        'name'          => "awp_postmark_api_key",
        'value'         => $awp_postmark_api_key,
        'placeholder'   => esc_html__( 'Provide your server API token', 'automate_hub' ),
        'label'         =>  esc_html__( 'Postmark Server API Token', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $postmark_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_postmark_save_api_key',
    )
);


$form_fields .= $postmark_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $postmark_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$postmark_form->render($form_fields);

?>
        </div>

        <div class="wrap">
            <form id="form-list" method="post">


                <input type="hidden" name="page" value="automate_hub" />

                <?php
                $data = [
                    'table-cols' => ['email' => 'Sender Email','api_key' => 'API Key', 'active_status' => 'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('postmark');
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
        <script type="text/template" id="postmark-action-template">
            <?php
            $app_data=array(
                            'app_slug'=>'postmark',
                           'app_name'=>'Postmark',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/postmark.png',
                           'app_icon_alter_text'=>'Postmark',
                           'account_select_onchange'=>'',
                           'tasks'=>array(
                                        'send_email'=>array(
                                                           

                                                        ),

                                    ),
                        ); 

                        require (AWP_VIEWS.'/awp_app_integration_format.php');
                ?>
        </script>
<?php
    }
}

$Awp_Postmark = AWP_Postmark::get_instance();

function awp_postmark_save_integration()
{
    AWP_Postmark::save_integration();
}

function awp_postmark_send_data($record, $posted_data)
{
    $temp = json_decode(($record["data"]), true);
    $temp    = $temp["field_data"];
    $platform_obj = new AWP_Platform_Shell_Table('postmark');
    $temp = $platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_key = $temp->api_key;
    $awp_postmark_sender_signature = $temp->email;
    $decoded_data = AWP_postmark::decode_data($record, $posted_data);

    $task = $decoded_data["task"];
    $data = $decoded_data["data"];

    if ($task == "send_email") {
        $fields =  [];
        foreach ($data as $key => $value) {
            if (strpos($key, 'dis') !== false) {
                continue;
            }
            $fields[$key] = awp_get_parsed_values($data[$key], $posted_data);
        }
        unset($fields["activePlatformId"]);
        $fields["From"] = $awp_postmark_sender_signature;
        $fields["To"] = get_bloginfo('admin_email');

        $args = [
            'headers' => [
                'X-Postmark-Server-Token' => $api_key,
                "Content-Type" => "application/json",
                "Accept" => "application/json"
            ],
            'body' => json_encode($fields)
        ];

        $response  = wp_remote_post(AWP_postmark::$postmark_url, $args);
        $args['headers']['X-Postmark-Server-Token'] = 'XXXXXXXXXXX';
        awp_add_to_log($response, AWP_postmark::$postmark_url, $args, $record);
        return $response;
    }
}


function awp_postmark_resend_data($log_id, $data, $integration)
{
    $temp = json_decode($integration["data"], true);
    $temp = $temp["field_data"];
    $platform_obj = new AWP_Platform_Shell_Table('postmark');
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
    $url = $data['url'];
    if (!$url) {
        $response['success'] = false;
        $response['msg'] = "Syntax Error! Request is invalid";
        return $response;
    }

    if ($task == "send_email") {
        $args = [
            'headers' => [
                'X-Postmark-Server-Token' => $api_key,
                "Content-Type" => "application/json",
                "Accept" => "application/json"
            ],
            'body' => json_encode($data['args']['body']),
        ];

        $response  = wp_remote_post(AWP_postmark::$postmark_url, $args);
        $args['headers']['X-Postmark-Server-Token'] = 'XXXXXXXXXXX';
        $args['body'] = $data['args']['body'];
        awp_add_to_log($response, AWP_postmark::$postmark_url, $args, $record);
    }

    $response['success'] = true;
    return $response;
}
