<div class="margintop19" v-if="action.task == '<?php echo esc_attr($task_name); ?>'">
                 <div class="avalible-fields">
                     <div>
                     <div class="avalible-fields__mapping hidden-box">
                         <label for="view-toggler-1" class="avalible-fields__mapping-top avalible-fields__top-tab">
                             <h3 class="avalible-fields__title"><?php esc_html_e( 'Product Mapping', 'automate_hub' ); ?></h3>
                         </label>
                         <input type="checkbox" id="view-toggler-1" />
                         <div class="avalible-fields__mapping-table avalible-fields__shown-tab hidden">
                             <table>
                                 <tr>
                                     <th><?php esc_html_e( 'Wordpress Product', 'automate_hub' ); ?></th>
                                     <th><?php esc_html_e( 'Sperse Product', 'automate_hub' ); ?></th>
                                     <th><?php esc_html_e( 'Remove', 'automate_hub' ); ?></th>
                                 </tr>
                                 <tr v-for="(section, rowIndex) in sections">
                                     <td>
                                       <vue-select
                                        
                                         placeholder="Wordpress Products "
                                         :options="fielddata.wpproducts" 
                                         label="name"
                                         :reduce="name => name.id"
                                         :id="'wpProductRow'+ rowIndex"
                                          v-model="sections[rowIndex].wpProduct"
                                         @input="setPMId($event, 'wpProductRow'+rowIndex)"
                                       ></vue-select>
                                     </td>
                                     <td>
                                          <table class='pm-table'>
                                            <tr v-for="(row, index) in section.sperseProducts">
                                             <td class="border_unset">
                                             <vue-select
                                             v-model="sections[rowIndex].sperseProducts[index].sperseProduct"
                                             placeholder="Sperse Products"
                                             :options="fielddata.sperseProducts" 
                                             label="name"
                                             :reduce="name => name.code"
                                             :id="'sperseProductCol'+ rowIndex + index"
                                             @input="setPMId($event, 'sperseProductCol'+ rowIndex + index)"
                                           ></vue-select>
                                              </td>
                                              
                                            </tr>
                                           </table>
                                           <div class='mapAndSaveBtns'>
                                             <span class="avalible-fields__add-map avalible-fields__button" v-on:click="addNewItem(rowIndex)">Map Sperse Product</span>
                                             <span class="avalible-fields__add-map avalible-fields__button" v-on:click="saveNewItem(rowIndex)">Save</span>
                                         </div>
                                     </td> 
                                     <td>
                                          <table class='pm-table'>
                                            <tr v-for="(row, index) in section.sperseProducts">
                                              <td class="border_unset">
                                                 <ul class="avalible-fields__action-list row-view">
                                                 <li>
                                                 <span class="avalible-fields__remove-btn avalible-fields__button hover-line" v-on:click="removeSperseElement(rowIndex, index);" :id="rowIndex"></span>
                                                 </li>
                                                 </ul>
                                              </td>
                                            </tr>
                                           </table>
                                     </td>  
                             </tr>
                             </table>
                             <div>
                             <span class="avalible-fields__add-mapping avalible-fields__button" v-on:click="addRow($event)">
                                 Add New Mapping
                             </span>
                             </div>
                         </div>
                         <div class="spinner" v-bind:class="{'is-active': mappedProductLoading}"></div>
                     </div>
                     </div>
                   </div>
               </div>