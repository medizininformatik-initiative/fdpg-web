<?php


class AWP_LionDesk{

    const service_name            = 'liondesk';
    const authorization_endpoint  = 'https://api-v2.liondesk.com//oauth2/authorize';
    const token_endpoint          = 'https://api-v2.liondesk.com//oauth2/token';
    const service_url      = 'https://sperse.io/scripts/authorization/auth.php';
    const client_id = '9e3840a365bf3990be302a5d3628c99df437d780';
    
    private static $instance = null;

    public static function get_instance() {
        if ( empty( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {   
        $this->init_filters();                  // Initialize the filter hooks
        $this->init_actions();                  // Initialize the action hooks 
    }
    public function init_actions(){
        add_action( 'awp_settings_view', [$this, 'awp_liondesk_settings_view'], 10, 1 );
        add_action( "rest_api_init", [$this, "create_webhook_route"]);
        add_action( 'awp_action_fields', [$this, 'awp_liondesk_action_fields']);
        add_action( 'awp_add_js_fields', [$this, 'awp_liondesk_js_fields'], 10, 1 );
    }

    public function init_filters(){
        add_filter( 'awp_action_providers', [$this, 'awp_liondesk_actions'], 10, 1 );
        add_filter( 'awp_settings_tabs', [$this, 'awp_liondesk_settings_tab'], 10, 1 );
    }


    protected function get_callback() {
        return get_rest_url(null,'automatehub/liondesk');
    }
    public function getLoginURL():string {
        $query = [
            'response_type' => "code",
            'client_id'=> self::client_id,
            'redirect_uri' => self::service_url,
            'state'=> $this->get_callback(), 
        ];
    
        
    
        return add_query_arg( $query , self::authorization_endpoint);
    }
    public function create_webhook_route() {
        register_rest_route( 'automatehub', '/liondesk',
        [
            'methods' => 'GET', 
            'callback' => [$this, 'get_webhook_data'], 
            'permission_callback' => function() { return ''; }
        ]);
    }
    public function get_webhook_data( $request ) {
        global $wpdb;
        $params = $request->get_params();
        if ( isset( $params['access_token'] ) ) {
            $query="select * from ".$wpdb->prefix."awp_platform_settings where platform_name='".self::service_name."'";
            $data=$wpdb->get_results($query);
            $len=count($data) + 1;
            $access_code = sanitize_text_field($params['access_token']);
            $platform_obj= new AWP_Platform_Shell_Table(self::service_name);

            $platform_obj->save_platform(['account_name'=>'Account Number '.$len,'api_key'=>$access_code]);
      
        }

        wp_safe_redirect( admin_url( 'admin.php?page=automate_hub&tab='.self::service_name ) );
        exit();

       
    }



    function awp_liondesk_actions( $actions ) {
        $actions['liondesk'] = array(
            'title' => esc_html__( 'LionDesk', 'automate_hub'),
            'tasks' => array('add_contact'   => esc_html__( 'Create New Contact', 'automate_hub'))
        );
        return $actions;
    }

    function awp_liondesk_settings_tab( $providers ) {
        $providers['liondesk'] =  array('name'=>esc_html__( 'LionDesk', 'automate_hub'),'cat'=>array('crm'));
        return $providers;
    }
    function awp_liondesk_settings_view( $current_tab ) {
        if( $current_tab != 'liondesk' ) { return; }
        $nonce     = wp_create_nonce( "awp_liondesk_settings" );
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $api_key = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";
        ?>
        <div class="platformheader">
        <a href="https://sperse.io/go/liondesk" target="_blank"><img src="<?php echo AWP_ASSETS; ?>/images/logos/liondesk.png" width="184" height="50" alt="LionDesk Logo"></a><br/><br/>
        <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?>
                <br/>
        <form name="liondesk_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
              method="post" class="container">
            <input type="hidden" name="action" value="awp_save_liondesk_api_key">
            <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>
            <input type="hidden" name="id" value="<?php echo $id ?>">

            <table class="form-table">

                <?php 
                        if(!empty($display_name)){
                    ?>
                        <tr valign="top">
                            <th scope="row"> <?php esc_html_e( 'Display name', 'automate_hub' ); ?></th>
                            <td>
                                <div class="form-table__input-wrap">
                                <input type="text" name="awp_liondesk_display_name" id="awp_liondesk_display_name" value="<?php echo $display_name ?>" placeholder="<?php esc_html_e( 'Enter Display name', 'automate_hub' ); ?>" class="basic-text"/>
                                <span class="spci_btn form-table__input-btn" data-clipboard-action="copy" data-clipboard-target="#awp_liondesk_display_name"><img src="<?php echo AWP_ASSETS; ?>/images/copy.png" alt="Copy to clipboard"></span>
                                </div>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row"></th>
                            <td>
                      
                                    <div class="submit-button-plugin"><?php submit_button(); ?></div>
                              
                            </td>
                        </tr>
                        

                    <?php
                        }
                        else{
                    ?>

                        <tr valign="top">
                            <th scope="row"> </th>
                            <td>
                                <a href="#" id="liondeskauthbtn" target="_blank" class="button button-primary"> Link Liondesk Account </a>
                            </td>
                        </tr>

                        <script type="text/javascript">
                            document.getElementById("liondeskauthbtn").addEventListener("click", function(e){
                                e.preventDefault();
                                var win=window.open('<?php echo $this->getLoginURL()?>','popup','width=600,height=600');
                                var id = setInterval(function() {
                                const queryString = win.location.search;
                                const urlParams = new URLSearchParams(queryString);
                                const page_type = urlParams.get('page');
                                if(page_type=='automate_hub'){win.close(); clearInterval(id); location.reload();}
                                }, 1000);
                            });
                        </script>
                    <?php
                        }
                    ?>
                
                
            </table>
            
        </form>
        </div>
        <div class="wrap">
            <form id="form-list" method="post">
                        
                
                <input type="hidden" name="page" value="automate_hub"/>

                <?php
                $data=[
                            'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                    ];
                $platform_obj= new AWP_Platform_Shell_Table('liondesk');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();
                        
                ?>
            </form>
        </div>
        <?php
    }

    function awp_liondesk_js_fields( $field_data ) {}
    function awp_liondesk_action_fields() {
        ?>
        <script type="text/template" id="liondesk-action-template">
              <?php
                        $app_data=array(
                                'app_slug'=>'liondesk',
                               'app_name'=>'Liondesk',
                               'app_icon_url'=>AWP_ASSETS.'/images/icons/liondesk.png',
                               'app_icon_alter_text'=>'Liondesk Icon',
                               'account_select_onchange'=>'',
                               'tasks'=>array(
                                            'add_contact'=>array(
                                                                
                                                            ),
                                                                                    
                                        ),
                            ); 

                            require (AWP_VIEWS.'/awp_app_integration_format.php');
            ?>
        </script>
        <?php
    }
}































/*
 * Saves connection mapping
 */
function awp_liondesk_save_integration() {
    $params = array();
    parse_str( awp_sanitize_text_or_array_field( $_POST['formData'] ), $params );

    $trigger_data = isset( $_POST["triggerData"] ) ? awp_sanitize_text_or_array_field( $_POST["triggerData"] ) : array();
    $action_data  = isset( $_POST["actionData"] ) ? awp_sanitize_text_or_array_field( $_POST["actionData"] ) : array();
    $field_data   = isset( $_POST["fieldData"] ) ? awp_sanitize_text_or_array_field( $_POST["fieldData"] ) : array();

    $integration_title = isset( $trigger_data["integrationTitle"] ) ? $trigger_data["integrationTitle"] : "";
    $form_provider_id  = isset( $trigger_data["formProviderId"] ) ? $trigger_data["formProviderId"] : "";
    $form_id           = isset( $trigger_data["formId"] ) ? $trigger_data["formId"] : "";
    $form_name         = isset( $trigger_data["formName"] ) ? $trigger_data["formName"] : "";
    $action_provider   = isset( $action_data["actionProviderId"] ) ? $action_data["actionProviderId"] : "";
    $task              = isset( $action_data["task"] ) ? $action_data["task"] : "";
    $type              = isset( $params["type"] ) ? $params["type"] : "";



    $all_data = array(
        'trigger_data' => $trigger_data,
        'action_data'  => $action_data,
        'field_data'   => $field_data
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

$AWP_LionDesk = AWP_LionDesk::get_instance();

function awp_liondesk_send_data( $record, $posted_data ) {

    $temp    = json_decode( $record["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('liondesk');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_key=$temp->api_key;
    
    if( !$api_key ) {
        return;
    }

    $data    = json_decode( $record["data"], true );
    $data    = $data["field_data"];
    $task    = $record["task"];

        $record_data = json_decode( $record["data"], true );
    if( array_key_exists( "cl", $record_data["action_data"]) ) {
        if( $record_data["action_data"]["cl"]["active"] == "yes" ) {
            $chk = awp_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data );
            if( !awp_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data ) ) {
                return;
            }
        }
    }


    if( $task == "add_contact" ) {
        $email      = empty( $data["email"] ) ? "" : awp_get_parsed_values( $data["email"], $posted_data );
        $first_name = empty( $data["firstName"] ) ? "" : awp_get_parsed_values( $data["firstName"], $posted_data );
        $last_name  = empty( $data["lasName"] ) ? "" : awp_get_parsed_values( $data["lasName"], $posted_data );

        $headers = array(
            "Authorization" => "Bearer " . $api_key,
            "Content-Type"  => "application/json"
        );

        $url = "https://api-v2.liondesk.com/contacts";

        $body = array(
            "first_name" => $first_name,
            "last_name"  => $last_name,
            "email"      => $email
        );

        $args = array(
            "headers" => $headers,
            "body" => json_encode( $body )
        );

        $response = wp_remote_post( $url, $args );
        $args['headers']['Authorization'] = "Bearer XXXXXXXXXXXX";
        $args['body']=$body;
        awp_add_to_log( $response, $url, $args, $record );
    }
    return $response;
}



function awp_liondesk_resend_data( $log_id,$data,$integration ) {

    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('liondesk');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_key=$temp->api_key;
   
    if( !$api_key ) {
        return;
    }

    $task=$integration['task'];
    $data=stripslashes($data);
    $data=preg_replace('/\s+/', '',$data); 
    $data=json_decode($data,true);
    $url=$data['url'];
    if(!$url){
        $response['success']=false;
        $response['msg']="Syntax Error! Request is invalid";
        return $response;
    }

    if( $task == "add_contact" ) {
        

        $headers = array(
            "Authorization" => "Bearer " . $api_key,
            "Content-Type"  => "application/json"
        );


        $args = array(
            "headers" => $headers,
            "body" => json_encode( $data['args']['body'] )
        );

        $response = wp_remote_post( $url, $args );
        $args['headers']['Authorization'] = "Bearer XXXXXXXXXXXX";
        $args['body']=$data['args']['body'];
        awp_add_to_log( $response, $url, $args, $integration );
    }

    $response['success']=true;
    return $response;
}
