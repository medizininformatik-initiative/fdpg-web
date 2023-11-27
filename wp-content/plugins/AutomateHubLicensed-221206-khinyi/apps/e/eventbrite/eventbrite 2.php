<?php

class AWP_Eventbrite extends Appfactory
{

    // replace with live key
    
    const api_key = "VUVEHZABDQALSNHVQC";

    protected function get_redirect_uri()
    {
        return 'https://sperse.io/scripts/authorization/auth.php';
    }

    public function init_actions()
    {
        add_action("rest_api_init", [$this, "create_webhook_route"]);
        add_action('wp_ajax_awp_fetch_organizers', [$this, 'fetch_organizers']);
    }

    public function init_filters()
    {
        add_filter('awp_platforms_connections', [$this, 'awp_eventbrite_platform_connection'], 10, 1);
    }

    public function get_callback()
    {
        return get_rest_url(null, 'automatehub/eventbrite');
    }

    public function getLoginURL(): string
    {
        $query = [
            'response_type' => "code",
            'client_id' => self::api_key,
            'redirect_uri' => $this->get_redirect_uri(),
            'state' => $this->get_callback(),
        ];

        $authorization_endpoint = "https://www.eventbrite.com/oauth/authorize?";

        return add_query_arg($query, $authorization_endpoint);
    }

    public function create_webhook_route()
    {
        register_rest_route('automatehub', '/eventbrite',
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

        if (isset($params['access_token'])) {
            $access_token = $params['access_token'];

            $query = "select * from " . $wpdb->prefix . "awp_platform_settings where platform_name='eventbrite'";

            $data = $wpdb->get_results($query);

            $len = count($data) + 1;

            $platform_obj = new AWP_Platform_Shell_Table('eventbrite');

            $platform_obj->save_platform(['account_name' => 'Account Number ' . $len, 'api_key' => $access_token]);

        }
        wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=eventbrite'));
        exit();
    }

    public function action_provider($actions)
    {
        $actions['eventbrite'] = [
            'title' => esc_html__('Eventbrite', 'automate_hub'),
            'tasks' => array(
                'createevent' => esc_html__('Create Event', 'automate_hub'),
                'createvenue' => esc_html__('Create Venue', 'automate_hub'),
            ),
        ];
        return $actions;
    }

    public function settings_tab($tabs)
    {
        $tabs['eventbrite'] = array('name' => esc_html__('Eventbrite', 'automate_hub'), 'cat' => array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'eventbrite') {
            return;
        }
        $nonce = wp_create_nonce("awp_eventbrite_settings");
        $api_token = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/eventbrite" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/eventbrite.png" width="292" height="50" alt="eventbrite Logo"></a><br /><br />
                <?php
require_once AWP_INCLUDES . '/class_awp_updates_manager.php';
        $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
        $instruction_obj->prepare_instructions();

        ?><br />
            <form name="eventbrite_save_form" action="<?=esc_url(admin_url('admin-post.php'));?>" method="post" class="container">
                <input type="hidden" name="action" value="awp_eventbrite_save_api_token">
                <input type="hidden" name="_nonce" value="<?=$nonce?>" />
                <input type="hidden" name="id" value="<?=$id?>" />
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"> </th>
                        <td>
                            <a onclick="btneventbrightAuth()" id="btneventbrightAuth" class="button button-primary"> Connect Your Eventbrite Account </a>
                        </td>
                    </tr>
                </table>
            </form>
            <script type="text/javascript">
                            function btneventbrightAuth(){
                                var win=window.open('<?php echo $this->getLoginURL() ?>','popup','width=600,height=600');
                                var id = setInterval(function() {
                                const queryString = win.location.search;
                                const urlParams = new URLSearchParams(queryString);
                                const page_type = urlParams.get('page');
                                if(page_type=='automate_hub'){win.close(); clearInterval(id); location.reload();}
                                }, 1000);
                            }
                        </script>
        </div>

        <div class="wrap">
            <form id="form-list" method="post">

                  <input type="hidden" name="page" value="automate_hub"/>

                  <?php
$data = [
            'table-cols' => ['api_key' => 'API Key', 'account_name' => 'Display Name', 'active_status' => 'Active'],
        ];
        $platform_obj = new AWP_Platform_Shell_Table('eventbrite');
        $platform_obj->initiate_table($data);
        $platform_obj->prepare_items();
        $platform_obj->display_table();

        ?>
            </form>
        </div>
        <?php
}

    public function load_custom_script()
    {
        wp_enqueue_script('awp-eventbrite-script', AWP_URL . '/apps/e/eventbrite/eventbrite.js', array('awp-vuejs'), '', 1);
    }

    public function action_fields()
    {
        ?>
        <script type="text/template" id="eventbrite-action-template">
            <?php

        $app_data = array(
            'app_slug' => 'eventbrite',
            'app_name' => 'Eventbrite',
            'app_icon_url' => AWP_ASSETS . '/images/icons/eventbrite.png',
            'app_icon_alter_text' => 'Eventbrite  Icon',
            'account_select_onchange' => 'getorganizationlist',
            'tasks' => array(
                'createevent' => array(
                    'task_assignments' => array(
                        array(
                            'label' => 'Select Organization',
                            'type' => 'select',
                            'name' => "organization",
                            'required' => 'required',
                            'onchange' => 'getcurrencylist',
                            'option_for_loop' => '(item) in data.organizerslist',
                            'option_for_value' => 'item.id',
                            'option_for_text' => '{{item.name}}',
                            'select_default' => 'Select Organization...',
                        ),
                        array(
                            'label' => 'Select Currency',
                            'type' => 'select',
                            'name' => "currency",
                            'required' => 'required',
                            'onchange' => 'selectedcurrency',
                            'option_for_loop' => '(item) in data.currencylist',
                            'option_for_value' => 'item.id',
                            'option_for_text' => '{{item.name}}',
                            'select_default' => 'Select Currency...',
                        ),
                    ),
                ),
                'createvenue' => array(
                    'task_assignments' => array(
                        array(
                            'label' => 'Select Organization',
                            'type' => 'select',
                            'name' => "organization",
                            'required' => 'required',
                            'onchange' => 'selectedorganization',
                            'option_for_loop' => '(item) in data.organizerslist',
                            'option_for_value' => 'item.id',
                            'option_for_text' => '{{item.name}}',
                            'select_default' => 'Select Organization...',
                        ),
                    ),
                ),
            ),
        );

        require AWP_VIEWS . '/awp_app_integration_format.php';
        ?>

    </script>
    <?php
}

    public function fetch_organizers()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }

        if (!wp_verify_nonce($_POST['_nonce'], 'automate_hub')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        if (!isset($_POST['platformid'])) {
            die(esc_html__('Invalid Request', 'automate_hub'));
        }

        $id = sanitize_text_field($_POST['platformid']);
        $platform_obj = new AWP_Platform_Shell_Table('eventbrite');
        $data = $platform_obj->awp_get_platform_detail_by_id($id);
        if (!$data) {
            die(esc_html__('No Data Found', 'automate_hub'));
        }

        $api_key = $data->api_key;

        $url = "https://www.eventbriteapi.com/v3/users/me/organizations/";
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $api_key,
            ),
        );

        $response = wp_remote_get($url, $args);
        $body = wp_remote_retrieve_body($response);
        $body = json_decode($body, true);
        wp_send_json_success($body["organizations"]);
    }

}

$AWP_Eventbrite = new AWP_Eventbrite();

/*
 * Saves connection mapping
 */
function awp_eventbrite_save_integration()
{
    Appfactory::save_integration();
}

function awp_eventbrite_send_data($record, $posted_data)
{
    $decoded_data = Appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"];

    $platform_obj = new AWP_Platform_Shell_Table('eventbrite');
    $temp = $platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key = $temp->api_key;
    $task = $decoded_data["task"];
    $token = 'Bearer ' . $api_key;

    if ($task == "createevent") {

        $name = empty($data["name"]) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $description = empty($data["description"]) ? "" : awp_get_parsed_values($data["description"], $posted_data);
        $capacity = empty($data["capacity"]) ? "" : awp_get_parsed_values($data["capacity"], $posted_data);
        $start = empty($data["start"]) ? "" : awp_get_parsed_values($data["start"], $posted_data);
        $end = empty($data["end"]) ? "" : awp_get_parsed_values($data["end"], $posted_data);
        $currency = empty($data["currency"]) ? "" : awp_get_parsed_values($data["currency"], $posted_data);

        $body = json_encode([
            "event" => [
                "name" => [
                    "html" => $name,
                ],
                "description" => [
                    "html" => $description,
                ],
                "capacity" => $capacity,

                "start" => [
                    "timezone" => "UTC",
                    "utc" => date('Y-m-d\TH:i:s\z', strtotime($start)),
                ],
                "end" => [
                    "timezone" => "UTC",
                    "utc" => date('Y-m-d\TH:i:s\z', strtotime($end)),
                ],
                "currency" => $currency,
            ],
        ]);

        $url = "https://www.eventbriteapi.com/v3/organizations/" . $data['organizationid'] . "/events/";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $token,
            ),

            'body' => $body,
        ];

        error_log(print_r($args, true), 0);

        $response = wp_remote_post($url, $args);

        $args['headers']['Authorization'] = "XXXXXXXXXXXX";

        awp_add_to_log($response, $url, $args, $record);

    } else if ($task == "createvenue") {

        $vname = empty($data["vname"]) ? "" : awp_get_parsed_values($data["vname"], $posted_data);
        $vcapacity = empty($data["vcapacity"]) ? "" : awp_get_parsed_values($data["vcapacity"], $posted_data);

        $body = json_encode([
            "venue" => [
                "name" => $vname,
                "capacity" => $vcapacity,
            ],
        ]);

        $url = "https://www.eventbriteapi.com/v3/organizations/" . $data['organizationid'] . "/venues/";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $token,
            ),

            'body' => $body,
        ];

        $response = wp_remote_post($url, $args);

        $args['headers']['Authorization'] = "XXXXXXXXXXXX";

        awp_add_to_log($response, $url, $args, $record);

    }
    return $response;
}

function awp_eventbrite_resend_data($log_id, $data, $integration)
{
    $temp = json_decode($integration["data"], true);
    $temp = $temp["field_data"];

    $platform_obj = new AWP_Platform_Shell_Table('eventbrite');
    $temp = $platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_key = $temp->api_key;
    if (!$api_key) {
        return;
    }
    $data = stripslashes($data);
    $data = preg_replace('/\s+/', '', $data);
    $data = str_replace('"{', '{', $data);
    $data = str_replace('}"', '}', $data);
    $data = json_decode($data, true);
    $body = $data['args']['body'];
    $url = $data['url'];
    if (!$url) {
        $response['success'] = false;
        $response['msg'] = "Syntax Error! Request is invalid";
        return $response;
    }

    $campfireUrl = $url;
    $token = 'Bearer ' . $api_key;

    $args = [
        'headers' => array(
            'Content-Type' => 'application/json',
            'Authorization' => $token,
        ),

        'body' => json_encode($body),
    ];

    $return = wp_remote_post($campfireUrl, $args);

    $args['headers']['Authorization'] = "Bearer XXXXXXXXXXXX";
    awp_add_to_log($return, $campfireUrl, $args, $integration);

    $response['success'] = true;
    return $response;
}
