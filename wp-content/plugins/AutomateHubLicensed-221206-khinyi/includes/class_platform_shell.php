<?php
if(class_exists('AWP_Platform_Shell_Table')){return;}
if( !class_exists( 'WP_List_Table' ) ) require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

// Connection List Table class.
class AWP_Platform_Shell_Table extends WP_List_Table {

    /**
     * Construct function
     * Set default settings.
     */
    private $wpdb;
    private $platform_name;
    private $table_name;
    private $row_action;
    private $first_col;
    private $table_columns;

    function __construct($platform_name) {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name=$this->wpdb->prefix.'awp_platform_settings';
        $this->platform_name=$this->platform_name_special_handling($platform_name);
        $this->row_action=array();
        // coloum values to show in the drop down account selection in new integration page

        add_action( 'wp_ajax_awp_get_platform_accounts',array( $this, 'awp_get_platform_detail_by_name'), 10, 0 );
        
    }

    public function platform_name_special_handling($platform_name){
        // googleauth is the platform name which is used in google sheets as well as google calendar so redirecting to googleauth was breaking the view 
        if($platform_name == 'googleauth'){
            $platform='googlesheets';
        }
        else{
            $platform=$platform_name;
        }

        return $platform;
    }

    function awp_get_platform_detail_by_id($platform_id){
    
        global $wpdb;
        if(empty($platform_id) || !is_numeric($platform_id)){
            return '';
        }
        $data = array();
        $query= $wpdb->prepare("SELECT* FROM  {$this->table_name} where id=%d",$platform_id);
        $results = $wpdb->get_results( $query, OBJECT );
        $results=(count($results) ? $results[0]: false);
        return $results;
    }

    function awp_get_platform_detail_by_name(){
        if ( ! current_user_can('administrator') ){
            wp_die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
        }
        // Security Check
        if (!empty($_POST['_nonce']) && ! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
            wp_die( esc_html__( 'Security check Failed', 'automate_hub' ) );
        }

        if(!isset($_POST['platform'])){
            wp_die( esc_html__( 'Invalid Request', 'automate_hub' ) );
        }

        $platform_name = isset($_POST['platform']) ? sanitize_text_field($_POST['platform']):'';
        $col=$this->selectbox_col_identifier($platform_name);

        if(empty($col)){
            wp_send_json_success( array('isConnected'=>true,'accounts'=>false) );
        }
        global $wpdb;
        $data = array();
        $query= $wpdb->prepare("SELECT id as id, {$col} as col,active_status as active_status FROM  {$this->table_name} where platform_name='%s'",$platform_name);
        $results = $wpdb->get_results( $query, OBJECT );
        foreach ($results as $key => $value) {
                if( !empty($value->active_status) && ($value->active_status=='true')){
                    $data[$value->id] = $value->col;
                }   
        }
        $is_connected=(count($data)?true:false);
        $data=array('isConnected'=>$is_connected,'accounts'=>$data);
        
        wp_send_json_success( $data );
    }

    function awp_add_new_spot($integration_id,$platform_id){
        $spot_counter=array();
        $details=$this->awp_get_platform_detail_by_id($platform_id);
        if(!empty($details->spots) || get_option($details->spots) != '' ){
            $spot_counter=unserialize($details->spots);
        }
        $spot_counter[]=$integration_id;
        $_POST['id']=(isset($details->id) ? $details->id:'');
        $this->save_platform(['spots'=>serialize($spot_counter)]);
    }


    function awp_get_spot_count($data){
        $data = json_decode(json_encode($data), true);
        if(empty($data['spots']) || $data['spots'] == '' ){
            return array();
        }
        else{
            return unserialize($data['spots']);
        }
    }

    function awp_get_spot_count_by_id($platform_id){
        $data=$this->awp_get_platform_detail_by_id($platform_id);
        return $data;
    }

    function initiate_table($data){

        if(isset($data['table-cols'])){
             
            $columns = isset($data['table-cols']) ? $data['table-cols'] :array();
            
            $this->table_columns =$columns;
            $this->first_col=$this->get_first_keyname($columns);
            
        }
        global $status, $page;
        
        //Set parent defaults
        parent::__construct( array(
            'ajax'     => False,
            'singular' => $this->platform_name.'-account',
            'plural'   => $this->platform_name.'-accounts',
        ) );
    }

    function display_table(){
        if($this->count()){
            $this->display();    
        }
        
    }

    function make_where_condition($values){
        $temp=array();
        foreach ($values as $key => $value) {
            $temp[]=$key.'="'.$value.'"';
        }

        $string=implode(" AND ", $temp);

        return $string;
    }

    function insert_or_update($data){
        global $wpdb;
        $where_clause=' where '.$this->make_where_condition($data);
        $query="SELECT * from ".$this->table_name.$where_clause;
        $res=$wpdb->get_results($query);
        if(count($res)){
            $res=$wpdb->update($this->table_name,$data,array('id'=>$res[0]->id));
        }
        else{
            $res=$wpdb->insert($this->table_name, $data);
        }

        return $res;
    }

    function insert_or_update_platform($data){
        global $wpdb;
        if(isset($data['id'])){
            $query=$wpdb->prepare("SELECT * from {$this->table_name} where id=%d",$data['id']);
            $res=$wpdb->get_results($query);
            if(count($data)){
                unset($data['id']);
                return $wpdb->update($this->table_name,$data,array('id'=>$res[0]->id));
            }

        }
        else{

            //for limited version start
            $usage_error=awp_usage_controller("A",$data['platform_name'],["Y"]);
            if(!empty($usage_error)){
                $response=json_decode($usage_error,true);
                if($response['success']==false){
                    $redirect_to = add_query_arg(
                        [
                            'tab'=>$data['platform_name'],
                            'head'=>$response['head'],
                            'msg' =>$response['msg'],
                            'type'=>'error',
                        ],
                        admin_url( 'admin.php?page=automate_hub')
                    );
                    
                    AWP_redirect( $redirect_to );
                    exit();
                }
            }
            //for limited version end

            return $wpdb->insert($this->table_name, $data);
        }
    }

    function save_platform($data){
        
        if(isset($_POST['id']) && !empty($_POST['id'])){
            $data['id']=  sanitize_text_field($_POST['id']);
        }
        $data['platform_name']=$this->platform_name;
        $data['active_status']='true';


        $this->insert_or_update_platform($data);


    }


    function column_default( $item, $column_name ) {

        switch ( $column_name ) {
            case 'id':
                $value = $item['id'];
                break;
            case 'platform_name':
                $value = $item['platform_name'];
                break;
            case 'url':
                $value = $item['url'];
                break;
            case 'api_key':
                $value = $item['api_key'];
                break;
            case 'email':
                $value = $item['email'];
                break;
            case 'client_id':
                $value = $item['client_id'];
                break;
            case 'client_secret':
                $value = $item['client_secret'];
                break;
            case 'account_name':
                $value = $item['account_name'];
                break;
            case 'sync_contacts':
                $value = $item['sync_contacts'];
                break;
            case 'activity_time':
                $value = $item['activity_time'];
                break;
            case 'spots':
                $value = $item['spots'];
                break;
            default:
                $value = '';
        }

        return apply_filters( 'awp_sperse_table_column_value', $value, $item, $column_name );
    }



    function get_columns() {
        if(!count($this->table_columns)){
            //show all default cols;
            $col= array(
                'url'    => esc_html__( 'Url', 'automate_hub' ),
                'api_key'    => esc_html__( 'Api Key', 'automate_hub' ),
                'email'    => esc_html__( 'Api Key', 'automate_hub' ),
                'client_id'    => esc_html__( 'Api Key', 'automate_hub' ),
                'client_secret'    => esc_html__( 'Api Key', 'automate_hub' ),
                'account_name'    => esc_html__( 'Api Key', 'automate_hub' ),
                'sync_contacts'    => esc_html__( 'Api Key', 'automate_hub' ),
                'active_status'   => esc_html__( 'Active', 'automate_hub' ),                
            );
        }
        
        
        $this->table_columns =array('cb' => '<input type="checkbox" />') + $this->table_columns;
        $columns = $this->table_columns;

       return $columns;
    }

    function get_sortable_columns() {
        return array(
            'id'           => array('id', TRUE),
            'active_status'   => array('active_status', TRUE),
        );
    }


     function change_active_status($account_id,$status){
        global $wpdb;
        $status=($status=="true"?'true':'false');
        $relation_table = $this->table_name;
        $action_status = $wpdb->update( $relation_table,
                array('active_status' => $status,),
                array( 'id'=> $account_id )
        );
    }

    public function other_actions(){
        if(isset(($_GET['tab'])) && sanitize_text_field($_GET['tab'])!=$this->platform_name){
            return;
        }
        if( isset($_GET['active_status']) && !empty($_GET['active_status']) && isset($_GET['id']) && !empty($_GET['id'])){
            $account_id=sanitize_text_field($_GET['id']);
            $status=sanitize_text_field($_GET['active_status']);
            $this->change_active_status($account_id,$status);
        }
    }



    function edit_action($item){

        
        $item=array_merge($item,
                    array(
                        'tab'=>$this->platform_name,
                        'action' => 'edit',
                        'id'     => $item['id']
                    )
                );

        $this->row_action['edit'] = sprintf(
            '<a href="%s" title="%s">%s</a>',
            add_query_arg(
                $item,
               admin_url( 'admin.php?page=automate_hub&tab='.$this->platform_name )
            ),
            esc_html__( 'Edit This Integration', 'automate_hub' ),
            esc_html__( 'Edit', 'automate_hub' )
        );

    }

    function delete_action($item){

        // Delete.
        $this->row_action['delete'] = sprintf(
            '<a href="%s" onclick="awp_delete_integration(event,'.count($this->awp_get_spot_count($item)).')" class="awp-integration-delete" title="%s">%s</a>',
            wp_nonce_url(
                add_query_arg(
                    array(
                        'tab'=>$this->platform_name,
                        'action'  => 'delete',
                        'id' => $item['id'],
                    ),
                    admin_url( 'admin.php?page=automate_hub&tab='.$this->platform_name )
                ),
                'awp_delete_'.$this->platform_name.'-account_nonce'
            ),
            esc_html__( 'Delete this integration', 'automate_hub' ),
            esc_html__( 'Delete', 'automate_hub' )
        );


    }


    public function column_cb( $item ) {
        $id = isset($item['id']) ? sanitize_text_field($item['id']) :'';
        return '<input type="checkbox" name="id[]" value="' . absint( $id ) . '" />';
    }

    public function column_spots( $item ) {
        return count($this->awp_get_spot_count($item));
    }
    public function column_url( $item ) {

        $url = ! empty( $item['url'] ) ? sanitize_url($item['url']) : $item['url'];
        // Build the row action links and return the value.
        $this->col_actions('url',$item);
        return $url . $this->row_actions( apply_filters( 'awp_googlesheet_row_actions', $this->row_action, $item ) );
    }

    public function column_account_name( $item ) {

        $url = ! empty( $item['account_name'] ) ? sanitize_text_field($item['account_name']) :'';
        // Build the row action links and return the value.
        $this->col_actions('account_name',$item);
        return $url . $this->row_actions( apply_filters( 'awp_googlesheet_row_actions', $this->row_action, $item ) );
    }

    public function column_email( $item ) {

        $url = ! empty( $item['email'] ) ? sanitize_email($item['email']) : $item['email'];
        // Build the row action links and return the value.
        $this->col_actions('email',$item);
        return $url . $this->row_actions( apply_filters( 'awp_googlesheet_row_actions', $this->row_action, $item ) );
    }

    public function column_api_key( $item ) {

        $api_key = ! empty( $item['api_key'] ) ? sanitize_text_field($item['api_key']) : $item['api_key'];

        $api_key=substr($api_key,0,3)."-XXXXXX-XXXXXX-XXXXXX-".substr($api_key, -3);
        // Build the row action links and return the value.
        $this->col_actions('api_key',$item);
        return $api_key . $this->row_actions( apply_filters( 'awp_googlesheet_row_actions', $this->row_action, $item ) );
    }

    public function column_client_id( $item ) {

        $url = ! empty( $item['client_id'] ) ? sanitize_text_field($item['client_id']) : $item['client_id'];
        // Build the row action links and return the value.
        $this->col_actions('client_id',$item);
        return $url . $this->row_actions( apply_filters( 'awp_googlesheet_row_actions', $this->row_action, $item ) );
    }

    public function column_active_status($item) {

        $id = $item['id'];

        if ($item['active_status'] == "true") {

            $actions = "<span onclick='window.location=\"admin.php?page=automate_hub&tab=".$this->platform_name."&action=active_status&active_status=false&id=".$item['id']."\"'  class='span_activation_cheackbox'  ><a class='a_activation_cheackbox' href='?page=automate_hub&tab=".$this->platform_name."&action=active_status&active_status=false&id=".$item['id']."'><label  class='switch'><input type='radio' name='status".$id."' checked=checked><span class='slider'></span></label></span>";
            
        }else{
            
            $actions = "<span onclick='window.location=\"admin.php?page=automate_hub&tab=".$this->platform_name."&action=active_status&active_status=true&id=".$item['id']."\"'  class='span_activation_cheackbox'  ><a class='a_activation_cheackbox' href='?page=automate_hub&tab=".$this->platform_name."&action=active_status&active_status=true&id=".$item['id']."'><label  class='switch'><input type='radio' name='status'><span class='slider'></span></label></span>";
            
        }
        return   $actions ;
    }

    public function column_sync_contacts($item) {

        $id = $item['id'];

        if ($item['active_status'] == "true") {
            
            if ($item['sync_contacts'] != 0) {
                
                $actions ="<span onclick='window.location=\"admin.php?page=automate_hub&tab=".$this->platform_name."&syncbtnhit=true"."&action=sync_contacts&id=".$id."&sync_contacts=".$item['sync_contacts']."\"'  class='span_activation_cheackbox'  ><a class='a_activation_cheackbox' href='?page=automate_hub&tab=".$this->platform_name."&action=sync_contacts&id=".$id."&sync_contacts=".$item['sync_contacts']."'><label class='switchCB'><input type='checkbox' name='sync_contact".$id."' checked><span class='sliderCB roundCB'></span></label></span>";    
            
            }else{
                
                $actions ="<span onclick='window.location=\"admin.php?page=automate_hub&tab=".$this->platform_name."&syncbtnhit=true"."&action=sync_contacts&id=".$id."&sync_contacts=".$item['sync_contacts']."\"'  class='span_activation_cheackbox'  ><a class='a_activation_cheackbox' href='?page=automate_hub&tab=".$this->platform_name."&action=sync_contacts&id=".$id."&sync_contacts=".$item['sync_contacts']."'><label class='switchCB'><input type='checkbox' name='sync_contact".$id."' ><span class='sliderCB roundCB'></span></label></span>";    
                
            }            
                
        } else {
            $actions ="<label class='switchCB'><input type='checkbox' disabled><span class='sliderCB roundCB'></span></label>";
        }
        return   $actions ;
    }

    public function col_actions($col_name,$item){
        $this->row_action=array();
        if(( $col_name == $this->first_col)){
            if($this->platform_name != 'googlesheets'){
                $this->edit_action($item);
            }
            
            $this->delete_action($item);
        }
        

    }



     /**
     * Message to be displayed when there are no relations.
     *
     * @since 1.0.0
     */
    public function no_items() {
        printf(
            wp_kses(
                esc_html__( 'Whoops, you haven\'t connected your account yet.', 'automate_hub' ),
                array(
                    'a' => array(
                        'href' => array(),
                    ),
                )
            ),
            admin_url( 'admin.php?page=automate_hub&tab='.$this->platform_name.'&action=new' )
        );
    }


    public function fetch_table_data($per_page='',$paged='') {
        global $wpdb;
        $wpdb_table    = $this->table_name;
        $orderby       = ( isset( $_GET['orderby'] ) ) ? sanitize_sql_orderby( $_GET['orderby'] ) : 'id';
        $order         = ( isset( $_GET['order'] ) ) ? sanitize_text_field( $_GET['order'] ) : 'DESC';
        $limit         = ( is_numeric( $per_page ) && is_numeric( $paged )) ? 'LIMIT '.$per_page.' OFFSET '.$paged : '';

        $user_query    = $wpdb->prepare("SELECT * FROM {$wpdb_table} where platform_name='%s' ORDER BY %s %s %s ",$this->platform_name,$orderby,$order,$limit);
        $query_results = $wpdb->get_results( $user_query, ARRAY_A );
        return $query_results;
    }


    public function fetch_active_platform() {
        global $wpdb;

        $wpdb_table    = $this->table_name;
        $orderby       = ( isset( $_GET['orderby'] ) ) ? sanitize_sql_orderby( $_GET['orderby'] ) : 'id';
        $order         = ( isset( $_GET['order'] ) ) ? sanitize_text_field( $_GET['order'] ) : 'DESC';

        $user_query    =  $wpdb->prepare("SELECT * FROM  {$wpdb_table} where platform_name='%s' AND active_status='true' ORDER BY %s  %s", $this->platform_name,$orderby, $order) ;
        $query_results = $wpdb->get_results( $user_query, ARRAY_A );
        return $query_results;
    }


    public function get_first_keyname(array $arr) {
        foreach($arr as $key => $unused) {
            return $key;
        }
        return NULL;
    }
    public function prepare_items() {
        // Process bulk actions if found.
        $this->process_bulk_actions();
        $this->other_actions();
        
        $per_page              = 5;
        $paged = isset($_REQUEST['paged']) ? max(0, intval(sanitize_text_field($_REQUEST['paged']) -1) * $per_page) : 0;
        $count                 = $this->count();
        $columns               = $this->get_columns();
        $hidden                = array();
        $sortable              = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $table_data            = $this->fetch_table_data($per_page,$paged);
        $this->items           = $table_data;
        $this->admin_header();

        $this->set_pagination_args(
            array(
                'total_items' => $count,
                'per_page'    => $per_page,
                'total_pages' => ceil( $count / $per_page ),
            )
        );

    }



    public function admin_header() {
        $page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
        if( 'automate_hub' != $page )
            return;

        echo '<style type="text/css">';
        echo '.wp-list-table .column-id { width: 10%; }';
        echo '.wp-list-table .column-title { width: 16%; }';
        echo '.wp-list-table .column-form_provider { width: 16%; }';
        echo '.wp-list-table .column-form_name { width: 16%; }';
        echo '.wp-list-table .column-action_provider { width: 16%; }';
        echo '.wp-list-table .column-action_name { width: 16%; }';
        echo '.wp-list-table .column-status { width: 10%; }';
        echo '</style>';
    }


    public function get_bulk_actions() {

        $actions = array(
            'delete' => esc_html__( 'Delete', 'automate_hub' ),
        );

        return $actions;
    }

    public function process_bulk_actions() {
        $cat = !empty( $_REQUEST['cat'] ) ? 'cat='.sanitize_text_field( $_REQUEST['cat']) : '';
        if(isset( $_REQUEST['id'] ) && is_array($_REQUEST['id'])){
           $ids = array_map('sanitize_text_field',$_REQUEST['id']);
        }
        
        else if(isset( $_REQUEST['id'] ) && !is_array($_REQUEST['id'])){
            $ids = sanitize_text_field( $_REQUEST['id']);

        }
        else{
            $ids=array();
        }
        
        if ( ! is_array( $ids ) ) {
            $ids = array( $ids );
        }

        $ids    = array_map( 'absint', $ids );
        $action = ! empty( $_REQUEST['action'] ) ? sanitize_text_field( $_REQUEST['action']) : false;
        if ( empty( $ids ) || empty( $action ) ) {

            return;
        }

        //special handling for connected apps tab as bulk delete giving actions value to -1 so in order to work we are hardcoding it
        // $action2 = ! empty( $_REQUEST['action2'] ) ? sanitize_text_field( $_REQUEST['action2']) : false;
        // if($action2=='delete'){
        //     $_REQUEST['action']='delete';
        // }
        

        // Delete one or multiple relations - both delete links and bulk actions.
        if ( 'delete' == $this->current_action() ) {
            if (
                wp_verify_nonce( sanitize_text_field($_REQUEST['_wpnonce']),'bulk-'.$this->platform_name.'-accounts') ||
                wp_verify_nonce( sanitize_text_field($_REQUEST['_wpnonce']), 'awp_delete_'.$this->platform_name.'-account_nonce' )
            ) {


                foreach ( $ids as $id ) {
                    $this->delete( $id );
                }

      

                AWP_redirect( admin_url( 'admin.php?page=automate_hub&tab='.$this->platform_name.'&'.$cat ) );

                exit;
            }
    
        }

        if ( 'delete' == $this->current_action() ) {
            if (
                wp_verify_nonce( sanitize_text_field($_REQUEST['_wpnonce']),'bulk-'.$this->platform_name.'-accounts') ||
                wp_verify_nonce( sanitize_text_field($_REQUEST['_wpnonce']), 'awp_delete_'.$this->platform_name.'-account_nonce' )
            ) {


                foreach ( $ids as $id ) {
                    $this->delete( $id );
                }

      

                AWP_redirect( admin_url( 'admin.php?page=automate_hub&tab='.$this->platform_name.'&'.$cat ) );

                exit;
            }
    
        }


    }

    public function selectbox_col_identifier($platform){
        $cols=[
            'googlesheets'=>'email',
            'googledrive'=>'email',
            'contactsplus'=>'email',
            'teachable'=>'email',
            'shopify'=>'url',            
            'postmark'=>'email'            
        ];

        $col_name=isset($cols[$platform])? sanitize_text_field($cols[$platform]):'account_name';

        return $col_name;
    }


    public function count() {
        global $wpdb;

        $relation_table = $this->table_name;
        $count          =  $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$relation_table} where platform_name='%s'",$this->platform_name ));

        return $count;
    }


    public function delete( $id='' ) {
        global $wpdb;
        $relation_table = $this->table_name;
        $action_status  = $wpdb->delete( $relation_table, array( 'id' => $id ) );
        return $action_status;
    }

   function extra_tablenav($which){
    echo wp_kses_post("<a href='admin.php?page=automate_hub-new'>
        <div class='create-int-awp button button-primary'>".esc_html__( 'Create Integration', 'automate_hub' )."</div>
    </a>");
   }
}
