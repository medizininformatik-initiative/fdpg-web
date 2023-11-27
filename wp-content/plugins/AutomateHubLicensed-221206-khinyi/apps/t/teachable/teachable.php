<?php

class AWP_Teachable extends appfactory
{

    public function init_actions()
    {
        add_action('admin_post_awp_teachable_save_api_token', [$this, 'awp_save_teachable_api_token'], 10, 0);
        add_action('wp_ajax_awp_get_teachable_list', [$this, 'awp_get_teachable_list'], 10, 0);
    }

    public function init_filters()
    {
        add_filter('awp_platforms_connections', [$this, 'awp_teachable_platform_connection'], 10, 1);
    }

    public function action_provider($actions)
    {
        $actions['teachable'] = [
            'title' => esc_html__('Teachable', 'automate_hub'),
            'tasks' => array('subscribe' => esc_html__('Add User to School', 'automate_hub')),
        ];
        return $actions;
    }

    public function settings_tab($tabs)
    {
        $tabs['teachable'] = array('name' => esc_html__('Teachable', 'automate_hub'), 'cat' => array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'teachable') {
            return;
        }
        $nonce = wp_create_nonce("awp_teachable_settings");
        $school_domain = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $user_email = isset($_GET['email']) ? $_GET['email'] : "";
        $user_password = isset($_GET['client_secret']) ? $_GET['client_secret'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/teachable" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/teachable.png" width="275" height="50" alt="Teachable Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
            ?><br />
          <?php 

$form_fields = '';
$app_name= 'teachable';
$teachable_form = new AWP_Form_Fields($app_name);

$form_fields = $teachable_form->awp_wp_text_input(
    array(
        'id'            => "awp_teachable_school_domain",
        'name'          => "awp_teachable_school_domain",
        'value'         => $school_domain,
        'placeholder'   =>  esc_html__('Enter your Teachable School Domain Name', 'automate_hub' ),
        'label'         =>  esc_html__('Teachable School Domain', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $teachable_form->awp_wp_text_input(
    array(
        'id'            => "awp_teachable_login_email",
        'name'          => "awp_teachable_login_email",
        'value'         => $user_email,
        'placeholder'   => esc_html__( 'Enter your Teachable Email Address', 'automate_hub' ),
        'label'         =>  esc_html__( 'Teachable Login Email', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);
$form_fields .= $teachable_form->awp_wp_text_input(
    array(
        'id'            => "awp_teachable_login_password",
        'name'          => "awp_teachable_login_password",
        'value'         => $user_password,
        'placeholder'   => esc_html__( 'Enter correct Teachable Login Password', 'automate_hub' ),
        'label'         =>  esc_html__( 'Teachable Login Password', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $teachable_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_teachable_save_api_token',
    )
);


$form_fields .= $teachable_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $teachable_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$teachable_form->render($form_fields);

?>
        </div>


        <div class="wrap">
                <form id="form-list" method="post">


                    <input type="hidden" name="page" value="automate_hub"/>

                    <?php
$data = [
            'table-cols' => ['api_key' => 'School Domain Name', 'email' => 'Client Email', 'active_status' => 'Active'],
        ];
        $platform_obj = new AWP_Platform_Shell_Table('teachable');
        $platform_obj->initiate_table($data);
        $platform_obj->prepare_items();
        $platform_obj->display_table();

        ?>
                </form>
        </div>
    <?php
}

    public function awp_save_teachable_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_teachable_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }
        $school_domain = sanitize_text_field($_POST["awp_teachable_school_domain"]);
        $user_email = sanitize_text_field($_POST["awp_teachable_login_email"]);
        $user_password = sanitize_text_field($_POST["awp_teachable_login_password"]);
        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('teachable');
        $platform_obj->save_platform(['api_key' => $school_domain, 'email' => $user_email, 'client_secret' => $user_password]);

        AWP_redirect("admin.php?page=automate_hub&tab=teachable");
    }

    public function awp_teachable_js_fields($field_data)
    {
    }

    public function load_custom_script()
    {
        wp_enqueue_script('awp-teachable-script', AWP_URL . '/apps/t/teachable/teachable.js', array('awp-vuejs'), '', 1);
    }

    public function action_fields()
    {
        ?>
        <script type="text/template" id="teachable-action-template">
            <?php

                    $app_data=array(
                            'app_slug'=>'teachable',
                           'app_name'=>'Teachable',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/teachable.png',
                           'app_icon_alter_text'=>'Teachable Icon',
                           'account_select_onchange'=>'',
                           'tasks'=>array(
                                        'subscribe'=>array(
                                                            

                                                        ),

                                    ),
                        ); 

                        require (AWP_VIEWS.'/awp_app_integration_format.php');
                ?>
            
    </script>
<?php
}

}

$AWP_Teachable = new AWP_Teachable();

/*
 * Saves connection mapping
 */
function awp_teachable_save_integration()
{
    Appfactory::save_integration();
}

/*
 * Handles sending data to teachable API
 */
function awp_teachable_send_data($record, $posted_data)
{

    $temp = json_decode(($record["data"]), true);
    $temp = $temp["field_data"];
    $platform_obj = new AWP_Platform_Shell_Table('teachable');
    $temp = $platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $school_domain = $temp->api_key;
    $client_email = $temp->email;
    $client_password = $temp->client_secret;

    if (!$school_domain) {
        return;
    }

    $decoded_data = AWP_Teachable::decode_data($record, $posted_data);
    $data = $decoded_data["data"];
    $task = $decoded_data["task"];

    if ($task == "subscribe") {
        $email = empty($data["email"]) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $name = empty($data["name"]) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $cf = empty($data["customFields"]) ? "" : awp_get_parsed_values($data["customFields"], $posted_data);

        $data = array(
            'email' => $email,
            'name' => $name,
        );

        if ($cf) {
            $cf = explode(",", $cf);
            $data["customFields"] = $cf;
        }

        $url = "https://" . $school_domain . "/api/v1/users";

        $args = array(
            'method' => 'POST',
            'headers' => array(
                'Content-Type' => 'application/json',
                'authorization' => 'Basic ' . base64_encode($client_email . ':' . $client_password),
            ),
            'body' => json_encode($data),
        );

        $return = wp_remote_request($url, $args);
        $args['headers']['authorization']='Basic XXXXXXXXXXX';
        $args['body'] = $data;
        awp_add_to_log($return, $url, $args, $record);
    }
    return $return;
}

function awp_teachable_resend_data($log_id, $data, $integration)
{

    $temp = json_decode($integration["data"], true);
    $temp = $temp["field_data"];
    $platform_obj = new AWP_Platform_Shell_Table('teachable');
    $temp = $platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $school_domain = $temp->api_key;
    $client_email = $temp->email;
    $client_password = $temp->client_secret;

    if (!$school_domain) {
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

    if ($task == "subscribe") {

        $url = "https://" . $school_domain . "/api/v1/users";

        $args = array(
            'method' => 'POST',
            'headers' => array(
                'Content-Type' => 'application/json',
                'authorization' => 'Basic ' . base64_encode($client_email . ':' . $client_password),
            ),
            'body' => json_encode($data['args']['body']),
        );

        $return = wp_remote_post($url, $args);
        $args['headers']['authorization']='Basic XXXXXXXXXXX';
        $args['body'] = $data['args']['body'];
        awp_add_to_log($return, $url, $args, $integration);
    }

    $response['success'] = true;
    return $response;
}
