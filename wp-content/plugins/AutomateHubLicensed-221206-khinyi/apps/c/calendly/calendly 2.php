<?php
class AWP_Calendly extends Appfactory
{
    public function init_actions()
    {
        add_action('admin_post_awp_calendly_save_api_token', [$this, 'awp_save_calendly_api_token'], 10, 0);
        add_action('wp_ajax_awp_get_calendly_list', [$this, 'awp_get_calendly_list'], 10, 0);
    }

    public function init_filters()
    {
        add_filter('awp_platforms_connections', [$this, 'awp_calendly_platform_connection'], 10, 1);
    }

    public function load_custom_script()
    {}


    public function action_provider($actions)
    {
        return $actions;
    }

    public function settings_tab($tabs)
    {
        $tabs['calendly'] = array('name' => esc_html__('Calendly', 'automate_hub'), 'cat' => array('esp'));
        return $tabs;
    }

    public function settings_view($current_tab)
    {
        if ($current_tab != 'calendly') {
            return;
        }
        $nonce = wp_create_nonce("awp_calendly_settings");
        $api_token = isset($_GET['api_key']) ? $_GET['api_key'] : "";
        $id = isset($_GET['id']) ? $_GET['id'] : "";
        ?>
        <div class="platformheader">
            <a href="https://sperse.io/go/calendly" target="_blank"><img src="<?=AWP_ASSETS;?>/images/logos/calendly.png" width="202" height="50" alt="Calendly Logo"></a><br /><br />
            <?php 
                    require_once(AWP_INCLUDES.'/class_awp_updates_manager.php');
                    $instruction_obj = new AWP_Updates_Manager($_GET['tab']);
                    $instruction_obj->prepare_instructions();
                        
                ?>

                <br />
                <?php 

$form_fields = '';
$app_name= 'calendly';
$calendly_form = new AWP_Form_Fields($app_name);

$form_fields .= $calendly_form->awp_wp_text_input(
    array(
        'id'            => "awp_calendly_api_token",
        'name'          => "awp_calendly_api_token",
        'value'         => $api_token,
        'placeholder'   => esc_html__( 'Enter your Calendly API Key', 'automate_hub' ),
        'label'         =>  esc_html__( 'Calendly API Key', 'automate_hub' ),
        'wrapper_class' => 'form-row',
        'show_copy_icon'=>true
        
    )
);

$form_fields .= $calendly_form->awp_wp_hidden_input(
    array(
        'name'          => "action",
        'value'         => 'awp_calendly_save_api_token',
    )
);


$form_fields .= $calendly_form->awp_wp_hidden_input(
    array(
        'name'          => "_nonce",
        'value'         =>$nonce,
    )
);
$form_fields .= $calendly_form->awp_wp_hidden_input(
    array(
        'name'          => "id",
        'value'         =>wp_unslash($id),
    )
);


$calendly_form->render($form_fields);

?>
        </div>

        <div class="wrap">
                <form id="form-list" method="post">

                    <input type="hidden" name="page" value="automate_hub"/>
                    <?php
$data = [
            'table-cols' => ['api_key' => 'API Key', 'active_status' => 'Active'],
        ];
        $platform_obj = new AWP_Platform_Shell_Table('calendly');
        $platform_obj->initiate_table($data);
        $platform_obj->prepare_items();
        $platform_obj->display_table();
        ?>
                </form>
        </div>
    <?php
}

    public function awp_save_calendly_api_token()
    {
        if (!current_user_can('administrator')) {
            die(esc_html__('You are not allowed to save changes!', 'automate_hub'));
        }
        // Security Check
        if (!wp_verify_nonce($_POST['_nonce'], 'awp_calendly_settings')) {
            die(esc_html__('Security check Failed', 'automate_hub'));
        }
        $api_token = sanitize_text_field($_POST["awp_calendly_api_token"]);
        // Save tokens
        $platform_obj = new AWP_Platform_Shell_Table('calendly');
        $platform_obj->save_platform(['api_key' => $api_token]);
        AWP_redirect("admin.php?page=automate_hub&tab=calendly");
    }
    public function action_fields(){}

}
$AWP_Calendly = new AWP_Calendly();
add_action( 'wp_enqueue_scripts', 'filter_footer');
function filter_footer(){
    wp_enqueue_script('awp-calendly-script', AWP_URL."/apps/c/calendly/calendly.js", array('jquery'),false,true);
}