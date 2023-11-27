<?php
if(class_exists('AWP_List_Table')){return;}
if( !class_exists( 'WP_List_Table' ) ) require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

// Connection List Table class.
class AWP_List_Table extends WP_List_Table {
    private $siblings=array();
    private $counter=1;
    /* Construct function. Set default settings. */
    function __construct() {
        global $status, $page;
        //Set parent defaults
        parent::__construct( array(
            'ajax'     => FALSE,
            'singular' => 'integration',
            'plural'   => 'integrations',
        ));

    }

    /* Renders the columns. @since 1.0.0 */
    function column_default( $item, $column_name ) {

        switch ( $column_name ) {
            case 'id'             : $value = $item['id'             ]; break;
            case 'title'          : $value = $item['title'          ]; break;
            case 'form_provider'  : $value = $item['form_provider'  ]; break;
            case 'form_name'      : $value = $item['form_name'      ]; break;
            case 'action_provider': $value = $item['action_provider']; break;
            case 'task'           : $value = $item['task'           ]; break;
            case 'action'         : $value = $item['action'         ]; break;
            default: $value = '';
        }
        return apply_filters( 'awp_integration_table_column_value', $value, $item, $column_name );
    }

    /* Retrieve the table columns. @since 1.0.0
     * @return array $columns Array of all the list table columns. */
    function get_columns() {
        $columns = array(
            'cb'              => '<input type="checkbox" />',
            'title'           => esc_html__( 'Integration Name Spot'    , 'automate_hub' ),
            'form_provider'   => esc_html__( 'Form Source'         , 'automate_hub' ),
            'form_name'       => esc_html__( 'Form Name'           , 'automate_hub' ),
            'action_provider' => esc_html__( 'Destination Platform', 'automate_hub' ),
            'task'            => esc_html__( 'Platform Action Task', 'automate_hub' ),
            'id'              => esc_html__( 'Spot ID'      , 'automate_hub' ),
            'status'          => esc_html__( 'Active'              , 'automate_hub' )
        );
        return apply_filters( 'awp_integration_table_columns', $columns );
    }


    public function single_row( $item ) {        
        $allowed_html = array(
            'span'      => array(
                'onclick'  => array(),
                'class'    => array(),
            ),
            'td' => array('class'=>array(),'data-colname'=>array()),
            'a' => array('href'=>array(),'class'=>array()),
            'label' => array('class'=>array()),
            'input' => array('type'=>array(),'name'=>array(),'checked'=>array()),
        );
        $rowcolor=$this->counter%2==0?'white':'#f6f7f7';
        $this->counter++;
        echo wp_kses_post('<tr style="background-color:'.$rowcolor.'!important;">') ;
        $this->single_row_columns( $item );
        if(isset($this->siblings[$item['id']])){
            foreach ($this->siblings[$item['id']] as $key => $value) {
                echo wp_kses_post('<tr style="background-color:'.$rowcolor.'!important;">');
                echo wp_kses_post('<th></th>');
                echo wp_kses_post('<td></td>');
                echo wp_kses_post('<td></td>');
                echo wp_kses_post('<td></td>');
                echo wp_kses_post('<td>'.$this->column_action_provider($this->siblings[$item['id']][$key]).'</td>');
                echo wp_kses_post('<td>'.$this->column_task($this->siblings[$item['id']][$key]).'</td>');
                echo wp_kses_post('<td>'.$this->column_id($this->siblings[$item['id']][$key]).'</td>');
                echo wp_kses('<td>'.$this->column_status($this->siblings[$item['id']][$key]).'</td>', $allowed_html);

                echo wp_kses_post('</tr>');
            }
            
        }
        echo wp_kses_post('</tr>');

    }
    

    
    /* Renders status column */
    public function column_status($item) {
        if ($item['status']) {
            $actions = "
            <span data-actiontype=".$item['form_provider']." onclick='window.location=\"admin.php?page=my_integrations&val=1&action=status&id=".$item['id']."\"'  class='span_activation_checkbox'>
                <a class='a_activation_checkbox' href='?page=my_integrations&action=status&id=".$item['id']."'>
                <label class='switchCB'><input type='checkbox' name='status' checked=checked > <span class='sliderCB roundCB'></span></label>
                </a>
            </span>" ;
        }else{
            $actions = "
            <span data-actiontype=".$item['form_provider']." onclick='window.location=\"admin.php?page=my_integrations&val=2&action=status&id=".$item['id']." \"'  class='span_activation_checkbox'>
                <a class='a_activation_checkbox' href='?page=my_integrations&action=status&id=".$item['id']."'>
                <label class='switchCB'><input type='checkbox' name='status' ><span class='sliderCB roundCB'></span></label>
                </a>
            </span>" ;
        }
        return   $actions ;
    }

    protected function display_tablenav( $which ) {
        if ( 'top' === $which ) {
            wp_nonce_field( 'bulk-' . $this->_args['plural'] );
        }
        ?>
        <div class="tablenav <?php echo esc_attr( $which ); ?>">
         
            <?php if ( $this->has_items() ) : ?>
            <div class="alignleft actions bulkactions">
                <?php $this->bulk_actions( $which ); ?>
            </div>
                <?php
            endif;
            ?>

            <div style="display:inline">
                <a href="<?php echo admin_url( 'admin.php?page=my_integrations' ); ?>">
                    <button type="button" class="button action"><?php echo esc_html__( 'Clear Filters', 'automate_hub' ); ?></button>
                </a>
                
            </div>

            <?php 
            $this->extra_tablenav( $which );
            $this->pagination( $which );
            ?>
         
            <br class="clear" />
        </div>
        <?php
    }

    public function column_id( $item ) {
        return '<span title="Created on: '.sanitize_text_field($item['time']).'">'.sanitize_text_field($item['id']).'</span>';
    }

    /* Render the checkbox column. @since 1.0.0 @return string */
    public function column_cb( $item ) {
      $id =   isset($item['id']) ? sanitize_text_field($item['id']):'';
        return '<input type="checkbox" name="id[]" value="' . esc_attr(absint( $id )) . '" />';
    }

    public function column_form_provider( $item ) {
        $form_providers = awp_get_form_providers();
        if( array_key_exists( $item['form_provider'], $form_providers ) ) {
            if(!empty( $item['form_provider'])){
                $form_provider_img = AWP_IMAGES.'/icons/'.$item['form_provider'].'.png';
                $html = '<div class="column_integration_id"><span class="form_provider_text"><img src="'.esc_url($form_provider_img).'"  class="action_provider_img" width="50" height="50" />&nbsp;'.$form_providers[$item['form_provider']].'</span></div>';                 
                return $html;
              }
            return $form_providers[$item['form_provider']];
        } else {
            return esc_html__( 'Deactivated?', 'automate_hub');
        }
    }

    public function column_form_name($item){
        $form_name= isset($item['form_name']) ? sanitize_text_field($item['form_name']) :'';
        return '<span class="form_name_text">'.esc_html($form_name).'</span>';
    }

    /* Render the form name column with action links. @since 1.0.0 @return string */
    public function column_title( $item ) {
        $title = ! empty( $item['title'] ) ? $item['title'] : $item['form_provider'];
        $name = sprintf( '<span><strong>%s</strong></span>', esc_html__( $title ) );
        $row_actions = array();                  // Build all of the row action links.
        $submiturl=wp_nonce_url(
                add_query_arg(array('action'  => 'quickedit', 'id' => $item['id'],),
                    admin_url( 'admin.php?page=my_integrations' )
                ),
                'awp_quickedit_integration_nonce'
            );

        


        $row_actions['edit'] = sprintf(          // Edit.
            '<a href="%s" class="edit-integration-href" title="%s">%s</a>',
            add_query_arg(
                array('action' => 'edit', 'id'     => $item['id'],),
                admin_url( 'admin.php?page=my_integrations' )
            ),
            esc_html__( 'Edit This Integration', 'automate_hub' ),
            esc_html__( 'Edit', 'automate_hub' )
        );


        if(isset($this->siblings[$item['id']])){
            $row_actions['edit'].='<ul class="menu menu-edit-integration">';
            $row_actions['edit'].='<li class="menu-item">';

            $row_actions['edit'].= sprintf( '<a href="%s" class="menu-btn">
                        <span></span>
                        <span class="menu-text">Edit Spot ID %s</span>
                        </a>',
                     add_query_arg(
                        array('action' => 'edit', 'id'     => $item['id']),
                        admin_url( 'admin.php?page=my_integrations' )
                        ),
                        esc_html($item['id'])  
                );
            $row_actions['edit'].='</li>';
            foreach ($this->siblings[$item['id']] as $key => $value) {
                $id=$this->siblings[$item['id']][$key]['id'];
                $row_actions['edit'].='<li class="menu-item">';

                $row_actions['edit'].= sprintf( '<a href="%s" class="menu-btn">
                            <span></span>
                            <span class="menu-text">Edit Spot ID %s</span>
                            </a>',
                         add_query_arg(
                            array('action' => 'edit', 'id'     => $id),
                            admin_url( 'admin.php?page=my_integrations' )
                            ),
                            $id  
                    );
                $row_actions['edit'].='</li>';

            }
            $row_actions['edit'].='</ul>';    
        }


        $row_actions['quickedit'] = sprintf(    // Quick Edit.            
            '<a href="%s" data-integration-id="%d" data-integration-existing-name="%s" data-integration-submit-url="%s"class="awp-integration-quickedit" title="%s">%s</a>',
            esc_url($submiturl),
            $item['id'],
            $title,
            esc_url( $submiturl),
            esc_html__( 'Rename this integration', 'automate_hub' ),
            esc_html__( 'Rename', 'automate_hub' )
        );
        $row_actions['duplicate'] = sprintf(    // Duplicate.            
            '<a href="%s" class="awp-integration-duplicate" title="%s">%s</a>',
            wp_nonce_url(
                add_query_arg(array('action'  => 'duplicate', 'id' => $item['id'],),
                    admin_url( 'admin.php?page=my_integrations' )
                ),
                'awp_duplicate_integration_nonce'
            ),
            esc_html__( 'Duplicate this integration', 'automate_hub' ),
            esc_html__( 'Duplicate', 'automate_hub' )
        );
        $row_actions['delete'] = sprintf(       // Delete.    
            '<a href="%s" class="awp-integration-delete delete-integration-href" onclick="awp_delete_integration2(event)" title="%s">%s</a>',
            wp_nonce_url(
                add_query_arg(
                    array('action'  => 'delete','id' => $item['id'],),
                    admin_url( 'admin.php?page=my_integrations' )
                ),
                'awp_delete_integration_nonce'
            ),
            esc_html__( 'Delete this integration', 'automate_hub' ),
            esc_html__( 'Delete', 'automate_hub' )
        );

        if(isset($this->siblings[$item['id']])){
            $row_actions['delete'].='<ul class="menu menu-delete-integration" style="left:200px">';
            $row_actions['delete'].='<li class="menu-item">
                    <a onclick="awp_delete_integration2(event)" href="'.wp_nonce_url(
                add_query_arg(
                    array('action'  => 'delete','id' => $item['id'],),
                    admin_url( 'admin.php?page=my_integrations' )
                ),
                'awp_delete_integration_nonce'
            ).'" class="menu-btn deleterow">
                        <span></span>
                        <span class="menu-text">Delete Spot ID '.$item['id'].'</span>
                    </a>
                </li>';
            foreach ($this->siblings[$item['id']] as $key => $value) {
                $id=$this->siblings[$item['id']][$key]['id'];
                $row_actions['delete'].='<li class="menu-item">
                    <a onclick="awp_delete_integration2(event)" href="'.wp_nonce_url(
                add_query_arg(
                    array('action'  => 'delete','id' => $id,),
                    admin_url( 'admin.php?page=my_integrations' )
                ),
                'awp_delete_integration_nonce'
            ).'" class="menu-btn deleterow">
                        <span></span>
                        <span class="menu-text">Delete Spot ID '.$id.'</span>
                    </a>
                </li>';
                
            }
            $row_actions['edit'].='</ul>';
            
        }



        // Build the row action links and return the value.
        return $name . $this->row_actions( apply_filters( 'awp_integration_row_actions', $row_actions, $item ) );
    }

    /* Renders action provider column */
    public function column_action_provider( $item ) {
        $actions = awp_get_action_providers();
        $action  = isset( $actions[$item['action_provider']] ) ? $actions[$item['action_provider']] : '';
        if(!empty( $item['action_provider'])){
           $action_provider_img = AWP_IMAGES.'/icons/'.$item['action_provider'].'.png';
           $html = '<div class="column_integration_id"><span class="action_provider_text"><img src="'.esc_url($action_provider_img).'" class="action_provider_img" width="50" height="50" />&nbsp;'.$action.'</span></div>';
           return $html;
        }

        return $action;
    }

    /* Renders task column */
    public function column_task( $item ) {
        $tasks = awp_get_action_tasks( $item["action_provider"] );
        $task  = isset( $tasks[$item['task']] ) ? $tasks[$item['task']] : '';
        return $task;
    }

    /* Define bulk actions available for our table listing. @since 1.0.0 @return array */
    public function get_bulk_actions() {
        $actions = array('delete' => esc_html__( 'Delete', 'automate_hub'),);
        return $actions;
    }

    /* Process the bulk actions.  @since 1.0.0 */
    public function process_bulk_actions() {
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
        $action = ! empty( $_REQUEST['action'] ) ?sanitize_text_field( $_REQUEST['action'] ): false;
        if ( empty( $ids ) || empty( $action ) ) {
            return;
        }
        // Delete one or multiple relations - both delete links and bulk actions.
        if ( 'delete' === $this->current_action() ) {
            if (
                wp_verify_nonce( sanitize_text_field($_REQUEST['_wpnonce']), 'bulk-integrations' ) ||
                wp_verify_nonce(sanitize_text_field( $_REQUEST['_wpnonce']), 'awp_delete_integration_nonce' )
            ) {
                foreach ( $ids as $id ) {
                    $this->delete( $id );
                }
                AWP_redirect( admin_url( 'admin.php?page=my_integrations' ) );
                exit;
            }
        }
    }

    /* Message to be displayed when there are no relations. @since 1.0.0 */
    public function no_items() {
        printf(wp_kses(__( 'You haven\'t created any platform integration yet. <a href="%s">Click here to setup your first account.</a>?', 'automate_hub' ), array( 'a' => array('href' => array(),),)),
        admin_url( 'admin.php?page=automate_hub-new' )
        );
    }

    /* Sortable settings. */
    function get_sortable_columns() {
        return array(
            'title'           => array('title'          , TRUE),
            'form_provider'   => array('form_provider'  , TRUE),
            'form_name'       => array('form_name'      , TRUE),
            'action_provider' => array('action_provider', TRUE),   
            'task'            => array('task'           , TRUE),    
            'status'          => array('status'         , TRUE),    
            'id'              => array('id'             , TRUE),
        );
    }

    function fetch_table_data($per_page='',$paged=''){
        global $wpdb;
        $wpdb_table    = $wpdb->prefix . 'awp_integration';
        $alldata=$this->fetch_all_records($per_page,$paged);
        $query="Select * from {$wpdb_table} where extra_data is not null";
        $query_results = $wpdb->get_results( $query, ARRAY_A );
        

        $siblings=array();
        foreach ($query_results as $key => $row) {
            if(!empty($row['extra_data'])){
                $extra_data=$row['extra_data'];
                $extra_data=json_decode($extra_data,true);
                
                if(!isset($extra_data['soft_delete'])){
                    //if soft delete is not set in this record that means its valid and we need to show on the table

                    if(isset($siblings[$extra_data['parent']['integration_id']]) && is_array($siblings[$extra_data['parent']['integration_id']])){
                        $siblings[$extra_data['parent']['integration_id']]=$siblings[$extra_data['parent']['integration_id']];
                    }
                    else{
                        $siblings[$extra_data['parent']['integration_id']]=array();
                    }
                    
                    array_push($siblings[$extra_data['parent']['integration_id']], $row);    
                }
                
            }
            
        }
        $this->siblings=$siblings;
        return $alldata;

    }

    public function fetch_all_records($per_page='',$paged='') {
        global $wpdb;
        $wpdb_table    = $wpdb->prefix . 'awp_integration';
        $orderby       = ( isset( $_GET['orderby'] ) ) ? ( sanitize_sql_orderby($_GET['orderby'] )) : 'id';
        $order         = ( isset( $_GET['order'] ) ) ? ( sanitize_sql_orderby($_GET['order'] )) : 'DESC';
        $limit         = ( is_numeric( $per_page ) && is_numeric( $paged )) ? true : false;
        $query =  "SELECT * FROM  {$wpdb_table}";
        $user_query    = "SELECT * FROM  {$wpdb_table} where extra_data is null ORDER BY {$orderby} {$order}";

        if($limit){
            $user_query=$wpdb->prepare($user_query." LIMIT %d OFFSET %d",$per_page,$paged);
        }
        $selectedplatforms = isset($_REQUEST['selectedplatforms']) ? sanitize_text_field( wp_unslash( $_REQUEST['selectedplatforms'] ) ):'';
        $selectedformprovider = isset($_REQUEST['selectedformprovider'])?sanitize_text_field( wp_unslash( $_REQUEST['selectedformprovider'] ) ):'';
        $selectedformname = isset($_REQUEST['selectedformname'])?sanitize_text_field( wp_unslash( $_REQUEST['selectedformname'] ) ):'';

        $columns               = $this->get_columns();
        $search = isset($_REQUEST['s'])?sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ):'';
        if(!empty($search)){
                    $s = $search;
                    $first_column;
                    $remaining_columns  = array();
                    $basic_search_query = '';
                    foreach ( $columns as $column_name => $columnlabel ) {
                            if ( ("id" == $column_name) || ("cb" == $column_name) ) {
                                continue;
                            } else {
                                if ( empty( $first_column ) ) {
                                    $first_column       = $column_name;
                                    $basic_search_query = " WHERE {$column_name} LIKE '%" . $s . "%'";
                                } else {
                                    $basic_search_query .= " or {$column_name} LIKE '%" . $s . "%'"; }
                                }
                    }
                    $query_to_get  = $query . $basic_search_query;
                    $query_to_get .= " ORDER BY " . $orderby ."  ". $order;
                    $user_query = $query_to_get;
        }
        if(!empty($selectedplatforms)){

            if(!empty($search)){

                $query_to_get  = $wpdb->prepare($query." AND action_provider = %s",$selectedplatforms);
            }
            else{
                $query_to_get  = $wpdb->prepare($query." WHERE action_provider = %s",$selectedplatforms);
            }
            
            $query_to_get .= " ORDER BY {$orderby} {$order}";
            $user_query = $query_to_get;
        }
        if(!empty($selectedformprovider)){

            if(!empty($search)){
                $query_to_get  = $wpdb->prepare($query." AND form_provider = %s",$selectedformprovider);
            }
            else{
                $query_to_get  = $wpdb->prepare($query." WHERE form_provider = %s",$selectedformprovider);
            }
            
            $query_to_get .= " ORDER BY {$orderby} {$order}";
            $user_query = $query_to_get;
        }
        if(!empty($selectedformname)){

            if(!empty($search)){
                $query_to_get  = $wpdb->prepare($query." AND form_name = %s",$selectedformname);
            }
            else{
                $query_to_get  = $wpdb->prepare($query." WHERE form_name = %s",$selectedformname);
            }
            
            $query_to_get .= " ORDER BY {$orderby} {$order}";
            $user_query = $query_to_get;

        }

        $query_results = $wpdb->get_results( $user_query, ARRAY_A );
        return $query_results;
    }

    //Query, filter data, handle sorting, pagination, and any other data-manipulation required prior to rendering
    public function prepare_items() {
        $this->process_bulk_actions();          // Process bulk actions if found.
        $per_page              = 20;
        $paged = isset($_REQUEST['paged']) ? max(0, intval(sanitize_text_field($_REQUEST['paged']) -1) * $per_page) : 0;
        $count                 = $this->count();
        $columns               = $this->get_columns();
        $hidden                = array();
        $sortable              = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $table_data            = $this->fetch_table_data($per_page,$paged);

        $this->items           = $table_data;

        

        $this->set_pagination_args(
            array(
                'total_items' => $count,
                'per_page'    => $per_page,
                'total_pages' => ceil( $count / $per_page ),
            )
        );
    }



    /* Handles delete */
    public function delete( $id='' ) {
        global $wpdb;
        $relation_table = $wpdb->prefix.'awp_integration';
        //detached childs if any
        $this->detached_child($id);
        //$action_status  = $wpdb->delete( $relation_table, array( 'id' => $id ) );
        //if it is rssfeed delete cron job as well
        $query=$wpdb->prepare("SELECT * from `$relation_table` where `id`=%d AND `form_provider` = %s",$id,'rssfeed');
        $isrss    = $wpdb->get_results( $query, ARRAY_A );
        $rss_actions_list=get_option('awp_rss_actions_list');
        $rss_actions_list=empty($rss_actions_list)?[]:unserialize($rss_actions_list);
        $topopelement='';
   
        if(count($isrss)){

            foreach ($rss_actions_list as $key => $rssdetail) {
                if($id==$rssdetail[0]){

                    $timestamp = wp_next_scheduled( 'bl_awp_rss_cron_hook_'.$id,$rssdetail[1]);
                    wp_unschedule_event( $timestamp, 'bl_awp_rss_cron_hook_'.$id,$rssdetail[1]);        
                    $topopelement=$key;
                }
                
            }

            if(!empty($topopelement)){
                unset($rss_actions_list[$topopelement]);
            }
            update_option('awp_rss_actions_list',serialize($rss_actions_list));
        }
        
        
        
        //soft delete
        $action_status = $wpdb->update( $relation_table,
                            array(
                                'extra_data'           => json_encode(array("soft_delete"=>true)),
                                'status'               =>false
                            ),
                            array(
                                'id' => $id
                            )
                        );
        return $action_status;
    }

    function detached_child($parent_id){
        global $wpdb;
        $relation_table = $wpdb->prefix . "awp_integration";
        $query= "SELECT * FROM {$relation_table} WHERE extra_data is not null";
        $status_data    = $wpdb->get_results( $query, ARRAY_A );
        
        foreach ($status_data as $key => $integration) {
            if(!empty($integration['extra_data'])){
                $extra_data=json_decode($integration['extra_data'],true);
                if(isset($extra_data['parent'])){
                    $oldparent = !empty($extra_data['parent']['integration_id']) ? $extra_data['parent']['integration_id'] :'';
                    if($oldparent == $parent_id){

                        $result = $wpdb->update( $relation_table,
                            array(
                                'extra_data'           => NULL,
                            ),
                            array(
                                'id' => $integration['id']
                            )
                        );

                    }
                }
            }
            
        }
    }


    /* Handles connection count */
    public function count() {
        global $wpdb;
        $relation_table = $wpdb->prefix.'awp_integration';
        $count          =  $wpdb->get_var("SELECT COUNT(*) FROM  {$relation_table}" );
        return $count;
    }


}
