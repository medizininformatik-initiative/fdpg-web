<?php
global $wpdb;
$table        = $wpdb->prefix . 'awp_integration';
$result       = $wpdb->get_row($wpdb->prepare( "SELECT * FROM {$table} WHERE id =%d", $id), ARRAY_A );
$data         = !empty($result["data"]) ? json_decode( $result["data"], true ):array();
$trigger_data = !empty($data["trigger_data"]) ? $data["trigger_data"] : array();
$action_data  = !empty($data["action_data"]) ? $data["action_data"] :'' ;
$action_data['paltformConnected']=true;
$field_data   = $data["field_data"];
$integration_title  = !empty($result["title"]) ? sanitize_text_field($result["title"]) :''  ;
$form_providers     = awp_get_form_providers();
$action_providers   = awp_get_action_providers();
$simple_field_data = array();
ksort( $action_providers );

?>

<script type="text/javascript">
    var triggerData = <?php echo wp_json_encode( $trigger_data, true ); ?>;
    var actionData  = <?php echo wp_json_encode( $action_data, true ); ?>;
    var fieldData   = <?php echo wp_json_encode( $field_data, true ); ?>;
    var int_id   = <?php echo esc_js($id); ?>;
    var editable_field=true;

</script>
<?php include AWP_INCLUDES.'/header.php'; ?>	
<div class="wrap-draggable-form">
	<div class="pages-background"></div>
    <div id="icon-options-general" class="icon32"></div>
      <div id="awp-new-integration" v-cloak>
        <div id="post-body" class="metabox-holder ">
            <form @submit="saveIntegration" action="" method="post" id="new-integration" >
				<div class="integration-name-wrap">
					<h1 class="integration-title"> <?php esc_html_e( 'Update Integration: ', 'automate_hub' ); ?></h1>
					<div class="integration-name">
						<input type="text" v-model="trigger.integrationTitle" name="integration_title" class="integration-text" value="<?php echo esc_attr($integration_title); ?>" placeholder="Enter a name for your integration" required="required">
                </div>
					  <div class="submit-button-integration">
						 <a class="button-secondary-integration" href="<?php echo esc_url(admin_url('admin.php?page=my_integrations')); ?>"> <?php esc_html_e( 'Cancel', 'automate_hub' ); ?></a>
                        <input type="submit" name="update_integration" class="button-primary-integration" value="<?php esc_html_e( 'Update Integration', 'automate_hub' ); ?>" />
                      </div>
					  <span class="view-toggler">
          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"><g transform="translate(-1711 -252)"><circle cx="16" cy="16" r="16" transform="translate(1711 252)" fill="#fff"/><path d="m1721.386 266.327 5.614 5.614 5.614-5.614" fill="#12222e"/></g></svg>
					</span>
				</div>
                <input type="hidden" name="type" value="update_integration" />
                <input type="hidden" name="edit_id" value="<?php echo esc_attr( wp_unslash($id));; ?>" />
				<div class="table-forms-wrap">			
                <div class="table-form-left">  
                    <h3><span class="no-circle">1</span><?php esc_html_e( 'Trigger - Your Source Form', 'automate_hub' ); ?></h3>            
						<div class="form-wrapper-left">
							<div class="form-plugin">		
                                <label><?php esc_html_e( 'Select Form Plugin', 'automate_hub' ); ?></label>                                          
                                <vue-select
                                    class = "style-chooser-formProvider"
                                    placeholder="Select Form Provider..."
                                    :options="action.formProviderList"
                                    label="name"
                                    :reduce="name => name.id"
                                    v-model="trigger.formProviderId"
                                    @input="changeFormProvider"
                                    :selectable="(option) => !option.disable"
                                    :disabled = "true"
                                    
                                  >
                                <template slot="option" slot-scope="option">
                                  <div :class="(!option.disable) ? 'ProvidersActive': 'ProvidersInactive'"> {{ option.name }} </div>
                                  
                                  </template>
                                  <template #search="{attributes, events}">
                                    <input
                                      class="vs__search"
                                      :required="!trigger.formProviderId"
                                      v-bind="attributes"
                                      v-on="events"
                                    />
                                  </template>
                                </vue-select>
                                <div class="spinner" v-bind:class="{'is-active': formLoading}"></div>
							</div>	
							<div class="form-plugin">
                                <label for="tablecell"><?php esc_html_e( 'Select Form ', 'automate_hub' ); ?></label>         
                                <vue-select
                                    class = "style-chooser-form"
                                    placeholder="Select Form"
                                    :options="trigger.forms" 
                                    label="name"
                                    :reduce="name => name.id"
                                    v-model="trigger.formId"
                                    @input="changedForm"
                                    :disabled = "true"
                                  >
                                  <template #search="{attributes, events}">
                                    <input
                                      class="vs__search"
                                      :required="!trigger.formId"
                                      v-bind="attributes"
                                      v-on="events"
                                    />
                                  </template>
                                </vue-select>
                                <div class="spinner" v-bind:class="{'is-active': fieldLoading}"></div>
							</div>
					</div>
				</div><!-- left section-->								

             <div class="table-form-right">
                        <h3><span class="no-circle">2</span><?php esc_html_e( 'Action - Post To Destination', 'automate_hub' ); ?></h3>                    					
						<div class="form-wrapper-right">
							<div class="form-platform">						
                                <label><?php esc_html_e( 'Select Platform', 'automate_hub' ); ?></label> 										 
                                <vue-select
                                    class = "style-chooser"
                                    placeholder="Select Platform!"
                                    :options="action.platformList" 
                                    :disabled = "true"
                                    label="title"
                                    :reduce="title => title.id"
                                    v-model="action.actionProviderId"
                                    @input="changeActionProvider"
                                  >
                                    <template slot="selected-option" slot-scope="option" >
                                      <img :src="option.favicon" class="img-sl" />
                                      {{ option.title }}
                                    </template>
                                    <template slot="option" slot-scope="option">
                                      <img :src="option.favicon" :class="(option.title == 'Sperse') ? 'SelectedPlatform': 'AvailablePlatforms'" />
                                      {{ option.title }}
                                  </template>
                                  <template #search="{attributes, events}">
                                    <input
                                      class="vs__search"
                                      :required="!action.actionProviderId"
                                      v-bind="attributes"
                                      v-on="events"
                                    />
                                  </template>
                                </vue-select>
                                <div class="spinner" v-bind:class="{'is-active': actionLoading}"></div>
							</div>
							<div class="form-task">               
                                <label> <?php esc_html_e( 'Select a Task', 'automate_hub' ); ?></label>                        
                                <vue-select
                                    :disabled="true"
                                    class = "style-chooser-task"
                                    placeholder="Select An Action..."
                                    :options="action.tasks" 
                                    label="name"
                                    :reduce="name => name.id"
                                    v-model="action.task"
                                  >
                                  <template #search="{attributes, events}">
                                    <input
                                      class="vs__search"
                                      :required="!action.task"
                                      v-bind="attributes"
                                      v-on="events"
                                    />
                                  </template>
                                </vue-select>
							</div>
						</div>						                  
                </div>
			</div>
                <component v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fieldData" v-bind:is="action.actionProviderId"></component>
                <br>
                <!-- Save intigartion Starts -->
                <div class="edit_save_integration_start"></div>
                <!-- Save intigartion Ends -->
            </form>
        </div>
        <!-- #post-body .metabox-holder .columns-2 -->
        <br class="clear">
    </div>
    <!-- #poststuff -->
</div> <!-- .wrap -->

<?php do_action( 'awp_action_fields' ); ?>

<script type="text/template" id="editable-field-template">
    <tr class="alternate" v-if="inArray(action.task, field.task)">
        <td>
            <label for="tablecell" class="sperse_form_label">
                {{field.title}}
            </label>
        </td>
        <div v-bind:class = "( !(typeof fielddata[field.value] == 'undefined') && fielddata[field.value].length>0) ? 'form_field_dropable sperse_dropped ':'form_field_dropable'">
            <div class="sperse_inner">
                <input type="hidden" ref="fieldValue" class="basic-text" v-bind:data-field=field.value v-model="fielddata[field.value]" v-bind:required="field.required">
                    <li class="form_fields_db_name"  v-for="(nfield, nindex) in fielddata[field.value+'dis']" :data-name=parseFieldValue(nfield) :id=parseFieldValue(nfield) :data-field=field.value v-if="parsedFormFieldValue(nfield)" >
                        <div class="field-actions">
                            <a type="copy" v-bind:id=parseFieldValue(nfield) v-bind:data-name=parseFieldValue(nfield) v-bind:data-field=field.value v-on:click=""  class="copy-img del-button btn formbuilder-icon-cancel copy-confirm" title="Copy Element"></a>

                            <a type="remove" v-bind:id=parseFieldValue(nfield) v-bind:data-name=parseFieldValue(nfield) v-bind:data-field=field.value v-on:click="say($event)"  class="del-button btn formbuilder-icon-cancel delete-confirm" title="Remove Element"></a>
                        </div>
                        <span class="input-group-addon fx-dragdrop-handle">{{parsedFormFieldValue(nfield)}}</span></li>
                <p v-if="field.description" class="description">{{field.description}}</p>
                <ul class="sortable">
                </ul>
            </div>
        </div>

    </tr>
</script>
