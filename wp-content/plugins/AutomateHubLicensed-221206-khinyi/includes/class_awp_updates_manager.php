<?php 
if(class_exists('AWP_Updates_Manager')){return;}
class AWP_Updates_Manager{
	private $platform_name;
	private $server_endpoint='https://sperse.io/scripts/authorization/instructions/index.php';
	private $local_update_endpoint='/automatehub-updates';
	private $instructions;
	private $brand='automatehub';
	private $version='ultimate';
	
	function __construct($platform_name=""){
		$this->platform_name=$platform_name;
		$this->instructions=unserialize( get_option('awp_apps_instructions') );
		$this->init_webhook();
		$this->init_actions();
		
	}

	function init_actions(){
		add_action( 'init',[ $this, 'awp_periodic_updates']);
	}
	function awp_plugin_status_difference(){
		$liveobj=$this->awp_get_live_plugin_status();
		$prevobj=$this->awp_get_plugin_status();
		$changed=false;
		foreach ($liveobj as $key => $value) {
			if($value != $prevobj[$key] && $key !='timestamp'){
				$changed=true;
			}
		}		

		return [$changed,$liveobj];
	}

	function awp_get_live_plugin_status(){
		$consumptions=plugin_status();
		$obj=[
				'timestamp'=>time(),
				'active_accounts'=>!empty($consumptions['X']) ? count($consumptions['X']) :'',
				'active_integrations'=>!empty($consumptions['W']) ? $consumptions['W'] :'' ,
				'apps_used'=>!empty($consumptions['Y']) ? $consumptions['Y'] :'',
				'api_calls'=>!empty($consumptions['Z']) ? $consumptions['Z'] :'',
				'succesfull_calls'=>!empty($consumptions['S']) ? $consumptions['S'] :'',
				'unsuccessfull_calls'=>!empty($consumptions['U']) ? $consumptions['U'] :'',
			];
		return $obj;
	}

	function awp_get_plugin_status(){
		$queue=get_option('awp_plugin_state_analytics');
		
	    if(empty($queue)){
	    	$obj=$this->awp_get_live_plugin_status();
	        $serialized=serialize($obj);
	        update_option('awp_plugin_state_analytics',$serialized);
	        return $obj;
	    }
	    else{
	        $list=unserialize($queue);
	        return $list;
	    }
	}

	function awp_periodic_updates(){
		$res=$this->awp_plugin_status_difference();
		if($res[0] == true){
			$stored_analytics=$this->awp_get_plugin_status();
			$time=86400+$stored_analytics['timestamp'];
			if($time<time()){
				$serialized=serialize($res[1]);
				$this->trigger_action('plugin_status',$serialized);	
				update_option('awp_plugin_state_analytics',$serialized);
			}
			
		}
	}

	public function init_webhook(){
		add_action( "rest_api_init", [$this, "create_webhook_route" ] );
	}
	public function create_webhook_route() {
		register_rest_route( 'automatehub', $this->local_update_endpoint,
        [
            'methods' => 'POST', 
            'callback' => [$this, 'new_updates'], 
            'permission_callback' => function() { return ''; }
        ]);

		
	}

	public function new_updates($request){
		$params = $request->get_params();
		if($params['action'] == 'updateinstructions'){
			$response=$this->request_instructions();
		}
		if($params['action'] == 'update_license_key'){
			if(isset($params['license_key'])){
				$response=$this->update_license_key($params['license_key']);
			}
		}
		
	}
	function prepare_instructions(){
		
		if(empty($this->instructions) || !isset($this->instructions[$this->platform_name])){
			$response=$this->request_instructions();
			if($response['success']){
				$response_body=$response['body'];
				$instructions=json_decode($response_body);
				$this->save_instructions($instructions);
				
			}
			else{
				$allowed_html = array(
					'a' => array(
						'href' => array(),
						'title' => array()
					),
					'br' => array(),
					'em' => array(),
					'strong' => array(),
				);
				echo sprintf("Error: %s",wp_kses($response['body'],$allowed_html)) ;
				return ;
			}
		}

		
		
		
		$this->display_instructions($this->instructions[$this->platform_name]);

	}

	function save_instructions($instructions){
		$processed_instructions=$this->process_instructions($instructions);
		$this->instructions=$processed_instructions;

		$processed_instructions=serialize($processed_instructions);
		update_option('awp_apps_instructions',$processed_instructions);

	}

	function get_app_directory_data(){
		//update_option('awp_apps_directory','');

		if(!empty(get_option('awp_apps_directory'))){
			return unserialize( get_option('awp_apps_directory') );
		}
		else{
			
			$data=unserialize($this->save_app_directory_data());

		}
		
		return $data;
	}

	function save_app_directory_data(){

		$data=$this->request_app_directory();
		if($data['success'] && count(json_decode($data['body'],true))){
			$data=serialize(json_decode($data['body'],true));
			update_option('awp_apps_directory',$data);
		}
		return $data;

	}

	function request_app_directory(){
		$request=array(
			'method'  => 'POST',
			'body' => array(
				'action'=>'get_app_directory',
				'updates_url'=>get_rest_url(null,'automatehub'.$this->local_update_endpoint),
				'brand'=>$this->brand,
				'version'=>$this->version,
				'license_key'=>get_option('sperse_license_key')
			),
		);
		
		$response=wp_remote_request($this->server_endpoint,$request);
		$response=$this->response_handler($response);
		return $response;
	}



	function process_instructions($instructions){
		
		$temp=array();
		foreach ($instructions as $key => $value) {
			$temp[$value->slug]=$value->text;
		}
		return $temp;
	}

	function request_instructions(){
		$request=array(
			'method'  => 'POST',
			'body' => array(
				'action'=>'get_instructions',
				'updates_url'=>get_rest_url(null,'automatehub'.$this->local_update_endpoint),
				'brand'=>$this->brand,
				'version'=>$this->version,
				'license_key'=>get_option('sperse_license_key')
			),
		);
		
		$response=wp_remote_request($this->server_endpoint,$request);
		$response=$this->response_handler($response);
		return $response;
	}

	function update_license_key($licensekey){
		if(empty($licensekey)){
			update_option('sperse_license_key', '');
			return;
		}
		$url          = "https://".AWP_DOMAIN."/scripts/licenseManager/licenseManager.php";
        $license_key  =  isset( $_REQUEST['sperse_license_key']) ?  sanitize_text_field($_REQUEST['sperse_license_key']):'';
        $properties   = array( 'licenseKey' => $licensekey, 'setStatus' => 'activate');
        $args = array('headers' => array('Content-Type' => 'application/json'), 'body' => json_encode($properties));
        $response = wp_remote_post( $url, $args );
        if (is_wp_error($response)){                                        
            echo esc_html__("Unexpected Error! The query returned with an error.",'automate_hub' );
        }
        $license_data = json_decode(wp_remote_retrieve_body($response));    
        //echo "<pre>";print_r($license_data);echo "</pre>";    
        if(($license_data->success == true)){
        	require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
	        update_option('sperse_license_key', $licensekey);
	        $obj=new AWP_Updates_Manager();
	        $obj->trigger_action('license_activated');
			
        }
		
	}

	function response_handler($response){
		$response_code = wp_remote_retrieve_response_code( $response );
		$response_body = wp_remote_retrieve_body( $response );
		
		if($response_code == 200){
			$return['success']=true;
			$return['body']=$response_body;
			
		}
		else{
			$return['success']=false;
			$return['response_code']=$response_code;
			$return['body']=$response_body;
		}
		return $return;
	}

	function display_instructions($instructions){

		if(!empty($instructions)){
			include(AWP_VIEWS.'/instruction_box.php');
		}
		//for limited version start
		$msg=isset($_GET['msg'])?sanitize_text_field($_GET['msg']):'';
		if(!empty($msg)){
			echo '<div class="alert alert-danger" role="alert">
  					'.wp_kses($msg,array()).'
					</div>';
		}
		//for limited version end
		
	}

	function trigger_action($action_name,$action_value=""){
		try{

			$request=array(
				'method'  => 'POST',
				'body' => array(
					'action'=>'add_action',
					'action_name'=>$action_name,
					'action_value'=>$action_value,
					'updates_url'=>get_rest_url(null,'automatehub'.$this->local_update_endpoint),
					'brand'=>$this->brand,
					'version'=>$this->version,
					'license_key'=>get_option('sperse_license_key')
				),
			);


			$response=wp_remote_request($this->server_endpoint,$request);
			$response=$this->response_handler($response);
			return $response;	
		}
		catch (Exception $e) {
			
		}
		
	}
}


