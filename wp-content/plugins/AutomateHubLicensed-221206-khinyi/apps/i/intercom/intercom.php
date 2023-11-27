<?php

class AWP_Intercom extends Appfactory
{

    // replace with live key
    const client_id = "ff7cf1fb-761f-49ac-93c1-0653551fcf80";

    protected function get_redirect_uri()
    {
        return 'https://sperse.io/scripts/authorization/auth.php';
    }

    public function init_actions()
    {
        add_action('admin_post_awp_intercom_save_api_token', [$this, 'awp_save_intercom_api_token'], 10, 0);
        add_action("rest_api_init", [$this, "create_webhook_route"]);
    }

    public function init_filters()
    {
        add_filter('awp_platforms_connections', [$this, 'awp_intercom_platform_connection'], 10, 1);
    }

    public function get_callback()
    {
        return get_rest_url(null,'automatehub/intercom');
    }

    public function getLoginURL(): string
    {
        $query = [
            'type' => "web_server",
            'client_id' => self::client_id,
            'redirect_uri' => urlencode($this->get_redirect_uri()),
            'state' => $this->get_callback(),
        ];
        $authorization_endpoint = "https://app.intercom.com/oauth?";

        return add_query_arg($query, $authorization_endpoint);
    }

    public function create_webhook_route()
    {
        register_rest_route('automatehub', '/intercom',
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
            $intercom_access_token = sanitize_text_field($params['access_token']);
            $query="select * from ".$wpdb->prefix."awp_platform_settings where platform_name='intercom'";

            $data=$wpdb->get_results($query);

            $len=count($data) + 1;

            $platform_obj= new AWP_Platform_Shell_Table('intercom');

            $platform_obj->save_platform(['account_name'=>'Account Number '.$len,'api_key'=>$intercom_access_token]);
      
        }
        
        wp_safe_redirect(admin_url('admin.php?page=automate_hub&tab=intercom'));
        exit();
    }

    public function action_provider($actions)
    {
        $actions['intercom'] = [
            'title' => esc_html__('intercom', 'automate_hub'),
            'tasks' => array(
                'createcontact' => esc_html__('Create Contact', 'automate_hub'),
                'createcompany' => esc_html__('Create Company', 'automate_hub')
            ),
        ];
        return $actions;
    }

    public function settings_tab($tabs)
    {
        $tabs['intercom'] = array('name' => esc_html__('Intercom', 'automate_hub'), 'cat' => array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'intercom') {
            return;
        }
        $nonce = wp_create_nonce("awp_intercom_settings");
        $api_token = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/intercom" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/intercom.png" height="50" alt="intercom Logo"></a><br /><br />
                <?php
        require_once AWP_INCLUDES . '/class_awp_updates_manager.php';
        $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
        $instruction_obj->prepare_instructions();

        ?><br />
            <form name="intercom_save_form" action="<?=esc_url(admin_url('admin-post.php'));?>" method="post" class="container">
                <input type="hidden" name="action" value="awp_intercom_save_api_token">
                <input type="hidden" name="_nonce" value="<?=$nonce?>" />
                <input type="hidden" name="id" value="<?=$id?>" />
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"> </th>
                        <td>
                            <a href="<?=$this->getLoginURL()?>" class="button button-primary"> Connect Your Intercom Account </a>
                        </td>
                    </tr>
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
        $platform_obj = new AWP_Platform_Shell_Table('intercom');
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
        wp_enqueue_script('awp-intercom-script', AWP_URL . '/apps/i/intercom/intercom.js', array('awp-vuejs'), '', 1);
    }

    public function action_fields()
    {
        ?>
        <script type="text/template" id="intercom-action-template">
            <?php

                $app_data=array(
                    'app_slug'=>'intercom',
                    'app_name'=>'Intercom',
                    'app_icon_url'=>AWP_ASSETS.'/images/icons/intercom.png',
                    'app_icon_alter_text'=>'Intercom  Icon',
                    'account_select_onchange'=>'getrole',
                    'tasks'=>array(
                        'createcontact'=>array(
                            'task_assignments'=>array(
                                array(
                                    'label'=>'Select Role',
                                    'type'=>'select',
                                    'name'=>"role",
                                    'required' => 'required',
                                    'onchange' => 'selectedrole',
                                    'option_for_loop'=>'(item) in data.rolelist',
                                    'option_for_value'=>'item.id',
                                    'option_for_text'=>'{{item.name}}',
                                    'select_default'=>'Select Role...'
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

$AWP_Intercom = new AWP_Intercom();

/*
 * Saves connection mapping
 */
function awp_intercom_save_integration()
{
    Appfactory::save_integration();
}


function awp_intercom_send_data( $record, $posted_data ) {
    $decoded_data = Appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('intercom');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $task = $decoded_data["task"]; 
    $token = 'Bearer '.$api_key;
    

    if( $task == "createcontact" ) {

        $name = empty( $data["name"] ) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $phone = empty( $data["phone"] ) ? "" : awp_get_parsed_values($data["phone"], $posted_data);
        $email = empty( $data["email"] ) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $external_id = "ID".mt_rand(000210, 999999);
        

        

        $body = json_encode([
            "name"=>$name,
            "phone"=>$phone,
            "email"=>$email,
            "external_id"=>$external_id,
            "signed_up_at"=>strtotime("now"),
            "last_seen_at"=>strtotime("now"),
            "role"=>$data['role']
        ]);

        $url = "https://api.intercom.io/contacts";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $token,
                'Accept' => 'application/json'
            ),

            'body'=> $body
        ];

        


        $response  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } else  if( $task == "createcompany" ) {

        $name = empty( $data["companyname"] ) ? "" : awp_get_parsed_values($data["companyname"], $posted_data);
        $plan = empty( $data["plan"] ) ? "" : awp_get_parsed_values($data["plan"], $posted_data);
        $companyid = "CID".mt_rand(001310, 999999);
        $remote_created_at = time();
        

        $body = json_encode([
            "name"=>$name,
            "plan"=>$plan,
            "company_id"=>$companyid,
            "remote_created_at"=>$remote_created_at
        ]);

        $url = "https://api.intercom.io/companies";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $token,
                'Accept' => 'application/json'
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        // exit();

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } 
    return $response;
}


function awp_intercom_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('intercom');
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

