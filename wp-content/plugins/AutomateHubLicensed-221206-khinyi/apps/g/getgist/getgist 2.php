<?php

class AWP_Getgist extends Appfactory
{

    // replace with live key
    const client_id = "a4w4DNUjk6PbWJ8L9swI4_SFZ3MYXxvezUXNxRv1_rw";
    const client_secret = "1nVCAYbq4v8wX3bdgrfems6xKbf-s-MFc1u1eHI-V4o";
    const gist_app_id = "1058";

    protected function get_redirect_uri()
    {
        return 'https://sperse.io/scripts/authorization/auth.php';
    }

    public function init_actions()
    {
        add_action("rest_api_init", [$this, "create_webhook_route"]);
    }

    public function init_filters()
    {
        add_filter('awp_platforms_connections', [$this, 'awp_getgist_platform_connection'], 10, 1);
    }

    public function get_callback()
    {
        return get_rest_url(null,'automatehub/getgist');
    }

    public function getLoginURL(): string
    {
        $query = [
            'client_id' => self::client_id,
            'response_type' => "code",
            'grant' => "basic",
            'redirect_uri' => urlencode($this->get_redirect_uri()),
            'state' => $this->get_callback(),
        ];
        $authorization_endpoint = "https://app.getgist.com/oauth/authorize?";

        return add_query_arg($query, $authorization_endpoint);
    }

    public function create_webhook_route()
    {
        register_rest_route('automatehub', '/getgist',
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

        if ( isset( $params['code'] ) ) {

            $query= $wpdb->prepare("select * from {$wpdb->prefix}awp_platform_settings where platform_name=%s",'getgist');

            $response=$wpdb->get_results($query);

            $len=count($response) + 1;

            $getgist_access_token = sanitize_text_field($params['code']);
            
            
            $authorization_url = "https://api.getgist.com/oauth/token";

            

            $data = [
                'body' => [
                    "grant_type" => "authorization_code",
                    "client_id" => self::client_id,
                    "client_secret" => self::client_secret,
                    "redirect_uri" => $this->get_redirect_uri(),
                    "code" => $getgist_access_token,
                ]
            ];

            $detail =  wp_remote_post($authorization_url, $data);

            $decoded_data = json_decode($detail['body']);

            $platform_obj= new AWP_Platform_Shell_Table('getgist');

            $platform_obj->save_platform(['account_name'=>"Account Number ".$len, 'api_key'=>$decoded_data->access_token]);
    
        }
        
        wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=getgist'));
        exit();
    }

    public function action_provider($actions)
    {
        $actions['getgist'] = [
            'title' => esc_html__('Getgist', 'automate_hub'),
            'tasks' => array(
                'createcontact' => esc_html__('Create Lead', 'automate_hub')
            ),
        ];
        return $actions;
    }

    public function settings_tab($tabs)
    {
        $tabs['getgist'] = array('name' => esc_html__('Getgist', 'automate_hub'), 'cat' => array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'getgist') {
            return;
        }
        $nonce = wp_create_nonce("awp_getgist_settings");
        $api_token = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/getgist" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/getgist.png" width="292" height="50" alt="getgist Logo"></a><br /><br />
                <?php
        require_once AWP_INCLUDES . '/class_awp_updates_manager.php';
        $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
        $instruction_obj->prepare_instructions();

        ?><br />
            <form name="getgist_save_form" action="<?=esc_url(admin_url('admin-post.php'));?>" method="post" class="container">
                <input type="hidden" name="action" value="awp_getgist_save_api_token">
                <input type="hidden" name="_nonce" value="<?=$nonce?>" />
                <input type="hidden" name="id" value="<?=$id?>" />
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"> </th>
                        <td>
                            <a onclick="getgistauthbtn()" id="getgistauthbtn" target="_blank" class="button button-primary"> Connect Your Getgist Account </a>
                        </td>
                    </tr>

                        <script type="text/javascript">
                            function getgistauthbtn(){
                                var win=window.open('<?php echo $this->getLoginURL()?>','popup','width=600,height=600');
                                var id = setInterval(function() {
                                const queryString = win.location.search;
                                const urlParams = new URLSearchParams(queryString);
                                const page_type = urlParams.get('page');
                                if(page_type=='automate_hub'){win.close(); clearInterval(id); location.reload();}
                                }, 1000);

                            }
                            
                        </script>
                </table>
            </form>
        </div>

        <div class="wrap">
            <form id="form-list" method="post">

                  <input type="hidden" name="page" value="automate_hub"/>

                  <?php
        $data = [
            'table-cols' => ['api_key' => 'API Key', 'account_name' => 'Display Name', 'active_status' => 'Active'],
        ];
        $platform_obj = new AWP_Platform_Shell_Table('getgist');
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
        wp_enqueue_script('awp-getgist-script', AWP_URL . '/apps/g/getgist/getgist.js', array('awp-vuejs'), '', 1);
    }

    public function action_fields()
    {
        ?>
        <script type="text/template" id="getgist-action-template">
            <?php

                $app_data=array(
                    'app_slug'=>'getgist',
                    'app_name'=>'Getgist',
                    'app_icon_url'=>AWP_ASSETS.'/images/icons/getgist.png',
                    'app_icon_alter_text'=>'Getgist  Icon',
                    'account_select_onchange'=>'getCampaign',
                    'tasks'=>array(

                    ),
                ); 

                    require (AWP_VIEWS.'/awp_app_integration_format.php');
                ?>

    </script>
    <?php
    }

}

$AWP_Getgist = new AWP_Getgist();

/*
 * Saves connection mapping
 */
function awp_getgist_save_integration()
{
    Appfactory::save_integration();
}


function awp_getgist_send_data( $record, $posted_data ) {
    $decoded_data = Appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('getgist');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $task = $decoded_data["task"]; 
    
    $token = 'Bearer '.$api_key;

    if( $task == "createcontact" ) {

        $name = empty( $data["name"] ) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $email = empty( $data["email"] ) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $phone = empty( $data["phone"] ) ? "" : awp_get_parsed_values($data["phone"], $posted_data);
        $city_name = empty( $data["city_name"] ) ? "" : awp_get_parsed_values($data["city_name"], $posted_data);
        $region_name = empty( $data["region_name"] ) ? "" : awp_get_parsed_values($data["region_name"], $posted_data);
        $country_name = empty( $data["country_name"] ) ? "" : awp_get_parsed_values($data["country_name"], $posted_data);
        $country_code = empty( $data["country_code"] ) ? "" : awp_get_parsed_values($data["country_code"], $posted_data);
        $address = empty( $data["address"] ) ? "" : awp_get_parsed_values($data["address"], $posted_data);
        $usertype = 'lead';
       

        $body = json_encode([
                "type" => $usertype,
                "user_id" => round(1012,9908),
                "name"=>$name,
                "email"=>$email,
                "phone"=>$phone,
                "location_data" => [
                    "city_name"=>$city_name,
                    "region_name"=>$region_name,
                    "country_name"=>$country_name,
                    "country_code"=>$country_code,
                ],
                "custom_properties" => [
                    "address"=>$address
                ]
        ]);

        $url = "https://api.getgist.com/contacts";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $token,
                'Accept' => 'application/json'
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );
        
        $args['headers']['Authorization'] = "XXXXXXXXXXXX";

        awp_add_to_log($response, $url, $args, $record);

    }
    return $response;
}


function awp_getgist_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('getgist');
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

