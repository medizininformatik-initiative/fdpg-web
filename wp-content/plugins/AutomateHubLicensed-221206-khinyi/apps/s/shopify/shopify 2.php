<?php

class AWP_Shopify extends appfactory
{

    // replace with working api_key and shared_secret_key
    const api_key = "cff060bbaf43e54de4bd59aaeeed38ac";

    protected function get_redirect_uri()
    {
        return 'https://sperse.io/scripts/authorization/auth.php';
    }

    public function init_actions()
    {
        add_action('admin_post_awp_shopify_save_api_token', [$this, 'awp_save_shopify_api_token'], 10, 0);
        add_action("rest_api_init", [$this, "create_webhook_route"]);
    }

    public function init_filters()
    {
        add_filter('awp_platforms_connections', [$this, 'awp_shopify_platform_connection'], 10, 1);
    }

    public function get_callback()
    {
        return get_rest_url(null,'automatehub/shopify');
     
    }

    public function getLoginURL($url)
    {
        $query = [
            'type' => "web_server",
            'client_id' => self::api_key,
            'scope' => "read_customers,write_customers,write_products,read_products",
            'redirect_uri' => urlencode($this->get_redirect_uri()),
            'state' => $this->get_callback(),
            'domain' => $url,
        ];
        $authorization_endpoint = "https://" . $url . "/admin/oauth/authorize?";
        return add_query_arg($query, $authorization_endpoint);
    }

    public function create_webhook_route()
    {
        register_rest_route('automatehub', '/shopify',
            [
                'methods' => 'GET',
                'callback' => [$this, 'get_webhook_data'],
                'permission_callback' => function () {return '';},
            ]);
    }

    public function get_webhook_data($request)
    {
        global $wpdb;
        $params = $request->get_params();
        if ( isset( $params['access_token'] ) ) {
            $client_accessToken = sanitize_text_field($params['access_token']);
            $shop = sanitize_text_field($params['shop']);
            $platform_obj = new AWP_Platform_Shell_Table('shopify');
            $platform_obj->save_platform(['url' => $shop, 'api_key' => $client_accessToken]);
      
        }

        wp_safe_redirect( admin_url( 'admin.php?page=automate_hub&tab=shopify' ) );
        exit();
    }

    public function action_provider($actions)
    {
        $actions['shopify'] = [
            'title' => esc_html__('Shopify', 'automate_hub'),
            'tasks' => array('subscribe' => esc_html__('Create Customer', 'automate_hub'), 'add_product' => esc_html__('Create Product', 'automate_hub')),
        ];
        return $actions;
    }

    public function settings_tab($tabs)
    {
        $tabs['shopify'] = array('name' => esc_html__('Shopify', 'automate_hub'), 'cat' => array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'shopify') {
            return;
        }
        $nonce = wp_create_nonce("awp_shopify_settings");
        $store_domain = isset($_GET['url']) ? $_GET['url'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/shopify" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/shopify.png" width="175" height="50" alt="Shopify Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
            ?><br />
            <?php 

$form_fields = '';
$app_name= 'shopify';
$shopify_form = new AWP_Form_Fields($app_name);

$form_fields = $shopify_form->awp_wp_text_input(
    array(
        'id'            => "awp_shopify_store_domain",
        'name'          => "awp_shopify_store_domain",
        'value'         => $store_domain,
        'placeholder'   =>  esc_html__('exampleshop.myshopify.com', 'automate_hub' ),
        'label'         =>  esc_html__('Store Domain Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);



$form_fields .= $shopify_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_shopify_save_api_token',
    )
);


$form_fields .= $shopify_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $shopify_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$shopify_form->render($form_fields);

?>
        </div>

        <div class="wrap">
                <form id="form-list" method="post">
                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
$data = [
            'table-cols' => ['url' => 'Store Domain', 'api_key' => 'API Key', 'active_status' => 'Active'],
        ];
        $platform_obj = new AWP_Platform_Shell_Table('shopify');
        $platform_obj->initiate_table($data);
        $platform_obj->prepare_items();
        $platform_obj->display_table();
        ?>
                </form>
        </div>
    <?php
}

    public function awp_save_shopify_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }

        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_shopify_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $store_domain = sanitize_text_field($_POST["awp_shopify_store_domain"]);
        if ($store_domain != "") {
            $url_regexPattern = '/\A[a-zA-Z0-9][a-zA-Z0-9\-]*\.myshopify\.com\z/';
            if (preg_match($url_regexPattern, $store_domain) == 1) {
                AWP_redirect($this->getLoginURL($store_domain));
            } else {
                wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=shopify'));
                exit();
            }
        } else {
            wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=shopify'));
            exit();
        }
    }

    public function load_custom_script()
    {
        wp_enqueue_script('awp-shopify-script', AWP_URL . '/apps/s/shopify/shopify.js', array('awp-vuejs'), '', 1);
    }

    public function action_fields()
    {
        ?>
        <script type="text/template" id="shopify-action-template">
            <?php
            $app_data=array(
                            'app_slug'=>'shopify',
                           'app_name'=>'Shopify',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/shopify.png',
                           'app_icon_alter_text'=>'Shopify',
                           'account_select_onchange'=>'',
                           'tasks'=>array(

                                        'subscribe'=>array(
                                                           

                                                        ),
                                        'add_product'=>array(
                                                           

                                                        ),

                                    ),
                        ); 

                        require (AWP_VIEWS.'/awp_app_integration_format.php');
                ?>
    </script>
<?php
}

}

$AWP_Shopify = new AWP_Shopify();

/*
 * Saves connection mapping
 */
function awp_shopify_save_integration()
{
    Appfactory::save_integration();
}

/*
 * Handles sending data to shopify API
 */
function awp_shopify_send_data($record, $posted_data)
{
    $temp = json_decode(($record["data"]), true);
    $temp = $temp["field_data"];
    $platform_obj = new AWP_Platform_Shell_Table('shopify');
    $temp = $platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $access_token = $temp->api_key;
    $store_url = $temp->url;

    if (!$access_token) {
        return;
    }

    $decoded_data = AWP_shopify::decode_data($record, $posted_data);
    $data = $decoded_data["data"];
    $task = $decoded_data["task"];

    if ($task == "subscribe") {
        $email = empty($data["email"]) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $name = empty($data["name"]) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $phoneNumber = empty($data["phoneNumber"]) ? "" : awp_get_parsed_values($data["phoneNumber"], $posted_data);
        $cf = empty($data["customFields"]) ? "" : awp_get_parsed_values($data["customFields"], $posted_data);
        $data = [
            'customer' => [
                'first_name' => $name,
                'email' => $email,
                'phone' => $phoneNumber,
            ],
        ];
        if ($cf) {
            $cf = explode(",", $cf);
            $data["customFields"] = $cf;
        }
        $url = "https://" . $store_url . "/admin/api/2022-01/customers.json";
        $args = array(
            'method' => 'POST',
            'headers' => array(
                'Content-Type' => 'application/json',
                'X-Shopify-Access-Token' => $access_token,
            ),
            'body' => json_encode($data),
        );
        $return = wp_remote_request($url, $args);
        $args['headers']['X-Shopify-Access-Token']='XXXXXXXXXXXXX';
        $args['body'] = $data;
        awp_add_to_log($return, $url, $args, $record);
    } else if ($task == "add_product") {
        $title = empty($data["title"]) ? "" : awp_get_parsed_values($data["title"], $posted_data);
        $description = empty($data["description"]) ? "" : awp_get_parsed_values($data["description"], $posted_data);
        $vendor = empty($data["vendor"]) ? "" : awp_get_parsed_values($data["vendor"], $posted_data);
        $cf = empty($data["customFields"]) ? "" : awp_get_parsed_values($data["customFields"], $posted_data);
        $data = [
            'product' => [
                'title' => $title,
                'body_html' => $description,
                'vendor' => $vendor,
            ],
        ];
        if ($cf) {
            $cf = explode(",", $cf);
            $data["customFields"] = $cf;
        }
        $url = "https://" . $store_url . "/admin/api/2022-01/products.json";
        $args = array(
            'method' => 'POST',
            'headers' => array(
                'Content-Type' => 'application/json',
                'X-Shopify-Access-Token' => $access_token,
            ),
            'body' => json_encode($data),
        );
        $return = wp_remote_request($url, $args);
        $args['headers']['X-Shopify-Access-Token']='XXXXXXXXXXXXX';
        $args['body'] = $data;
        awp_add_to_log($return, $url, $args, $record);
    }
    return $return;
}

function awp_shopify_resend_data($log_id, $data, $integration)
{

    $temp = json_decode($integration["data"], true);
    $temp = $temp["field_data"];
    $platform_obj = new AWP_Platform_Shell_Table('shopify');
    $temp = $platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $access_token = $temp->api_key;


    if (!$access_token) {
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



        $args = array(
            'method' => 'POST',
            'headers' => array(
                'Content-Type' => 'application/json',
                'X-Shopify-Access-Token' => $access_token,
            ),
            'body' => json_encode($data['args']['body']),
        );
        $response = wp_remote_request($url, $args);
        $args['body'] = $data['args']['body'];
        awp_add_to_log($response, $url, $args, $record);
    } else if ($task == "add_product") {
        $args = array(
            'method' => 'POST',
            'headers' => array(
                'Content-Type' => 'application/json',
                'X-Shopify-Access-Token' => $access_token,
            ),
            'body' => json_encode($data['args']['body']),
        );
        $response = wp_remote_request($url, $args);
        $args['headers']['X-Shopify-Access-Token']='XXXXXXXXXXXXX';
        $args['body'] = $data['args']['body'];
        awp_add_to_log($response, $url, $args, $record);
    }

    $response['success'] = true;
    return $response;
}
