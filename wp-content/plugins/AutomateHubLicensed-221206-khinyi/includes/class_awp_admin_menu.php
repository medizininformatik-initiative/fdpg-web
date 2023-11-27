<?php
if(class_exists('AWP_Admin_Menu')){return;}
/* Class Admin_Menu */
class AWP_Admin_Menu {
    
/* Class constructor. */
public function __construct() {                             
    add_action( 'admin_menu', array( $this, 'admin_menu' ) );
    add_action( 'wp_ajax_awp_log_resend_request',  array( $this, 'awp_log_resend_request' ) );
    add_action( 'wp_ajax_awp_get_actionProviders',  array( $this, 'awp_get_actionProviders'), 10, 0 );
    add_action( 'wp_ajax_awp_get_allFormProviders',  array( $this, 'awp_get_allFormProviders'), 10, 0 );
    
    
}

/* Register the admin menu. @return void */
public function admin_menu() {
    global $submenu;
	$sperse_hub =    add_menu_page(                 esc_html__( 'Automate Hub'      , 'automate_hub'), esc_html__('Automate Hub'         , 'automate_hub'), 'manage_options', 'automate_hub_dashboard', array( $this,'awp_intro'), AWP_ASSETS.'/images/sperseio.png');
        add_submenu_page( 'automate_hub_dashboard', esc_html__( 'Introduction'      , 'automate_hub'), esc_html__('Introduction'         , 'automate_hub'), 'manage_options', 'automate_hub_dashboard');
   if(get_option('sperse_license_key')){
        add_submenu_page( 'automate_hub_dashboard', esc_html__( 'App Directory'     , 'automate_hub'), esc_html__('App Directory'        , 'automate_hub'), 'manage_options', 'awp_app_directory'     , array( $this,'awp_app_directory'  ));
        add_submenu_page( 'automate_hub_dashboard', esc_html__( 'Platform Settings' , 'automate_hub'), esc_html__('My Accounts'          , 'automate_hub'), 'manage_options', 'automate_hub'          , array( $this,'awp_settings'       ));
        add_submenu_page( 'automate_hub_dashboard', esc_html__( 'My Integrations'   , 'automate_hub'), esc_html__('My Integrations'      , 'automate_hub'), 'manage_options', 'my_integrations'       , array( $this,'awp_routing'        ));
        add_submenu_page( 'automate_hub_dashboard', esc_html__( 'New Integrations'  , 'automate_hub'), esc_html__('New Integrations'     , 'automate_hub'), 'manage_options', 'automate_hub-new'      , array( $this,'awp_new_integration'));
        add_submenu_page( null                    , esc_html__( 'Add Tenant'        , 'automate_hub'), esc_html__('Create A New Account' , 'automate_hub'), 'manage_options', 'add-tenant'            , array( $this,'aws_sperse_tenant'  ));
        add_submenu_page( null                    , esc_html__( 'Import Log'        , 'automate_hub'), esc_html__('Import Log'           , 'automate_hub'), 'manage_options', 'import_log'            , array( $this,'awp_import_log'     ));   
        add_submenu_page( null                    , esc_html__( 'Export Log'        , 'automate_hub'), esc_html__('Export Log'           , 'automate_hub'), 'manage_options', 'export_log'            , array( $this,'awp_export_log'     ));   
        add_submenu_page( 'automate_hub_dashboard', esc_html__( 'Activity Log'      , 'automate_hub'), esc_html__('Activity Audit Log'   , 'automate_hub'), 'manage_options', 'automate_hub_log'      , array( $this,'automate_log'       ));

	}
       add_submenu_page('automate_hub_dashboard'  , esc_html__( 'Activate License'  , 'automate_hub'), esc_html__('Activate License'     , 'automate_hub'), 'manage_options', 'automate_license'      , array( $this,'sp_license_management'));
       add_action( 'admin_print_scripts-'.$sperse_hub, array($this,'sperse_required_scripts' ) );
       add_action( 'admin_enqueue_scripts', array( $this, 'sperse_register_scripts' ) );
       add_action( 'admin_notices', array( __CLASS__, 'sp_inject_before_notices' ), -9999 );
       add_action( 'admin_notices', array( __CLASS__, 'sp_inject_after_notices' ), PHP_INT_MAX );
}

public static function sp_inject_before_notices(){
    if ( ! self::sp_is_admin_or_embed_page() ) {
            return;
        }
        $allowed_html = array(
            'div' => array(),
        );

		echo wp_kses('<div class="sperse-layout__notice-list-hide" id="wp__notice-list">',$allowed_html);
		echo wp_kses('<div class="wp-header-end" id="sperse-layout__notice-catcher"></div>',$allowed_html);
        
}

public static function sp_inject_after_notices(){
    if ( ! self::sp_is_admin_or_embed_page() ) {
            return;
        }
        $allowed_html = array(
            'div' => array(),
        );
        echo wp_kses('</div>',$allowed_html);
}

public static function sp_is_admin_or_embed_page() {
     global $current_screen;
     $sperse_pages_base = array(
        'toplevel_page_automate_hub_dashboard',
        'automate-hub_page_awp_app_directory',
        'automate-hub_page_automate_hub',
        'automate-hub_page_my_integrations',
        'automate-hub_page_automate_hub-new',
        'automate-hub_page_automate_hub_log',
        'automate-hub_page_automate_add_message_template',
        'automate-hub_page_automate_message_templates',
        'automate-hub_page_automate_license',
     );
     $is_sperse_page = (!empty($current_screen->base) && (in_array($current_screen->base, $sperse_pages_base))) ? true : false;
    return  $is_sperse_page;
}

function automate_add_message_template(){
    $form_providers   = awp_get_form_providers();
    $action_providers = awp_get_action_providers();
    ksort( $action_providers );
    require_once AWP_VIEWS . '/new_message_template.php';
}

function automate_message_templates(){
    if ( isset( $_GET['status'] ) ) {
            $status = sanitize_text_field($_GET['status']);
        }
    ?>
    <?php include AWP_INCLUDES.'/header.php'; ?>
        <div class="wrap">
            <h3 class="sperse-app-page-title"><?php esc_html_e( 'Integrations', 'automate_hub' ); ?></h3>
            <a href="<?php echo esc_url(admin_url( 'admin.php?page=automate_hub-new' )); ?>" class="page-title-action"><?php esc_html_e( 'Add New', 'automate_hub' ); ?></a>
            <form id="form-list" method="post">
                <input type="hidden" name="page" value="automate_hub"/>
                <?php
                $list_table = new AWP_List_Table();
                $list_table->prepare_items();
                $list_table->display();
                ?>
            </form>
        </div>
    <?php
}

function sp_license_management() {
    require_once AWP_VIEWS . '/license.php';
}

function sperse_register_scripts(){
    global $current_screen;
    if(!empty($current_screen->base) && ( $current_screen->base=='automate_hub_page_automate_license')  ){
        wp_enqueue_style( 'awp-bootstrap.min', AWP_CSS."/bootstrap.min.css" );
        wp_enqueue_script( 'sperse-editor', AWP_ASSETS.'/js/sperse_editor.js',array(),false,true);    
    }
    if(!empty($current_screen->base) && ($current_screen->base=='toplevel_page_automate_hub_dashboard')  ){
        wp_enqueue_style( 'awp-bootstrap.min', AWP_CSS."/bootstrap.min.css" );
    }
    if(!empty($current_screen->base) && ($current_screen->base=='automate-hub_page_automate_hub' || $current_screen->base=='automate-hub_page_automate_license')  ){
        wp_enqueue_script('clipboard');     
        wp_enqueue_script('awp-clipboard-custom-script', AWP_ASSETS . '/js/clipboad-custom.js', array('jquery','clipboard'), '', 1);  
    }
    
    if(!empty($current_screen->base) && ($current_screen->base=='automate-hub_page_awp_app_directory')  ){
        wp_enqueue_style( 'app-directory-style',  AWP_ASSETS."/css/app-directory-style.css",false);
        wp_enqueue_script( 'app-directory-main'   , AWP_ASSETS.'/js/app-directory-main.js',array(),true,true);  
    }

    if($this->sp_is_admin_or_embed_page()){
        $action=isset($_GET['action'])?sanitize_text_field($_GET['action']):false;
        if(!empty($current_screen->base) && ($current_screen->base!='automate-hub_page_my_integrations' || $action=='edit') ){
         wp_enqueue_style( 'awp-bootstrap.min', AWP_CSS."/bootstrap.min.css" );   
        }
        wp_enqueue_style( 'add-google-fonts',  'https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap',false );
        wp_enqueue_script( 'bootstrap.min-js',  AWP_JS.'/bootstrap.min.js',array(),false,true);  

        wp_enqueue_script( 'sperse-editor'   , AWP_ASSETS . '/js/sperse_editor.js',array(),false,true);  
        $localize_scripts = array( 'nonce' => wp_create_nonce( 'automate_hub'));
        wp_localize_script('sperse-editor', 'awpObj', $localize_scripts );
    }

} 

function sperse_required_scripts(){
    global $current_screen;

    wp_enqueue_script('awp-vuejs',                  AWP_ASSETS . '/js/vue.min.js', array('jquery'), '', 1);
    wp_enqueue_script('awp-main-script',            AWP_ASSETS . '/js/script.js?version='.rand(1,1000) , array('awp-vuejs'), rand(10,100), 1);
    wp_enqueue_script( 'sperse-editor',             AWP_ASSETS . '/js/sperse_editor.js',array(),false,true);  
}

/* Display the Tasks page. @return void */
public function awp_intro() {
    $action = isset( $_GET['action'] ) ? sanitize_text_field($_GET['action']) : 'list';
    if($action=='edit')
        return '';
    require_once AWP_VIEWS . '/dashboard.php';
}
        
public function awp_routing() {
    $action = isset( $_GET['action'] ) ? sanitize_text_field($_GET['action']) : 'list';
    $id     = isset( $_GET['id'] ) ? intval( sanitize_text_field($_GET['id'] )) : 0;
    $val     = isset( $_GET['val'] ) ? intval( sanitize_text_field($_GET['val'] )) : 0;
    $integration_name     = isset( $_GET['integration_name'] ) ? sanitize_text_field($_GET['integration_name'] ) : '';
    switch ( $action ) {
    case 'edit'     : $this->awp_edit($id)         ; break;
    case 'status'   : $this->awp_change_status($id,$val); break;
    case 'duplicate': $this->awp_duplicate($id)    ; break;
    case 'quickedit': $this->awp_quick_edit($id,$integration_name)    ; break;
    default         : $this->awp_list_page()       ; break;
    }
}

public function awp_quick_edit($id,$integration_name){
    global $wpdb;
    $relation_table = $wpdb->prefix . "awp_integration";
    $status_data    = $wpdb->get_row( $wpdb->prepare("SELECT * FROM {$relation_table} WHERE id = %d",$id), ARRAY_A );
   
    if(!empty($status_data) && count($status_data)>0 && !empty($integration_name)){
        $result = $wpdb->update( $relation_table,array('title' => $integration_name), array( 'id'=> $id ));
    }
    AWP_redirect( admin_url( 'admin.php?page=my_integrations' ));
    }


/* This function generates the list of connections */
public function awp_list_page() {

    if(!awp_get_active_integrations_count()){
        AWP_redirect( admin_url( 'admin.php?page=automate_hub-new' ));
    }
    if ( isset( $_GET['status'] ) ) {
        $status = sanitize_text_field($_GET['status']);
    }

    $list_table = new AWP_List_Table();
    $list_table->prepare_items();
            
?>
<?php include AWP_INCLUDES.'/header.php'; 
// if(isset($_GET['type'])){
//     $type=sanitize_text_field($_GET['type']);
    
//     if($type=='error'){
//         if(isset($_GET['msg'])){
//             $msg=sanitize_text_field($_GET['msg']);
//         }
//     }
// }
    //for limited version start
    $notice=awp_usage_controller("A","sperse",["W","Y","Z"]);
    $notice=json_decode($notice,true);
    if($notice['success'] == false){
        $allowed_html = wp_kses_allowed_html( 'post' );

        $div='<div id="updateLicense" style="background-color: #e9d000;margin-left: -20px;color: black;text-align: center;font-size: 16px;height: 29px;line-height: 28px;">'.sanitize_text_field($notice['msg']).'</div>';
        echo wp_kses($div,$allowed_html);
    
    }
    //for limited version end
?>
    <div class="wrap integrationstable">
        <form method="post" action="<?php echo esc_url( admin_url( 'admin.php?page=my_integrations' ) ); ?>">
    		<div class="bottom-line main-log">
    		    <h3 class="sperse-app-page-title main-title"><?php esc_html_e( 'My Integration Spots', 'automate_hub' ); ?></h3>
                <?php $list_table->search_box( '.', 'search_id' ) ; ?>
                <a href="<?php echo esc_url(admin_url( 'admin.php?page=automate_hub-new' )); ?>" class="page-title-action"><?php esc_html_e( 'Add New', 'automate_hub' ); ?></a>	
    		</div>
        </form>
        <style type="text/css">
            .wp-list-table .column-id { width: 79px; }
            .wp-list-table .column-status { width: 93px; }
            .wp-list-table .column-title { width: 20%; }
        </style>
        <form id="form-list" method="post" class="form-list__wrap">
            <input type="hidden" name="page" value="automate_hub"/>
            <?php   
                $list_table->display();
            ?>
            
        </form>
    </div>



<?php

include AWP_VIEWS.'/context_menu.php';
}

/* Handles the app directory */
public function awp_app_directory () {
    require_once AWP_VIEWS . '/directory.php';
}
    
public function awp_export_log(){

  ?>
<?php include AWP_INCLUDES.'/header.php'; ?>
    <div class="wrap">
        <h3 class="sperse-app-page-title"><?php esc_html_e( 'Export Logs', 'automate_hub' ); ?></h3>
    </div>
    <?php
    require_once AWP_VIEWS . '/export_log.php';
}

function awp_import_log(){
?>
<?php include AWP_INCLUDES.'/header.php'; ?>
    <div class="wrap">
		<div class="bottom-line">
			<h3 class="sperse-app-page-title main-title"><?php esc_html_e( 'Import Logs', 'automate_hub' ); ?></h3>
		</div>
        
    </div>
    <?php
    require_once AWP_VIEWS . '/import_log.php';
}
    
/* Handles new connection */
public function awp_new_integration(){
    $form_providers   = awp_get_form_providers();
    $action_providers = awp_get_action_providers();
    ksort( $action_providers );
    // for limited version start
    $notice=awp_usage_controller("A","sperse",["W"]);
    $notice=json_decode($notice,true);
    // for limited version end
    require_once AWP_VIEWS . '/new_integration.php';
}

/* Handles connection view */
public function awp_view( $id='' ) {
}

/* Handles connection edit */
public function awp_edit( $id='' ) {
    if ( $id ) {
        require_once AWP_VIEWS . '/edit_integration.php';
    }
}

/* Settings Submenu View */
public function awp_settings( $value = '' ) {
    $tabs = awp_get_settings_tabs();
    include AWP_VIEWS . '/apps.php';
}

/* Tenant Hidden Page View */
public function aws_sperse_tenant( $value = '' ) {
    $tabs = awp_get_settings_tabs();
    include AWP_VIEWS . '/new_tenant.php';
}

/* Log Submenu View */
public function automate_log( $value = '' ) {
    $action = isset( $_GET['action'] ) ? sanitize_text_field($_GET['action']) : 'list';
    $id     = isset( $_GET['id'] ) ? intval( sanitize_text_field($_GET['id'] )) : 0;
    switch ( $action ) {
        case 'view': $this->automate_log_view( $id ); break;
        default    : $this->automate_log_list_page(); break;
    }
}
public function awp_get_allFormProviders() {
    $form_providers = awp_get_form_providers();
    $allFormsArray = array("ARForms", "BuddyBoss", "Caldera Forms", "Contact Form 7", "Everest Forms", "Elementor Pro Form", "Fluent Forms", "Formcraft", "Formidable", "Forminator", "Gravity Forms", "Happy Forms", "JetEngine Forms","Ninja Forms", "PlanSo Forms", "Smart Forms", "weForms", "WPForms", "WSForms", "WooCommerce", "Receiver Webhook");
    
    $formsArray = [];
    $formProviders = [];
    foreach ( $form_providers as $key => $value ) {
        $formProvider = [];
        $formProvider['id'] = $key;
        $formProvider['name'] = $value;
        $formProvider['disable'] = false;
        array_push($formProviders, $formProvider);
        array_push($formsArray, $value);
     }
     $disableFroms = array_diff($allFormsArray, $formsArray);
     foreach($disableFroms as $key => $val) {
        $formProvider = [];
        $formProvider['id'] = $key;
        $formProvider['name'] = $val. "(not installed)";
        $formProvider['disable'] = true;
        array_push($formProviders, $formProvider);
     }
     wp_send_json_success( $formProviders);
}
public function awp_get_actionProviders() {
                $actions   = awp_get_actions();
                $providers = [];
                // $favicon = get_platform_fav('sperse');
                // $favicon = function_exists('get_platform_fav');
                foreach( $actions as $key => $value ) {
                    $provider = [];
                    $provider['id'] = $key;
                    $provider['title'] = $value['title'];
                    switch ($key) {
                        case 'webhookin'     : $provider['favicon'] = AWP_ASSETS."/images/favicons/webhookin.png"     ; break;
                        case 'webhookout'    : $provider['favicon'] = AWP_ASSETS."/images/favicons/webhookout.png"    ; break;
                      default                : $provider['favicon'] = AWP_ASSETS."/images/favicons/".$key.".png";
                                          //   $provider['favicon'] = "https://www.google.com/s2/favicons?sz=16&domain=$key.com";
                    }
                    // $providers['favicon'] = $favicon;
                    array_push($providers,$provider);
                }

                wp_send_json_success( $providers );

            }
            
public function awp_log_resend_request(){
    $data  = isset( $_POST['data'] ) ?  sanitize_text_field(urldecode(stripslashes($_POST['data'])) ) : '';
    $log_id  = isset( $_POST['log_id'] ) ?  sanitize_text_field($_POST['log_id'] ) : '';
    global $wpdb;
    $response=array();
    if($data != '' && $log_id != ''){
        $relation_table = $wpdb->prefix . "automate_log";
        $log    = $wpdb->get_row( $wpdb->prepare("SELECT * FROM {$relation_table} WHERE id =%d",$log_id), ARRAY_A );
        $integration_id = isset($log["integration_id"]) ? $log["integration_id"] :'';
        $relation_table = $wpdb->prefix . "awp_integration";
        $integration    = $wpdb->get_row( $wpdb->prepare("SELECT * FROM {$relation_table} WHERE id =%d",$integration_id), ARRAY_A );
        $action_provider=$integration['action_provider'];
   
        if(is_callable("awp_{$action_provider}_resend_data")){
            $response=call_user_func( "awp_{$action_provider}_resend_data", $log_id, $data,$integration );
        }
        else{
            $response['success']=false;
            $response['msg']="Log not editable";    
        }
        $relation_table = $wpdb->prefix . "automate_log";
        $log    = $wpdb->get_row( "SELECT * FROM {$relation_table} ORDER BY id DESC limit 1", ARRAY_A );
        $response['log_id']= !empty($log['id']) ? $log['id'] :'';
    }
    echo json_encode($response);
    wp_die();
}

/* Generates the list of connections */
public function automate_log_list_page() {
    if ( isset( $_GET['status'] ) ) {
        $status = sanitize_text_field($_GET['status']);
} ?>
<?php include AWP_INCLUDES.'/header.php'; ?>   
    <?php
            $list_table = new AWP_Log_Table();
            $list_table->prepare_items();
            $actionsdata=awp_actions_status();
    ?>





    <div id="root" style="width: 99%;">
      <div class="container pt-5 myrulesremoved" style="width: 100%;">
        <h4 style="padding-left: 10px;"> <?php esc_html_e("Overview",'automate_hub'); ?></h4>
        <div class="row align-items-stretch">

            <div class="c-dashboardInfo col-lg-4 col-md-8">
                <div class="wrap">
                  <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title"><?php esc_html_e("Actions Today",'automate_hub'); ?>
                  </h4>
                  <span class="hind-font caption-12 c-dashboardInfo__count"><?php 
                  $actionsdata_t = isset($actionsdata['T']) ? sanitize_text_field(count($actionsdata['T'])) :'0';
                  echo esc_html($actionsdata_t);  ?></span>
                </div>
            </div>

            <div class="c-dashboardInfo col-lg-4 col-md-8">
                <div class="wrap">
                  <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title"><?php esc_html_e("Actions Used",'automate_hub'); ?>
                  </h4>
                  <span class="hind-font caption-12 c-dashboardInfo__count"><?php 
                  $actionsdata_m = isset($actionsdata['M']) ? sanitize_text_field(count($actionsdata['M'])) :'0';
                  echo esc_html($actionsdata_m);  ?></span>

                </div>
            </div>




            <div class="c-dashboardInfo col-lg-4 col-md-8">
                <div class="wrap">
                  <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">
                      
                      <?php esc_html_e("Actions Allowed",'automate_hub'); ?>
                  </h4>
                  <span class="hind-font caption-12 c-dashboardInfo__count"><?php 
                  $actionsdata_a = isset($actionsdata['A']) ? sanitize_text_field($actionsdata['A']) :'';
                  echo esc_html($actionsdata_a);    ?></span>
                </div>
            </div>

        </div>
      </div>
    </div>







   <div class="wrap">
        <?php $list_table->show_notification( $list_table->response ); ?>
        <fieldset>
            <form method="get" action="<?php echo esc_url( admin_url( 'admin.php?page=automate_hub_log' ) ); ?>">
            <div class="bottom-line main-log">
		    <h3 class="sperse-app-page-title main-title"><?php esc_html_e( 'My Activity Log', 'automate_hub' ); ?></h3>
            <?php
                $list_table->search_box( '.', 'search_id' );
            ?> 

				<div class="log-buttons">
				<a href="<?php echo esc_url(admin_url( 'admin.php?page=import_log' )); ?>" class="page-title-action"><?php esc_html_e( 'Import', 'automate_hub' ); ?></a>
                <a href="#" class="page-title-action log-export-btn"><?php esc_html_e( 'Export', 'automate_hub' ); ?></a>
				</div>
                <input type="hidden" class="check" name="page" value="automate_hub_log"/>
                </div> 
                <div>
     				<?php $list_table->display(); ?>
				</div>
            </form>
        </fieldset>  
	  
    
        
    </div>
    <?php 
}

/* Handles log view */
public function automate_log_view( $id='' ) {
    if ( $id ) {
        require_once AWP_VIEWS . '/view_log.php';
    }
}

/* Relation Status Change awp_status */
public function awp_change_status( $id = '' ,$val=0) {
    global $wpdb;
    $relation_table = $wpdb->prefix . "awp_integration";
    $status_data    = $wpdb->get_row( $wpdb->prepare("SELECT * FROM {$relation_table} WHERE id = %d",$id), ARRAY_A );
    $status         = $status_data["status"];
    //this helps to ignore double hits on url thus prevents integration status bug
    if($val){
        if($val == 1){
            //if val is 1 means that on dashboard the integration is turned on when the userclicks on it
            //so we are changing status value accordingly
            $status=1;
        }
        else{
            //
            $status=0;
        }
    }
    if ( $status ) {
        $action_status = $wpdb->update( $relation_table,
            array('status' => false), array( 'id'=> $id ));
        }else{
        //for limited version start
        $usage_error=awp_usage_controller("A",'sperse',['W']);
        if(!empty($usage_error)){
                $response=json_decode($usage_error,true);
                if($response['success']==false){
                    $redirect_to = add_query_arg(
                        [
                            'head'=>$response['head'],
                            'msg' =>$response['msg'],
                            'type'=>'error',
                        ],
                        admin_url( 'admin.php?page=my_integrations')
                    );
                    
                    AWP_redirect( $redirect_to );
                    exit();
                }
        }
        //for limited version end
        $action_status = $wpdb->update( $relation_table,
            array('status' => true), array( 'id'=> $id ));
        }

    
        AWP_redirect( admin_url( 'admin.php?page=my_integrations' ) );
    }

/* Relation Status Change awp_status */
public function awp_duplicate( $id = '' ) {
     
    global $wpdb;
    $relation_table = $wpdb->prefix . "awp_integration";
    $status_data    = $wpdb->get_row($wpdb->prepare( "SELECT * FROM {$relation_table} WHERE id = %d",$id), ARRAY_A );
    if(!empty($status_data) && count($status_data)>0){
        $status_data['title'] = $status_data["title"].'_Copy';
        unset($status_data['id']);
        $result = $wpdb->insert( $relation_table, $status_data);
        $lastid = $wpdb->insert_id;
        //check if integration has childs if yes create those too
        $this->create_children($id,$lastid);
    }
    AWP_redirect( admin_url( 'admin.php?page=my_integrations' ));
    }

    public function create_children($original_integration_id,$new_parent_id){
        global $wpdb;
        $relation_table = $wpdb->prefix . "awp_integration";
        $query="SELECT * FROM {$relation_table} WHERE extra_data is not null";
        $status_data    = $wpdb->get_results( $query, ARRAY_A );
        $childrens=array();
        
        foreach ($status_data as $key => $integration) {
            if(!empty($integration['extra_data'])){
                $extra_data=json_decode($integration['extra_data'],true);
                if(isset($extra_data['parent'])){
                    $oldparent=$extra_data['parent']['integration_id'];
                    if($oldparent == $original_integration_id){
                        $extra_data['parent']['integration_id']=$new_parent_id;
                        $integration['extra_data']=json_encode($extra_data);
                        array_push($childrens, $integration);
                    }
                }
            }
            
        }

        //now create those childrens
        foreach ($childrens as $key => $child) {
            $child_title = isset($child["title"]) ? $child["title"]:'';
            $child['title'] = $child_title.'_Copy';
            unset($child['id']);
            $result = $wpdb->insert( $relation_table, $child);
        }

    }
}



