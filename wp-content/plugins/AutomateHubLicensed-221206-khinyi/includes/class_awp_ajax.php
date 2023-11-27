<?php
if(class_exists('AWP_Ajax_Handler')){return;}
class AWP_Ajax_Handler {
    
	/* Class constructor. */
	public function __construct() {              
		add_action( 'wp_ajax_get_platform_favourite', array($this,'awp_get_platform_favourite') );  
		add_action( 'wp_ajax_add_platform_favourite', array($this,'awp_add_platform_favourite') );
		add_action( 'wp_ajax_remove_platform_favourite', array($this,'awp_remove_platform_favourite') );
		add_action( 'wp_ajax_awp_deactivate_feedback', array($this,'awp_deactivate_feedback') );
        add_action( 'wp_ajax_awp_export_log', array($this,'awp_export_log') );      
        add_action( 'wp_ajax_awp_refresh_app_directory', array($this,'awp_refresh_app_directory') );          
             
	    
	}

    function awp_refresh_app_directory(){
        if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
            die( esc_html__( 'Security check Failed', 'automate_hub' ) );
        }
        $app_directory_obj=new AWP_Updates_Manager();
        $apps_data=$app_directory_obj->save_app_directory_data();
        print_r($apps_data);
    }
	
	function awp_deactivate_feedback(){
		if (! wp_verify_nonce( $_POST['security'], 'awp-deactivate-nonce' ) ) {
	        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
	    }
	    $email = get_option('admin_email');
        $_reason = !empty($_POST['reason']) ? sanitize_text_field(wp_unslash($_POST['reason'])) :'';
        $reason_detail = !empty($_POST['reason_detail']) ? sanitize_text_field(wp_unslash($_POST['reason_detail'])) :'';
        $reason = '';
        $browser = $this->getBrowser();
        $cuurent_user = wp_get_current_user();


        if ($_reason == '1') {
            $reason = 'I only needed the plugin for a short period';
        } elseif ($_reason == '2') {
            $reason = 'I found a better plugin';
        } elseif ($_reason == '3') {
            $reason = 'The plugin broke my site';
        } elseif ($_reason == '4') {
            $reason = 'The plugin suddenly stopped working';
        } elseif ($_reason == '5') {
            $reason = 'I no longer need the plugin';
        } elseif ($_reason == '6') {
            $reason = 'It\'s a temporary deactivation. I\'m just debugging an issue.';
        } elseif ($_reason == '7') {
            $reason = 'Other';
        }
        elseif ($_reason == '8') {
            $reason = 'Couldnot find the required platform in the list';
        }

        $fields = array(
        	'email' => $email, 
        	'website' => get_site_url(), 
        	'action' => 'Deactivate', 
        	'plugin_version' => AWP_VERSION,
        	'plugin_type' => AWP_PLUGIN,
        	'reason' => $reason, 
        	'reason_detail' => $reason_detail, 
        	'display_name' => $cuurent_user->display_name, 
        	'blog_language' => get_bloginfo('language'), 
        	'wordpress_version' => get_bloginfo('version'), 
        	'php_version' => PHP_VERSION, 
        	'wordpress_timezone' => date_default_timezone_get(), 
        	'ip_address' => getUserIpAddrForSperse(),
        	'browser' => $browser['name'] . '/' . $browser['version'] . '/' . $browser['platform'],
        );

        if(!class_exists('AWP_Updates_Manager')){require_once(AWP_FREE_INCLUDES .'/class_awp_updates_manager.php');}
        $obj=new AWP_Updates_Manager();
        $obj->trigger_action('deactivate_feedback',json_encode($fields) );

	}
	public function getBrowser() {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version = "";
        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        } elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }
        // Next get the name of the useragent yes seperately and for good reason
        if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        } elseif (preg_match('/Firefox/i', $u_agent)) {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        } elseif (preg_match('/OPR/i', $u_agent)) {
            $bname = 'Opera';
            $ub = "Opera";
        } elseif (preg_match('/Chrome/i', $u_agent) && !preg_match('/Edge/i', $u_agent)) {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        } elseif (preg_match('/Safari/i', $u_agent) && !preg_match('/Edge/i', $u_agent)) {
            $bname = 'Apple Safari';
            $ub = "Safari";
        } elseif (preg_match('/Netscape/i', $u_agent)) {
            $bname = 'Netscape';
            $ub = "Netscape";
        } elseif (preg_match('/Edge/i', $u_agent)) {
            $bname = 'Edge';
            $ub = "Edge";
        } elseif (preg_match('/Trident/i', $u_agent)) {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }
        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }
        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                $version = $matches['version'][0];
            } else {
                $version = $matches['version'][1];
            }
        } else {
            $version = $matches['version'][0];
        }
        // check if we have a number
        if ($version == null || $version == "") {
            $version = "?";
        }
        return array('userAgent' => $u_agent, 'name' => $bname, 'version' => $version, 'platform' => $platform, 'pattern' => $pattern);
    }
	function awp_get_platform_favourite(){
        if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
	        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
	    }
		$resp=awp_get_favourite();
		$response["success"]=true;
	    $response["data"]=$resp;
		echo json_encode($response);
		exit;
	}
	function awp_add_platform_favourite(){
        if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
	        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
	    }
		$response["success"]=false;
	    $response["msg"] = esc_html__("There was problem adding platform as favourite","automate_hub");
	    $platform = !empty($_POST['platform']) ? sanitize_text_field($_POST['platform']) :'';
	    if($platform=="" || $platform==null){
	    }
	    else{
	        
	    	$resp=awp_add_favourite($platform);
	        $response["success"]=true;
	        $response["msg"]=$resp;
			
	    }
	    
		echo json_encode($response);
		exit;
	}

	function awp_remove_platform_favourite(){
        if (! wp_verify_nonce( $_POST['_nonce'], 'automate_hub' ) ) {
	        die( esc_html__( 'Security check Failed', 'automate_hub' ) );
	    }
		$response["success"]=false;
	    $response["msg"] = esc_html__("There was problem removing platform as favourite","automate_hub");
	    $platform = !empty($_POST['platform']) ? sanitize_text_field($_POST['platform']) :'';
	    if($platform=="" || $platform==null){
	    }
	    else{
	        
	    	$resp=awp_remove_favourite($platform);
	        $response["success"]=true;
	        $response["msg"]=$resp;
			
	    }
	    
		echo json_encode($response);
		exit;
	}



    public function awp_export_log(){
        require_once AWP_VIEWS . '/export_log.php';
    }


	
}
