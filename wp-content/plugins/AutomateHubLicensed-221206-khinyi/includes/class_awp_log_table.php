<?php
if(class_exists('AWP_Log_Table')){return;}
if( !class_exists( 'WP_List_Table' ) ) require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

// Connection List Table class.
class AWP_Log_Table extends WP_List_Table {

    /* Construct function Set default settings.*/
    function __construct() {
        global $status, $page;
        //Set parent defaults
        parent::__construct( array(
            'ajax'     => FALSE,
            'singular' => 'log',
            'plural'   => 'logs',
        ) );
    }

    /* Renders the columns. @since 1.0.0 */
    function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            case 'id'              : $value = $item['id'              ]; break;
            case 'response_code'   : $value = $item['response_code'   ]; break;
            case 'response_message': $value = $item['response_message']; break;
         // case Form & Form Name to be added
         // case Platform Name to be added   
            case 'integration_id'  : $value = $item['integration_id'  ]; break;
            case 'request_data'    : $value = $item['request_data'    ]; break;
            case 'response_data'   : $value = $item['response_data'   ]; break;
            case 'action'          : $value = $item['action'          ]; break;
            case 'time'            : $value = $item['time'            ]; break;
            $value = '';
        }
        return apply_filters( 'automate_log_table_column_value', $value, $item, $column_name );
    }

    public function show_notification( $response ) {
    if ( ! empty( $response['error'] ) ) {
        $this->show_message( $response['error'], true ); } elseif ( ! empty( $response['success'] ) ) {
        $this->show_message( $response['success'] ); }
    }
    public function show_message( $message, $errormsg = false ) {
    if ( empty( $message ) ) {
        return; }
        if ( $errormsg ) {
            echo wp_kses_data("<div class='sp_error_message'>{$message}</div>");
        } else {
            echo wp_kses_data("<div class='sp_success_message'>{$message}</div>");
        }
    }

    /* Display records listing. */
    public function show_listing() { 
             $this->show_notification( $this->response ); ?>
            <fieldset>
            <form method="GET" action="<?php echo esc_url( admin_url( 'admin.php?page=automate_hub_log' ) ); ?>">
            <?php
                $this->search_box( 'search', 'search_id' );
                $this->display();
            ?>
            </form>
            </fieldset>
        <?php 

     }

    /* Retrieve the table columns. @since 1.0.0  @return array $columns Array of all the list table columns. */
    function get_columns() {
        $columns = array(
            'cb'                => '<input type="checkbox" />',
            'response_code'     => esc_html__( 'Status Code'      , 'automate_hub'),
            'response_message'  => esc_html__( 'Response'         , 'automate_hub'),
            'integration_id'    => esc_html__( 'Integration ID'   , 'automate_hub'),
            'request_data'      => esc_html__( 'Form Data Sent'   , 'automate_hub'),
            'response_data'     => esc_html__( 'Response Received', 'automate_hub'),
            'time'              => esc_html__( 'Log Time'         , 'automate_hub'),
            'ip'                => esc_html__( 'IP Address'       , 'automate_hub'),

        );
        return apply_filters( 'automate_log_table_columns', $columns );
    }

    function column_time($record){
       $timestamp = $record['time'];
       $date1 = new DateTime($timestamp, new DateTimeZone('EDT'));
        $date1->setTimezone(new DateTimeZone('America/Phoenix'));
        $end_date_time =  $date1->format('Y-m-d h:i:s A'); // 2011-11-10 15:17:23 -0500
        return $end_date_time;
    }

    function column_ip($record){
        
       $ip = isset($record['ip']) ? $record['ip'] :'' ;
       $ip=(strpos($ip, ','))? explode(',', $ip)[0]: $ip;
       if(!empty($ip)){
            $ip_url = add_query_arg( 'query', $ip, 'https://search.arin.net/rdap/' );
            return sprintf('<a href="%s" target="_blank">%s</a>',esc_url($ip_url), esc_html($ip)) ;
       }
        return $ip;
    }


    function column_integration_id($record){
       $int_id = isset($record['integration_id']) ? sanitize_text_field($record['integration_id']) :'';

        $int_data =   get_integration_by_id($int_id);

        $form_provider = !empty($int_data['form_provider']) ? sanitize_text_field($int_data['form_provider']) : '';

        $action_provider = !empty($int_data['action_provider']) ? sanitize_text_field($int_data['action_provider']) : '';

        if(!empty($form_provider) && !empty($action_provider)){
            $form_provider_img = AWP_IMAGES.'/icons/'.$form_provider.'.png';
            $action_provider_img = AWP_IMAGES.'/icons/'.$action_provider.'.png';
            
          $html =  '<div class="column_integration_id">
                <span>'.$int_id.'</span>
                    <img src="'.esc_url($form_provider_img).'" class="form_provider_img" width="50" height="50" /><span class="int_arrow">></span>
                    <img src="'.esc_url($action_provider_img).'" class="action_provider_img" width="50" height="50" />
            </div>';

                    return $html;


        } 
        return $int_id=="0"?wp_kses_post('Sperse <img src="'.AWP_IMAGES.'/icons/sperse.png'.'" class="form_provider_img" width="50" height="50" />') :$int_id;    

    }

    /* Render the checkbox column.  @since 1.0.0 @return string */
    public function column_cb( $item ) {
        $item_id = isset($item['id']) ? sanitize_text_field($item['id']) :'';
        return '<input type="checkbox" name="log_id[]" value="' . absint(esc_html($item_id)) . '" />';
    }

    /* Render the form name column with action links. @since 1.0.0  @return string */
    public function column_response_code( $item ) {
        $name = ! empty( $item['response_code'] ) ? $item['response_code'] : _e( 'Unknown', 'automate_hub' );
        $positive_reponse_codes=array(200,201,202,203,204);
        if (in_array($item["response_code"], $positive_reponse_codes))
           { $name = sprintf( '<div id="codeone"><strong>%s</strong></div>', esc_html__( $name ));} 
        else
           { $name = sprintf( '<div id="codetwo"><strong>%s</strong></div>', esc_html__( $name )); } 

        $row_actions = array();                       // Build all of the row action links.
        $row_actions['view'] = sprintf(               // Edit.
           '<a href="%s" title="%s">%s</a>',
            add_query_arg(array('action' => 'view', 'id' => $item['id'],), admin_url( 'admin.php?page=automate_hub_log')),
            esc_html__( 'View', 'automate_hub' ),
            esc_html__( 'View', 'automate_hub' )
        );
        // Build the row action links and return the value.
        return $name . $this->row_actions( apply_filters( 'awp_integration_row_actions', $row_actions, $item ) );
    }

    public function column_request_data( $item ) {
        $allowed_html = array(
            'a' => array(
                'href' => array(),
                'title' => array()
            ),
            'br' => array(),
            'em' => array(),
            'strong' => array(),
        );

        $request_data = isset($item["request_data"]) ? substr( stripslashes($item["request_data"]), 0, 120 ) : '';
         echo wp_kses($request_data,$allowed_html). "...";
    }

    public function column_response_data( $item ) {
        $allowed_html = array(
            'a' => array(
                'href' => array(),
                'title' => array()
            ),
            'br' => array(),
            'em' => array(),
            'strong' => array(),
        );

        $response_data = isset($item["response_data"]) ? substr( stripslashes($item["response_data"]), 0, 120 ) : '';
         echo wp_kses($response_data,$allowed_html). "...";
    }

    /* Define bulk actions available for our table listing. @since 1.0.0  @return array */
    
    public function get_bulk_actions() {
        $actions = array('delete' => esc_html__( 'Delete', 'automate_hub' ),);
        return $actions;
    }

    /* Process the bulk actions. @since 1.0.0 */
    public function process_bulk_actions() {
        

        $ids = isset( $_REQUEST['log_id'] ) ? array_map('sanitize_text_field',$_REQUEST['log_id']): array();
        if ( ! is_array( $ids ) ) {
            $ids = array( $ids );
        }
        $ids    = array_map( 'absint', $ids );
        $action = ! empty( $_REQUEST['action'] ) ? sanitize_text_field($_REQUEST['action']) : false;
        if ( empty( $ids ) || empty( $action ) ) {
            return;
        }

        // Delete one or multiple relations - both delete links and bulk actions.
        if ( 'delete' === $this->current_action() ) {
            if (
                wp_verify_nonce(sanitize_text_field( $_REQUEST['_wpnonce']), 'bulk-logs' ) ||
                wp_verify_nonce( sanitize_text_field($_REQUEST['_wpnonce']), 'awp_delete_log_nonce' )
            ) {

                foreach ( $ids as $id ) {
                    $this->delete( $id );
                }
                AWP_redirect( admin_url( 'admin.php?page=automate_hub_log' ) );
                exit;
            }
        }
    }

    /* Sortable settings. */
    function get_sortable_columns() {
        return array(
            'response_code'           => array('response_code'   , TRUE),
            'response_message'        => array('response_message', TRUE), 
            'integration_id'          => array('integration_id'  , TRUE),
            'time'                    => array('time'            , TRUE),
            'ip'                      => array('ip'              , TRUE),            
        );
    }

    public function fetch_table_data($per_page='',$paged='') {
        global $wpdb;
        $log_table     = $wpdb->prefix . 'automate_log';
        $orderby       = ( isset( $_GET['orderby'] ) ) ? ( $log_table.'.'.sanitize_sql_orderby($_GET['orderby']) ) : $log_table.'.'.'id';
        $order         = ( isset( $_GET['order'] ) ) ? sanitize_sql_orderby($_GET['order'] ) : 'DESC';
        
        $limit         = ( is_numeric( $per_page ) && is_numeric( $paged )) ? true: false;
        $query =  "SELECT * FROM {$log_table}";
        $user_query    = "SELECT * FROM {$log_table} ORDER BY {$orderby} {$order}";
        if($limit){
            $user_query=$wpdb->prepare($user_query." LIMIT %d OFFSET %d",$per_page,$paged);
        }
        $columns               = $this->get_columns();

        $page = isset($_REQUEST['page'])?sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ):'';
        $search = isset($_REQUEST['s'])?sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ):'';
        $onlysuccess=isset($_REQUEST['onlysuccess'])?sanitize_text_field( wp_unslash( $_REQUEST['onlysuccess'] ) ):'';
        $onlyfail=isset($_REQUEST['onlyfail'])?sanitize_text_field( wp_unslash( $_REQUEST['onlyfail'] ) ):'';
        $request_form_provider=isset($_REQUEST['form_provider'])?sanitize_text_field( wp_unslash( $_REQUEST['form_provider'] ) ):'';
        $request_action_provider=isset($_REQUEST['action_provider'])?sanitize_text_field( wp_unslash( $_REQUEST['action_provider'] ) ):'';
        if(!empty($search)){
            $s = $search;
            $first_column;

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

        if(!empty($onlysuccess)){

            if(!empty($search)){
                $query_to_get  = $wpdb->prepare($query." AND response_code = %s OR response_code = %s OR response_code = %s OR response_code = %s OR response_code = %s",'200','201','202','203','204');
            }
            else{

                $query_to_get  = $wpdb->prepare($query." WHERE response_code = %s OR response_code = %s OR response_code = %s OR response_code = %s OR response_code = %s",'200','201','202','203','204');

            }
            
            $query_to_get .= " ORDER BY {$orderby} {$order}";
            $user_query = $query_to_get;
        }

        if(!empty($onlyfail)){

            if(!empty($search)){
                $query_to_get  = $wpdb->prepare($query." AND response_code != %s AND response_code != %s AND response_code != %s AND response_code != %s AND response_code != %s",'200','201','202','203','204');
            }
            else{
                $query_to_get  = $wpdb->prepare($query." WHERE response_code != %s AND response_code != %s AND response_code != %s AND response_code != %s AND response_code != %s",'200','201','202','203','204');
            }
            
            $query_to_get .= " ORDER BY {$orderby} {$order}";
            $user_query = $query_to_get;

        }

        if(!empty($request_form_provider) || !empty($request_action_provider)){
            $integration_tble=$wpdb->prefix.'awp_integration';
            $form_pro=!empty($request_form_provider)?' form_provider="'.$request_form_provider.'"':'';
            $action_pro=!empty($request_action_provider)?' action_provider="'.$request_action_provider.'"':'';

            $query =  'SELECT '. $log_table.'.id,'.$log_table.'.response_code,'.$log_table.'.response_message,'.$log_table.'.integration_id,'.$log_table.'.request_data,'.$log_table.'.response_data,'.$log_table.'.time,'.$log_table.'.start_time,'.$log_table.'.ip'; 
            $query .='  FROM ' . $log_table;
            $query_to_get=$query.' INNER JOIN '.$integration_tble.' ON '.$integration_tble.'.id = '.$log_table.'.integration_id ';
            if(!empty($search) || !empty($onlyfail) || !empty($onlysuccess)){

                $query_to_get.=' AND'.$action_pro;
                $query_to_get.=' AND'.$form_pro;
                
            }
            else{
                $query_to_get.=" WHERE ";
                if( !empty($form_pro) && !empty($action_pro)){
                    //means both are defined
                    $query_to_get.=$form_pro.' AND '.$action_pro;
                }
                else{
                    // means only one is defined so need for AND clause
                    $query_to_get.=$form_pro;
                    $query_to_get.=$action_pro;
                }    
                
                
            }

            $query_to_get .= " ORDER BY " . $orderby ."  ". $order;
            $user_query = $query_to_get;
        }
        $query_results = $wpdb->get_results( $user_query, ARRAY_A );
        return $query_results;
    }

    function get_all_activity_forms(){
        global $wpdb;
        $wpdb_table=$wpdb->prefix.'awp_integration';
        $log_table=$wpdb->prefix.'automate_log';
        $query="select form_provider from {$wpdb_table} INNER JOIN {$log_table} ON {$wpdb_table}.id = {$log_table}.integration_id group by form_provider";
        $query_results = $wpdb->get_results( $query, ARRAY_A );
        return $query_results;
    }
    function get_all_activity_platforms(){
        global $wpdb;
        $wpdb_table=$wpdb->prefix.'awp_integration';
        $log_table=$wpdb->prefix.'automate_log';
        $query="select action_provider from {$wpdb_table} INNER JOIN {$log_table} ON {$wpdb_table}.id = {$log_table}.integration_id group by action_provider";
        $query_results = $wpdb->get_results( $query, ARRAY_A );
        return $query_results;
    }

    protected function display_tablenav( $which ) {
            $allforms=$this->get_all_activity_forms();
            $allPlatforms=$this->get_all_activity_platforms();

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

                <div class="log-table-option-sa">
                    <a href="<?php echo admin_url( 'admin.php?page=automate_hub_log' ) ?>">
                        <button type="button" class="button action">Clear Filters</button>
                    </a>
                    
                </div>
                <a class="anchorcodeone" href="<?php echo esc_url( admin_url( 'admin.php?page=automate_hub_log&onlysuccess=true' ) ); ?>"><div id="codeone" style=""><strong>Successful</strong></div></a>
                <a class="anchorcodetwo" href="<?php echo esc_url( admin_url( 'admin.php?page=automate_hub_log&onlyfail=true' ) ); ?>"><div id="codetwo" style=""><strong>Failed</strong></div></a>

                <div  class=" filter log-filter-a">
                    <div class="log-inline-display">
                        <label>From</label>
                        <select name="log_form_provider">
                            <option value="">Any</option>
                            <?php
                                $allowed_html = array(
                                    'option'      => array(
                                        'selected'  => array(),
                                        'value' => array(),
                                    )
                                );
                                $preselected=isset($_GET['form_provider'])?sanitize_text_field($_GET['form_provider']):'';
                                 
                                foreach ($allforms as $key => $form) {
                                    if($preselected==$form['form_provider']){
                                        echo wp_kses('<option selected value="'.$form['form_provider'].'">'. ucfirst($form['form_provider']) .'</option>',$allowed_html); 
                                    }
                                    else{
                                        echo wp_kses('<option value="'.$form['form_provider'].'">'. ucfirst($form['form_provider']) .'</option>',$allowed_html);    
                                    }
                                    
                                }
                            ?>
                            
                        </select>
                    </div>

                    <div class="log-inline-display">
                        <label>To</label>
                        <select name="log_action_provider">
                            <option value="">Any</option>
                            <?php 
                                $preselected=isset($_GET['action_provider'])?sanitize_text_field($_GET['action_provider']):'';
                                foreach ($allPlatforms as $key => $platform) {
                                    if($preselected==$platform['action_provider']){
                                        echo wp_kses('<option selected value="'.$platform['action_provider'].'">'. ucfirst($platform['action_provider']) .'</option>',$allowed_html); 
                                    }
                                    else{
                                        echo wp_kses('<option value="'.$platform['action_provider'].'">'. ucfirst($platform['action_provider']) .'</option>',$allowed_html);    
                                    }
                                    
                                }
                            ?>
                        </select>
                    </div>

                    <button class="button action logFilter">Search</button>

                </div>
                <?php
                $this->extra_tablenav( $which );
                $this->pagination( $which );
                ?>
             
                <br class="clear" />
            </div>
                <?php
    }


    //Query, filter data, handle sorting, pagination, and any other data-manipulation required prior to rendering
    public function prepare_items() {
        // Process bulk actions if found.
        $this->process_bulk_actions();
        $per_page              = 20;
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
          array('total_items' => $count,
                'per_page'    => $per_page,
                'total_pages' => ceil( $count / $per_page ),));
    }

    /* Renders status column */
    public function column_status($item) {
        if ($item['status']) {
            $actions = "<span onclick='window.location=\"admin.php?page=automate_hub_log&action=status&id=".$item['id']."\"'  class='span_activation_checkbox'  ><a class='a_activation_checkbox' href='?page=automate_hub&action=edit&id=".$item['id']."'>  <input type='checkbox' name='status' checked=checked > </a></span>" ;
        }else{
            $actions = "<span onclick='window.location=\"admin.php?page=automate_hub&action-log=status&id=".$item['id']." \"'  class='span_activation_checkbox'  ><a class='a_activation_checkbox' href='?page=automate_hub&action=edit&id=".$item['id']."'>  <input type='checkbox' name='status' > </a></span>" ;
        }
        return  wp_kses_data($actions);
    }

    /* Handles delete */
    public function delete( $id='' ) {
        global $wpdb;
        $relation_table = $wpdb->prefix.'automate_log';
        $action_status  = $wpdb->delete( $relation_table, array( 'id' => $id ) );
       return $action_status;
    }

    /* Handles connection count */
    public function count() {
        global $wpdb;
        $relation_table = $wpdb->prefix.'automate_log';
        $count          =  $wpdb->get_var("SELECT COUNT(*) FROM  $relation_table" );
        return $count;
    }

        /* Handles column width */
        public function admin_header() {
            $page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
            if( 'automate_hub_log' != $page ) return;

            echo wp_kses('<style type="text/css">.wp-list-table .column-id               {width:  2%;}
            .wp-list-table .column-response_code    {width:  5%;}
            .wp-list-table .column-response_message {width:  8%;}
            .wp-list-table .column-integration_id   {width:  12%;}
            .wp-list-table .column-request_data     {width: 25%;}
            .wp-list-table .column-response_data    {width: 28%;}
            .wp-list-table .column-timestamp        {width: 12%;}
            </style>
            ',array('style'=>array()));
        }

}
