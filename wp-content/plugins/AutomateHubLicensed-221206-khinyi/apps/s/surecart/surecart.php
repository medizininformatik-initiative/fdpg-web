<?php
class AWP_Surecart extends appfactory
{

    public function init_actions()
    {
        add_action('admin_post_awp_surecart_save_api_token', [$this, 'awp_save_surecart_api_token'], 10, 0);
    }

    public function init_filters()
    {
        add_filter('awp_platforms_connections', [$this, 'awp_surecart_platform_connection'], 10, 1);
    }

    public function action_provider($actions)
    {
        $actions['surecart'] = [
            'title' => esc_html__('Surecart', 'automate_hub'),
            'tasks' => array('createcustomer' => esc_html__('Create Customer', 'automate_hub')),
        ];
        return $actions;
    }

    public function settings_tab($tabs)
    {
        $tabs['surecart'] = array('name' => esc_html__('Surecart', 'automate_hub'), 'cat' => array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'surecart') {
            return;
        }
        $nonce = wp_create_nonce("awp_surecart_settings");
        $api_key = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $display_name = isset($_GET['account_name']) ? $_GET['account_name'] : "";

        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/surecart" target="_blank"><img src="<?=AWP_ASSETS_REMOTE;?>/images/logos/surecart.png"  height="50" alt="Surecart Logo"></a><br /><br />
            <?php
require_once AWP_INCLUDES . '/class_awp_updates_manager.php';
        $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
        $instruction_obj->prepare_instructions();

        ?>
                <br />
                <?php

        $form_fields = '';
        $app_name = 'surecart';
        $surecart_form = new AWP_Form_Fields($app_name);

        $form_fields = $surecart_form->awp_wp_text_input(
            array(
                'id' => "awp_surecart_display_name",
                'name' => "awp_surecart_display_name",
                'value' => $display_name,
                'placeholder' => esc_html__('Enter an identifier for this account', 'automate_hub'),
                'label' => esc_html__('Display Name', 'automate_hub'),
                'wrapper_class' => 'form-row',
                'show_copy_icon' => true,

            )
        );

        $form_fields .= $surecart_form->awp_wp_text_input(
            array(
                'id' => "awp_surecart_api_token",
                'name' => "awp_surecart_api_token",
                'value' => $api_key,
                'placeholder' => esc_html__('Enter your Surecart API Key', 'automate_hub'),
                'label' => esc_html__('Surecart API Key', 'automate_hub'),
                'wrapper_class' => 'form-row',
                'show_copy_icon' => true,

            )
        );

        $form_fields .= $surecart_form->awp_wp_hidden_input(
            array(
                'name' => "action",
                'value' => 'awp_surecart_save_api_token',
            )
        );

        $form_fields .= $surecart_form->awp_wp_hidden_input(
            array(
                'name' => "_nonce",
                'value' => $nonce,
            )
        );
        $form_fields .= $surecart_form->awp_wp_hidden_input(
            array(
                'name' => "id",
                'value' => wp_unslash($id),
            )
        );

        $surecart_form->render($form_fields);
        ?>
        </div>


        <div class="wrap">
                <form id="form-list" method="post">
                    <input type="hidden" name="page" value="automate_hub"/>

                    <?php
$data = [
            'table-cols' => ['account_name' => 'Display name', 'api_key' => 'API Key', 'spots' => 'Active Spots', 'active_status' => 'Active'],
        ];
        $platform_obj = new AWP_Platform_Shell_Table('surecart');
        $platform_obj->initiate_table($data);
        $platform_obj->prepare_items();
        $platform_obj->display_table();

        ?>
                </form>
        </div>
    <?php
}

    public function awp_save_surecart_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_surecart_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }
        $api_token = sanitize_text_field($_POST["awp_surecart_api_token"]);
        $display_name = sanitize_text_field($_POST["awp_surecart_display_name"]);
        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('surecart');
        $platform_obj->save_platform(['account_name' => $display_name, 'api_key' => $api_token]);

        AWP_redirect("admin.php?page=automate_hub&tab=surecart");
    }

    public function load_custom_script()
    {
        wp_enqueue_script('awp-surecart-script', AWP_URL . '/apps/s/surecart/surecart.js', array('awp-vuejs'), '', 1);
    }

    public function action_fields()
    {
        ?>
    <script type="text/template" id="surecart-action-template">
                <?php

                $app_data=array(
                           'app_slug'=>'surecart',
                           'app_name'=>'Surecart ',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/surecart.png',
                           'app_icon_alter_text'=>'Surecart  Icon',
                           'account_select_onchange'=>'',
                           'tasks'=>array(),
                    ); 

                        require (AWP_VIEWS.'/awp_app_integration_format.php');
        ?>
    </script>
<?php
}

}

$AWP_Surecart = new AWP_Surecart();

/*
 * Saves connection mapping
 */
function awp_surecart_save_integration()
{
    Appfactory::save_integration();
}

/*
 * Handles sending data to surecart API
 */
function awp_surecart_send_data($record, $posted_data)
{
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('surecart');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    
    $api_key= $temp->api_key;
    

    $task = $decoded_data["task"]; 
    
    $token = 'Bearer '.$api_key;

    if ($task == "createcustomer") {

        $email = empty($data["email"]) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $name = empty($data["name"]) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $phone = empty($data["phone"]) ? "" : awp_get_parsed_values($data["phone"], $posted_data);
        $country = empty($data['country']) ? "" : awp_get_parsed_values($data['country'], $posted_data);
        $state = empty($data['state']) ? "" : awp_get_parsed_values($data['state'], $posted_data);
        $city = empty($data['city']) ? "" : awp_get_parsed_values($data['city'], $posted_data);
        $postal_code = empty($data['postal_code']) ? "" : awp_get_parsed_values($data['postal_code'], $posted_data);
        $line_1 = empty($data['line_1']) ? "" : awp_get_parsed_values($data['line_1'], $posted_data);

        $body = json_encode([
            "name"=>$name,
            "email"=>$email,
            "phone"=>$phone,
            "shipping_address" => [
                "country" => $country,
                "state" => $state,
                "city" => $city,
                "postal_code" => $postal_code,
                "line_1" => $line_1
            ]
        ]);

        $url = "https://api.surecart.com/v1/customers";

         $args = [
            'headers' => array(
                "Accept" =>"application/json", 
                "Content-Type" => "application/json",
                'Authorization' => $token,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    }
    return $response;
}

function awp_surecart_resend_data($log_id, $data, $integration)
{
    $temp = json_decode($integration["data"], true);
    $temp = $temp["field_data"];
    $platform_obj = new AWP_Platform_Shell_Table('surecart');
    $temp = $platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_token = $temp->api_key;

    if (!$api_token) {
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

    if ($task == "createcontact") {
        $response = wp_remote_post($url);
        awp_add_to_log($response, $url, $args, $integration);
    }

    $response['success'] = true;
    return $response;
}
