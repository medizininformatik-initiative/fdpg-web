<?php

if(!empty($_POST['action']) && ($_POST['action']=='map_fields') ){

     function validate_extension( $file_name ) {

            $ext_array = array( '.csv' );
            $extension = strtolower( strrchr( $file_name, '.' ) );
            $ext_count = count( $ext_array );
            $valid_extension = false;
            if ( ! $file_name ) {
                    return false;
            } else {
                    if ( ! $ext_array ) {
                            return true;
                    } else {
                            foreach ( $ext_array as $value ) {
                                    $first_char = substr( $value, 0, 1 );
                                    if ( '.' <> $first_char ) {
                                            $extensions[] = '.' . strtolower( $value );
                                    } else {
                                            $extensions[] = strtolower( $value );
                                    }
                            }

                            foreach ( $extensions as $value ) {
                                    if ( $value == $extension ) {
                                            $valid_extension = true;
                                    }
                            }

                            if ( $valid_extension ) {
                                    return true;
                            } else {
                                    return false;
                            }
                    }
            }
    }

        if ( isset( $_FILES['import_file']['tmp_name'] ) and '' == sanitize_file_name( wp_unslash( $_FILES['import_file']['tmp_name'] ) ) ) {
                $response['error'] = esc_html__( 'Please select file to be imported.', 'wpgmp-google-map' );
        } elseif ( isset( $_FILES['import_file']['name'] ) and ! validate_extension( sanitize_file_name( wp_unslash( $_FILES['import_file']['name'] ) ) ) ) {
                $response['error'] = esc_html__( 'Please upload a valid csv file.', 'wpgmp-google-map' );
        } else {

                if ( ! function_exists( 'wp_handle_upload' ) ) {
                        require_once ABSPATH . 'wp-admin/includes/file.php';
                }

                $uploadedfile     = $_FILES['import_file'];
                $upload_overrides = array( 'test_form' => false );

                $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

                if ( $movefile && ! isset( $movefile['error'] ) ) {
                        update_option( 'awp_current_csv', $movefile );
                } 
        }
}




$current_csv = get_option( 'awp_current_csv' );
$step        = 'step-1';

if ( is_array( $current_csv ) and file_exists( $current_csv['file'] ) ) {
        $step = 'step-2';
}

if ( $step == 'step-1' ) {

 ?>
    <div class="awp-ui awp-ui-height">
		<div class="pages-background"></div>
        <div class="awp-main">
                <div class="awp-container">
                        <div class="awp-divider   awp-item-shadow">
                                <div class=" awp-back">
                                        <div class="awp-12"><h4 class="awp-title-blue">Step 1 - Upload CSV</h4></div>
                                                <div class="wpomp-overview">
                                                        <form enctype="multipart/form-data" method="post" action="" name="wpomp_form" novalidate="">
                                                                <div class="form-horizontal">
                                                                        <div class="awp-form-group ">
                                                                                <div class="awp-3"><label for="import_file">Choose File</label></div>
                                                                                <div class="awp-8">
                                                                                        <div class="awp-field ext_btn">
                                                                                                <input type="file" class="awp-file_input" name="import_file">
<!--                                                                                                 <label for="file"><span class="icon-upload2"></span> &nbsp;Choose a file </label> -->
<!--                                                                                                 <label class="awp-file-details"></label> -->
                                                                                        </div>
                                                                                        <p class="help-block">Please upload a valid CSV file.</p>
                                                                                </div>
                                                                        </div>
                                                                        <div class="awp-form-group ">
                                                                                <div class="awp-8">
                                                                                        <div class="awp-divider awp-footer awp-no-sticky">
                                                                                                <div class="awp-12">
                                                                                               <input type="submit" name="import_loc" class="awp-btn awp-btn-submit awp-btn-big log-form__btn" value="Continue">
                                                                                                </div>
                                                                                        </div>
                                                                                </div>
                                                                        </div>
                                                                        <div class="awp-form-group ">
                                                                                <input name="action" type="hidden" class="form-control" value="map_fields">
                                                                        </div>
                                                                        <div class="awp-form-group ">
                                                                                <input name="import" type="hidden" class="form-control" value="location_import">
                                                                        </div>
                                                                </div>
                                                                <input type="hidden" id="_wpnonce" name="_wpnonce" value="499b17df96">
                                                                <input type="hidden" name="_wp_http_referer" value="/wp/wp-admin/admin.php?page=wpomp_import_location">
                                                        </form>
                                                </div>
                                </div>
                        </div>
                </div>
        </div>
</div>
<?php } else { 

$response = array();


if(!empty($_POST['cancel_import'])){
    $current_csv = get_option('awp_current_csv');
   unlink( $current_csv['file'] );
   delete_option('wpgmp_current_csv');
}

if(!empty($_POST['import_log'])){

    function awp_import_to_log($data, $where = '') {
        global $wpdb;
        $table = $wpdb->prefix . 'automate_log';
        $wpdb->show_errors(); 
        if ( ! is_array( $where ) ) {
             $wpdb->insert( $table, $data );
             $result = $wpdb->insert_id;
        } else { $result = $wpdb->update( $table, $data, $where );
        }

    }

    $current_csv = get_option( 'awp_current_csv' );
    
    if ( ! is_array( $current_csv ) or ! file_exists( $current_csv['file'] ) ) {
        $response['error'] = esc_html__( 'Something went wrong. Please start import process again.', 'wpgmp-google-map' );
    }

        $csv_columns = wp_unslash( $_POST['csv_columns'] );

        $colums_mapping    = array();
        $duplicate_columns = array();

        // Unset unasigned field
    foreach ( $csv_columns as $key => $value ) {

        if ( $value == '' ) {
            unset( $csv_columns[ $key ] );
        }
    }

    // Find duplicate fields
    $duplicate_columns = array_count_values( $csv_columns );
    $not_allowed = array();
    foreach ( $duplicate_columns as $name => $count ) {

        if ( $count > 1 and $name != 'category' and $name != 'extra_field' ) {
            $not_allowed[] = $name;
        }
    }

    if ( count( $csv_columns ) == 0 ) {
        $response['error'] = _( 'Please map locations fields to csv columns.', 'wpgmp-google-map' );

    }

    $is_update_process = false;

    if ( in_array( 'id', $csv_columns ) !== false ) {
        $is_update_process = true;
    }

    if ( count( $not_allowed ) > 0 ) {
        $response['error'] = _( 'Duplicate mapping is not allowed except category and extra field.', 'wpgmp-google-map' );

    }

        // Address and title is required if add process.
    if ( $is_update_process == false ) {

        if ( in_array( 'response_code', $csv_columns ) === false or in_array( 'response_message', $csv_columns ) === false ) {
            $response['error'] = esc_html__( 'response message & response code fields are required.', 'wpgmp-google-map' );
        }
    }

    if ( count( $csv_columns ) > 0 ) {

        $file_data = array();
        $file_datas = array();
        $filename =  $current_csv['file'];
        ini_set( 'auto_detect_line_endings', true );
        $row = 1;
        if ( ($handle = fopen( $filename, 'r' )) !== false ) {
            while ( ($data = fgetcsv( $handle )) !== false ) {
                $num = count( $data );

                ++$row;
                for ( $c = 0; $c < $num; ++$c ) {
                    $data[ $c ]."<br />\n";
                }

                $file_datas[] = $data;
            }

            fclose( $handle );

        }
        if ( ! empty( $file_datas ) ) {
            $first_row    = $file_datas[0];
            unset( $file_datas[0] );
            foreach ( $file_datas as $data ) {

                $all_data_in_string = implode(' ',$data);
                if( empty( trim($all_data_in_string) ) || trim($all_data_in_string) == '' )
                continue;
                
                $datas             = array();
                foreach ( $data as $key => $value ) {

                    if ( ! isset( $csv_columns[ $key ] ) || trim( $csv_columns[ $key ] ) == '' ) {
                        continue;
                    }
                    $datas[ $csv_columns[ $key ] ] = trim( $value );
                }

                $entityID = '';
                if ( isset( $datas['id'] ) ) {
                    $entityID = intval( wp_unslash( $datas['id'] ) );
                    unset( $datas['id'] );
                }

                // Rest Columns are extra fields.
                if ( $entityID > 0 ) {
                    $where['id' ] = $entityID;
                } else {
                    $where = '';
                }
                
                $datas = array_filter( $datas );
                if(  count( $datas ) == 0 )
                continue;

                awp_import_to_log($datas, $where );

            }

            $response['success'] = count( $file_datas ) . ' ' . esc_html__( 'records imported successfully.', 'wpgmp-google-map' );
            // Here remove the temp file.
            unlink( $current_csv['file'] );
            delete_option( 'awp_current_csv' );

        } else {
            $response['error'] = esc_html__( 'No records found in the csv file.', 'wpgmp-google-map' );
        }
    } else {
        $response['error'] = esc_html__( 'Please assign fields to the csv columns.', 'wpgmp-google-map' );
    }
}


function import( $action, $filename ) {
    global $_FILES;
    $file_data  = array();
    $file_datas = array();
    if ( 'csv' == $action ) {
        $row = 1;
        if ( ( $handle = fopen( $filename, 'r' ) ) !== false ) {
                while ( ( $data = fgetcsv( $handle ) ) !== false ) {
                        $num = count( $data );

                        ++$row;
                        for ( $c = 0; $c < $num; ++$c ) {
                                $data[ $c ] . "<br />\n";
                        }

                        $file_datas[] = $data;
                }

                fclose( $handle );

        }
    }
    return $file_datas;
}
$current_csv = get_option( 'awp_current_csv' );
$file_data = import( 'csv', $current_csv['file'] );

?>
<div class="awp-ui awp-ui-height">
<div class="awp-main">
        <div class="awp-container">
                <div class="awp-divider   awp-item-shadow">
                        <div class=" awp-back">
                        <div class="awp-12">
                                <h4 class="awp-title-blue">Step 2 - Columns Mapping</h4>
                        </div>
                        <div class="awp-overview">
                            <div class="fc-12 fc-msg fc-danger fade in">Sorry, this file type is not permitted for security reasons.</div>
                                <form enctype="multipart/form-data" method="post" action="" name="awp_form" novalidate="">
                                        <div class="form-horizontal">
                                                <div class="awp-form-group ">
                                                        <div class="awp-11">
                                                                <div class="">
                                                                        <p class="awp-msg"><b><?php echo ( count( $file_data ) - 1 );  ?> </b> records are ready to upload. Please map csv columns below and click on Import button.. Leave ID field empty if you're adding new records. ID field is used to update existing location.</p>
                                                                        <div class="awp-table-responsive">
 <table class="awp-table">
 <thead><tr><th>CSV Field</th><th>Assign</th></tr></thead>
<tbody>

        <?php


                $csv_columns = array_values( $file_data[0] );

        $extra_fields = array();
        $core_fields  = array(
                ''                     => esc_html__( 'Select Field', 'wpgmp-google-map' ),
                'response_code'       => esc_html__( 'Response Code', 'wpgmp-google-map' ),
                'response_message'     => esc_html__( 'Response Message', 'wpgmp-google-map' ),
                'integration_id'    => esc_html__( 'Integration ID', 'wpgmp-google-map' ),
                'request_data'   => esc_html__( 'Request Data', 'wpgmp-google-map' ),
                'response_data'        => esc_html__( 'Response Data', 'wpgmp-google-map' ),
                'time'       => esc_html__( 'Time', 'wpgmp-google-map' ),
                'start_time'     => esc_html__( 'Start Time', 'wpgmp-google-map' ),
                'ip' => esc_html__( 'IP', 'wpgmp-google-map' ),
                'id'          => esc_html__( 'ID', 'wpgmp-google-map' ),
        );

        foreach ( $core_fields as $key => $value ) {
                $csv_options[ $key ] = $value;
        }

        foreach ( $csv_columns as $key => $value ) {
              
                if ( isset( $_POST['csv_columns'][ $key ] ) ) {
                        $selected = $_POST['csv_columns'][ $key ];
                } elseif ( array_key_exists( $value, $core_fields ) ) {
                        $selected = array_key_exists( $value, $core_fields );
                        if($selected){
                            $selected =$value;
                        }
                } else {
                        $selected = '';
                }


               echo  '<tr><td>' . $value . '</td>';

               $select_field = '<select id="csv_columns['.$key.']" class="awp_select2 form-control " name="csv_columns['.$key.']">';

               foreach ($csv_options as $key1 => $option) {

                $is_selected = ($key1==$selected) ? 'selected="selected"':'';
                       # code...
                $select_field .='<option value="'.$key1.'" '.$is_selected.' >'.$option.'</option>';
               }
                          
                $select_field .='</select>';


               echo '<td>'.$select_field.'</td></tr>';
        }
         ?>
</tbody></table>

</div></div></div><div class="awp-form-group "><input name="action" type="hidden" class="form-control" value="import_location"></div><div class="awp-form-group "><input name="import" type="hidden" class="form-control" value="location_import"></div><div class="awp-form-group "><div class="awp-12"><div class=""><div class="awp-row"><div class="awp-2"><div class="awp-divider awp-footer awp-no-sticky">
                                                <div class="awp-12">
                                                <input type="submit" name="import_log" class="awp-btn awp-btn-submit awp-btn-big" value="Import Logs">
                                                </div>
                                                </div></div><div class="awp-9"><input type="submit" value="Cancel" name="cancel_import" class="awp-btn awp-danger awp-btn-big cancel_import"></div></div></div></div></div></div><input type="hidden" id="_wpnonce" name="_wpnonce" value="5718c89b03"><input type="hidden" name="_wp_http_referer" value="/wp/wp-admin/admin.php?page=wpomp_import_location"></div></form>
                                                </div></div>
                                                </div>
                                                </div>
                                               
                                                </div>
<div class="clear"></div></div>
<?php } ?>
