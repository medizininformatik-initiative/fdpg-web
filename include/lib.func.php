<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

/**
 * function to create a md5 token for any optin process.
 *
 * creates a md5 token for a double-optin process or magic link.
 *
 * @since 0.0.1
 *
 * @param integer $length token length

 * @return string ready to use md5 token.
 */
function create_token($length=64) {
    $token = md5(uniqid(rand(), true));
    $token = substr($token, 0, $length);
    return $token;
} // end function

function send_html_email($to, $subject, $message, $from, $from_name, $smtp_server, $smtp_port, $smtp_user, $smtp_pass) {
    $mail = new PHPMailer();
    $mail->IsSMTP();

    $mail->SMTPKeepAlive = true;
    $mail->Mailer= "smtp";
    $mail->SMTPSecure = "tls";
    $mail->CharSet = 'UTF-8';

    $mail->SMTPAuth = true;
    $mail->Host = $smtp_server;
    $mail->Port = $smtp_port;
    $mail->Username = $smtp_user;
    $mail->Password = $smtp_pass;
    $mail->From = $from;
    $mail->FromName = $from_name;
    $mail->AddAddress($to);
    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->IsHTML(true);

    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    $mail->Send();
    return $mail;
} // end function

function read_email_template_file($file) {
    $fp = fopen($file, "r");
    $message = fread($fp, filesize($file));
    fclose($fp);
    return $message;
} // end function

function execute_sql_queries($sql_queries){
    $servername = DB_SERVER;
    $username = DB_USER;
    $password = DB_PASS;
    $dbname = DB_NAME;

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error)
    {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert download information into table tmfev114_sync
    foreach($sql_queries as $sql){
        if ($conn->query($sql) === TRUE)
        {
            //echo 'New record created successfully' . br;
            $bla = 0;
        }
        else
        {
            echo 'Error: ' . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close();
}

function execute_custom_sql_query($sql,$link) {
    $result = mysqli_query($link, $sql);
    return $result;
} // end function

/*
title
firstname
lastname
institution
street
zipcode
city
email
phone
*/

/*
 *  ################
 *  HELPER-FUNCTIONS
 *  ################
 */
// create array containing element-names or values for creating insert-sql-statements
function create_value_array($values){
    $value_array = [];
    foreach($values as $value){
        $value_array[] = $value;
    }

    return $value_array;
}

function send_slack_message($message, $channel = '#general', $username = 'webhookbot', $icon = ':ghost:') {
    $data = "payload=" . json_encode(array(
            "channel"       =>  "#{$channel}",
            "text"          =>  $message,
            "username"      =>  $username,
            "icon_emoji"    =>  $icon
        ));
    $ch = curl_init("https://hooks.slack.com/services/T00000000/B00000000/XXXXXXXXXXXXXXXXXXXXXXXX");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

/*
 *  ##################
 *  DATABASE-FUNCTIONS
 *  ##################
 */
function db_connect() {
    //$connection = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    $connection = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    confirm_db_connect($connection);
    return $connection;
}

function db_disconnect($connection) {
    if(isset($connection)) {
        $connection->close();
    }
}

function confirm_db_connect($connection) {
    if($connection->connect_errno) {
        $msg = "Database connection failed: ";
        $msg .= $connection->connect_error;
        $msg .= " (" . $connection->connect_errno . ")";
        exit($msg);
    }
}

function confirm_result_set($result_set) {
    if (!$result_set) {
        exit("Database query failed.");
    }
}

function get_dataset($table,$link) {
    $sql = "SELECT * FROM $table";
    $result = mysqli_query($link, $sql);
    return $result;
} // end function

function get_dataset_where($table, $where, $sorted ,$link) {
    $sql = "SELECT * FROM $table WHERE " . $where . " " . $sorted . ";";
    $result = mysqli_query($link, $sql);
    return $result;
} // end function

function get_dataset_sorted($table,$link, $orderKey) {
    $sql = "SELECT * FROM $table ORDER BY $orderKey ASC";
    $result = mysqli_query($link, $sql);
    return $result;
} // end function

/*
function get_datasets($table,$link) {
    $sql = "SELECT * FROM $table";
    $result = mysqli_query($link, $sql);
    return $result;
} // end function
*/

function update_dataset($table, $set_condition, $where_condtion, $link) {
    $sql = "UPDATE $table SET $set_condition WHERE $where_condtion";
    $result = mysqli_query($link, $sql);
    return $result;
} // end function

// generate an insert query for html formular (optin call)
function build_insert_query($table, $values, $element_names) {

    $query = "INSERT INTO $table (";

    foreach ($element_names as $element_name) {
        $query .= "$element_name, ";
    } // end for

    $query = substr($query, 0, -2);
    $query .= ") VALUES (";

    foreach ($values as $value) {
        $query .= "'$value', ";
    } // end for

    $query = substr($query, 0, -2);
    $query .= ")";
    return $query;
} // end function

function build_update_query($table, $values, $element_names, $where_condition) {

    $query = "UPDATE $table SET ";

    for ($i_counter = 0; $i_counter < count($element_names); $i_counter++) {
        $query .= "`$element_names[$i_counter]`=" . $values[$i_counter] . ", ";
    } // end for

    $query = substr($query, 0, -2);

    $query .= " WHERE " . $where_condition;

    return $query;
} // end function

function count_value($table, $column, $value, $link) {

    if ($value=="*") {
        $query = "SELECT * FROM $table";
    } else {
        $query = "SELECT * FROM $table WHERE $column = '$value'";
    } // end if

    $result = mysqli_query($link, $query);
    $count = mysqli_num_rows($result);

    return $count;
} // end function

function get_single_column_value($table, $aim_col, $column, $value, $link) {

    if ($value=="*") {
        $query = "SELECT * FROM $table";
    } else {
        $query = "SELECT $aim_col FROM $table WHERE $column = '$value'";
    } // end if

    $result = mysqli_query($link, $query);
    $res = mysqli_fetch_assoc($result);

    return $res;
} // end function

function get_distinct_column_value($table, $aim_col, $column, $value, $link, $bValOnly = false) {

    if ($value=="*") {
        $query = "SELECT DISTINCT $aim_col FROM $table";
    } else {
        $query = "SELECT DISTINCT $aim_col FROM $table WHERE $column = '$value' ";
    } // end if

    $result = mysqli_query($link, $query);

    if($bValOnly){
        $res = mysqli_num_rows($result);
    }else{
        $res = mysqli_fetch_assoc($result);

    }

    return $res;
} // end function

// TO-DO Workaround ausbauen (table sync_details)
function get_distinct_column_values($table, $aim_col, $column, $value, $link,  $bValOnly = false, $bCustomWhere = false, $sCustomWhere = "") {

    if ($value=="*" && !$bCustomWhere) {
        $query = "SELECT DISTINCT $aim_col FROM $table";
    } else {
        $query = "SELECT DISTINCT $aim_col FROM $table WHERE $column = '$value' ";
    } // end if

    if($bCustomWhere){
        $query = "SELECT DISTINCT $aim_col FROM $table WHERE $sCustomWhere";
    }

    // BULLSHIT-CODE --- added
    if($table == DB_PREFIX . "sync_details"){
        $query .= " ORDER BY $aim_col ASC";
    }

    $result = mysqli_query($link, $query);

    if($bValOnly){
        $res = mysqli_num_rows($result);
    }else{
        $res = [];
        while($row = mysqli_fetch_assoc($result)){
            $res[] = $row;
        }
    }

    return $res;
} // end function

function get_avg_col_value($table, $aim_col, $value, $link, $whereCond) {

    if ($value=="*") {
        $query = "SELECT avg($aim_col)  FROM $table";
    } else {
        $query = "SELECT avg($aim_col) FROM $table WHERE $whereCond";
    } // end if

    $result = mysqli_query($link, $query);
    $res = mysqli_fetch_assoc($result);

    return $res;
} // end function

function get_max_col_value($table, $aim_col, $column, $value, $link, $whereCond) {

    if ($value=="*") {
        $query = "SELECT max($aim_col)  FROM $table";
    } else {
        $query = "SELECT max($aim_col) FROM $table WHERE $whereCond";
    } // end if

    $result = mysqli_query($link, $query);
    $res = mysqli_fetch_assoc($result);

    return $res;
} // end function

function get_val_with_custom_where_cond($table, $aim_col, $link, $whereCond, $bValOnly = false){
    $query = "SELECT $aim_col FROM $table WHERE $whereCond";

    $result = mysqli_query($link, $query);
    $res = mysqli_fetch_assoc($result);

    if($bValOnly){
        $res = mysqli_num_rows($result);
    }

    return $res;
} // end function

function get_last_drop($table, $aim_col, $column, $value, $link) {

    if ($value=="*") {
        $query = "SELECT max($aim_col) FROM $table";
    } else {
        $query = "SELECT max($aim_col) FROM $table WHERE $column = '$value'" ;
    } // end if

    $result = mysqli_query($link, $query);
    $res = mysqli_fetch_assoc($result);

    return $res;
} // end function

// execute sql-queries to get stored information from the database
function get_information_from_database($sql){
    $servername = DB_SERVER;
    $username = DB_USER;
    $password = DB_PASS;
    $dbname = DB_NAME;

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $result = $conn->query($sql);
    $array = [];

    while($row = mysqli_fetch_assoc($result)){
        $array[] = $row;
    }

    $conn->close();
    return $array;
}
//endregion

function get_last_two_drops($location_id, $link) {
    $query = "SELECT DISTINCT `date_delivery` FROM `tmfev114_sync_details` WHERE `location_id` = " . $location_id . " ORDER BY `date_delivery` DESC LIMIT 2; ";

    $result = mysqli_query($link, $query);
    //$res = mysqli_fetch_assoc($result);
    $dates = [];
    foreach($result as $date){
        $dates[] = strtotime($date["date_delivery"]);
    }

    $color = "";
    if(count($dates) != 2){
        $color = "lightgray";
    }
    else if(count($dates) == 2)
    {
        $diff = $dates[0] - $dates[1];
        if($diff < 86400){
            $color = "green";
        }
        else if($diff >= 86400 && $diff < 604800){
            $color = "yellow";
        }
        else if($diff >= 604800){
            $color = "red";
        }
        else{
            $color = "lightgray";
        }
    }

    return $color;
} // end function

function set_database_icon_color($last_drop) {
    $color = [];
    try{
        $curDate = new DateTime();
        $cur_date = $curDate->format('Y-m-d H:i:s');
        $curDate = strtotime($cur_date);

        if($last_drop !=""){
            $lastDrop = new DateTime($last_drop);
        } else{
            $lastDrop = new DateTime("2022-08-28 00:00:00");
        }
        $lastDrop = $lastDrop->format('Y-m-d H:i:s');
        $lastDrop = strtotime($lastDrop);

        $diff = $curDate - $lastDrop;

        if($diff < (86400 * 1.5)){
            $color[] = "#008f00";
            $color[] = "Letzte Lieferung innerhalb von 24h";
        }
        else if($diff >= (86400 * 1.5) && $diff < 604800){
            $color[] = "#ffcc00";
            $color[] = "Letzte Lieferung innerhalb von 7 Tagen";
        }
        else if($diff >= 604800){
            $color[] = "#de0000";
            $delivery_last_days = (int)($diff / (60*60*24));
            $color[] = "Letzte Lieferung vor " . $delivery_last_days . " Tagen";
        }
        else{
            $color = "lightgray";
        }
    } catch (\Exception $e){
        var_dump($e);
        $color = "gray";
    }

    return $color;
} // end function

function print_location_options($link){
    $locations = get_dataset_sorted(DB_PREFIX."locations", $link, "name_short");
    $options = "";

    foreach($locations as $loc){
        $options .= '<option value ="' . $loc["id"] . '">' . $loc["name_short"] . '</option>';
    }

    return $options;
}

function create_card($cid,$link) {

    $aCard = array();
    $aLocations = array();

    $sCardTemplate = '
	<div class=" card [ is-collapsed ] ">
      <div class="card__inner [ js-expander ]" id="##card-id##">
        <span style="margin-bottom: 10px">##location##</span>
        <div style="display: flex; padding-top: 15px">
            <i data-toggle="tooltip" title="Am FDPG angeschlossen" style="color: white; width: 100%" class="fas fa-link"></i>
            <i data-toggle="tooltip" title="###tooltiptext###" style="color: ###database-color###" class="fas fa-database">
            
</i>
        </div>
        
      </div>
      <div class="card__expander">
        <i class="fa fa-window-close [ js-collapser ]" style="font-size: 1.5em;"></i>     
            <div class="divTable" style="width: 100%;" >
            <div class="divTableBody">
            <div class="divTableRow">
            <div class="divTableCell">Status Values: 0/0</div>
            <div class="divTableCell">FHIR Server: Name (Version)</div>
            <div class="divTableCell">Average Runtime: 0.0000(s)</div>
            </div>
            <div class="divTableRow">
            <div id="idLastDrop" class="divTableCell" style="cursor: pointer">Last Drop: 0000-00-00 00:00:00</a></div>
            <div class="divTableCell">DIZ Members: ###diz-members###</div>
            <div class="divTableCell">DAILY QUESTS: <a href="#" style="color: #1abc9c; text-decoration: none;">0/0</a> </div>
            
            </div>
            <div class="divTableRow">
            <div class="divTableCell">ERRORS: ###failed-stattab-responses###</div>
            <div class="divTableCell">&nbsp;</div>
            <div class="divTableCell">&nbsp;</div>
            </div>
            <div class="divTableRow">
            <div class="divTableCell">&nbsp;</div>
            <div class="divTableCell">&nbsp;</div>
            </div>
            </div>
            </div>
      </div>
	</div>
	';

    $sCardTemplateNoDelivery = '
	<div class=" card [ is-collapsed ] ">
      <div class="card__inner" id="##card-id##">
        <span style="padding-bottom: 10px">##location##</span>
        <br>
        <!--<span style="font-size: 12px">-no deliveries-</span>
        <br>-->
        <div style="display: flex; padding-top: 10px">
        <i data-toggle="tooltip" title="Nicht am FDPG angeschlossen" class="fa fa-times"></i>
        </div>
      </div>
	</div>
	';

    if ($cid=="all") {
        $aLocations = get_dataset(DB_PREFIX."locations",$link);
        $locations = [];
        foreach ($aLocations as $loc123){
            $locations[] = $loc123;
        }

        $aLocations_all = get_dataset(DB_PREFIX."locations_all",$link);

        $aLocations_short_names = [];
        foreach ($aLocations as $loc){
            $aLocations_short_names[] = strtolower($loc["name_short"]);
        }

        $aLocations_all_short_names = [];
        foreach ($aLocations_all as $loc){
            $aLocations_all_short_names[] = strtolower($loc["loc_code"]);
        }

        $aLocations_no_delivery = [];
        foreach($aLocations_all as $loc) {
            if(!in_array(strtolower($loc["loc_code"]), $aLocations_short_names)){
                $aLocations_no_delivery[] = strtolower($loc["loc_code"]);
            }
        }

        $iCountLoc = count_value(DB_PREFIX."locations", "name_short", "*", $link);
        //$iMaxValueFhirProfile = count_value(DB_PREFIX."fhirprofiles", "name_short", "*", $link);
        $fhir_profiles_db = get_dataset(DB_PREFIX . "fhirprofiles", $link);

        $unique_profiles = [];
        foreach($fhir_profiles_db as $prof){
            if(!in_array($prof["fhir_profile"], $unique_profiles)){
                $unique_profiles[] = $prof["fhir_profile"];
            }
        }

        $iMaxValueFhirProfile = count($unique_profiles);

        // update each location card with location specific values
        foreach($locations as $row){
            // replace location name
            if(in_array(strtolower($row["name_short"]), $aLocations_all_short_names)){
                //$sOutPut_Locations = str_replace("##location##", $row["name_short"], $sCardTemplate);
                $sOutPut_Locations = str_replace("##location##", location_city_mapper($row["name_short"]), $sCardTemplate);

                // replace last drop
                $loc_id = get_single_column_value(DB_PREFIX."locations", "id", "name_short", $row["name_short"], $link);
                $iLoc_id = $loc_id["id"];
                $last_drop = get_last_drop(DB_PREFIX."sync_details", "date_delivery", "location_id", $iLoc_id, $link);
                $sLastDrop = $last_drop["max(date_delivery)"];
                $sLastDrop_conv = convertDate($sLastDrop);
                $sOutPut_Locations = str_replace("Last Drop: 0000-00-00 00:00:00", "Last Drop: " . $sLastDrop_conv, $sOutPut_Locations);

                //$dbcolor = get_last_two_drops($iLoc_id, $link);
                $dbcolor = set_database_icon_color($sLastDrop);
                $sOutPut_Locations = str_replace("###database-color###", $dbcolor[0] . "; width: 100%", $sOutPut_Locations);
                $sOutPut_Locations = str_replace("###tooltiptext###", $dbcolor[1], $sOutPut_Locations);

                $sOutPut_Locations = str_replace("Last Drop: " . $sLastDrop_conv,"Last Drop: " . '<a href="' . $_SERVER['PHP_SELF'] . '?list=1&loc=' . $loc_id["id"] . '#list_profiles_location_detail"  style="color: #1abc9c; text-decoration: none;">' . $sLastDrop_conv , $sOutPut_Locations);

                $sOutPut_Locations = str_replace("##card-id##", 'card_id_' . $iLoc_id, $sOutPut_Locations);

                // replace server-information
                $srv_id = get_distinct_column_value(DB_PREFIX."sync_details", "server_id", "location_id", $iLoc_id, $link);
                $srv_info = get_single_column_value(DB_PREFIX."servers", "name, version", "id", $srv_id["server_id"], $link);
                if($srv_info["name"] == ""){
                    $sSrvInfo = "N/A";
                } else{
                    $sSrvInfo = $srv_info["name"] . " (" . $srv_info["version"] . ")";
                }

                $sOutPut_Locations = str_replace("FHIR Server: Name (Version)", 'FHIR Server: <a href="#" style="color: #1abc9c; text-decoration: none;">' . $sSrvInfo . '</a>', $sOutPut_Locations);

                // replace average-runtime
                $whereCond = "`location_id` = '$iLoc_id' AND `date_delivery` = '$sLastDrop'";
                $avg_runtime = get_avg_col_value(DB_PREFIX."sync_details", "qruntime", "", $link, $whereCond);

                $sAvgTime = $avg_runtime["avg(qruntime)"];
                $fAvgTime = (float)$sAvgTime;
                $sAvgTime = sprintf("%.3f", $fAvgTime);
                $sOutPut_Locations = str_replace("Average Runtime: 0.0000(s)", 'Average Runtime: <a href="#" style="color: #1abc9c; text-decoration: none;">' . $sAvgTime . '(s)</a>', $sOutPut_Locations);

                // replace value-information
                //$iCurrValueFhirProfile = get_distinct_column_value(DB_PREFIX."sync_details", "name", "location_id", $iLoc_id, $link, true);
                $iCurrValueFhirProfile = execute_custom_sql_query("SELECT DISTINCT `name` FROM `" . DB_PREFIX . "sync_details` WHERE `location_id` = " . $iLoc_id . " AND `status` = 'success';", $link)->num_rows;
                $sOutPut_Locations = str_replace("Status Values: 0/0", 'Status Values: <a href="dashboard.php?statusvalues&locid=' . $iLoc_id . '#list_status_table" style="color: #1abc9c; text-decoration: none;">' . $iCurrValueFhirProfile . '/' . $iMaxValueFhirProfile.'</a>', $sOutPut_Locations);

                // replace diz-members
                $diz_members = execute_custom_sql_query("SELECT * FROM `" . DB_PREFIX . "users_stattab` WHERE `location_name_short`=" . $iLoc_id . " AND `status`=1", $link)->num_rows;
                //$diz_members = count_value(DB_PREFIX . "users_stattab", "location_name_short", $iLoc_id, $link);
                $sOutPut_Locations = str_replace("###diz-members###", '<a href="/dashboard.php?members&locid=' . $iLoc_id . '" style="color: #1abc9c; text-decoration: none;">' . $diz_members .'</a>', $sOutPut_Locations);

                // replace failed stattab responses
                $location_report_errors = get_dataset_where(DB_PREFIX . "sync_details", "location_id=" . $iLoc_id . " AND status='failed' AND date_delivery >= '". start_report_date . "'" , "", $link);
                $errors = $location_report_errors -> num_rows;
                $sOutPut_Locations = str_replace("###failed-stattab-responses###", '<a href="/dashboard.php?errorslocation&locid=' . $iLoc_id . '#list_error_table" style="color: red; text-decoration: none;">' . $errors .'</a>', $sOutPut_Locations);
                //$sOutPut_Locations = str_replace("###failed-stattab-responses###", '<a href="#" style="color: red; text-decoration: none;">' . $errors .'</a>', $sOutPut_Locations);

                echo $sOutPut_Locations;
            }
        } // end while

        foreach($aLocations_no_delivery as $loc){
           //$sOutPut_Locations2 = str_replace("##location##", $loc, $sCardTemplateNoDelivery);
           $sOutPut_Locations2 = str_replace("##location##", location_city_mapper($loc), $sCardTemplateNoDelivery);
           echo $sOutPut_Locations2;
        }
    } // end if

    return NULL;
} // end function

function create_diz_beat_for_all_locations($link){
    echo "var chartliexample8 = echarts.init(document.getElementById('lineChart8'));";

    $tempLocId = 1;
    //$temp_beat = get_beat("sync", "", $link);
    $sChartDate = "";
    $sChartVal = "";
    $sChartValMax = "";
    //$sChartValAvg = "";
    $sChartValLocDeliveries = "";
    $sChartValLocDeliveriesAllLoc = "";

    $custom_sql_query = "SELECT DISTINCT `date_delivery` FROM `" . DB_PREFIX . "sync_details` ORDER BY `date_delivery` ASC; ";
    $aDrops = execute_custom_sql_query($custom_sql_query, $link);

    //$aDrops = get_distinct_column_values(DB_PREFIX . "sync_details", "`date_delivery`", "`location_id`", "*", $link, "", true, "`date_delivery` LIKE '%2022%' ORDER BY `date_delivery` ASC");
    //$aDropsAll = get_distinct_column_values(DB_PREFIX . "sync_details", "`date_delivery`", "", "*", $link);

    //$locName = get_single_column_value(DB_PREFIX . "locations", "name_long", "`id`", $tempLocId, $link);

    $iMaxProfiles = count_value(DB_PREFIX . "fhirprofiles", "", "*", $link);

    $aDropDaysOnly = [];
    foreach ($aDrops as $tempDrop){
        $drop = $tempDrop["date_delivery"];
        $dayOnly = explode(" ", $drop)[0];
        if(!in_array($dayOnly, $aDropDaysOnly)){
            $aDropDaysOnly[] = $dayOnly;
        }
    }

    $locProfValues = [];

    //foreach ($aDrops as $drop) {
    foreach ($aDropDaysOnly as $drop) {
        //$sChartDate .= "'" . convertDate($drop["date_delivery"]) . "',";
        $sChartDate .= "'" . $drop . "',";

        $sChartValMax .= $iMaxProfiles . ",";

        // Anzahl gelieferte standorte
        $custom_sql_query = "SELECT DISTINCT `location_id` FROM `" . DB_PREFIX . "sync_details` WHERE `date_delivery` LIKE '" . $drop . "%'; ";
        $custom_erg_delivered_locations = execute_custom_sql_query($custom_sql_query, $link);
        $sChartValLocDeliveries .= $custom_erg_delivered_locations->num_rows . ",";

        $location_ids = [];
        foreach ($custom_erg_delivered_locations as $loc) {
            if(!in_array($loc["location_id"], $location_ids)){
                $location_ids[] = $loc["location_id"];
            }
        }
        // Durchschnittswert aller standorte für profile, die einen wert größer 0 haben
        $mean_amount_profiles = [];
        foreach($location_ids as $loc_id){
            $custom_sql_query = "SELECT DISTINCT `name`,`response` FROM `" . DB_PREFIX . "sync_details` WHERE `date_delivery` LIKE '" . $drop . "%' AND `location_id` = " . $loc_id . " and `response` > 0;";
            $custom_erg_delivered_locations = execute_custom_sql_query($custom_sql_query, $link);
            $mean_amount_profiles[] = $custom_erg_delivered_locations->num_rows;
        }

        if (count($mean_amount_profiles) > 0) {
            $aAvgLocDeliveries = floor(array_sum($mean_amount_profiles) / count($mean_amount_profiles));
        } else {
            $aAvgLocDeliveries = 1;
        }

        $sChartValLocDeliveriesAllLoc .= $aAvgLocDeliveries . ",";
    }

    //$locProfValues = array_filter($locProfValues);
    //$iAvgProfiles = array_sum($locProfValues) / count($locProfValues);

    $sChartDate = substr($sChartDate, 0, -1);
    $sChartVal = substr($sChartVal, 0, -1);
    //$sChartValMax = substr($sChartValMax, 0, -1);
    $sChartValLocDeliveriesAllLoc = substr($sChartValLocDeliveriesAllLoc, 0, -1);

    //$erg = create_chartlie_for_all($sChartDate, $sChartVal, $sChartValLocDeliveriesAllLoc, $sChartValLocDeliveries, $iMaxProfiles, $iMaxProfiles, $locName["name_long"]);
    $erg = create_chartlie_for_all($sChartDate, $sChartVal, $sChartValLocDeliveriesAllLoc, $sChartValLocDeliveries, $iMaxProfiles, $iMaxProfiles);
    echo $erg;
}
function create_diz_beat_for_all_locations_old($link){
    echo "var chartliexample8 = echarts.init(document.getElementById('lineChart8'));";

    $tempLocId = 1;
    $temp_beat = get_beat("sync", "", $link);
    $sChartDate = "";
    $sChartVal = "";
    $sChartValMax = "";
    $sChartValAvg = "";
    $sChartValLocDeliveries = "";
    $sChartValLocDeliveriesAllLoc = "";

    $aDrops = get_distinct_column_values(DB_PREFIX . "sync_details", "`date_delivery`", "`location_id`", "*", $link, "", true, "`date_delivery` LIKE '%2022%' ORDER BY `date_delivery` ASC");
    //$aDropsAll = get_distinct_column_values(DB_PREFIX . "sync_details", "`date_delivery`", "", "*", $link);

    $locName = get_single_column_value(DB_PREFIX . "locations", "name_long", "`id`", $tempLocId, $link);

    $iMaxProfiles = count_value(DB_PREFIX . "fhirprofiles", "", "*", $link);

    $aDropDaysOnly = [];
    foreach ($aDrops as $tempDrop){
        $drop = $tempDrop["date_delivery"];
        $dayOnly = explode(" ", $drop)[0];
        if(!in_array($dayOnly, $aDropDaysOnly)){
            $aDropDaysOnly[] = $dayOnly;
        }
    }

    $locProfValues = [];

    //foreach ($aDrops as $drop) {
    foreach ($aDropDaysOnly as $drop) {
        //$sChartDate .= "'" . convertDate($drop["date_delivery"]) . "',";
        $sChartDate .= "'" . $drop . "',";

        //$whereCond = "`location_id`='" . $tempLocId . "' AND `date_delivery`='" . $drop["date_delivery"] . "' AND `response` > 0;";
        //$locProfileVal = get_val_with_custom_where_cond(DB_PREFIX . "sync_details", "name", $link, $whereCond, true);
        //$locProfValues[] = $locProfileVal;
        //$sChartVal .= $locProfileVal . ",";

        $sChartValMax .= $iMaxProfiles . ",";

        // Anzahl gelieferte standorte
        $custom_sql_query = "SELECT DISTINCT `location_id` FROM `" . DB_PREFIX . "sync_details` WHERE `date_delivery` LIKE '" . $drop . "%'; ";
        $custom_erg_delivered_locations = execute_custom_sql_query($custom_sql_query, $link);
        $sChartValLocDeliveries .= $custom_erg_delivered_locations->num_rows . ",";
        //echo  $custom_erg->num_rows;
        //$whereCond2 = "`date_delivery`='" . $drop["date_delivery"] . "' AND `response` > 0;";
        //$numLocDeliveries = get_distinct_column_values(DB_PREFIX . "sync_details", "`location_id`", "", "", $link, true, true, $whereCond2);

        $location_ids = [];
        foreach ($custom_erg_delivered_locations as $loc) {
            if(!in_array($loc["location_id"], $location_ids)){
                $location_ids[] = $loc["location_id"];
            }
        }

        //var_dump($location_ids);
        $mean_amount_profiles = [];
        foreach($location_ids as $loc_id){
            $custom_sql_query = "SELECT DISTINCT `name`,`response` FROM `" . DB_PREFIX . "sync_details` WHERE `date_delivery` LIKE '" . $drop . "%' AND `location_id` = " . $loc_id . " and `response` > 0;";
            $custom_erg_delivered_locations = execute_custom_sql_query($custom_sql_query, $link);
            $mean_amount_profiles[] = $custom_erg_delivered_locations->num_rows;
        }

        if (count($mean_amount_profiles) > 0) {
            $aAvgLocDeliveries = floor(array_sum($mean_amount_profiles) / count($mean_amount_profiles));
        } else {
            $aAvgLocDeliveries = 1;
        }

        // Durchschnitt aller Standorte die Profile geliefert haben > 0
        //$locDeliveries = get_distinct_column_values(DB_PREFIX . "sync_details", " location_id", "", "", $link, false, true, $whereCond2);
        //$aLocDeliveries = [];
        //foreach ($locDeliveries as $loc) {
        //    $whereCond4 = "`location_id`='" . $loc["location_id"] . "' AND `date_delivery`='" . $drop["date_delivery"] . "' AND `response` > 0;";
        //    $numLocDeliveriesAllLoc = get_distinct_column_values(DB_PREFIX . "sync_details", " value", "", "", $link, true, true, $whereCond4);
        //    $aLocDeliveries[] = $numLocDeliveriesAllLoc;
        //}

        //$aLocDeliveries = array_filter($aLocDeliveries);
        //if (count($aLocDeliveries) > 0) {
        //    $aAvgLocDeliveries = floor(array_sum($aLocDeliveries) / count($aLocDeliveries));
        //} else {
        //    $aAvgLocDeliveries = 1;
        //}

        $sChartValLocDeliveriesAllLoc .= $aAvgLocDeliveries . ",";
    }

    $locProfValues = array_filter($locProfValues);
    $iAvgProfiles = array_sum($locProfValues) / count($locProfValues);

    $sChartDate = substr($sChartDate, 0, -1);
    $sChartVal = substr($sChartVal, 0, -1);
    $sChartValMax = substr($sChartValMax, 0, -1);
    $sChartValLocDeliveriesAllLoc = substr($sChartValLocDeliveriesAllLoc, 0, -1);

    //$erg = create_chartlie_for_all($sChartDate, $sChartVal, $sChartValLocDeliveriesAllLoc, $sChartValLocDeliveries, $iMaxProfiles, $iMaxProfiles, $locName["name_long"]);
    $erg = create_chartlie_for_all($sChartDate, $sChartVal, $sChartValLocDeliveriesAllLoc, $sChartValLocDeliveries, $iMaxProfiles, $iMaxProfiles);
    echo $erg;
}

function create_diz_beat_for_single_location_old($link){
    echo "var chartliexample8 = echarts.init(document.getElementById('lineChart8'));";

    $temp_beat = get_beat("sync","",$link);
    $sChartDate = "";
    $sChartVal = "";
    $sChartValMax = "";
    $sChartValAvg = "";
    $sChartValLocDeliveries = "";
    $sChartValLocDeliveriesAllLoc = "";

    $aDrops = get_distinct_column_values(DB_PREFIX."sync_details", "`date_delivery`", "`location_id`", "*", $link);
    $aDropsAll = get_distinct_column_values(DB_PREFIX."sync_details", "`date_delivery`", "", "*", $link);

    $locName = get_single_column_value(DB_PREFIX."locations", "name_long", "`id`", $_GET['loc'], $link);

    $iMaxProfiles = count_value(DB_PREFIX."fhirprofiles", "", "*", $link);

    $locProfValues = [];

    foreach($aDrops as $drop){
        $sChartDate .= "'".convertDate($drop["date_delivery"])."',";

        $whereCond = "`location_id`='" . $_GET['loc'] . "' AND `date_delivery`='" . $drop["date_delivery"] . "' AND `response` > 0;";
        $locProfileVal = get_val_with_custom_where_cond(DB_PREFIX."sync_details", "name", $link, $whereCond, true);
        $locProfValues[] = $locProfileVal;
        $sChartVal .= $locProfileVal . ",";

        $sChartValMax .= $iMaxProfiles . ",";

        // Anzahl gelieferte standorte
        $whereCond2 = "`date_delivery`='" . $drop["date_delivery"] . "' AND `response` > 0;";
        $numLocDeliveries = get_distinct_column_values(DB_PREFIX."sync_details", "`location_id`", "", "", $link, true, true, $whereCond2);
        $sChartValLocDeliveries .= $numLocDeliveries . ",";

        // Durchschnitt aller Standorte die Profile geliefert haben > 0
        $locDeliveries = get_distinct_column_values(DB_PREFIX."sync_details", " location_id", "", "", $link, false, true, $whereCond2);
        $aLocDeliveries = [];
        foreach ($locDeliveries as $loc){
            $whereCond4 = "`location_id`='" . $loc["location_id"] . "' AND `date_delivery`='" . $drop["date_delivery"] . "' AND `response` > 0;";
            $numLocDeliveriesAllLoc = get_distinct_column_values(DB_PREFIX."sync_details", " value", "", "", $link, true, true, $whereCond4);
            $aLocDeliveries[] = $numLocDeliveriesAllLoc;
        }

        $aLocDeliveries = array_filter($aLocDeliveries);
        if(count($aLocDeliveries) > 0){
            $aAvgLocDeliveries = floor(array_sum($aLocDeliveries)/count($aLocDeliveries));
        }
        else{
            $aAvgLocDeliveries = 1;
        }


        $sChartValLocDeliveriesAllLoc .= $aAvgLocDeliveries . ",";

        //$locationsAvgVal = get_val_with_custom_where_cond(DB_PREFIX."sync_details", "DISTINCT `value`", $hDB, $whereCond3, true);
        //$sChartValAvg .= $locationsAvgVal . ",";
    }

    $locProfValues = array_filter($locProfValues);
    $iAvgProfiles = array_sum($locProfValues)/count($locProfValues);

    $sChartDate = substr($sChartDate, 0, -1);
    $sChartVal = substr($sChartVal, 0, -1);
    $sChartValMax = substr($sChartValMax, 0, -1);
    $sChartValLocDeliveriesAllLoc = substr($sChartValLocDeliveriesAllLoc, 0, -1);

    $erg = create_chartlie_for_location($sChartDate, $sChartVal, $sChartValLocDeliveriesAllLoc, $sChartValLocDeliveries, $iMaxProfiles, $iMaxProfiles, $locName["name_long"]);
    echo $erg;
}


function create_diz_beat_for_single_location($link){

    echo "var chartliexample8 = echarts.init(document.getElementById('lineChart8'));";

    //$temp_beat = get_beat("sync", "", $link);
    $sChartDate = "";
    $sChartVal = "";
    $sChartValMax = "";
    //$sChartValAvg = "";
    $sChartValLocDeliveries = "";
    $sChartValLocDeliveriesAllLoc = "";

    $custom_sql_query = "SELECT DISTINCT `date_delivery` FROM `" . DB_PREFIX . "sync_details` ORDER BY `date_delivery` ASC; ";
    $aDrops = execute_custom_sql_query($custom_sql_query, $link);

    //$aDrops = get_distinct_column_values(DB_PREFIX . "sync_details", "`date_delivery`", "`location_id`", "*", $link, "", true, "`date_delivery` LIKE '%2022%' ORDER BY `date_delivery` ASC");
    //$aDropsAll = get_distinct_column_values(DB_PREFIX . "sync_details", "`date_delivery`", "", "*", $link);

    $locName = get_single_column_value(DB_PREFIX . "locations", "name_long", "`id`", $_GET["loc"], $link);

    $iMaxProfiles = count_value(DB_PREFIX . "fhirprofiles", "", "*", $link);

    $aDropDaysOnly = [];
    foreach ($aDrops as $tempDrop){
        $drop = $tempDrop["date_delivery"];
        $dayOnly = explode(" ", $drop)[0];
        if(!in_array($dayOnly, $aDropDaysOnly)){
            $aDropDaysOnly[] = $dayOnly;
        }
    }

    $locProfValues = [];

    //foreach ($aDrops as $drop) {
    foreach ($aDropDaysOnly as $drop) {
        $sChartDate .= "'" . $drop . "',";

        $sChartValMax .= $iMaxProfiles . ",";

        // Anzahl gelieferte standorte
        $custom_sql_query = "SELECT DISTINCT `location_id` FROM `" . DB_PREFIX . "sync_details` WHERE `date_delivery` LIKE '" . $drop . "%'; ";
        $custom_erg_delivered_locations = execute_custom_sql_query($custom_sql_query, $link);
        $sChartValLocDeliveriesAllLoc .= $custom_erg_delivered_locations->num_rows . ",";

        // Anzahl gelieferte standorte
        $custom_sql_query = "SELECT DISTINCT `name` FROM `" . DB_PREFIX . "sync_details` WHERE `date_delivery` LIKE '" . $drop . "%' AND `location_id` = " . $_GET["loc"] . " AND `response` > 0; ";
        $custom_erg_delivered_single_location = execute_custom_sql_query($custom_sql_query, $link);
        if($custom_erg_delivered_single_location->num_rows == 0){
            $sChartVal .= 0 . ",";
        }
        else {
            $sChartVal .= $custom_erg_delivered_single_location->num_rows . ",";
        }


        $location_ids = [];
        foreach ($custom_erg_delivered_locations as $loc) {
            if(!in_array($loc["location_id"], $location_ids)){
                $location_ids[] = $loc["location_id"];
            }
        }
        // Durchschnittswert aller standorte für profile, die einen wert größer 0 haben
        $mean_amount_profiles = [];
        foreach($location_ids as $loc_id){
            $custom_sql_query = "SELECT DISTINCT `name`,`response` FROM `" . DB_PREFIX . "sync_details` WHERE `date_delivery` LIKE '" . $drop . "%' AND `location_id` = " . $loc_id . " and `response` > 0;";
            $custom_erg_delivered_locations = execute_custom_sql_query($custom_sql_query, $link);
            $mean_amount_profiles[] = $custom_erg_delivered_locations->num_rows;
        }

        if (count($mean_amount_profiles) > 0) {
            $aAvgLocDeliveries = floor(array_sum($mean_amount_profiles) / count($mean_amount_profiles));
        } else {
            $aAvgLocDeliveries = 1;
        }

        $sChartValLocDeliveries .= $aAvgLocDeliveries . ",";
    }

    //$locProfValues = array_filter($locProfValues);
    //$iAvgProfiles = array_sum($locProfValues) / count($locProfValues);

    $sChartDate = substr($sChartDate, 0, -1);
    $sChartVal = substr($sChartVal, 0, -1);
    //$sChartValMax = substr($sChartValMax, 0, -1);
    $sChartValLocDeliveriesAllLoc = substr($sChartValLocDeliveriesAllLoc, 0, -1);

    $erg = create_chartlie_for_location($sChartDate, $sChartVal, $sChartValLocDeliveriesAllLoc, $sChartValLocDeliveries, $iMaxProfiles, $iMaxProfiles, $locName["name_long"]);
    echo $erg;

    /*

    echo "var chartliexample8 = echarts.init(document.getElementById('lineChart8'));";

    $temp_beat = get_beat("sync","",$link);
    $sChartDate = "";
    $sChartVal = "";
    $sChartValMax = "";
    $sChartValAvg = "";
    $sChartValLocDeliveries = "";
    $sChartValLocDeliveriesAllLoc = "";

    $aDrops = get_distinct_column_values(DB_PREFIX."sync_details", "`date_delivery`", "`location_id`", "*", $link);
    $aDropsAll = get_distinct_column_values(DB_PREFIX."sync_details", "`date_delivery`", "", "*", $link);

    $locName = get_single_column_value(DB_PREFIX."locations", "name_long", "`id`", $_GET['loc'], $link);

    $iMaxProfiles = count_value(DB_PREFIX."fhirprofiles", "", "*", $link);

    $locProfValues = [];

    foreach($aDrops as $drop){
        $sChartDate .= "'".convertDate($drop["date_delivery"])."',";

        $whereCond = "`location_id`='" . $_GET['loc'] . "' AND `date_delivery`='" . $drop["date_delivery"] . "' AND `response` > 0;";
        $locProfileVal = get_val_with_custom_where_cond(DB_PREFIX."sync_details", "name", $link, $whereCond, true);
        $locProfValues[] = $locProfileVal;
        $sChartVal .= $locProfileVal . ",";

        $sChartValMax .= $iMaxProfiles . ",";

        // Anzahl gelieferte standorte
        $whereCond2 = "`date_delivery`='" . $drop["date_delivery"] . "' AND `response` > 0;";
        $numLocDeliveries = get_distinct_column_values(DB_PREFIX."sync_details", "`location_id`", "", "", $link, true, true, $whereCond2);
        $sChartValLocDeliveries .= $numLocDeliveries . ",";

        // Durchschnitt aller Standorte die Profile geliefert haben > 0
        $locDeliveries = get_distinct_column_values(DB_PREFIX."sync_details", " location_id", "", "", $link, false, true, $whereCond2);
        $aLocDeliveries = [];
        foreach ($locDeliveries as $loc){
            $whereCond4 = "`location_id`='" . $loc["location_id"] . "' AND `date_delivery`='" . $drop["date_delivery"] . "' AND `response` > 0;";
            $numLocDeliveriesAllLoc = get_distinct_column_values(DB_PREFIX."sync_details", " value", "", "", $link, true, true, $whereCond4);
            $aLocDeliveries[] = $numLocDeliveriesAllLoc;
        }

        $aLocDeliveries = array_filter($aLocDeliveries);
        if(count($aLocDeliveries) > 0){
            $aAvgLocDeliveries = floor(array_sum($aLocDeliveries)/count($aLocDeliveries));
        }
        else{
            $aAvgLocDeliveries = 1;
        }


        $sChartValLocDeliveriesAllLoc .= $aAvgLocDeliveries . ",";

        //$locationsAvgVal = get_val_with_custom_where_cond(DB_PREFIX."sync_details", "DISTINCT `value`", $hDB, $whereCond3, true);
        //$sChartValAvg .= $locationsAvgVal . ",";
    }

    $locProfValues = array_filter($locProfValues);
    $iAvgProfiles = array_sum($locProfValues)/count($locProfValues);

    $sChartDate = substr($sChartDate, 0, -1);
    $sChartVal = substr($sChartVal, 0, -1);
    $sChartValMax = substr($sChartValMax, 0, -1);
    $sChartValLocDeliveriesAllLoc = substr($sChartValLocDeliveriesAllLoc, 0, -1);

    $erg = create_chartlie_for_location($sChartDate, $sChartVal, $sChartValLocDeliveriesAllLoc, $sChartValLocDeliveries, $iMaxProfiles, $iMaxProfiles, $locName["name_long"]);
    echo $erg;

    */
}

// create table, displaying values for each profile and location
function create_table($link){
    $sCardTemplate = '
        <div class="container" id="datatableAllLocations">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8 col-12" style="width: 100%">
                    <div class="auth-box">
                        <table id="list_profiles_locations" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>FHIR-Profile</th>';

    $aLocations = get_dataset(DB_PREFIX."locations", $link);
    $aLocIds = [];
    $aLocLD = [];

    while($row = mysqli_fetch_assoc($aLocations)){
        $aLocIds[] = $row["id"];
        $loc_id = get_single_column_value(DB_PREFIX."locations", "id", "name_short", $row["name_short"], $link);
        $iLoc_id = $loc_id["id"];
        $last_drop = get_last_drop(DB_PREFIX."sync_details", "date_delivery", "location_id", $iLoc_id, $link);
        $aLocLD[] = $last_drop["max(date_delivery)"];
    }

    $aLocations = get_dataset(DB_PREFIX."locations", $link);
    $iCounter = 0;
    while($row = mysqli_fetch_assoc($aLocations)){
        $ld = convertDate($aLocLD[$iCounter]);
        $sCardTemplate = $sCardTemplate .  "<td>" . $row["name_long"] . "<br>" . $ld . "</td>";
        $iCounter = $iCounter + 1;
    }
    $sCardTemplate2 = '
                                </tr>
                            </thead>
                            <tbody>
                            ';

    $aProfiles = get_dataset(DB_PREFIX."fhirprofiles", $link);

    while($row = mysqli_fetch_assoc($aProfiles)){
        $profile = $row["fhir_profile"];

        $sCardTemplate2 = $sCardTemplate2 . "<tr>" . "\n";
        $sCardTemplate2 = $sCardTemplate2 . "<td>" . $profile . "</td>";

        for($i = 0; $i < count($aLocIds); $i++) {
            $whereCond = "`location_id`=" . $aLocIds[$i] . " AND `date_delivery`='" . $aLocLD[$i] . "' AND `name`='" . $profile . "';";
            $erg = get_val_with_custom_where_cond(DB_PREFIX . "sync_details", "response", $link, $whereCond);
            $sCardTemplate2 = $sCardTemplate2 . "<td>" . $erg["response"] . "</td>";
        }
        $sCardTemplate2 = $sCardTemplate2 . "</tr>" . "\n";
    }
    $sCardTemplate3 = '        
                            </tbody>
                        </table>
                     </div>
                </div>
            </div>
        </div>';

    echo $sCardTemplate . $sCardTemplate2 . $sCardTemplate3;
}

function create_table_single_location_drop($link, $locId){
    $dropFilter = new DateTime(start_report_date);
    $sCardTemplate = '
                        <table id="list_profiles_location_detail" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>FHIR-Profile</th>';

    $sLocName = get_single_column_value(DB_PREFIX."locations","name_long", "id", $locId, $link);
    $sCardTemplate = str_replace("<th>FHIR-Profile</th>", "<th>" . $sLocName["name_long"] . "</th>", $sCardTemplate);

    $aDrops_unrev = get_distinct_column_values(DB_PREFIX."sync_details", "`date_delivery`", "`location_id`", $locId, $link);
    $aDrops = array_reverse($aDrops_unrev);

    $bla123 = get_dataset_where(DB_PREFIX . "sync_details", "location_id=" . $locId,"", $link);
    $temp = mysqli_fetch_all($bla123);


    $iCounter = 0;
    $aDropsFilter = [];
    foreach($aDrops as $drop){
        $drop1 = new DateTime($drop["date_delivery"]);

        if($drop1 >= $dropFilter){
            $sCardTemplate = $sCardTemplate .  "<td>" . $drop["date_delivery"] . "</td>";
            $iCounter = $iCounter + 1;
            $aDropsFilter[] = $drop["date_delivery"];
        }
    }

    $sCardTemplate2 = '
                                </tr>
                            </thead>
                            <tbody>
                            ';

    //$aProfiles = get_dataset_where(DB_PREFIX."sync_details", "location_id=" . $locId, "" , $link);
    $aProfiles = get_information_from_database("SELECT DISTINCT `name` FROM `tmfev114_sync_details` WHERE `location_id`=" . $locId);

    //while($row = mysqli_fetch_assoc($aProfiles)){
    foreach($aProfiles as $profile){
        //$profile = $row["name"];
        $sCardTemplate2 = $sCardTemplate2 . "<tr>";
        $sCardTemplate2 = $sCardTemplate2 . "<td>" . $profile["name"] . "</td>";
        $profile_name = $profile["name"];

        for($i = 0; $i < count($aDrops); $i++){

            $date_array = $aDrops[$i];
            $date = $date_array["date_delivery"];

            //echo $date;
            $adults = array_filter($temp, function($value) use ($profile_name, $date) {
                return $value[4] == $profile_name && $value[9] == $date;
            });

            if(count($adults)> 0){
                foreach ($adults as $adult){
                    if($adult[9] == $date){
                        $sCardTemplate2 = $sCardTemplate2 . "<td>" . $adult[8] . "</td>";
                    }
                    else{
                        $sCardTemplate2 = $sCardTemplate2 . "<td>" . "" . "</td>";
                    }
                    break;
                }
            }
            else{
                $sCardTemplate2 = $sCardTemplate2 . "<td>" . " "  . "</td>";
            }
        }

        /*
        for($i = 0; $i < count($aDropsFilter); $i++) {
            if(new DateTime($drop["date_delivery"]) > $dropFilter) {
                $date = $aDropsFilter[$i];
                //$whereCond = "`location_id`=" . $locId . " AND `date_delivery`='" . $aDropsFilter[$i] . "' AND `name`='" . $profile["name"] . "';";
                //$erg = get_val_with_custom_where_cond(DB_PREFIX . "sync_details", "response", $link, $whereCond);
                //$sCardTemplate2 = $sCardTemplate2 . "<td>" . $erg["response"] . "</td>";
                $adults = array_filter($temp, function($value) use ($profile_name, $date) {
                    return $value[4] == $profile_name && $value[9] == $date;
                });

                foreach ($adults as $adult){
                    $sCardTemplate2 = $sCardTemplate2 . "<td>" . $adult[8] . "</td>";
                //$sCardTemplate2 = $sCardTemplate2 . "<td>" . "Undefined" . "</td>";
                }

                //$sCardTemplate2 = $sCardTemplate2 . "<td>" . $adults[0][8] . "</td>";
            }
        }
        */
        $sCardTemplate2 = $sCardTemplate2 . "</tr>";

    }

    $sCardTemplate3 = '        
                            </tbody>
                        </table>

        <!--<script>
            //let divId = document.getElementById("datatableAllLocations");
            //divId.parentNode.removeChild(divId);       
        </script>-->';

    echo $sCardTemplate . $sCardTemplate2 . $sCardTemplate3;
}

function create_table_single_location_drop_v1($link, $locId){
    $dropFilter = new DateTime(start_report_date);
    $sCardTemplate = '
                        <table id="list_profiles_location_detail" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>FHIR-Profile</th>';

    $sLocName = get_single_column_value(DB_PREFIX."locations","name_long", "id", $locId, $link);
    $sCardTemplate = str_replace("<th>FHIR-Profile</th>", "<th>" . $sLocName["name_long"] . "</th>", $sCardTemplate);

    $aDrops = get_distinct_column_values(DB_PREFIX."sync_details", "`date_delivery`", "`location_id`", $locId, $link);
    $iCounter = 0;
    $aDropsFilter = [];
    foreach($aDrops as $drop){
        $drop1 = new DateTime($drop["date_delivery"]);

        if($drop1 >= $dropFilter){
            $sCardTemplate = $sCardTemplate .  "<td>" . $drop["date_delivery"] . "</td>";
            $iCounter = $iCounter + 1;
            $aDropsFilter[] = $drop["date_delivery"];
        }
    }

    $sCardTemplate2 = '
                                </tr>
                            </thead>
                            <tbody>
                            ';

    $aProfiles = get_dataset(DB_PREFIX."fhirprofiles", $link);

    while($row = mysqli_fetch_assoc($aProfiles)){
        $profile = $row["fhir_profile"];

        $sCardTemplate2 = $sCardTemplate2 . "<tr>";
        $sCardTemplate2 = $sCardTemplate2 . "<td>" . $profile . "</td>";

        for($i = 0; $i < count($aDropsFilter); $i++) {
            if(new DateTime($drop["date_delivery"]) > $dropFilter) {
                $whereCond = "`location_id`=" . $locId . " AND `date_delivery`='" . $aDropsFilter[$i] . "' AND `name`='" . $profile . "';";
                $erg = get_val_with_custom_where_cond(DB_PREFIX . "sync_details", "response", $link, $whereCond);
                $sCardTemplate2 = $sCardTemplate2 . "<td>" . $erg["response"] . "</td>";
            }
        }
        $sCardTemplate2 = $sCardTemplate2 . "</tr>";
    }

    $sCardTemplate3 = '        
                            </tbody>
                        </table>

        <script>
            let divId = document.getElementById("datatableAllLocations");
            divId.parentNode.removeChild(divId);       
        </script>';

    echo $sCardTemplate . $sCardTemplate2 . $sCardTemplate3;
}

function create_table_user_log($link, $user_id){

    $sCardTemplate = '
        <table id="list_data_table" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                ';
        //<th>Nutzer(ID)</th>
    $aDrops = ["Datum", "Aktion"];
    foreach($aDrops as $drop){
        $sCardTemplate = $sCardTemplate .  "<td>" . $drop . "</td>";
    }

    $sCardTemplate2 = '
                                </tr>
                            </thead>
                            <tbody>
                            ';

    $a_log_entries = get_dataset_where(DB_PREFIX . "users_stattab_log", "`uid`=" . $user_id , "ORDER BY `date_created` DESC ", $link);

    foreach ($a_log_entries as $entry){

        $user_id = $entry["uid"];
        $sCardTemplate2 = $sCardTemplate2 . "<tr>";
        //$sCardTemplate2 = $sCardTemplate2 . "<td>" . $user_id . "</td>";
        $sCardTemplate2 = $sCardTemplate2 . "<td>" . $entry["date_created"] . "</td>" . "<td>" . $entry["uaction"] . "</td>";
        $sCardTemplate2 = $sCardTemplate2 . "</tr>";
    }

    $sCardTemplate3 = '        
            </tbody>
        </table>
        <script>
            let divId = document.getElementById("datatableAllLocations");
            divId.parentNode.removeChild(divId);       
        </script>';

    echo $sCardTemplate . $sCardTemplate2 . $sCardTemplate3;
}

function create_activation_table($link){

    if (isset($_SESSION["user_id"])) {
        //$hDB = db_connect();

        $user_infos = get_dataset_where(DB_PREFIX . "users_stattab", "id=" . $_SESSION["user_id"], "", $link);
        $user = "";
        foreach ($user_infos as $user_info) {
            $user = $user_info;
        }

        if ($user["usergroup"] == "l_admin") {
            $users = get_dataset(DB_PREFIX . "users_stattab", $link);

            $activation_table_start = '
            <div class="container" >
                <table id="list_user_log" class="table table-striped" style="width:100%">
                    <thead>
                     <tr>
                            <th>ID</th>
                            <th>Vorname</th>
                            <th>Nachname</th>
                            <th>Email</th>
                            <th>Standort</th>
                            <th>Aktivierungsstatus</th>
                            <th>Temp. Passwort setzen</th>
                        </tr>
                    </thead>
                    <tbody>
            ';

            //create row for each user
            foreach ($users as $user) {

                $inp_enabled_status = "enabled";
                $inp_placeholder = 'placeholder=""';
                $inp_button = '<button type="submit" id="btnSubmit" name="btnSubmit" value="login" class="secondary-btn w-25">+</button>';

                $i_user_passwords = count_value(DB_PREFIX . "salted_passwd", "uid", $user["id"], $link);

                if($i_user_passwords > 0){
                    $inp_enabled_status = "enabled"; // temporary workaround -> should be set to 'disabled'
                    $inp_button = '<button type="submit" id="btnSubmit" name="btnSubmit" value="login" class="secondary-btn w-25">+</button>';
                    $inp_placeholder = 'placeholder="Temporäres Passwort bereits vergeben"';
                }

                $tabRow = '    <tr>
                                    <td>' . $user["id"] . '</td>
                                    <td>' . $user["firstname"] . '</td>
                                    <td>' . $user["lastname"] . '</td>
                                    <td>' . $user["email"] . '</td>
                                    <td>' . $user["location_name_short"] . '</td>
                                    <td>###link###</td>
                                    <td>
                                        <form method="POST" id="formSubmit" name="temp" style="display: block ruby" action="' . $_SERVER["PHP_SELF"]. '?activation">  
                                            <input
                                                type="text"
                                                ' . $inp_placeholder . '
                                                class="form-input w-75"
                                                id="passwd"
                                                name="passwd"
                                                value="" 
                                                ' . $inp_enabled_status . '
                                                />
                                             
                                            <input type="hidden" name="actiontag" id="actiontag" value="54907780">' .
                    $inp_button .
                    '
                                            <input
                                                type="text"
                                                class="form-input"
                                                id="user_id"
                                                name="user_id"
                                                value="' . $user["id"] . '"
                                                style="visibility: hidden"
                                            />
                                        </form> 
                                    </td>
                                </tr>';
                if ($user["status"] == 0) {
                    $tabRow = str_replace("###link###", '<a href="dashboard.php?actiontag=65487325&uid=' . $user["id"] . '">aktivieren</a>', $tabRow);
                } else {
                    $tabRow = str_replace("###link###", 'bereits aktiv', $tabRow);
                }
                $activation_table_start .= $tabRow;
            }

            $activation_table_end = '
                            </tbody>
                    </table>
                </div>
            ';

            $activation_table = $activation_table_start . $activation_table_end;

            echo $activation_table;
        }
    }
}

function create_member_table($link, $locId = ""){
    if (isset($_SESSION["user_id"])) {
        $user_infos = get_dataset_where(DB_PREFIX . "users_stattab", "id=" . $_SESSION["user_id"], "", $link);

        $user = "";
        foreach ($user_infos as $user_info) {
            $user = $user_info;
        }

        if($locId == ""){
            //$users = get_dataset(DB_PREFIX . "users_stattab", $link);
            $users = get_dataset_where(DB_PREFIX . "users_stattab", "status=1", "", $link);
        } else{
            $users = get_dataset_where(DB_PREFIX . "users_stattab", "location_name_short=" . $locId . " AND status=1", "", $link);
        }

        $locations = get_dataset(DB_PREFIX . "locations", $link);
        $loc_ids = [];

        foreach ($locations as $location){
            $loc_ids[] = strval($location["id"]);
        }

        $activation_table_start = '
                <table id="list_user_log" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Vorname</th>
                            <th>Nachname</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
        ';

        //table_header
        foreach ($users as $user) {
            if($user["usergroup"] == "l_user"){

                $user_loc_id = strval($user["location_name_short"]);
                //if($user["location_name_short"]
                $loc_name = "";

                if(in_array($user_loc_id, $loc_ids)){
                    $locs = get_dataset_where(DB_PREFIX . "locations", "id=" . $user["location_name_short"], "", $link);
                    foreach ($locs as $loc){
                        $loc_name_temp = $loc["name_long"];
                    }
                    $loc_name = $loc_name_temp;
                } else{
                    $loc_name =$user["location_name_short"];
                }

                $tabRow = ' <tr>
                                <td>' . $user["firstname"] . '</td>
                                <td>' . $user["lastname"] . '</td>
                                <td>' . $user["email"] . '</td>
                            </tr>';
                $activation_table_start .= $tabRow;
            }
        }

        $activation_table_end = '
                    </tbody>
                </table>
        ';

        $activation_table = $activation_table_start . $activation_table_end;

        echo $activation_table;
    }
}

function call_action_tag($tag, $link){
    switch($tag){
        case "80306179";
            scientist_register($link);
            break;
        case "02967104":
            user_register($link);
            break;
        case "78206624":
            user_login($link);
            break;
        case "49715764":
            create_csr_request();
            break;
        case "65487325":
            user_activation($link);
            break;
        case "54907780":
            insert_encrypted_password($link);
            break;
        case "00313089";
            daily_ma_email_delivery($link);
            break;
        case "62042615";
            set_request_user_log($link);
            break;
        case "07410120";
            set_group_id_for_fhir_profile($link);
            break;
        default:
            break;
    }
}

function user_register($link){
    $numEmails = count_value(DB_PREFIX . "users_stattab","email", $_POST["email"] , $link);

    if($numEmails > 0){
        header("Location: login.php?register&email=" . $_POST["email"] . "");
    } else{
        if(isset($_POST["btnSubmitRegister"]) ){
            if($_POST["btnSubmitRegister"] == "submission"){
                $curDate = new DateTime();
                $formattedDate = $curDate->format('Y-m-d H:i:s');
                $edit_rights = 0;
                $location = "";

                // no->location hat immer 0 als edit rights
                if($_POST["location_select"] == "otherLocation"){
                    $location = $_POST["other_location"];
                }
                else {
                    $location = $_POST["location_select"];
                }
                $locationsInTable = count_value(DB_PREFIX . "users_stattab", "location_name_short", $location, $link);
                //$num_users_of_location = count_value(DB_PREFIX . "users_stattab", "location_name_short", $location );

                // set edit-rights to person, who is first to existing location and not related to other location
                if($locationsInTable < 1 && $_POST["location_select"] != "otherLocation" && $_POST["location_select"] != "noLocation"){
                    $edit_rights = 1;
                } else {
                    $edit_rights = 0;
                }

                $hash = create_token();
                $token  = create_token();

                // get the user-input
                $values_temp_user = [
                    $_POST["title"], $_POST["vname"], $_POST["nname"], $_POST["email"], "l_user", $location, $_SERVER['REMOTE_ADDR'], $formattedDate, 0, $edit_rights, $hash
                ];
                //$values_temp_user = create_value_array($aValues);

                // get the element-names for each user-input
                $element_names_temp_user = [
                    "title", "firstname", "lastname", "email","usergroup", "location_name_short", "ipaddress", "date_created", "status", "edit_rights", "sec_hash"
                ];
                //$element_names_temp_user = create_value_array($aElement_names);

                $sql_query_add_temp_user = build_insert_query(DB_PREFIX."users_stattab", $values_temp_user, $element_names_temp_user);
                $sql_queries = [$sql_query_add_temp_user];
                //$sql_queries = [];
                //$sql_queries[] = $sql_query_add_temp_user;

                // insert query into database
                execute_sql_queries($sql_queries);

                // create magic-link
                $user_info = get_val_with_custom_where_cond(DB_PREFIX . "users_stattab", "*", $link, "`sec_hash`='" . $hash . "'");

                $date_created = $user_info["date_created"];
                $dateTime = new DateTime($date_created);
                $date_end = $dateTime->modify("+120 minutes");
                $date_end = $date_end->format('Y-m-d H:i:s');

                $values_user_session = [
                    $user_info["id"], $token, 1, $date_created, $date_end
                ];

                // get the element-names for each user-input
                $element_names_user_session = [
                    "uid", "mytoken", "status", "created_date", "end_date"
                ];

                $sql_query_add_magic_link = build_insert_query(DB_PREFIX."magiclink", $values_user_session, $element_names_user_session);
                $sql_queries_ml = [$sql_query_add_magic_link];
                //$sql_queries_ml = [];
                //$sql_queries_ml[] = $sql_query_add_magic_link;
                //execute_sql_queries($sql_queries_ml);

                set_user_stattab_log($formattedDate, $user_info["id"], "user registered");
                set_user_stattab_log($formattedDate, $user_info["id"], "register-link send");

                $message = read_email_template_file("templates/emails/email.html");
                send_html_email($_POST["email"], "FDPG Schaufenster - Registrierung", $message, EMAIL_SENDER, EMAIL_SENDER_NAME, "5.35.240.133", "587", EMAIL_SENDER, EMAIL_PASSWORD);

                // redirection to email-send-view
                header("Location: login.php?emailSend");
            }
        }  else {
            header("Location: login.php?register&email=" . $_POST["email"] ."");
        }
    }
}

function user_activation($link){
    if(isset($_GET["uid"])){
        $curDate = new DateTime();
        $formattedDate = $curDate->format('Y-m-d H:i:s');

        $whereCond = "id='" . $_GET["uid"] . "';";
        //$set_condition = "status=1";

        update_dataset(DB_PREFIX . "users_stattab", "`status`=1", $whereCond, $link);

        $user_infos = get_dataset_where(DB_PREFIX . "users_stattab", "id=" . $_GET["uid"], "", $link);
        $user_info = "";
        foreach($user_infos as $user){
            $user_info = $user;
        }

        $hash = $user_info["sec_hash"];
        $token  = create_token();

        // create magic-link
        $user_info_123 = get_val_with_custom_where_cond(DB_PREFIX . "users_stattab", "*", $link, "`sec_hash`='" . $user["sec_hash"] . "'");

        $date_created = $formattedDate;
        $dateTime = new DateTime($date_created);
        $date_end = $dateTime->modify("+120 minutes");
        $date_end = $date_end->format('Y-m-d H:i:s');

        $values_magic_link = [
            $user_info_123["id"], $token, 1, $date_created, $date_end
        ];
        //$values_user_session = create_value_array($aValues);

        // get the element-names for each user-input
        $element_names_magic_link = [
            "uid", "mytoken", "status", "created_date", "end_date"
        ];
        //$element_names_user_session = create_value_array($aElement_names);

        $sql_query_add_magic_link = build_insert_query(DB_PREFIX."magiclink", $values_magic_link, $element_names_magic_link);
        $sql_queries_ml = [];
        $sql_queries_ml[] = $sql_query_add_magic_link;
        execute_sql_queries($sql_queries_ml);

        //set_user_stattab_log($formattedDate, $user_info_123["id"], "user activated");
        set_user_stattab_log($formattedDate, $user_info_123["id"], "login-link send");

        $message = read_email_template_file("templates/emails/email_login_first.html");
        $message = str_replace('###activation_link###', 'https://forschen-fuer-gesundheit.de/dashboard.php?hash=' . $hash . '&token=' . $token, $message);
        send_html_email($user_info_123["email"], "FDPG Login", $message, EMAIL_SENDER, EMAIL_SENDER_NAME, "5.35.240.133", "587", EMAIL_SENDER, EMAIL_PASSWORD);
        header("Location: dashboard.php?activation");
    }
}

function user_login($link){
    if($_POST["btnSubmit"] == "login"){

        $curDate = new DateTime();
        $formattedDate = $curDate->format('Y-m-d H:i:s');

        //$hash = create_token();
        $token  = create_token();

        $whereCond = "`email`='" . $_POST["email"] . "';";
        $user = get_val_with_custom_where_cond(DB_PREFIX . "users_stattab","*", $link, $whereCond, $bValOnly = false);

        if(is_array($user)){
            $last_user_entry = count($user)-1;
        }

        // user has existing email-address
        if($user != null){
            if(!check_is_admin($user["usergroup"]) && $user["status"] == "1"){
                $user_info = $user;

                $location = $user_info["location_name_short"];
                $locationsInTable = count_value(DB_PREFIX . "users_stattab", "location_name_short", $location, $link);

                if($locationsInTable < 1){
                    $edit_rights = 1;
                } else {
                    $edit_rights = 0;
                }

                // create magic-link
                $user_info = get_val_with_custom_where_cond(DB_PREFIX . "users_stattab", "*", $link, "`sec_hash`='" . $user["sec_hash"] . "'");

                //$date_created = $user_info["date_created"];
                $date_created = $formattedDate;
                $dateTime = new DateTime($date_created);
                $date_end = $dateTime->modify("+120 minutes");
                $date_end = $date_end->format('Y-m-d H:i:s');

                $values_magic_link = [
                    $user_info["id"], $token, 1, $date_created, $date_end
                ];
                //$values_user_session = create_value_array($aValues);

                // get the element-names for each user-input
                $element_names_magic_link = [
                    "uid", "mytoken", "status", "created_date", "end_date"
                ];
                //$element_names_user_session = create_value_array($aElement_names);

                $sql_query_add_magic_link = build_insert_query(DB_PREFIX."magiclink", $values_magic_link, $element_names_magic_link);
                $sql_queries_ml = [];
                $sql_queries_ml[] = $sql_query_add_magic_link;
                execute_sql_queries($sql_queries_ml);

                //set_user_stattab_log($formattedDate, $user_info["id"], "login-link send");

                $message = read_email_template_file("templates/emails/email_login.html");
                $message = str_replace('###activation_link###', 'https://forschen-fuer-gesundheit.de/dashboard.php?hash=' . $user_info["sec_hash"] . '&token=' . $token, $message);
                try{
                    send_html_email($_POST["email"], "FDPG Login", $message, EMAIL_SENDER, EMAIL_SENDER_NAME, "5.35.240.133", "587", EMAIL_SENDER, EMAIL_PASSWORD);
                    set_user_stattab_log($formattedDate, $user_info["id"], "login-link send");
                } catch(\Exception $e){
                    set_user_stattab_log($formattedDate, $user_info["id"], "login-link not send");
                }

                // redirection to email-send-view
                //header("Location: login.php?reloginSend");
                header("Location: login_send.php");
            }
            else if(!check_is_admin($user["usergroup"]) && $user["status"] == "0"){
                header("Location: login.php?notactivated");
            }
            else {
                $date_created = $user["date_created"];
                $dateTime = new DateTime($date_created);
                $date_end = $dateTime->modify("+120 minutes");
                $date_end = $date_end->format('Y-m-d H:i:s');

                $_SESSION["session_start"]=$date_created;
                $_SESSION["session_end"]=$date_end;
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["sec_hash"] = $user["sec_hash"];

                set_user_stattab_log($formattedDate, $user["id"], "admin-login");

                header("Location: dashboard.php");
            }

        }
        else {
            header("Location: login.php?noEmail&email=" . $_POST["email"] . "");
        }
    }
}

function scientist_register($link){
    if(isset($_POST["btnSubmit"]) && $_POST["btnSubmit"] == "submission"){
        //add_recipient();

        $curDate = new DateTime();
        $formattedDate = $curDate->format('Y_m_d__H_i_s');

        $values = [];
        $aValues = [
            $_POST["anrede"], $_POST["vorname"], $_POST["nachname"], $_POST["institution"], $_POST["street"],
            $_POST["street_nr"], $_POST["zipcode"], $_POST["city"], $_POST["email"], $_POST["phone"], "0", $formattedDate, $formattedDate
        ];

        $sMailString = "";
        foreach($aValues as $value){
            $values[] = $value;
            $sMailString = $sMailString." ". $value;
        }

        $element_names = [];
        $aElement_names = [
            "title", "firstname", "lastname", "institute", "address_street", "address_hnum", "address_zip", "address_town",
            "email", "phone", "status", "created_date", "updated_date"
        ];

        foreach($aElement_names as $elem){
            $element_names[] = $elem;
        }

        $sql_query = build_insert_query(DB_PREFIX."preregist", $values, $element_names);

        $sql_queries = [];
        $sql_queries[] = $sql_query;

        // insert query into database
        execute_sql_queries($sql_queries);

        $message = read_email_template_file("templates/emails/email.html");
        $erg = send_html_email($_POST["email"], "FDPG - Registrierung", $message, EMAIL_SENDER, "Registrierung eingegangen", "5.35.240.133", "587", EMAIL_SENDER, EMAIL_PASSWORD);
        $erg = send_html_email("techgrube@tmf-ev.de", "FDPG - Neue Registrierung", $sMailString, EMAIL_SENDER, "Registrierung eingegangen", "5.35.240.133", "587", EMAIL_SENDER, EMAIL_PASSWORD);
        //$erg = send_html_email("philip.kleinert@tmf-ev.de", "FDPG - Neue Registrierung", $sMailString, EMAIL_SENDER, "Registrierung eingegangen", "5.35.240.133", "587", EMAIL_SENDER, EMAIL_PASSWORD);

        header("Location: register_send.php");
    }
}

// check
function perform_login($link): int {
    if(isset($_GET["token"]) && isset($_GET["hash"])){ //als  separate funktion auslagern
        $whereCond = "`sec_hash`='" . $_GET["hash"] . "';";

        $user_info = get_val_with_custom_where_cond(DB_PREFIX . "users_stattab", "*", $link, $whereCond);

        $whereCond = "`mytoken`='" . $_GET["token"] . "';";
        update_dataset(DB_PREFIX . "magiclink", "`status`=0", $whereCond, $link);

        $magiclink = get_val_with_custom_where_cond(DB_PREFIX . "magiclink", "*", $link, $whereCond);

        // funktion -> checkusertime -> 0 oder 1, als zugriffsrecht für eine seite
        $date_created = $magiclink["created_date"];
        $dateTime = new DateTime($date_created);
        $date_end = $dateTime->modify("+120 minutes");
        $date_end = $date_end->format('Y-m-d H:i:s');

        $curDate = new DateTime();
        $formattedDate = $curDate->format('Y-m-d H:i:s');

        if($formattedDate > $date_end){
            update_dataset(DB_PREFIX . "magiclink", "`status` = 0", "`mytoken`='" . $_GET["token"] . "';", $link);
            set_user_stattab_log($formattedDate, $user_info["id"], "login not successful - link expired");
        }

        else {

            $values_user_session = [
                $user_info["id"], $_GET["token"],  1, $date_created, $date_end
            ];

            // get the element-names for each user-input
            $element_names_user_session = [
                "uid", "mytoken", "status", "created_date", "end_date"
            ];

            $sql_query_add_user_session = build_insert_query(DB_PREFIX."magiclink", $values_user_session, $element_names_user_session);
            $sql_queries = [];
            $sql_queries[] = $sql_query_add_user_session;
            execute_sql_queries($sql_queries);

            set_user_stattab_log($formattedDate, $user_info["id"], "login successful");
            //session_start();
            $_SESSION["session_start"]=$date_created;
            $_SESSION["session_end"]=$date_end;
            $_SESSION["user_id"] = $user_info["id"];
            $_SESSION["sec_hash"] = $_GET["hash"];

        }
        return 0;
    }
    else{
        return 1;
    }
}

function check_login($link): int{
    //$res_array = [];
    $i_user_id_exists = 0;
    $i_sec_hash_exists = 0;
    $i_session_time_exists = 0;

    $i_user_is_logged_in = 0;
    $error_code = -1;

    // check if user-id exists
    if(isset($_SESSION["user_id"])){
        $i_user_id_exists = 1;
    } else{
        $error_code = 1; //"user-id not set";
    }

    // check if hash exists
    if(isset($_SESSION["sec_hash"])){
        $i_sec_hash_exists = 1;
    }
    else{
        $error_code =  2; //"hash not identical";
    }

    // check if session-time-information exist
    if(isset($_SESSION["session_start"]) && isset($_SESSION["session_end"])){
        $i_session_time_exists = 1;
    } else {
        $error_code = 3; //"session-time-information not set";
    }

    // if all information are available, user_check is okay
    if($i_user_id_exists && $i_sec_hash_exists && $i_session_time_exists){
        $i_user_is_logged_in = 0; // 0 as valid logged-in state
    }

    if($i_user_is_logged_in == 0){
        return $i_user_is_logged_in;
    } else{
        return $error_code;
    }
}

function check_session_time(): int{
    if(isset($_SESSION["session_end"])){
        $cur_date = new DateTime();
        $current_date = $cur_date->format('Y-m-d H:i:s');

        $session_end = $_SESSION["session_end"];

        // check if the current_date is within the 120 minute time-frame
        if($current_date < $session_end){
            return 0;
        }
        else{
            // set the session inactive by setting the status to 0! update-command needs to be implemented
            return 1;
        }
    }
    else{
        // set the session inactive by setting the status to 0!
        return 1;
    }
}

function check_link_state($link): int{

    if(isset($_SESSION["sec_hash"])){
        $erg = get_val_with_custom_where_cond(DB_PREFIX . "users_stattab", "`state`", $link, "`sec_hash`='" . $_SESSION["sec_hash"] . "';");
    } else{
        return 1;
    }

    if($erg == "1"){
        return 0;
    }else{
        return 1;
    }
}

function check_dashboard_access($link){

    $b_is_admin = false;

    if(isset($_GET["token"])){
        $whereCond = "`mytoken`='". $_GET["token"] . "'";
        $magiclink = get_val_with_custom_where_cond(DB_PREFIX . "magiclink", "*", $link, $whereCond);
    }

    if(isset($magiclink)){
        if($magiclink["status"] == 1){
            $login_res = perform_login($link);

            if($login_res > 0){
                $erg = set_session_status($_SESSION["token"], $whereCond, $link, "0");
                echo "erg";
                $msg_2 = "invalidlink";
                header("Location: login.php?relogin&msg=" . $msg_2);
            }

            $login_state = check_login($link);
            $session_time = check_session_time(); // die session-zeit muss überprüft werden, ob sie noch gültig

            if($session_time > 0){
                $whereCond = "`mytoken`='" . $_GET["token"] . "';";
                update_dataset(DB_PREFIX . "magiclink", "`status`=0", $whereCond, $link);

                $msg_1 = "sessionstop";
                //header("Location: login.php?relogin&msg=" . $msg_1); // -> redirect, parameter mit angeben, je nachdem, was der user_session_check sagt -> user_status überprüfen, zb 0 gesperrt, etc
                header("Location: login.php?dizdash"); // -> redirect, parameter mit angeben, je nachdem, was der user_session_check sagt -> user_status überprüfen, zb 0 gesperrt, etc
            }

            else if($login_state > 0){
                $whereCond = "`mytoken`='" . $_GET["token"] . "';";
                update_dataset(DB_PREFIX . "magiclink", "`status`=0", $whereCond, $link);

                $msg_2 = "User-information nicht korrekt";
                header("Location: login.php?relogin&msg=" . $msg_2);
            }
        } else if($magiclink["status"] == "0" && isset($_SESSION["user_id"])) {
            header("Location: login.php?relogin&msg=invalidlink"); // -> redirect, parameter mit angeben, je nachdem, was der user_session_check sagt -> user_status überprüfen, zb 0 gesperrt, etc
        } else if($magiclink["status"] == "0" && !isset($_SESSION["user_id"])) {
            header("Location: login.php?relogin&msg=invalidlink"); // -> redirect, parameter mit angeben, je nachdem, was der user_session_check sagt -> user_status überprüfen, zb 0 gesperrt, etc
        }
    }

    elseif(isset($_SESSION["user_id"])){
        $user_info = get_val_with_custom_where_cond(DB_PREFIX . "users_stattab", "*", $link, "`id`='" . $_SESSION["user_id"] . "'");
        if(check_is_admin($user_info["usergroup"])){
            $b_is_admin = true;
        }
        else{
            $b_is_admin = false;
        }
        if($user_info["status"] == 0){
            header("Location: login.php");
        }
    }

    if(isset($_SESSION["session_end"]) && !$b_is_admin){
        $session_time = check_session_time(); // die session-zeit muss überprüft werden, ob sie noch gültig ist
        if($session_time > 0){
            $whereCond = "`mytoken`='" . $_GET["token"] . "';";
            update_dataset(DB_PREFIX . "magiclink", "`status`=0", $whereCond, $link);
            $msg_1 = "sessionstop";
            header("Location: login.php?relogin&msg=" . $msg_1); // -> redirect, parameter mit angeben, je nachdem, was der user_session_check sagt -> user_status überprüfen, zb 0 gesperrt, etc
        }
        elseif($session_time == 0){
            $bla = 1;
        }
    }

    elseif(isset($_SESSION["session_end"]) == false && !$b_is_admin){
        header("Location: login.php");
    }
}

// check if user is in admin-user-group -> temporarily skips admin check!
function check_is_admin($user_group){
    if($user_group == "l_admin_123"){
        return true;
    } else {
        return false;
    }
}

function set_session_status($table, $where_cond,  $link, $state = "1"){
    $set_cond = "`status`='" . $state . "'";
    $erg = update_dataset(DB_PREFIX . $table, $set_cond, $where_cond, $link);
    return $erg;
}

// print the DIZ beat
function get_beat($table,$type,$link) {

    if ($type=="start") {
        $sql = "SELECT * FROM ".DB_PREFIX.$table." ORDER BY sync_start ASC LIMIT 1";
    } else {
        $sql = "SELECT * FROM ".DB_PREFIX.$table." ORDER BY sync_start ASC";
    } // end if

    $result = mysqli_query($link, $sql);
    return $result;

} // end function

// get and print all timestamps
function print_list_stattab() {

    $dir = "./stattab/";
    $dh  = opendir($dir);
    while (false !== ($filename = readdir($dh))) {
        $files[] = $filename;
    }
    sort($files);

    echo "<table>";
    foreach ($files as $file) {
        if ($file!="." && $file!="..") {
            echo "<tr><td><a href='dashboard.php?t=".$file."&a=rd'>".$file."</a></td></tr>";

        } // end if
    }
    echo "</table>";
} // end function

// print files
function print_files_stattab($subdir = '') {

    $dir = "./stattab/".$subdir;
    $files = scandir($dir);

    echo "<table>";
    foreach($files as $file) {
        if ($file!="." && $file!="..") {
            echo "<tr><td><a href='dashboard.php?f=".$file."&d=".$subdir."&a=pf'>$file</a></td></tr>";
        }
    }
    echo "</table>";

} // end function

// print single data set like the Son of J
function print_json_stattab($jfile='', $subdir = '') {

    $json_url = "https://forschen-fuer-gesundheit.de/stattab/".$subdir."/".$jfile;

    print "<div style='font-family: Arial;'>
		Exploring a DIZ JSON from [site-name] created " . date ("F d Y H:i:s.", filectime($json_url) ) .
        "(<a href='" . $json_url . "' target='_blank'> RAW DATA </a>).
	</div>";

    explore_json_url(
        $json_url,
        array(
            'enable_js'  => ! isset( $_GET['disable_js'] ),
            'expand_all' => false,
        )
    );

} // end function

function set_user_stattab_log($formattedDate, $user_id, $activity){
    // get the user-input for users_stattab_log
    $values_temp_user_log = [
        $formattedDate, $user_id, $activity, $_SERVER['REMOTE_ADDR']
    ];

    // get the element-names for each user-input
    $element_names_temp_user_log = [
        "date_created", "uid", "uaction", "ipaddress"
    ];

    $sql_query_add_temp_user_log = build_insert_query(DB_PREFIX."users_stattab_log", $values_temp_user_log, $element_names_temp_user_log);
    $sql_queries_log = [$sql_query_add_temp_user_log];

    execute_sql_queries($sql_queries_log);
}

function create_chartlie_for_location($sDeliveryDates, $sDeliveryValuesSingleLoc, $sDeliveryMaxValues, $iNumLocationDeliveries, $iMaxProfiles, $iAvgProfiles, $locName){

    $chartlie_single_location = "
        option = {
            title: {
                text: '',
                subtext: '',
                x: 'center'
            },
            tooltip: {
                trigger: 'axis',
                formatter: function (params) {
                    return params[0].name + '<br>'
                           + params[0].seriesName + ' : ' + params[0].value + '<br>'
                           + params[1].seriesName + ' : ' + params[1].value + '<br>'
                           + params[2].seriesName + ' : ' + params[2].value + '';
                }
            },
            legend: {
                data: [
                'Anzahl Profile von " . $locName . " (>0)', 
                'Anzahl Profile von allen Standorten (>0)',
                'Anzahl Standorte, die geliefert haben'
                ],
                x: 'left'
            },
            toolbox: {
                show: true,
                feature: {
                    mark: { show: false },
                    dataView: { show: false, readOnly: false },
                    magicType: { show: true, type: ['line', 'bar'] },
                    restore: { show: true, title: 'Refresh' },
                    saveAsImage: { show: true, title: 'Save As Image' }
                }
            },
            color: ['#345995', '#03CEA4', '#FB4D3D', '#D0A90EFF'],
            dataZoom: {
                show: true,
                realtime: true,
                start: 0,
                end: 100
            },
            grid: {
                show: false,
                containLabel: true,
                left: '20',
                right: '20',
                top: '100',
                bottom: '10'

            },
            xAxis: [
                {
                    type: 'category',
                    boundaryGap: false,
                    axisLine: { onZero: false },
                    data: [" . $sDeliveryDates . "
                    ]
                }
            ],
            yAxis: [
                {
                    name: 'Anz. Standorte',
                    type: 'value',
                    max: " . $iAvgProfiles . " 
                },
                {
                    name: 'Anz. Profile',
                    type: 'value',
                    max: " . $iMaxProfiles . "
                }
            ],
            series: [
                {
                    name: 'Anzahl Profile von " . $locName . " (>0)',
                    type: 'line',
                    itemStyle: { normal: { areaStyle: { type: 'default' } } },
                    data: [" . $sDeliveryValuesSingleLoc . "]
                },
                {
                    name: 'Anzahl Profile von allen Standorten (>0)',
                    type: 'line',
                    yAxisIndex: 1,
                    itemStyle: { normal: { areaStyle: { type: 'default' } } },
                    data: [" . $sDeliveryMaxValues . "]
                },
                {
                    name: 'Anzahl Standorte, die geliefert haben',
                    type: 'line',
                    yAxisIndex: 0,
                    itemStyle: { normal: { areaStyle: { type: 'default' } } },
                    data: [" . $iNumLocationDeliveries . "]
                }
            ]
        };
        chartliexample8.setOption(option);
    ";

    return $chartlie_single_location;
}

//function create_chartlie_for_all($sDeliveryDates, $sDeliveryValues,$sDeliveryMaxValues, $iLocationDeliveries, $iMaxProfiles, $iAvgProfiles, $locName){
function create_chartlie_for_all($sDeliveryDates, $sDeliveryValues,$sDeliveryMaxValues, $iLocationDeliveries, $iMaxProfiles, $iAvgProfiles){
    $chartlie_all_locations = "
        option = {
            title: {
                text: '',
                subtext: '',
                x: 'center'
            },
            tooltip: {
                trigger: 'axis',
                formatter: function (params) {
                    return params[0].name + '<br>'
                           + params[0].seriesName + ' : ' + params[0].value + '<br>'
                           + params[1].seriesName + ' : ' + params[1].value + '';
                }
            },
            legend: {
                data: [
                'Anzahl Profile von allen Standorten (>0)',
                'Anzahl Standorte, die geliefert haben'
                ],
                x: 'left'
            },
            toolbox: {
                show: true,
                feature: {
                    mark: { show: false },
                    dataView: { show: false, readOnly: false },
                    magicType: { show: true, type: ['line', 'bar'] },
                    restore: { show: true, title: 'Refresh' },
                    saveAsImage: { show: true, title: 'Save As Image' }
                }
            },
            color: ['#03CEA4', '#FB4D3D', '#D0A90EFF'],
            dataZoom: {
                show: true,
                realtime: true,
                start: 0,
                end: 100
            },
            grid: {
                show: false,
                containLabel: true,
                left: '20',
                right: '20',
                top: '100',
                bottom: '10'

            },
            xAxis: [
                {
                    type: 'category',
                    boundaryGap: false,
                    axisLine: { onZero: false },
                    data: [" . $sDeliveryDates . "
                    ]
                }
            ],
            yAxis: [
                {
                    name: 'Anz. Standorte',
                    type: 'value',
                    max: " . $iAvgProfiles . " 
                },
                {
                    name: 'Anz. Profile',
                    type: 'value',
                    max: " . $iMaxProfiles . "
                }
            ],
            series: [
                {
                    name: 'Anzahl Profile von allen Standorten (>0)',
                    type: 'line',
                    yAxisIndex: 1,
                    itemStyle: { normal: { areaStyle: { type: 'default' } } },
                    data: [" . $sDeliveryMaxValues . "]
                },
                {
                    name: 'Anzahl Standorte, die geliefert haben',
                    type: 'line',
                    yAxisIndex: 0,
                    itemStyle: { normal: { areaStyle: { type: 'default' } } },
                    data: [" . $iLocationDeliveries . "]
                }
            ]
        };
        chartliexample8.setOption(option);
    ";

    return $chartlie_all_locations;
}

function convertDate($date) {
    $date = explode(" ", $date);
    $date = explode("-", $date[0]);
    $date = $date[0]."/".$date[2] . "/" . $date[1];
    return $date;
} // end function

function print_csr_success(){
    print   '<div class="mb-3">
                <p>Zertifikat erfolgereich beantragt. Weitere Informationen für das Zertifikat-Setup werden in den kommenden 5 Tagen per Email zugestellt.</p>
            </div>
              <div class="mb-3">
                <a href="https://forschen-fuer-gesundheit.de/">Zur Startseite</a>
            </div>';

    $message = read_email_template_file("templates/emails/email.html");
    send_html_email("techgrube@tmf-ev.de", "FDPG - Zertifikat beantragt", $message, EMAIL_SENDER, EMAIL_SENDER_NAME, "5.35.240.133", "587", EMAIL_SENDER, EMAIL_PASSWORD);
}

function print_invalid_link(){
    print   '<div class="mb-3">
                <p>Login-Link ungültig oder abgelaufen. Jetzt neuen Login-Link anfordern.</p>
            </div> ';
}

function print_session_stop(){
    print   '<div class="mb-3">
                <p>Session abgelaufen. Jetzt neuen Login-Link anfordern.</p>
            </div>';

}

function print_no_email(){
    print   '<div class="mb-3">
                <p>Email-Adresse nicht gefunden. <a href="login.php?register">Jetzt registrieren</a></p>
            </div>';
}

function print_not_activated(){
    print   '<div class="mb-3">
                <p>Konto noch nicht aktiviert oder gesperrt. Nach der Aktivierung erfolgt eine Benachrichtigung per Email.</p>
            </div>';
}

function print_taken_email(){
    print   '<div class="mb-3">
                <p>Email-Adresse bereits in Verwendung.  <a href="login.php">Zum Login</a></p>
            </div>';
}

function print_email_send(){
    print   '<div class="mb-3">
                <p>Registrierungsdaten erfolgreich übermittelt. Sobald Ihre Daten geprüft wurden, erhalten Sie eine Email mit den Login-Daten.</p>
            </div> ';
}

function print_relogin_send(){
    print   '<div class="mb-3">
                <p>Login-Link wurde erfolgreich per Email verschickt.</p>
            </div> ';
}

function print_simple_form_message($header, $msg){
    print '
        <div id="msg" class="auth-heading text-center">
            <h2>'
        . $header .
        '</h2>
        </div>
                    
        <div class="mb-3">
            <p>'
        . $msg .
        '</p>
        </div>
        ';
}

function print_activation_link($link)
{
    $cnt = '';
    if (isset($_SESSION["user_id"])) {
        $user_infos = get_dataset_where(DB_PREFIX . "users_stattab", "id=" . $_SESSION["user_id"], "", $link);
        $user = "";
        foreach($user_infos as $user_info){
            $user = $user_info;
        }

        if($user["usergroup"] == "l_admin"){
            $cnt =
                '<li class="nav-item">
                <a class="nav-link" id="a-activation" href="/dashboard.php?activation">
                    <i class="fa fa-tasks"></i>
                    <span>Userliste</span>
                </a>
            </li>';
        }
    }

    return $cnt;
}

function print_feasibility_admin_link($link)
{
    $cnt = '';
    if (isset($_SESSION["user_id"])) {
        $user_infos = get_dataset_where(DB_PREFIX . "users_stattab", "id=" . $_SESSION["user_id"], "", $link);
        $user = "";
        foreach($user_infos as $user_info){
            $user = $user_info;
        }

        if($user["usergroup"] == "l_admin"){
            $cnt =
                '<li class="nav-item">
                <a class="nav-link" id="a-log" href="dashboard.php?dailyma">
                    <i class="fa fa-tasks"></i>
                    <span>Machbarkeitsanfragen (Admin)</span>
                </a>
            </li>';
        }
    }
    return $cnt;
}

function create_view_keycloak_login($link){
    if(isset($_SESSION["user_id"])){
        $user_info = "";
        $users = get_dataset_where(DB_PREFIX . "users_stattab", "id=". $_SESSION["user_id"], "", $link);
        foreach ($users as $user){
            $user_info = $user;
        }
        if(isset($user_info["email"])){
            print '
            <div id="msg" class="auth-heading text-center">
                <h2>
                Feasibility Credentials
                </h2>
				<p style="color: red;"><b>HINWEIS: </b>Das temporäre Passwort wird nur 1 mal angezeigt. Bitte notieren und bei der ersten Anmeldung bei der Feasibility App verwenden.<br><br></p>

            </div>
            <div class="mb-3">
                <label for="email" id="lab_email" class="form-label">Username</label>
                <input
                    type="email"
                    class="form-control"
                    id="email"
                    name="email"
                    placeholder=""
                    value="' . $user_info["email"] . '"
                    disabled
                    required
                />
            </div>
            <div class="mb-3">    
                <label for="passwd" id="lab_passwd" class="form-label">Passwort</label>
                <div class="input-group mx-auto">
                    <input
                        type="password"
                        class="form-control"
                        id="passwd"
                        name="passwd"
                        placeholder=""
                        value="'. uniqid() .'"
                        disabled
                        required
                        style="width: 85%"
                    />
                    <div class="form-check" id="eye">
                        <button class="secondary-btn" onclick="show_pass()">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                        </button>
                    </div>        
                    <div class="form-check" id="save" style="display: none">
                        <button class="secondary-btn" onclick="copyToClipboard()">
                            <i class="fa fa-hdd" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>
            <a href="https://feasibility.forschen-fuer-gesundheit.de" target="_blank">
                <button type="submit" id="btnSubmit" name="btnSubmit" value="login" class="secondary-btn w-100">Zum Login</button>                            
            </a>
        ';
        }
        else {
            header("/dashboard.php?profile=nopassword");
        }
    }
}

function create_login_view($formerEmail = ""){
    $pre_value = $formerEmail;

    print   '<div class="mb-3">
                <label for="email" id="lab_email" class="form-label">Email</label>
                <input
                    type="email"
                    class="form-control"
                    id="email"
                    name="email"
                    placeholder="Ihre Email-Adresse"
                    value="' . $pre_value . '"
                    required
                />
            </div>
        <input type="hidden" name="actiontag" id="actiontag" value="78206624">
        <button type="submit" id="btnSubmit" name="btnSubmit" value="login" class="secondary-btn w-100">Login</button>
        <div class="mb-3">
            <!-- <a href="https://forschen-fuer-gesundheit.de/start.php?sid=2">Registrierung</a> -->
            <a href="register_diz.php">Registrierung</a>
        </div>';

}

function create_register_view($link, $email){
    $part1 =            '<div class="mb-3">
                            <label for="title" id="lab_title" class="form-label">Anrede</label>
                            <select id="title" name="title" class="form-control" required>
                                <option value ="-">Bitte auswählen</option>
                                <option value ="Frau">Frau</option>
                                <option value ="Herr">Herr</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="vname" id="lab_vname" class="form-label">Vorname</label>
                            <input
                                type="text"
                                class="form-control"
                                id="vname"
                                name="vname"
                                placeholder="Ihr Vorname"
                                required
                            />
                        </div>

                        <div class="mb-3">
                            <label for="nname" id="lab_nname" class="form-label">Nachname</label>
                            <input
                                type="text"
                                class="form-control"
                                id="nname"
                                name="nname"
                                placeholder="Ihr Nachname"
                                required
                            />
                        </div>

                        <div class="mb-3">
                            <label for="email" id="lab_email" class="form-label">Email</label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                value="' . $email . '"
                                placeholder="Ihre Email-Adresse"
                                required
                            />
                        </div>

                        <div class="mb-3">
                            <label for="location_select" id="lab_location_select" class="form-label">DIZ-Standort</label>
                            <select id="location_select" onchange="getComboA(this)" name="location_select" class="form-control" required>
                                <option value ="noLocation">Kein Standort</option>
                                <option value ="otherLocation">Anderer Standort</option>';

    $part1 .= print_location_options($link);
    $part2 =    '</select>
                        </div>

                        <div class="mb-3">
                            <input
                                type="text"
                                class="form-control"
                                id="other_location"
                                name="other_location"
                                placeholder="Anderen Standort angeben"
                                style="visibility: hidden"
                            />
                        </div>
                        
                        <p style="font-size: 12px; color: #333">
                        Bitte beachten Sie vor der Nutzung der Funktionen des Deutschen Forschungsdatenportal für Gesundheit (FDPG), insbesondere vor der Durchführung von Machbarkeitsanfragen, unsere Nutzungsbedingungen. Eine Nichteinhaltung der Vorgaben und Regelungen der Nutzungsbedingungen kann zu einer Sperrung des Nutzeraccounts und/oder (bei schwerwiegenden Verstößen) zu weiteren (gerichtlichen) Maßnahmen, z.B. wegen der Verletzung von Urheberrechten, führen!
                        </p>
                        <p style="font-size: 6px; color: #333">&nbsp;</p>
                        <p style="font-size: 12px; color: #333">
                            So dürfen z.B. die Ergebnisse eine Machbarkeitsanfrage nur für die Planung/Vorbereitung eines Forschungsvorhabens genutzt werden! Eine Veröffentlichung von Machbarkeitsanfragen bzw. deren Ergebnissen ist grundsätzlich nicht gestattet!                  
                        </p>
                        <p>&nbsp;</p>
                        <div class="middle-section mb-3">
                            <label class="container-checkbox"><a href="https://forschen-fuer-gesundheit.de/nutzungsbedingungen.php" target="_blank" class="link-tag">Nutzungsbedingungen akzeptieren</a>
                              <input id="cb1" type="checkbox" />
                              <span class="checkmark-secondary"></span>
                            </label>
                            <label class="container-checkbox"><a href="https://forschen-fuer-gesundheit.de/datenschutz.php" target="_blank" class="link-tag">Datenschutzerklärung</a>
                              <input id="cb2" type="checkbox" />
                              <span class="checkmark-secondary"></span>
                            </label>
                        </div>
                        
                        <input type="hidden" name="actiontag" id="actiontag" value="02967104">
                        <button type="submit" id="btnSubmitRegister" name="btnSubmitRegister" value="submission" style="opacity: 0.5" class="secondary-btn w-100">Registrieren</button>';

    print $part1 . $part2;
}

function create_csr_request_view($link){
    $part1 = '<div class="mb-3">
                        <label for="title" class="form-label">Anrede</label>
                        <select id="title" name="title" class="form-control" required>
                            <option value ="-">Bitte auswählen</option>
                            <option value ="Frau">Frau</option>
                            <option value ="Herr">Herr</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="vname" class="form-label">Vorname</label>
                        <input
                        type="text"
                        class="form-control"
                        id="vname"
                        name="vname"
                        placeholder="Ihr Vorname"
                        required
                        />
                    </div>

                    <div class="mb-3">
                        <label for="nname" class="form-label">Nachname</label>
                        <input
                        type="text"
                        class="form-control"
                        id="nname"
                        name="nname"
                        placeholder="Ihr Nachname"
                        required
                        />
                    </div>

				  <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input
                      type="email"
                      class="form-control"
                      id="email"
                      name="email"
                      placeholder="Ihre Email-Adresse"
                      required
                    />
                  </div>
                  
                  <div class="mb-3">
                    <label for="phone" class="form-label">Telefon</label>
                    <input
                      type="phone"
                      class="form-control"
                      id="phone"
                      name="phone"
                      placeholder="Ihre Telefonnummer"
                      required
                    />
                  </div>

                    <div class="mb-3">
                        <label for="location_select" class="form-label">DIZ-Standort</label>
                        <select id="location_select" name="location_select" class="form-control" required>
                            <option value ="-">Bitte auswählen</option>';

    $part1 .= print_location_options($link);


    $part2=                        '</select>
                    </div>
                  <!--
                  <div class="mb-3">
                    <label for="location_input" class="form-label">DIZ-Standort</label>
                    <input
                      type="text"
                      class="form-control"
                      id="location_input"
                      name="location_input"
                      placeholder="Names Ihres DIZ-Standortes"
                      required
                    />
                  </div>
                  -->

                  <div class="mb-3">
                        <label for="reason" class="form-label">Grund für die Beantragung</label>
                        <textarea
                            class="form-control"
                            id="reason"
                            name="reason"
                            style="height: 100px"
                            rows="4"
                            cols="50"
                            required
                            ></textarea>
                  </div>

                  <div class="mb-3">
                        <label for="hostname" class="form-label">Hostname</label>
                        <input
                            class="form-control"
                            id="hostname"
                            name="hostname"
                            placeholder="dsf.domain.tld"
                            required
                            />
                  </div>

                  <div class="mb-3">
                        <label for="csr" class="form-label">CSR</label>
                        <textarea
                            class="form-control"
                            id="csr"
                            name="csr"
                            placeholder="CSR-Inhalt bitte hier einfügen"
                            style="height: 100px"
                            rows="4"
                            cols="50"
                            required
                            ></textarea>
                  </div>

                  <div class="mb-3">
                        <label for="cert_typ" class="form-label">Zertifikatstyp</label>
                        <select id="cert_typ" name="cert_typ" class="form-control" required>
                            <option value ="-">Bitte auswählen</option>
                            <option value ="basic_team_id">Basic Team ID (1 Jahr)</option>
                            <option value ="basic_personal_id">Basic Personal ID (1 Jahr)</option>
                            <option value ="basic_ssl_id">Basic SSL ID (1 Jahr)</option>
                        </select>
                  </div>

                  <div class="mb-3">
                        <label for="server_type" class="form-label">Serveranwendung</label>
                        <input
                            type="text"
                            class="form-control"
                            id="server_type"
                            name="server_type"
                            placeholder="FHIR-Server, BPE-Server, ..."
                            required
                        />
                  </div>

                  <div class="mb-3">
                        <label for="cli_srv_cert" class="form-label">Zertifikat für Client oder Server</label>
                      <select id="cli_srv_cert" name="cli_srv_cert" class="form-control" >
                          <option value ="-">Bitte auswählen</option>
                          <option value ="client_cert">Clientzertifikat</option>
                          <option value ="server_cert">Serverzertifikat</option>
                      </select>
                  </div>

                  <div class="mb-3">
                        <label for="comment" class="form-label">Kommentar</label>
                        <textarea
                            class="form-control"
                            style="height: 100px"
                            id="comment"
                            name="comment"
                            rows="4"
                            cols="50"
                            ></textarea>
                  </div>
                  
                   
                  
                    <input type="hidden" name="actiontag" id="actiontag" value="49715764">
                    <button type="submit" name="btnSubmit" value="submission" class="secondary-btn w-100">ABSENDEN</button>

                  </div>';
    print $part1 . $part2;

}

function create_csr_request(){
    if($_POST["btnSubmit"] == "submission"){
        $curDate = new DateTime();
        $formattedDate = $curDate->format('Y_m_d__H_i_s');

        // get the user-input
        $aValues = [
            $_POST["title"], $_POST["vname"], $_POST["nname"], $_POST["email"], $_POST["phone"],
            $_POST["location_select"], $_POST["reason"], $_POST["hostname"], $_POST["csr"],
            $_POST["cert_typ"], $_POST["server_type"], $_POST["cli_srv_cert"], $_POST["comment"], $formattedDate,
        ];
        $values = create_value_array($aValues);

        // get the element-names for each user-input
        $aElement_names = [
            "title", "firstname", "lastname", "email", "phone", "location_id", "reason",
            "hostname", "csr", "cert_typ", "srv_type", "cli_srv_type", "comment", "date_created"
        ];
        $element_names = create_value_array($aElement_names);

        $sql_query = build_insert_query(DB_PREFIX."certificate_requests", $values, $element_names);
        $sql_queries = [];
        $sql_queries[] = $sql_query;

        // insert query into database
        execute_sql_queries($sql_queries);

        $message = read_email_template_file("templates/emails/email2.html");
        send_html_email($_POST["email"], "CSR Daten eingegangen", $message, EMAIL_SENDER, "CSR-Registrierung eingegangen", "5.35.240.133", "587", EMAIL_SENDER, EMAIL_PASSWORD);
        header("Location: certrequest.php?csrsuccess");

    }
}

// wird im action-tag aufgerufen
function encrypt_passwd($passwd, $seed=DICKESB_SEED) {
    $result = '';
    for($i=0, $k= strlen($passwd); $i<$k; $i++) {
        $char = substr($passwd, $i, 1);
        $keychar = substr($seed, ($i % strlen($seed))-1, 1);
        $char = chr(ord($char)+ord($keychar));
        $result .= $char;
    }
    return base64_encode($result);
} // end function


function decrypt_passwd($passwd, $seed=DICKESB_SEED) {
    $result = '';
    $passwd = base64_decode($passwd);
    for($i=0; $i<strlen($passwd); $i++) {
        $char = substr($passwd, $i, 1);
        $keychar = substr($seed, ($i % strlen($seed))-1, 1);
        $char = chr(ord($char)-ord($keychar));
        $result.=$char;
    }
    return $result;
} // end function

function insert_encrypted_password($link){
    $curDate = new DateTime();
    $formattedDate = $curDate->format('Y-m-d H:i:s');
    $passwd = encrypt_passwd($_POST["passwd"]);

    $users = get_dataset(DB_PREFIX . "salted_passwd", $link);

    $i_count = 0;
    foreach ($users as $user){
        if($user["uid"] == $_POST["user_id"]){
            $i_count += 1;
        }
    }
    $query = "";

    // if user has no temp-passwd inside table, create temp-passwd
    if($i_count == 0){
        $query = build_insert_query(DB_PREFIX . "salted_passwd", [$_POST["user_id"], $passwd, $formattedDate, $formattedDate], ["uid","passwd","created_date", "updated_date"]);
    }
    // if user has already temp-passwd inside table, update temp-passwd
    else {
        $query = build_update_query(DB_PREFIX . "salted_passwd", ["'" . $passwd . "'", "'" . $formattedDate . "'"] , ["passwd", "created_date"], "uid=". $_POST["user_id"] , $link);
    }

    execute_sql_queries([$query]);

    $users_stattab = get_dataset_where(DB_PREFIX . "users_stattab", "id=" . $_POST["user_id"], "", $link);

    foreach ($users_stattab as $item) {
        $user = $item;
    }

    set_user_stattab_log($formattedDate, $_SESSION["user_id"], "set temporary password for user " . $user["id"]);

    $message = read_email_template_file("templates/emails/email_feas_pass.html");
    $message = str_replace('###activation_link###', 'https://forschen-fuer-gesundheit.de/login.php', $message);

    send_html_email($user["email"], "Feasibility Portal - Zugang aktiviert", $message, EMAIL_SENDER, EMAIL_SENDER_NAME, "5.35.240.133", "587", EMAIL_SENDER, EMAIL_PASSWORD);

    send_html_email("martin.bartow@tmf-ev.de", "Feasibility Portal - Zugang aktiviert für den User mit der Email " . $user["email"], $message, EMAIL_SENDER, EMAIL_SENDER_NAME, "5.35.240.133", "587", EMAIL_SENDER, EMAIL_PASSWORD);
    send_html_email("franziska.klepka@tmf-ev.de", "Feasibility Portal - Zugang aktiviert für den User mit der Email " . $user["email"], $message, EMAIL_SENDER, EMAIL_SENDER_NAME, "5.35.240.133", "587", EMAIL_SENDER, EMAIL_PASSWORD);
}

function load_encrypted_password($link){
    $pwds = get_dataset_where(DB_PREFIX . "salted_passwd", "uid=" . $_SESSION["user_id"], "", $link);
    $pwd = "";
    foreach($pwds as $single_pwd){
        $pwd = $single_pwd;
    }

    if(isset($pwd["passwd"])){
        $passwd = decrypt_passwd($pwd["passwd"]);
        return $passwd;
    }else{
        create_view_no_password($link);
    }
}

function create_view_no_password($link){
    $part1 = "";
    $part2 = "";
    if (isset($_SESSION["user_id"])) {

        $logs = get_dataset_where(DB_PREFIX . "users_stattab_log", "uid=" . $_SESSION["user_id"], "", $link);

        $i_counter = 0;
        foreach ($logs as $log) {
            if ($log["uaction"] == "first temporary password requested") {
                $i_counter += 1;
            }
        }

        $user_info = "";
        $users = get_dataset_where(DB_PREFIX . "users_stattab", "id=" . $_SESSION["user_id"], "", $link);
        foreach ($users as $user) {
            $user_info = $user;
        }

        if (isset($user_info["email"])) {
            $part1 = '
                <div id="msg" class="auth-heading text-center">
                    <h2>
                    Feasibility Credentials
                    </h2>
                </div>
                <div class="mb-3">
                    <label for="email" id="lab_email" class="form-label" >Username</label>
                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        placeholder=""
                        value="' . $user_info["email"] . '"
                        disabled
                        style="font-style: italic"
                    />
                </div>';

            if($i_counter == 0){
                $part2 = '<div class="mb-3">
                    <button type="submit" id="btnSubmit" name="btnSubmit" class="secondary-btn w-100" onclick="request_first_password()">Erstmaliges temporäres Passwort anfordern</button>
                </div>
            ';
            } else{
                $part2 = '<div class="mb-3">
                    <input
                                            type="email"
                                            class="form-control"
                                            id="email"
                                            name="email"
                                            placeholder=""
                                            value="Passwort wurde bereits angefordert"
                                            disabled
                                            style="font-style: italic"
                                        />
                    <!-- <button type="submit" id="btnSubmit" name="btnSubmit" disabled class="secondary-btn w-100" >Temporäres Passwort wurde bereits angefordert</button> -->
                </div>
            ';
            }

        }
    }
    print $part1 . $part2;
}

function print_expired_temp_password(){
    print '
        <div id="msg" class="auth-heading text-center">
            <h2>
                Feasibility Credentials
            </h2>
        </div>
                    
        <div class="mb-3">
            <p>Ihr temporäres Passwort ist abgelaufen. Bitte fordern Sie ein neues Passwort an.</p>
        </div>
        ';

    $message = read_email_template_file("templates/emails/email.html");
    send_html_email("martin.bartow@tmf-ev.de", "FDPG - Temporäres Passwort anlegen", $message, EMAIL_SENDER, EMAIL_SENDER_NAME, "5.35.240.133", "587", EMAIL_SENDER, EMAIL_PASSWORD);
    //send_html_email("philip.kleinert@tmf-ev.de", "FDPG - Temporäres Passwort anlegen", $message, EMAIL_SENDER, EMAIL_SENDER_NAME, "5.35.240.133", "587", EMAIL_SENDER, EMAIL_PASSWORD);
}

function check_is_temporary_password_expired($link){
    $passwords = get_dataset_where(DB_PREFIX . "salted_passwd", "uid=" . $_SESSION["user_id"], "", $link);

    $pwd = "";
    foreach($passwords as $password){
        $pwd = $password;
    }

    $time_created = ($pwd["created_date"]);

    $curDate = new DateTime();
    $cur_timestamp = $curDate->format('Y-m-d H:i:s');

    $additional_three_days_in_sec = 3600*72; // additional 72 hours converted into seconds

    $time_created_in_sec = strtotime($time_created) + $additional_three_days_in_sec;
    $time_current_in_sec = strtotime($cur_timestamp);

    // password is within timeframe -> not expired
    if($time_created_in_sec >= $time_current_in_sec) {
        return 0;
    }
    // password is out of timeframe -> expired
    else {
        return 1;
    }
}

function create_view_expired_password($link)
{
    if (isset($_SESSION["user_id"])) {
        $user_info = "";

        $logs = get_dataset_where(DB_PREFIX . "users_stattab_log", "uid=" . $_SESSION["user_id"], "", $link);

        $i_counter = 0;
        foreach ($logs as $log) {
            if ($log["uaction"] == "new temporary password requested") {
                $i_counter += 1;
            }
        }

        $users = get_dataset_where(DB_PREFIX . "users_stattab", "id=" . $_SESSION["user_id"], "", $link);
        foreach ($users as $user) {
            $user_info = $user;
        }
        if (isset($user_info["email"])) {
            print '
                <div id="msg" class="auth-heading text-center">
                    <h2>
                    Feasibility Credentials
                    </h2>
				<p style="color: red;"><b>HINWEIS: </b>Das temporäre Passwort wird nur 1 mal angezeigt. Bitte notieren und bei der ersten Anmeldung bei der Feasibility App verwenden.<br><br></p>

                </div>
                <div class="mb-3">
                    <label for="email" id="lab_email" class="form-label" >Username</label>
                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        placeholder=""
                        value="' . $user_info["email"] . '"
                        disabled
                        style="font-style: italic"
                    />
                </div>
                <div class="mb-3">    
                    <label for="passwd" id="lab_passwd" class="form-label" style="color: red">Passwort</label>
                    <div style="display: block ruby">
                        <input
                            type="text"
                            class="form-control"
                            id="passwd"
                            name="passwd"
                            placeholder=""
                            value="Temporäres Passwort abgelaufen"
                            disabled
                            style="font-style: italic; color: red"
                        />
                    </div>
                </div>
                <div class="mb-3">
                    <button type="submit" id="btnSubmit" name="btnSubmit" class="secondary-btn w-100" onclick="request_new_password()">Temporäres Passwort erneut anfordern</button>
                </div>
                
            ';
        }
    }
}

function check_password_is_seen($link){
    $logs = get_dataset_where(DB_PREFIX . "users_stattab_log", "uid=" . $_SESSION["user_id"], "", $link);

    $i_counter = 0;
    foreach ($logs as $log) {
        if($log["uaction"] == "user saw temporary password"){
            $i_counter += 1;
        }
    }

    if($i_counter == 0){
        return false;
    }
    else {
        return true;
    }
}

function create_view_seen_password($link)
{
    if (isset($_SESSION["user_id"])) {
        $user_info = "";
        $users = get_dataset_where(DB_PREFIX . "users_stattab", "id=" . $_SESSION["user_id"], "", $link);
        foreach ($users as $user) {
            $user_info = $user;
        }
        if (isset($user_info["email"])) {
            print '
                <div id="msg" class="auth-heading text-center">
                    <h2>
                    Feasibility Credentials
                    </h2>
                </div>
                <div class="mb-3">
                    <label for="email" id="lab_email" class="form-label" >Username</label>
                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        placeholder=""
                        value="' . $user_info["email"] . '"
                        disabled
                        style="font-style: italic"
                    />
                </div>
                <div class="mb-3">    
                    <label for="passwd" id="lab_passwd" class="form-label" style="color: green">Passwort</label>
                    <div style="display: block ruby">
                        <input
                            type="text"
                            class="form-control"
                            id="passwd"
                            name="passwd"
                            placeholder=""
                            value="Das von Ihnen gewählte Passwort"
                            disabled
                            style="font-style: italic; color: green"
                        />
                    </div>
                </div>
                <div class="mb-3">
                    <a href="https://feasibility.forschen-fuer-gesundheit.de" target="_blank">
                        <button type="submit" id="btnSubmit" name="btnSubmit" value="login" class="secondary-btn w-100">Zum Login</button>                            
                    </a>
                </div>
                
            ';
        }
    }
}

function check_has_temporary_password($link){
    $passwords = get_dataset_where(DB_PREFIX . "salted_passwd", "uid=" . $_SESSION["user_id"], "", $link);

    $pwd = "";
    foreach($passwords as $password){
        $pwd = $password;
    }

    if(isset($pwd["created_date"])){
        return true;
    }
    else{
        return false;
    }
}

function create_view_add_feasibility($link){

    if (isset($_SESSION["user_id"])) {
        //$hDB = db_connect();

        $user_infos = get_dataset_where(DB_PREFIX . "users_stattab", "id=" . $_SESSION["user_id"], "", $link);
        $user = "";
        foreach ($user_infos as $user_info) {
            $user = $user_info;
        }

        if ($user["usergroup"] == "l_admin") {
            $users = get_dataset(DB_PREFIX . "users_stattab", $link);

            $part1 =
                '
            <form method="POST" id="formSubmit" name="dailyma_form" action="' . $_SERVER["PHP_SELF"] . '?dailyma">  
                <div id="msg" class="auth-heading text-center">
                    <h2>
                    Durchgeführte Machbarkeitsanfrage
                    </h2>
                </div>
                
                <div class="mb-3">
                    <label for="ma_title" id="lab_ma_title" class="form-label" >Titel der MA</label>
                    <input
                        type="text"
                        class="form-control"
                        id="ma_title"
                        name="ma_title"
                        placeholder=""
                        value=""
                    />
                </div>
                
                <div class="mb-3">
                    <label for="ma_query" id="lab_ma_query" class="form-label">Query der MA</label>
                    <input
                        type="text"
                        class="form-control"
                        id="ma_query"
                        name="ma_query"
                        placeholder=""
                        value=""
                    />
                </div>
                
                <div class="mb-3">
                    <label for="ma_response_locations" id="lab_ma_response_locations" class="form-label" >Anzahl Standorte, die geantwortet haben</label>
                    <input
                        type="number"
                        class="form-control"
                        id="ma_response_locations"
                        name="ma_response_locations"
                        placeholder=""
                        value=""
                    />
                </div>
                
                <div class="mb-3">
                    <label for="ma_response_sum" id="lab_email" class="form-label">Ergebnis der MA</label>
                    <input
                        type="number"
                        class="form-control"
                        id="ma_response_sum"
                        name="ma_response_sum"
                        placeholder=""
                        value=""
                    />
                </div>
                
                <div class="mb-3">
                    <label for="ma_notes" id="lab_email" class="form-label" >Kommentar</label>
                    <textarea
                        class="form-control-csr"
                        style="height: 100px"
                        id="ma_notes"
                        name="ma_notes"
                        rows="4"
                        cols="50"
                        ></textarea>
                </div>
                
                <div class="mb-3">
                    <label class="container-checkbox">Aktivierte User per Email informieren
                        <input id="cb1" type="checkbox" value="1" name="ma_notification_broadcast"/>
                        <span class="checkmark-secondary"></span>
                    </label>
                </div>
                
                 <div class="mb-3">
                    <label class="container-checkbox">Nur Administratoren informieren
                        <input id="cb2" type="checkbox" value="1" name="ma_notification_broadcast_admin_only"/>
                        <span class="checkmark-secondary"></span>
                    </label>
                </div>
                
                <input type="hidden" name="actiontag" id="actiontag" value="00313089">
                <button type="submit" name="btnSubmit" value="submission" class="secondary-btn w-100">ABSENDEN</button>
            </form>
            ';

            echo $part1;
        }
    }
}

function daily_ma_email_delivery($link){
    $curDate = new DateTime();
    $formattedDate = $curDate->format('Y-m-d H:i:s');

    $ma_notification_broadcast = "0";
    $ma_notification_broadcast_admin_only = "0";

    if(isset($_POST["ma_notification_broadcast"]) && $_POST["ma_notification_broadcast"] == "1"){
        $ma_notification_broadcast = "1";
    }

    if(isset($_POST["ma_notification_broadcast_admin_only"]) && $_POST["ma_notification_broadcast_admin_only"] == "1"){
        $ma_notification_broadcast_admin_only = "1";
    }

    $elem_values = [
        $_POST["ma_title"],
        $_POST["ma_query"],
        $_POST["ma_response_locations"],
        $_POST["ma_response_sum"],
        $_POST["ma_notes"],
        $ma_notification_broadcast,
        $_SESSION["user_id"],
        $formattedDate
    ];

    $elem_names = [
        "`ma_title`",
        "`ma_query`",
        "`ma_response_locations`",
        "`ma_response_sum`",
        "`ma_notes`",
        "`ma_notification_broadcast`",
        "`ma_request_uid`",
        "`date_created`",
    ];
    $ma_log_entry = build_insert_query(DB_PREFIX . "ma_request_log", $elem_values, $elem_names);

    execute_sql_queries([$ma_log_entry]);

    if($ma_notification_broadcast == "0"){
        $users_db = get_dataset_where(DB_PREFIX . "users_stattab", "usergroup='l_admin'", "", $link);
    }
    else {
        $users_db = get_dataset_where(DB_PREFIX . "users_stattab", "usergroup='l_user' AND status=1", "", $link);
    }

    if($ma_notification_broadcast_admin_only == "1"){
        $users_db = get_dataset_where(DB_PREFIX . "users_stattab", "usergroup='l_admin'", "", $link);
    }


    foreach($users_db as $user){
        if($user["email"] == "martin.bartow@tmf-ev.de" || $user["email"] == "steven.rohner@tmf-ev.de"){

            $message = read_email_template_file("templates/emails/email_ma_report.html");
            $message = str_replace("###ma_title###", $_POST["ma_title"], $message);
            $message = str_replace("###ma_query###", $_POST["ma_query"], $message);
            $message = str_replace("###ma_response_locations###", $_POST["ma_response_locations"], $message);
            $message = str_replace("###ma_response_sum###", $_POST["ma_response_sum"], $message);
            $message = str_replace("###ma_notes###", $_POST["ma_notes"], $message);

            send_html_email($user["email"], "FDPG - MA Report", $message, EMAIL_SENDER, EMAIL_SENDER_NAME, "5.35.240.133", "587", EMAIL_SENDER, EMAIL_PASSWORD);
        }
    }

    // add ma report to user log
    set_user_stattab_log($formattedDate, $_SESSION["user_id"], "added new feasibility by user: " . $_SESSION["user_id"]);
}

function create_feasibility_list_view($link){
    $user_db = get_dataset_where(DB_PREFIX . "users_stattab", "id=" . $_SESSION["user_id"], "", $link);
    $user = [];
    foreach ($user_db as $user1) {
        $user = $user1;
    }
    //$user["location_name_short"] = "0";
    $user_loc = $user["location_name_short"];

    $disable_btn = 'style="opacity: 0.5;" disabled';
    $disable_cmd = 'disabled';


    $locations = get_dataset(DB_PREFIX . "locations", $link);
    $loc_ids = [];

    foreach ($locations as $location){
        $loc_ids[] = strval($location["id"]);
    }

    if(in_array(strval($user["location_name_short"]), $loc_ids)){
        $feasibility_list = get_dataset(DB_PREFIX . "ma_request_log", $link);
        $feasibility_location_list = get_dataset_where(DB_PREFIX . "ma_request_user_log", "loc_id=" . $user_loc, "", $link);

        $feasibility_table_start = '
            <div class="container" >
                <table id="list_user_log" class="table table-striped" style="width:100%">
                    <thead>
                     <tr>
                            <th>Datum</th>
                            <th>Titel</th>
                            <th>Query</th>
                            <th>Anzahl gelieferte Standorte</th>
                            <th>Ergebnis</th>
                            <th>Status DIZ-Interne Durchführung</th>
                            <th>Aktion</th>
                        </tr>
                    </thead>
                    <tbody>
            ';

        //create row for each feasibility
        $i_counter = 1;
        foreach ($feasibility_list as $feasibility) {
            $tabRow = '    <tr>
                            <form method="POST" id="formSubmit" name="temp" action="' . $_SERVER["PHP_SELF"]. '?feasibilitylist">
                                <td>' . $feasibility["date_created"] . '</td>
                                <td>' . $feasibility["ma_title"] . '</td>
                                <td>' . $feasibility["ma_query"] . '</td>
                                <td>' . $feasibility["ma_response_locations"] . '</td>
                                <td>' . $feasibility["ma_response_sum"] . '</td>
                                <td>
                                    <select ###disable_select### class="form-select form-select-sm" name="cmb_success_check" id="select_r' . $i_counter . '" ">
                                        <option>-</option>
                                        <option value="success" ###selected_success###>Erfolgreich</option>
                                        <option value="error"###selected_error###>Fehlerhaft</option>
                                        <option value="noproof"###selected_noproof###>Nicht prüfbar</option>
                                    </select>
                                </td>
                                <td> 
                                    <div class="input-group mb-3">
                                        <input type="hidden" name="actiontag" id="actiontag" value="62042615">
                                        <button ###disable_btn### type="submit" id="btnSubmit" name="btnSubmit" value="login" class="secondary-btn w-100">Speichern</button>
                                        <input type="hidden" class="form-input" id="user_id" name="user_id" value="' . $_SESSION["user_id"] . '" style="visibility: hidden; width: 0;"/>
                                        <input type="hidden" class="form-input" id="feasibility_id" name="feasibility_id" value="' . $feasibility["id"] . '" style="visibility: hidden; width: 0;"/>
                                    </div>
                                </td>
                            </form>  
                        </tr>
                            ';

            foreach ($feasibility_location_list as $entry){
                if($entry["ma_request_id"] == $feasibility["id"]){
                    if($entry["loc_id"] == $user["location_name_short"]){
                        $tabRow = str_replace('###disable_select###', $disable_cmd, $tabRow);
                        $tabRow = str_replace('###disable_btn###', $disable_btn, $tabRow);

                        switch($entry["success_check"]){
                            case "success":
                                $tabRow = str_replace('###selected_success###', "selected", $tabRow);
                                $tabRow = str_replace('###selected_error###', "", $tabRow);
                                $tabRow = str_replace('###selected_noproof###', "", $tabRow);
                                break;
                            case "error":
                                $tabRow = str_replace('###selected_success###', "", $tabRow);
                                $tabRow = str_replace('###selected_error###', "selected", $tabRow);
                                $tabRow = str_replace('###selected_noproof###', "", $tabRow);
                                break;
                            case "noproof":
                                $tabRow = str_replace('###selected_success###', "", $tabRow);
                                $tabRow = str_replace('###selected_error###', "", $tabRow);
                                $tabRow = str_replace('###selected_noproof###', "selected", $tabRow);
                                break;
                            default:
                                //pass
                                break;
                        }
                    } else{
                        $tabRow = str_replace('###disable_select###', "", $tabRow);
                        $tabRow = str_replace('###disable_btn###', "", $tabRow);
                        $tabRow = str_replace('###selected_success###', "", $tabRow);
                        $tabRow = str_replace('###selected_error###', "", $tabRow);
                        $tabRow = str_replace('###selected_noproof###', "", $tabRow);

                    }
                }
            }

            $feasibility_table_start .= $tabRow;
            $i_counter += 1;
        }

        $feasibility_table_end = '
                            </tbody>
                    </table>
                </div>
            ';

        $feasibility_table = $feasibility_table_start . $feasibility_table_end;

        echo $feasibility_table;
    }
    else{
        echo
            '
            <div id="msg" class="auth-heading text-center">
                <div id="msg" class="auth-heading text-center">
                    <h2>Keine DIZ-Anbindung!</h2>
                </div>

                <div class="mb-3">
                    &nbsp;
                </div>
                <div class="mb-3">

                    <p>Der Standort ist noch nicht an das FDPG angeschlossen.</p>
                </div>
                <div class="mb-3">
                    
                    <a class="nav-link" target="_blank" href="https://github.com/medizininformatik-initiative/feasibility-deploy/wiki/DSF-Middleware-Setup">
                    <i class="fa fa-download"></i>
                    <span style="color: #778CA2">DSF Setup</span>
                    </a>

                    <a class="nav-link" target="_blank" href="https://github.com/medizininformatik-initiative/feasibility-deploy/blob/main/feasibility-triangle/README.md">
                    <i class="fa fa-download"></i>
                    <span style="color: #778CA2">AKTIN Setup</span>
                    </a>
                    
                </div>
            </div>
        ';
    }

}

function set_request_user_log($link){
    $loc_id = get_location_by_user($_SESSION["user_id"], $link);

    $curDate = new DateTime();
    $formattedDate = $curDate->format('Y-m-d H:i:s');

    $success_check = $_POST["cmb_success_check"];

    $values = [
        $_POST["feasibility_id"],
        $loc_id["id"],
        $_POST["user_id"],
        $success_check,
        $formattedDate,
        $formattedDate
    ];

    $elements = [
        "`ma_request_id`",
        "`loc_id`",
        "`uid`",
        "`success_check`",
        "`date_created`",
        "`date_updated`",
    ];

    $insert_query = build_insert_query(DB_PREFIX . "ma_request_user_log", $values, $elements);

    if($success_check != "-"){
        execute_sql_queries([$insert_query]);

        // add ma report to user log
        set_user_stattab_log($formattedDate, $_SESSION["user_id"], "editing of MA (id=" . $_POST["feasibility_id"] . ") by user: " . $_SESSION["user_id"]);
    } else{
        header("Location: /dashboard.php?feasibilitylist=error");
    }
}

function get_location_by_user($user, $link){
    $location_db = get_dataset_where(DB_PREFIX . "locations", "id=" . $user["location_name_short"], "", $link);

    $location = [];
    foreach($location_db as $loc){
        $location[] = $loc;
    }

    return $location;
}

function get_users_by_location($location, $link){
    $users_db = get_dataset_where(DB_PREFIX . "users_stattab","location_name_short=" . $location["id"], "", $link);
    $users = [];

    foreach($users_db as $user){
        $users[] =$user;
    }

    return $users;
}

function print_feasibility_error_view(){
    print   '
 <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-12" style="width: 70%">
            
            <div id="msg" class="auth-heading text-center">
                <div class="mb-3">
                    <p>Der Wert "-" kann nicht gespeichert werden! Bitte wählen Sie "Erfolgreich","Fehlerhaft" oder "Nicht prüfbar" als Status DIZ-interne Durchführung aus.</p>
                    <a href="/dashboard.php?feasibilitylist">Zurück zu den Machbarkeitsanfragen</a>
                </div>
            </div>
        </div>
     </div>
 </div>
         ';
}
/*
function print_diz_monitor(){
    $file = "report_logs/feasibility-results.json";
    $lines    = file($file);

    $loc_w_delivery = ""; // locations, which delivered
    $loc_wo_delivery = ""; // locations, which didn't delivered
    $sDates = "";
    $i_max_locations = 0;
    foreach($lines as $line){
        $i_counter_deli = 0;
        $i_counter_no_deli = 0;
        $someArray = json_decode($line, true);
        $date = $someArray["datetime"];
        //
        //$dates_array[] = $date;
        $date = str_replace("-","_", $date);
        $dateParts = explode("_", $date);
        $date = "'" . $dateParts[0] . "/" . $dateParts[1] . "/" . $dateParts[2] . " " . $dateParts[3] . ":" . $dateParts[4] . " '";
        //$date = convertDate($date);
        $sDates .= $date . ",";

        $results = $someArray["results"];
        $i_count_locations = count($results);

        if($i_count_locations >= $i_max_locations){
            $i_max_locations = $i_count_locations;
        }

        foreach ($results as $result){
            $val0 = $result[0];
            $val1 = $result[1];

            if($val1 == null){
                //$loc_wo_delivery[] = $val;
                $i_counter_no_deli += 1;

            } else{
                //$loc_w_delivery[] = $val0;
                $i_counter_deli += 1;

            }
        }
        $loc_wo_delivery = $loc_wo_delivery . $i_counter_no_deli . ",";
        $loc_w_delivery = $loc_w_delivery . $i_counter_deli . ",";
    }

    $sDates = substr($sDates, 0, -1);
    $loc_w_delivery = substr($loc_w_delivery, 0, -1);
    $loc_wo_delivery = substr($loc_wo_delivery, 0, -1);

    echo "var chartliDizMonitor = echarts.init(document.getElementById('dizMonitor'));";

    $erg = create_chartlie_for_diz_monitor($sDates, $loc_w_delivery, $loc_wo_delivery, 45);

    echo $erg;
}
*/
function create_chartlie_for_diz_monitor($sDeliveryDates, $iLocationsNoDelivery, $iLocationsDelivery, $iNumLocations){
    $chartlie_diz_monitor = "
        option = {
            title: {
                text: '',
                subtext: '',
                x: 'center'
            },
            tooltip: {
                trigger: 'axis',
                formatter: function (params) {
                   return params[0].name + '<br>'
                           + params[1].seriesName + ' : ' + params[1].value + '<br>'
                           + params[0].seriesName + ' : ' + params[0].value + '';
                }
            },
            legend: {
                data: [
                'Anzahl Standorte, die einen Wert >0 geliefert haben',
                'Anzahl Standorte, die erreichbar waren'
                ],
                x: 'left'
            },
            toolbox: {
                show: true,
                feature: {
                    mark: { show: false },
                    dataView: { show: false, readOnly: false },
                    magicType: { show: true, type: ['line', 'bar'] },
                    restore: { show: true, title: 'Refresh' },
                    saveAsImage: { show: true, title: 'Save As Image' }
                }
            },
            color: ['#FB4D3D','#03CEA4'],
            dataZoom: {
                show: true,
                realtime: true,
                start: 0,
                end: 100
            },
            grid: {
                show: false,
                containLabel: true,
                left: '20',
                right: '20',
                top: '100',
                bottom: '10'
            },
            xAxis: [
                {
                    type: 'category',
                    boundaryGap: false,
                    axisLine: { onZero: false },
                    data: [" . $sDeliveryDates . "
                    ]
                }
            ],
            yAxis: [
                {
                    name: 'Anz. Standorte',
                    type: 'value',
                    max: " . $iNumLocations . " 
                },
                 {
                    name: 'Anz. Standorte',
                    type: 'value',
                    max: " . $iNumLocations . " 
                }
            ],
            series: [
                {
                    name: 'Anzahl Standorte, die einen Wert >0 geliefert haben',
                    type: 'line',
                    yAxisIndex: 0,
                    itemStyle: { normal: { areaStyle: { type: 'default' } } },
                    data: [" . $iLocationsNoDelivery . "]
                },
                {
                    name: 'Anzahl Standorte, die erreichbar waren',
                    type: 'line',
                    yAxisIndex: 1,
                    itemStyle: { normal: { areaStyle: { type: 'default' } } },
                    data: [" . $iLocationsDelivery . "]
                }
            ]
        };
        chartliDizMonitor.setOption(option);
    ";

    return $chartlie_diz_monitor;
}

function create_table_single_location_errors($locId, $link){

    $whereCond_err = "`location_id`=" . $locId . " AND status='failed' AND date_delivery > '" . start_report_date . "'" ;

    $erg_err = get_dataset_where(DB_PREFIX . "sync_details", $whereCond_err, "", $link);
    $loc_name_db = get_dataset_where(DB_PREFIX . "locations", "id=" . $locId, "", $link);
    $loc_name = "";
    foreach($loc_name_db as $loc){
        $loc_name = $loc["name_long"];
    }

    $sCardTemplate = '
                        <table id="list_error_table" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>FHIR-Profile</th>';
    $aDrops = [];
    $aProfiles = [];

    foreach($erg_err as $err){
        //$sCardTemplate = str_replace("<th>FHIR-Profile</th>", "<th>" . $err["name_long"] . "</th>", $sCardTemplate);
        if(!in_array($err["date_delivery"], $aDrops)){
            $aDrops[] = $err["date_delivery"];
        }
        if(!in_array($err["name"], $aProfiles)){
            $aProfiles[] = $err["name"];
        }
    }

    //$sLocName = get_single_column_value(DB_PREFIX."locations","name_long", "id", $locId, $link);
    //$sCardTemplate = str_replace("<th>FHIR-Profile</th>", "<th>" . $loc_name . "</th>", $sCardTemplate);

    $iCounter = 0;

    foreach($aDrops as $drop){
        $sCardTemplate = $sCardTemplate .  "<td>" . $drop . "</td>";
        $iCounter = $iCounter + 1;
    }
    $sCardTemplate2 = '
                                </tr>
                            </thead>
                            <tbody>
                            ';

    foreach($aProfiles as $aProfile) {
        $sCardTemplate2 = $sCardTemplate2 . "<tr>";
        $sCardTemplate2 = $sCardTemplate2 . "<td>" . $aProfile . "</td>";
        foreach($aDrops as $drop) {
            $whereCond = "`location_id`=" . $locId . " AND `date_delivery`='" . $drop . "' AND `name`='" . $aProfile . "' AND status='failed' ;";
            //$whereCond = "`location_id`=" . $locId . " AND `date_delivery`='2020-07-26 10:00:19' AND `name`='" . $aProfile . "' AND status='failed' ;";
            //$erg = get_val_with_custom_where_cond(DB_PREFIX . "sync_details", "status", $link, $whereCond);
            $erg123 = get_dataset_where(DB_PREFIX . "sync_details", $whereCond, "", $link);

            if($erg123->num_rows == 1){
                foreach ($erg123 as $val) {
                    $sCardTemplate2 .= "<td>" . $val["status"] . "</td>";
                }
            } else{
                $sCardTemplate2 .= "<td></td>";
            }
        }
        $sCardTemplate2 = $sCardTemplate2 . "</tr>";
    }
    $sCardTemplate3 = '        
                            </tbody>
                        </table>';

    echo $sCardTemplate . $sCardTemplate2 . $sCardTemplate3;
}
/*
function insert_location_deliveries($link){
    $entries = get_dataset(DB_PREFIX . "monitor_log", $link);
    $dates_in_db = [];
    foreach ($entries as $entry){
        if(!in_array($entry["date_created"], $dates_in_db)){
            $dates_in_db[] = $entry["date_created"];
        }
    }

    $file = "report_logs/feasibility-results.json";
    $lines    = file($file);

    foreach($lines as $line){
        $someArray = json_decode($line, true);
        $date = $someArray["datetime"];

        if(!in_array($date, $dates_in_db)){
            $lineCounter += 1;
            $results = $someArray["results"];

            $sql_queries = [];
            $processed_locations = [];

            foreach ($results as $result){
                $val0 = $result[0];
                $val1 = $result[1];

                if($val1 == null || $val1 < 0){
                    $val1 = 0;
                } else{
                    $val1 = 1;
                }

                if(!in_array($val0, $processed_locations)){
                    $locId = location_mapper($val0);
                    $processed_locations[] = $val0;
                    $sql_queries[] = build_insert_query(DB_PREFIX . "monitor_log", [$locId, $val1, $date], ["location_id","status","date_created"]);
                }
            }
            execute_sql_queries($sql_queries);
            //print_r($sql_queries);
            //echo "<br><br>";
        }
    }
}
*/

function load_location_deliveries($link){

    $entries_db = get_dataset(DB_PREFIX . "monitor_log", $link);
    $dates_in_db = [];
    foreach ($entries_db as $entry){
        $date1 = new DateTime($entry["date_created"]);
        $date_filter = new DateTime(start_report_date);
        if($date1 >= $date_filter){
            if(!in_array($entry["date_created"], $dates_in_db)){
                $dates_in_db[] = $entry["date_created"];
            }
        }
    }

    sort($dates_in_db);

    $loc_w_delivery = ""; // locations, which delivered
    $loc_wo_delivery = ""; // locations, which didn't delivered
    $sDates = "";

    foreach($dates_in_db as $date){
        $entries_for_date = get_dataset_where(DB_PREFIX . "monitor_log", "date_created='" . $date . "'", "", $link);

        $i_counter_deli = 0;
        $i_counter_no_deli = 0;

        $date = str_replace("-","_", $date);
        $dateParts = explode("_", $date);
        $dateParts_2 = str_replace(" 00:00:00", " ", $dateParts[2]);
        //$date = "'" . $dateParts[0] . "/" . $dateParts[1] . "/" . $dateParts[2] . " " . $dateParts[3] . "'";
        $date = "'" . $dateParts[0] . "-" . $dateParts[1] . "-" . $dateParts_2 . "'";
        $sDates .= $date . ",";
        $i_max_locations = 0;
        foreach($entries_for_date as $entry){
            $i_count_locations = $entries_for_date->num_rows;

            if($i_count_locations >= $i_max_locations){
                $i_max_locations = $i_count_locations;
            }

            if($entry["status"] > 0) {
                $i_counter_deli += 1;
            } else {
                //$i_counter_no_deli += 1;
                $i_counter_no_deli = $entries_for_date->num_rows;
            }
        }
        $loc_wo_delivery = $loc_wo_delivery . $i_counter_no_deli . ",";
        $loc_w_delivery = $loc_w_delivery . $i_counter_deli . ",";
    }

    $sDates = substr($sDates, 0, -1);
    $loc_w_delivery = substr($loc_w_delivery, 0, -1);
    $loc_wo_delivery = substr($loc_wo_delivery, 0, -1);

    echo "var chartliDizMonitor = echarts.init(document.getElementById('dizMonitor'));";
    $i_max_locations = get_dataset(DB_PREFIX . "locations_all", $link)->num_rows + 10;
    $erg = create_chartlie_for_diz_monitor($sDates, $loc_w_delivery, $loc_wo_delivery, $i_max_locations);

    echo $erg;
}

function location_mapper($location){
    $location = strtolower($location);
    switch($location){
        case "mri":
            $locId = 1;
            break;
        case "kum":
            $locId = 2;
            break;
        case "ukt":
            $locId = 3;
            break;
        case "uku":
            $locId = 4;
            break;
        case "ukr":
            $locId = 5;
            break;
        case "uks":
            $locId = 6;
            break;
        case "ukau":
            $locId = 7;
            break;
        case "charite":
            $locId = 8;
            break;
        case "umg":
            $locId = 9;
            break;
        case "mhh":
            $locId = 10;
            break;
        case "ukhd":
            $locId = 11;
            break;
        case "uksh":
            $locId = 12;
            break;
        case "ukk":
            $locId = 13;
            break;
        case "ukm":
            $locId = 14;
            break;
        case "ukw":
            $locId = 15;
            break;
        case "ukdd":
            $locId = 16;
            break;
        case "uker":
            $locId = 17;
            break;
        case "ukf":
            $locId = 18;
            break;
        case "ukfr":
            $locId = 19;
            break;
        case "ukgi":
            $locId = 20;
            break;
        case "ukmr":
            $locId = 21;
            break;
        case "ukg":
            $locId = 22;
            break;
        case "ummd":
            $locId = 23;
            break;
        case "um":
            $locId = 24;
            break;
        case "umm":
            $locId = 25;
            break;
        case "uka":
            $locId = 26;
            break;
        case "ukb":
            $locId = 27;
            break;
        case "ume":
            $locId = 28;
            break;
        case "ukh":
            $locId = 29;
            break;
        case "uke":
            $locId = 30;
            break;
        case "ukj":
            $locId = 31;
            break;
        case "ukl":
            $locId = 32;
            break;
        case "umr":
            $locId = 33;
            break;
        case "ukd":
            $locId = 34;
            break;
        case "ukrub":
            $locId = 35;
            break;
        default:
            $locId = -1;
            break;
    }

    return $locId;
}

function location_city_mapper($location){
    $location = strtolower($location);
    switch($location){
        case "mri":
            $locId = 1;
            $cityName = "München (MRI)";
            break;
        case "kum":
            $locId = 2;
            $cityName = "München (KUM)";
            break;
        case "ukt":
            $locId = 3;
            $cityName = "Tübingen";
            break;
        case "uku":
            $locId = 4;
            $cityName = "Ulm";
            break;
        case "ukr":
            $locId = 5;
            $cityName = "Regensburg";
            break;
        case "uks":
            $locId = 6;
            $cityName = "Saarland";
            break;
        case "ukau":
            $locId = 7;
            $cityName = "Augsburg";
            break;
        case "charite":
            $locId = 8;
            $cityName = "Charité";
            break;
        case "umg":
            $locId = 9;
            $cityName = "Göttingen";
            break;
        case "mhh":
            $locId = 10;
            $cityName = "Hannover";
            break;
        case "ukhd":
            $locId = 11;
            $cityName = "Heidelberg";
            break;
        case "uksh":
            $locId = 12;
            $cityName = "Schleswig-Holstein";
            break;
        case "ukk":
            $locId = 13;
            $cityName = "Köln";
            break;
        case "ukm":
            $locId = 14;
            $cityName = "Münster";
            break;
        case "ukw":
            $locId = 15;
            $cityName = "Würzburg";
            break;
        case "ukdd":
            $locId = 16;
            $cityName = "Dresden";
            break;
        case "uker":
            $locId = 17;
            $cityName = "Erlangen";
            break;
        case "ukf":
            $locId = 18;
            $cityName = "Frankfurt";
            break;
        case "ukfr":
            $locId = 19;
            $cityName = "Freiburg";
            break;
        case "ukgi":
            $locId = 20;
            $cityName = "Gießen";
            break;
        case "ukmr":
            $locId = 21;
            $cityName = "Marburg";
            break;
        case "ukg":
            $locId = 22;
            $cityName = "Greifswald";
            break;
        case "ummd":
            $locId = 23;
            $cityName = "Magdeburg";
            break;
        case "um":
            $locId = 24;
            $cityName = "Mainz";
            break;
        case "umm":
            $locId = 25;
            $cityName = "Mannheim";
            break;
        case "uka":
            $locId = 26;
            $cityName = "Aachen";
            break;
        case "ukb":
            $locId = 27;
            $cityName = "Bonn";
            break;
        case "ume":
            $locId = 28;
            $cityName = "Essen";
            break;
        case "ukh":
            $locId = 29;
            $cityName = "Halle";
            break;
        case "uke":
            $locId = 30;
            $cityName = "Hamburg";
            break;
        case "ukj":
            $locId = 31;
            $cityName = "Jena";
            break;
        case "ukl":
            $locId = 32;
            $cityName = "Leipzig";
            break;
        case "umr":
            $locId = 33;
            $cityName = "Rostock";
            break;
        case "ukd":
            $locId = 34;
            $cityName = "Düsseldorf";
            break;
        case "ukrub":
            $locId = 35;
            $cityName = "Bochum";
            break;
        default:
            $locId = -1;
            $cityName = "Not defined";
            break;
    }

    return $cityName;
}
//TO-DO -> improve processing of duplicates
function create_table_single_location_status_values($locId, $link){

    $whereCond_err = "`location_id`=" . $locId . " AND `date_delivery` > '" . start_report_date . "'";

    $entries_loc = get_dataset_where(DB_PREFIX . "sync_details", $whereCond_err, "", $link);
    $entries_db = get_dataset(DB_PREFIX . "fhirprofiles", $link);

    $a_fhir_profiles = [];
    foreach ($entries_loc as $entry){
        if(!in_array($entry["name"], $a_fhir_profiles)){
            $a_fhir_profiles[] = $entry["name"];
        }
    }

    $a_fhir_profiles_db = [];
    $a_fhir_queries_db = [];
    $skippable_ids = [1, 21];

    foreach ($entries_db as $entry_db){
        if(!in_array($entry_db["fhir_profile"],$a_fhir_profiles_db) && !in_array($entry_db["id"], $skippable_ids)){
            $a_fhir_profiles_db[] = $entry_db["fhir_profile"];
            $a_fhir_queries_db[] = $entry_db["fhir_query"];
        }
    }

    $sCardTemplate = '
                        <table id="list_status_table" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>FHIR-Profile</th>';
    $sCardTemplate = $sCardTemplate .  "<td>Query</td>";
    $sCardTemplate = $sCardTemplate .  "<td>Min. 1x lieferbar gewesen</td>";
    $sCardTemplate2 = '
                                </tr>
                            </thead>
                            <tbody>
                            ';

    $i_counter = 0;
    foreach($a_fhir_profiles_db as $aProfile) {
        $sCardTemplate2 = $sCardTemplate2 . "<tr>";
        $sCardTemplate2 = $sCardTemplate2 . "<td>" . $aProfile . "</td>" ;
        $sCardTemplate2 .= "<td>" . $a_fhir_queries_db[$i_counter] . "</td>";

        $whereCond_err_bla = "`location_id`=" . $locId . " AND `date_delivery` > '". start_report_date . "' AND `name` = '" . $aProfile . "';";
        //$whereCond_err = "`location_id`=" . $locId . " AND `date_delivery` > '" . start_report_date . "';";
        $entries_for_single_profile = get_dataset_where(DB_PREFIX . "sync_details", $whereCond_err_bla, "", $link);

        $i_counter_success = 0;
        foreach($entries_for_single_profile as $single_item){
            if($single_item["status"] == "success"){
                $i_counter_success += 1;
            }
        }

        if($i_counter_success > 0){
            $sCardTemplate2 .= "<td>JA</td>";
        } else {
            $sCardTemplate2 .= "<td>NEIN</td>";
        }

        $sCardTemplate2 = $sCardTemplate2 . "</tr>";
        $i_counter += 1;
    }

    $sCardTemplate3 = '        
                            </tbody>
                        </table>';

    echo $sCardTemplate . $sCardTemplate2 . $sCardTemplate3;
}

function create_table_fhir_profiles($link){

   $groups = get_dataset(DB_PREFIX . "fhirprofiles_groups", $link);

   $sCardTemplate = ' 
                       <input type="hidden" name="actiontag" id="cb_group" value=07410120>
                       <table id="list_status_table" class="table table-striped" style="width:100%">
                           <thead>
                               <tr>
                                   <th>FHIR-Profile</th>';

   foreach($groups as $group){
       $sCardTemplate .=    '<th>Group-Id ' . $group["group_label"] . '</th>';
   }

   $sCardTemplate2 = '
                               </tr>
                           </thead>
                           <tbody>';



   $fhir_profiles = get_dataset(DB_PREFIX . "fhirprofiles", $link);

    foreach($fhir_profiles as $fhir_profile){
        $sCardTemplate2 = $sCardTemplate2 . "<tr>";
        $sCardTemplate2 = $sCardTemplate2 . "<td>" . $fhir_profile["fhir_profile"] . "</td>" ;

        $i_counter = 1;
        foreach($groups as $group){

            $checked_state = "";
            if($fhir_profile["group_id"] == $i_counter){
                $checked_state = "checked";
            }

            $sCardTemplate2 = $sCardTemplate2 . "<td>
                                                    <form method='POST' id='formSubmit' name='temp' action='" . $_SERVER["PHP_SELF"]. "?profile_edit'>
                                                        <input type='hidden' name='actiontag' id='actiontag' value='07410120'>
                                                        <input type='checkbox' name='checkbox_id' onChange='submit();' " . $checked_state . ">
                                                        <input type='hidden' id='user_id' name='fhir_profile_id' value='" . $fhir_profile["id"] . "' style='visibility: hidden' />
                                                        <input type='hidden' id='checkbox_group_id' name='checkbox_group_id' value='" . $i_counter . "' style='visibility: hidden' />
                                                    </form>
                                                </td>" ;
            $i_counter += 1;
        }
        $sCardTemplate2 = $sCardTemplate2 . "</tr>";
    }

    $sCardTemplate3 = '
                           </tbody>
                       </table></form>';

   echo $sCardTemplate . $sCardTemplate2 . $sCardTemplate3;
}

function set_group_id_for_fhir_profile($link){
    if(isset($_POST["checkbox_id"]) && $_POST["checkbox_id"] == "on"){
        $group_id = $_POST["checkbox_group_id"];
        $values = [$group_id];
        $profile_id = $_POST["fhir_profile_id"];
    } else if(!isset($_POST["checkbox_id"])){
        //$group_id = $_POST["checkbox_group_id"];
        $values = [0];
        $profile_id = $_POST["fhir_profile_id"];
    }

    $element_names = ["group_id"];
    $where_cond = "`id` = " . $profile_id;
    $update = build_update_query(DB_PREFIX . "fhirprofiles", $values, $element_names, $where_cond);
    //echo $update;

    execute_sql_queries([$update]);
}

function check_diz_connection_status($link){

    if(isset($_SESSION["user_id"])){

        $user_info = get_dataset_where(DB_PREFIX . "users_stattab", "id=" . $_SESSION["user_id"], "", $link);
        $user_infos = [];
        foreach($user_info as $info){
            $user_infos[] = $info;
        }

        $connected_loc = get_dataset_where(DB_PREFIX . "locations_all", "id_connected=" . $user_infos[0]["location_name_short"], "", $link);

        if($connected_loc->num_rows == 1){
            $locs = [];
            foreach ($connected_loc as $loc){
                $locs[] = $loc;
            }
            echo "<p>Ihr Standort " . $locs[0]["loc_display"] . " ist bereits an das FDPG angeschlossen.</p>";
        }
        else{
            echo "<p>Ihr Standort ist noch nicht an das FDPG angeschlossen.</p>";
        }
    }
}

?>