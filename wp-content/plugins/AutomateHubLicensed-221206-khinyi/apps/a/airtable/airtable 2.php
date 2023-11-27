<?php

class AWP_Airtable {
    private static $instance = null;
    public static $url = "https://api.airtable.com/v0";

    public static function get_instance() {
        if ( empty( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        $this->init_filters();
        $this->init_actions();
    }

    public function init_actions(){
        add_action( 'admin_post_awp_airtable_save_api_key'  , [ $this, 'save_api_key'], 10, 0 );
        add_action( 'awp_custom_script', [$this, 'load_custom_script']);
        add_action( 'awp_settings_view', [$this, 'add_settings_view'], 10, 1 );
        add_action( 'awp_action_fields', [$this, 'add_action_fields']);
        add_action( 'wp_ajax_awp_fetch_table_fields', [$this, 'fetch_table_fields']);
    }
    
    public function init_filters(){
        add_filter( 'awp_action_providers', [$this, 'add_action_provider'], 10, 1 );

        add_filter( 'awp_settings_tabs', [$this, 'add_settings_tab'], 10, 1 );
    }

    public function save_api_key() {
        if ( ! current_user_can('administrator') ){
            die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
        }

        // Security Check
        if (! wp_verify_nonce( $_POST['_nonce'], 'awp_airtable_api_key' ) ) {
            die( esc_html__( 'Security check Failed', 'automate_hub' ) );
        }

        $api_key = sanitize_text_field( $_POST["awp_airtable_api_key"] );
        $display_name     = sanitize_text_field( $_POST["awp_airtable_display_name"] );

        
        // Save tokens
        $platform_obj= new AWP_Platform_Shell_Table('airtable');
        $platform_obj->save_platform(['account_name'=>$display_name,'api_key'=>$api_key]);
        AWP_redirect( "admin.php?page=automate_hub&tab=airtable" ); 
    }

    public function add_settings_tab( $tabs ) {
        $tabs['airtable'] = array('name'=>esc_html__( 'Airtable', 'automate_hub'), 'cat'=>array('crm'));
        return $tabs;
    }

    public function load_custom_script() {
        wp_enqueue_script( 'awp-airtable-script', AWP_URL . '/apps/a/airtable/airtable.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function add_action_provider( $providers ) {
        $providers['airtable'] = [
            'title' => __( 'Airtable', 'automate_hub' ),
            'tasks' => array(
                'create_record'   => __( 'Create Record', 'automate_hub' )
                )
            ];

        return  $providers;
    }

    public function fetch_table_fields() {
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
        $platform_obj= new AWP_Platform_Shell_Table('airtable');
        $data=$platform_obj->awp_get_platform_detail_by_id($id);
        if(!$data){
            die( esc_html__( 'No Data Found', 'automate_hub' ) );
        }
        $api_key =$data->api_key;

        $tableName = rawurlencode($_POST['tableName']);

        $url = self::$url.'/'.$_POST['tableId'].'/'.$tableName."?maxRecords=1&view=Grid%20view";
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$api_key,
                'User-Agent' => "Sperse (https://www.sperse.com/)"
            )
        );

        $response = wp_remote_get( $url, $args );
        $body = wp_remote_retrieve_body( $response );
        $body = json_decode( $body );
        wp_send_json_success($body);
    }

    public function add_settings_view( $current_tab ) {
        
        if( $current_tab != 'airtable' ) { return; }

        $nonce     = wp_create_nonce( "awp_airtable_api_key" );
        $id = isset($_GET['id']) ? intval( sanitize_text_field($_GET['id'] )) : "";
        $api_key = isset($_GET['api_key']) ? sanitize_text_field($_GET['api_key']) : "";
        $display_name     = isset($_GET['account_name']) ? sanitize_text_field($_GET['account_name']) : "";

        ?>
            <div class="platformheader">

                <a href="https://sperse.io/go/airtable" target="_blank"><img src="<?php echo esc_url(AWP_ASSETS.'/images/logos/airtable.png'); ?>" width="221" height="50" alt="Airtable Logo"></a><br/><br/>
                <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager(sanitize_text_field($_GET['tab']));
                    $instruction_obj->prepare_instructions();
                        
                ?>
                <br/>
                <?php
                $form_fields = '';
                $app_name= 'airtable';
                $airtable_form = new AWP_Form_Fields($app_name);

                $form_fields = $airtable_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_airtable_display_name",
                        'name'          => "awp_airtable_display_name",
                        'value'         => $display_name,
                        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
                        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $airtable_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_airtable_api_key",
                        'name'          => "awp_airtable_api_key",
                        'value'         => $api_key,
                        'placeholder'   => esc_html__( 'Enter your Airtable API Key', 'automate_hub' ),
                        'label'         =>  esc_html__( 'Airtable API Key', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $airtable_form->awp_wp_hidden_input(
                    array(
                        'name'          => "action",
                        'value'         => 'awp_airtable_save_api_key',
                    )
                );


                $form_fields .= $airtable_form->awp_wp_hidden_input(
                    array(
                        'name'          => "_nonce",
                        'value'         =>$nonce,
                    )
                );
                $form_fields .= $airtable_form->awp_wp_hidden_input(
                    array(
                        'name'          => "id",
                        'value'         =>wp_unslash($id),
                    )
                );


                $airtable_form->render($form_fields);
                ?>



            </div>


            <div class="wrap">
                    <form id="form-list" method="post">
                        
             
                        <input type="hidden" name="page" value="automate_hub"/>

                        <?php
                        $data=[
                            'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                        ];
                        $platform_obj= new AWP_Platform_Shell_Table('airtable');
                        $platform_obj->initiate_table($data);
                        $platform_obj->prepare_items();
                        $platform_obj->display_table();
                        
                        ?>
                    </form>
            </div>
        <?php
    }

    public function add_action_fields() {
        ?>
            <script type="text/template" id="airtable-action-template">
                <?php

                $app_data=array(
                    'app_slug'=>'airtable',
                   'app_name'=>'Airtable',
                   'app_icon_url'=>AWP_ASSETS.'/images/icons/airtable.png',
                   'app_icon_alter_text'=>'Airtable Icon',
                   'account_select_onchange'=>'',
                   'tasks'=>array(
                                'create_record'=>array(
                                                    'task_assignments'=>array(

                                                                            array(
                                                                                'label'=>'Provide Table Link Or Workspace ID',
                                                                                'type'=>'text',
                                                                                'disabled'=>'loading',
                                                                                'name'=>"table_id",
                                                                                'model'=>'tableId',
                                                                                'placeholder'=>'https://airtable.com/appb6efh...',
                                                                                'size'=>'50',
                                                                                'spinner'=>array(
                                                                                                'bind-class'=>"{'is-active': loading}",
                                                                                            )
                                                                            ),
                                                                            array(
                                                                                'label'=>'Provide Table Name',
                                                                                'type'=>'text',
                                                                                'disabled'=>'loading',
                                                                                'name'=>"table_name",
                                                                                'model'=>'tableName',
                                                                                'placeholder'=>'Table 1',
                                                                                'spinner'=>array(
                                                                                                'bind-class'=>"{'is-active': loading}",
                                                                                            )
                                                                            ),

                                                                            array(
                                                                                'label'=>'',
                                                                                'type'=>'button',
                                                                                'text'=>'Fetch Table Fields',
                                                                                'onclick'=>'getFields'
                                                                            ),

                                                                        ),

                                                ),

                            ),
                ); 

                require (AWP_VIEWS.'/awp_app_integration_format.php');
                ?>
            </script>
        <?php
    }
}


$Awp_Airtable = AWP_Airtable::get_instance();

function awp_airtable_save_integration() {
    $params = array();
    parse_str( awp_sanitize_text_or_array_field( $_POST['formData'] ), $params );

    $trigger_data      = isset( $_POST["triggerData"]) ? awp_sanitize_text_or_array_field( $_POST["triggerData"]) : array();
    $action_data       = isset( $_POST["actionData" ]) ? awp_sanitize_text_or_array_field( $_POST["actionData" ]) : array();
    $field_data        = isset( $_POST["fieldData"  ]) ? awp_sanitize_text_or_array_field( $_POST["fieldData"  ]) : array();

    $integration_title = isset( $trigger_data["integrationTitle"]) ? $trigger_data["integrationTitle"] : "";
    $form_provider_id  = isset( $trigger_data["formProviderId"  ]) ? $trigger_data["formProviderId"  ] : "";
    $form_id           = isset( $trigger_data["formId"          ]) ? $trigger_data["formId"          ] : "";
    $form_name         = isset( $trigger_data["formName"        ]) ? $trigger_data["formName"        ] : "";
    $action_provider   = isset( $action_data ["actionProviderId"]) ? $action_data ["actionProviderId"] : "";
    $task              = isset( $action_data ["task"            ]) ? $action_data ["task"            ] : "";
    $type              = isset( $params["type"] ) ? $params["type"] : "";
    $table_name        = isset( $params["table_name"] ) ? $params["table_name"] : "";
    $table_id          = isset( $params["table_id"] ) ? $params["table_id"] : "";

    
    $all_data = array(
        'trigger_data' => $trigger_data,
        'action_data'  => $action_data,
        'field_data'   => $field_data,
        'table_id'     => $table_id,
        'table_name'   => $table_name
    );

    global $wpdb;

    $integration_table = $wpdb->prefix . 'awp_integration';

    if ( $type == 'new_integration' ) {

        $result = $wpdb->insert(
            $integration_table,
            array(
                'title'           => $integration_title,
                'form_provider'   => $form_provider_id,
                'form_id'         => $form_id,
                'form_name'       => $form_name,
                'action_provider' => $action_provider,
                'task'            => $task,
                'data'            => json_encode( $all_data, true ),
                'status'          => 1
            )
        );
        if($result){
            $platform_obj= new AWP_Platform_Shell_Table($action_provider);
            $platform_obj->awp_add_new_spot($wpdb->insert_id,$field_data['activePlatformId']);
        }
    }

    if ( $type == 'update_integration' ) {

        $id = esc_sql( trim( $params['edit_id'] ) );

        if ( $type != 'update_integration' &&  !empty( $id ) ) {
            exit;
        }

        $result = $wpdb->update( $integration_table,
            array(
                'title'           => $integration_title,
                'form_provider'   => $form_provider_id,
                'form_id'         => $form_id,
                'form_name'       => $form_name,
                'data'            => json_encode( $all_data, true ),
            ),
            array(
                'id' => $id
            )
        );
    }
    $return=array();
    $return['type']=$type;
    $return['result']=$result;
    $return['insertid']=$wpdb->insert_id;
    return $return;
   
}


function awp_airtable_send_data( $record, $posted_data ) {
    $record_data = json_decode(($record["data"]), true );
    $temp    = $record_data["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('airtable');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_key=$temp->api_key;
    if( array_key_exists( "cl", $record_data["action_data"] ) ) {
        if( $record_data["action_data"]["cl"]["active"] == "yes" ) {
            if( !awp_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data ) ) {
                return;
            }
        }
    }

    $field_data = $record_data["field_data"];
    
    unset($field_data["listId"]);
    unset($field_data['activePlatformId']);
    $task = $record["task"];
    $table_name = $record_data["table_name"];
    $table_id = $record_data["table_id"];

    if( $task == "create_record" ) {
        
        $fields = array();
        foreach ( $field_data as $key => $value ) {
            if(strpos($key, 'dis') !== false){
                continue;
            }
      
            $fields[$key] = awp_get_parsed_values( $field_data[$key], $posted_data );
        }

        if ( !empty( $fields ) ) {
            $table_name = rawurlencode($table_name);

            $url = AWP_Airtable::$url.'/'.$table_id.'/'.$table_name;
            $body = json_encode([
                "records" => [
                    [
                        'fields' => $fields
                    ]
                ],
            ]);

            $request = [
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer '.$api_key,
                    'User-Agent' => "Sperse (https://www.sperse.com/)"
                ),
    
                'body'=> $body
            ];

            $response = wp_remote_post( $url, $request );

            awp_add_to_log( $response, $url, $request, $record );
        }
    }

    return $response;
}


function awp_airtable_resend_data($log_id,$data,$integration){
    
        $temp    = json_decode( $integration["data"], true );
        $temp    = $temp["field_data"];
        $platform_obj= new AWP_Platform_Shell_Table('airtable');
        $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
        $api_key=$temp->api_key;

        if(!$api_key ) {
            exit;
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
        $headers = array(
                "Authorization" => "Bearer ".$api_key,
                "Content-Type"  => "application/json",
                'User-Agent' => "Sperse (https://www.sperse.com/)"
        );

        
        $temp = $body;
        $body=json_encode($body);
        $args = array(
                "headers" => $headers,
                "body" => $body
        );

        $return = wp_remote_post( $url, $args );

        $args['headers']['Authorization']='api_key  XXXXXXXXXXX';
        $args["body"]=$temp;
        awp_add_to_log( $return, $url, $args, $integration );


        $response['success']=true;
        return $response;
}
