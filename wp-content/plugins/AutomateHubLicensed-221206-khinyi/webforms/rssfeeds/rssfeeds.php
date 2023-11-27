<?php 

add_filter( 'awp_form_providers', 'awp_rssfeed_add_provider' );

function awp_rssfeed_add_provider( $providers ) {

    $providers['rssfeed'] = esc_html__( 'RSS Feed ', 'automate_hub' );

    return $providers;
}

function awp_rssfeed_get_forms( $form_provider ) {

    if ( $form_provider != 'rssfeed' ) {
        return;
    }
   

    $triggers=[
        '1' => 'New Item In Feed',
    ];

    return $triggers;
}

function awp_rssfeed_get_form_fields( $form_provider, $form_id ) {

    if ( $form_provider != 'rssfeed' ) {
        return;
    }
    // $_POST["awp_feed_url"]='https://automatehubultimate.faizanjaved.com/feed/';
    if(isset($_POST["awp_feed_url"]) && isset($_POST["awp_feed_cron"])){
    $feed_url = sanitize_text_field( $_POST["awp_feed_url"] );
    $cron_schedule = sanitize_text_field( $_POST["awp_feed_cron"] );

    $simpleXml = simplexml_load_file($feed_url, "SimpleXMLElement", LIBXML_NOCDATA);
    if(empty($simpleXml) || !isset($simpleXml->channel)){
        return [];
    }
    $cron_jobs=['hourly','twicedaily','daily','weekly'];
    if(!in_array($cron_schedule, $cron_jobs)){
        return [];
    }
    $data=json_decode(json_encode($simpleXml->channel->item[0]),true); 

    $fields=array();
    foreach ($data as $key => $value) {
        $fields[$key]=$key;
    }

    }    
    else{
            $fields=[];

    }


    return $fields;
}
    
function awp_rss_cron_submission( $posted_data, $integration_id ) {

    global $wpdb;

    // $posted_data                    = wp_list_pluck( $fields, 'value', 'id' );
    $posted_data["submission_date"] = date( "Y-m-d" );
    $posted_data["user_ip"]         = awp_get_user_ip();
    
    

    $query= $wpdb->prepare("SELECT * FROM {$wpdb->prefix}awp_integration WHERE status = 1 AND form_provider = 'rssfeed' AND id =%d ",$integration_id);
    $saved_records                  = $wpdb->get_results( $query, ARRAY_A );
    
    //tracking info
    include AWP_INCLUDES.'/tracking_info_cookies.php';

    foreach ( $saved_records as $record ) {
        $action_provider = isset($record['action_provider']) ? $record['action_provider']:'';

        $res=call_user_func("awp_{$action_provider}_send_data",$record,$posted_data);

    }
}


function awp_rss_cron_exec($data){

    $data=json_decode($data,true);
   
    $saved_integration_id=$data['saved_integration_id'];
    $form=sanitize_url($data['formData']);
    parse_str($form, $formData);
    $url=$formData['rssfeedtxt'];

    //fetch from url

    $rssobj=new RssFeedController();
    $newItems=$rssobj->fetchNewItems($url,$saved_integration_id);
    

  

    foreach ($newItems as $key => $item) {
        

        awp_rss_cron_submission($item,$saved_integration_id);
    }
    
    $feed_log=[
        'integration_id'=>$saved_integration_id,
        'url'=>$url,
        'last_item'=>$newItems[0],
    ];
    $res=$rssobj->updateFeedLog($feed_log);

}


$rss_actions_list=get_option('awp_rss_actions_list');
$rss_actions_list=empty($rss_actions_list)?[]:unserialize($rss_actions_list);
foreach ($rss_actions_list as $key => $rss_action_name) {
    add_action( 'bl_awp_rss_cron_hook_'.$rss_action_name[0], 'awp_rss_cron_exec' );

}




class RssFeedController{
    public $feed;
    public $saved_log;

    function __construct__(){
        $this->saved_log=array();
    }
    function loadFeed($url){
        $simpleXml = simplexml_load_file($url, "SimpleXMLElement", LIBXML_NOCDATA);
        if(empty($simpleXml) || !isset($simpleXml->channel)){
            return false;
        }

        $data=json_decode(json_encode($simpleXml->channel),true);
        $this->feed=$data;
    }
    function getFeed($url){
         if (empty($this->feed)){
            $this->loadFeed($url);
         }
         return $this->feed;
    }

    function getSavedLogs(){
        if(!empty($this->saved_log)){
            return $this->saved_log;
        }
        $this->saved_log=get_option('awp_rss_logs');
        if(empty($this->saved_log)){
            $this->saved_log=array();
            return [];
        }
        $this->saved_log=unserialize($this->saved_log);
        return $this->saved_log;
    }

    function updateFeedLog($data){
        if(empty($data['last_item'])){
            return false;
        }
        $existing_log=$this->getFeedLog($data['integration_id']);
       
        if($existing_log){
            $this->saved_log[(int)$existing_log[0]]=$data;
            
        }
        else{
            array_push($this->saved_log, $data);    
            
        }
        update_option('awp_rss_logs',serialize($this->saved_log));
        return true;
    }

    function getFeedLog($integration_id){
        $logs=$this->getSavedLogs();
        foreach ($logs as $key => $value) {
            if($value['integration_id'] == $integration_id){
                return [$key,$logs[$key]];
            }
        }
        return false;
    }

    function fetchNewItems($url,$integration_id){
        $feed_log=$this->getFeedLog($integration_id);
        $feed=$this->getFeed($url);
        $feed_items=$feed['item'];
        $new_items=array();
        $last_saved_item=isset($feed_log[1]['last_item'])?$feed_log[1]['last_item']:"" ;


        for ($i=0; $i < count($feed_items) ; $i++) { 
            
            if(serialize($feed_items[$i]) == serialize($last_saved_item)){
                break;
            }
            else{
                array_push($new_items, $feed_items[$i]);
            }

        }
        return $new_items;

    }
}


