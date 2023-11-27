<?php

class AWP_Squarespace extends Appfactory
{

    // replace with live key
    const client_id = "XinzVdo0sUUy6ycJUbUQbZBNb4mxIthv";
    const secret_id = "fIL7Nasd7ewoQM7Wa6T8fy9DnDUwk6QtV7T9mBRh6iM=";

    protected function get_redirect_uri()
    {
        // return 'http://localhost/wp/wp-json/automatehub/squarespace';
        return 'https://sperse.io/scripts/authorization/auth.php';
    }

    public function init_actions()
    {
        add_action("rest_api_init", [$this, "create_webhook_route"]);
        add_action( 'wp_ajax_awp_fetch_storePageId', [$this, 'awp_fetch_storePageId']);
    }

    public function init_filters()
    {
        add_filter('awp_platforms_connections', [$this, 'awp_squarespace_platform_connection'], 10, 1);
    }

    public function get_callback()
    {
        return get_rest_url(null,'automatehub/squarespace');
    }

    public function getLoginURL(): string
    {
        $query = [
            'client_id' => self::client_id,
            'redirect_uri' => $this->get_redirect_uri(),
            'scope' => 'website.orders,website.orders.read,website.transactions.read,website.inventory,website.inventory.read,website.products,website.products.read',
            'state' => 'SID'.rand(0001,1000),
            'access_type' => 'offline',
            
        ];
        $authorization_endpoint = "https://login.squarespace.com/api/1/login/oauth/provider/authorize";

      
        return add_query_arg($query, $authorization_endpoint);
    }

    public function create_webhook_route()
    {
        register_rest_route('automatehub', '/squarespace',
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

       

        $code = isset($params['code']) ? $params['code'] : "";


         if ($code) {

            $args = array(
                'headers' => array(
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Authorization'=> 'Basic '.base64_encode(self::client_id.":".self::secret_id),
                    'User-Agent'=> 'sperse',
                ),
                'body'=> array(
                    'grant_type' => 'authorization_code',
                    'code' => $code,
                    'redirect_uri' => $this->get_redirect_uri(),
                )
            );

            

            $access_url = "https://login.squarespace.com/api/1/login/oauth/provider/tokens";

            $return = wp_remote_post($access_url, $args);

            $body = json_decode($return['body'], true);
        
            
            if (isset($body['access_token'])) {

                $query="select * from ".$wpdb->prefix."awp_platform_settings where platform_name='squarespace'";

                $data=$wpdb->get_results($query);
  
                $len=count($data) + 1;

                
                $platform_obj = new AWP_Platform_Shell_Table('squarespace');

                $platform_obj->save_platform(['account_name' => 'Account '.$len, 'api_key' => $body['access_token'], 'client_secret' => $body['refresh_token']]);
            }

        }
        
        wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=squarespace'));
        exit();
    }

    public function action_provider($actions)
    {
        $actions['squarespace'] = [
            'title' => esc_html__('squarespace', 'automate_hub'),
            'tasks' => array(
                'createproduct' => esc_html__('Create Product', 'automate_hub'),
                'createcompany' => esc_html__('Create Company', 'automate_hub')
            ),
        ];
        return $actions;
    }

    public function settings_tab($tabs)
    {
        $tabs['squarespace'] = array('name' => esc_html__('Squarespace', 'automate_hub'), 'cat' => array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'squarespace') {
            return;
        }
        $nonce = wp_create_nonce("awp_squarespace_settings");
        $api_token = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/squarespace" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/squarespace.png" width="292" height="50" alt="squarespace Logo"></a><br /><br />
                <?php
        require_once AWP_INCLUDES . '/class_awp_updates_manager.php';
        $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
        $instruction_obj->prepare_instructions();

        ?><br />
            <form name="squarespace_save_form" action="<?=esc_url(admin_url('admin-post.php'));?>" method="post" class="container">
                <input type="hidden" name="action" value="awp_squarespace_save_api_token">
                <input type="hidden" name="_nonce" value="<?=$nonce?>" />
                <input type="hidden" name="id" value="<?=$id?>" />
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"> </th>
                        <td>
                            <a id="squarespaceauthbtn" target="_blank"  class="button button-primary"> Connect Your Squarespace Account </a>
                        </td>
                    </tr>
                    <script type="text/javascript">
                            document.getElementById("squarespaceauthbtn").addEventListener("click", function(){
                                var win=window.open('<?php echo $this->getLoginURL()?>','popup','width=600,height=600');
                                var id = setInterval(function() {
                                const queryString = win.location.search;
                                const urlParams = new URLSearchParams(queryString);
                                const page_type = urlParams.get('page');
                                if(page_type=='automate_hub'){win.close(); clearInterval(id); location.reload();}
                                }, 1000);
                            });
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
        $platform_obj = new AWP_Platform_Shell_Table('squarespace');
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
        wp_enqueue_script('awp-squarespace-script', AWP_URL . '/apps/s/squarespace/squarespace.js', array('awp-vuejs'), '', 1);
    }

    public function action_fields()
    {
        ?>
        <script type="text/template" id="squarespace-action-template">
            <?php

                $app_data=array(
                    'app_slug'=>'squarespace',
                    'app_name'=>'Squarespace',
                    'app_icon_url'=>AWP_ASSETS.'/images/icons/squarespace.png',
                    'app_icon_alter_text'=>'Squarespace  Icon',
                    'account_select_onchange'=>'getstorePageId',
                    'tasks'=>array(
                        'createproduct'=>array(
                            'task_assignments'=>array(
                                array(
                                    'label'=>'Select Page',
                                    'type'=>'select',
                                    'name'=>"storePageId",
                                    'required' => 'required',
                                    'onchange' => 'gettaglist',
                                    'option_for_loop'=>'(item) in data.storePageIdList',
                                    'option_for_value'=>'item.id',
                                    'option_for_text'=>'{{item.title}}',
                                    'select_default'=>'Select Page...'
                                ),
                                array(
                                    'label'=>'Select Tag',
                                    'type'=>'select',
                                    'name'=>"tag",
                                    'required' => 'required',
                                    'onchange' => 'getbasecurrencylist',
                                    'option_for_loop'=>'(item) in data.taglist',
                                    'option_for_value'=>'item.id',
                                    'option_for_text'=>'{{item.name}}',
                                    'select_default'=>'Select Tag...'
                                ),
                                array(
                                    'label'=>'Select Base Currency',
                                    'type'=>'select',
                                    'name'=>"basecurrency",
                                    'required' => 'required',
                                    'onchange' => 'getsalescurrencylist',
                                    'option_for_loop'=>'(item) in data.basecurrencylist',
                                    'option_for_value'=>'item.id',
                                    'option_for_text'=>'{{item.name}}',
                                    'select_default'=>'Select Base Currency...'
                                ),
                                array(
                                    'label'=>'Select Sales Currency',
                                    'type'=>'select',
                                    'name'=>"salescurrency",
                                    'required' => 'required',
                                    'onchange' => 'shippingweightmeasuringunit',
                                    'option_for_loop'=>'(item) in data.salescurrencylist',
                                    'option_for_value'=>'item.id',
                                    'option_for_text'=>'{{item.name}}',
                                    'select_default'=>'Select Base Currency...'
                                ),
                                array(
                                    'label'=>'Select Measuring Unit',
                                    'type'=>'select',
                                    'name'=>"shippingweightmeasuringunit",
                                    'required' => 'required',
                                    'onchange' => 'selectedshippingweightmeasuringunit',
                                    'option_for_loop'=>'(item) in data.shippingweightmeasuringunitlist',
                                    'option_for_value'=>'item.id',
                                    'option_for_text'=>'{{item.name}}',
                                    'select_default'=>'Select Measuring Unit...'
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


    public function awp_fetch_storePageId() {
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
        $platform_obj= new AWP_Platform_Shell_Table('squarespace');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }


        $api_key =$data->api_key;

        $url = "https://api.squarespace.com/1.0/commerce/store_pages";
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$api_key,
                'User-Agent' => 'Sperse'
            )
        );

        $response = wp_remote_get( $url, $args );
        $body = wp_remote_retrieve_body( $response );
        
        $body = json_decode( $body, true );

        wp_send_json_success($body['storePages']);
    }

}

$AWP_Squarespace = new AWP_Squarespace();

/*
 * Saves connection mapping
 */
function awp_squarespace_save_integration()
{
    Appfactory::save_integration();
}


function awp_squarespace_send_data( $record, $posted_data ) {
    $decoded_data = Appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('squarespace');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $task = $decoded_data["task"]; 
    $token = 'Bearer '.$api_key;
    $refresh_token = $temp->client_secret;

    if( $task == "createproduct" ) {

        $type = 'PHYSICAL';
        $storePageId = empty( $data["storePageId"] ) ? "" : awp_get_parsed_values($data["storePageId"], $posted_data);
        $name = empty( $data["name"] ) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $tags = empty( $data["tags"] ) ? "" : awp_get_parsed_values($data["tags"], $posted_data);
        $description = empty( $data["description"] ) ? "" : awp_get_parsed_values($data["description"], $posted_data);
        $variants = [
            'sku' => 'SQ'.rand(0001, 0557),        
            'pricing' => [
                'basePrice' => [
                    'currency' =>  empty( $data["basecurrency"] ) ? "" : awp_get_parsed_values($data["basecurrency"], $posted_data),
                    'value' => empty( $data["baseamount"] ) ? "" : awp_get_parsed_values($data["baseamount"], $posted_data)
                ],
                'salePrice' => [
                    'currency' =>  empty( $data["salescurrency"] ) ? "" : awp_get_parsed_values($data["salescurrency"], $posted_data),
                    'value' => empty( $data["saleamount"] ) ? "" : awp_get_parsed_values($data["saleamount"], $posted_data)
                ]
            ],
            'shippingMeasurements' => [
                'weight' => [
                    'unit' => empty( $data["shippingweightmeasuringunit"] ) ? "" : awp_get_parsed_values($data["shippingweightmeasuringunit"], $posted_data), // KiLOGRAM & POUND
                    'value' => empty( $data["shippingweight"] ) ? "" : awp_get_parsed_values($data["shippingweight"], $posted_data),
                ],
                'dimensions' => [
                    'unit' => 'INCH',
                    'length' => empty( $data["productlenght"] ) ? "" : awp_get_parsed_values($data["productlenght"], $posted_data),
                    'width' => empty( $data["productwidth"] ) ? "" : awp_get_parsed_values($data["productwidth"], $posted_data),
                    'height' => empty( $data["productheight"] ) ? "" : awp_get_parsed_values($data["productheight"], $posted_data),
                ]
            ],
            'stock' => [
                'quantity' => empty( $data["quantity"] ) ? "" : awp_get_parsed_values($data["quantity"], $posted_data)
            ]
        ];
                

        

        $body = json_encode([
            "type"=>$type,
            "storePageId"=>$storePageId,
            "name"=>$name,
            "tags"=>[$tags],
            "description"=>$description,
            "variants"=>[$variants]
        ]);

        $url = "https://api.squarespace.com/1.0/commerce/products/";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $token,
                'User-Agent' => 'Sperse'
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $result = json_decode($response['body'], true);
        
        $error_data = isset($result['type']) && $result['type'] == 'AUTHORIZATION_ERROR' ? true : false;

        if ($error_data) {

            $new_accessToken = awp_refresh_squarespace_token($refresh_token, $token);

            if (isset($new_accessToken)) {
                $args['headers']['Authorization'] = 'Bearer ' . $new_accessToken;
                $returned_data = wp_remote_request($url, $args);
                $args['headers']['Authorization'] = 'Bearer XXXXXXXXXXX';
                $args['body'] = $body;
                awp_add_to_log($returned_data, $url, $args, $record);
            }
        } else {
            $args['headers']['Authorization'] = 'Bearer XXXXXXXXXXX';
            $args['body'] = $body;
            awp_add_to_log($response, $url, $args, $record);
        }
        

    } 
    return $response;
}

function awp_refresh_squarespace_token($refresh_token, $key)
{   
 
        $url = 'https://login.squarespace.com/api/1/login/oauth/provider/tokens';

        $args = array(
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization'=> 'Basic '.base64_encode(AWP_Squarespace::client_id.":".AWP_Squarespace::secret_id),
                'User-Agent'=> 'sperse',
            ),
            'body'=> array(
                'grant_type' => 'refresh_token',
                'refresh_token' => $refresh_token,
            )
        );
        
        $returned  = wp_remote_post($url,  $args );

        $decoded_data = json_decode($returned['body']);
        
        $new_accessToken = $decoded_data->access_token;
        $new_refreshToken = $decoded_data->refresh_token;

        if (isset($new_accessToken)) {
            global $wpdb;
            $res = $wpdb->update($wpdb->prefix . 'awp_platform_settings', ['api_key' => $new_accessToken, 'client_secret'=> $new_refreshToken ], ['api_key' => $key]);
        }
        return $new_accessToken;                                                                       
        

}


function awp_squarespace_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('squarespace');
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

   
        $token = 'Bearer '.$api_key;
        

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $token,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),

            'body'=> json_encode($body)
        ];


        $return  = wp_remote_post( $url,  $args );

        $args['headers']['Authorization']="Bearer XXXXXXXXXXXX";
        awp_add_to_log( $return,  $url, $args, $integration );

    $response['success']=true;    
    return $response;
}

