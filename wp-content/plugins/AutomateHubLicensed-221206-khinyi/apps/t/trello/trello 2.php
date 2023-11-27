<?php
add_filter( 'awp_action_providers', 'awp_trello_actions', 10, 1 );
function awp_trello_actions( $actions ) {
    $actions['trello'] = array(
        'title' => __( 'Trello', 'automate_hub' ),
        'tasks' => array(
            'add_card'   => __( 'Add New Card', 'automate_hub' ),
        )
    );
    return $actions;
}

add_filter( 'awp_settings_tabs', 'awp_trello_settings_tab', 10, 1 );

function awp_trello_settings_tab( $providers ) {
    $providers['trello'] = __( 'Trello', 'automate_hub' );
    return $providers;
}

add_action( 'awp_settings_view', 'awp_trello_settings_view', 10, 1 );

function get_permission_url(){
    return 'https://trello.com/1/authorize?expiration=never&name=Automate%20Hub&scope=read%2Cwrite%2Caccount&response_type=token&key='.awp_trello_get_api_key();
}
function awp_trello_settings_view( $current_tab ) {
    if( $current_tab != 'trello' ) { return; }
    $nonce     = wp_create_nonce( 'awp_trello_settings' );
    $api_key   = awp_trello_get_api_key();
    $id = isset($_GET['id']) ? $_GET['id'] : "";
    $api_keys = isset($_GET['api_key']) ? $_GET['api_key'] : "";
    $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";
    ?>
    <div class="platformheader">
    <a href="https://sperse.io/go/trello" target="_blank"><img src="<?php echo AWP_ASSETS; ?>/images/logos/trello.png" width="180" height="50" alt="Trello Logo"></a><br/><br/>
    <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?>
                <br/>   
                <a href="#" id="trelloauthbtn" target="_blank" class="button button-primary">Sign in with Trello</a>
                        
                        <script type="text/javascript">

                            jQuery("#trelloauthbtn").unbind().click(function(e) {
                                e.preventDefault();
                                var win=window.open('<?php echo get_permission_url(); ?>','popup','width=600,height=600');
                            });
                       
                    </script>           
                    <?php 

                $form_fields = '';
                $app_name= 'trello';
                $trello_form = new AWP_Form_Fields($app_name);

                $form_fields = $trello_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_trello_display_name",
                        'name'          => "awp_trello_display_name",
                        'value'         => $display_name,
                        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
                        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $trello_form->awp_wp_text_input(
                    array(
                        'id'            => "awp_trello_api_token",
                        'name'          => "awp_trello_api_token",
                        'value'         => $api_key,
                        'placeholder'   => esc_html__( 'Please enter API Token', 'automate_hub' ),
                        'label'         =>  esc_html__( 'API Token', 'automate_hub' ),
                        'wrapper_class' => 'form-row',
                        'show_copy_icon'=>true
                        
                    )
                );

                $form_fields .= $trello_form->awp_wp_hidden_input(
                    array(
                        'name'          => "action",
                        'value'         => 'awp_trello_save_api_token',
                    )
                );


                $form_fields .= $trello_form->awp_wp_hidden_input(
                    array(
                        'name'          => "_nonce",
                        'value'         =>$nonce,
                    )
                );
                $form_fields .= $trello_form->awp_wp_hidden_input(
                    array(
                        'name'          => "id",
                        'value'         =>wp_unslash($id),
                    )
                );


                $trello_form->render($form_fields);

                ?>
    </div>
    <div class="wrap">
        <form id="form-list" method="post">
                    
            
            <input type="hidden" name="page" value="automate_hub"/>

            <?php
            $data=[
                        'table-cols'=>['account_name'=>'Display name','api_key'=>'API Token','spots'=>'Active Spots','active_status'=>'Active']
                ];
            $platform_obj= new AWP_Platform_Shell_Table('trello');
            $platform_obj->initiate_table($data);
            $platform_obj->prepare_items();
            $platform_obj->display_table();
                    
            ?>
        </form>
    </div>
    <?php 
}

add_action( 'admin_post_awp_trello_save_api_token', 'awp_save_trello_api_token', 10, 0 );

function awp_save_trello_api_token() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'awp_trello_settings' ) ) {
        die( __( 'Security check Failed', 'automate_hub' ) );
    }
    $api_token = sanitize_text_field( $_POST["awp_trello_api_token"] );
    $display_name     = sanitize_text_field( $_POST["awp_trello_display_name"] );
    // Save tokens
    $platform_obj= new AWP_Platform_Shell_Table('trello');
    $platform_obj->save_platform(['account_name'=>$display_name,'api_key'=>$api_token]);
    
    awp_redirect( "admin.php?page=automate_hub&tab=trello" );
}

add_action( 'awp_action_fields', 'awp_trello_action_fields' );

function awp_trello_action_fields() {
    ?>
    <script type="text/template" id="trello-action-template">
                <?php

                    $app_data=array(
                            'app_slug'=>'trello',
                           'app_name'=>'Trello',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/trello.png',
                           'app_icon_alter_text'=>'Trello Icon',
                           'account_select_onchange'=>'getTrelloList',
                           'tasks'=>array(
                                        'add_card'=>array(
                                                            'task_assignments'=>array(

                                                                                    array(
                                                                                        'label'=>'Select Trello Board',
                                                                                        'type'=>'select',
                                                                                        'name'=>"fieldData[boardId]",
                                                                                        'model'=>'fielddata.boardId',
                                                                                        'required'=>'required',
                                                                                        'onchange'=>'getLists',
                                                                                        'select_default'=>'Select Board...',
                                                                                        'option_for_loop'=>'(item, index) in fielddata.boards',
                                                                                        'spinner'=>array(
                                                                                                        'bind-class'=>"{'is-active': boardLoading}",
                                                                                                    )
                                                                                    ),
                                                                                    array(
                                                                                        'label'=>'Select Trello List',
                                                                                        'type'=>'select',
                                                                                        'name'=>"fieldData[listId]",
                                                                                        'model'=>'fielddata.listId', 
                                                                                        'required'=>'required',                     
                                                                                        'select_default'=>'Select List...',
                                                                                        'option_for_loop'=>'(item, index) in fielddata.lists',
                                                                                        'spinner'=>array(
                                                                                                        'bind-class'=>"{'is-active': listLoading}",
                                                                                                    )
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

function awp_trello_get_api_key() {
    return '3555cdefe4dd766c94bac76564e07695';
}

add_action( 'wp_ajax_awp_get_trello_boards', 'awp_get_trello_boards', 10, 0 );

/* Get Trello add_cardr lists */
function awp_get_trello_boards() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
        die( __( 'Security check Failed', 'automate_hub' ) );
    }
    $api_key   = awp_trello_get_api_key();
    if (!isset( $_POST['platformid'] ) ) {
        die( esc_html__( 'Invalid Request', 'automate_hub' ) );
    }
    $id=sanitize_text_field($_POST['platformid']);
    $platform_obj= new AWP_Platform_Shell_Table('trello');
    $data=$platform_obj->awp_get_platform_detail_by_id($id);
    if(!$data){
        die( esc_html__( 'No Data Found', 'automate_hub' ) );
    }
    $api_token =$data->api_key;
    
    if( !$api_key || !$api_token ) {
        return array();
    }
    $args = array(
        'headers' => array(
            'Content-Type'  => 'application/json'
        )
    );
    $url = "https://api.trello.com/1/members/me/boards?&filter=open&key={$api_key}&token={$api_token}";
    $result = wp_remote_get( $url, $args);
    if( is_wp_error( $result ) || '200' != $result['response']['code'] ) {
        wp_send_json_error();
    }
    $body = json_decode( wp_remote_retrieve_body( $result ) );
    $boards = wp_list_pluck( $body, 'name', 'id' );
    wp_send_json_success( $boards );
}

add_action( 'wp_ajax_awp_get_trello_lists', 'awp_get_trello_lists', 10, 0 );

function awp_get_trello_lists() {
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
        die( __( 'Security check Failed', 'automate_hub' ) );
    }
    $api_key   = awp_trello_get_api_key();
    if (!isset( $_POST['platformid'] ) ) {
        die( esc_html__( 'Invalid Request', 'automate_hub' ) );
    }
    $id=sanitize_text_field($_POST['platformid']);
    $platform_obj= new AWP_Platform_Shell_Table('trello');
    $data=$platform_obj->awp_get_platform_detail_by_id($id);
    if(!$data){
        die( esc_html__( 'No Data Found', 'automate_hub' ) );
    }
    $api_token =$data->api_key;
    
    $board_id  = isset( $_POST['boardId'] ) ? $_POST['boardId'] : '';
    if( !$api_key || !$api_token || !$board_id ) {
        return array();
    }
    $args = array(
        'headers' => array(
            'Content-Type'  => 'application/json'
        )
    );
    $url = "https://api.trello.com/1/boards/{$board_id}/lists?filter=open&key={$api_key}&token={$api_token}";
    $result = wp_remote_get( $url, $args);
    if( is_wp_error( $result ) || '200' != $result['response']['code'] ) {
        wp_send_json_error();
    }
    $body = json_decode( wp_remote_retrieve_body( $result ) );
    $lists = wp_list_pluck( $body, 'name', 'id' );
    wp_send_json_success( $lists );
}

/* Saves connection mapping */
function awp_trello_save_integration() {
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
            return;
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

/* Handles sending data to Trello API */
function awp_trello_send_data( $record, $posted_data ) {
    $api_key   = awp_trello_get_api_key();
    $temp    = json_decode( $record["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('trello');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_token=$temp->api_key;
    
    if( !$api_key || !$api_token ) {
        return;
    }
    $record_data    = json_decode( $record["data"], true );
    if( array_key_exists( "cl", $record_data["action_data"] ) ) {
        if( $record_data["action_data"]["cl"]["active"] == "yes" ) {
            if( !awp_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data ) ) {
                return;
            }
        }
    }
    $data    = $record_data["field_data"];
    $task    = $record["task"];
    if( $task == "add_card" ) {
        $board_id    = $data["boardId"];
        $list_id     = $data["listId"];
        $name        = empty( $data["name"] ) ? "" : awp_get_parsed_values( $data["name"], $posted_data );
        $description = empty( $data["description"] ) ? "" : awp_get_parsed_values( $data["description"], $posted_data );
        $url         = "https://api.trello.com/1/cards?key={$api_key}&token={$api_token}&idList={$list_id}";
        $pos         = empty( $data["pos"] ) ? "" : awp_get_parsed_values( $data["pos"], $posted_data );
        $body = array(
            'name' => $name,
            'desc' => $description,
            'pos'  => $pos
        );
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode( $body )
        );
        $return = wp_remote_post( $url, $args );

        $url         = "https://api.trello.com/1/cards?key=XXXXXXXXX&token=XXXXXXXXX&idList=XXXXXXXXXX";
        awp_add_to_log( $return, $url, $args, $record );
    }
    return $return;
}



function awp_trello_resend_data( $log_id, $data, $integration ) {

    

    $api_key   = awp_trello_get_api_key();

    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('trello');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_token=$temp->api_key;
    
    if( !$api_key || !$api_token ) {
        return;
    }
    $data=stripslashes($data);
    $data=preg_replace('/\s+/', '',$data); 
    $data=str_replace('"{', '{', $data);
    $data=str_replace('}"', '}', $data);        
    $data=json_decode($data,true);
    $body=$data['args']['body'];
    $temp    = json_decode( $integration["data"], true );
    $list_id    = $temp["field_data"]['listId'];

    
        $url         = "https://api.trello.com/1/cards?key={$api_key}&token={$api_token}&idList={$list_id}";
        
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode( $body )
        );
        $return = wp_remote_post( $url, $args );

        $url         = "https://api.trello.com/1/cards?key=XXXXXXXXX&token=XXXXXXXXX&idList=XXXXXXXXXX";
        awp_add_to_log( $return, $url, $args, $integration );

    $response['success']=true;    
    return $response;
}
