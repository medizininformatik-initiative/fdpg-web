<?php
        global $wpdb;  
        $filename = 'Export_Logs' . time() . '.csv';
        $header_row =  array(
                'id',
                'response_code',
                'response_message',
                'integration_id',
                'request_data',
                'response_data',
                'time',
                'start_time',
                'ip',
            );
       $data_rows = array();

        $log_table            = $wpdb->prefix . 'automate_log';
        $query =  "SELECT * FROM {$log_table}";
        
        $orderby       = ( isset( $_GET['orderby'] ) ) ? ( $log_table.'.'.sanitize_sql_orderby($_GET['orderby']) ) : $log_table.'.'.'id';
        $order         = ( isset( $_GET['order'] ) ) ? sanitize_sql_orderby($_GET['order'] ) : 'DESC';
        $onlysuccess=isset($_REQUEST['onlysuccess'])?sanitize_text_field( wp_unslash( $_REQUEST['onlysuccess'] ) ):'';
        $onlyfail=isset($_REQUEST['onlyfail'])?sanitize_text_field( wp_unslash( $_REQUEST['onlyfail'] ) ):'';
        $search = isset($_REQUEST['s'])?sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ):'';
        $request_form_provider=isset($_REQUEST['form_provider'])?sanitize_text_field( wp_unslash( $_REQUEST['form_provider'] ) ):'';
        $request_action_provider=isset($_REQUEST['action_provider'])?sanitize_text_field( wp_unslash( $_REQUEST['action_provider'] ) ):'';
        $user_query=$query;

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
        
        $users = $wpdb->get_results($user_query,"ARRAY_A");
        if(count($users)){

                foreach ( $users as $user ) 
                {


                  $user_id = isset($user['id']) ?  $user['id']:"";
                   $response_code =  isset($user['response_code']) ? $user['response_code']:'';
                   
                   $response_message = isset($user['response_message']) ? $user['response_message'] :'';

                   $integration_id = isset($user['integration_id']) ? $user['integration_id'] :'' ;
                  $request_data = isset($user['request_data']) ?  $user['request_data']:'';
                  $response_data = isset($user['response_data']) ? $user['response_data'] :'';
                  $time = isset($user['time']) ? $user['time'] :'';
                  $start_time = isset($user['start_time']) ?  $user['start_time'] :'';
                  $ip = isset($user['ip']) ? $user['ip'] :'' ;


                    $row = array(
                        $user_id,$response_code,$response_message,$integration_id,$request_data,$response_data,$time,$start_time,$ip  
                    );
                    $data_rows[] = $row;
                }
                ob_end_clean ();
                header('Content-Type: application/csv');
                header( 'Content-Disposition: attachment; filename="'.$filename.'";' );
                $fh = @fopen( 'php://output', 'w' );
                fputcsv( $fh, $header_row );
                
                foreach ( $data_rows as $data_row ) 
                {
                    fputcsv( $fh, $data_row );
                }
            
              wp_die();  
        }
        else{
                echo $user_query;
                echo esc_html__("No records to export", 'automate_hub' );
        }
        

?>
