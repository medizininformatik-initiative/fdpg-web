<?php

global $wpdb;
$table          = $wpdb->prefix . 'automate_log';
$result         = $wpdb->get_row($wpdb->prepare( "SELECT * FROM {$table} WHERE id =%d",$id), ARRAY_A );
$all_results    = $wpdb->get_results("SELECT * FROM  {$table}", ARRAY_A );
$collection_ids = array();


if(!empty($all_results)){
	foreach ($all_results as $key => $log_result) {
	$collection_ids[] = $log_result['id'];
	}
}

$prev_link_id  = '';
$next_link_id  = ''; 
$first_link_id = '';
$end_link_id   = '';

if(!empty($collection_ids)){
	$current_log_key = array_search($id, $collection_ids); // $key = 2;
	if($id>0)
	$prev_link_id = !empty($collection_ids[$current_log_key-1]) ? $collection_ids[$current_log_key-1] :'';
	
    if($id != end($collection_ids))
	$next_link_id = isset($collection_ids[$current_log_key+1]) ? $collection_ids[$current_log_key+1] :'' ;

    $first_link_id = isset($collection_ids[0]) ? $collection_ids[0] :'';
    $end_link_id = end($collection_ids);
}

$integration_id   = isset($result["integration_id"  ]) ? $result["integration_id"  ] : "";
$request_data     = isset($result["request_data"    ]) ? $result["request_data"    ] : "";
$response_code    = isset($result["response_code"   ] )? $result["response_code"   ] : "";
$response_data    = isset($result["response_data"   ]) ? $result["response_data"   ] : "";
$response_message = isset($result["response_message"]) ? $result["response_message"] : "";
$response_time    = isset($result["time"]) ? $result["time"] : "";
$request_time     = isset($result["start_time"]) ? $result["start_time"] : "NULL";
$start_inst       = strtotime( $request_time . ' +0000' );
$start_date       = new DateTime("@".$start_inst);  
$start_date       ->setTimezone(new DateTimeZone('America/Phoenix'));
$start_date_time  = $start_date->format('Y-m-d h:i:s A');     
$date1            = new DateTime($response_time, new DateTimeZone('EDT'));
$date1            ->setTimezone(new DateTimeZone('America/Phoenix'));
$end_date_time    = $date1->format('Y-m-d h:i:s A'); // 2011-11-10 15:17:23 -0500
$responsecode     = $response_code;
$itable           = $wpdb->prefix . 'awp_integration';
if(isset($integration_id) && !empty($integration_id)){
    $iresult          = $wpdb->get_row($wpdb->prepare( "SELECT * FROM {$itable}  WHERE id =%d",$integration_id), ARRAY_A );
}
else{
    $iresult="";
}
$iresult          = $wpdb->get_row($wpdb->prepare( "SELECT * FROM {$itable} WHERE id =%d" ,$integration_id), ARRAY_A );
$form_provider    = isset($iresult["form_provider"]) ? $iresult["form_provider"] : "";
$action_provider  = isset($iresult["action_provider"]) ? $iresult["action_provider"] : "";
$form_provider_img = empty($form_provider) ? false : AWP_IMAGES.'/icons/'.$form_provider.'.png';
$action_provider_img = empty($form_provider) ? false : AWP_IMAGES.'/icons/'.$action_provider.'.png';
 include AWP_INCLUDES.'/header.php'; 

 $allowed_html = array(
    'a' => array(
        'href' => array(),
        'title' => array()
    ),
    'br' => array(),
    'em' => array(),
    'strong' => array(),
);

 ?>

<div class="wrap test-wrap">
	<div class="pages-background"></div>
    <div id="icon-options-general" class="icon32"></div>
    <div class="log-heading bottom-line">
    <h3 class="sperse-app-page-title main-title"> <?php esc_html_e( 'Activity Log', 'automate_hub' ); ?>
        <a href="<?php echo esc_url(admin_url( 'admin.php?page=automate_hub_log' )); ?>"><?php esc_html_e( 'Back', 'automate_hub' ); ?></a>
    </h3>
    <?php
    if($form_provider_img!=false){
    ?>

		<div class="column_integration_id">
            <img src="<?php echo esc_url($form_provider_img); ?>" class="form_provider_img" width="50" height="50">
            <span class="int_arrow">&gt;</span>
            <img src="<?php echo esc_url($action_provider_img); ?>" class="action_provider_img" width="50" height="50">
        </div>
    <?php
    } 
    ?>
		    <div class="tablenav-pages">

        <span class="displaying-num"><?php echo esc_html(count($collection_ids)); ?> <?php esc_html_e( 'items', 'automate_hub' ); ?></span>
                    <?php if($prev_link_id){ ?>
		
        <div class="tablenav-pages__links">
	  <a href="<?php echo esc_url(admin_url( 'admin.php?page=automate_hub_log&action=view&id='.$first_link_id )); ?>" class="button"><span class="tablenav-pages-navspan" aria-hidden="true"><?php esc_html_e( '«', 'automate_hub' ); ?></span></a>
    <?php } else { ?>

        <span class="tablenav-pages-navspan button disabled" aria-hidden="true"><?php esc_html_e( '«', 'automate_hub' ); ?></span>

   <?php }
         if($prev_link_id){ ?>
        <a href="<?php echo esc_url(admin_url( 'admin.php?page=automate_hub_log&action=view&id='.$prev_link_id )); ?>" class="button" ><span class="tablenav-pages-navspan" aria-hidden="true"><?php esc_html_e( '‹ Previous', 'automate_hub' ); ?></span></a>
        <?php } else{ ?>

            <span class="tablenav-pages-navspan button disabled" aria-hidden="true"><?php esc_html_e( '‹ Previous', 'automate_hub' ); ?></span>

            <?php 
        } ?>

		<?php

		if(!empty($_POST['go-to-log'])){
			$n_id = !empty($_POST['gotopaged']) ? sanitize_text_field($_POST['gotopaged']) : $id;
			$nlogurl = admin_url( 'admin.php?page=automate_hub_log&action=view&id='); ;
			$nlogfinalurl = $nlogurl.$n_id;
			wp_safe_redirect($nlogfinalurl);
			exit();
		}


		?>
        <span class="paging-input">
        	<form name="log-selector" action="" method="post"> 
            	<input class="current-page" id="current-page-selector" value="<?php echo esc_attr($id); ?>" type="text" name="gotopaged" aria-describedby="table-paging">
            	<input type="submit" class="go-to-log" name="go-to-log" value="<?php esc_html_e( 'go-to-log', 'automate_hub' ); ?>">
        	</form>
        </span>

        <?php  if($next_link_id){ ?>
        <a href="<?php echo esc_url(admin_url( 'admin.php?page=automate_hub_log&action=view&id='.$next_link_id )); ?>" class="button"><span aria-hidden="true"><?php esc_html_e( 'Next ›', 'automate_hub' ); ?></span></a>
      <?php }else{ ?>

            <span class="tablenav-pages-navspan button disabled" aria-hidden="true"><?php esc_html_e( 'Next ›', 'automate_hub' ); ?></span>
     <?php } ?>
             <?php  if($next_link_id){ ?>

        <a href="<?php echo esc_url(admin_url( 'admin.php?page=automate_hub_log&action=view&id='.$end_link_id )); ?>" class="button"><span aria-hidden="true"><?php esc_html_e( '»', 'automate_hub' ); ?></span></a>
    <?php } else { ?>
        <span class="tablenav-pages-navspan button disabled" aria-hidden="true"><?php esc_html_e( '»', 'automate_hub' ); ?></span></a>

    <?php } ?>
		</div>
      

    </div>
    </div>

    <div id="awp-new-integration" v-cloak>
        <div id="post-body" class="metabox-holder">
            <table class="form-table" border="1" width="100%" >
            <tr valign="top">
                <td class="halfwidth" width="50%"><?php esc_html_e( 'Integration ID', 'automate_hub' ); ?><strong> #<?php echo esc_html($integration_id.' '.ucfirst($form_provider).' to '.ucfirst($action_provider)); ?> </strong>
                    <div class="response_time">
                       <span> <strong><?php esc_html_e( 'Start Time : ', 'automate_hub' ); ?></strong><?php echo esc_html($request_time); ?></span>
                       <span> <strong><?php esc_html_e( 'End Time : ', 'automate_hub' ); ?></strong><?php echo esc_html($response_time); ?></span>
                    </div>
                </td>
                <td width=25%><?php esc_html_e( 'Response Code' , 'automate_hub' ); ?><br/>
                <?php 
                $positive_reponse_codes=array(200,201,202,203,204);
                if (in_array($response_code, $positive_reponse_codes))
                {
                    ?>
                        <div id="codeone"><strong>
                    <?php 
                 echo esc_html(stripslashes( $response_code ));
                    ?>
                    </strong></div>
                    <?php 

                } else
                {
                    ?>
                    <div id="codetwo"><strong>
                    <?php 
                        echo esc_html(stripslashes( $response_code ));
                    ?>
                    </strong></div>
                    <?php 

                } ?>
                </div></td>
                <td width=25%><?php esc_html_e( 'Response Message', 'automate_hub' ); ?><br/><strong><?php echo  esc_html(stripslashes( $response_message)); ?></strong></td>
            </tr>
            <tr valign="top">
                <td class="halfwidth aligned" width=50% valign="top" colspan="0">
                    <table class="form-table" border="0" valign="top">
                    <tr valign="top">
                        <td valign="top">
                        <p><?php esc_html_e( 'Request Data', 'automate_hub' ); ?><button class="btn btn-primary"  id="sendRequestBtn"><?php esc_html_e( 'Retry', 'automate_hub' ); ?></button><br/>
                        <pre id="preleft">

                            <?php 

                            if(isJson($request_data)){
                                $fprint= stripslashes(json_encode(json_decode($request_data,true), JSON_PRETTY_PRINT));
                                if(strpos($fprint, '{{')){
                                    $fprint= str_replace('{{', '', $fprint);
                                }
                                if(strpos($fprint, '}}')){
                                    $fprint=str_replace('}}', '', $fprint);
                                }
                                echo wp_kses($fprint,$allowed_html);
                            }
                            else{
                                echo wp_kses($request_data,$allowed_html);
                            }

                            ?>
                                

                            </pre>
                        </p>
                        </td>
                    </tr>
                    </table>
                </td>
                <td width=50% valign="top" colspan="2" class="halfwidth aligned">
                    <table class="form-table" border="0">
                    <tr valign="top">
                        <td valign="top">
                        <p><?php esc_html_e( 'Response Data', 'automate_hub' ) ?><br/>
                            <pre id="preright"><?php 

                            if(isJson($response_data)){
                                $fprint= stripslashes(json_encode(json_decode($response_data,true), JSON_PRETTY_PRINT));
                                if(strpos($fprint, '{{')){
                                    $fprint= str_replace('{{', '', $fprint);
                                }
                                if(strpos($fprint, '}}')){
                                    $fprint=str_replace('}}', '', $fprint);
                                }
                                echo wp_kses($fprint,$allowed_html);
                            }
                            else{
                                echo wp_kses($response_data,$allowed_html);
                            }
                             ?></pre>
                            
                        </p>
                        </td>
                    </tr>
                    </table>
                </td>
            </tr>    
            </table>
        </div>
        <!-- #post-body .metabox-holder .columns-2 -->
        <br class="clear">
    </div>
    <!-- #poststuff -->

</div> <!-- .wrap -->
