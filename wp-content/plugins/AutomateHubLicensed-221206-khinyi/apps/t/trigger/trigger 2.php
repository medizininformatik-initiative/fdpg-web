<?php

class AWP_Trigger extends appfactory
{

   
    public function init_actions(){
       
        add_action('admin_post_awp_trigger_save_api_token', [$this, 'awp_save_trigger_api_token'], 10, 0);
        
        add_action( 'wp_ajax_awp_trigger_fetch_companylist', [$this, 'awp_trigger_fetch_companylist']);
        
    }

    public function init_filters(){

        add_filter('awp_platforms_connections', [$this, 'awp_trigger_platform_connection'], 10, 1);
    }

    public function action_provider( $providers ) {
        $providers['trigger'] = array(
            'title' => esc_html__( 'Trigger', 'automate_hub' ),
            'tasks' => array(
                'createcompany'   => esc_html__( 'Create Company', 'automate_hub' ),
                'trigger_createproject'   => esc_html__( 'Create Project', 'automate_hub' ),
                'createtask'   => esc_html__( 'Create Task', 'automate_hub' )
            )
        );
    
        return  $providers;
    }

    public function settings_tab( $tabs ) {
        $tabs['trigger'] = array('name'=>esc_html__( 'Trigger', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'trigger') {
            return;
        }
        $nonce = wp_create_nonce("awp_trigger_settings");
        $api_key = isset($_GET['api_key']) ? sanitize_text_field($_GET['api_key']) : "";
        $id = isset($_GET['id']) ? intval( sanitize_text_field($_GET['id'] )) : "";
        $display_name     = isset($_GET['account_name']) ? sanitize_text_field($_GET['account_name']) : "";
        $api_token     = isset($_GET['client_id']) ? sanitize_text_field($_GET['client_id']) : "";
        ?>
        <div class="platformheader">

            <a href="https://sperse.io/go/trigger" target="_blank"><img src="<?php echo esc_url(AWP_ASSETS.'/images/logos/trigger.png'); ?>" height="50" alt="trigger Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager(sanitize_text_field($_GET['tab']));
                    $instruction_obj->prepare_instructions();
                        
                ?>

                <br />

                <?php 

                $form_fields = '';
                $app_name= 'trigger';
                $trigger_form = new AWP_Form_Fields($app_name);

                $form_fields = $trigger_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_trigger_display_name",
                        'name'          => "awp_trigger_display_name",
                        'value'         => $display_name,
                        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
                        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $trigger_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_trigger_token",
                        'name'          => "awp_trigger_token",
                        'value'         => $api_token,
                        'placeholder'   =>  esc_html__('Enter Token for this account', 'automate_hub' ),
                        'label'         =>  esc_html__('Token', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $trigger_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_trigger_api_key",
                        'name'          => "awp_trigger_api_key",
                        'value'         => $api_key,
                        'placeholder'   => esc_html__( 'Enter your Trigger API Key', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Trigger API Key', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $trigger_form->awp_wp_hidden_input(
                    array(
                        'name'          => "action",
                        'value'         => 'awp_trigger_save_api_token',
                    )
                );


                $form_fields .= $trigger_form->awp_wp_hidden_input(
                    array(
                        'name'          => "_nonce",
                        'value'         =>$nonce,
                    )
                );
                $form_fields .= $trigger_form->awp_wp_hidden_input(
                    array(
                        'name'          => "id",
                        'value'         =>wp_unslash($id),
                    )
                );


                $trigger_form->render($form_fields);

                ?>


        </div>

        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                 $data=[
                    'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('trigger');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();

                ?>
                        </form>
                </div>
        <?php
    }

    public function awp_save_trigger_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_trigger_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_key = sanitize_text_field($_POST["awp_trigger_api_key"]);
        $display_name     = sanitize_text_field( $_POST["awp_trigger_display_name"] );
        $token     = sanitize_text_field( $_POST["awp_trigger_token"] );

        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('trigger');
        $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_key, 'client_id' => $token]);

        AWP_redirect("admin.php?page=automate_hub&tab=trigger");
    } 
    
    public function load_custom_script() {
        wp_enqueue_script( 'awp-trigger-script', AWP_URL . '/apps/t/trigger/trigger.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="trigger-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'trigger',
                           'app_name'=>'Trigger',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/trigger.png',
                           'app_icon_alter_text'=>'Trigger  Icon',
                           'account_select_onchange'=>'getOthers',
                           'tasks'=>array(
                                'createcompany'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Status',
                                            'type'=>'select',
                                            'name'=>"status",
                                            'required' => 'required',
                                            'onchange' => 'selectedStatus',
                                            'option_for_loop'=>'(item) in data.statuslist',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Status...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': statusLoading}",
                                                        )
                                        ),
                                        array(
                                            'label'=>'Select Currency',
                                            'type'=>'select',
                                            'name'=>"currency",
                                            'required' => 'required',
                                            'onchange' => 'selectedCurrency',
                                            'option_for_loop'=>'(item) in data.currencyList',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Currency...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': currencyLoading}",
                                                        )
                                        )
                                    ),
                                ),
                                'trigger_createproject'=>array(
                                    'task_assignments'=>array( 
                                        array(
                                            'label'=>'Select Status',
                                            'type'=>'select',
                                            'name'=>"projectstatus",
                                            'required' => 'required',
                                            'onchange' => 'getcompanylist',
                                            'option_for_loop'=>'(item) in data.projectStatusList',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Status...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': statusLoading}",
                                                        )
                                        ),
                                        array(
                                            'label'=>'Select Company',
                                            'type'=>'select',
                                            'name'=>"company_id",
                                            'required' => 'required',
                                            'onchange' => 'getBillableType',
                                            'option_for_loop'=>'(item) in data.companylist',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Company...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': projectlistLoading}",
                                                        )
                                        ),
                                       
                                        array(
                                            'label'=>'Select Billable Type',
                                            'type'=>'select',
                                            'name'=>"billable_type",
                                            'required' => 'required',
                                            'onchange' => 'selectedBillabletype',
                                            'option_for_loop'=>'(item) in data.billableTypeList',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.name}}',
                                            'select_default'=>'Select Billable Type...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': billableLoading}",
                                                        )
                                        )

                                    ),
                                ),

                            ),
                    ); 

                        require (AWP_VIEWS.'/awp_app_integration_format.php');
                ?>
            </script>
        <?php
    }

    
    public function awp_trigger_fetch_companylist() {
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
        $platform_obj= new AWP_Platform_Shell_Table('trigger');

        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key = $data->api_key;
        $token = $data->client_id;

        $url = "https://www.triggerapp.com/api/v1/companies";

        $args =  array(
            "headers" => array(
                'Content-Type' => 'application/json'
            ),
            'body' => array(
                "token" => $token,
                "api_key"  => $api_key,
                "format" => "json"
            )
        );

        $response = wp_remote_get( $url, $args );

        $body = wp_remote_retrieve_body( $response );
        $body = json_decode( $body, true );
        
        $awp_trigger_companies = array();

        foreach($body["companies"] as $company){
            array_push($awp_trigger_companies, $company['company']);
        }

        wp_send_json_success($awp_trigger_companies);

    }

};


$Awp_Trigger = new AWP_Trigger();

function awp_trigger_save_integration() {
    Appfactory::save_integration();
}

function awp_trigger_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('trigger');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $token=$temp->client_id;
    
    $task = $decoded_data["task"]; 

   

    if( $task == "createcompany" ) {

        $name = empty( $data["name"] ) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $status = empty( $data["status"] ) ? "" : awp_get_parsed_values($data["status"], $posted_data);
        $address1 = empty( $data["address1"] ) ? "" : awp_get_parsed_values($data["address1"], $posted_data);
        $city = empty( $data["city"] ) ? "" : awp_get_parsed_values($data["city"], $posted_data);
        $postcode = empty( $data["postcode"] ) ? "" : awp_get_parsed_values($data["postcode"], $posted_data);
        $state = empty( $data["state"] ) ? "" : awp_get_parsed_values($data["state"], $posted_data);
        $phone = empty( $data["phone"] ) ? "" : awp_get_parsed_values($data["phone"], $posted_data);
        $country = empty( $data["country"] ) ? "" : awp_get_parsed_values($data["country"], $posted_data);
        $currency_symbol = empty( $data["currency"] ) ? "" : awp_get_parsed_values($data["currency"], $posted_data);

        $body = "company[name]=".$name."&company[billable]=true&company[status]=".$status."&company[address1]=".$address1."&company[city]=".$city."&company[postcode]=".$postcode."&company[state]=".$state."&company[phone]=".$phone."&company[country]=".$country."&company[currency_symbol]=". $currency_symbol."&token=".$token."&api_key=".$api_key;

        $args = [
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded'
            ),

            'body'=> $body
        ];

        $url = "https://www.triggerapp.com/api/v1/companies";


        $response  = wp_remote_post($url,  $args);

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    } else if($task == "trigger_createproject"){

     

        $name = empty( $data["projectname"] ) ? "" : awp_get_parsed_values($data["projectname"], $posted_data);
        $description = empty( $data["description"] ) ? "" : awp_get_parsed_values($data["description"], $posted_data);
        $due_date = empty( $data["due_date"] ) ? "" : awp_get_parsed_values($data["due_date"], $posted_data);
        $billable_type = empty( $data["billable_type"] ) ? "" : awp_get_parsed_values($data["billable_type"], $posted_data);
        $status = empty( $data["projectstatus"] ) ? "" : awp_get_parsed_values($data["projectstatus"], $posted_data);
        $companyid = empty( $data["company_id"] ) ? "" : awp_get_parsed_values($data["company_id"], $posted_data);
     

        $body = "project[name]=".$name."&project[description]=".$description."&project[due_date]=".$due_date."&project[billable]=true&project[billable_type]=".$billable_type."&project[state]=".$status."&token=".$token."&api_key=".$api_key;
        
        $url = "https://www.triggerapp.com/api/v1/companies/".$companyid."/projects";

        $args = [
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded',
            ),

            'body'=> $body
        ];
        
        $response  = wp_remote_post($url,  $args );

        $args['headers']['Authorization']="XXXXXXXXXXXX";

        awp_add_to_log( $response, $url, $args, $record );

    }
    return $response;
}


function awp_trigger_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('trigger');
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
