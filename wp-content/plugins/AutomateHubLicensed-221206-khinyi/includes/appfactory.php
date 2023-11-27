<?php
if(class_exists('Appfactory')){return;}
abstract class Appfactory {
    private static $_instances = array();

    public static function get_instance() {
        $class = get_called_class();
        if (!isset(self::$_instances[$class])) {
            self::$_instances[$class] = new $class();
        }
        return self::$_instances[$class];
    }

    public function __construct() {
        $this->init_filters();
        $this->init_actions();

        add_action( 'awp_settings_view', [$this, 'settings_view'], 10, 1 );
        add_action( 'awp_action_fields', [$this, 'action_fields']);
        add_action( 'awp_custom_script', [$this, 'load_custom_script']);
        
        add_filter( 'awp_settings_tabs', [$this, 'settings_tab'], 10, 1 );
        add_filter( 'awp_action_providers', [$this, 'action_provider'], 10, 1 );
    }

    abstract public function init_actions();
    abstract public function init_filters();
    abstract public function settings_tab( $tab );
    abstract public function action_provider( $providers );
    abstract public function settings_view( $current_tab );
    abstract public function action_fields();
    abstract public function load_custom_script();
    
    static public function save_integration() {
        $params = array();
        parse_str( awp_sanitize_text_or_array_field( $_POST['formData'] ), $params );
        $trigger_data      = isset( $_POST["triggerData"]) ? awp_sanitize_text_or_array_field( $_POST["triggerData"]) : array();
        $action_data       = isset( $_POST["actionData" ]) ? awp_sanitize_text_or_array_field( $_POST["actionData" ]) : array();
        $field_data        = isset( $_POST["fieldData"  ]) ? awp_sanitize_text_or_array_field( $_POST["fieldData"  ]) : array();

        $integration_title = isset( $trigger_data["integrationTitle"]) ? sanitize_text_field($trigger_data["integrationTitle"]) : "";
        $form_provider_id  = isset( $trigger_data["formProviderId"  ]) ? sanitize_text_field($trigger_data["formProviderId"  ]) : "";
        $form_id           = isset( $trigger_data["formId"          ]) ? sanitize_text_field($trigger_data["formId"          ]) : "";
        $form_name         = isset( $trigger_data["formName"        ]) ? sanitize_text_field($trigger_data["formName"        ]) : "";
        $action_provider   = isset( $action_data ["actionProviderId"]) ? sanitize_text_field($action_data ["actionProviderId"]) : "";
        $task              = isset( $action_data ["task"            ]) ? sanitize_text_field($action_data ["task"            ]) : "";
        $type              = isset( $params["type"] ) ? sanitize_text_field($params["type"]) : "";
        
        $all_data = array(
            'trigger_data' => $trigger_data,
            'action_data'  => $action_data,
            'field_data'   => $field_data
        );

        global $wpdb;

        $integration_table = $wpdb->prefix . 'awp_integration';

        if ( $type == 'new_integration' ) {

            $result = $wpdb->insert(
                $integration_table,
                array(
                    'title'           => $integration_title,
                    'form_provider'   => $form_provider_id,
                    'form_id'         => $form_id,
                    'form_name'       => $form_name,
                    'action_provider' => $action_provider,
                    'task'            => $task,
                    'data'            => json_encode( $all_data, true ),
                    'status'          => 1
                )
            );
            if($result){
                $platform_obj= new AWP_Platform_Shell_Table($action_provider);
                $activePlatformId = isset($field_data['activePlatformId']) ? sanitize_text_field($field_data['activePlatformId']) :'';
                $platform_obj->awp_add_new_spot($wpdb->insert_id,$$activePlatformId);
            }
        }

        if ( $type == 'update_integration' ) {

            $id = !empty($params['edit_id']) ? trim( sanitize_text_field( $params['edit_id'] ) ) : '';

            if ( $type != 'update_integration' &&  !empty( $id ) ) {
                exit;
            }

            $result = $wpdb->update( $integration_table,
                array(
                    'title'           => $integration_title,
                    'form_provider'   => $form_provider_id,
                    'form_id'         => $form_id,
                    'form_name'       => $form_name,
                    'data'            => json_encode( $all_data, true ),
                ),
                array(
                    'id' => $id
                )
            );
        }

        $return=array();
        $return['type']=$type;
        $return['result']=$result;
        $return['insertid']=$wpdb->insert_id;
        return $return;
    }

    static public function decode_data($record, $posted_data) {
        $record_data = isset($record["data"]) ? json_decode(($record["data"]), true ) :'';

        if( !empty($record_data["action_data"]) && array_key_exists( "cl", $record_data["action_data"] ) ) {
            if( !empty($record_data["action_data"]["cl"]["active"]) && ($record_data["action_data"]["cl"]["active"] == "yes") ) {
                if( !awp_match_conditional_logic( $record_data["action_data"]["cl"], $posted_data ) ) {
                    return;
                }
            }
        }
    
        $data = isset($record_data["field_data"]) ? $record_data["field_data"] :'' ;
        $task  = isset($record["task"]) ? $record["task"] :'' ;

        return ["task" => $task, "data" => $data];
    }
}
