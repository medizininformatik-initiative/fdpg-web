<?php
add_filter( 'awp_action_providers', 'awp_slack_actions', 10, 1 );

function awp_slack_actions( $actions ) {
    $actions['slack'] = array(
        'title' => __( 'Slack', 'automate_hub' ),
        'tasks' => array(
            'sendmsg'   => __( 'Send Channel Message', 'automate_hub' )
        )
    );
    return $actions;
}

add_action( 'awp_action_fields', 'awp_slack_action_fields' );

function awp_slack_action_fields() {
    ?>
    <script type="text/template" id="slack-action-template">
        
<div class="forms-setting-wrapper"">
   <div class="form_fields sperse_reverse_draggable ">
      <div class="dynamic-form-logo"> <img v-if="trigger.formProviderId" v-bind:src="'<?php echo AWP_ASSETS; ?>/images/icons/' + trigger.formProviderId + '.png'" v-bind:alt="trigger.formProviderId" class="logo-form">
         <span>Drag your form field to map it to a destination field.</span>
      </div>
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
      <div class="dynamic-platform-logo"><img class="logo-platform" src="<?php echo AWP_ASSETS; ?>/images/icons/slack.png"alt="Slack Logo"><span class="label-title"><strong>Slack</strong> Available Fields To Map Your Form Values:</span>
      </div>
      <div>
         <div v-if="action.paltformConnected == true">
            <div  class="form-description-text">
               <span class="label-title"><?php esc_html_e( 'Map Fields', 'automate_hub' ); ?></span>
               <div v-if="action.task == 'sendmsg'">
                    <span class="label-select"> 
                        <label> 
                            <?php esc_attr_e( 'Instructions', 'automate_hub' ); ?>
                        </label>
                    </span>
                    <p><a target="_blank" href="https://sperse.io">Documentation</a></p>
               </div>
               <div v-if="action.task == 'sendmsg'">
                    <span class="label-select"> 
                        <label> 
                            <?php esc_attr_e( 'Inbound Webhook URL', 'automate_hub' ); ?>
                        </label>
                    </span>
                    <input type="text" class="regular-text" v-model="fielddata.url" name="fieldData[url]" placeholder="<?php _e( 'Enter URL here', 'automate_hub'); ?>" required="required">
                   <div class="spinner" v-bind:class="{'is-active': listLoading}"></div>
               </div>
            </div>
            <table class="form-table form-fields-table">
                <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata"></editable-field>
            </table>
         </div>
         <div v-if="action.paltformConnected == false">
            <div class="submit-button-plugin" style="width: 100%;display: flex;">
               <a style="margin: 0 auto;" href="<?php echo admin_url( 'admin.php?page=automate_hub&tab=slack' ) ?>">
                  <div  class="button button-primary" style="padding: 8px;font-size: 14px;">Connect Your Slack Accout</div>
               </a>
            </div>
         </div>
      </div>
   </div>
</div>
    </script>
    <?php
}

/* Sends data to Slack API */
function awp_slack_send_data( $record, $posted_data ) {
    $record_data = json_decode( $record["data"], true );
    if( array_key_exists( "cl", $record_data["action_data"] ) ) {
        if( $record_data["action_data"]["cl"]["active"] == "yes" ) {
            if( !awp_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data ) ) {
                return;
            }
        }
    }
    $data = $record_data["field_data"];
    $task = $record["task"];
    if( $task == "sendmsg" ) {
        $url     = empty( $data["url"] ) ? "" : awp_get_parsed_values( $data["url"], $posted_data );
        $message = empty( $data["message"] ) ? "" : awp_get_parsed_values( $data["message"], $posted_data );
        if( !$url ) {
            return;
        }
        $data = array(
            'text' => $message
        );
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode( $data )
        );
        $return = wp_remote_post( $url, $args );
        awp_add_to_log( $return, $url, $args, $record );
    }
    return;
}