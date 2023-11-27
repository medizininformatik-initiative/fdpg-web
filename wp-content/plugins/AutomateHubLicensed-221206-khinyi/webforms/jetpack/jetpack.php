<?php

add_filter('awp_form_providers', 'awp_jetpack_add_provider');
function awp_jetpack_add_provider($providers)
{
  if (is_plugin_active('jetpack/jetpack.php')) {
    $providers['jetpack'] = esc_html__('Jetpack Form', 'automate_hub');
  }

  return $providers;
}

function awp_jetpack_get_forms($form_provider)
{
  if ($form_provider != 'jetpack') {
    return;
  }

  global $wpdb;

  $result = $wpdb->get_results("SELECT p.ID, p.post_title FROM {$wpdb->posts} p INNER JOIN (SELECT DISTINCT post_id FROM {$wpdb->postmeta} WHERE meta_key LIKE '_g_feedback_shortcode_%') pm ON p.ID= pm.post_id", ARRAY_A);

  $forms = array();
  foreach ($result as $key => $value) {
    $forms[$value["ID"]] = isset($value["post_title"]) ? $value["post_title"] :'' ;
  }
  return $forms;
}

function awp_jetpack_get_form_fields($form_provider, $form_id)
{
  if ($form_provider != 'jetpack') {
    return;
  }

  $args  = array('ID' =>  $form_id, 'posts_per_page' => -1);
  $post = (array) get_posts($args);
  $content  = $post[0]->post_content;
  $fields = explode("<!-- wp:jetpack/", $content);

  $field_names = array();

  for ($i = 2; $i < count($fields) - 1; $i++) {
    $field_name = explode(" ", $fields[$i])[0];
    $field_name = explode("-", $field_name)[1];
    $field_names[$field_name] = $field_name;
  }

  return $field_names;
}

add_action("grunion_after_message_sent", "awp_jetpack_submission", 10, 7);
function awp_jetpack_submission($post_id, $to, $subject, $message, $headers, $all_values, $extra_values)
{
  $posted_data = array();
  foreach ($all_values as $key => $value) {
    if (is_numeric($key[0])) {
      $field_name = get_default_field_name_from_label(explode("_", $key)[1]);
      $posted_data[$field_name] = $value;
    } else {
      continue;
    }
  }
  $posted_data["submission_date"] = date("Y-m-d");
  $posted_data["user_ip"]         = awp_get_user_ip();
  //tracking info
  include AWP_INCLUDES.'/tracking_info_cookies.php';

  global $wpdb;

  $post_data = $wpdb->get_results($wpdb->prepare("SELECT p.post_parent FROM {$wpdb->posts} p WHERE ID=%d",$post_id), ARRAY_A);
  $form_id = $post_data[0]['post_parent'];
  $saved_records = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}awp_integration WHERE status = 1 AND form_provider = 'jetpack' AND form_id = %d",$form_id), ARRAY_A);

  foreach ($saved_records as $record) {
    $action_provider = isset($record['action_provider']) ? $record['action_provider']:'';
    awp_add_queue_form_submission("awp_{$action_provider}_send_data",$record,$posted_data);
  }
}


function get_default_field_name_from_label($label)
{
  $str = null;
  switch ($label) {
    case  esc_html__('Text', 'automate_hub'):
      $str = 'text';
      break;
    case esc_html__('Name', 'automate_hub');
      $str =  'name';
      break;
    case esc_html__('Email', 'automate_hub');
      $str = 'email';
      break;
    case esc_html__('Website', 'automate_hub');
      $str = 'url';
      break;
    case esc_html__('Date', 'automate_hub');
      $str =  'date';
      break;
    case esc_html__('Phone', 'automate_hub');
      $str = 'telephone';
      break;
    case  esc_html__('Message', 'automate_hub');
      $str = 'textarea';
      break;
    case  esc_html__('Checkbox', 'automate_hub');
      $str = 'checkbox';
      break;
    case esc_html__('Choose several', 'automate_hub');
      $str = 'checkbox-multiple';
      break;
    case esc_html__('Choose one', 'automate_hub');
      $str = 'radio';
      break;
    case esc_html__('Select one', 'automate_hub');
      $str = 'select';
      break;
    case esc_html__('Consent', 'automate_hub');
      $str =  'consent';
      break;
    default:
      $str = null;
  }
  return $str;
}
