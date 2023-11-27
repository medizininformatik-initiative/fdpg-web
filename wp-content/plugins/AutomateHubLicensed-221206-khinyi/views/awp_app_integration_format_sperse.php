<?php 

$app_name = isset($app_data['app_name'])? sanitize_text_field($app_data['app_name']) : '' ;  
$app_icon_url = isset($app_data['app_icon_url'])? sanitize_url($app_data['app_icon_url']) : ''; 

$app_icon_alter_text = isset($app_data['app_icon_alter_text'])? sanitize_text_field($app_data['app_icon_alter_text']) : ''


?>

<div class="forms-setting-wrapper">
   <div class="form_fields sperse_reverse_draggable">
      <div class="dynamic-form-logo"> <img v-if="trigger.formProviderId" v-bind:src="'<?php echo AWP_ASSETS; ?>/images/icons/' + trigger.formProviderId + '.png'" v-bind:alt="trigger.formProviderId" class="logo-form">
         <span><?php esc_html_e( 'Drag your form field to map it to a destination field.'     , 'automate_hub' ); ?></span>
      </div>
      <ul>
         <li class="form_fields_name"  v-for="(nfield, nindex) in trigger.formFields" :data-name="nindex"  :data-fname="nfield" v-if="CheckinDatabase(nindex,nfield)" >
            <div class="field-actions hide">
               <a type="copy" v-bind:id=nindex v-bind:data-name=nindex v-bind:data-field=nfield v-on:click="say($event)"  class="copy-img del-button btn formbuilder-icon-cancel copy-confirm" title="Copy Element"></a>
               <a type="remove" v-bind:id=nindex v-bind:data-name=nindex v-bind:data-field=nfield v-on:click="say($event)"  class="del-button btn formbuilder-icon-cancel delete-confirm" title="Remove Element"></a>
            </div>
            <span class="input-group-addon fx-dragdrop-handle">{{nfield}}</span>
         </li>
      </ul>
   </div>
   <div class="form_placeholder_wrap">

      <div v-if="action.paltformConnected == false">
         <div class="submit-button-plugin">
            <a href="<?php echo esc_url_raw( add_query_arg(['page'=>'automate_hub','tab'=>$app_data['app_slug']],admin_url( 'admin.php'))); ?>">
               <div  class="button button-primary"><?php echo sprintf('Connect Your %s Account',$app_name); ?> </div>
            </a>
         </div>
      </div>

      <div v-if="action.paltformConnected == true">
         <div class="form-select-account-wrapper">
            <div class="avalible-fields avalible-fields-wrapper ">
               <div class="avalible-fields__header">
                  <div class="avalible-fields__logo-wrap">
                     <div class="avalible-fields__logo-icon">
                        <img src="<?php echo esc_url($app_icon_url) ; ?>" alt="<?php echo esc_html($app_icon_alter_text); ?>">
                     </div>
                     <div class="avalible-fields__logo-text">
                        <span><?php echo esc_html($app_name); ?></span>
                       
                        <?php esc_html_e( 'Available Fileds'     , 'automate_hub' ); ?>

                     </div>
                  </div>
                  <div v-if="action.paltformConnected == true">
                     <div class="avalible-fields__header-select">
                        <p><?php echo sprintf(esc_html__('Select %s Account ','automate_hub'),$app_name); ?></p>
                        <?php 
                           $account_select_onchange = isset($app_data['account_select_onchange'])?$app_data['account_select_onchange']:'';
                           $account_select_name     = isset($app_data['account_select_name'])?$app_data['account_select_name']:'activePlatformId';
                           $account_select_model    = isset($app_data['account_select_model'])?$app_data['account_select_model']:'fielddata.activePlatformId';
                           $account_select_vfor     = isset($app_data['account_select_vfor'])?$app_data['account_select_vfor']:'(item, index) in action.accountList';
                           $account_select_vfor_value =isset($app_data['account_select_vfor_value'])?$app_data['account_select_vfor_value']:'index';
                           $account_select_vfor_text =isset($app_data['account_select_vfor_text'])?$app_data['account_select_vfor_text']:'{{item}}';
                        ?>
                        <select id="account_select_dropdown" name="<?php echo esc_attr($account_select_name); ?>" 
                           <?php if(!empty($account_select_model)){echo 'v-model="'.esc_attr($account_select_model).'"';} ?>
                           @change="<?php echo esc_attr($account_select_onchange); ?>" required="required"  class="global-two-form">
                            <option value=""> <?php esc_html_e( 'Select Account...', 'automate_hub' ); ?> </option>
                            <option v-for="<?php echo esc_attr($account_select_vfor); ?>" :value="<?php echo esc_attr($account_select_vfor_value); ?>" > <?php echo esc_html($account_select_vfor_text); ?>  </option>
                        </select>
                      
                     </div>
                  </div>
               </div>
            </div>
            <div v-if='fielddata.activePlatformId || fielddata.googleaccountID'>

               <?php 
                  $tasks= isset($app_data['tasks'])? $app_data['tasks']:array();

                  if(count($tasks)){
               ?>


                  <?php 
                        foreach ($tasks as $task_name => $task) {
                  ?>
                        <?php 
                        $product_mapping = isset($task['product_mapping']) ? $task['product_mapping']:false;
                        if($product_mapping){
                           require_once(AWP_VIEWS.'/product_mapping.php');
                        }

                        ?>
                        <?php 
                           $task_assignments = isset($task['task_assignments']) ? $task['task_assignments']:array();
                           
                           if(count($task_assignments)){
                        ?>

                           <div v-if="action.task == '<?php echo esc_attr($task_name); ?>'" class="avalible-fields avalible-fields-style">
                              <div class="avalible-fields__settings hidden-box">
                                 <label for="view-toggler-2" class="avalible-fields__mapping-top avalible-fields__top-tab">
                                    <h3 class="avalible-fields__title"><?php esc_html_e( 'Assignment Settings'     , 'automate_hub' ); ?></h3>
                                 </label>
                                 <input type="checkbox" id="view-toggler-2" />

                                 
                                    <div class="avalible-fields__shown-tab avalible-fields__settings-list hidden-2">
                                       
                                       <ul>

                                          <?php 
                                             foreach ($task_assignments as $key => $assignment) {

                                             ?>

                                                   <li>
                                                      <span><?php 
                                                      
                                                     $alabel = isset($assignment['label'])? $assignment['label']:''; 
                                                      echo esc_html($alabel);
                                                     ?></span>
                                                      
                                                         <?php 

                                                            $disabled = isset($assignment['disabled'])  ?  $assignment['disabled']:'';
                                                            $name=isset($assignment['name'])? $assignment['name']:'';
                                                            $v_model=isset($assignment['model'])?$assignment['model']:'';
                                                            $placeholder=isset($assignment['placeholder'])?$assignment['placeholder']:'';
                                                            $size=isset($assignment['size'])?$assignment['size']:'';
                                                            $onclick=isset($assignment['onclick'])?$assignment['onclick']:'';
                                                            $text=isset($assignment['text'])?$assignment['text']:'';
                                                            $spinner_class=isset($assignment['spinner']['bind-class'])?$assignment['spinner']['bind-class']:'';
                                                            $required=(isset($assignment['required']) && $assignment['required']!="")?$assignment['required']:'';
                                                            $multiple=(isset($assignment['multiple']) && $assignment['multiple']!="")?$assignment['multiple']:'';
                                                            $onchange=isset($assignment['onchange'])?$assignment['onchange']:'';
                                                        
                                                            $option_for_loop=isset($assignment['option_for_loop'])?$assignment['option_for_loop']:'';
                                                            $option_for_value=isset($assignment['option_for_value'])?$assignment['option_for_value']:"index";
                                                            $option_for_text=isset($assignment['option_for_text'])?$assignment['option_for_text']:'{{item}}';
                                                            
                                                            

                                                            $select_default=isset($assignment['select_default'])?$assignment['select_default']:'';
                                                            $reduce=isset($assignment['reduce'])?$assignment['reduce']:'';


                                                            $dynamic_option=isset($assignment['dynamic_option'])?$assignment['dynamic_option']:'null';
                                                            if($dynamic_option != 'null'){
                                                               $dynamic_select_name=isset($dynamic_option['name'])?$dynamic_option['name']:'';
                                                               $dynamic_v_model=isset($dynamic_option['model'])?$dynamic_option['model']:'';

                                                               $dynamic_option_for_loop=isset($dynamic_option['option_for_loop'])?$dynamic_option['option_for_loop']:'';

                                                               $dynamic_option_for_value=isset($dynamic_option['option_for_value'])?$dynamic_option['option_for_value']:'';

                                                               $dynamic_option_for_text=isset($dynamic_option['option_for_text'])?$dynamic_option['option_for_text']:'';
                                                               $dynamic_show_when=isset($dynamic_option['show_when'])?$dynamic_option['show_when']:'false';
                                                               
                                                               
                                                            }
                                                            
                                                            
                                                         if( isset($assignment['type']) && $assignment['type'] == 'select'){

                                                         ?>
                                                         <div class="">
                                                            <?php 
                                                               if( $multiple !="" ){
                                                                  ?> 

                                                                  <vue-select
                                                                   multiple
                                                                   placeholder="<?php esc_html_e( $select_default, 'automate_hub' ); ?>"
                                                                   :options="<?php echo esc_attr($option_for_loop); ?>" 
                                                                   label="name"
                                                                   :reduce="<?php echo esc_attr($reduce); ?>"
                                                                   v-model="<?php echo esc_attr($v_model); ?>"
                                                                 ></vue-select>

                                                                  <?php
                                                               }
                                                               else{


                                                            ?>
                                                            <select size="<?php echo esc_attr($size); ?>" name="<?php echo esc_attr($name); ?>" 
                                                               <?php if(!empty($v_model)){echo 'v-model="'.esc_attr($v_model).'"';} ?>
                                                               <?php if(!empty($onchange)){echo '@change="'.esc_attr($onchange).'"';} ?>
                                                              
                                                               <?php if(!empty($required)){echo 'required="'.esc_attr($required).'"';} ?>
                                                              
                                                               class="global-two-form" <?php if(!empty($disabled)){ ?> :disabled="<?php echo esc_attr($disabled);  ?>" <?php  } ?> >
                                                                <option value="" selected disabled> <?php esc_html_e( $select_default, 'automate_hub' ); ?> </option>

                                                                <option 
                                                                <?php if(!empty($option_for_loop)){echo 'v-for="'.esc_attr($option_for_loop).'"';} ?>
                                                                <?php if(!empty($option_for_value)){echo ':value="'.esc_attr($option_for_value).'"';} ?>
                                                               > <?php echo esc_html($option_for_text); ?>  </option>

                                                            </select>
                                                         
                                                         <?php          
                                                            }//end of if else of multiple 
                                                            echo '</div>';                                              
                                                         }// end of if type==select
                                                         ?>
                                                         <?php
                                                            if(isset($assignment['type']) &&  $assignment['type'] == 'text'){

                                                         ?>
                                                            <input placeholder="<?php echo esc_attr($placeholder); ?>" size="<?php echo esc_attr($size); ?>" name="<?php echo esc_attr($name); ?>" 
                                                            <?php if(!empty($v_model)){echo 'v-model="'.esc_attr($v_model).'"';} ?>
                                                            @change="<?php echo esc_attr($onchange); ?>" 
                                                            <?php if(!empty($required)){echo 'required="'.esc_attr($required).'"';} ?>
                                                            <?php if(!empty($disabled)){ ?> :disabled="<?php echo esc_attr($disabled);  ?>" <?php  } ?>  type="text" class="global-two-form">

                                                         <?php
                                                            }
                                                         ?>

                                                        

                                                         <?php
                                                            if( $assignment['type'] == 'button'){

                                                         ?>
                                                            <div class="">
                                                               <div @click="<?php echo esc_js($onclick); ?>"  class="button button-primary custom_field_type" ><?php echo esc_html($text); ?> </div>
                                                            </div>

                                                            

                                                         <?php
                                                            }
                                                         ?>
                                                         
                                                               <?php
                                                               if($dynamic_option != 'null'){
                                                               ?>
                                                                  <div class="spinner" v-bind:class="<?php echo wp_unslash(esc_js($spinner_class)); ?>"></div>
                                                                  <span v-if="<?php echo $dynamic_show_when ?> == false">

                                                                     <span class="avalible-fields__add-btn avalible-fields__button hover-line dx-additonal-condition">
                                                                         Use dynamic value
                                                                     </span>
                                                                     <div class="basedonfieldlist displaynone">
                                                                        <div class="inner-sction">
                                                                           <select name="<?php echo esc_attr($name); ?>" class="global-two-form margintop19"
                                                                              <?php if(!empty($dynamic_v_model)){echo 'v-model="'.esc_attr($dynamic_v_model).'"';} ?>
                                                                              >
                                                                              <option v-for="<?php echo esc_attr($dynamic_option_for_loop); ?>" :value="<?php echo esc_attr($dynamic_option_for_value); ?>" 
                                                                              > 
                                                                                 <?php echo esc_html($dynamic_option_for_text); ?>
                                                                               </option>
                                                                           </select>
                                                                        </div>
                                                                        <span class="dynamiccross">X</span>
                                                                     </div>
                                                                     
                                                                  </span>
                                                                  
                                                               <?php
                                                               } //end of if dynamic option
                                                               ?>

                                                         



                                                    
                                                     
                                                   </li>
                                          <?php 
                                                }
                                          ?>
                                          </ul>
                                    </div>
                                 
                              </div>
                           </div>




                        <?php
                           }
                        ?>


                        <!-- Addittional fields start -->


                        <?php 
                        
                           $custom_fields=isset($task['custom_fields'])?$task['custom_fields']:'';
                           if(is_array($custom_fields)){
                        ?>

                           <div v-if="action.task == '<?php echo esc_attr($task_name); ?>'" class="avalible-fields avalible-fields-style">
                              <div class="avalible-fields__settings hidden-box">
                                 <label for="view-toggler-3" class="avalible-fields__mapping-top avalible-fields__top-tab">
                                    <h3 class="avalible-fields__title"><?php esc_html_e( 'Additional Fields'     , 'automate_hub' ); ?></h3>
                                 </label>
                                 <input type="checkbox" id="view-toggler-3" />

                                 
                                    <div class="avalible-fields__shown-tab avalible-fields__settings-list hidden-3">
                                       
                                       <ul class="">
                                          <li>
                                             <span class="label-parent"></span>
                                             <div class="">
                                                <select class="global-two-form">
                                                      <option value=""></option>
                                                   
                                                </select>
                                             </div>
                                          </li>
                                          <?php 
                                                foreach ($custom_fields as $key => $custom_field) {
                                          ?>

                                                   <li>
                                                      <span><?php 
                                                       $clabel = isset($custom_field['label'])?$custom_field['label']:'';
                                                       echo esc_html($clabel);
                                                       ?></span>
                                                      
                                                         <?php 

                                                            $disabled=isset($custom_field['disabled'])?$custom_field['disabled']:'';
                                                            $name=isset($custom_field['name'])?$custom_field['name']:'';
                                                            $v_model=isset($custom_field['model'])?$custom_field['model']:'';
                                                            $placeholder=isset($custom_field['placeholder'])?$custom_field['placeholder']:'';
                                                            $size=isset($custom_field['size'])?$custom_field['size']:'';
                                                            $onclick=isset($custom_field['onclick'])?$custom_field['onclick']:'';
                                                            $text=isset($custom_field['text'])?$custom_field['text']:'';
                                                            $spinner_class=isset($custom_field['spinner']['bind-class'])?$custom_field['spinner']['bind-class']:'';
                                                            $required=(isset($custom_field['required']) && $custom_field['required']!="")?$custom_field['required']:'';
                                                            $onchange=isset($custom_field['onchange'])?$custom_field['onchange']:'';
                                                        
                                                            $option_for_loop=isset($custom_field['option_for_loop'])?$custom_field['option_for_loop']:'';
                                                            $option_for_value=isset($custom_field['option_for_value'])?$custom_field['option_for_value']:"index";
                                                            $option_for_text=isset($custom_field['option_for_text'])?$custom_field['option_for_text']:'{{item}}';
                                                            
                                                            

                                                            $select_default=isset($custom_field['select_default'])?$custom_field['select_default']:'';
                                                            
                                                         ?>

                                                         <?php
                                                            if($custom_field['type'] == 'text'){

                                                         ?>

                                                         <input placeholder="<?php echo esc_attr($placeholder); ?>" size="<?php echo esc_attr($size); ?>" name="<?php echo esc_attr($name); ?>" 
                                                         <?php if(!empty($v_model)){echo 'v-model="'.esc_attr($v_model).'"';} ?>
                                                         @change="<?php echo esc_attr($onchange); ?>" 
                                                         <?php if(!empty($required)){echo 'required="'.esc_attr($required).'"';} ?>   
                                                         <?php if(!empty($disabled)){ ?> :disabled="<?php echo esc_attr($disabled);  ?>" <?php  } ?>  type="text" class="global-two-form">

                                                         <?php
                                                            }
                                                         ?>
                                                         <?php
                                                            if($custom_field['type'] == 'select'){

                                                         ?>
                                                         <div class="">
                                                            <select size="<?php echo esc_attr($size); ?>" name="<?php echo esc_attr($name); ?>" 
                                                               <?php if(!empty($v_model)){echo 'v-model="'.esc_attr($v_model).'"';} ?>
                                                               @change="<?php echo esc_attr($onchange); ?>" 
                                                               <?php if(!empty($required)){echo 'required="'.esc_attr($required).'"';} ?>  
                                                               class="global-two-form" <?php if(!empty($disabled)){ ?> :disabled="<?php echo esc_attr($disabled);  ?>" <?php  } ?> >
                                                               <option value=""> <?php esc_html_e( $select_default, 'automate_hub' ); ?> </option>
                                                               <option v-for="<?php echo esc_attr($option_for_loop); ?>" :value="<?php echo esc_attr($option_for_value); ?>" > <?php echo esc_html($option_for_text); ?>  </option>

                                                            </select>
                                                         </div>

                                                         <?php
                                                            }
                                                         ?>

                                                        

                                                         <?php
                                                            if($custom_field['type'] == 'button'){

                                                         ?>
                                                         <div class="">
                                                            <div @click="<?php echo esc_js($onclick); ?>"  class="button button-primary custom_field_type" ><?php echo esc_html($text); ?> </div>
                                                         </div>

                                                            

                                                         <?php
                                                            }
                                                         ?>
                                                         
                                                         <div class="spinner" v-bind:class="<?php echo wp_unslash(esc_js($spinner_class)); ?>"></div>
                                                   </li>
                                          <?php 
                                                }
                                          ?>
                                          
                                          
                                       </ul>
                                    </div>
                                 
                              </div>
                           </div>




                        <?php
                           }
                        ?>


                        <!-- Addittional fields end -->

                  <?php
                        } // for each end
                  ?>


               <?php
                  } //end of if count condition
               ?>
               <h3 class="map-field-title"><?php echo $app_name ; ?> <?php esc_html_e( 'API fields available to map your form values to:'     , 'automate_hub' ); ?></h3>
               <table class="form-table form-fields-table">
                  <editable-field v-for="field in fields" v-bind:key="field.value" v-bind:field="field" v-bind:trigger="trigger" v-bind:action="action" v-bind:fielddata="fielddata" ></editable-field>
                  <tr valign="top" class="alternate" v-if="action.task == 'createLead' || action.task == 'createUser' ">
                                    <td scope="row-title" class="sperse_not_drop">
                                        <label for="tablecell" class="sperse_form_label">
                                            <?php esc_attr_e( 'User Agent', 'automate_hub' ); ?>
                                        </label>
                                    </td>
                                    <td>
                                        <input name="userAgent" type="text" v-model="fielddata.userAgent"  id="formfields">
                                    </td>
                                </tr>
                                <tr valign="top" class="alternate" v-if="action.task == 'createLead' || action.task == 'createUser' ">
                                    <td scope="row-title" class="sperse_not_drop">
                                        <label for="tablecell" class="sperse_form_label">
                                            <?php esc_attr_e( 'Referrer URL', 'automate_hub' ); ?>
                                        </label>
                                    </td>
                                    <td>
                                        <input name="referrerUrl" type="text" v-model="fielddata.referrerUrl"  id="formfields">
                                    </td>
                                </tr>
                                <tr valign="top" class="alternate" v-if="action.task == 'createLead' || action.task == 'createUser' ">
                                    <td scope="row-title" class="sperse_not_drop">
                                        <label for="tablecell" class="sperse_form_label">
                                            <?php esc_attr_e( 'Entry URL', 'automate_hub' ); ?>
                                        </label>
                                    </td>
                                    <td>
                                        <input name="entryUrl" type="text" v-model="fielddata.entryUrl"  id="formfields">
                                    </td>
                                </tr>
                                <tr valign="top" class="alternate" v-if="action.task == 'createLead' || action.task == 'createUser' ">
                                    <td scope="row-title" class="sperse_not_drop">
                                        <label for="tablecell" class="sperse_form_label">
                                            <?php esc_attr_e( 'Redirect URL', 'automate_hub' ); ?>
                                        </label>
                                    </td>
                                    <td>
                                        <input name="redirectUrl" type="text" v-model="fielddata.redirectUrl"  id="formfields">
                                    </td>
                                </tr>
                                <tr valign="top" class="alternate" v-if="action.task == 'createLead' || action.task == 'createUser' ">
                                    <td scope="row-title" class="sperse_not_drop">
                                        <label for="tablecell" class="sperse_form_label">
                                            <?php esc_attr_e( 'Client IP', 'automate_hub' ); ?>
                                        </label>
                                    </td>
                                    <td>
                                        <input name="clientIP" type="text" v-model="fielddata.clientIP"  id="formfields">
                                    </td>
                                </tr>
                                <tr valign="top" class="alternate" v-if="action.task == 'createLead' || action.task == 'createUser' ">
                                    <td scope="row-title" class="sperse_not_drop">
                                        <label for="tablecell" class="sperse_form_label">
                                            <?php esc_attr_e( 'channelCode', 'automate_hub' ); ?>
                                        </label>
                                    </td>
                                    <td>
                                        <input name="channelCode" type="text" v-model="fielddata.channelCode"  id="formfields">
                                    </td>
                                </tr>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>
