<?php

add_filter( 'awp_action_providers', 'awp_zapier_actions', 10, 1 );

function awp_zapier_actions( $actions ) {
    $actions['zapier'] = array(
        'title' => esc_html__( 'Zapier', 'automate_hub'),
        'tasks' => array('send_to_webhook' => esc_html__( 'Send Data to Webhook', 'automate_hub'))
    );
    return $actions;
}

add_filter( 'awp_settings_tabs', 'awp_zapier_settings_tab', 10, 1 );

function awp_zapier_settings_tab( $providers ) {
    $providers['zapier'] = esc_html__( 'Zapier', 'automate_hub');
    return $providers;
}

add_action( 'awp_settings_view', 'awp_zapier_settings_view', 10, 1 );

function awp_zapier_settings_view( $current_tab ) {
    if( $current_tab != 'zapier') { return;}
    ?>
    <div class="platformheader">
	<a href="https://sperse.io/go/zapier" target="_blank"><img src="<?php echo AWP_ASSETS; ?>/images/logos/zapier.png" width="105" height="50" alt="Zapier Logo"></a><br/><br/>
	<?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?>
	<br/><br/>        
    </div>
    <?php
}

add_action( 'awp_add_js_fields', 'awp_zapier_js_fields', 10, 1 );

function awp_zapier_js_fields( $field_data ) {}
add_action( 'awp_action_fields', 'awp_zapier_action_fields' );

function awp_zapier_action_fields() {
?>
    <!-- <script type="text/template" id="zapier-action-template">
        <div v-if="action.paltformConnected == true">

            <table class="form-table">
                <tr valign="top" v-if="action.task == 'send_to_webhook'">
                    <th scope="row"><?php esc_html_e( 'Zapier Webhook Fields', 'automate_hub' ); ?></th>
                    <td scope="row"></td>
                </tr>
                <tr class="alternate" v-if="action.task == 'send_to_webhook'">
                    <td><label for="tablecell"><?php esc_html_e( 'Webhook URL', 'automate_hub' ); ?></label></td>
                    <td>
					<div class="form-table__input-wrap">
    				<input type="text" class="basic-text" v-model="fielddata.webhookUrl" name="webhook_url" id="webhook_url" placeholder="<?php esc_html_e( 'Enter URL here', 'automate_hub'); ?>" required="required">
    				<span class="spci_btn" data-clipboard-action="copy" data-clipboard-target="#webhook_url"><img src="<?php echo AWP_ASSETS; ?>/images/copy.png" alt="Copy to clipboard"></span>              				</div>																																													
    				</td>
                </tr>
            </table>
        </div>
        <div v-if="action.paltformConnected == false">
            <div class="submit-button-plugin" style="width: 100%;display: flex;">
            <a style="margin: 0 auto;" href="<?php echo admin_url( 'admin.php?page=automate_hub&tab=zapier' ) ?>">
                <div  class="button button-primary" style="padding: 8px;font-size: 14px;">Connect Your Zapier Account</div>
            </a>
            </div>
        </div>
    </script> -->

    <script type="text/template" id="zapier-action-template">
            <table class="form-table">
                <tr valign="top" v-if="action.task == 'send_to_webhook'">
                    <th scope="row"><?php esc_html_e( 'Zapier Webhook Fields', 'automate_hub' ); ?></th>
                    <td scope="row"></td>
                </tr>
                <tr class="alternate" v-if="action.task == 'send_to_webhook'">
                    <td><label for="tablecell"><?php esc_html_e( 'Webhook URL', 'automate_hub' ); ?></label></td>
                    <td>
                    <div class="form-table__input-wrap">
                    <input type="text" class="basic-text" v-model="fielddata.webhookUrl" name="webhook_url" id="webhook_url" placeholder="<?php esc_html_e( 'Enter URL here', 'automate_hub'); ?>" required="required">
                    <span class="spci_btn" data-clipboard-action="copy" data-clipboard-target="#webhook_url"><img src="<?php echo AWP_ASSETS; ?>/images/copy.png" alt="Copy to clipboard"></span>                           </div>                                                                                                                                                                                  
                    </td>
                </tr>
            </table>
    </script>
    <?php
}

/* Saves connection mapping */
function awp_zapier_save_integration() {
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
        if ( $type != 'update_integration' &&  !empty( $id ) ) { exit; }
        $result = $wpdb->update( $integration_table,
            array(
                'title'           => $integration_title,
                'form_provider'   => $form_provider_id,
                'form_id'         => $form_id,
                'form_name'       => $form_name,
                'data'            => json_encode( $all_data, true ),
            ),
            array('id' => $id)
        );
    }
    $return=array();
    $return['type']=$type;
    $return['result']=$result;
    $return['insertid']=$wpdb->insert_id;
    return $return;
}

/* Handles sending data to Zapier API */
function awp_zapier_send_data( $record, $posted_data ) {
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
    if( $task == "send_to_webhook" ) {
        $webhook_url = $data["webhookUrl"];
        if( !$webhook_url ) { return; }
        $args = array(
            'headers' => array('Content-Type' => 'application/json',),
            'body' => json_encode( $posted_data));
        $return = wp_remote_post( $webhook_url, $args );
        
        awp_add_to_log( $return, $webhook_url, $args, $record );
        return $return;
    }
}
