<?php
global $wpdb;
$integration_table = $wpdb->prefix . 'awp_integration';
$last_id           = $wpdb->get_var( "SELECT MAX(id) FROM {$integration_table}" );
$last_id           = empty( $last_id ) ? 0 : $last_id;
$integration_title = "Integration #" . ( $last_id + 1 );
$nonce             = wp_create_nonce( 'awp_integration' );
$field_data        = array();
?>
<script type="text/javascript">
    var integrationTitle = <?php echo wp_json_encode( $integration_title, true ) ; ?> ;
</script>

<?php do_action( "awp_add_js_fields", $field_data ); ?>
<?php include AWP_INCLUDES.'/header.php'; ?>
<?php 
// for limited version start
if($notice['success'] == false){
    echo '<div id="updateLicense" style="background-color: #e9d000;margin-left: -20px;color: black;text-align: center;font-size: 16px;height: 29px;line-height: 28px;">You have reached your account limit. Please upgrade your account </div>';
}
// for limited version end
?>
<div class="pages-background"></div>	
	<div class="wrap-draggable-form">
    <div id="icon-options-general" class="icon32"></div>

    <div id="awp-new-integration" v-cloak>
			<div id="post-body" class="metabox-holder">
            <form @submit.prevent="saveIntegration" action="" method="post" id="new-integration" >
				<div class="integration-name-wrap bottom-line">
					<h1 class="integration-title main-title"> <?php esc_html_e( 'Create New Integration', 'automate_hub' ); ?></h1>
					<div class="integration-name">
                        <input type="text" class="integration-text" v-model="trigger.integrationTitle" name="integration_title" placeholder="<?php esc_html_e( 'Enter a name for your integration', 'automate_hub'); ?>" required="required">

                    </div>
					<div class="submit-button-integration">
					<a class="button-secondary-integration" href="<?php echo esc_url(admin_url('admin.php?page=my_integrations')) ;?>" class="button-secondary"> <?php esc_html_e( 'Cancel', 'automate_hub' ); ?></a>


                        <input class="button-primary-integration save_primary_integration" type="submit" name="save_integration" value="<?php esc_html_e( 'Save Integration', 'automate_hub' ); ?>" />
                       	</div>
					<span class="view-toggler">
<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"><g transform="translate(-1711 -252)"><circle cx="16" cy="16" r="16" transform="translate(1711 252)" fill="#fff"/><path d="m1721.386 266.327 5.614 5.614 5.614-5.614" fill="#12222e"/></g></svg>
					</span>
				</div>
			<!-- <input type="hidden" name="action" value="awp_save_integration">-->
                <input type="hidden" name="type" value="new_integration" />
                <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( 'awp_integration' ); ?>" />
                <input type="hidden" name="form_name" :value="trigger.formName" />
				<div class="table-forms-wrap">
				    <div class="table-form-left">               
                        <h3><span class="no-circle">1</span><?php esc_html_e( 'Trigger - Your Source Form', 'automate_hub' ); ?></h3>   
							<div class="form-wrapper-left">
						<div class="form-plugin">
                            <label class="select-dd-lable"><?php esc_html_e( 'Select Form Plugin', 'automate_hub' ); ?></label>
                             <vue-select
                                    class = "style-chooser-formProvider"
                                    placeholder="Select Form Provider..."
                                    :options="action.formProviderList"
                                    label="name"
                                    :reduce="name => name.id"
                                    v-model="trigger.formProviderId"
                                    @input="changeFormProvider"
                                    :selectable="(option) => !option.disable"
                                    :disabled="formValidated == 2"                                    
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
							<div class="form-select">
                                <label class="select-dd-lable" for="tablecell" ><?php esc_html_e( 'Select Form ', 'automate_hub' ); ?> <span v-if="trigger.totalForms"> (Total  {{trigger.totalForms}})</label>
                                <vue-select
                                    :disabled="formValidated == 1 || formValidated == 2"
                                    class = "style-chooser-form"
                                    placeholder="Select Form"
                                    :options="trigger.forms" 
                                    label="name"
                                    :reduce="name => name.id"
                                    v-model="trigger.formId"
                                    @input="changedForm"
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
                                <label class="select-dd-lable"><?php esc_html_e( 'Select Platform', 'automate_hub' ); ?></label>
                                <vue-select
                                    class = "style-chooser"
                                    placeholder="Select Platform!"
                                    :options="action.platformList" 
                                    :disabled = "(title => title.id == 'sperse') ? false:true"
                                    label="title"
                                    :reduce="title => title.id"
                                    v-model="action.actionProviderId"
                                    @input="changeActionProvider"
                                    :selectable="(option) => !option.disable"

                                  >
                                    <template slot="selected-option" slot-scope="option" >
                                      <img :src="option.favicon" class="img-sl" />
                                      {{ option.title }}
                                    </template>
                                    <template slot="option" slot-scope="option">
                                      <img :src="option.favicon"  :class="(option.title == 'Sperse') ? 'SelectedPlatform': 'AvailablePlatforms'" />
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
                                <label class="select-dd-lable"> <?php esc_html_e( 'Select a Task', 'automate_hub' ); ?></label>
                                <vue-select
                                    :disabled="actionValidated == 1"
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

    <tr class="alternate" valign="top" v-if="inArray(action.task, field.task)">
        <td>
            <label for="tablecell" class="sperse_form_label">
                {{field.title}}
            </label>
        </td>
        <div class="form_field_dropable"   >
            <div class="sperse_inner">
                <input type="hidden" ref="fieldValue" class="basic-text" v-bind:data-field=field.value v-model="fielddata[field.value]"  v-bind:required="field.required">
                <p v-if="field.description" class="description">{{field.description}}</p>
                <ul class="sortable">
                </ul>
            </div>
        </div>
    </tr>

</script>





<script type="text/javascript">
    <?php 
        if(isset($_GET['form_provider_id']) && !empty($_GET['form_provider_id']) && array_key_exists(sanitize_text_field($_GET['form_provider_id']),$form_providers)){

            $form_provider_id = sanitize_text_field($_GET['form_provider_id']);
            $form_id = sanitize_text_field($_GET['form_id']);

    ?>
                jQuery(document).ready(function($) {
                    triggerFormProviderSelect('<?php echo esc_html($form_provider_id) ; ?>','<?php echo esc_html($form_id); ?>');
                });        
    <?php
        }

    ?>



</script>
