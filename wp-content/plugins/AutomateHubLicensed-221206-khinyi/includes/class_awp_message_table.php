<?php
if(class_exists('AWP_Message_Template_Table')){return;}
if( !class_exists( 'WP_List_Table' ) ) require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

// Connection List Table class.
class AWP_Message_Template_Table extends WP_List_Table {

    /* Construct function. Set default settings. */
    function __construct() {
        global $status, $page;
        //Set parent defaults
        parent::__construct( array(
            'ajax'     => FALSE,
            'singular' => 'messageTemplate',
            'plural'   => 'messageTemplates',
        ));
    }

    /* Renders the columns. @since 1.0.0 */
    function column_default( $item, $column_name ) {

        switch ( $column_name ) {
            case 'id'             : $value = $item['id'             ]; break;
            case 'title'          : $value = $item['title'          ]; break;
            case 'external_template_id'  : $value = $item['external_template_id'  ]; break;
            case 'subject_name'      : $value = $item['subject_name'      ]; break;
            case 'action_provider': $value = $item['action_provider']; break;
            case 'message_template'           : $value = $item['message_template'           ]; break;
            case 'sender_email'         : $value = $item['sender_email'         ]; break;
            case 'sender_phone'         : $value = $item['sender_phone'         ]; break;
            case 'action'         : $value = $item['action'         ]; break;
            default: $value = '';
        }
        return apply_filters( 'awp_message_table_column_value', $value, $item, $column_name );
    }

    /* Retrieve the table columns. @since 1.0.0
     * @return array $columns Array of all the list table columns. */
    function get_columns() {
        $columns = array(

            'cb'              => '<input type="checkbox" />',
            'action_provider' => esc_html__( 'Destination Platform', 'automate_hub' ),
            'title'           => esc_html__( 'Message Template Name'    , 'automate_hub' ),
            'external_template_id'=> esc_html__( 'External Template ID'         , 'automate_hub' ),
            'subject_name'=> esc_html__( 'Message Subject Name'         , 'automate_hub' ),
            'message_template'=> esc_html__( 'Message Template'         , 'automate_hub' ),
            'sender_phone'=> esc_html__( 'Sender Phone'         , 'automate_hub' ),
            'sender_email'=> esc_html__( 'Sender Email'         , 'automate_hub' ),
            'id'              => esc_html__( 'ID'      , 'automate_hub' ),
            'status'          => esc_html__( 'Active'              , 'automate_hub' )
        );
        return apply_filters( 'awp_message_template_table_columns', $columns );
    }

    public function column_message_template($item){ 

        $message_template = htmlspecialchars($item['message_template']);
        $message_template = substr($message_template, 0, -10).' ...';
        return $message_template;
    }
    /* Render the checkbox column. @since 1.0.0 @return string */
    public function column_cb( $item ) {
        return '<input type="checkbox" name="id[]" value="' . absint( $item['id'] ) . '" />';
    }

    public function column_form_provider( $item ) {
        $form_providers = awp_get_form_providers();
        if( array_key_exists( $item['form_provider'], $form_providers ) ) {
            if(!empty( $item['form_provider'])){
                $form_provider_img = AWP_IMAGES.'/icons/'.$item['form_provider'].'.png';
                $html = '<div class="column_integration_id"><img src="'.$form_provider_img.'"  class="action_provider_img" width="50" height="50" />&nbsp;'.$form_providers[$item['form_provider']].'</div>';                 
                return $html;
              }
            return $form_providers[$item['form_provider']];
        } else {
            return esc_html__( 'Deactivated?', 'automate_hub');
        }
    }

    /* Render the form name column with action links. @since 1.0.0 @return string */
    public function column_title( $item ) {
        $name = ! empty( $item['title'] ) ? $item['title'] : $item['action_provider'];
        $name = sprintf( '<span><strong>%s</strong></span>', esc_html__( $name ) );
        $row_actions = array();                  // Build all of the row action links.
        $row_actions['edit'] = sprintf(          // Edit.
            '<a href="%s" title="%s">%s</a>',
            add_query_arg(
                array('action' => 'edit', 'id'     => $item['id'],),
                admin_url( 'admin.php?page=automate_message_templates' )
            ),
            esc_html__( 'Edit This Template', 'automate_hub' ),
            esc_html__( 'Edit', 'automate_hub' )
        );
        $row_actions['duplicate'] = sprintf(    // Duplicate.            
            '<a href="%s" class="awp-message-duplicate" title="%s">%s</a>',
            wp_nonce_url(
                add_query_arg(array('action'  => 'duplicate', 'id' => $item['id'],),
                    admin_url( 'admin.php?page=automate_message_templates' )
                ),
                'awp_duplicate_message_nonce'
            ),
            esc_html__( 'Duplicate this template', 'automate_hub' ),
            esc_html__( 'Duplicate', 'automate_hub' )
        );
        $row_actions['delete'] = sprintf(       // Delete.    
            '<a href="%s" class="awp-message-delete" title="%s">%s</a>',
            wp_nonce_url(
                add_query_arg(
                    array('action'  => 'delete','id' => $item['id'],),
                    admin_url( 'admin.php?page=automate_message_templates' )
                ),
                'awp_delete_message_nonce'
            ),
            esc_html__( 'Delete this message template', 'automate_hub' ),
            esc_html__( 'Delete', 'automate_hub' )
        );
        // Build the row action links and return the value.
        return $name . $this->row_actions( apply_filters( 'awp_message_template_row_actions', $row_actions, $item ) );
    }

    /* Renders action provider column */
    public function column_action_provider( $item ) {
        $actions = awp_get_action_providers();
        $action  = isset( $actions[$item['action_provider']] ) ? $actions[$item['action_provider']] : '';
        if(!empty( $item['action_provider'])){
           $action_provider_img = AWP_IMAGES.'/icons/'.$item['action_provider'].'.png';
           $html = '<div class="column_integration_id"><img src="'.$action_provider_img.'" class="action_provider_img" width="50" height="50" />&nbsp;'.$action.'</div>';
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
        $ids = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : array();
        if ( ! is_array( $ids ) ) {
            $ids = array( $ids );
        }
        $ids    = array_map( 'absint', $ids );
        $action = ! empty( $_REQUEST['action'] ) ? $_REQUEST['action'] : false;
        if ( empty( $ids ) || empty( $action ) ) {
            return;
        }
        // Delete one or multiple relations - both delete links and bulk actions.
        if ( 'delete' === $this->current_action() ) {
            if (
                wp_verify_nonce( $_REQUEST['_wpnonce'], 'bulk-integrations' ) ||
                wp_verify_nonce( $_REQUEST['_wpnonce'], 'awp_delete_message_nonce' )
            ) {
                foreach ( $ids as $id ) {
                    $this->delete( $id );
                }
                AWP_redirect( admin_url( 'admin.php?page=automate_message_templates' ) );
                exit;
            }
        }
    }

    /* Message to be displayed when there are no relations. @since 1.0.0 */
    public function no_items() {
        printf(wp_kses(__( 'You haven\'t created any platform message template yet. <a href="%s">Click here to setup your first template.</a>?', 'automate_hub' ), array( 'a' => array('href' => array(),),)),
        admin_url( 'admin.php?page=automate_add_message_template' )
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

    public function fetch_table_data() {
        global $wpdb;
        $wpdb_table    = $wpdb->prefix . 'awp_message_template';
        $orderby       = ( isset( $_GET['orderby'] ) ) ? esc_sql( $_GET['orderby'] ) : 'id';
        $order         = ( isset( $_GET['order'] ) ) ? esc_sql( $_GET['order'] ) : 'DESC';
        $user_query    = "SELECT * FROM ". $wpdb_table ." ORDER BY " . $orderby ."  ". $order ;
        $query_results = $wpdb->get_results( $user_query, ARRAY_A );
        return $query_results;
    }

    //Query, filter data, handle sorting, pagination, and any other data-manipulation required prior to rendering
    public function prepare_items() {
        $this->process_bulk_actions();          // Process bulk actions if found.
        $per_page              = 20;
        $count                 = $this->count();
        $columns               = $this->get_columns();
        $hidden                = array();
        $sortable              = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $table_data            = $this->fetch_table_data();
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

    /* Renders status column */
    public function column_status($item) {
        if ($item['status']) {
            $actions = "
            <span onclick='window.location=\"admin.php?page=automate_message_templates&action=status&id=".$item['id']."\"'  class='span_activation_checkbox'>
                <a class='a_activation_checkbox' href='?page=automate_message_templates&action=status&id=".$item['id']."'>
                <label class='switchCB'><input type='checkbox' name='status' checked=checked > <span class='sliderCB roundCB'></span></label>
                </a>
            </span>" ;
        }else{
            $actions = "
            <span onclick='window.location=\"admin.php?page=automate_message_templates&action=status&id=".$item['id']." \"'  class='span_activation_checkbox'>
                <a class='a_activation_checkbox' href='?page=automate_message_templates&action=status&id=".$item['id']."'>
                <label class='switchCB'><input type='checkbox' name='status' ><span class='sliderCB roundCB'></span></label>
                </a>
            </span>" ;
        }
        return   $actions ;
    }

    /* Handles delete */
    public function delete( $id='' ) {
        global $wpdb;
        $relation_table = $wpdb->prefix.'awp_message_template';
        $action_status  = $wpdb->delete( $relation_table, array( 'id' => $id ) );
        return $action_status;
    }

    /* Handles connection count */
    public function count() {
        global $wpdb;
        $relation_table = $wpdb->prefix.'awp_message_template';
        $count          =  $wpdb->get_var("SELECT COUNT(*) FROM " . $relation_table );
        return $count;
    }

    /* Handles column width */
    public function admin_header() {
        $page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
        if( 'automate_hub' != $page ) return;
        echo '<style type="text/css">';
        echo '.wp-list-table .column-id              { width: 10%;}';
        echo '.wp-list-table .column-title           { width: 16%;}';
        echo '.wp-list-table .column-form_provider   { width: 16%;}';
        echo '.wp-list-table .column-form_name       { width: 16%;}';
        echo '.wp-list-table .column-action_provider { width: 16%;}';
        echo '.wp-list-table .column-action_name     { width: 16%;}';
        echo '.wp-list-table .column-status          { width: 10%;}';
        echo '</style>';
    }
}