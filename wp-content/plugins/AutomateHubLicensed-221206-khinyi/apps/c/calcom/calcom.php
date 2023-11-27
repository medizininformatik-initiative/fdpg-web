<?php

class AWP_Calcom extends appfactory
{

   
    public function init_actions(){
       
        add_action('admin_post_awp_calcom_save_api_token', [$this, 'awp_save_calcom_api_token'], 10, 0);
        
        add_action( 'wp_ajax_awp_fetch_eventtype', [$this, 'awp_fetch_eventtype']);
        add_action( 'wp_ajax_awp_fetch_bookinglist', [$this, 'awp_fetch_bookinglist']);
       
        
        
    }

    public function init_filters(){

        add_filter('awp_platforms_connections', [$this, 'awp_calcom_platform_connection'], 10, 1);
    }

    public function action_provider( $providers ) {
        $providers['calcom'] = array(
            'title' => esc_html__( 'Cal.com', 'automate_hub' ),
            'tasks' => array(
                'createbooking'   => esc_html__( 'Create Bookings', 'automate_hub' ),
                'addattendee'   => esc_html__( 'Add Attendee', 'automate_hub' )
            )
        );
    
        return  $providers;
    }

    public function settings_tab( $tabs ) {
        $tabs['calcom'] = array('name'=>esc_html__( 'Cal.com', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'calcom') {
            return;
        }
        $nonce = wp_create_nonce("awp_calcom_settings");
        $api_token = isset($_GET['api_key']) ? sanitize_text_field($_GET['api_key']) : "";
        $id = isset($_GET['id']) ? intval( sanitize_text_field($_GET['id'] )) : "";
        $display_name     = isset($_GET['account_name']) ? sanitize_text_field($_GET['account_name']) : "";
        ?>
        <div class="platformheader">

            <a href="https://sperse.io/go/calcom" target="_blank"><img src="<?php echo esc_url(AWP_ASSETS.'/images/logos/calcom.png'); ?>"  alt="Calcom Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager(sanitize_text_field($_GET['tab']));
                    $instruction_obj->prepare_instructions();
                        
                ?>

                <br />

                <?php 

                $form_fields = '';
                $app_name= 'calcom';
                $calcom_form = new AWP_Form_Fields($app_name);

                $form_fields = $calcom_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_calcom_display_name",
                        'name'          => "awp_calcom_display_name",
                        'value'         => $display_name,
                        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
                        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $calcom_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_calcom_api_token",
                        'name'          => "awp_calcom_api_token",
                        'value'         => $api_token,
                        'placeholder'   => esc_html__( 'Enter your Calcom Live API Key', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Calcom API Key', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $calcom_form->awp_wp_hidden_input(
                    array(
                        'name'          => "action",
                        'value'         => 'awp_calcom_save_api_token',
                    )
                );


                $form_fields .= $calcom_form->awp_wp_hidden_input(
                    array(
                        'name'          => "_nonce",
                        'value'         =>$nonce,
                    )
                );
                $form_fields .= $calcom_form->awp_wp_hidden_input(
                    array(
                        'name'          => "id",
                        'value'         =>wp_unslash($id),
                    )
                );


                $calcom_form->render($form_fields);

                ?>


        </div>

        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
                 $data=[
                    'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                ];
                $platform_obj = new AWP_Platform_Shell_Table('calcom');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();

                ?>
                        </form>
                </div>
        <?php
    }

    public function awp_save_calcom_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_calcom_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }

        $api_token = sanitize_text_field($_POST["awp_calcom_api_token"]);
        $display_name     = sanitize_text_field( $_POST["awp_calcom_display_name"] );

        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('calcom');
        $platform_obj->save_platform(['account_name'=>$display_name,'api_key' => $api_token]);

        AWP_redirect("admin.php?page=automate_hub&tab=calcom");
    } 
    
    public function load_custom_script() {
        wp_enqueue_script( 'awp-calcom-script', AWP_URL . '/apps/c/calcom/calcom.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function action_fields() {
        ?>
            <script type="text/template" id="calcom-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'calcom',
                           'app_name'=>'Calcom ',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/calcom.png',
                           'app_icon_alter_text'=>'Calcom  Icon',
                           'account_select_onchange'=>'gettimezone',
                           'tasks'=>array(
                                'createbooking'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Timezone',
                                            'type'=>'select',
                                            'name'=>"timezone",
                                            'required' => 'required',
                                            'onchange' => 'geteventtype',
                                            'option_for_loop'=>'(item) in data.timezonelist',
                                            'option_for_value'=>'item',
                                            'option_for_text'=>'{{item}}',
                                            'select_default'=>'Select Timezone...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': timezoneLoading}",
                                                        )
                                        ),
                                        array(
                                            'label'=>'Select Eventtype',
                                            'type'=>'select',
                                            'name'=>"eventtype",
                                            'required' => 'required',
                                            'onchange' => 'selectedeventtype',
                                            'option_for_loop'=>'(item) in data.eventypelist',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.title}}',
                                            'select_default'=>'Select Eventtype...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': eventtypeLoading}",
                                                        )
                                        )
                                        
                                    ),
                                ),
                                'addattendee'=>array(
                                    'task_assignments'=>array(
                                        array(
                                            'label'=>'Select Timezone',
                                            'type'=>'select',
                                            'name'=>"timezone",
                                            'required' => 'required',
                                            'onchange' => 'getbookings',
                                            'option_for_loop'=>'(item) in data.timezonelist',
                                            'option_for_value'=>'item',
                                            'option_for_text'=>'{{item}}',
                                            'select_default'=>'Select Timezone...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': timezoneLoading}",
                                                        )
                                        ),
                                        array(
                                            'label'=>'Select Booking Title',
                                            'type'=>'select',
                                            'name'=>"bookingId",
                                            'required' => 'required',
                                            'onchange' => 'selectedbookingId',
                                            'option_for_loop'=>'(item) in data.bookinglist',
                                            'option_for_value'=>'item.id',
                                            'option_for_text'=>'{{item.title}}',
                                            'select_default'=>'Select Booking Title...',
                                            'spinner'=>array(
                                                            'bind-class'=>"{'is-active': bookingsloading}",
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

    
    public function awp_fetch_eventtype() {
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
        $platform_obj= new AWP_Platform_Shell_Table('calcom');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;

        $url = "https://api.cal.com/v1/event-types?apiKey=".$api_key;
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json'
            )
        );

        $response = wp_remote_get( $url, $args );
        $body = wp_remote_retrieve_body( $response );
        $body = json_decode( $body, true );
        wp_send_json_success($body["event_types"]);
    }

    public function awp_fetch_bookinglist() {
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
        $platform_obj= new AWP_Platform_Shell_Table('calcom');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }

        $api_key =$data->api_key;

        $url = "https://api.cal.com/v1/bookings?apiKey=".$api_key;
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json'
            )
        );

        $response = wp_remote_get( $url, $args );
        $body = wp_remote_retrieve_body( $response );
        $body = json_decode( $body, true );
        wp_send_json_success($body["bookings"]);
    }
   
};


$Awp_Calcom = new AWP_Calcom();

function awp_calcom_save_integration() {
    Appfactory::save_integration();
}

function awp_calcom_send_data( $record, $posted_data ) {
    $decoded_data = appfactory::decode_data($record, $posted_data);
    $data = $decoded_data["data"]; 


    $platform_obj= new AWP_Platform_Shell_Table('calcom');
    $temp=$platform_obj->awp_get_platform_detail_by_id($data['activePlatformId']);
    $api_key=$temp->api_key;
    $task = $decoded_data["task"]; 

   
    if( $task == "createbooking" ) {

        $title = empty( $data["title"] ) ? "" : awp_get_parsed_values($data["title"], $posted_data);
        $startTime = empty( $data["startTime"] ) ? "" : awp_get_parsed_values($data["startTime"], $posted_data);
        $endTime = empty( $data["endTime"] ) ? "" : awp_get_parsed_values($data["endTime"], $posted_data);
        $name = empty( $data["name"] ) ? "" : awp_get_parsed_values($data["name"], $posted_data);
        $email = empty( $data["email"] ) ? "" : awp_get_parsed_values($data["email"], $posted_data);
        $eventtype = empty( $data["eventtype"] ) ? "" : awp_get_parsed_values($data["eventtype"], $posted_data);
        $timezone = empty( $data["timezone"] ) ? "" : awp_get_parsed_values($data["timezone"], $posted_data);
      
        $body = json_encode([
            "title"=>$title,
            "start"=>$startTime,
            "end"=>$endTime,
            "name"=>$name,
            "email"=>$email,
            "location"=>"",
            "eventTypeId"=> (integer) $eventtype,
            "timeZone"=> $timezone,
            "language"=> "en",
            "metadata" => (object) [],
            "customInputs" => []
        ]);

        $url = "https://api.cal.com/v1/bookings?apiKey=".$api_key;

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        awp_add_to_log( $response, $url, $args, $record );

    } else if( $task == "addattendee" ) {

        $bookingId = empty( $data["bookingId"] ) ? "" : awp_get_parsed_values($data["bookingId"], $posted_data);
        $aname = empty( $data["aname"] ) ? "" : awp_get_parsed_values($data["aname"], $posted_data);
        $aemail = empty( $data["aemail"] ) ? "" : awp_get_parsed_values($data["aemail"], $posted_data);
        $atimezone = empty( $data["atimezone"] ) ? "" : awp_get_parsed_values($data["atimezone"], $posted_data);
      
        $body = json_encode([
            "bookingId"=> (integer) $bookingId,
            "name" => $aname,
            "email" => $aemail,
            "timeZone" => $atimezone
        ]);

        $url = "https://api.cal.com/v1/attendees?apiKey=".$api_key;

        $args = [
            'headers' => array(
                'Content-Type' => 'application/json',
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            ),

            'body'=> $body
        ];

        $response  = wp_remote_post($url,  $args );

        awp_add_to_log( $response, $url, $args, $record );

    } 
    return $response;
}


function awp_calcom_resend_data( $log_id,$data,$integration ) {
    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('calcom');
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
