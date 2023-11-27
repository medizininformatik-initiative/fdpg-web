<?php

add_action( 'awp_custom_script', 'awp_varisend_custom_script' );

function awp_varisend_custom_script() {
    
    wp_enqueue_script( 'awp-select2-min-script', AWP_ASSETS.'/js/select2.min.js',true);
    wp_enqueue_style( 'awp-select2-min', AWP_ASSETS.'/css/select2.min.css',true);
}

add_filter( 'awp_action_providers', 'awp_varisend_actions', 10, 1 );

function awp_varisend_actions( $actions ) {
    $actions['varisend'] = array(
    'title' => esc_html__( 'Varisend', 'automate_hub'),
    'tasks' => array('add_contact'   => esc_html__( 'Create New Contact', 'automate_hub')));
    return $actions;
}

add_filter( 'awp_settings_tabs', 'awp_varisend_settings_tab', 10, 1 );
function awp_varisend_settings_tab( $providers ) {
    $providers['varisend'] = array('name'=>esc_html__( 'Varisend', 'automate_hub'), 'cat'=>array('sms'));
    return $providers;
}

add_action( 'awp_settings_view', 'awp_varisend_settings_view', 10, 1 );
function awp_varisend_settings_view( $current_tab ) {

    if( $current_tab != 'varisend' ) {
        return;
    }
    $nonce           = wp_create_nonce( "awp_sperse_settings" );
    $action          = isset( $_GET['action'       ] ) ? $_GET['action'] : 'list';
    $id              = isset( $_GET['id'   ] ) ? intval( $_GET['id'] ) : 0;
    switch ( $action ) {
        case 'edit'         :   awp_varisend_settings( $id );                      awp_varisend_list_page() ; break;
        case 'active_status':   awp_varisend_change_active_status($id);                   awp_varisend_list_page() ; break;    
        default:                awp_varisend_settings();                           awp_varisend_list_page() ; break;
    }
}



    
    function awp_varisend_change_active_status( $id = '' ) {
        global $wpdb;
        $relation_table = $wpdb->prefix."varisend_accounts";
        $status = !empty($_GET['active_status']) ? sanitize_text_field($_GET['active_status']) : 'yes';
      //$action_status = $wpdb->query($wpdb->prepare("UPDATE $relation_table SET status=$db_status WHERE account_id = $id"));
      //echo $wpdb->print_error();
        $action_status = $wpdb->update( $relation_table,
                array('active_status' => $status,),
                array( 'id'=> $id )
            );
       AWP_redirect( admin_url( 'admin.php?page=automate_hub&tab=varisend' ) );
    }
    

    
    function awp_varisend_list_page() {
        if ( isset( $_GET['status'] ) ) {
            $status = $_GET['status'];
        }
        ?>
        <div class="wrap">
            <h3 class="sperse-app-page-title"><?php esc_html_e( 'Varisend Accounts', 'automate_hub' ); ?></h3>
            <a href="<?php echo admin_url( 'admin.php?page=automate_hub' ); ?>" class="page-title-action"><?php esc_html_e( 'Add New', 'automate_hub' ); ?></a>
            <form id="form-list" method="post">
                <input type="hidden" name="page" value="automate_hub"/>
                <?php
                $list_table = new AWP_Varisend_Table();
                $list_table->prepare_items();
                $list_table->display();
                ?>
            </form>
        </div>
        <?php
    }



function awp_varisend_settings($id=''){

    $nonce     = wp_create_nonce( "awp_varisend_settings" );
    $data=array();
    if(!empty($id)){
        global $wpdb;
       $relation_table = $wpdb->prefix . "varisend_accounts";
       $data    = $wpdb->get_row( "SELECT * FROM {$relation_table} WHERE id = {$id}", ARRAY_A );
    } ?>
    <div style="padding-left:30px">
        <a href="https://sperse.io/go/varisend" target="_blank"><img src="<?php echo(AWP_ASSETS) ?>/images/logos/varisend1.png" width="270px" height="80" alt="Varisend Logo"></a>
        <br/><br/>
        <div id="introbox">
            <div style="float:right;clear: both;">
                <img data-appname="Varisend" id="videobtn" src="http://automatehubultimate.faizanjaved.com/wp-content/plugins/Automate Hub Ultimate/assets/images/videobutton.png" style="height: 120px;width: 240px;">
            </div>
            <?php 
            include_once (AWP_INCLUDES.'/awp_app_videos.php');
            ?>
            See the instructions below to setup your <b>Varisend</b> integration: <br/>
            1. If you don't have a Close account, <a href="https://sperse.io/go/varisend" target="_blank">click here to create a new account</a>.<br/>
            2. Go to Account Settings > API Keys. For further details <a href="https://help.sperse.io/" target="_blank">click here</a>. <br/>
            3. After you configure and save your connection settings, <?php printf( '%s <a href="%s">%s</a>', esc_html__( 'click here to setup a ', 'automate_hub'), admin_url( 'admin.php?page=automate_hub-new'), esc_html__( 'New Form Integration', 'automate_hub')); ?> <br/>
        </div>
        <br/>
        <form name="close_save_form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" class="container">
            <input type="hidden" name="action" value="awp_save_varisend_api_token">
            <input type="hidden" name="_nonce" value="<?php echo $nonce ?>"/>
            <input type="hidden" name="id" value="<?php echo esc_attr($id); ?>">
            <table class="form-table">
                <tr valign="top">
                    <?php $user_email =  !empty($data['user_id']) ? $data['user_id'] : ''; ?>
                    <th scope="row"> <?php esc_html_e('Enter User Email ID', 'automate_hub' ); ?></th>
                    <td>
                        <input type="email" required name="awp_varisend_user_email" id="awp_varisend_user_email" value="<?php echo esc_attr($user_email); ?>" placeholder="<?php esc_html_e( 'Enter user Email ID', 'automate_hub' ); ?>" class="basic-text"/>
                        
<!--                         <select name="awp_varisend_user_id" class="awp_select2" id="awp_varisend_user_id">
 -->                            <?php
                            /*$blogusers = get_users();
                            foreach ( $blogusers as $user ) {
                                $selected = ($user->ID==$user_id) ? 'selected' : '';
                                echo '<option   '.$selected.' value="'.$user->ID.'">' . esc_html( $user->display_name ) . '  ('.$user->user_login.')</option>';
                            }*/
                            ?>
<!--                         </select>                  
 -->                    </td>
                </tr>
                <tr valign="top">
                    <?php $account_name =  !empty($data['account_name']) ? $data['account_name'] : ''; ?>
                    <th scope="row"> <?php esc_html_e( 'Display name', 'automate_hub' ); ?></th>
                    <td>
						<div class="form-table__input-wrap">
						<input type="text" name="awp_varisend_account_name" id="awp_varisend_account_name" value="<?php echo esc_attr($account_name); ?>" placeholder="<?php esc_html_e( 'Enter an identifier for this account', 'automate_hub' ); ?>" class="basic-text"/>
                        <span class="spci_btn" data-clipboard-action="copy" data-clipboard-target="#awp_varisend_account_name"><img src="<?php echo AWP_ASSETS; ?>/images/copy.png" alt="Copy to clipboard"></span></div>                    
                    </td>
                </tr>

            <tr valign="top">
                <th scope="row"> <?php esc_html_e( 'Varisend  API Key', 'automate_hub' ); ?></th><?php $api_key =  !empty($data['api_key']) ? $data['api_key'] : ''; ?>
                <td>
					<div class="form-table__input-wrap">
					<input type="text" name="awp_varisend_api_key" id="awp_varisend_api_key" value="<?php echo esc_attr($api_key); ?>" placeholder="<?php esc_html_e( 'Paste your Varisend API Key here', 'automate_hub' ); ?>" class="basic-text" required />
                    <span class="spci_btn" data-clipboard-action="copy" data-clipboard-target="#awp_varisend_api_key"><img src="<?php echo AWP_ASSETS; ?>/images/copy.png" alt="Copy to clipboard"></span></div>                    
                </td>
            </tr>
            <tr valign="top">
            <th scope="row"> <?php esc_html_e( 'Varisend Endpoint URL', 'automate_hub' ); ?></th><?php $awp_varisend_url =  !empty($data['base_url']) ? $data['base_url'] : 'https://app.varisend.com/apis'; ?>
            <td>
				<div class="form-table__input-wrap">
				<input type="text" name="awp_varisend_url" id="awp_varisend_url" value="<?php echo esc_url($awp_varisend_url); ?>" placeholder="<?php esc_html_e( 'Varisend Endpoint URL', 'automate_hub' ); ?>" class="basic-text"/>
                <span class="spci_btn" data-clipboard-action="copy" data-clipboard-target="#awp_varisend_url"><img src="<?php echo AWP_ASSETS; ?>/images/copy.png" alt="Copy to clipboard"></span>                    </div>
            </td>
            </tr>
            <tr valign="top">
                <th scope="row"> <?php esc_html_e( 'Account Status', 'automate_hub' ); ?></th>
                <?php $awp_active_Status =  (!empty($data['active_status']) && ($data['active_status']=='yes')) ? 'checked' : ''; ?>
                <td><div class="sperse_active_account">
                    <label class="switch"><input name="awp_active_Status" value="yes" type="checkbox" checked <?php echo $awp_active_Status; ?> class="wpic-form-control "><span class="slider"></span></label>
                    </div>
                </td>
            </tr>
            </table>
<!--             <div class="submit-button-plugin"><?php //submit_button(); ?></div>
        </form>
 -->




        <?php     
        $default_api_token = get_option( 'default_varisend_api_key' ) ? get_option( 'default_varisend_api_key' ) : "";
        $default_varisend_url = get_option( 'default_varisend_url' ) ? get_option( 'default_varisend_url' ) : "https://app.varisend.com/apis/addcontact";
         ?>
   <!--      <form name="close_save_form" action="<?php //echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
          method="post" class="container"> -->
<!--         <input type="hidden" name="action" value="awp_save_varisend_default">
        <input type="hidden" name="_nonce" value="<?php //echo $nonce ?>"/> -->
        <table class="form-table">
            <tr valign="top">
                <th scope="row"> <?php esc_html_e( 'Default Varisend API Key', 'automate_hub' ); ?></th>
                <td><input type="text" name="default_varisend_api_key" id="default_varisend_api_key" value="<?php echo esc_attr($default_api_token); ?>" placeholder="<?php esc_html_e( 'Default Paste your Varisend API Key here', 'automate_hub' ); ?>" class="basic-text"/>
                    <span class="spci_btn" data-clipboard-action="copy" data-clipboard-target="#default_varisend_api_key"><img src="<?php echo AWP_ASSETS; ?>/images/copy.png" alt="Copy to clipboard"></span>                    
                </td>
            </tr>
            <tr valign="top">
            <th scope="row"> <?php esc_html_e( 'Default Varisend Endpoint URL', 'automate_hub' ); ?></th>
            <td><input type="text" name="default_varisend_url" id="default_varisend_url" value="<?php echo esc_url($default_varisend_url); ?>" placeholder="<?php esc_html_e( 'Default Varisend Endpoint URL', 'automate_hub' ); ?>" class="basic-text"/>
                <span class="spci_btn" data-clipboard-action="copy" data-clipboard-target="#default_varisend_url"><img src="<?php echo AWP_ASSETS; ?>/images/copy.png" alt="Copy to clipboard"></span>                    
            </td>
            </tr>
        </table>
        <div class="submit-button-plugin"><?php submit_button(); ?></div>
    </form>
    </div>  
<?php } 
add_action( 'wp_ajax_awp_get_messages_list2', 'awp_get_messages_list2', 10, 0 );
function awp_get_messages_list2() {
      if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }

    global $wpdb;
    $data = array();
    $message_template = $wpdb->prefix.'awp_message_template';
    $results = $wpdb->get_results( "SELECT * FROM $message_template", OBJECT );
    foreach ($results as $key => $value) {
            if( !empty($value->status)){
                $data[$value->id] = ucfirst($value->title);
            }   
    }
    wp_send_json_success( $data );
}

add_action( 'wp_ajax_awp_get_varisend_accounts', 'awp_get_varisend_accounts', 10, 0 );
function awp_get_varisend_accounts() {
      if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }

    global $wpdb;
    $data = array();
    $varisend_accounts = $wpdb->prefix.'varisend_accounts';
    $results = $wpdb->get_results( "SELECT * FROM $varisend_accounts", OBJECT );
    foreach ($results as $key => $value) {
        if( !empty($value->active_status)){
            $data[$value->id] = ucfirst($value->account_name);
        }   
    }
    wp_send_json_success( $data );
}


add_action( 'admin_post_awp_save_varisend_default', 'awp_save_default_varisend_api_token', 10, 0 );

function awp_save_default_varisend_api_token() {
    
        if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }

    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'awp_varisend_settings' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }

    $api_token = sanitize_text_field( $_POST["default_varisend_api_key"] );
    $base_url = sanitize_text_field($_POST['default_varisend_url']);
    // Save tokens
    update_option( "default_varisend_api_key", $api_token );
    update_option( "default_varisend_url", $base_url );         
    AWP_redirect( "admin.php?page=automate_hub&tab=varisend" );
}


add_action( 'admin_post_awp_save_varisend_api_token', 'awp_save_varisend_api_token', 10, 0 );

function awp_save_varisend_api_token() {
    
        if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }

    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'awp_varisend_settings' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }
                $settings = $_POST;
                global $wpdb;
                $varisend_accounts = $wpdb->prefix.'varisend_accounts';
                $where_clause = array();
                if(!empty($_POST['id'])){
                    $where_clause['id'] = sanitize_text_field($_POST['id']);
                }
                unset($settings['_nonce']);
                unset($settings['action']);
                if(empty($settings['awp_active_Status'])){
                    $settings['awp_active_Status'] = 'no';
                } else {
                    //$wpdb->query($wpdb->prepare("UPDATE $sperse_accounts SET active_status='no'"));
                }
                $data = array(
                    'account_name'=>sanitize_text_field($_POST['awp_varisend_account_name']),
                    'base_url'=>sanitize_text_field($_POST['awp_varisend_url']),
                    'user_email'=>$_POST['awp_varisend_user_email'],
                    'api_key'=>sanitize_text_field($_POST['awp_varisend_api_key']),
                    'active_status'=>$settings['awp_active_Status'],

                );

                if ( count($where_clause )>0 ) {
                    $result = $wpdb->update( $varisend_accounts, $data, $where_clause );
                 } else {
                    $result = $wpdb->insert( $varisend_accounts, $data );     
                }

                $api_token = sanitize_text_field( $_POST["default_varisend_api_key"] );
                $base_url = sanitize_text_field($_POST['default_varisend_url']);
               
                if(!empty($api_token) && !empty($base_url) ){
                    // Save tokens
                    update_option( "default_varisend_api_key", $api_token );
                    update_option( "default_varisend_url", $base_url );    
                }
               AWP_redirect( "admin.php?page=automate_hub&tab=varisend" );
}


add_action( 'awp_action_fields', 'awp_varisend_action_fields' );

function awp_varisend_action_fields() { ?>
    <script type="text/template" id="varisend-action-template">

	<div class="forms-setting-wrapper"">
        <div class="form_fields sperse_reverse_draggable">
			<div class="dynamic-form-logo"> <img v-if="trigger.formProviderId" v-bind:src="'<?php echo AWP_ASSETS; ?>/images/icons/' + trigger.formProviderId + '.png'" v-bind:alt="trigger.formProviderId" class="logo-form">
<span>Drag your form field to map it to a destination field.</span></div>				
        <ul>
            <li class="form_fields_name"  v-for="(nfield, nindex) in trigger.formFields" :data-name="nindex"  :data-fname="nfield" v-if="CheckinDatabase(nindex,nfield)" >
            <div class="field-actions hide">
            <a type="remove" v-bind:id=nindex v-bind:data-name=nindex v-bind:data-field=nfield v-on:click="say($event)"  class="del-button btn formbuilder-icon-cancel delete-confirm" title="Remove Element"></a>
            </div>
            <span class="input-group-addon fx-dragdrop-handle">{{nfield}}</span>
            </li>
        </ul>
        </div>
        <div class="form_placeholder_wrap">
			<div class="dynamic-platform-logo"><img class="logo-platform" src="<?php echo AWP_ASSETS; ?>/images/icons/varisend.png"alt="Varisend Logo"><span class="label-title"><strong>Varisend</strong> Available Fields</span></div>
            <div v-if="action.paltformConnected == true">
                <div v-if="action.task == 'add_contact'">
                    <div class="select-option-account">                                 
                        <select name="accountSelectType" v-model="fielddata.accountSelectType" required="required" @change="changeAccountSelectType">
                            <option value="static" >  <?php esc_html_e( 'Select Account Static', 'automate_hub' ); ?> </option>
                            <option value="dynamic" > <?php esc_html_e( 'Select Account Dynamically', 'automate_hub' ); ?>  </option>
                        </select>
                    </div>
                </div>

                <div v-if="fielddata.accountSelectType == 'static'">
                    <div class="select-option-account">                                 
                        <select name="staticAccount" v-model="fielddata.staticAccountId" id="form-staticaccount-selection">
                            <option value=""> <?php esc_html_e( 'Select Varisend Account', 'automate_hub' ); ?> </option>
                            <option v-for="(item, index) in fielddata.staticAccounts" :value="index" > {{item}}  </option>
                        </select>
                    </div>
                    <div class="spinner" v-bind:class="{'is-active': staticAccountLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;"></div>
                </div>

                <div v-if="fielddata.accountSelectType == 'dynamic'">
                    <div class="select-option-account">                                 
                        <select name="selectedFieldValue" v-model="fielddata.selectedFieldValue" id="form-list-selection">
                            <option value=""> <?php esc_html_e( 'Based on value of selected field', 'automate_hub' ); ?> </option>
                            <option v-for="(item, index) in trigger.formFields" :value="index" > {{item}}  </option>
                        </select>
                    </div>
                </div>



			<table class="form-table form-fields-table">
				<editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>
			</table>

        </div>
        <div v-if="action.paltformConnected == false">
            <div class="submit-button-plugin" style="width: 100%;display: flex;">
            <a style="margin: 0 auto;" href="<?php echo admin_url( 'admin.php?page=automate_hub&tab=varisend' ) ?>">
                <div  class="button button-primary" style="padding: 8px;font-size: 14px;">Connect Your Varisend Account</div>
            </a>
            </div>
        </div>
        

        </div>		
    </div>
    </script>
    <?php
}

function awp_get_varisend_account_api_key($value){
        global $wpdb;
        $config = array();
        $varisend_accounts = $wpdb->prefix.'varisend_accounts';
        $where_clause = array();

        $matched_results = $wpdb->get_results("SELECT * FROM $varisend_accounts WHERE (account_name = '".$value ."' OR user_email = '".$value."' OR api_key = '".$value."')",ARRAY_A);

        if(count($matched_results)>0){

            $config['apikey'] = !empty($matched_results[0]['api_key']) ? $matched_results[0]['api_key'] : '';
            $config['base_url'] = !empty($matched_results[0]['base_url']) ? $matched_results[0]['base_url'] : '';
        }
        return $config;
}
function awp_get_static_varisend_account_api_key($value){
        global $wpdb;
        $config = array();
        $varisend_accounts = $wpdb->prefix.'varisend_accounts';
        $where_clause = array();

        $matched_results = $wpdb->get_results("SELECT * FROM $varisend_accounts WHERE (id = '".$value ."')",ARRAY_A);

        if(count($matched_results)>0){

            $config['apikey'] = !empty($matched_results[0]['api_key']) ? $matched_results[0]['api_key'] : '';
            $config['base_url'] = !empty($matched_results[0]['base_url']) ? $matched_results[0]['base_url'] : '';

        }
        return $config;
}




/* Handles sending data to close API */
function awp_varisend_send_data( $record, $posted_data ) {
    
    $data    = json_decode( $record["data"], true );
    $record_data = json_decode( $record["data"], true );
    $data    = $data["field_data"];
    $task    = $record["task"];
    $api_token  = false;
    $config_data = array();
    $ins_url = 'https://app.varisend.com/apis/addcontact/'; 
    $accountSelectType = !empty($data['accountSelectType']) ? $data['accountSelectType'] : '';
    if($accountSelectType=='dynamic'){
        $selectedFieldValue = !empty($data['selectedFieldValue']) ? $data['selectedFieldValue'] : '';
        $based_field_value = $posted_data[$selectedFieldValue];
        $config_data  = awp_get_varisend_account_api_key($based_field_value);
    }else{
        $staticAccountId = !empty($data['staticAccountId']) ? $data['staticAccountId'] : '';
        $config_data  = awp_get_static_varisend_account_api_key($staticAccountId);
    }
    
    if(!empty($config_data) && is_array($config_data) && count($config_data)>0){
        $api_token = $config_data['apikey'] ? $config_data['apikey'] : "";
        $ins_url = $config_data['base_url'] ? $config_data['base_url'] : "";
    }else{
        $api_token = get_option( 'default_varisend_api_key' ) ? get_option( 'default_varisend_api_key' ) : "";
        $ins_url = get_option( 'default_varisend_url' ) ? get_option( 'default_varisend_url' ) : "";
    }
    if( !$api_token || !$ins_url ) {
        return;
    }
    
    if( array_key_exists( "cl", $record_data["action_data"]) ) {
        if( $record_data["action_data"]["cl"]["active"] == "yes" ) {
            $chk = awp_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data );
            if( !awp_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data ) ) {
                return;
            }
        }
    }

    if( $task == "add_contact" ) {
        $email      = empty( $data["email"   ]) ? "" : awp_get_parsed_values( $data["email"   ], $posted_data );
        $phone      = empty( $data["phone"   ]) ? "" : awp_get_parsed_values( $data["phone"   ], $posted_data );
        $name       = empty( $data["name"    ]) ? "" : awp_get_parsed_values( $data["name"    ], $posted_data );
        $bankcode   = empty( $data["bankcode"]) ? "" : awp_get_parsed_values( $data["bankcode"], $posted_data );     

        $fields = array(
            'apikey'   => $api_token,
            'group'    => 'Crackmycode Funnel',
            'number'   => $phone,
            'name'     => $name,           
            'email'    => $email,
            'bankcode' => $bankcode,            
        );      
        $headers = array('Content-Type' => 'application/x-www-form-urlencoded');
        $args = array("headers" => $headers, "body" => http_build_query($fields) );
        $response = wp_remote_post( $ins_url, $args );

        $fields_back = array(
            'apikey'   => 'XXXXXXXXXXX',
            'group'    => 'Crackmycode Funnel',
            'number'   => $phone,
            'name'     => $name,
            'email'    => $email,
            'bankcode' => $bankcode,            
        );    

		$args = array("headers" => $headers, "body" => http_build_query($fields_back) );
        awp_add_to_log( $response, $ins_url, $args, $record );
        $sms_url   = 'https://app.varisend.com/apis/smscontact/';
        $from      = '16027557289';
        $message   = 'Thank you for register';
        $smsfields = array('apikey' => $api_token,
        'from'    => $from,
        'to'      => $phone,
        'message' => $message,
        );
        $smsargs = array("headers" => $headers, "body" => http_build_query($smsfields) );
        $smsresponse = wp_remote_post( $sms_url, $smsargs );
    }
    return;
}

function awp_varisend_save_integration() {
    $params = array();
    parse_str( awp_sanitize_text_or_array_field( $_POST['formData'] ), $params );
    $trigger_data = isset( $_POST["triggerData"]) ? awp_sanitize_text_or_array_field( $_POST["triggerData"]) : array();
    $action_data  = isset( $_POST["actionData" ]) ? awp_sanitize_text_or_array_field( $_POST["actionData" ]) : array();
    $field_data   = isset( $_POST["fieldData"  ]) ? awp_sanitize_text_or_array_field( $_POST["fieldData"  ]) : array();
    $integration_title = isset( $trigger_data["integrationTitle"]) ? $trigger_data["integrationTitle"] : "";
    $form_provider_id  = isset( $trigger_data["formProviderId"  ]) ? $trigger_data["formProviderId"  ] : "";
    $form_id           = isset( $trigger_data["formId"          ]) ? $trigger_data["formId"          ] : "";
    $form_name         = isset( $trigger_data["formName"        ]) ? $trigger_data["formName"        ] : "";
    $action_provider   = isset( $action_data ["actionProviderId"]) ? $action_data ["actionProviderId"] : "";
    $task              = isset( $action_data ["task"            ]) ? $action_data ["task"            ] : "";
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
                'status'          => 1 ));
    }
    if ( $type == 'update_integration' ) {
        $id = esc_sql( trim( $params['edit_id'] ) );
        if ( $type != 'update_integration' &&  !empty( $id ) ) {
            exit;
        }
        $result = $wpdb->update( $integration_table,
            array( 'title'           => $integration_title,
                   'form_provider'   => $form_provider_id,
                   'form_id'         => $form_id,
                   'form_name'       => $form_name,
                   'data'            => json_encode( $all_data, true ),),
            array( 'id' => $id )
        );
    }
    $return=array();
    $return['type']=$type;
    $return['result']=$result;
    $return['insertid']=$wpdb->insert_id;
    return $return;
}




function awp_varisend_resend_data($log_id,$posted_data,$record  ) {
    

    $posted_data=json_decode($posted_data,true);
    $task=$record['task'];
    parse_str($posted_data['args']['body'],$params);
    $url=$posted_data['url'];
    if(!$url){
        $response['success']=false;
        $response['msg']="Syntax Error! Request is invalid";
        return $response;
    }




    $data    = json_decode( $record["data"], true );
    $record_data = json_decode( $record["data"], true );
    $data    = $data["field_data"];
    $task    = $record["task"];
    $api_token  = false;
    $config_data = array();

    $accountSelectType = !empty($data['accountSelectType']) ? $data['accountSelectType'] : '';
    if($accountSelectType=='dynamic'){
        $selectedFieldValue = !empty($data['selectedFieldValue']) ? $data['selectedFieldValue'] : '';
        $based_field_value = $posted_data[$selectedFieldValue];
        $config_data  = awp_get_varisend_account_api_key($based_field_value);
    }else{
        $staticAccountId = !empty($data['staticAccountId']) ? $data['staticAccountId'] : '';
        $config_data  = awp_get_static_varisend_account_api_key($staticAccountId);
    }
    
    if(!empty($config_data) && is_array($config_data) && count($config_data)>0){
        $api_token = $config_data['apikey'] ? $config_data['apikey'] : "";
        $ins_url = $config_data['base_url'] ? $config_data['base_url'] : "";
    }else{
        $api_token = get_option( 'default_varisend_api_key' ) ? get_option( 'default_varisend_api_key' ) : "";
        $ins_url = get_option( 'default_varisend_url' ) ? get_option( 'default_varisend_url' ) : "";
    }
    if( !$api_token || !$ins_url ) {
        return;
    }
    
    


    
    if( $task == "add_contact" ) {
   

        $params['apikey']=$api_token;
        $headers = array('Content-Type' => 'application/x-www-form-urlencoded');
        $args = array("headers" => $headers, "body" => http_build_query($params) );
        $response = wp_remote_post( $url, $args );
        $params['apikey']='XXXXXXXXXXX';
        

        $args = array("headers" => $headers, "body" => http_build_query($params) );
        awp_add_to_log( $response, $url, $args, $record );


        $sms_url   = 'https://app.varisend.com/apis/smscontact/';
        $from      = '16027557289';
        $message   = 'Thank you for register';
        $smsfields = array('apikey' => $api_token,
        'from'    => $from,
        'to'      => $params['number'],
        'message' => $message,
        );
        $smsargs = array("headers" => $headers, "body" => http_build_query($smsfields) );
        $smsresponse = wp_remote_post( $sms_url, $smsargs );
    }
    $response['success']=true;
    return $response;
}
