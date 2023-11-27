<?php
global $wpdb;
$integration_table = $wpdb->prefix . 'awp_message_template';
$last_id           = $wpdb->get_var( "SELECT MAX(id) FROM {$integration_table}" );
$last_id           = empty( $last_id ) ? 0 : $last_id;
$integration_title = "Message Template #" . ( $last_id + 1 );
$nonce             = wp_create_nonce( 'awp_integration' );
$field_data        = array();
?>
<script type="text/javascript">
    var messageTitle = <?php echo json_encode( $integration_title, true ) ; ?> ;
</script>

<?php do_action( "awp_add_js_fields", $field_data ); ?>
<?php include AWP_INCLUDES.'/header.php'; ?>

<div class="wrap">
	<div class="pages-background"></div>
    <div id="icon-options-general" class="icon32">  </div>
    <h3 class="sperse-app-page-title"><?php esc_html_e( 'Create New Message Template', 'automate_hub' ); ?></h3>
    <div id="awp-new-message" v-cloak>
        <div id="post-body" class="metabox-holder ">
            <form @submit.prevent="saveMessage" action="" method="post" id="new-message" >
<!--            <input type="hidden" name="action" value="awp_save_integration">-->
                <input type="hidden" name="type" value="new_message" />
                <input type="hidden" name="_wpnonce" value="<?php echo $nonce; ?>" />
                <input type="hidden" name="form_name" :value="trigger.formName" />
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">
                            <?php esc_html_e( 'Message Template Name', 'automate_hub' ); ?>
                        </th>
                        <td scope="row">

                        </td>
                    </tr>
                    <tr valign="top" class="alternate">
                        <td scope="row-title">
                            <label for="tablecell">
                                <?php esc_html_e( 'Name Your Message Template', 'automate_hub' ); ?>
                            </label>
                        </td>
                        <td>
                            <input type="text" class="basic-text" v-model="trigger.messageTitle" name="message_title" placeholder="<?php esc_html_e( 'Enter an integration title here', 'automate_hub'); ?>" required="required" style="width: 400px;">
                        </td>
                    </tr>
                    <tr valign="top" class="alternate">
                            <td scope="row-title">
                                <label for="tablecell">
                                    <?php esc_html_e( 'Select Form Plugin', 'automate_hub' );
                                     ?>
                                </label>
                            </td>
                            <td> 
                                <select name="form_provider_id" v-model="trigger.formProviderId" @change="changeFormProvider" required="required" id="ProviderForms">
                                    <option value=""> <?php esc_html_e( 'Select Form Provider...', 'automate_hub' ); ?> </option>
                                     <?php
                                    $allFormsArray = array("ARForms", "Caldera Forms", "Contact Form 7", "CRM Perks", "Everest Forms", "Formcraft", "Formidable", "Forminator", "Gravity Forms", "Happy Forms", "Ninja Forms", "Planso Forms", "RegistrationMagic", "Smart Forms", "Sperse Forms", "weForms", "WPForms");
                                    $formsArray = [];
                                    foreach ( $form_providers as $key => $value ) {
                                        echo "<option value='" . $key . "' id=\"ProvidersActive\"> " . $value . " </option>";
                                        array_push($formsArray, $value);
                                     }
                                     $disableFroms = array_diff($allFormsArray, $formsArray);
                                     foreach($disableFroms as $val) {
                                         echo "<option value='' disabled  id=\"ProvidersInactive\"> " . $val . " (not installed) </option>";
                                     }
                                     ?>
                                    } ?>
                                </select>
                                <div class="spinner" v-bind:class="{'is-active': formLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;">
                            </td>
                    </tr>
                    <tr valign="top" class="alternate">
                            <td scope="row-title">
                                <label for="tablecell">
                                    <?php esc_html_e( 'Select Form by Name', 'automate_hub' ); ?>
                                </label>
                            </td>
                            <td>
                                <select name="form_id" v-model="trigger.formId" :disabled="formValidated == 1" @change="changedForm"  required="required" style="width: 400px;">
                                    <option value=""> <?php esc_html_e( 'Select Form...', 'automate_hub' ); ?> </option>
                                    <option v-for="(item, index) in trigger.forms" :value="index" id="ActiveFormNames"> {{item}} (#{{ index }})   </option>
                                </select>
                                <p v-if="trigger.totalForms" class="description"><?php _e( 'Total Forms for selected Provider : ', 'automate_hub' ); ?>{{trigger.totalForms}}</p>

                                <div class="spinner" v-bind:class="{'is-active': fieldLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;">

                            </td>
                    </tr>
                    <tr valign="top" class="alternate">
                        <td scope="row-title">
                            <label for="tablecell">
                                <?php esc_html_e( 'Message Subject Name', 'automate_hub' ); ?>
                            </label>
                        </td>
                        <td>
                            <input type="text" class="basic-text" v-model="trigger.subjectName" name="message_subject_name" placeholder="<?php esc_html_e( 'Enter Message Subject Name', 'automate_hub'); ?>" required="required" style="width: 400px;">
                            <p v-if="trigger.isformFields" class="description"><?php _e( 'Please use form fields as placeholder : ', 'automate_hub' ); ?>            
                                <span v-for="(nfield, nindex) in trigger.formFields">{{'{' +nindex+ '}'}}</span>
                            </p>

                        </td>
                    </tr>
                    <tr valign="top" class="alternate">
                        <td scope="row-title">
                            <label for="tablecell">
                                <?php esc_html_e( 'Message Template', 'automate_hub' ); ?>
                            </label>
                        </td>
                        <td>
                        <textarea  class="basic-text" v-model="trigger.messageTemplate" rows="10" cols="50" name="message_template" placeholder="<?php esc_html_e( 'Enter Message Template', 'automate_hub'); ?>" required="required" style="width: 400px;"></textarea>
                        <p v-if="trigger.isformFields" class="description"><?php _e( 'Please use form fields as placeholder : ', 'automate_hub' ); ?>            
                                <span v-for="(nfield, nindex) in trigger.formFields">{{'{' +nindex+ '}'}}</span>
                            </p>

                        </td>
                    </tr>
                    <tr valign="top" class="alternate">
                        <td scope="row-title">
                            <label for="tablecell">
                                <?php esc_html_e( 'External Template ID', 'automate_hub' ); ?>
                            </label>
                        </td>
                        <td>
                            <input type="text" class="basic-text" v-model="trigger.externalTemplateID" name="external_template_id" placeholder="<?php esc_html_e( 'Enter External Template ID', 'automate_hub'); ?>"  style="width: 400px;">
                        </td>
                    </tr>
                    <tr valign="top" class="alternate">
                        <td scope="row-title">
                            <label for="tablecell">
                                <?php esc_html_e( 'Sender Phone', 'automate_hub' ); ?>
                            </label>
                        </td>
                        <td>
                            <input type="text" class="basic-text" v-model="trigger.senderPhone" name="message_Sender_Phone" placeholder="<?php esc_html_e( 'Enter Sender Phone', 'automate_hub'); ?>" required="required" style="width: 400px;">
                        </td>
                    </tr><tr valign="top" class="alternate">
                        <td scope="row-title">
                            <label for="tablecell">
                                <?php esc_html_e( 'Sender Email', 'automate_hub' ); ?>
                            </label>
                        </td>
                        <td>
                            <input type="email" class="basic-text" v-model="trigger.SenderEmail" name="message_sender_email" placeholder="<?php esc_html_e( 'Enter Sender Email', 'automate_hub'); ?>" required="required" style="width: 400px;">
                        </td>
                    </tr>

                        <tr valign="top" class="alternate">
                            <td scope="row-title">
                                <label for="tablecell">
                                    <?php esc_html_e( 'Select Destination Platform', 'automate_hub' ); ?>
                                </label>
                            </td>
                            <td>
                                <select name="action_provider" v-model="action.actionProviderId" required="required" id="PlatformList">
                                    <option value=""> <?php esc_html_e( 'Select Your Platform ...', 'automate_hub' ); ?> </option>
                                    <?php
                                    foreach ( $action_providers as $key => $value ) {
                                        if ($key == "sperse")
                                        { 
                                             echo "<option value='" . $key . "' selected id=\"SelectedPlatform\"> " . $value . " </option>"; 
                                        }
                                        else
                                        { 
                                            echo "<option value='" . $key . "' id=\"AvailablePlatforms\"> " . $value . " </option>"; 
                                        }
                                    } ?>

                                </select>
                                <div class="spinner" v-bind:class="{'is-active': actionLoading}" style="float:none;width:auto;height:auto;padding:10px 0 10px 50px;background-position:20px 0;">
                            </td>
                        </tr>

                </table>    
                <component v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fieldData" v-bind:is="action.actionProviderId"></component>
                <br>
                <!-- Save intigartion Starts -->
                <div style="float: left;width: 100%">
                    <p>
                        <input class="button-primary" type="submit" name="save_integration" value="<?php esc_html_e( 'Save Message Template', 'automate_hub' ); ?>" />
                        <a class="button-secondary" style="color: red" href="<?php echo esc_url(admin_url('admin.php?page=automate_hub')) ;?>" class="button-secondary"> <?php esc_html_e( 'Cancel', 'automate_hub' ); ?></a>
                    </p>
                </div>
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
