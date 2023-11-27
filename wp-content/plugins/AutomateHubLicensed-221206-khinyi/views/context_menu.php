<ul class="menu menu-platform">

	<?php 

		global $wpdb;
        $wpdb_table    = $wpdb->prefix . 'awp_integration';
        $query =  "SELECT * FROM  `{$wpdb_table}`";

        $query_results = $wpdb->get_results( $query, ARRAY_A );
		$actions = awp_get_action_providers();




        $validiapps=array();
        foreach ($query_results as $key => $row) {
            if(!empty($row['extra_data'])){
                $extra_data=$row['extra_data'];
                $extra_data=json_decode($extra_data,true);
                if(!isset($extra_data['soft_delete'])){
                    array_push($validiapps, $row);
                }
            }
            else{
                array_push($validiapps, $row);
            }
        }
        $groupbyapps=array();
        $groupbyappsdetails=array();
        foreach ($validiapps as $key => $row) {
            if(!in_array($row['action_provider'], $groupbyapps)){
                array_push($groupbyapps, $row['action_provider']);
                array_push($groupbyappsdetails,$row);
            }
        }



        
		foreach ($groupbyappsdetails as $key => $value) {
			$action  = isset( $actions[$value['action_provider']] ) ? $actions[$value['action_provider']] : '';
			$action_provider_img = AWP_IMAGES.'/icons/'.$value['action_provider'].'.png';
			?>

			<li class="menu-item">
		        <a href="<?php echo esc_url( admin_url( 'admin.php?page=my_integrations&selectedplatforms='.$value['action_provider'] ) ); ?>" class="menu-btn">
		        	<span><img class="menu-icon" src="<?php echo esc_url($action_provider_img); ?>"></span>
		            <span class="menu-text"><?php echo esc_html($action); ?></span>
		        </a>
		    </li>
			<?php

		}
	?> 
</ul>



<ul class="menu menu-form-source">

    <?php 

        global $wpdb;
        $wpdb_table    = $wpdb->prefix . 'awp_integration';
        $query =  "SELECT * FROM `{$wpdb_table}` GROUP BY form_provider";
        $query_results = $wpdb->get_results( $query, ARRAY_A );
        $form_providers = awp_get_form_providers();
        foreach ($query_results as $key => $value) {
            $action  = isset( $form_providers[$value['form_provider']] ) ? $form_providers[$value['form_provider']] : '';
            $action_provider_img = AWP_IMAGES.'/icons/'.$value['form_provider'].'.png';
            ?>

            <li class="menu-item">
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=my_integrations&selectedformprovider='.$value['form_provider'] ) ); ?>" class="menu-btn">
                    <span><img class="menu-icon" src="<?php echo esc_url($action_provider_img); ?>"></span>
                    <span class="menu-text"><?php echo esc_html($action); ?></span>
                </a>
            </li>
            <?php

        }
    ?>
</ul>


<ul class="menu menu-form-name">

    <?php 

        global $wpdb;
        $wpdb_table    = $wpdb->prefix . 'awp_integration';
        $query = "SELECT * FROM `{$wpdb_table}` GROUP BY form_name";
        $query_results = $wpdb->get_results( $query, ARRAY_A );
        
        
        foreach ($query_results as $key => $value) {
            $form_name = isset($value['form_name']) ? sanitize_text_field($value['form_name']) :'';
            
            ?>

            <li class="menu-item">
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=my_integrations&selectedformname='.$value['form_name'] ) ); ?>" class="menu-btn">
                    <span></span>
                    <span class="menu-text"><?php echo esc_html($form_name); ?></span>
                </a>
            </li>
            <?php

        }
    ?>
</ul>




