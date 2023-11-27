<?php
add_filter( 'awp_action_providers', 'awp_sperse_actions', 10, 1 );
// ****************************************** 
// *** ACTIONS AVAILABLE IN SPERSE        *** 
// ****************************************** 
function awp_sperse_actions( $actions ) {
    $actions['sperse'] = array(
        'title' => esc_html__( 'Sperse', 'automate_hub'),
        'tasks' => array(
            'createLead'  => esc_html__( 'Save or Update Contact in CRM'  , 'automate_hub' ),
            'createUser'  => esc_html__( 'Create and Login a New User'    , 'automate_hub' ),
            'loginUser'   => esc_html__( 'Login to User Account'          , 'automate_hub' ),
            'subscribe'   => esc_html__( 'Subscribe Contact to a List'    , 'automate_hub' ),
            'unsubscribe' => esc_html__( 'Unsubscribe Contact from List'  , 'automate_hub' ),
        )
    );
    return $actions;
}

add_filter( 'awp_settings_tabs', 'awp_sperse_settings_tab', 10, 1 );

function awp_sperse_settings_tab( $providers ) {
    $providers['sperse'] =
    array('name'=>esc_html__( 'Sperse', 'automate_hub'),'cat' =>array('crm','esp','sms'));
    return $providers;
}

add_action( 'awp_settings_view', 'awp_sperse_settings_view', 10, 1 );

// ****************************************** 
// ***  SPERSE SETTINGS AND INSTRUCTIONS  *** 
// ****************************************** 
function awp_sperse_settings_view( $current_tab ) {
    if( $current_tab != 'sperse' ) {
        return;
    }
    $nonce           = wp_create_nonce( "awp_sperse_settings" );
    $api_key         = get_option( 'awp_sperse_api_key' ) ? get_option( 'awp_sperse_api_key' ) : "";
    $url             = get_option( 'awp_sperse_url') ? get_option( 'awp_sperse_url' ) : "";
    $Environment_url = array( 'https://app.sperse.com','https://testadmin.sperse.com','https:/beta.sperse.com');
    $action          = isset( $_GET['action'       ] ) ? sanitize_text_field($_GET['action']) : 'list';
    $id              = isset( $_GET['account_id'   ] ) ? intval(sanitize_text_field( $_GET['account_id']) ) : 0;
    $sync_contact_id = isset( $_GET['sync_contacts'] ) ? intval( sanitize_text_field($_GET['sync_contacts'] )) : 0;
    switch ( $action ) {
        case 'edit'         :   awp_sperse_settings( $id );                      awp_list_page() ; break;
        case 'status'       :   awp_change_status($id);                          awp_list_page() ; break;
        case 'active_status':   awp_change_status($id);                   awp_list_page() ; break;    
        // case 'sync_contacts':   awp_change_sync_contacts($sync_contact_id,$id ); awp_list_page() ; break;      
        default:                awp_sperse_settings();                           awp_list_page() ; break;
    }
}

add_action( 'admin_post_awp_save_sperse_api_key', 'awp_save_sperse_api_key', 10, 0 );

$current_tab = isset( $_REQUEST['tab'] ) ? sanitize_text_field($_REQUEST['tab']) : 'sperse';
if($current_tab == 'sperse' && isset( $_GET['sync_contacts'] )){
    $sync_contact_id = isset( $_GET['sync_contacts'] ) ? intval(sanitize_text_field( $_GET['sync_contacts'] )) : 0;
    $id              = isset( $_GET['id'   ] ) ? intval( sanitize_text_field($_GET['id'] )) : 0;
    awp_change_sync_contacts($sync_contact_id,$id );
}

    function awp_change_sync_contacts(  $sync_contact_id, $id = '' ) {
        global $wpdb;
        $config_data = awp_get_sperse_active_status_account($id);
        $ins_url = $config_data['base_url'] ? $config_data['base_url'] : "";
        $api_key = $config_data['apikey'] ? $config_data['apikey'] : "";
        $relation_table = $wpdb->prefix . "awp_platform_settings";    
        if(($sync_contact_id == 0)) {
            $targetUrl = get_bloginfo('url').'/wp-json/automatehub/userCreatedOrUpdated';
            $url       = $ins_url . "/api/services/Platform/Event/Subscribe";
            $properties =  array("eventName" => "User.CreatedOrUpdated", "targetUrl" => $targetUrl);
            $args = array('headers' => array('api-key'      => $api_key,'Content-Type' => 'application/json'), 'body' => json_encode($properties));
            $start_time = current_time('mysql',true);
            $return = wp_remote_post( $url, $args );
            $record = [];
            if(is_wp_error($return) || empty($return["body"])){
                AWP_redirect( 'admin.php?page=automate_hub&tab=sperse&cat=favorites');
            }else{
                $resp = json_decode($return["body"]);
                if(empty($resp->error)){
                    if(isset($resp->result->id)){
                        $respId = $resp->result->id;
                        $wpdb->query($wpdb->prepare("UPDATE $relation_table SET sync_contacts='%d' WHERE id='%d'",$respId,$id));
                        AWP_redirect( 'admin.php?page=automate_hub&tab=sperse&cat=favorites');
                    }                    
                }else{
                    $pattern = "/Such target url is already linked to event/i";
                    if(preg_match($pattern, $resp->error->message)){
                        $url  = $ins_url . "/api/services/Platform/Event/GetSubscriptions";
                        $args = array(
                               'headers' => array(
                                  'api-key' => $api_key
                                )
                        );
                        $get_subscriptions = wp_remote_get( $url,$args);
                        if(is_wp_error($get_subscriptions) || empty($get_subscriptions["body"])){
                        }else{
                            $all_subs = (array)json_decode($get_subscriptions["body"]);
                            if(!empty($all_subs['result']) && count($all_subs['result'])>0){
                                foreach ( $all_subs['result'] as $key => $value) {
                                    if($value->targetUrl==$targetUrl){
                                        $respId = $value->id;
                                        $wpdb->query($wpdb->prepare("UPDATE $relation_table SET sync_contacts='%d' WHERE id='%d'",$respId,$id));
                                    }
                                }
                            }
                        }
                    } 
                    AWP_redirect( 'admin.php?page=automate_hub&tab=sperse&cat=favorites');
                }
            }
        } else {
            $properties = array('id'   => $sync_contact_id);
            $query = http_build_query( $properties );
            $url = $ins_url . "/api/services/Platform/Event/Unsubscribe?" . $query;
            $args = array('headers' => array('api-key' => $api_key, 'Content-Type' => 'application/x-www-form-urlencoded'), 'body' => json_encode($properties));
            $start_time = current_time('mysql',true);
            $return = wp_remote_post( $url, $args );
            $record = [];
            $wpdb->query($wpdb->prepare("UPDATE $relation_table SET sync_contacts='0' WHERE id = '%d' ",$id));   
            AWP_redirect( 'admin.php?page=automate_hub&tab=sperse&cat=favorites');
        }
        awp_add_to_log( $return, $url, $args, $record,$start_time );
    }
   function awp_change_status( $id = '' ) {
        global $wpdb;
        $relation_table = $wpdb->prefix . "awp_integration";
        $status_data    = $wpdb->get_row( "SELECT * FROM {$relation_table} WHERE id = {$id}", ARRAY_A );
        $status         = $status_data["status"];
        if ( $status ) {
            $action_status = $wpdb->update( $relation_table,
                array('status' => false,),
                array( 'id'=> $id));
        }else{
            $action_status = $wpdb->update( $relation_table,
                array('status' => true,),
                array( 'id'=> $id));
        }
        AWP_redirect( admin_url( 'admin.php?page=automate_hub' ) );
    }
    
    function awp_list_page() {
        if ( isset( $_GET['status'] ) ) {
            $status = sanitize_text_field($_GET['status']);
        }
        ?>
        <div class="wrap">
            <form id="form-list" method="post">
                <input type="hidden" name="page" value="automate_hub"/>
                <?php
                $data=['table-cols'=>['account_name'=>'Display Name','url'=>'Sperse Environment URL Path','api_key'=>'API Key','sync_contacts'=>'Sync Contacts','active_status'=>'Active']];
                $platform_obj= new AWP_Platform_Shell_Table('sperse');
                $platform_obj->initiate_table($data);
                $platform_obj->prepare_items();
                $platform_obj->display_table();                        
                ?>
            </form>
        </div>
        <?php
    }

function awp_sperse_settings($id=''){
$data=array();
$id = isset($_GET['id']) ? intval( sanitize_text_field($_GET['id'] )) : "";
$edit_data = awp_get_app_edit_data($id);
$account_name     = isset($edit_data['account_name']) ? sanitize_text_field($edit_data['account_name']) : "";
$api_key = isset($edit_data['api_key']) ? sanitize_text_field($edit_data['api_key']) : "";
$url     = isset($edit_data['url']) ? sanitize_url($edit_data['url']) : "https://app.sperse.com";
?>
<div class="no-platformheader">
<a href="https://sperse.io/go/sperse" target="_blank"><img src="<?php echo esc_url(AWP_ASSETS.'/images/logos/sperse.png'); ?>" width="169" height="50" alt="Sperse Logo"></a><br/><br/>
<?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager('sperse');
                    $instruction_obj->prepare_instructions();
                ?>
                <br/>
                <?php 
$form_fields = '';
$app_name= 'sperse';
$sperse_form = new AWP_Form_Fields($app_name);
$form_fields = $sperse_form->awp_wp_text_input(
    array(
        'id'            => "awp_sperse_account_name",
        'name'          => "awp_sperse_account_name",
        'value'         => $account_name,
        'placeholder'   => 'Enter an identifier for this account',
        'label'         => 'Display Name',
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $sperse_form->awp_wp_text_input(
    array(
        'id'            => "awp_sperse_api_key",
        'name'          => "awp_sperse_api_key",
        'value'         => $api_key,
        'placeholder'   => esc_html__( 'Paste your Sperse API Key here', 'automate_hub' ),
        'label'         =>  esc_html__( 'Sperse API Key', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $sperse_form->awp_wp_text_input(
    array(
        'id'            => "awp_sperse_url",
        'name'          => "awp_sperse_url",
        'value'         => $url,
        'placeholder'   => esc_html__( 'Enter your Sperse environment URL path', 'automate_hub' ),
        'label'         =>  esc_html__( 'Sperse Environment URL', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'data_type'=>'url',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $sperse_form->awp_wp_hidden_input(
    array(
        'name'          => "account_id",
        'value'         => $id,
    )
);
$form_fields .= $sperse_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_save_sperse_api_key',
    )
);

$form_fields .= $sperse_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);

$form_fields .= $sperse_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>wp_create_nonce('awp_sperse_settings'),
    )
);




$sperse_form->render($form_fields);


?>
</div>
<?php 
}
// ****************************************** 
// *** SAVE SPERSE API KEY AND URL IN DB  *** 
// ****************************************** 
function awp_save_sperse_api_key() {
      if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'awp_sperse_settings' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }
    $platform_obj= new AWP_Platform_Shell_Table('sperse');
    $platform_obj->save_platform([
        'account_name'=> isset($_POST['awp_sperse_account_name']) ? sanitize_text_field($_POST['awp_sperse_account_name']) :'',
        'url'=> isset($_POST['awp_sperse_url']) ? sanitize_url($_POST['awp_sperse_url']) :'',
        'api_key'=>isset($_POST['awp_sperse_api_key']) ? sanitize_text_field($_POST['awp_sperse_api_key']):'',
        'sync_contacts'=>'0'
    ]);
    AWP_redirect( "admin.php?page=automate_hub&tab=sperse" );
}
add_action( 'awp_add_js_fields', 'awp_sperse_js_fields', 10, 1 );

function awp_sperse_js_fields( $field_data ) {}
add_action( 'awp_action_fields', 'awp_sperse_action_fields' );

// ****************************************** 
// *** Map Fields for Login Action        *** 
// ****************************************** 
function awp_sperse_action_fields() {
    global $wpdb;
    $integration_id = !empty($_GET['id']) ? intval( sanitize_text_field($_GET['id'] )) : '';
    $sperse_accountId='';
    if(!empty($integration_id)){
        $relation_table = $wpdb->prefix . "awp_integration";
        $status_data    = $wpdb->get_row($wpdb->prepare( "SELECT * FROM ".$relation_table." WHERE id = %d",$integration_id), ARRAY_A );
        $result_data = json_decode($status_data['data']);
        $sperse_accountId = !empty($result_data->field_data->sperse_accountId) ? sanitize_text_field($result_data->field_data->sperse_accountId) : '';
        if(!empty($sperse_accountId)){
        ?>
        <script type="text/javascript">
            var integration_id = <?php echo esc_html($sperse_accountId); ?>
        </script>
        <?php }
} ?>
<div class="dragabble-wrapper">
    <script type="text/template" id="sperse-action-template">
    <div class="forms-setting-wrapper">
        <div class="form_fields sperse_reverse_draggable">
            <div class="dynamic-form-logo"> <img v-if="trigger.formProviderId" v-bind:src="'<?php echo AWP_ASSETS; ?>/images/icons/' + trigger.formProviderId + '.png'" v-bind:alt="trigger.formProviderId" class="logo-form">
                <span>Drag your form field to map it to a destination field.</span></div>       
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
            <div class="form-select-account-wrapper">
            <div class="avalible-fields">
            <div class="avalible-fields__header">
                <div class="avalible-fields__logo-wrap">
                    <div class="avalible-fields__logo-icon">
                        <img src="<?php echo AWP_ASSETS; ?>/images/icons/sperse.png" alt="Sperse Logo">
                    </div>
                    <div class="avalible-fields__logo-text">
                        <span>                        <?php esc_html_e( 'Sperse', 'automate_hub' ); ?></span>
                        <?php esc_html_e( 'Available Fileds', 'automate_hub' ); ?>
                    </div>
                </div>
                <div v-if="action.paltformConnected == true">
                <div class="avalible-fields__header-select">
                    <p><?php esc_html_e( 'Select Sperse Account ', 'automate_hub' ); ?></p>
                        <vue-select
                        placeholder="Select Sperse Account "
                        :options="action.accountList" 
                        label="accountName"
                        :reduce="accountName => accountName.accountId"
                        v-model="fielddata.sperse_accountId"
                        @input="increaseCount"
                      ></vue-select>
                </div>
                </div>
            </div>
    </div> 
        <div v-if='fielddata.sperse_accountId'>
        <div class="avalible-fields">
            <div v-if="action.task == 'createUser'">
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
            <div class="avalible-fields__settings hidden-box">
                <label for="view-toggler-2" class="avalible-fields__mapping-top avalible-fields__top-tab">
                    <h3 class="avalible-fields__title">Assignment Settings</h3>
                </label>
                <input type="checkbox" id="view-toggler-2" />
                <div v-if="(action.task == 'createLead' || action.task == 'createUser') && action.paltformConnected==true" class="avalible-fields__shown-tab avalible-fields__settings-list hidden-2">
                <ul class="">
                    <li>
                        <span>Contact Group</span>
                        <div class="">
                            <vue-select
                            placeholder="Select Contact Group "
                            :options="fielddata.list" 
                            label="name"
                            :reduce="name => name.id"
                            v-model="fielddata.listId"
                            @input='changeContactGroup'
                          ></vue-select>
                            <div class="spinner" v-bind:class="{'is-active': listLoading}"></div>
                        </div>
                        <span class="avalible-fields__add-btn avalible-fields__button hover-line dx-additonal-condition">
                            Use dynamic value
                        </span>
                        <div class="basedonfieldlist" style="display:none;">
                        <div class="inner-sction">
                            <select name="selectedContactValue" class="dropdown_sperse_account" v-model="fielddata.selectedContactValue">
                                <option value="" selected > <?php esc_html_e( 'Based on value of selected field', 'automate_hub' ); ?> </option>
                                <option v-for="(nitem, nindex) in trigger.formFields" :value="nindex" > {{nitem}}  </option>
                            </select>
                            
                        </div>
                        <span class="dynamiccross">X</span>
                    </div>
                    </li>
                    <li>
                        <span>Stage</span>
                        <div class="">
                            <vue-select
                            class="selectedStagesClassW"
                            placeholder="Select Stage "
                            :options="fielddata.stages" 
                            label="name"
                            :reduce="name => name.id"
                            v-model="fielddata.StagesId"
                          ></vue-select>
                            <div class="spinner" v-bind:class="{'is-active': StagesLoading}"></div>
                        </div>
                        <span class="avalible-fields__add-btn avalible-fields__button hover-line dx-additonal-condition">
                            Use dynamic value
                        </span>
                        <div class="basedonfieldlist displaynone">
                        <div class="inner-sction">
                            <select name="selectedContactValue" class="dropdown_sperse_account" v-model="fielddata.selectedContactValue">
                                <option value="" selected> <?php esc_html_e( 'Based on value of selected field', 'automate_hub' ); ?> </option>
                                <option v-for="(nitem, nindex) in trigger.formFields" :value="nindex" > {{nitem}}  </option>
                            </select>
                            
                        </div>
                        <span class="dynamiccross">X</span>
                    </div>
                    </li>
                    <li>
                        <span>Assign</span>
                        <div class="">
                            <vue-select
                            placeholder="Select Assign To"
                            :options="fielddata.ausers" 
                            label="name"
                            :reduce="name => name.id"
                            v-model="fielddata.userId"
                          ></vue-select>
                            <div class="spinner" v-bind:class="{'is-active': usesLoading}" ></div>
                        </div>
                        <span class="avalible-fields__add-btn avalible-fields__button hover-line dx-additonal-condition">
                            Use dynamic value
                        </span>
                        <div class="basedonfieldlist displaynone">
                        <div class="inner-sction">
                            <select name="selectedContactValue" class="dropdown_sperse_account" v-model="fielddata.selectedContactValue">
                                <option selected> <?php esc_html_e( 'Based on value of selected field', 'automate_hub' ); ?> </option>
                                <option v-for="(nitem, nindex) in trigger.formFields" :value="nindex" > {{nitem}}  </option>
                            </select>
                            
                        </div>
                        <span class="dynamiccross">X</span>
                    </div>
                    </li>
                    <li>
                        <span>List</span>
                        <div class="">
                            <vue-select
                            multiple
                            placeholder="Select List"
                            :options="fielddata.lists" 
                            label="name"
                            :reduce="name => name.name"
                            v-model="fielddata.listsid"
                          ></vue-select>
                            <div class="spinner" v-bind:class="{'is-active': lisstLoading}"></div>
                        </div>
                        <span class="avalible-fields__add-btn avalible-fields__button hover-line dx-additonal-condition">
                            Use dynamic value
                        </span>
                        <div class="basedonfieldlist displaynone" >
                        <div class="inner-sction">
                            <select name="selectedContactValue" class="dropdown_sperse_account" v-model="fielddata.selectedContactValue">
                                <option value="" selected> <?php esc_html_e( 'Based on value of selected field', 'automate_hub' ); ?> </option>
                                <option v-for="(nitem, nindex) in trigger.formFields" :value="nindex" > {{nitem}}  </option>
                            </select>
                            
                        </div>
                        <span class="dynamiccross">X</span>
                    </div>
                    </li>
                    <li>
                        <span>Tags</span>
                        <div class="">
                                <vue-select
                                multiple
                                placeholder="Select Tags "
                                :options="fielddata.tags" 
                                label="name"
                                :reduce="name => name.name"
                                v-model="fielddata.tagId"
                              ></vue-select>
                            <div class="spinner" v-bind:class="{'is-active': tagLoading}"></div>
                        </div>
                        <span class="avalible-fields__add-btn avalible-fields__button hover-line dx-additonal-condition">
                            Use dynamic value
                        </span>
                        <div class="basedonfieldlist displaynone">
                        <div class="inner-sction">
                            <select name="selectedContactValue" class="dropdown_sperse_account" v-model="fielddata.selectedContactValue">
                                <option value="" selected> <?php esc_html_e( 'Based on value of selected field', 'automate_hub' ); ?> </option>
                                <option v-for="(nitem, nindex) in trigger.formFields" :value="nindex" > {{nitem}}  </option>
                            </select>
                            
                        </div>
                        <span class="dynamiccross">X</span>
                    </div>
                    </li>
                    <li>
                        <span>Roles</span>
                        <div class="">
                            <vue-select
                            multiple
                            placeholder="Select Roles"
                            :options="fielddata.roles" 
                            label="name"
                            :reduce="name => name.name"
                            v-model="fielddata.roleID"
                          ></vue-select>
                            <div class="spinner" v-bind:class="{'is-active': roleLoading}"></div>
                        </div>
                        <span class="avalible-fields__add-btn avalible-fields__button hover-line dx-additonal-condition">
                            Use dynamic value
                        </span>
                        <div class="basedonfieldlist displaynone" >
                        <div class="inner-sction">
                            <select name="selectedContactValue" class="dropdown_sperse_account" v-model="fielddata.selectedContactValue">
                                <option value="" selected> <?php esc_html_e( 'Based on value of selected field', 'automate_hub' ); ?> </option>
                                <option v-for="(nitem, nindex) in trigger.formFields" :value="nindex" > {{nitem}}  </option>
                            </select>
                        </div>
                        <span class="dynamiccross">X</span>
                    </div>
                    </li>
                    <div v-if="(action.task == 'createLead' || action.task == 'createUser') && action.paltformConnected==true">
                        <li>
                            <span>Invitation Email</span>
                            <div class="">
                                <vue-select
                                placeholder="Send Option"
                                :options="fielddata.inviteOptions" 
                                label="name"
                                :reduce="name => name.id"
                                v-model="fielddata.inviteSelected"
                              ></vue-select>        
                            </div>
                        </li>
                    </div
                    <li>
                        <span>Organigation ID</span>
                        <div class="">
                            <input type="hidden" name="OrganigationID" v-model="fielddata.OrganigationID" /> 
                        </div>
                        <span class="avalible-fields__add-btn avalible-fields__button hover-line dx-additonal-condition">
                            Use dynamic value
                        </span>
                        <div class="basedonfieldlist displaynone">
                        <div class="inner-sction">
                            <select name="selectedContactValue" class="dropdown_sperse_account" v-model="fielddata.selectedContactValue">
                                <option value="" selected> <?php esc_html_e( 'Based on value of selected field', 'automate_hub' ); ?> </option>
                                <option v-for="(nitem, nindex) in trigger.formFields" :value="nindex" > {{nitem}}  </option>
                            </select>
                        </div>
                        <span class="dynamiccross">X</span>
                    </div>
                    </li>
                </ul>
            </div>
            </div>
        </div>
                            <h3 class="map-field-title"><?php esc_attr_e( 'Sperse API fields available to map your form values to: ', 'automate_hub' ); ?></h3>
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
                    <div v-if="action.paltformConnected == false">
                        <div class="submit-button-plugin submit-button-plugin-type">
                        <a href="<?php echo esc_url(admin_url( 'admin.php?page=automate_hub&tab=sperse' )); ?>">
                            <div  class="button button-primary custom_field_type" > <?php esc_attr_e( 'Connect Your Sperse Account', 'automate_hub' ); ?></div>
                        </a>
                        </div>
                    </div>
        </div>
    </div>
    </div>
    </script>
</div>
    <?php
}

add_action( 'wp_ajax_awp_get_sperse_list', 'awp_get_sperse_list', 10, 0 );

function awp_get_sperse_list() {
      if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }
    $integration_id = !empty($_POST['id']) ? sanitize_text_field($_POST['id']) : '';
    $config_data = awp_get_sperse_active_status_account($integration_id);
    if(!empty($config_data) && is_array($config_data) && count($config_data)>0){
        $api_key = $config_data['apikey'] ? $config_data['apikey'] : "";
        $ins_url = $config_data['base_url'] ? $config_data['base_url'] : "";
    }else{
        $api_key = get_option( 'awp_sperse_api_key' ) ? get_option( 'awp_sperse_api_key' ) : "";
        $ins_url = get_option( 'awp_sperse_url'     ) ? get_option( 'awp_sperse_url'     ) : "";
    }
    if( !$api_key || !$ins_url ) {
        return array('empty');
    }
    $url = $ins_url . "/api/services/CRM/Contact/GetContactGroups";
    $args = array(
        'headers' => array(
            'api-key'      => $api_key,
            'Content-Type' => 'application/json'
        )
    );
    $data = wp_remote_request( $url, $args );
    if( !is_wp_error( $data ) ) {
        $body  = json_decode( $data["body"] );
        $lists = wp_list_pluck( $body->result, 'name', 'id' );
        wp_send_json_success( $lists );
    } else {
        wp_send_json_error();
    }
}

add_action( 'wp_ajax_awp_get_sperse_stages', 'awp_get_sperse_stages', 10, 0 );

function awp_get_sperse_stages() {

      if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }
    $integration_id = !empty($_POST['id']) ? sanitize_text_field($_POST['id']) : '';
    $config_data = awp_get_sperse_active_status_account($integration_id);
    if(!empty($config_data) && is_array($config_data) && count($config_data)>0){
        $api_key = $config_data['apikey'] ? $config_data['apikey'] : "";
        $ins_url = $config_data['base_url'] ? $config_data['base_url'] : "";
    }else{
        $api_key = get_option( 'awp_sperse_api_key' ) ? get_option( 'awp_sperse_api_key' ) : "";
        $ins_url = get_option( 'awp_sperse_url'     ) ? get_option( 'awp_sperse_url'     ) : "";
    }
    if( !$api_key || !$ins_url ) {
        return array('empty');
    }
    $contactGroupId = !empty($_POST['groupId']) ?sanitize_text_field($_POST['groupId'] ):'C';
    $url = $ins_url . "/api/services/CRM/Pipeline/GetPipelineDefinitions?purposeId=L&contactGroupId=".$contactGroupId;
    $args = array(
        'headers' => array(
            'api-key'      => $api_key,
            'Content-Type' => 'application/json'
        ),
    );
    $data = wp_remote_request( $url, $args );
    if( !is_wp_error( $data ) ) {
        $body  = json_decode( $data["body"] );
        $lists = wp_list_pluck( $body->result[0]->stages, 'name', 'id' );
        wp_send_json_success( $lists);
    } else {
        wp_send_json_error();
    }
}

add_action( 'wp_ajax_awp_get_sperse_tags', 'awp_get_sperse_tags', 10, 0 );

function awp_get_sperse_tags() {

      if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }
    $integration_id = !empty($_POST['id']) ? sanitize_text_field($_POST['id']) : '';
    $config_data = awp_get_sperse_active_status_account($integration_id);
    if(!empty($config_data) && is_array($config_data) && count($config_data)>0){
        $api_key = $config_data['apikey'] ? $config_data['apikey'] : "";
        $ins_url = $config_data['base_url'] ? $config_data['base_url'] : "";
    }else{
        $api_key = get_option( 'awp_sperse_api_key' ) ? get_option( 'awp_sperse_api_key' ) : "";
        $ins_url = get_option( 'awp_sperse_url'     ) ? get_option( 'awp_sperse_url'     ) : "";
    }
    if( !$api_key || !$ins_url ) {
        return array('empty');
    }
    $url = $ins_url . "/api/services/CRM/Dictionary/GetTags";
    $contactGroupId = !empty($_POST['groupId']) ?sanitize_text_field($_POST['groupId'] ):'C';
    $properties = array('purposeId'=>"L", 'contactGroupId'=>$contactGroupId );
    $args = array('headers' => array('api-key'      => $api_key, 'Content-Type' => 'application/json'));
    $data = wp_remote_request( $url, $args );
    if( !is_wp_error( $data ) ) {
        $body  = json_decode( $data["body"] );
        $lists = wp_list_pluck( $body->result, 'name', 'id' );
        wp_send_json_success( $lists );
    } else {
        wp_send_json_error();
    }
}

add_action( 'wp_ajax_awp_get_sperse_lists', 'awp_get_sperse_lists', 10, 0 );

function awp_get_sperse_lists() {

      if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }
    $integration_id = !empty($_POST['id']) ? sanitize_text_field($_POST['id']) : '';
    $config_data = awp_get_sperse_active_status_account($integration_id);
    if(!empty($config_data) && is_array($config_data) && count($config_data)>0){
        $api_key = $config_data['apikey'] ? $config_data['apikey'] : "";
        $ins_url = $config_data['base_url'] ? $config_data['base_url'] : "";
    }else{
        $api_key = get_option( 'awp_sperse_api_key' ) ? get_option( 'awp_sperse_api_key' ) : "";
        $ins_url = get_option( 'awp_sperse_url'     ) ? get_option( 'awp_sperse_url'     ) : "";
    }
    if( !$api_key || !$ins_url ) {
        return array('empty');
    }
    $url = $ins_url . "/api/services/CRM/Dictionary/GetLists";
    $contactGroupId = !empty($_POST['groupId']) ? sanitize_text_field( $_POST['groupId']) :'C';

    $properties = array('purposeId'=>"L", 'contactGroupId'=>$contactGroupId);
    $args = array('headers' => array('api-key' => $api_key,'Content-Type' => 'application/json'));
    $data = wp_remote_request( $url, $args );
    if( !is_wp_error( $data ) ) {
        $body  = json_decode( $data["body"] );
        $lists = wp_list_pluck( $body->result, 'name', 'id' );
        wp_send_json_success( $lists );
    } else {
        wp_send_json_error();
    }
}

add_action( 'wp_ajax_awp_get_sperse_roles', 'awp_get_sperse_roles', 10, 0 );

function awp_get_sperse_roles() {

      if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }
    $integration_id = !empty($_POST['id']) ? sanitize_text_field($_POST['id']) : '';
    $config_data = awp_get_sperse_active_status_account($integration_id);
    if(!empty($config_data) && is_array($config_data) && count($config_data)>0){
        $api_key = $config_data['apikey'] ? $config_data['apikey'] : "";
        $ins_url = $config_data['base_url'] ? $config_data['base_url'] : "";
    }else{
        $api_key = get_option( 'awp_sperse_api_key' ) ? get_option( 'awp_sperse_api_key' ) : "";
        $ins_url = get_option( 'awp_sperse_url'     ) ? get_option( 'awp_sperse_url'     ) : "";
    }
    if( !$api_key || !$ins_url ) {
        return array('empty');
    }
    $url = $ins_url . "/api/services/Platform/Role/GetRoles"; 
    $args = array('headers' => array('api-key' => $api_key,'Content-Type' => 'application/json'), 'body' => '{}');
    $data = wp_remote_post( $url, $args );

    if( !is_wp_error( $data ) ) {
        $body  = json_decode( $data["body"] );
        $lists = wp_list_pluck( $body->result->items, 'name', 'displayName' );
        wp_send_json_success($lists);
    } else {
        wp_send_json_error();
    }
}

add_action( 'wp_ajax_awp_get_sperse_ausers', 'awp_get_sperse_ausers', 10, 0 );

function awp_get_sperse_ausers() {

      if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }
    $integration_id = !empty($_POST['id']) ? sanitize_text_field($_POST['id']) : '';
    $config_data = awp_get_sperse_active_status_account($integration_id);
    if(!empty($config_data) && is_array($config_data) && count($config_data)>0){
        $api_key = $config_data['apikey'] ? $config_data['apikey'] : "";
        $ins_url = $config_data['base_url'] ? $config_data['base_url'] : "";
    }else{
        $api_key = get_option( 'awp_sperse_api_key' ) ? get_option( 'awp_sperse_api_key' ) : "";
        $ins_url = get_option( 'awp_sperse_url'     ) ? get_option( 'awp_sperse_url'     ) : "";
    }
    if( !$api_key || !$ins_url ) {
        return array('empty');
    }
                $contactGroupId = !empty($_POST['groupId']) ? sanitize_text_field($_POST['groupId']) :'C';

    $url = $ins_url . "/api/services/CRM/Contact/GetAllowedAssignableUsers?ContactGroupId=".$contactGroupId;
    $properties = array('contactGroupId'=>$contactGroupId);
    $args = array('headers' => array('api-key' => $api_key, 'Content-Type' => 'application/json'));
    $data = wp_remote_request( $url, $args );
    if( !is_wp_error( $data ) ) {
        $body  = json_decode( $data["body"] );
        $lists = wp_list_pluck( $body->result, 'name', 'id' );
        wp_send_json_success( $lists );
    } else {
        wp_send_json_error();
    }
}

add_action( 'wp_ajax_awp_save_sperse_mapping', 'awp_save_sperse_mapping', 10, 0 );
function awp_save_sperse_mapping(){
    if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }
    $integration_id = !empty($_POST['id']) ? sanitize_text_field($_POST['id']) : '';
    $returnResponse = array();
    if (isset($_REQUEST["action"]) && sanitize_text_field($_REQUEST["action"]) == "awp_save_sperse_mapping"):
        // $product_id = isset($_POST['wpProductId']) ? $_POST['wpProductId'] : '';
        $productMappingObj = json_decode( html_entity_decode( stripslashes ($_POST['productMappingObj'] ) ) );
        update_option('sperse_woo_product_mapping_'.$integration_id ,$productMappingObj);
        $returnResponse['message'] = 'Your changes have been saved!';
    endif;
    echo json_encode($returnResponse);
    die();
}
add_action( 'wp_ajax_awp_get_mapped_products', 'awp_get_mapped_products', 10, 0 );

function awp_get_mapped_products(){
    if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }
    $integration_id = !empty($_POST['id']) ? sanitize_text_field($_POST['id']) : '';
    $arr1 = get_option('sperse_woo_product_mapping_'.$integration_id);
    if( $arr1 ) {
        wp_send_json_success( $arr1 );
    } else {
        wp_send_json_error();
    }
}

add_action( 'wp_ajax_awp_sperse_mapping_remove', 'awp_sperse_mapping_remove', 10, 0 );
function awp_sperse_mapping_remove(){
    if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }
    $integration_id = !empty($_POST['id']) ? sanitize_text_field($_POST['id']) : '';
    $returnResponse = array();
    if (isset($_REQUEST["action"]) && sanitize_text_field($_REQUEST["action"]) == "awp_sperse_mapping_remove"):
        $product_id = isset($_POST['wpProductId']) ? sanitize_text_field($_POST['wpProductId']) : '';
        if($product_id){
            $stored_mapping = get_post_meta($product_id,'sperse_woo_product_mapping_'.$integration_id,true);
            $match = false;
            if(is_array($stored_mapping) && count($stored_mapping)>0){
                foreach ($stored_mapping as $key => $mapping) {
                    if(($mapping['sperseProductCode']==sanitize_text_field($_POST['sperseProductCode']))){
                        unset($stored_mapping[$key]);
                    }
                }
                if(is_array($stored_mapping) && count($stored_mapping)>0){
                    update_post_meta($product_id,'sperse_woo_product_mapping_'.$integration_id,$stored_mapping);    
                }else{
                    delete_post_meta($product_id,'sperse_woo_product_mapping_'.$integration_id);    
                }
                $returnResponse['message'] = 'Sperse Product Mapping Removed';
            }else{
                $returnResponse['message'] = 'Error in removing product';
            }
        }
    endif;
    echo json_encode($returnResponse);
    die();
}

add_action( 'wp_ajax_awp_get_sperse_wpproducts', 'awp_get_sperse_wpproducts', 10, 0 );

function awp_get_sperse_wpproducts() {
      if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    } 
       $args = array(
        'limit' => -1,
        );
    $data = array();
    $products =  wc_get_products( $args );
    foreach($products as $product){
        $arr = [];
        $arr['id'] = $product->id;
        $arr['name'] = $product->name;
        array_push($data, $arr);
    }
    if( $products ) {
        wp_send_json_success( $data );
    } else {
        wp_send_json_error();
    }
}
add_action( 'wp_ajax_awp_get_sperse_products', 'awp_get_sperse_products', 10, 0 );

function awp_get_sperse_products() {
      if ( ! current_user_can('administrator') ){
        die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
    }
    // Security Check
    if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
    }
    $integration_id = !empty($_POST['id']) ? sanitize_text_field($_POST['id']) : 1;
    $config_data = awp_get_sperse_active_status_account($integration_id);
    if(!empty($config_data) && is_array($config_data) && count($config_data)>0){
        $api_key = $config_data['apikey'] ? $config_data['apikey'] : "";
        $ins_url = $config_data['base_url'] ? $config_data['base_url'] : "";
    }else{
        $api_key = get_option( 'awp_sperse_api_key' ) ? get_option( 'awp_sperse_api_key' ) : "";
        $ins_url = get_option( 'awp_sperse_url'     ) ? get_option( 'awp_sperse_url'     ) : "";
    }
    if( !$api_key || !$ins_url ) {
        return array('empty');
    }
    $url = $ins_url . "/api/services/CRM/Product/GetProducts";
    // $properties = array('purposeId'=>"L", 'contactGroupId'=>$contactGroupId);
    $args = array('headers' => array('api-key' => $api_key,'Content-Type' => 'application/json'));
    $data = wp_remote_request( $url, $args );
    if( !is_wp_error( $data ) ) {
        $body  = json_decode( $data["body"] );
        $lists = wp_list_pluck( $body->result, 'code', 'id' );
        wp_send_json_success( $body );
    } else {
        wp_send_json_error();
    }
}
// ****************************************** 
// *** SAVE THE CONNECTION FIELD MAPPING  *** 
// ****************************************** 
function awp_sperse_save_integration() {   
    $params = array();
    parse_str( awp_sanitize_text_or_array_field( $_POST['formData'] ), $params );
    $trigger_data      = isset( $_POST["triggerData"] ) ? awp_sanitize_text_or_array_field( $_POST["triggerData"] ) : array();
    $action_data       = isset( $_POST["actionData" ] ) ? awp_sanitize_text_or_array_field( $_POST["actionData" ] ) : array();
    $field_data        = isset( $_POST["fieldData"  ] ) ? awp_sanitize_text_or_array_field( $_POST["fieldData"  ] ) : array();
    $integration_title = isset( $trigger_data["integrationTitle"] ) ? $trigger_data["integrationTitle"] : "";    
    $form_provider_id  = isset( $trigger_data["formProviderId"  ] ) ? $trigger_data["formProviderId"  ] : "";
    $form_id           = isset( $trigger_data["formId"          ] ) ? $trigger_data["formId"          ] : "";
    $form_name         = isset( $trigger_data["formName"        ] ) ? $trigger_data["formName"        ] : "";
    $action_provider   = isset( $action_data ["actionProviderId"] ) ? $action_data ["actionProviderId"] : "";
    $task              = isset( $action_data ["task"            ] ) ? $action_data ["task"            ] : "";
    $type              = isset( $params      ["type"            ] ) ? $params      ["type"            ] : "";
    $all_data = array(
        'trigger_data' => $trigger_data,
        'action_data'  => $action_data,
        'field_data'   => $field_data,
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
    }
    if ( $type == 'update_integration' ) {
        $id = isset($params['edit_id']) ?  ( trim( sanitize_text_field($params['edit_id']) ) ):'';
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

// ****************************************** 
// *** GET, MAP & SEND DATA TO SPERSE API *** 
// ****************************************** 
function awp_get_sperse_active_status_account($id=''){
    global $wpdb;
    $data = array();
    $add_user_table = $wpdb->prefix.'awp_platform_settings';    
    if(!empty($id)){
        $result = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$add_user_table}  WHERE id = %d",$id), ARRAY_A );            
        $data['apikey'] = $result['api_key'];
        $data['base_url'] = $result['url'];
        $data['sync_contacts'] = $result['sync_contacts'];
        $data['account_id'] = $result['id'];
    }else{
        $results = $wpdb->get_results("SELECT * FROM {$add_user_table} where platform_name='sperse'", OBJECT );
        foreach ($results as $key => $value) {
            if( !empty($value->active_status) && ($value->active_status=='true')){
                $data['apikey'] = $value->api_key;
                $data['base_url'] = $value->url;
                $data['sync_contacts'] = $value->sync_contacts;
                $data['account_id'] = $value->id;
                break;
            }   
        }
    }
    return $data;
}

function awp_sperse_send_data( $record, $posted_data ) {
    $result_data = json_decode($record['data']);
    $sperse_accountId = $result_data->field_data->sperse_accountId;
    $config_data = awp_get_sperse_active_status_account($sperse_accountId);
    if(!empty($config_data) && is_array($config_data) && count($config_data)>0){
        $api_key = $config_data['apikey'] ? $config_data['apikey'] : "";
        $ins_url = $config_data['base_url'] ? $config_data['base_url'] : "";
        $integration_id = $config_data['account_id'] ? $config_data['account_id'] : "";
    }else{
        $api_key = get_option( 'awp_sperse_api_key' ) ? get_option( 'awp_sperse_api_key' ) : "";
        $ins_url = get_option( 'awp_sperse_url'     ) ? get_option( 'awp_sperse_url'     ) : "";
    }
    if( !$api_key || !$ins_url ) {
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

    //========================================================================================================================
     if( $task == "createLead" ) {
        //========================================================================================================================
        $properties =  createOrUpdateContactRequest($data, $posted_data, $record, $ins_url, $api_key, false);
        $start_time = current_time('mysql',true);
        //-------------------------------------------------------------------------------------------------------------------------
        $url = $ins_url . "/api/services/CRM/Contact/CreateOrUpdateContact";
        $args = array('headers' => array('api-key' => $api_key, 'Content-Type' => 'application/json'), 'body' => json_encode($properties));
        $return = wp_remote_post( $url, $args );
        $log_return = $return;
        $log_respone =   (!is_wp_error($return) && !empty($log_return["body"])) ? json_decode($log_return["body"]) : '';
        if(!empty($log_respone->result->userId) && !empty($data['roleID']) ){
            $roles = $data['roleID'];
            $userId = $log_respone->result->userId;
            $roleURL = $ins_url."/api/services/Platform/User/AddToRole";
            if(count($data['roleID']) > 1){
                foreach ($data['roleID'] as $key => $value) {
                        $role_properties = array(
                        'id'=>$userId,
                        'roleName'=>$value
                    );
                    $roleargs = array('headers' => array('api-key' => $api_key, 'Content-Type' => 'application/json'), 'body' => json_encode($role_properties));
                    $rolereturn = wp_remote_post( $roleURL, $roleargs );
                    $start_time = current_time('mysql',true);
                    awp_add_to_log( $rolereturn, $roleURL, $role_properties, $record,$start_time );
                }
            } else {
                $role_properties = array(
                'id'=>$userId,
                    'roleName'=>!empty($data['roleID']['0']) ? $data['roleID']['0'] : 'CRM User'
                );
                $roleargs = array('headers' => array('api-key' => $api_key, 'Content-Type' => 'application/json'), 'body' => json_encode($role_properties));
                $rolereturn = wp_remote_post( $roleURL, $roleargs );
                $start_time = current_time('mysql',true);
                awp_add_to_log( $rolereturn, $roleURL, $role_properties, $record,$start_time );
            }
        }
        if(!empty($log_respone->result->userKey)){
            $userKey = $log_respone->result->userKey;
            $first =substr($userKey,0,6);
            $last =substr($userKey,-6);
            $log_respone->result->userKey =$first.'-XXXXXX-XXXXXX-XXXXXX-'.$last;   
        }
        if(!empty($log_respone->result->autoLoginLink)){
            $autoLoginLink = $log_respone->result->autoLoginLink;
            $secured_string = parse_url($autoLoginLink,PHP_URL_QUERY);
            $log_key = '';
            if(!empty($secured_string)){
                $secured_string_components =    explode("=", $secured_string);
                if(!empty( $secured_string_components)){
                    $userKey = end($secured_string_components);
                    if(!empty($userKey)){
                        $first =substr($userKey,0,6);
                        $last =substr($userKey,-6);
                        $log_key =$first.'-XXXXXX-XXXXXX-XXXXXX-'.$last; 
                    } 
                }
                $log_secured_key = "secureId=".$log_key;
               $autoLoginLink =  str_replace($secured_string, $log_secured_key, $autoLoginLink);
            }
            $log_respone->result->autoLoginLink =  $autoLoginLink;
        }
        if(!empty($log_respone)){
            $log_return['body'] = json_encode($log_respone); 
        }
        awp_add_to_log( $log_return, $url, $properties, $record,$start_time );
    }

    //========================================================================================================================
    if( $task == "createUser" ) {
        //========================================================================================================================        
        // woocoommerce logic
        if(isset($posted_data['wcOrderPlaced'])){    //if order comes from woocommerce
            $order       = wc_get_order( $posted_data['id'] );
            $wpUserId    = $order->get_user_id();

            //addBankCardPaymentRequestTEST($posted_data);        
            if(!empty($posted_data['orderNewStatus'])){
                // if the order status is processing or completed
                if($posted_data['orderNewStatus'] == 'processing' || $posted_data['orderNewStatus'] == 'completed'){
                    if(get_user_meta($wpUserId, '_contactId')){
                        $contactId = get_user_meta($wpUserId, '_contactId')[0];
                        $leadId    = get_user_meta($wpUserId, '_leadId')[0];
                    } else {
                        $properties =  createOrUpdateContactRequest($data, $posted_data, $record, $ins_url, $api_key);
                        $url = $ins_url . "/api/services/CRM/Contact/CreateOrUpdateContact";
                        $args = array(
                            'headers' => array(
                                'api-key'      => $api_key,
                                'Content-Type' => 'application/json'
                            ),
                            'body' => json_encode($properties)
                        );
                        $start_time = current_time('mysql',true);
                        $return = wp_remote_post( $url, $args );
                        $respone = json_decode($return["body"]);
                        $status = $respone->success;
                        $log_return = $return;
                        $log_respone = json_decode($log_return["body"]);
                        if(!empty($log_respone->result->userId) && !empty($data['roleID']) ){
                            $roles = $data['roleID'];
                            $userId = $log_respone->result->userId;
                            $roleURL = $ins_url."/api/services/Platform/User/AddToRole";
                            if(count($data['roleID']) > 1){
                                foreach ($data['roleID'] as $key => $value) {
                                        $role_properties = array(
                                        'id'=>$userId,
                                        'roleName'=>$value
                                    );
                                    $roleargs = array('headers' => array('api-key' => $api_key, 'Content-Type' => 'application/json'), 'body' => json_encode($role_properties));
                                    $rolereturn = wp_remote_post( $roleURL, $roleargs );
                                    $start_time = current_time('mysql',true);
                                    awp_add_to_log( $rolereturn, $roleURL, $role_properties, $record,$start_time );
                                }
                            } else {
                                $role_properties = array(
                                'id'=>$userId,
                                    'roleName'=>!empty($data['roleID']['0']) ? $data['roleID']['0'] : 'CRM User'
                                );
                                $roleargs = array('headers' => array('api-key' => $api_key, 'Content-Type' => 'application/json'), 'body' => json_encode($role_properties));
                                $rolereturn = wp_remote_post( $roleURL, $roleargs );
                                $start_time = current_time('mysql',true);
                                awp_add_to_log( $rolereturn, $roleURL, $role_properties, $record,$start_time );
                            }
                        }
                        if(!empty($log_respone->result->userKey)){
                            $userKey = $log_respone->result->userKey;
                            $first =substr($userKey,0,6);
                            $last =substr($userKey,-6);
                            $log_respone->result->userKey =$first.'-XXXXXX-XXXXXX-XXXXXX-'.$last;   
                        }
                        if(!empty($log_respone->result->autoLoginLink)){
                            $autoLoginLink = $log_respone->result->autoLoginLink;
                            $secured_string = parse_url($autoLoginLink,PHP_URL_QUERY);
                            $log_key = '';
                            if(!empty($secured_string)){
                                $secured_string_components =    explode("=", $secured_string);
                                if(!empty( $secured_string_components)){
                                    $userKey = end($secured_string_components);
                                    if(!empty($userKey)){
                                        $first =substr($userKey,0,6);
                                        $last =substr($userKey,-6);
                                        $log_key =$first.'-XXXXXX-XXXXXX-XXXXXX-'.$last; 
                                    } 
                                }
                                $log_secured_key = "secureId=".$log_key;
                               $autoLoginLink =  str_replace($secured_string, $log_secured_key, $autoLoginLink);
                            }
                            $log_respone->result->autoLoginLink =  $autoLoginLink;
                        }  
                        $log_return['body'] = json_encode($log_respone);
                        if(!empty($log_respone->result->contactId)){
                            $contactId = $log_respone->result->contactId;
                            $leadId    = $log_respone->result->leadId;
                        }
                        $order            = wc_get_order($posted_data['id']);
                        $order->update_meta_data('createOrUpdateContactRequest', json_encode($properties));
                        $order->update_meta_data('createOrUpdateContactResponse',$log_return['body'] );
                        awp_send_email_to_sperse($contactId,$posted_data['id'],$api_key,$ins_url);
                        $order->update_meta_data('_userKey', $respone->result->userKey);
                        $order->update_meta_data('_contactId', $respone->result->contactId);
                        $order->update_meta_data('_leadId', $respone->result->leadId);
                        $order->update_meta_data('_userId', $respone->result->userId);
                        $order->save();
                        awp_add_to_log( $log_return, $url, $properties, $record,$start_time );
                }
                if(isset($contactId) && !empty($contactId)){
                    $order            = wc_get_order($posted_data['id']);
                    if(!get_user_meta($wpUserId, '_contactId')){
                        update_user_meta($wpUserId, '_contactId', $respone->result->contactId);
                        update_user_meta($wpUserId, '_userKey', $respone->result->userKey);
                        update_user_meta($wpUserId, '_leadId', $respone->result->leadId);
                        update_user_meta($wpUserId, '_userId', $respone->result->userId);
                    }
                    $properties = createInvoiceRequest($integration_id, $contactId, $leadId, $posted_data);
                    if($properties != null){
                        if( ! get_post_meta( $posted_data['id'], '_sent_order_to_crm', true ) ) {
                            $url = $ins_url . "/api/services/CRM/Invoice/Create";
                            $args = array(
                                'headers' => array(
                                    'api-key'      => $api_key,
                                    'Content-Type' => 'application/json'
                                ),
                                'body' => json_encode($properties)
                            );
                            $start_time = current_time('mysql',true);
                            $return = wp_remote_post( $url, $args );
                            $log_return = $return;
                            $log_respone        = (!is_wp_error($return) && !empty($log_return["body"])) ? json_decode($log_return["body"]) : ''; 
                            $order->update_meta_data('createInvoiceRequest', json_encode($properties));
                            $order->update_meta_data('createInvoiceResponse',json_encode($log_respone) );
                            $order->save();
                            awp_add_to_log( $log_return, $url, $properties, $record,$start_time );
                            if (!is_wp_error($return)){
                                $invoiceId       = $log_respone->result;
                                $properties = addBankCardPaymentRequest($invoiceId, $posted_data);
                                $url = $ins_url . "/api/services/CRM/Invoice/AddBankCardPayment";
                                $args = array(
                                    'headers' => array(
                                        'api-key'      => $api_key,
                                        'Content-Type' => 'application/json'
                                    ),
                                    'body' => json_encode($properties)
                                );
                                $start_time = current_time('mysql',true);
                                $return = wp_remote_post( $url, $args );
                                $log_return = $return;
                                $log_respone = json_decode($log_return["body"]);
                                $log_return['body'] = json_encode($log_respone);
                                $response           = json_encode($log_respone);
                                awp_add_to_log( $log_return, $url, $properties, $record,$start_time );
                                $order->update_meta_data('addBankCardPaymentRequest', json_encode($properties));
                                $order->update_meta_data('addBankCardPaymentResponse',$log_return['body'] );
                                $order->save();
                                if((wp_remote_retrieve_response_code( $return ) == 200 )){
                                    $order->update_meta_data( '_sent_order_to_crm', true );
                                    $order->save();
                                    if(!empty($data['redirectUrl'])){
                                    $redirectUrl = $data['redirectUrl'];
                                    ?>
                                    <script type="text/javascript">
                                        window.location.replace("<?php echo esc_js($redirectUrl); ?>)";
                                    </script>
                                    <?php 
                                    }
                                }   
                            }                            
                        }
                    } //end check
                }
            }
                // if order status is pending, failed or on-hold
                if($posted_data['orderNewStatus'] == 'pending' || $posted_data['orderNewStatus'] == 'failed' || $posted_data['orderNewStatus'] == 'on-hold'){
                    if(get_user_meta($wpUserId, '_contactId')){
                        $contactId = get_user_meta($wpUserId, '_contactId')[0];
                        $leadId    = get_user_meta($wpUserId, '_leadId')[0];
                    } else {
                        $properties =  createOrUpdateContactRequest($data, $posted_data, $record, $ins_url, $api_key);
                        $url = $ins_url . "/api/services/CRM/Contact/CreateOrUpdateContact";
                        $args = array(
                            'headers' => array(
                                'api-key'      => $api_key,
                                'Content-Type' => 'application/json'
                            ),
                            'body' => json_encode($properties)
                        );
                        $start_time = current_time('mysql',true);
                        $return = wp_remote_post( $url, $args );
                        $respone = json_decode($return["body"]);
                        $status = $respone->success;
                        $log_return = $return;
                        $log_respone = json_decode($log_return["body"]);
                        if(!empty($log_respone->result->userId) && !empty($data['roleID']) ){
                            $roles = $data['roleID'];
                            $userId = $log_respone->result->userId;
                            $roleURL = $ins_url."/api/services/Platform/User/AddToRole";
                            if(count($data['roleID']) > 1){
                                foreach ($data['roleID'] as $key => $value) {
                                        $role_properties = array(
                                        'id'=>$userId,
                                        'roleName'=>$value
                                    );
                                    $roleargs = array('headers' => array('api-key' => $api_key, 'Content-Type' => 'application/json'), 'body' => json_encode($role_properties));
                                    $rolereturn = wp_remote_post( $roleURL, $roleargs );
                                    $start_time = current_time('mysql',true);
                                    awp_add_to_log( $rolereturn, $roleURL, $role_properties, $record,$start_time );
                                }
                            } else {
                                $role_properties = array(
                                'id'=>$userId,
                                    'roleName'=>!empty($data['roleID']['0']) ? $data['roleID']['0'] : 'CRM User'
                                );
                                $roleargs = array('headers' => array('api-key' => $api_key, 'Content-Type' => 'application/json'), 'body' => json_encode($role_properties));
                                $rolereturn = wp_remote_post( $roleURL, $roleargs );
                                $start_time = current_time('mysql',true);
                                awp_add_to_log( $rolereturn, $roleURL, $role_properties, $record,$start_time );
                            }
                        }
                        if(!empty($log_respone->result->userKey)){
                            $userKey = $log_respone->result->userKey;
                            $first =substr($userKey,0,6);
                            $last =substr($userKey,-6);
                            $log_respone->result->userKey =$first.'-XXXXXX-XXXXXX-XXXXXX-'.$last;   
                        }
                        if(!empty($log_respone->result->autoLoginLink)){
                            $autoLoginLink = $log_respone->result->autoLoginLink;
                            $secured_string = parse_url($autoLoginLink,PHP_URL_QUERY);
                            $log_key = '';
                            if(!empty($secured_string)){
                                $secured_string_components =    explode("=", $secured_string);
                                if(!empty( $secured_string_components)){
                                    $userKey = end($secured_string_components);
                                    if(!empty($userKey)){
                                        $first =substr($userKey,0,6);
                                        $last =substr($userKey,-6);
                                        $log_key =$first.'-XXXXXX-XXXXXX-XXXXXX-'.$last; 
                                    } 
                                }
                                $log_secured_key = "secureId=".$log_key;
                               $autoLoginLink =  str_replace($secured_string, $log_secured_key, $autoLoginLink);
                            }
                            $log_respone->result->autoLoginLink =  $autoLoginLink;
                        }  
                        $log_return['body'] = json_encode($log_respone);

                        if(!empty($log_respone->result->contactId)){
                            $contactId = $log_respone->result->contactId;
                            $leadId    = $log_respone->result->leadId;
                        }
                        $order         = wc_get_order($posted_data['id']);
                        $order->update_meta_data('createOrUpdateContactRequest', json_encode($properties));
                        $order->update_meta_data('createOrUpdateContactResponse',$log_return['body'] );
                        awp_send_email_to_sperse($contactId,$posted_data['id'],$api_key,$ins_url);

                        $order->update_meta_data('_userKey', $respone->result->userKey);
                        $order->update_meta_data('_contactId', $respone->result->contactId);
                        $order->update_meta_data('_leadId', $respone->result->leadId);
                        $order->update_meta_data('_userId', $respone->result->userId);
                        $order->save();
                        awp_add_to_log( $log_return, $url, $properties, $record,$start_time );
                    }
                    if(isset($contactId) && !empty($contactId)){
                    $order            = wc_get_order($posted_data['id']);
                    if(!get_user_meta($wpUserId, '_contactId')){
                        update_user_meta($wpUserId, '_contactId', $respone->result->contactId);
                        update_user_meta($wpUserId, '_userKey', $respone->result->userKey);
                        update_user_meta($wpUserId, '_leadId', $respone->result->leadId);
                        update_user_meta($wpUserId, '_userId', $respone->result->userId);
                    }
                    $properties = createInvoiceRequest($integration_id, $contactId, $leadId, $posted_data);
                    if($properties != null){
                        $url = $ins_url . "/api/services/CRM/Invoice/Create";
                        $args = array(
                            'headers' => array(
                                'api-key'      => $api_key,
                                'Content-Type' => 'application/json'
                            ),
                            'body' => json_encode($properties)
                        );
                        $start_time = current_time('mysql',true);
                        $return = wp_remote_post( $url, $args );
                        $log_return = $return;
                        $log_respone        = json_decode($log_return["body"]);
                        $log_return['body'] = json_encode($log_respone);
                        $order->update_meta_data('createInvoiceRequest', json_encode($properties));
                        $order->update_meta_data('createInvoiceResponse',$log_return['body'] );
                        $order->save();
                        awp_add_to_log( $log_return, $url, $properties, $record,$start_time );    
                    }
                }
                }
                //if order status is refunded
                if($posted_data['orderNewStatus'] == 'partiallyOrFullyRefunded'){
                    $createInvoiceResponse = get_post_meta( $posted_data['id'], 'createInvoiceResponse' ,true );
                      if($createInvoiceResponse) {
                          $createInvoiceResponse = json_decode($createInvoiceResponse);
                          $invoiceId = $createInvoiceResponse->result;   
                      }  else {
                          $invoiceId = null;
                      }
                      // check if transaction is refunded or voided
                      $transactionIdO       = get_post_meta( $posted_data['id'], '_transaction_id' ,true );
                      $transactionIdAuth    = get_post_meta( $posted_data['id'], '_wc_authorize_net_cim_credit_card_refund_trans_id' ,true );
                      $transactionIdStripe  = get_post_meta( $posted_data['id'], '_stripe_refund_id' ,true );
                      $transactionIdAuthV   = get_post_meta( $posted_data['id'], '_wc_authorize_net_cim_credit_card_void_trans_id' ,true );
                      $transactionIdStripeV = get_post_meta( $posted_data['id'], '_stripe_void_id' ,true );
                      $transactionSt = false;
                      if($transactionIdAuth){
                          $transactionIdR = $transactionIdAuth;
                          $transactionSt = true;
                      }else if($transactionIdStripe){
                          $transactionIdR = $transactionIdStripe;
                          $transactionSt = true;
                      }else if($transactionIdAuthV){
                          $transactionIdV = $transactionIdAuthV;
                          $transactionSt = true;
                      }else if($transactionIdStripeV){
                          $transactionIdV = $transactionIdStripeV;
                          $transactionSt = true;
                      }
                      // check if transaction is refunded or voided
                      if($transactionSt){
                         if($transactionIdR){
                            $properties = addBankCardPaymentRequestRefunded($invoiceId, $posted_data);
                            $url = $ins_url . "/api/services/CRM/Invoice/AddBankCardPayment";
                            $args = array(
                                'headers' => array(
                                    'api-key'      => $api_key,
                                    'Content-Type' => 'application/json'
                                ),
                                'body' => json_encode($properties)
                            );
                            $start_time = current_time('mysql',true);
                            $return = wp_remote_post( $url, $args );
                            $log_return = $return;
                            $log_respone = json_decode($log_return["body"]);
                            $log_return['body'] = json_encode($log_respone);
                            $response           = json_encode($log_respone);
                            awp_add_to_log( $log_return, $url, $properties, $record,$start_time );
                            $order->update_meta_data('addBankCardPaymentRequestRef', json_encode($properties));
                            $order->update_meta_data('addBankCardPaymentResponseRef',$log_return['body'] );
                            $order->save();      
                         }else if($transactionIdV){
                            $properties = voidBankCardPaymentRequest($invoiceId, $posted_data);
                            $url = $ins_url . "/api/services/CRM/Invoice/VoidBankCardPayment";
                            $args = array(
                                'headers' => array(
                                    'api-key'      => $api_key,
                                    'Content-Type' => 'application/json'
                                ),
                                'body' => json_encode($properties)
                            );
                            $start_time = current_time('mysql',true);
                            $return = wp_remote_post( $url, $args );
                            $log_return = $return;
                            $log_respone = json_decode($log_return["body"]);
                            $log_return['body'] = json_encode($log_respone);
                            $response           = json_encode($log_respone);
                            awp_add_to_log( $log_return, $url, $properties, $record,$start_time );
                            $order->update_meta_data('addBankCardPaymentRequestVoid', json_encode($properties));
                            $order->update_meta_data('addBankCardPaymentResponseVoid',$log_return['body'] );
                            $order->save();
                         }
                      }
                }
                // if the subscription is cancelled
                if($posted_data['orderNewStatus'] == 'cancelled'){
                    $createOrUpdateContactRequest = get_post_meta( $posted_data['id'], 'createOrUpdateContactResponse' ,true );
                      $contactId = (get_user_meta($wpUserId, '_contactId')[0]) ? get_user_meta($wpUserId, '_contactId')[0] : null;
                      $properties = cancelSubscriptionRequest($integration_id, $contactId, $posted_data);
                      $url = $ins_url . "/api/services/CRM/OrderSubscription/CancelAll";
                        $args = array(
                            'headers' => array(
                                'api-key'      => $api_key,
                                'Content-Type' => 'application/json'
                            ),
                            'body' => json_encode($properties)
                        );
                        $start_time = current_time('mysql',true);
                        $return = wp_remote_post( $url, $args );
                        $log_return = $return;
                        $log_respone = json_decode($log_return["body"]);
                        $log_return['body'] = json_encode($log_respone);
                        $response           = json_encode($log_respone);
                        awp_add_to_log( $log_return, $url, $properties, $record,$start_time );
                        $order->update_meta_data('orderSubscriptionCancelRequest', json_encode($properties));
                        $order->update_meta_data('orderSubscriptionCancelResponse',$log_return['body'] );
                        $order->save();
                }
            } else { //if subscription status is changed
                if($posted_data['subscriptionStatus'] == 'active'){
                }
            }
        } else {
            $properties =  createOrUpdateContactRequest($data, $posted_data, $record, $ins_url, $api_key);
            $url = $ins_url . "/api/services/CRM/Contact/CreateOrUpdateContact";
            $args = array(
                'headers' => array(
                    'api-key'      => $api_key,
                    'Content-Type' => 'application/json'
                ),
                'body' => json_encode($properties)
            );
            $start_time = current_time('mysql',true);
            $return = wp_remote_post( $url, $args );
            $respone = json_decode($return["body"]);
            $status = $respone->success;
        $log_return = $return;
        $log_respone = json_decode($log_return["body"]);
        if(!empty($log_respone->result->userId) && !empty($data['roleID']) ){
            $roles = $data['roleID'];
            $userId = $log_respone->result->userId;
            $roleURL = $ins_url."/api/services/Platform/User/AddToRole";
            if(count($data['roleID']) > 1){
                foreach ($data['roleID'] as $key => $value) {
                        $role_properties = array(
                        'id'=>$userId,
                        'roleName'=>$value
                    );
                    $roleargs = array('headers' => array('api-key' => $api_key, 'Content-Type' => 'application/json'), 'body' => json_encode($role_properties));
                    $rolereturn = wp_remote_post( $roleURL, $roleargs );
                    $start_time = current_time('mysql',true);
                    awp_add_to_log( $rolereturn, $roleURL, $role_properties, $record,$start_time );
                }
            } else {
                $role_properties = array(
                'id'=>$userId,
                    'roleName'=>!empty($data['roleID']['0']) ? $data['roleID']['0'] : 'CRM User'
                );
                $roleargs = array('headers' => array('api-key' => $api_key, 'Content-Type' => 'application/json'), 'body' => json_encode($role_properties));
                $rolereturn = wp_remote_post( $roleURL, $roleargs );
                $start_time = current_time('mysql',true);
                awp_add_to_log( $rolereturn, $roleURL, $role_properties, $record,$start_time );
            }
        }
        if(!empty($log_respone->result->userKey)){
            $userKey = $log_respone->result->userKey;
            $first =substr($userKey,0,6);
            $last =substr($userKey,-6);
            $log_respone->result->userKey =$first.'-XXXXXX-XXXXXX-XXXXXX-'.$last;   
        }
        if(!empty($log_respone->result->autoLoginLink)){
            $autoLoginLink = $log_respone->result->autoLoginLink;
            $secured_string = parse_url($autoLoginLink,PHP_URL_QUERY);
            $log_key = '';
            if(!empty($secured_string)){
                $secured_string_components =    explode("=", $secured_string);
                if(!empty( $secured_string_components)){
                    $userKey = end($secured_string_components);
                    if(!empty($userKey)){
                        $first =substr($userKey,0,6);
                        $last =substr($userKey,-6);
                        $log_key =$first.'-XXXXXX-XXXXXX-XXXXXX-'.$last; 
                    } 
                }
                $log_secured_key = "secureId=".$log_key;
               $autoLoginLink =  str_replace($secured_string, $log_secured_key, $autoLoginLink);
            }
            $log_respone->result->autoLoginLink =  $autoLoginLink;
        }  
        $log_return['body'] = json_encode($log_respone);
        awp_add_to_log( $log_return, $url, $properties, $record,$start_time );
        }
        // woocoommerce logic
        if(!empty($data['redirectUrl'])){
            $redirectUrl = $data['redirectUrl'];
        ?>
        <script type="text/javascript">
            window.location.replace("<?php echo esc_js($redirectUrl); ?>)";
        </script>
        <?php 
        }
    }

//========================================================================================================================    
    if( $task == "loginUser" ) {
//========================================================================================================================        
        $system_type   = $data["systemType"];
        $email         = empty( $data["email"      ] ) ? "" : awp_get_parsed_values( $data["email"      ], $posted_data );  $email    = trim($email   , " ");
        $password      = empty( $data["password"   ] ) ? "" : awp_get_parsed_values( $data["password"   ], $posted_data );  $password = trim($password, " ");
        $properties = array(
            'userNameOrEmailAddress'   => $email,
            'password'                 => $password,
            'rememberClient'           => true,
            "autoDetectTenancy"        => true
        );
        $url = $ins_url . "/api/TokenAuth/Authenticate";
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json'
            ),
            'body' => json_encode($properties)
        );
        $start_time = current_time('mysql',true);
        $return = wp_remote_post( $url, $args );
        $log_properties = $properties;
        $log_properties['password'] ='****************';
        $log_return = $return;
        $log_respone = json_decode($log_return["body"]);
        if(!empty($log_respone->result->accessToken)){
            $accesstoken = $log_respone->result->accessToken;
            $first =substr($accesstoken,0,6);
            $last =substr($accesstoken,-6);
           $log_respone->result->accessToken =$first.'-XXXXXX-XXXXXX-XXXXXX-'.$last;   
        }
        if(!empty($log_respone->result->encryptedAccessToken)){
            $enaccesstoken = $log_respone->result->encryptedAccessToken;
            $first =substr($enaccesstoken,0,6);
            $last =substr($enaccesstoken,-6);
           $log_respone->result->encryptedAccessToken =$first.'-XXXXXX-XXXXXX-XXXXXX-'.$last;   
        }
        $log_return['body'] = json_encode($log_respone);
        awp_add_to_log( $log_return, $url, $log_properties, $record,$start_time );
        if(!is_wp_error($return)) {
             $response = json_decode($return["body"]);
             $status = $response->success;
             if($status) {
                $accessToken = $response->result->accessToken;
                $userId              = $response->result->userId;
                $detectedTenancies   = $response->result->detectedTenancies[0]->id;
                $properties = array('userId' => $userId, 'tenantId' => $detectedTenancies);
                $url = $ins_url . "/api/services/Platform/Account/Impersonate";
                $args = array(
                                'headers' => array(
                                    'api-key'      => $api_key,
                                    'Content-Type' => 'application/json'
                                ),
                                'body' => json_encode($properties)
                            );
                $start_time = current_time('mysql',true);
                $return = wp_remote_post( $url, $args );
                $log_return = $return;
                $log_respone = json_decode($log_return["body"]);
                if(!empty($log_respone->result->impersonationToken)){
                    $impersonationToken = $log_respone->result->impersonationToken;
                    $first =substr($impersonationToken,0,6);
                    $last =substr($impersonationToken,-6);
                   $log_respone->result->userKey =$first.'-XXXXXX-XXXXXX-XXXXXX-'.$last;   
                }
                $log_return['body'] = json_encode($log_respone);
                awp_add_to_log( $log_return, $url, $properties, $record,$start_time );
                $respone = json_decode($return["body"]);
                $status = $respone->success;
                if($status) {
                    $redUrl = $ins_url.'/?secureId='.$impersonationToken;
                                                        ?>
                        <script type="text/javascript">
                            window.location.replace("<?php echo esc_js($redUrl); ?>)";
                        </script>
                <?php 
                } // die();
                } else {
                     ?>
                        <script type="text/javascript">
                            alert('Invalid username or password. Please try again.');
                            window.location.replace(window.location.href);
                        </script>
                <?php 
                }
            }
            else {
                 ?>
                        <script type="text/javascript">
                            alert('Invalid username or password. Please try again!');
                            window.location.replace(window.location.href);
                        </script>
                <?php 
            }
        }
    return;
}


function createOrUpdateContactRequest($data, $posted_data, $record, $ins_url, $api_key, $inviteUser = true) {
    // get tracking information
    $referrerUrl = $entryUrl = "";
   if(isset($_COOKIE['_referringURL'])) {
       $referrerUrl = sanitize_url($_COOKIE['_referringURL']);
    }else if(!empty($posted_data['referrerUrl'])){
        $referrerUrl = sanitize_url($posted_data['referrerUrl']);
    }
    if(isset($_COOKIE['_entryUrl'])) {
        $entryUrl = sanitize_url($_COOKIE['_entryUrl']);
    }else if(!empty($posted_data['entryUrl'])){
        $entryUrl = sanitize_url($posted_data['entryUrl']);
    }
    $refAffiliateCode="";
    if(isset($_COOKIE['refAffiliateCode'])) {
        $refAffiliateCode = sanitize_text_field($_COOKIE['refAffiliateCode']);
    }else if(!empty($posted_data['refAffiliateCode'])){
        $refAffiliateCode = sanitize_text_field($posted_data['refAffiliateCode']);
    }
    if(!empty($data['userAgent'])){
         $userAgent = sanitize_text_field($data['userAgent']);
    }else{
        $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']):'';
    }
    if(!empty($data['clientIP'])){
            $ip = isset($data['clientIP']) ? sanitize_text_field($data['clientIP']) : '';
    }else{
        $ip = getUserIpAddrForSperse();
    }
    $inviteSelected      = empty( $data["inviteSelected"    ] ) ? null : awp_get_parsed_values( $data["inviteSelected"     ], $posted_data );
    $inviteSelected = (!empty($inviteSelected) || $inviteSelected!='')?$inviteSelected:$inviteUser;
    $fullName       = empty( $data["fullName"     ] ) ? null : awp_get_parsed_values( $data["fullName"      ], $posted_data );
    $firstName      = empty( $data["firstName"    ] ) ? null : awp_get_parsed_values( $data["firstName"     ], $posted_data );
    $lastName       = empty( $data["lastName"     ] ) ? null : awp_get_parsed_values( $data["lastName"      ], $posted_data );
    $companyName    = empty( $data["companyName"  ] ) ? null : awp_get_parsed_values( $data["companyName"   ], $posted_data );
    $jobTitle       = empty( $data["jobTitle"     ] ) ? null : awp_get_parsed_values( $data["jobTitle"      ], $posted_data );
    $industry       = empty( $data["industry"     ] ) ? null : awp_get_parsed_values( $data["industry"      ], $posted_data );
    $note           = empty( $data["note"         ] ) ? null : awp_get_parsed_values( $data["note"          ], $posted_data );
    $bankCode       = empty( $data["bankCode"     ] ) ? null : awp_get_parsed_values( $data["bankCode"      ], $posted_data );
    $personalCode   = empty( $data["personalCode" ] ) ? null : awp_get_parsed_values( $data["personalCode"  ], $posted_data );  
    $questoin_answers = array();
    $answerQ1       = empty( $data["answerQ1"] ) ? "" : awp_get_parsed_values( $data["answerQ1"], $posted_data );
    $answerQ2       = empty( $data["answerQ2"] ) ? "" : awp_get_parsed_values( $data["answerQ2"], $posted_data );  
    $answerQ3       = empty( $data["answerQ3"] ) ? "" : awp_get_parsed_values( $data["answerQ3"], $posted_data ); 
    if(!empty($answerQ1) && count($answerQ1)>0){$questoin_answers[]=$answerQ1;}
    if(!empty($answerQ2) && count($answerQ2)>0){$questoin_answers[]=$answerQ2;}
    if(!empty($answerQ3) && count($answerQ3)>0){$questoin_answers[]=$answerQ3;}
    if(isset($_COOKIE['affwp_ref'])) {
      global $wpdb;
      $aff_id = $_COOKIE['affwp_ref']; 
      if($aff_id == 0) {$aff_id = 9; }
      $results = $wpdb->get_results($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}affiliate_wp_affiliates WHERE affiliate_id = %d",$aff_id), OBJECT );
      foreach ($results as $key => $value) {
        $user_id = $value->user_id;
      }
      $user_info = get_userdata($user_id);
      $accessCode = $user_info->user_login;
    } else {
        $accessCode = "";        
    }
    // set addresses stat here
    $addresses = $addressArray = [];
    if(!empty($data["streetAddress"])) { 
        $streetAddress = awp_get_parsed_values( $data["streetAddress" ], $posted_data );
        $addressArray['streetAddress'] = $streetAddress;
    }
    if(!empty($data["city"         ])) {
        $city = awp_get_parsed_values( $data["city"          ], $posted_data );
        $addressArray['city'] = $city;
    } 
    if(!empty($data["stateName"    ])) {
        $stateName = awp_get_parsed_values( $data["stateName"    ], $posted_data );
        $addressArray['stateName'] = $stateName;
    }
    if(!empty($data["stateId"      ])) {
        $stateId = awp_get_parsed_values( $data["stateId"    ], $posted_data );
        $addressArray['stateId'] = $stateId;
    }
    if(!empty($data["countryId"    ])) {
        $countryId = awp_get_parsed_values( $data["countryId"    ], $posted_data );
        if($countryId == "US" || $countryId == "CA") {
            if(!empty($data["stateId"      ])) {
                $stateId = awp_get_parsed_values( $data["stateId"    ], $posted_data );
                $addressArray['stateId'] = $stateId;
            }
        } else {
            if(!empty($data["stateName"    ])) {
                $stateName = awp_get_parsed_values( $data["stateName"    ], $posted_data );
                $addressArray['stateName'] = $stateName;
            }
        }
        $addressArray['countryId'] = $countryId;
    }
    if(!empty($data["zipCode"      ])) {
        $zipCode = awp_get_parsed_values( $data["zipCode"    ], $posted_data );
        $addressArray['zip'] = $zipCode;
    }
    if(!empty($addressArray)) {
        array_push($addresses, $addressArray);
    } else {
        $addresses = null;
    }
    $email       = empty( $data["email"     ] ) ? "" : awp_get_parsed_values( $data["email"     ], $posted_data );
        if($email != "") {
            $emailAddress = array(array(
                "emailAddress"=>$email,
                "usageTypeId"=>"P"
            ));
        } else {
            $emailAddress = null;
        }
    // set email Addresses end here
    // set phone numbers start here
        $phoneNumber  = empty( $data["phoneNumber"] ) ? "" : awp_get_parsed_values( $data["phoneNumber"], $posted_data );
        if($phoneNumber != "") {
            $phoneNumbers = array(array(
                                "phoneNumber"=>$phoneNumber,
                                "usageTypeId"=>"M"
                            ));
        } else {
            $phoneNumbers = null;
        }
    // set phone numbers end here
    // set link/web url starts here
    $webURL  = empty( $data["webURL" ] ) ? "" : awp_get_parsed_values( $data["webURL"        ], $posted_data );
        if(!empty($webURL)) {
            $links = array(array(
                 "url"                 => $webURL
                  ));
        } else {
            $links = null;
        }
    // set link/web url ends here
    
   if(!empty($data['selectedContactValue']) && !empty($posted_data[$data['selectedContactValue']]) ){
         $groupid = $posted_data[$data['selectedContactValue']];
    }else{
        $groupid = !empty($data['listId']) ? $data['listId'] : "C";
    }
    // SET Properties
    $questionsAndAnswers = array();
    if(!empty($questoin_answers) &&  count($questoin_answers)>0){
        foreach ($questoin_answers as $key3 => $question_answer_set) {
            $sperseqa = array(
                'question' => Array
                    (
                        'xref' => 'sf2_quest'.$key3,
                        'type' => 'QuestionWithOptions',
                        'text' => !empty($question_answer_set[0]) ? ($question_answer_set[0]) : '',
                        'sortOrder' => 0
                    ),
                'answers' => Array
                    (
                        '0' => Array
                            (
                                'xref' => 'q2_answer'.$key3,
                                'text' => !empty($question_answer_set[1]) ? $question_answer_set[1] : '',
                                'sortOrder' => 0
                            )
                    )
            );
            $questionsAndAnswers[] = $sperseqa;
        }
    }
            $title = get_the_title();
        $user_pass = '';
        if(!empty($firstName) && !empty($lastName) ){
         $user_pass = trim($firstName).trim($lastName).'1';
        }else{
            if(!empty($firstName)){
                $user_pass = trim($firstName).'1';
            }
        }
        if(empty($user_pass)){
            $user_pass = $emailAddress.'1';
        }
        if(strlen($user_pass)<6){

            $user_pass = $emailAddress.'@12356A';
        }
        // remove spaces from this string
        if(!empty($user_pass)){
            $user_pass=str_replace(' ', '', $user_pass);
        }
        $personalCode=($personalCode=="")?$user_pass:$personalCode;
        $OrganigationIDs = null;
        if(isset($data['OrganigationID']) && !empty($posted_data[$data['OrganigationID']])){
            $OrganigationIDs = $posted_data[$data['OrganigationID']];
        }
        $OrganigationIDs = null;
        if( !empty($data['selectedContactValue']) &&  !empty($posted_data[$data['selectedContactValue']])){
            $OrganigationIDs = $posted_data[$data['selectedContactValue']];
        }
        // check if org. unit exists
        $orgUnitId = null;
        if ( filter_var($OrganigationIDs, FILTER_VALIDATE_INT) === false && !empty($OrganigationIDs)) {
            $properties = array('searchPhrase' => trim($OrganigationIDs));
            $query = http_build_query( $properties );
            $url = $ins_url . "/api/services/CRM/Dictionary/GetOrganizationUnits?" . $query;
            $args = array('headers' => array('api-key' => $api_key ));
            $start_time = current_time('mysql',true);
            $return = wp_remote_get( $url, $args );
            $log_return = $return;
            awp_add_to_log( $log_return, $url, $query, $record, $start_time );
            if (wp_remote_retrieve_response_code( $return ) == 200 ){
                $log_respone = json_decode($log_return["body"]);
                if(count($log_respone->result) > 0){
                    foreach($log_respone->result as $val){
                        if($val->displayName == $OrganigationIDs){
                            $orgUnitId = $log_respone->result[0]->id; 
                        }
                    }
                    if($orgUnitId){
                        $orgUnitId = $orgUnitId;
                    } else {
                        // if org. unit does not exist
                        $properties = array ( "displayName" => trim($OrganigationIDs) );
                        $start_time = current_time('mysql',true);
                        $url = $ins_url . "/api/services/Platform/OrganizationUnit/CreateOrganizationUnit";
                        $args = array('headers' => array('api-key' => $api_key, 'Content-Type' => 'application/json'), 'body' => json_encode($properties));
                        $return = wp_remote_post( $url, $args );
                        $log_return = $return;
                        $log_respone =   (!is_wp_error($return) && !empty($log_return["body"])) ? json_decode($log_return["body"]) : '';
                         awp_add_to_log( $log_return, $url, $properties, $record, $start_time );
                         if (wp_remote_retrieve_response_code( $return ) == 200 ){
                             $orgUnitId = $log_respone->result->id;
                         }
                    }
                }else {
                    // if org. unit does not exist
                    $properties = array ( "displayName" => trim($OrganigationIDs) );
                    $start_time = current_time('mysql',true);
                    $url = $ins_url . "/api/services/Platform/OrganizationUnit/CreateOrganizationUnit";
                    $args = array('headers' => array('api-key' => $api_key, 'Content-Type' => 'application/json'), 'body' => json_encode($properties));
                    $return = wp_remote_post( $url, $args );
                    $log_return = $return;
                    $log_respone =   (!is_wp_error($return) && !empty($log_return["body"])) ? json_decode($log_return["body"]) : '';
                     awp_add_to_log( $log_return, $url, $properties, $record, $start_time );
                     if (wp_remote_retrieve_response_code( $return ) == 200 ){
                         $orgUnitId = $log_respone->result->id;
                     }
                }
            }
        }
    
       $OrganigationIDs = (is_null($orgUnitId)) ? $OrganigationIDs : $orgUnitId;
       // check if org. unit exists    
       $groupid = !empty($data['listId']) ? $data['listId'] : "C";
       //check if there are some questionsandanswers 
       if(count($questionsAndAnswers)){
        $questionnaireAnswers=
                   array(
                        "questionsAndAnswers" => $questionsAndAnswers,
                        "questionnaire"     => 
                            array (
                                "xref"         => "submitform1",
                                "name"         => $title
                            ),
                    );
       }
       else{
        $questionnaireAnswers="";
       }

   $properties   = array(
    //      ----            CREATE OR UPDATE CONTACT               -----
    //      ---- SPERSE API FIELDS ----   ---- FORM FIELDS & VALUES ----
            "matchExisting"            => true,
            "contactGroupId"           => $groupid,
            "firstName"                => $firstName,
            "lastName"                 => $lastName,
            "emailAddresses"           => $emailAddress,
            "phoneNumbers"             => $phoneNumbers,
            "companyName"              => $companyName,
            "title"                    => $jobTitle,
            "industry"                 => $industry,
            "bankCode"                 => $bankCode,
            "note"                     => $note,
            "addresses"                => $addresses,
            "links"                    => $links,
            "inviteUser"               => $inviteSelected,
            "newUserPassword"          => '',
            "bypassValidation"         => true, 
            "generateAutoLoginLink"    => true,
            "sourceOrganizationUnitId"=>$OrganigationIDs,
            "trackingInfo"             => 
                array(
                  "channelCode"        => !empty($data['channelCode']) ? $data['channelCode'] :'Automate WP Plugin',
                  "refererUrl"         => $referrerUrl,
                  "entryUrl"           => $entryUrl,
                  "userAgent"          => $userAgent,
                  "clientIp"           => $ip,
                  "affiliateCode"      => $refAffiliateCode
                ),
         );
        if(!empty($questionnaireAnswers)){
            $properties['questionnaireAnswers']=$questionnaireAnswers;
        }
        if(!empty($data['selectedAssignValue']) && !empty($posted_data[$data['selectedAssignValue']]) ){
             $properties['assignedUserId']   = $posted_data[$data['selectedAssignValue']];
        }else{
            if(!empty($data['userId'])){$properties['assignedUserId']   = $data['userId']; }
        }
        if(!empty($data['selectedListValue']) && !empty($posted_data[$data['selectedListValue']]) ){
            $properties['lists'         ][] = array('name'=> $posted_data[$data['selectedListValue']]);
        }else{
            if(!empty($data['listsid'])){
                $listsidsArr = [];
                foreach ($data['listsid'] as $key => $value) {
                    $listIdArr = [];
                    $listIdArr['name'] = $value;
                    array_push($listsidsArr, $listIdArr);
                }
                $properties['lists'         ] = $listsidsArr;
            }
        }
        if(!empty($data['selectedTagsValue']) && !empty($posted_data[$data['selectedTagsValue']]) ){
            $properties['tags'          ][] = array('name'=> $posted_data[$data['selectedTagsValue']] );
        }else{
            if(!empty($data['tagId'])){
                
                $tagidsArr = [];
                foreach ($data['tagId'] as $key => $value) {
                    $tagIdArr = [];
                    $tagIdArr['name'] = $value;
                    array_push($tagidsArr, $tagIdArr);
                }
                $properties['tags'         ] = $tagidsArr;
            }
        }
        if(!empty($data['selectedStageValue']) && !empty($posted_data[$data['selectedStageValue']])  ){
            $properties['stageId']   = $posted_data[$data['selectedStageValue']];
        }else{
            if(!empty($data['StagesId'])){$properties['stageId']   = $data['StagesId'];}
        }
    return $properties;
}

function createInvoiceRequest($integration_id, $contactId, $leadId, $posted_data){
    $orderDiscountTotal = number_format((float)$posted_data['discount_total'], 2, '.', '');
    $orderShippingTotal = number_format((float)$posted_data['shipping_total'], 2, '.', '');
    $orderTotalTax      = number_format((float)$posted_data['total_tax'], 2, '.', '');
    $orderTotal         = number_format((float)$posted_data['subtotal'], 2, '.', '');
    $order              = wc_get_order($posted_data['id']);
    $transactionIdC     = get_post_meta( $posted_data['id'], '_transaction_id' ,true );
    if($order->is_paid()){
        $billingAddress = $shippingAddress = $lines = [];
        $billingAddress['countryId'] = $posted_data['billing_country'];
        if($posted_data['billing_country'] == "US" || $posted_data['billing_country'] == "CA") {
            $billingAddress['stateId']   = $posted_data['billing_state'];   
        } else {
            $billingAddress['stateName'] = $posted_data['billing_state'];
        }
        $billingAddress['city']      = $posted_data['billing_city'];
        $billingAddress['zip']       = $posted_data['billing_postcode'];
        $billingAddress['address1']  = $posted_data['billing_address_1'];
        $billingAddress['address2']  = $posted_data['billing_address_2'];
        $billingAddress['firstName'] = $posted_data['billing_first_name'];  
        $billingAddress['lastName']  = $posted_data['billing_last_name'];
        $billingAddress['email']     = $posted_data['billing_email'];
        $billingAddress['phone']     = $posted_data['billing_phone'];
        if(!empty($posted_data['shipping_address_1'])){
            $shippingAddress['countryId'] = $posted_data['shipping_country'];
            if($posted_data['shipping_country'] == "US" || $posted_data['shipping_country'] == "CA") {
                $shippingAddress['stateId']   = $posted_data['shipping_state']; 
            } else {
                $shippingAddress['stateName'] = $posted_data['shipping_state'];
            }
            $shippingAddress['city']      = $posted_data['shipping_city'];
            $shippingAddress['zip']       = $posted_data['shipping_postcode'];
            $shippingAddress['address1']  = $posted_data['shipping_address_1'];
            $shippingAddress['address2']  = $posted_data['shipping_address_2'];
            $shippingAddress['firstName'] = $posted_data['shipping_first_name'];  
            $shippingAddress['lastName']  = $posted_data['shipping_last_name'];
            $shippingAddress['email']     = $posted_data['shipping_email'];
            $shippingAddress['phone']     = $posted_data['shipping_phone'];
        }
        $productIds = $posted_data['items_id'];
        $productIds = explode(",",$productIds);
        foreach($productIds as $key => $productId){
            $itemsQuantity = $posted_data['items_quantity'];
            $itemsQuantity = explode(",",$itemsQuantity);
            $itemsTotal = $posted_data['items_total'];
            $itemsTotal = explode(",",$itemsTotal);
            $itemsVariationId = $posted_data['items_variation_id'];
            $itemsVariationId = explode(",",$itemsVariationId);
            $product = wc_get_product( $productId );
            $stored_mapping = get_option('sperse_woo_product_mapping_'.$integration_id,true);
            $matchedProductMapping = false;
            if(is_array($stored_mapping) && count($stored_mapping)>0){
                // break;
                foreach ($stored_mapping as $mapping) {
                    if($productId == $mapping->wpProduct){
                        foreach($mapping->sperseProducts as $sperseProducts){
                            $line = [];
                            $line['productCode'] = $sperseProducts->sperseProduct;
                            if($product instanceof WC_Product_Variable_Subscription){
                                $unitId = (get_post_meta( $itemsVariationId[$key], '_subscription_period', true )) ? get_post_meta( $itemsVariationId[$key], '_subscription_period', true ) : 'Piece';
                            }else{
                                if(class_exists('WC_Subscriptions_Product')){
                                    $unitId = (!empty(WC_Subscriptions_Product::get_period( $productId ))) ? WC_Subscriptions_Product::get_period( $productId ) : 'Piece';
                                }else{
                                    $unitId='Piece';
                                }                                
                            }                  
                            $rate = $itemsTotal[$key];
                            $commissionableAmount = $itemsTotal[$key];
                            $paidItemAmount = $itemsTotal[$key];
                            include AWP_APPS."/s/sperse/customrules.php";
                            if(isset($itemsVariationId[$key]) && !empty($itemsVariationId[$key])){
                                $variableProduct = wc_get_product( $itemsVariationId[$key] );
                                $productPrice    = $variableProduct->get_price();
                            }else{
                                $productPrice = $product->get_price();
                            }
                            $line['unitId'] = $unitId;
                            $line['description'] = $product->get_name();
                            $line['rate']        = $productPrice;
                            $line['quantity']    = $itemsQuantity[$key];
                            $line['commissionableAmount'] = $commissionableAmount / $itemsQuantity[$key];
                            $line['sortOrder']   = 1;
                            $line['total']       = number_format($itemsTotal[$key], 2, '.', '');
                            array_push($lines,$line);
                        }
                        $matchedProductMapping = true;
                    }
                }
            }
            
            if(!$matchedProductMapping){
                $line = [];
                $line['productCode'] = $productId;
                if($product instanceof WC_Product_Variable_Subscription){
                    $unitId = (get_post_meta( $itemsVariationId[$key], '_subscription_period', true )) ? get_post_meta( $itemsVariationId[$key], '_subscription_period', true ) : 'Piece';
                }else{
                    $unitId = (!empty(WC_Subscriptions_Product::get_period( $productId ))) ? WC_Subscriptions_Product::get_period( $productId ) : 'Piece';
                }
                $rate = $itemsTotal[$key];
                $commissionableAmount = $itemsTotal[$key];
                $paidItemAmount = $itemsTotal[$key];
                include AWP_APPS."/s/sperse/customrules.php";
                if(isset($itemsVariationId[$key]) && !empty($itemsVariationId[$key])){
                    $variableProduct = wc_get_product( $itemsVariationId[$key] );
                    $productPrice    = $variableProduct->get_price();
                }else{
                    $productPrice = $product->get_price();
                }
                $line['unitId'] = $unitId;
                $line['description'] = $product->get_name();
                $line['rate']        = $productPrice;
                $line['quantity']    = $itemsQuantity[$key];
                $line['commissionableAmount'] = $commissionableAmount / $itemsQuantity[$key];
                $line['sortOrder']   = 1;
                $line['total']       = number_format($itemsTotal[$key], 2, '.', '');
                array_push($lines,$line);
            }
        }
        //   add fees
            foreach( $order->get_items('fee') as $item_id => $item_fee ){
            $line = [];
            $fee_name = $item_fee->get_name();              // The fee name
            $fee_total = $item_fee->get_total();            // The fee total amount
            $fee_total_tax = $item_fee->get_total_tax();    // The fee total tax amount
            $commissionableAmount = $fee_total;
            $paidItemAmount = $fee_total;
            include AWP_APPS."/s/sperse/customrules.php";
            $line['unitId'     ] = 'Unit'; 
            $line['description'] = $fee_name;
            $line['rate'] = $fee_total;
            $line['quantity'] = 1;
            $line['commissionableAmount'] = $commissionableAmount;
            $line['sortOrder'] = 1;
            $line['total'] = $fee_total * $line['quantity'];
           array_push($lines, $line);
        } // add fees
        $orderCreatedDate = $order->order_date;
        $orderCreatedDate  = get_gmt_from_date($orderCreatedDate);
        $shippingAddress  = (!empty($shippingAddress)) ? $shippingAddress : null;
        $billingAddress   = (!empty($billingAddress)) ? $billingAddress : null;
        $properties        = array(
        "leadId"           => $leadId,
        "contactId"        => $contactId,
        "orderId"          => null,
        "orderNumber"      => $posted_data['id'],
        "status"           => "Sent",
        "number"           => $posted_data['id'],
        "date"             => $orderCreatedDate,
        "dueDate"          => $orderCreatedDate,
        "discountTotal"    => number_format((float)$orderDiscountTotal, 2, '.', ''),
        "shippingTotal"    => number_format((float)$orderShippingTotal, 2, '.', ''),
        "taxTotal"         => number_format((float)$orderTotalTax, 2, '.', ''),
        "grandTotal"       => number_format((float)$order->get_total(), 2, '.', ''),
        "billingAddress"   => $billingAddress,
        "shippingAddress"  => $shippingAddress,
        "description"      => "Order #".$posted_data['id'],
        "lines"            => $lines,
        "bypassValidation" => true
        );
       return $properties;
    } else {
        return null;
    }
}

function addBankCardPaymentRequest($invoiceId, $posted_data){
    $orderCreatedDate=current_time('mysql',true);
    $invoiceId       = $invoiceId;
    $order              = wc_get_order($posted_data['id']);
    $transactionDate = (get_post_meta( $posted_data['id'], '_paid_date' ,true ) ? get_post_meta( $posted_data['id'], '_paid_date' ,true ) : $orderCreatedDate );
    $transactionDate = get_gmt_from_date($transactionDate);
    $orderTotal      = number_format((float)$posted_data['subtotal'], 2, '.', '');
    if(get_post_meta( $posted_data['id'], '_wc_authorize_net_cim_credit_card_authorization_code' ,true )){
        $authorizationCode   = get_post_meta( $posted_data['id'], '_wc_authorize_net_cim_credit_card_authorization_code' ,true );    
    }else{
        $authorizationCode   = "";
    }
    $paymentGatewayName = get_post_meta( $posted_data['id'], '_payment_method' ,true );
    $expireYear  = '2050';
    $expireMonth = '12';
    $cardNumber  = "4242424242424242";
    if(strtoupper($paymentGatewayName) == strtoupper("stripe")){
        $paymentGatewayName = "Stripe";
        if(!empty($posted_data['cardDetails']['0']['source_object']->card)){
            $card_details = $posted_data['cardDetails']['0']['source_object']->card;
            $expireMonth = $card_details->exp_month;
            $expireYear = $card_details->exp_year;
            $cardNumber = $card_details->last4;
        }
    }else if(strtoupper($paymentGatewayName) == strtoupper("authorize_net_cim_credit_card")){
        $paymentGatewayName = "Authorize.net";
        $expiryDate = get_post_meta( $posted_data['id'], '_wc_authorize_net_cim_credit_card_card_expiry_date' ,true );
        if(!empty($expiryDate)){
            $expiryDate_array = explode("-",$expiryDate);
            $expireYear = '20'.$expiryDate_array['0'];
            $expireMonth =$expiryDate_array['1'];
        }   
        $cardNumber = get_post_meta( $posted_data['id'], '_wc_authorize_net_cim_credit_card_account_four' ,true );
    }else{
        $paymentGatewayName = $paymentGatewayName;
    }
    $properties = array(
        "invoiceId"            => $invoiceId,
        "date"                 => $transactionDate,
        "description"          => "Order #".$posted_data['id'],
        "amount"              => number_format((float)$order->get_total(), 2, '.', ''),
        "gatewayTransactionId" => $posted_data['transaction_id'],
        "authorizationCode"    => $authorizationCode,
        "gatewayName" => $paymentGatewayName,
        "transactionType"=>"Sale",
        "bankCardInfo"=>array(
            "holderName"         => $posted_data['billing_first_name']." ".$posted_data['billing_last_name'],
            "cardNumber"         => $cardNumber,
            "expirationMonth"    => $expireMonth,
            "expirationYear"     => $expireYear,
            "billingAddress"     => $posted_data['billing_address_1']." ".$posted_data['billing_address_2'],
            "billingZip"         => $posted_data['billing_postcode'],
            "billingCity"        => $posted_data['billing_city'],
            "billingStateCode"   => $posted_data['billing_state'],
            "billingCountryCode" => $posted_data['billing_country']
        )
    );
    return $properties;
}

function addBankCardPaymentRequestRefunded($invoiceId, $posted_data){
    $invoiceId       = $invoiceId;
    $orderTotal      = number_format((float)$posted_data['subtotal'], 2, '.', '');
    if(get_post_meta( $orderId, '_wc_authorize_net_cim_credit_card_authorization_code' ,true )){
        $authorizationCode   = get_post_meta( $posted_data['id'], '_wc_authorize_net_cim_credit_card_authorization_code' ,true );    
    }else{
        $authorizationCode   = "";
    }
    $paymentGatewayName = get_post_meta( $posted_data['id'], '_payment_method' ,true );
    $expireYear = '2030';
    $expireMonth = '10';
    $cardNumber = "4444444444444444";
    if(strtoupper($paymentGatewayName) == strtoupper("stripe")){
        $paymentGatewayName = "Stripe";
        if(!empty($posted_data['cardDetails']['0']['source_object']->card)){
            $card_details = $posted_data['cardDetails']['0']['source_object']->card;
            $expireMonth = $card_details->exp_month;
            $expireYear = $card_details->exp_year;
            $cardNumber = $card_details->last4;
        }
    }else if(strtoupper($paymentGatewayName) == strtoupper("authorize_net_cim_credit_card")){
        $paymentGatewayName = "Authorize.net";
        $expiryDate = get_post_meta( $posted_data['id'], '_wc_authorize_net_cim_credit_card_card_expiry_date' ,true );
        if(!empty($expiryDate)){
            $expiryDate_array = explode("-",$expiryDate);
            $expireYear = '20'.$expiryDate_array['0'];
            $expireMonth = $expiryDate_array['1'];
        }   
        $cardNumber = get_post_meta( $posted_data['id'], '_wc_authorize_net_cim_credit_card_account_four' ,true );
    }else if(strtoupper($paymentGatewayName) == strtoupper("paytrace")){
        $paymentGatewayName = "Paytrace";
        $cardNumber =  get_post_meta( $order_id, 'wc_paytrace_last4', true);
        $expireMonth = get_post_meta( $order_id, 'wc_paytrace_exp_month', true);
        if(!empty($expireMonth)){
            $expireMonth ='20'.$expireMonth;
        }    
        $expireYear = get_post_meta( $order_id, 'wc_paytrace_exp_year', true);
    }else{
        $paymentGatewayName = $paymentGatewayName;
    }
    $transactionIdO       = get_post_meta( $posted_data['id'], '_transaction_id' ,true );
    $transactionIdAuth    = get_post_meta( $posted_data['id'], '_wc_authorize_net_cim_credit_card_refund_trans_id' ,true );
    $transactionIdStripe  = get_post_meta( $posted_data['id'], '_stripe_refund_id' ,true );
    if($transactionIdAuth){
          $transactionIdR = $transactionIdAuth;
    } else if($transactionIdStripe){
          $transactionIdR = $transactionIdStripe;
    }
    $refundAmount = get_post_meta( $posted_data['refundId'], '_refund_amount' ,true );
    $refundReason = get_post_meta( $posted_data['refundId'], '_refund_reason' ,true );
    $transactionDate = get_gmt_from_date(get_the_modified_date($posted_data['id']));
    $properties = array(
        "invoiceId"            => $invoiceId,
        "date"                 => $transactionDate,
        "description"          => "Order #".$posted_data['id'],
        "amount"               => number_format(- $refundAmount, 2),
        "gatewayTransactionId" => $transactionIdR,
        "gatewayOriginTransactionId" => $transactionIdO,
        "authorizationCode"    => $authorizationCode,
        "gatewayName" => $paymentGatewayName,
        "transactionType"=>"Refund",
        "bankCardInfo"=>array(
            "holderName"         => $posted_data['billing_first_name']." ".$posted_data['billing_last_name'],
            "cardNumber"         => $cardNumber,
            "expirationMonth"    => $expireMonth,
            "expirationYear"     => $expireYear,
            "billingAddress"     => $posted_data['billing_address_1']." ".$posted_data['billing_address_2'],
            "billingZip"         => $posted_data['billing_postcode'],
            "billingCity"        => $posted_data['billing_city'],
            "billingStateCode"   => $posted_data['billing_state'],
            "billingCountryCode" => $posted_data['billing_country']
        )
    );
    return $properties;
}

function voidBankCardPaymentRequest($invoiceId, $posted_data) {
    $transactionIdAuthV       = get_post_meta( $posted_data['id'], '_wc_authorize_net_cim_credit_card_void_trans_id' ,true );
    $transactionIdStripeV     = get_post_meta( $posted_data['id'], '_stripe_void_id' ,true );
    if($transactionIdAuthV){
        $transactionIdV = $transactionIdAuthV;
    }else if($transactionIdStripeV){
        $transactionIdV = $transactionIdStripeV;
    }
    $paymentGatewayName = get_post_meta( $posted_data['id'], '_payment_method' ,true );
    if(strtoupper($paymentGatewayName) == strtoupper("stripe")){
        $paymentGatewayName = "Stripe";
    }else if(strtoupper($paymentGatewayName) == strtoupper("authorize_net_cim_credit_card")){
        $paymentGatewayName = "Authorize.net";
    }else{
        $paymentGatewayName = $paymentGatewayName;
    }
    $properties = array(
        "invoiceId"=> $invoiceId,
        "invoiceNumber"=> $invoiceId,
        "gatewayName"=> $paymentGatewayName,
        "gatewayTransactionId"=> $transactionIdV
    );
    return $properties;
}

function cancelSubscriptionRequest($integration_id, $contactId, $posted_data){
    $productIds = $posted_data['items_id'];
    $productIds = explode(",",$productIds);
    $productCodes = [];
    foreach($productIds as $key => $productId){
        $product = wc_get_product( $productId );
        // $stored_mapping = get_post_meta($productId,'sperse_woo_product_mapping_'.$integration_id,true);
        $stored_mapping = get_option('sperse_woo_product_mapping_'.$integration_id);
        $matchedMappingProduct = false;
        if(is_array($stored_mapping) && count($stored_mapping)>0){
            foreach ($stored_mapping as $mapping) {
                if($productId == $mapping->wpProduct){
                    foreach($mapping->sperseProducts as $val){
                        $matchedMappingProduct = true;
                        $productCode = $val->sperseProduct;
                        array_push($productCodes, $productCode);
                    }
                }
            }
            if(!$matchedMappingProduct){
                $productCode = $productId;
                array_push($productCodes, $productCode);
            }
        }else{
                $productCode = $productId;
                array_push($productCodes, $productCode);
        }
    }
    $properties      = array(
    "contactId"              => $contactId,
    "productCodes"    => $productCodes,
    "cancelationReason"      => "Woocommerce Order Cancelled"
    );
    return $properties;
}

function updateSubscriptionRequest($integration_id, $contactId, $posted_data, $nextPaymentDate){
    $productIds = $posted_data['items_id'];
    $productIds = explode(",",$productIds);
    $subscriptions = [];
    foreach($productIds as $key => $productId){
        $subscription = [];
        $product = wc_get_product( $productId );
        $stored_mapping = get_post_meta($productId,'sperse_woo_product_mapping_'.$integration_id,true);
        if(is_array($stored_mapping) && count($stored_mapping)>0){
            foreach ($stored_mapping as $mapping) {
                $productCode = $mapping['sperseProductCode'];
            }
        }else{
                $productCode = $productId;
        }
        $product = wc_get_product( $productId );
        $price   = number_format((float) $product->get_regular_price(), 2, '.', '');
        $subscription['code'    ] = $productCode;
        $subscription['name'    ] = $productCode;
        $subscription['endDate' ] = $nextPaymentDate;
        $subscription['amount'  ] = $price;
        array_push($subscriptions, $subscription);
    }
    $properties      = array(
    "contactId"              => $contactId,
    "orderNumber"            => $posted_data['id'],
    "subscriptions"          => $subscriptions
    );
    return $properties;
}

function awp_sperse_resend_data($log_id,$data,$integration){
        $result_data = json_decode($integration['data']);
        $sperse_accountId = $result_data->field_data->sperse_accountId;
        $config_data = awp_get_sperse_active_status_account($sperse_accountId);
        if(!empty($config_data) && is_array($config_data) && count($config_data)>0){
            $api_key = $config_data['apikey'] ? $config_data['apikey'] : "";
            $ins_url = $config_data['base_url'] ? $config_data['base_url'] : "";
        }else{
            $api_key = get_option( 'awp_sperse_api_key' ) ? get_option( 'awp_sperse_api_key' ) : "";
            $ins_url = get_option( 'awp_sperse_url'     ) ? get_option( 'awp_sperse_url'     ) : "";
        }
        if(!$api_key ) {
            return;
        }
        $data=stripslashes($data);
        $data=json_decode($data,true);
        $url=$data['url'];
        $properties=$data['args'];
        $resp=array();
        $resp['success']=true;
        if(!$url){
            $resp['success']=false;
            $resp['msg']="Syntax Error! Request is invalid";
            return $resp;
        }
        if( $integration['task'] == 'createLead'){
            $args = array(
                'headers' => array(
                    'api-key'      => $api_key,
                    'Content-Type' => 'application/json'
                ),
                'body' => json_encode($properties)
            );
            $start_time = current_time('mysql',true);
            $return = wp_remote_post( $url, $args );
            $log_return = $return;
            $log_respone =   (!is_wp_error($return) && !empty($log_return["body"])) ? json_decode($log_return["body"]) :'';
            if(!empty($log_respone->result->userKey)){
                $userKey = $log_respone->result->userKey;
                $first =substr($userKey,0,6);
                $last =substr($userKey,-6);
                $log_respone->result->userKey =$first.'-XXXXXX-XXXXXX-XXXXXX-'.$last;   
            }
            if(!empty($log_respone->result->autoLoginLink)){
                $autoLoginLink = $log_respone->result->autoLoginLink;
                $secured_string = parse_url($autoLoginLink,PHP_URL_QUERY);
                $log_key = '';
                if(!empty($secured_string)){
                    $secured_string_components =    explode("=", $secured_string);
                    if(!empty( $secured_string_components)){
                        $userKey = end($secured_string_components);
                        if(!empty($userKey)){
                            $first =substr($userKey,0,6);
                            $last =substr($userKey,-6);
                            $log_key =$first.'-XXXXXX-XXXXXX-XXXXXX-'.$last; 
                        } 
                    }
                    $log_secured_key = "secureId=".$log_key;
                   $autoLoginLink =  str_replace($secured_string, $log_secured_key, $autoLoginLink);
                }
                $log_respone->result->autoLoginLink =  $autoLoginLink;
            }  
            $log_return['body'] = json_encode($log_respone); 
            awp_add_to_log( $log_return, $url, $properties, $integration,$start_time );
        }
        elseif($integration['task'] == 'createUser'){
            $args = array(
                'headers' => array(
                    'api-key'      => $api_key,
                    'Content-Type' => 'application/json'
                ),
                'body' => json_encode($properties)
            );
            $start_time = current_time('mysql',true);
            $return = wp_remote_post( $url, $args );
            $respone = json_decode($return["body"]);
            $status = $respone->success;
            $log_return = $return;
            $log_respone = json_decode($log_return["body"]);
            if(!empty($log_respone->result->userKey)){
                $userKey = $log_respone->result->userKey;
                $first =substr($userKey,0,6);
                $last =substr($userKey,-6);
                $log_respone->result->userKey =$first.'-XXXXXX-XXXXXX-XXXXXX-'.$last;   
            }
            if(!empty($log_respone->result->autoLoginLink)){
                $autoLoginLink = $log_respone->result->autoLoginLink;
                $secured_string = parse_url($autoLoginLink,PHP_URL_QUERY);
                $log_key = '';
                if(!empty($secured_string)){
                    $secured_string_components =    explode("=", $secured_string);
                    if(!empty( $secured_string_components)){
                        $userKey = end($secured_string_components);
                        if(!empty($userKey)){
                            $first =substr($userKey,0,6);
                            $last =substr($userKey,-6);
                            $log_key =$first.'-XXXXXX-XXXXXX-XXXXXX-'.$last; 
                        } 
                    }
                    $log_secured_key = "secureId=".$log_key;
                   $autoLoginLink =  str_replace($secured_string, $log_secured_key, $autoLoginLink);
                }
                $log_respone->result->autoLoginLink =  $autoLoginLink;
            }  
            $log_return['body'] = json_encode($log_respone);
            awp_add_to_log( $log_return, $url, $properties, $integration,$start_time );
        }
        elseif($integration['task'] == 'loginUser'){
            $args = array(
                'headers' => array(
                    'Content-Type' => 'application/json'
                ),
                'body' => json_encode($properties)
            );
            $start_time = current_time('mysql',true);
            $return = wp_remote_post( $url, $args );
            $log_properties = $properties;
            $log_properties['password'] ='****************';
            $log_return = $return;
            $log_respone = json_decode($log_return["body"]);
            if(!empty($log_respone->result->accessToken)){
                $accesstoken = $log_respone->result->accessToken;
                $first =substr($accesstoken,0,6);
                $last =substr($accesstoken,-6);
               $log_respone->result->accessToken =$first.'-XXXXXX-XXXXXX-XXXXXX-'.$last;   
            }
            if(!empty($log_respone->result->encryptedAccessToken)){
                $enaccesstoken = $log_respone->result->encryptedAccessToken;
                $first =substr($enaccesstoken,0,6);
                $last =substr($enaccesstoken,-6);
               $log_respone->result->encryptedAccessToken =$first.'-XXXXXX-XXXXXX-XXXXXX-'.$last;   
            }
            $log_return['body'] = json_encode($log_respone);
            awp_add_to_log( $log_return, $url, $log_properties, $integration,$start_time );
            if(!is_wp_error($return)) {
                 $response = json_decode($return["body"]);
                 $status = $response->success;
                 if($status) {
                    $accessToken = $response->result->accessToken;
                    $properties = array('SystemType' => $result_data->field_data->systemType);
                    $query = http_build_query( $properties );
                    $url = $ins_url . "/api/services/Platform/MemberSubscription/GetMemberInfo?" . $query;
                    $args = array('headers' => array(
                                // 'api-key' => $api_key,
                                'Authorization' => 'Bearer '. $accessToken, 'Content-Type' => 'application/x-www-form-urlencoded'),
                    );
                    $start_time = current_time('mysql',true);
                    $return = wp_remote_get( $url, $args );
                    $log_return = $return;
                    $log_respone = json_decode($log_return["body"]);
                    if(!empty($log_respone->result->userKey)){
                        $userKey = $log_respone->result->userKey;
                        $first =substr($userKey,0,6);
                        $last =substr($userKey,-6);
                       $log_respone->result->userKey =$first.'-XXXXXX-XXXXXX-XXXXXX-'.$last;   
                    }
                    $log_return['body'] = json_encode($log_respone);
                    awp_add_to_log( $log_return, $url, $query, $integration,$start_time );
                    $respone = json_decode($return["body"]);
                    $status = $respone->success;
                    if($status) {
                        $userKey = $respone->result->userKey;
                        $redUrl = $ins_url.'/?secureId='.$userKey;
                        
                        $resp['success']=true;
                        $resp['msg']="Success";
                    } // die();
                    } else {                        
                        $resp['success']=false;
                        $resp['msg']="Invalid username or password. Please try again!";
                    }
                }
                else {
                    $resp['success']=false;
                    $resp['msg']="Invalid username or password. Please try again!";
                }
        }
        return $resp;
}
// add_filter( 'woocommerce_defer_transactional_emails', '__return_true' );
// *************************************************
// *** SEND EMAIL TO CRM  *** 
// *************************************************
// delay woocommerce emails
add_filter( 'woocommerce_mail_callback_params', 'wsec_mail_callback_params_sperse',25,2);

function awp_send_email_to_sperse($contactid,$order_id,$sperse_api,$sperse_url){
    if(empty($order_id) || empty($contactid) || empty($sperse_api)  || empty($sperse_url)){
        return;
    }
    $start_time = current_time('mysql',true);
    $sperse_url = $sperse_url."/api/services/CRM/ContactCommunication/StoreEmail";
    if(!get_post_meta($order_id, '_pending_email',true)){
      return;
  }
  $content =  get_post_meta($order_id, '_processing_email_content',true);
  $to =  get_post_meta($order_id, '_processing_email_to' , true);
  $subject = get_post_meta($order_id, '_processing_email_subject' , true);
  $properties =  array(
    'ContactId'=>$contactid,   
    'subject'=>$subject,
    'to'=>array(
            $to
        ),
    'body'=>$content,
    );
    $params = array(
            'headers' => array(
                'api-key'      => $sperse_api,
                'Content-Type' => 'application/json'
            ), 
            'body' => json_encode($properties)
        );
    $return = wp_remote_post( $sperse_url, $params );
    $api_response = json_decode( wp_remote_retrieve_body( $return ), true );
    delete_post_meta($order_id, '_pending_email');
    awp_add_to_log( $return, $sperse_url , $properties, "",$start_time );
}

function wsec_mail_callback_params_sperse($args,$obj){
    $start_time = current_time('mysql',true);
    $config_data = awp_get_sperse_active_status_account();
    $ins_url = $config_data['base_url'] ? $config_data['base_url'] : "";
    $api_key = $config_data['apikey'] ? $config_data['apikey'] : "";
    if($obj->id=='new_order'){
        return $args;
    }
    if(empty($obj->object)){
        return $args;
    }
    $to = $args['0'];
    $subject = $args['1'];
    $content = $args['2'];
    $attachments = $args['3'];
    $url       = $ins_url."/api/services/CRM/ContactCommunication/StoreEmail";
    $contactId = '';
    $renewal =  get_post_meta($obj->object->get_id(), 'createOrUpdateContactResponse', true);
    if($contactId = get_user_meta($obj->object->get_user_id(), "_contactId", true)){
        $contactId = $contactId;
    }else if(!empty($renewal)){
        $renewal = json_decode($renewal);
        $contactId = !empty($renewal->result->contactId) ? $renewal->result->contactId :'';
    }else {
        $invoice =  get_post_meta($obj->object->get_id(), 'createInvoiceRequest', true);
        if($invoice)
        $invoice =  json_decode($invoice);
        $contactId = !empty($invoice->contactId) ? $invoice->contactId :'';
    }
    if($contactId){
        $properties =  array(
        'ContactId'=>$contactId,   
        'subject'=>$subject,
        'to'=>array(
                $to
            ),
           'body'=>$content,
        );
        $params = array(
                'headers' => array(
                    'api-key'      => $api_key,
                    'Content-Type' => 'application/json'
                  ), 
                'body' => json_encode($properties)
            );
        $return = wp_remote_post( $url, $params );
        $api_response = json_decode( wp_remote_retrieve_body( $return ), true );
        $response = array(
            'response'=> $api_response,
            'property'=>$properties
        );
        update_post_meta($obj->object->get_id(), '_emailLog'.$start_time , "found contact id");
        awp_add_to_log( $return, $ins_url."/api/services/CRM/ContactCommunication/StoreEmail" , $properties, "",$start_time );
    }else{
        update_post_meta($obj->object->get_id(), '_emailLog'.$start_time , "not found contact id");
        $to = $args['0'];
        $subject = $args['1'];
        $content = $args['2'];
        update_post_meta($obj->object->get_id(), '_pending_email' , true);
        update_post_meta($obj->object->get_id(), '_processing_email_content' , $content);
        update_post_meta($obj->object->get_id(), '_processing_email_to' , $to);
        update_post_meta($obj->object->get_id(), '_processing_email_subject' , $subject);
    }
        return $args;
    }

// *************************************************
// *** SEND EMAIL TO CRM  *** 
// *************************************************
function awp_checkout_create_acct( $post_data ) {
    $user = get_user_by( 'email', $post_data['billing_email'] );
    if ( $user ) {
        $post_data['createaccount'] = 0;
    } else {
        $post_data['createaccount'] = 1;
    }
    return $post_data;
}
add_filter('woocommerce_checkout_posted_data', 'awp_checkout_create_acct', 10, 1);
// Attach order to existing account if user not logged in
function awp_checkout_set_customer_id( $current_user_id ) { 
    if ( !$current_user_id ) {
        $user = get_user_by('email', $_POST['billing_email']);
        if ( $user ) {
            $current_user_id = $user->ID;
            wp_set_current_user($user->ID);
        }
    }
    return $current_user_id;
} 
add_filter('woocommerce_checkout_customer_id', 'awp_checkout_set_customer_id',10,1);