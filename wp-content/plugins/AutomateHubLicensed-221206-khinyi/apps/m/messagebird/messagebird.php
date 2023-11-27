<?php

class AWP_Messagebird extends Appfactory  {
    public static $contact_url = "https://rest.messagebird.com/contacts";

    public function init_actions(){
        add_action( 'admin_post_awp_messagebird_save_api_key'  , [ $this, 'save_api_key'], 10, 0 );
    }
    
    public function init_filters(){}

    public function settings_tab( $tabs ) {
        $tabs['messagebird'] = array('name'=>esc_html__( 'MessageBird', 'automate_hub'), 'cat'=>array('esp'));
        return $tabs;
    }

    public function load_custom_script() {
        wp_enqueue_script( 'awp-messagebird-script', AWP_URL.'/apps/m/messagebird/messagebird.js', array( 'awp-vuejs' ), '', 1 );
    }

    public function save_api_key() {
        if ( ! current_user_can('administrator') ){
            die( esc_html__( 'You are not allowed to save changes!','automate_hub' ) );
        }

        // Security Check
        if (! wp_verify_nonce( $_POST['_nonce'], 'awp_messagebird_api_key' ) ) {
            die( esc_html__( 'Security check Failed', 'automate_hub' ) );
        }

        $api_key = sanitize_text_field( $_POST["awp_messagebird_api_key"] );
        $display_name     = sanitize_text_field( $_POST["awp_messagebird_display_name"] );
        // Save tokens
        $platform_obj= new AWP_Platform_Shell_Table('messagebird');
        $platform_obj->save_platform(['account_name'=>$display_name,'api_key'=>$api_key]);
        AWP_redirect( "admin.php?page=automate_hub&tab=messagebird" ); 
    }

    public function action_provider( $providers ) {
        $providers['messagebird'] = [
            'title' => __( 'MessageBird', 'automate_hub' ),
            'tasks' => array(
                'create_contact'   => __( 'Create contact', 'automate_hub' )
                )
            ];

        return  $providers;
    }

    public function settings_view( $current_tab ) { 
      if( $current_tab != 'messagebird' ) { return; }

      $nonce = wp_create_nonce( "awp_messagebird_api_key" );
      $id = isset($_GET['id']) ? $_GET['id'] : "";
      $awp_messagebird_api_key = isset($_GET['api_key']) ? $_GET['api_key'] : "";
      $display_name     = isset($_GET['account_name']) ? $_GET['account_name'] : "";

      ?>
          <div class="no-platformheader">
              <a href="https://sperse.io/go/messagebird" target="_blank"><img src="<?php echo AWP_ASSETS; ?>/images/logos/messagebird.png" width="258" height="50" alt="messagebird"></a><br/><br/>
              <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?>
              <br/>
              <?php 

$form_fields = '';
$app_name= 'hubspot';
$sperse_form = new AWP_Form_Fields($app_name);

$form_fields = $sperse_form->awp_wp_text_input(
    array(
        'id'            => "awp_messagebird_display_name",
        'name'          => "awp_messagebird_display_name",
        'value'         => $display_name,
        'placeholder'   =>  esc_html__('Enter an identifier for this account', 'automate_hub' ),
        'label'         =>  esc_html__('Display Name', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $sperse_form->awp_wp_text_input(
    array(
        'id'            => "awp_hubspot_api_token",
        'name'          => "awp_hubspot_api_token",
        'value'         => $awp_messagebird_api_key,
        'placeholder'   => esc_html__( 'Enter your MessageBird API Key', 'automate_hub' ),
        'label'         =>  esc_html__( 'MessageBird API Key', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $sperse_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_messagebird_save_api_key',
    )
);


$form_fields .= $sperse_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $sperse_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$sperse_form->render($form_fields);

?>


          </div>

          <div class="wrap">
              <form id="form-list" method="post">
                          
                  
                  <input type="hidden" name="page" value="automate_hub"/>

                  <?php
                  $data=[
                              'table-cols'=>['account_name'=>'Display name','api_key'=>'API Key','spots'=>'Active Spots','active_status'=>'Active']
                      ];
                  $platform_obj= new AWP_Platform_Shell_Table('messagebird');
                  $platform_obj->initiate_table($data);
                  $platform_obj->prepare_items();
                  $platform_obj->display_table();
                          
                  ?>
              </form>
          </div>
      <?php
    }

    public function action_fields() { 
      ?>
      <script type="text/template" id="messagebird-action-template">
                  <?php

                    $app_data=array(
                            'app_slug'=>'messagebird',
                           'app_name'=>'Messagebird',
                           'app_icon_url'=>AWP_ASSETS.'/images/icons/messagebird.png',
                           'app_icon_alter_text'=>'Messagebird Icon',
                           'account_select_onchange'=>'',
                           'tasks'=>array(
                                        'create_contact'=>array(
                                                            

                                                        ),

                                    ),
                        ); 

                        require (AWP_VIEWS.'/awp_app_integration_format.php');
                ?>
      </script>
  <?php
    }
}

$Awp_Messagebird = AWP_Messagebird::get_instance();

function awp_messagebird_save_integration() {
  AWP_Messagebird::save_integration();
}

function awp_messagebird_send_data( $record, $posted_data ) {
    $temp = json_decode(($record["data"]), true );
    $temp    = $temp["field_data"];
    $platform_obj= new AWP_Platform_Shell_Table('messagebird');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_key=$temp->api_key;
    $decoded_data = AWP_Messagebird::decode_data($record, $posted_data);

    $task = $decoded_data["task"]; 
    $data = $decoded_data["data"]; 
    $api_key = 'AccessKey '.$api_key;

    if( $task == "create_contact" ) {
        $fields =  [];
        foreach ( $data as $key => $value ) {
            if(strpos($key, 'dis') !== false){
                continue;
            }
            $fields[$key] = awp_get_parsed_values( $data[$key], $posted_data );
        }

        $args = [
            'headers' => [
                'Authorization' => $api_key,
            ],
            'body'=> $fields
        ];

        $response  = wp_remote_post(AWP_Messagebird::$contact_url , $args );

        $args['headers']['Authorization']='AccessKey XXXXXXX';
        awp_add_to_log( $response, AWP_Messagebird::$contact_url, $args, $record );
        return $response;
    }
}


function awp_messagebird_resend_data( $log_id,$data,$integration ) {

    $temp    = json_decode( $integration["data"], true );
    $temp    = $temp["field_data"];


    $platform_obj= new AWP_Platform_Shell_Table('messagebird');
    $temp=$platform_obj->awp_get_platform_detail_by_id($temp['activePlatformId']);
    $api_key=$temp->api_key;
    if(!$api_key ) {
        return;
    }
    
   

    $data=stripslashes($data);
    $data=preg_replace('/\s+/', '',$data); 
    $data=str_replace('"{', '{', $data);
    $data=str_replace('}"', '}', $data);        
    $data=json_decode($data,true);
    $body=$data['args']['body'];
    $url=$data['url'];
    if(!$url){
        $response['success']=false;
        $response['msg']="Syntax Error! Request is invalid";
        return $response;
    }

    $api_key = 'AccessKey '.$api_key;


        $args = [
            'headers' => [
                'Authorization' => $api_key,
            ],
            'body'=> $body
        ];

        $ret  = wp_remote_post($url , $args );

        $args['headers']['Authorization']='AccessKey XXXXXXX';
        awp_add_to_log( $ret, $url, $args, $integration );

    $response['success']=true;    
    return $response;
}
