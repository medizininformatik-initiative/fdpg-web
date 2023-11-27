<?php

class AWP_Vercel extends appfactory
{

   
    public function init_actions(){
       
        add_action('admin_post_awp_vercel_save_api_token', [$this, 'awp_save_vercel_api_token'], 10, 0);
        
        add_action( 'wp_ajax_awp_fetch_team', [$this, 'awp_fetch_team']);
       
        
        
    }

    public function init_filters(){

        add_filter('awp_platforms_connections', [$this, 'awp_vercel_platform_connection'], 10, 1);
    }

    public function action_provider( $providers ) {
        $providers['vercel'] = array(
            'title' => esc_html__( 'Vercel', 'automate_hub' ),
            'tasks' => array(
                'createteam'   => esc_html__( 'Create Team', 'automate_hub' ),
                'createproject'   => esc_html__( 'Create Project', 'automate_hub' ),
                'createsecret'   => esc_html__( 'Create Secret', 'automate_hub' )
            )
        );
    
        return  $providers;
    }

    public function settings_tab( $tabs ) {
        $tabs['vercel'] = array('name'=>esc_html__( 'Vercel', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'vercel') {
            return;
        }
        $nonce = wp_create_nonce("awp_vercel_settings");
        $api_token = isset($_GET['api_key']) ? sanitize_text_field($_GET['api_key']) : "";
        $id = isset($_GET['id']) ? intval( sanitize_text_field($_GET['id'] )) : "";
        $display_name     = isset($_GET['account_name']) ? sanitize_text_field($_GET['account_name']) : "";
        ?>
        <div class="platformheader">

            <a href="https://sperse.io/go/vercel" target="_blank"><img src="<?php echo esc_url(AWP_ASSETS.'/images/logos/vercel.png'); ?>"  height="50" alt="VercelLogo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager(sanitize_text_field($_GET['tab']));
                    $instruction_obj->prepare_instructions();
                        
                ?>

                <br />

                <?php 

                $form_fields = '';
                $app_name= 'vercel';
                $vercel_form = new AWP_Form_Fields($app_name);

                $form_fields = $vercel_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_vercel_display_name",
                        'name'          => "awp_vercel_display_name",
                        'value'         => $display_name,
                        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
                        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $vercel_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_vercel_api_token",
                        'name'          => "awp_vercel_api_token",
                        'value'         => $api_token,
                        'placeholder'   => esc_html__( 'Enter your VercelLive API Key', 'automate_hub' ),
                        'label'         =>  esc_html__( 'VercelAPI Key', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $vercel_form->awp_wp_hidden_input(
                    array(
                        'name'          => "action",
                        'value'         => 'awp_vercel_save_api_token',
                    )
                );


                $form_fields .= $vercel_form->awp_wp_hidden_input(
                    array(
                        'name'          => "_nonce",
                        'value'         =>$nonce,
                    )
                );
                $form_fields .= $vercel_form->awp_wp_hidden_input(
                    array(
                        'name'          => "id",
                        'value'         =>wp_unslash($id),
                    )
                );


                $vercel_form->render($form_fields);

                ?>


        </div>

        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                 $data=[
                    'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('vercel');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();

                ?>
                        </form>
                </div>
        <?php
    }

    public function awp_save_vercel_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_vercel_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_token = sanitize_text_field($_POST["awp_vercel_api_token"]);
        $display_name     = sanitize_text_field( $_POST["awp_vercel_display_name"] );

        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('vercel');
        $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_token]);

        AWP_redirect("admin.php?page=automate_hub&tab=vercel");
    } 
    
    public function load_custom_script() {
        wp_enqueue_script( 'awp-vercel-script', AWP_URL . '/apps/v/vercel/vercel.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="vercel-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'vercel',
                           'app_name'=>'Vercel',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/vercel.png',
                           'app_icon_alter_text'=>'Vercel Icon',
                           'account_select_onchange'=>'getteam',
                           'tasks'=>array(
                                'createproject'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Team',
                                            'type'=>'select',
                                            'name'=>"teamid",
                                            'required' => 'required',
                                            'onchange' => 'getframeworkList',
                                            'option_for_loop'=>'(item) in data.teamList',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Team...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': teamLoading}",
                                                        )
                                        ),
                                        array(
                                            'label'=>'Select Framework',
                                            'type'=>'select',
                                            'name'=>"framework",
                                            'required' => 'required',
                                            'onchange' => 'selectedframework',
                                            'option_for_loop'=>'(item) in data.frameworkList',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Framework...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': frameworkLoading}",
                                                        )
                                        )
                                    ),
                                ),
                                'createsecret'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Team',
                                            'type'=>'select',
                                            'name'=>"teamid",
                                            'required' => 'required',
                                            'onchange' => 'getframeworkList',
                                            'option_for_loop'=>'(item) in data.teamList',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Team...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': teamLoading}",
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

    
    public function awp_fetch_team() {
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
        $platform_obj= new AWP_Platform_Shell_Table('vercel');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;

        $url = "https://api.vercel.com/v2/teams";
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$api_key
            )
        );

        $response = wp_remote_get( $url, $args );
        $body = wp_remote_retrieve_body( $response );
        $body = json_decode( $body, true );
        wp_send_json_success($body["teams"]);
    }

};


$Awp_vercel= new AWP_Vercel();

function awp_vercel_save_integration() {
    Appfactory::save_integration();
}

function awp_vercel_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('vercel');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $task = $decoded_data["task"]; 

   

    if( $task == "createteam" ) {

        $name = empty( $data["name"] ) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $slug = empty( $data["slug"] ) ? "" : awp_get_parsed_values($data["slug"], $posted_data);
        

        $token = 'Bearer '.$api_key;

        $body = json_encode([
            "name"=>$name,
            "slug"=>$slug,
           
        ]);

        $url = "https://api.vercel.com/v1/teams";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $token,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } else if( $task == "createproject" ) {

        $name = empty( $data["projectname"] ) ? "" : awp_get_parsed_values($data["projectname"], $posted_data);
        $framework = empty( $data["framework"] ) ? "" : awp_get_parsed_values($data["framework"], $posted_data);
        $teamid = empty( $data["teamid"] ) ? "" : awp_get_parsed_values($data["teamid"], $posted_data);
        

        $token = 'Bearer '.$api_key;

        $body = json_encode([
            "name"=>$name,
            "framework"=>$framework
        ]);

        $url = "https://api.vercel.com/v9/projects?teamId=".$teamid;

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => $token,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } else if( $task == "createsecret" ) {

        $secretname = empty( $data["secretname"] ) ? "" : awp_get_parsed_values($data["secretname"], $posted_data);
        $value = empty( $data["secretvalue"] ) ? "" : awp_get_parsed_values($data["secretvalue"], $posted_data);
        $teamid = empty( $data["teamid"] ) ? "" : awp_get_parsed_values($data["teamid"], $posted_data);
        

        $token = 'Bearer '.$api_key;

        $body = json_encode([
            "name"=>$secretname,
            "value"=>$value
        ]);

        $url = "https://api.vercel.com/v2/secrets?teamId=".$teamid;

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
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


function awp_vercel_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('vercel');
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


        $return  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="Bearer XXXXXXXXXXXX";
        awp_add_to_log( $return, $url, $args, $integration );

    $response['success']=true;    
    return $response;
}
