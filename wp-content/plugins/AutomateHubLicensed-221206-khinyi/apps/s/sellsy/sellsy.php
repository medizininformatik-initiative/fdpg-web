<?php

class AWP_sellsy extends Appfactory
{   
    // Consumer token: 58aebdabbdde9bec2a92b35ad2006600cb42413c

    // Secret consumer: 97f47f2000f9fd89c753bd39e2b429ab5481d192


    // replace with live key
    const client_id = "49e52d54-d4b4-4262-89dd-0dfa969471b3";
    const secret_id = "622a029b13bbdaebcdfa97250eace0245a5f0ce8062635770f90d420e2970cbc";

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
        add_filter('awp_platforms_connections', [$this, 'awp_sellsy_platform_connection'], 10, 1);
    }

    public function get_callback()
    {
        return get_rest_url(null,'automatehub/sellsy');
    }

    public function getLoginURL(): string
    {

         // Generate the code challenge using the OS / cryptographic random function
         $verifierBytes = random_bytes(64);
         $codeVerifier = rtrim(strtr(base64_encode($verifierBytes), "+/", "-_"), "=");
 
         $challengeBytes = hash("sha256", $codeVerifier, true);
         $codeChallenge = rtrim(strtr(base64_encode($challengeBytes), "+/", "-_"), "=");
         update_option("awp_sellsy_keys_holder",$codeVerifier);

        
        $query = [
            'response_type' => 'code',
            'redirect_uri' => $this->get_redirect_uri(),
            'code_challenge' => $codeChallenge,
            'code_challenge_method' => 'S256',
            'client_id' => self::client_id,
            'client_secret' => self::secret_id,
            'state' => uniqid(),
            'scope' => 'all'
            
        ];
        $authorization_endpoint = "https://login.sellsy.com/oauth2/authorization?";

      
        return add_query_arg($query, $authorization_endpoint);
    }

    public function create_webhook_route()
    {
        register_rest_route('automatehub', '/sellsy',
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
        $state = isset($params['state']) ? $params['state'] : "";

         if ($code) {

            $args = array(
                'header' => array(
                    'Content-type' => 'application/json'
                ),
                'body'=> json_encode(array(
                    "grant_type" => "authorization_code",
                    "client_id" => self::client_id,
                    "redirect_uri" => $this->get_redirect_uri(),
                    "code_verifier" => get_option('awp_sellsy_keys_holder'),
                    "code" => $code,
                    "scope" => "all"
                ))
            );

            $access_url = "https://login.sellsy.com/oauth2/access-tokens";

            $return = wp_remote_post($access_url, $args);

            $body = json_decode($return['body'], true);

            if (isset($body['access_token'])) {

                $query="select * from ".$wpdb->prefix."awp_platform_settings where platform_name='sellsy'";

                $data=$wpdb->get_results($query);
  
                $len=count($data) + 1;

                
                $platform_obj = new AWP_Platform_Shell_Table('sellsy');

                $platform_obj->save_platform(['account_name' => 'Account '.$len, 'api_key' => $body['access_token'], 'client_secret' => $body['refresh_token']]);
            }

            update_option("awp_sellsy_keys_holder","");

        }
        
        wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=sellsy'));
        exit();
    }

    public function action_provider($actions)
    {
        $actions['sellsy'] = [
            'title' => esc_html__('Sellsy', 'automate_hub'),
            'tasks' => array(
                'createcontact'   => esc_html__( 'Create Contact', 'automate_hub' ),
                'createtask'   => esc_html__( 'Create Task', 'automate_hub' ),
                // 'createindividual'   => esc_html__( 'Create Individual', 'automate_hub' ),
            ),
        ];
        return $actions;
    }

    public function settings_tab($tabs)
    {
        $tabs['sellsy'] = array('name' => esc_html__('Sellsy', 'automate_hub'), 'cat' => array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'sellsy') {
            return;
        }
        $nonce = wp_create_nonce("awp_sellsy_settings");
        $api_token = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/sellsy" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/sellsy.png" height="50" alt="sellsy Logo"></a><br /><br />
                <?php
        require_once AWP_INCLUDES . '/class_awp_updates_manager.php';
        $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
        $instruction_obj->prepare_instructions();

        ?><br />
            <form name="sellsy_save_form" action="<?=esc_url(admin_url('admin-post.php'));?>" method="post" class="container">
                <input type="hidden" name="action" value="awp_sellsy_save_api_token">
                <input type="hidden" name="_nonce" value="<?=$nonce?>" />
                <input type="hidden" name="id" value="<?=$id?>" />
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"> </th>
                        <td>
                            <a id="sellsyauthbtn" target="_blank"  class="button button-primary"> Connect Your sellsy Account </a>
                        </td>
                    </tr>
                    <script type="text/javascript">
                            document.getElementById("sellsyauthbtn").addEventListener("click", function(){
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
        $platform_obj = new AWP_Platform_Shell_Table('sellsy');
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
        wp_enqueue_script('awp-sellsy-script', AWP_URL . '/apps/s/sellsy/sellsy.js', array('awp-vuejs'), '', 1);
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="sellsy-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'sellsy',
                           'app_name'=>'sellsy ',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/sellsy.png',
                           'app_icon_alter_text'=>'sellsy  Icon',
                           'account_select_onchange'=>'gettype',
                           'tasks'=>array(
                                'createtask'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Status',
                                            'type'=>'select',
                                            'name'=>"status",
                                            'required' => 'required',
                                            'onchange' => 'getlabel',
                                            'option_for_loop'=>'(item) in data.statuslist',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Status...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': statusLoading}",
                                                        )
                                        ),
                                        array(
                                            'label'=>'Select Label',
                                            'type'=>'select',
                                            'name'=>"label",
                                            'required' => 'required',
                                            'onchange' => 'selectedlabel',
                                            'option_for_loop'=>'(item) in data.labelList',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Label...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': labelLoading}",
                                                        )
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

}

$AWP_sellsy = new AWP_sellsy();

/*
 * Saves connection mapping
 */
function awp_sellsy_save_integration()
{
    Appfactory::save_integration();
}


function awp_sellsy_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('sellsy');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $refresh_token=$temp->client_secret;
    $task = $decoded_data["task"]; 

    $token = 'Bearer '.$api_key;
   

    if( $task == "createcontact" ) {

        $civility = empty($data['civility']) ? "" : awp_get_parsed_values($data['civility'], $posted_data);
        $first_name = empty($data['first_name']) ? "" : awp_get_parsed_values($data['first_name'], $posted_data);
        $last_name = empty($data['last_name']) ? "" : awp_get_parsed_values($data['last_name'], $posted_data);
        $email = empty($data['email']) ? "" : awp_get_parsed_values($data['email'], $posted_data);
        $phone_number = empty($data['phone_number']) ? "" : awp_get_parsed_values($data['phone_number'], $posted_data);
        $mobile_number = empty($data['mobile_number']) ? "" : awp_get_parsed_values($data['mobile_number'], $posted_data);
        $note = empty($data['note']) ? "" : awp_get_parsed_values($data['note'], $posted_data);
        

        $body = json_encode([
            "civility"=>strtolower($civility),
            "first_name"=>$first_name,
            "last_name"=>$last_name,
            "email"=>$email,
            "phone_number"=>$phone_number,
            "mobile_number"=>$mobile_number,
            "note"=>$note,
        ]);

        $url = "https://api.sellsy.com/v2/contacts";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $token
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $error_data = isset(json_decode($response['body'])->error)? json_decode($response['body'])->error:'';

        if (isset($error_data->code) && $error_data->code == 401) {
            $new_accessToken = awp_refresh_sellsy_token($refresh_token, $AWP_sellsy->client_id, $AWP_sellsy->secret_id);
            
            if (isset($new_accessToken)) {
                $args['headers']['Authorization'] = 'Bearer ' . $new_accessToken;
                $returned_data = wp_remote_post($url, $args);
                $args['headers']['Authorization'] = 'Bearer XXXXXXXXXXX';
                $args['body'] = $body;
                awp_add_to_log($returned_data, $url, $args, $record);
            }
        }else{
            $args['headers']['Authorization']="XXXXXXXXXXXX";

            awp_add_to_log( $response, $url, $args, $record );
        }

      

    // } else if($task == "createindividual"){

    //     $name = empty($data['name']) ? "" : awp_get_parsed_values($data['name'], $posted_data);
    //     $email = empty($data['semail']) ? "" : awp_get_parsed_values($data['semail'], $posted_data);
    //     $website = empty($data['website']) ? "" : awp_get_parsed_values($data['website'], $posted_data);
    //     $phone_number = empty($data['sphone_number']) ? "" : awp_get_parsed_values($data['sphone_number'], $posted_data);
    //     $mobile_number = empty($data['smobile_number']) ? "" : awp_get_parsed_values($data['smobile_number'], $posted_data);
    //     $fax_number = empty($data['fax_number']) ? "" : awp_get_parsed_values($data['fax_number'], $posted_data);
    //     $capital = empty($data['capital']) ? "" : awp_get_parsed_values($data['capital'], $posted_data);
    //     $reference = empty($data['reference']) ? "" : awp_get_parsed_values($data['reference'], $posted_data);
    //     $note = empty($data['snote']) ? "" : awp_get_parsed_values($data['snote'], $posted_data);
    //     $auxiliary_code = empty($data['auxiliary_code']) ? "" : awp_get_parsed_values($data['auxiliary_code'], $posted_data);
    //     $type = empty($data['type']) ? "" : awp_get_parsed_values($data['type'], $posted_data);

        

    //     $body = json_encode([
    //         "name"=>$name,
    //         "email"=>$email,
    //         "website"=>$website,
    //         "phone_number"=>$phone_number,
    //         "mobile_number"=>$mobile_number,
    //         "fax_number"=>$fax_number,
    //         "capital"=>$capital,
    //         "reference"=>$reference,
    //         "note"=>$note,
    //         "auxiliary_code"=>$auxiliary_code,
    //         "type"=>$type
    //     ]);

       
        
    //     $url = "https://api.sellsy.com/v2/companies";


    //     $args = [
    //         'headers' => array(
    //             'Content-Type' => 'application/json',
    //             'Authorization' => $token
    //         ),

    //         'body'=> $body
    //     ];

    //     $response  = wp_remote_post($url,  $args );

    //     $error_data = isset(json_decode($response['body'])->error)? json_decode($response['body'])->error:'';

    //     if (isset($error_data->code) && $error_data->code == 401) {
    //         $new_accessToken = awp_refresh_sellsy_token($refresh_token, $AWP_sellsy->client_id, $AWP_sellsy->secret_id);
            
    //         if (isset($new_accessToken)) {
    //             $args['headers']['Authorization'] = 'Bearer ' . $new_accessToken;
    //             $returned_data = wp_remote_post($url, $args);
    //             $args['headers']['Authorization'] = 'Bearer XXXXXXXXXXX';
    //             $args['body'] = $body;
    //             awp_add_to_log($returned_data, $url, $args, $record);
    //         }
    //     }else{
    //         $args['headers']['Authorization']="XXXXXXXXXXXX";

    //         awp_add_to_log( $response, $url, $args, $record );
    //     }

    // } 
    } else if($task == "createtask"){

        $title = empty($data['title']) ? "" : awp_get_parsed_values($data['title'], $posted_data);
        $description	 = empty($data['description']) ? "" : awp_get_parsed_values($data['description'], $posted_data);
        $due_date = empty($data['due_date']) ? "" : awp_get_parsed_values($data['due_date'], $posted_data);
        $status = empty($data['status']) ? "" : awp_get_parsed_values($data['status'], $posted_data);
        $label = empty($data['label']) ? "" : awp_get_parsed_values($data['label'], $posted_data);
       
        $body = json_encode([
            "title"=>$title,
            "description"=>$description,
            "is_private"=> false,
            "due_date"=> date('Y-m-d\TH:i:s\z', strtotime($due_date)),
            "status"=>$status,
            "label_id" => (integer) $label
        ]);

       
        
        $url = "https://api.sellsy.com/v2/tasks";


        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $token
            ),

            'body'=> $body
        ];


        $response  = wp_remote_post($url,  $args );

        $error_data = isset(json_decode($response['body'])->error)? json_decode($response['body'])->error:'';

        if (isset($error_data->code) && $error_data->code == 401) {

            $new_accessToken = awp_refresh_sellsy_token($refresh_token, $AWP_sellsy->client_id, $AWP_sellsy->secret_id);
            
            if (isset($new_accessToken)) {
                $args['headers']['Authorization'] = 'Bearer ' . $new_accessToken;
                $returned_data = wp_remote_post($url, $args);
                $args['headers']['Authorization'] = 'Bearer XXXXXXXXXXX';
                $args['body'] = $body;
                awp_add_to_log($returned_data, $url, $args, $record);
            }
        }else{
            $args['headers']['Authorization']="XXXXXXXXXXXX";

            awp_add_to_log( $response, $url, $args, $record );
        }
    }

    return $response;
}

function awp_refresh_sellsy_token($refresh_token, $client, $secret)
{   
 
        $url = 'https://login.sellsy.com/oauth2/access-tokens';

        $args = array(
            'body'=> array(
                "grant_type" => "refresh_token",
                "client_id" => $client,
                "client_secret" => $secret,
                "refresh_token" => $refresh_token
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


function awp_sellsy_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('sellsy');
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

