<?php
/**
 *
 * X-DASH
 *
 * Version: 2.1
 *
 * Data dashbord showing status tables from connected DIZ locations in Germany.
 *
 * Author: Steven Rohner <steven.rohner@tmf-ev.de>, Martin Bartow <martin.bartow@tmf-ev.de>,
 * Philip Kleinert <philip.kleinert@tmf-ev.de>
 *
 * Copyright (c) 2022 - 2023 TMF e.V. (built for the MII)
 *
 * Licensed under the terms of the MIT License, as specified in the included LICENSE file.
 *
 * For more information about this application, see https://roadmap.forschen-fuer-gesundheit.de/.
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (X-DASH), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:

 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.

 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * Developed and built in Berlin. Powered by coffee.
 *
 */

session_start();

if (isset($_POST['username']) && isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if ($username === 'dashxmaster01' && $password === 'aBT11485$35100srigugumm482!') {
    $_SESSION['logged_in'] = true;
    $_SESSION['username'] = $username;
    $_SESSION['expiry'] = time() + 60 * 60;
  }
}

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && $_SESSION['expiry'] > time()) {
  
  echo "<!-- X -->";
} else {
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login Formular</title>
</head>
<body>
  <form action="xdash.php" method="post">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    <br><br>
    <label for="password">Passwort:</label>
    <input type="password" id="password" name="password" required>
    <br><br>
    <input type="submit" value="Einloggen">
  </form>
</body>
</html>
<?php
exit;
}






// error config

include 'include/lib.func.php';
include 'include/cfg.inc.php';

// Stuff for the Son of J ;-)
require_once("./include/explore.php");

$hDB = db_connect();
$sLog = "";

		$aPersonKeys = array("SUM-PATIENT-MII", "SUM-PATIENT-PSEUDONYMISIERT-MII", "SUM-RESEARCHSUBJECT-MII", "SUM-TODESURSACHE-MII", "SUM-VITALSTATUS-MII");
		$aFallKeys = array("SUM-ENCOUNTER-ABTEILUNG-MII", "SUM-ENCOUNTER-EINRICHTUNG-MII", "SUM-ENCOUNTER-VERSORGUNG-MII");
		$aDiagnoseKeys = array("SUM-CONDITION-MII");
		$aLaborKeys = array("SUM-DIAGNOSTICREPORT-MII", "SUM-OBSERVATION-LAB-MII", "SUM-SERVICEREQUEST-MII", "SPECIMEN (SUM-SPECIMEN-ALL)");
		$aProzedurKeys = array("SUM-PROCEDURE-MII");
		$aMedikationKeys = array("SUM-MDEICATION-ADMINISTRATION-MII", "SUM-MEDICATION-STATEMENT-MII");
		$aConsentKeys = array("SUM-CONSENT-MII");
		$aBioprobenKeys = array("SUM-SPECIMEN-MII-BIOBANK");

		$aAllKeys = array_merge($aPersonKeys, $aFallKeys, $aDiagnoseKeys, $aLaborKeys, $aProzedurKeys, $aMedikationKeys, $aConsentKeys, $aBioprobenKeys);


$location_id = $_GET['lid'];
$kdskey = $_GET['kid'];
$network = $_GET['net'];
$iTrendDays = $_GET['range'];

if (!$iTrendDays) {
	$iTrendDays=40;
}

$trend_end_date = date("Y-m-d");
$trend_start_date = subtractDays($trend_end_date, $iTrendDays);

$trend_end_date_before = $trend_start_date;
$trend_start_date_before = subtractDays($trend_end_date_before, $iTrendDays);



if (!$location_id) {
	$network=1;
}

function get_max_response($location_id, $hDB) {

    $query = "SELECT MAX(response) as max_response
              FROM tmfev114_sync_details
              WHERE name = 'sum-patient-mii' AND location_id = '".$location_id."' AND date_created >= DATE_SUB(NOW(), INTERVAL 7 DAY)";


    $result = mysqli_query($hDB, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['max_response'];
    } else {
    	return 0;
    }
}


function getYearlyChartData($location_id, $hDB) {

  $query = "SELECT year, value FROM tmfev114_n_patients_by_year WHERE location_id = ".$location_id." AND sync_id = (SELECT MAX(sync_id) FROM tmfev114_n_patients_by_year WHERE location_id = ".$location_id.")";
  $result = mysqli_query($hDB, $query);
  $chartData = array();
  while ($row = mysqli_fetch_assoc($result)) {
    $chartData[] = array((int)$row['year'], (int)$row['value']);
  }
  return json_encode($chartData);
}


 
function getLocationNameShort($id, $hDB) {

  $sql = "SELECT name_short FROM tmfev114_locations WHERE id = ".$id;
  $stmt = mysqli_prepare($hDB, $sql);

  mysqli_stmt_bind_param($stmt, "i", $id);

  mysqli_stmt_execute($stmt);

  mysqli_stmt_bind_result($stmt, $name_short);
  mysqli_stmt_fetch($stmt);

  return $name_short;
}


function getResponse($location_id, $name, $hDB) {

    $sql = "SELECT * FROM tmfev114_sync_details WHERE location_id = ".$location_id." AND name = '".$name."' ORDER BY id DESC LIMIT 1";

    $result = mysqli_query($hDB, $sql);

    if (mysqli_num_rows($result) > 0) {

        $row = mysqli_fetch_assoc($result);
		$name = $row["name"];
        $response = $row["response"];
        $date = $row["date_delivery"];
        
        return array($name, $response, $date);
    } else {
        return array();
    }
} // end function


function checkDeliveryStatus($location_id, $hDB) {
    $query = "SELECT COUNT(*) as count FROM tmfev114_sync_details WHERE location_id = ".$location_id." AND date_delivery > DATE_SUB(NOW(), INTERVAL 2 DAY) LIMIT 1";
    $result = mysqli_query($hDB, $query);
    $count = mysqli_fetch_assoc($result)['count'];
    
    if ($count > 0) {
        return '<i class="fa fa-database text-success" aria-hidden="true"></i>';
    } else {
        return '<i class="fa fa-database text-danger" aria-hidden="true"></i>';
    }
}


// function to check amount of years delivered
function checkYearStatus($location_id, $hDB) {

  $query = "SELECT year, value FROM tmfev114_n_patients_by_year WHERE location_id = ".$location_id." AND sync_id = (SELECT MAX(sync_id) FROM tmfev114_n_patients_by_year WHERE location_id = ".$location_id.")";
  $result = mysqli_query($hDB, $query);
  $chartData = array();
  while ($row = mysqli_fetch_assoc($result)) {
    $chartData[] = array((int)$row['year'], (int)$row['value']);
  }
  $max = count($chartData);
  $count = 0;
  for($i = 0; $i <= $max; $i++) {
    if ($chartData[$i][1] > 0) {
      $count++;
    }
  }
  return $count;
}

function getFhirServer($location_id, $hDB) {
    $query = "SELECT `name`,`version` FROM tmfev114_servers
              WHERE id = (SELECT MAX(DISTINCT(server_id)) FROM tmfev114_sync_details WHERE location_id =" . $location_id . ")";
    $result = mysqli_query($hDB, $query);
    $chartData = array();

    $rows = mysqli_fetch_assoc($result);
    while ($row = mysqli_fetch_assoc($result)) {
        $chartData[] = array($row['name'], $row['version']);
    }

    return $rows;
}

function getData($location_id, $name, $hDB) {

    $sql = "SELECT date_delivery, response FROM tmfev114_sync_details WHERE location_id = ".$location_id." AND name = '".$name."' ORDER BY date_delivery ASC";

    $result = mysqli_query($hDB, $sql);

    if (mysqli_num_rows($result) > 0) {

        $data = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $date_delivery = strtotime($row["date_delivery"]) * 1000;
            $response = (int)$row["response"]; 
            $data[] = array($date_delivery, $response);
        }

        return $data;
    } else {

        return array();
    }
}



function parseResponse(array $val, $kdskey) {

	if (strtoupper($kdskey)==strtoupper($val[0])) {
		$cssactive = " activekey";
	} else {
		$cssactive = "";	
	}

	if ($val[1]<1000 && $val[1]>0 && $val[1] != NULL) {
		$response = '<span class="badge bg-warning'.$cssactive.'">'.strtoupper($val[0]).'</span>';
	} else if ($val[1]>=1000) {
		$response = '<span class="badge bg-success'.$cssactive.'">'.strtoupper($val[0]).'</span>';
	} else {
		$response = '<span class="badge bg-danger'.$cssactive.'">'.strtoupper($val[0]).'</span>';
	}
	
	return $response;
}




function getUniqueLocationData($hDB) {
  $sql = "SELECT DISTINCT location_id, name_short FROM tmfev114_sync_details JOIN tmfev114_locations ON tmfev114_sync_details.location_id = tmfev114_locations.id";
  $result = mysqli_query($hDB, $sql);
  $locationData = array();
  while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $locationData[] = array("id" => $row["location_id"], "name_short" => $row["name_short"]);
  }
  return $locationData;
}



function getLocationDropdown($hDB,$kdskey) {

  $locationData = getUniqueLocationData($hDB);
  
  $dropdown = '<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" style="">';

  foreach ($locationData as $location) {
    $dropdown .= '<li><a class="dropdown-item" href="xdash.php?lid=' . $location['id'] . '&kid='.$kdskey.'">' . $location['name_short'] . '</a></li>';
  }
  $dropdown .= '</ul>';

  return $dropdown;
}

function convertNameShortToLocationName($nameShort){
    switch($nameShort){
        case "Charite":
            return "Berlin (Charité)";
        case "KUM":
            return "München (KUM)";
        case "MRI":
            return "München (MRI)";
        case "UKAU":
            return "Augsburg (UKAU)";
        case "UKB":
            return "Bonn (UKB)";
        case "UKDD":
            return "Dresden (UKDD)";
        case "UKE":
            return "Hamburg (UKE)";
        case "UKEr":
            return "Erlangen (UKEr)";
        case "UKFR":
            return "Freiburg (UKFR)";
        case "UKF":
            return "Frankfurt (UKF)";
        case "UKGI":
            return "Gießen (UKGI)";
        case "UKHD":
            return "Heidelberg (UKHD)";
        case "UKH":
            return "Halle (UKH)";
        case "UKJ":
            return "Jena (UKJ)";
        case "UKL":
            return "Leipzig (UKL)";
        case "UKMR":
            return "Marburg (UKMR)";
        case "UKR":
            return "Regensburg (UKR)";
        case "UKSH":
            return "Schleswig-Holstein/Kiel (UKSH)";
        case "UKT":
            return "Tübingen (UKT)";
        case "UKU":
            return "Ulm (UKU)";
        case "UKW":
            return "Würzburg (UKW)";
        case "UMM":
            return "Mannheim (UMM)";
        case "UKS":
            return "Saarland (UKS)";
        case "UKG":
            return "Greifswald (UKG)";
        case "UME":
            return "Mannheim (UMM)";
        case "UKK":
            return "Köln (UKK)";
        case "UKM":
            return "Münster (UKM)";
        case "UM":
            return "Mainz (UM)";
        case "UKA":
            return "Aachen (UKA)";
        default:
            return "";
    }

}



function getNameShortArray($start_date, $end_date, $trendwindow, $hDB) {

	global $aPersonKeys,$aFallKeys,$aDiagnoseKeys,$aLaborKeys,$aProzedurKeys,$aMedikationKeys,$aConsentKeys,$aBioprobenKeys,$aAllKeys;
	global $iTrendDays;

	$trend_end_date_before = $start_date;
	$trend_start_date_before = subtractDays($trend_end_date_before, $trendwindow);


  $sql = "SELECT * FROM tmfev114_locations";
  $result = mysqli_query($hDB, $sql);

  if (mysqli_num_rows($result) > 0) {

    $name_short_array = array();

    while ($row = mysqli_fetch_assoc($result)) {
    
    	
		$sql2 = "SELECT * 
            FROM tmfev114_sync_details 
            WHERE location_id = '".$row["id"]."'
            ORDER BY id DESC
            LIMIT 1";

    	$result2 = mysqli_query($hDB, $sql2);
    	
    	while ($row2 = mysqli_fetch_assoc($result2)) {
    		$row["date_delivery"] = $row2["date_delivery"];
    	}
    	
    	$iPatientsSum = get_max_response($row["id"], $hDB);
    	
    	if (!isset($iPatientsSum)) {
    		$iPatientsSum=0;
    	}
    
		$result1 = getResponsePercentage($row["id"], $aAllKeys, $start_date, $end_date, $hDB);
		$result2 = getResponsePercentage($row["id"], $aAllKeys, $trend_start_date_before, $trend_end_date_before, $hDB);
		$sResultPercent = checkTrend($result1, $result2, "All");    
		
		
    
		$result1 = getResponsePercentage($row["id"], $aPersonKeys, $start_date, $end_date, $hDB);
		$result2 = getResponsePercentage($row["id"], $aPersonKeys, $trend_start_date_before, $trend_end_date_before, $hDB);
		$sResultPercent_Person = checkTrend($result1, $result2, "Person");   
		     
		$result1 = getResponsePercentage($row["id"], $aFallKeys, $start_date, $end_date, $hDB);
		$result2 = getResponsePercentage($row["id"], $aFallKeys, $trend_start_date_before, $trend_end_date_before, $hDB);
		$sResultPercent_Fall = checkTrend($result1, $result2, "Fall");  	
			     
		$result1 = getResponsePercentage($row["id"], $aDiagnoseKeys, $start_date, $end_date, $hDB);
		$result2 = getResponsePercentage($row["id"], $aDiagnoseKeys, $trend_start_date_before, $trend_end_date_before, $hDB);
		$sResultPercent_Diagnose = checkTrend($result1, $result2, "Diagnose");    
    
		$result1 = getResponsePercentage($row["id"], $aLaborKeys, $start_date, $end_date, $hDB);
		$result2 = getResponsePercentage($row["id"], $aLaborKeys, $trend_start_date_before, $trend_end_date_before, $hDB);
		$sResultPercent_Labor = checkTrend($result1, $result2, "Labor");    
			     
		$result1 = getResponsePercentage($row["id"], $aProzedurKeys, $start_date, $end_date, $hDB);
		$result2 = getResponsePercentage($row["id"], $aProzedurKeys, $trend_start_date_before, $trend_end_date_before, $hDB);
		$sResultPercent_Prozedur = checkTrend($result1, $result2, "Prozedur");    
    
		$result1 = getResponsePercentage($row["id"], $aMedikationKeys, $start_date, $end_date, $hDB);
		$result2 = getResponsePercentage($row["id"], $aMedikationKeys, $trend_start_date_before, $trend_end_date_before, $hDB);
		$sResultPercent_Medikation = checkTrend($result1, $result2, "Medikation");    

        $fhirServerInfos = getFhirServer($row["id"], $hDB);

        $locNameWithShortage = convertNameShortToLocationName($row["name_short"]);

    	array_push($name_short_array, '
    <div class="col-xl-3 col-sm-6">
                    <div class="card mb-4">
                        <div class="card-body p-3">
                            <div class="row"> 
                                <h4 class="font-weight-bolder mb-0">
                                    <a href="?lid='.$row["id"].'&kid=sum-condition-mii">'. $locNameWithShortage .'</a>&nbsp;
                                    '.checkDeliveryStatus($row["id"],$hDB).'&nbsp;
                                    <a href="?lid='.$row["id"].'&kid=sum-condition-mii#myChartYearly" class="text-sm mb-0"> '.checkYearStatus($row["id"],$hDB).'&nbsp;Jahre</a>
                                </h4>
                                <div class="col-8" >

                                    <div class="numbers"> 
                                        <!--<div class="text-sm mb-0 text-capitalize text-dark font-weight-bold">'.$iTrendDays.' Days Data Growth Trend</div>-->
<!--
<div style="display: flex; align-items: center; flex-wrap: wrap; width: 100%; font-size: 11px;"">
  <div style="width: 30%; flex-shrink: 0; white-space: nowrap; flex-wrap: wrap; font-weight: bold;">FROM:</div>
  <div style="width: 70%; white-space: nowrap;">'.$trend_start_date_before.' to '.$trend_end_date_before.'</div>
</div>

<div style="display: flex; align-items: center; flex-wrap: wrap; width: 100%; font-size: 11px;"">
  <div style="width: 30%; flex-shrink: 0; white-space: nowrap; flex-wrap: wrap; font-weight: bold;">TO:</div>
  <div style="width: 70%; white-space: nowrap;">'.$start_date.' to '.$end_date.'</div>
</div>
-->
<div style="font-size: 11px;" id="timer'.$row["id"].'">




</div>


<script>
  startTimer("'.$row["date_delivery"].'", document.getElementById("timer'.$row["id"].'"));
</script>

<div style="display: flex; align-items: center; flex-wrap: wrap; width: 100%; font-size: 11px;">
	  <div style="width: 50%; flex-shrink: 0; white-space: nowrap; flex-wrap: wrap; font-weight: bold;">FHIR SERVER:</div>
	  <div style="width: 50%; white-space: nowrap;"> ' . $fhirServerInfos["name"] . ' (' . $fhirServerInfos["version"] . ')</div>
	</div>
<br>

									<b>Personen: </b>'.$iPatientsSum.' 

                                    </div>

                                    <br>


                                </div>
                                <div class="col-4 text-end">

                                </div>
                               '.$sResultPercent_Person.'
                                
								'.$sResultPercent_Fall.'

								'.$sResultPercent_Diagnose.'

								'.$sResultPercent_Labor.'

								'.$sResultPercent_Prozedur.'

								'.$sResultPercent_Medikation.'


                            </div>
                        </div>
                    </div>
                </div>');
    
      
    }

    return $name_short_array;
  } else {

    echo "Keine Datensätze gefunden";
  }
}

function showMainMenu() {

	return '
			 <nav class="navbar navbar-expand navbar-light navbar-bg" id="navbarTop">
                <ul class="navbar-nav d-none d-lg-block">
                    <li class="nav-item px-2 dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Men&uuml;
                        </a>
                        <div class="dropdown-menu dropdown-menu-start dropdown-mega" aria-labelledby="servicesDropdown">
                            <div class="d-md-flex align-items-start justify-content-start">
                                <div class="dropdown-mega-list">
                                    <div class="dropdown-header">Auswertung</div>
                                    <a class="dropdown-item" href="https://forschen-fuer-gesundheit.de/xdash.php">Standorte</a>
                                    <a class="dropdown-item" href="https://forschen-fuer-gesundheit.de/xdash.php?tab">Profilübersicht</a>
                                </div>
                                <div class="dropdown-mega-list">
                                    <div class="dropdown-header">Externe Links</div>
                                    <a class="dropdown-item" href="https://github.com/medizininformatik-initiative/" target="_blank">MII GitHub</a>
                                    <a class="dropdown-item" href="https://simplifier.net/MedizininformatikInitiative-ModulConsent/~introduction" target="_blank">MII@Simplifier.net</a>
                                    <a class="dropdown-item" href="https://app.slack.com/huddle/T014VFTEU92/C014MBZ74NS" target="_blank">MII Slack-Channel</a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </nav>	
	';

}



function subtractDays($date, $days) {
    $datetime = new DateTime($date);
    $datetime->sub(new DateInterval("P" . $days . "D"));
    return $datetime->format("Y-m-d");
}


function getResponsePercentage($location_id, $kdskey, $start_date, $end_date, $hDB) {
	global $sLog;
    $kdskey_query = "";
    
    if (is_array($kdskey)) {
        $kdskey_query = "AND (";
        foreach ($kdskey as $key) {
            $kdskey_query .= "name = '".$key."' OR ";
        }
        $kdskey_query = rtrim($kdskey_query, " OR ");
        $kdskey_query .= ")";
    } else {
        $kdskey_query = "AND name = '".$kdskey."'";
    }
    
  	$sql = "SELECT location_id, MAX(response) as response
            FROM tmfev114_sync_details
            WHERE location_id = ".$location_id."
            ".$kdskey_query."
            AND date_delivery BETWEEN '".$start_date."' AND '".$end_date."'";
            
    $result = mysqli_query($hDB, $sql);
    
    $response_sum = 0;
    $location_id = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $location_id = $row['location_id'];
        $response_sum += $row['response'];
    }
   
    if ($location_id == 0) {
        return 0;
    }
    
    return $response_sum;
}




function checkTrend($res1, $res2, $type) {

  if ($res2 > 1) {
    $percentage = ((($res1) - $res2) / ($res2)) * 100;
  }else {
    $percentage = 0;
  }
    $percentage = number_format($percentage, 2, ',', '');
    
    if ($type=="Person" || $type=="Fall" || $type=="Diagnose" || $type=="Labor" || $type=="Prozedur" || $type=="Medikation") {
      if ($percentage > 0) {
      return '
                                      <div class="d-flex align-items-center">
                                          <p class="text-sm mb-0">'.$type.'<br></p>
                                          <i class="fa fa-caret-up text-fail text-xxs me-1 ms-auto text-success" aria-hidden="true"></i>
                                          <p class="text-xs text-success mb-0">'.$percentage.'%</p>
                                      </div>		
      ';
      } elseif ($percentage == 0) {
      return '
      <div class="d-flex align-items-center">
      <p class="text-sm mb-0">'.$type.'<br></p>
      <i class="fa fa-minus text-fail text-xxs me-1 ms-auto text-fail" aria-hidden="true"></i>
      <p class="text-xs text-pending mb-0">0%</p>
  </div>	

      ';
      } else {
      return '
      <div class="d-flex align-items-center">
      <p class="text-sm mb-0">'.$type.'<br></p>
      <i class="fa fa-caret-down text-fail text-xxs me-1 ms-auto text-danger" aria-hidden="true"></i>
      <p class="text-xs text-danger mb-0">'.$percentage.'%</p>
  </div>		
      ';		
      } // end if 
      
    
    } else {
        if ($percentage > 0) {
          return '
                                          <span class="badge bg-success-soft text-xxs">
                                              <i class="fas fa-angle-up text-success" aria-hidden="true"></i>
                                          </span>
                                          <span class="text-xs font-weight-bolder ms-1">+'.$percentage.'%<br></span>        
          ';
        } elseif ($percentage < 0) {
          return '
                                          <span class="badge bg-danger-soft text-xxs">
                                              <i class="fas fa-angle-down text-danger" aria-hidden="true"></i>
                                          </span>
                                          <span class="text-xs font-weight-bolder ms-1">'.$percentage.'%<br></span>        
          ';
        } else {
          return '
                                          <span class="badge bg-muted-soft text-xxs">
                                              <i class="fas fa-minus text-muted" aria-hidden="true"></i>
                                          </span>
                                          <span class="text-xs font-weight-bolder ms-1"><br></span>        
          ';
        } // end if
      } // end if - type

  }

/*
function create_table_single_location_drop($locId, $hDB){

    $dropFilter = new DateTime(start_report_date);
    $sCardTemplate = '
                        <table id="list_profiles_location_detail" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>FHIR-Profile</th>';

    $query = "SELECT `name_long` FROM `tfev114_locations` WHERE `location_id`=" . $locId;
    $result = mysqli_query($hDB, $query);
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

    while ($row = mysqli_fetch_assoc($result)) {
        $locationData[] = array($row['name_long']);
    }
    echo $locationData["name_long"];

    //$sLocName = get_single_column_value(DB_PREFIX."locations","name_long", "id", $locId, $hDB);
    $sCardTemplate = str_replace("<th>FHIR-Profile</th>", "<th>" . $locationData["name_long"] . "</th>", $sCardTemplate);

    //$aDrops_unrev = get_distinct_column_values(DB_PREFIX."sync_details", "`date_delivery`", "`location_id`", $locId, $link);
    //$aDrops = array_reverse($aDrops_unrev);

    $query = "SELECT DISTINCT `date_delivery` FROM `tfev114_locations` WHERE `location_id`=" . $locId . " ORDER BY `date_delivery` DESC ";
    $result = mysqli_query($hDB, $query);
    $aDrops = mysqli_fetch_all($result, MYSQLI_ASSOC);

    //$dataset = get_dataset_where(DB_PREFIX . "sync_details", "location_id=" . $locId,"", $link);
    //$temp = mysqli_fetch_all($dataset);

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
    //$aProfiles = get_information_from_database("SELECT DISTINCT `name` FROM `tmfev114_sync_details` WHERE `location_id`=" . $locId);

    $query = "SELECT DISTINCT `name` FROM `tmfev114_sync_details` WHERE `location_id`=" . $locId;
    $result = mysqli_query($hDB, $query);
    $aProfiles = mysqli_fetch_all($result, MYSQLI_ASSOC);

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

        $sCardTemplate2 = $sCardTemplate2 . "</tr>";

    }

    $sCardTemplate3 = '
                            </tbody>
                        </table>';

    echo $sCardTemplate . $sCardTemplate2 . $sCardTemplate3;


}
*/

function create_location_drop($locId, $hDB){
    $dropFilter = new DateTime("2022-08-29 00:00:00");
    // get location-specific name
    $query = "SELECT name_long FROM tmfev114_locations WHERE id=" . $locId;
    $result = mysqli_query($hDB, $query);
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // get location-specific drops
    $query = "SELECT DISTINCT date_delivery FROM tmfev114_sync_details WHERE location_id=" . $locId . " ORDER BY date_delivery DESC ";
    $result = mysqli_query($hDB, $query);
    $aDrops = mysqli_fetch_all($result, MYSQLI_ASSOC);


    $sCardTemplate = '
                        <table id="list_profiles_location_detail" class="table table-striped" style="width:100%;">
                            
                            <thead>
                                <tr>
                                    <th>FHIR-Profile</th>';

    $sCardTemplate = str_replace("<th>FHIR-Profile</th>", "<th>" . $rows[0]["name_long"] . "</th>", $sCardTemplate);

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

    // get location-specific profiles
    $query = "SELECT DISTINCT `name` FROM `tmfev114_sync_details` WHERE `location_id`=" . $locId;
    $result = mysqli_query($hDB, $query);
    $aProfiles = mysqli_fetch_all($result, MYSQLI_ASSOC);

    /*
    echo "<br><br>Profile";
    var_dump($aProfiles);
    echo "<br><br>Drops";
    var_dump($aDrops);
    echo "<br><br>";
    */

    //$dataset = get_dataset_where(DB_PREFIX . "sync_details", "location_id=" . $locId,"", $link);
    $query = "SELECT * FROM tmfev114_sync_details WHERE location_id=" . $locId;
    $result = mysqli_query($hDB, $query);
    $temp = mysqli_fetch_all($result);

    //while($row = mysqli_fetch_assoc($aProfiles)){
    foreach($aProfiles as $profile){
        //$profile = $row["name"];
        $sCardTemplate2 = $sCardTemplate2 . "<tr>";
        $sCardTemplate2 = $sCardTemplate2 . "<td>" . $profile["name"] . "</td>";
        $profile_name = $profile["name"];

        for($i = 0; $i < count($aDrops); $i++){
            $date_array = $aDrops[$i];
            $date = $date_array["date_delivery"];

            $adults = array_filter($temp, function($value) use ($profile_name, $date) {
                return $value[4] == $profile_name && $value[9] == $date;
            });

            if(count($adults) > 0){


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

        $sCardTemplate2 = $sCardTemplate2 . "</tr>";

    }

    $sCardTemplate3 = '
                            </tbody>
                        </table>';

    echo $sCardTemplate . $sCardTemplate2 . $sCardTemplate3;
}


?>

<html>

<?php
// SO Detail View
if (isset($location_id) && !isset($network)) {
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>DIZ DASH-X</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./assets-x/css/theme.css">
    <link rel="stylesheet" href="./assets-x/css/loopple/loopple.css">
<style>
  .activekey {
    animation: pulse 1s infinite;
  }

  @keyframes pulse {
    0% {
      transform: scale(1);
    }
    50% {
      transform: scale(1.05);
    }
    100% {
      transform: scale(1);
    }
  }
  	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
	<style>
		canvas {
			-webkit-print-color-adjust: exact;
			print-color-adjust: exact;
		}
	</style>
</style>    
</head>
<body class="null">
    <div class="wrapper">
        <div class="main" id="panel">
<?php

echo showMainMenu();

?>            
            <main class="content">
                <div class="container-fluid p-0">

                    <div class="card flex-fill">
                        <div class="card-header">

                            <h5 class="card-title mb-0"><a href="xdash.php?net=1">Alle Standorte</a> > <?php echo getLocationNameShort($location_id, $hDB);?></h5>

                            <div class="dropdown">
                            	<br>
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    Standort
                                </button>
                                <a href="/xdash.php?location_id=<?php echo $_GET["lid"]?>&showLastDrop" target="_blank">Datentabelle anzeigen</a>

                            <?php echo getLocationDropdown($hDB,$kdskey);?>
                            </div>

                        </div>
                        <table class="table table-hover my-0">
                            <thead>
                                <tr>
                                    <th>Modul</th>
                                    <th class="d-none d-xl-table-cell"></th>
                                    <th class="d-none d-xl-table-cell"></th>
                                    <th></th>
                                    <th class="d-none d-md-table-cell"></th>
                                    <th class="d-none d-md-table-cell"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr> 
                                    <td>Person</td>
                                    <td><a href="?lid=<?php echo $location_id;?>&kid=sum-patient-mii"><?php echo parseResponse(getResponse($location_id, "sum-patient-mii", $hDB), $kdskey); ?></a></td>
                                    <td><a href="?lid=<?php echo $location_id;?>&kid=sum-patient-pseudonymisiert-mii"><?php echo parseResponse(getResponse($location_id, "sum-patient-pseudonymisiert-mii", $hDB), $kdskey); ?></a></td>
                                    <td><a href="?lid=<?php echo $location_id;?>&kid=sum-researchsubject-mii"><?php echo parseResponse(getResponse($location_id, "sum-researchsubject-mii", $hDB), $kdskey); ?></a></td>
                                    <td><a href="?lid=<?php echo $location_id;?>&kid=sum-todesursache-mii"><?php echo parseResponse(getResponse($location_id, "sum-todesursache-mii", $hDB), $kdskey); ?></a></td>
                                    <td><a href="?lid=<?php echo $location_id;?>&kid=sum-vitalstatus-mii"><?php echo parseResponse(getResponse($location_id, "sum-vitalstatus-mii", $hDB), $kdskey); ?></a></td>
                                </tr>
                                <tr>
                                    <td>Fall</td>
                                    <td><a href="?lid=<?php echo $location_id;?>&kid=sum-encounter-abteilung-mii"><?php echo parseResponse(getResponse($location_id, "sum-encounter-abteilung-mii", $hDB), $kdskey); ?></a></td>
                                    <td><a href="?lid=<?php echo $location_id;?>&kid=sum-encounter-einrichtung-mii"><?php echo parseResponse(getResponse($location_id, "sum-encounter-einrichtung-mii", $hDB), $kdskey); ?></a></td>
                                    <td><a href="?lid=<?php echo $location_id;?>&kid=sum-encounter-versorgung-mii"><?php echo parseResponse(getResponse($location_id, "sum-encounter-versorgung-mii", $hDB), $kdskey); ?></a></td>
                                    <td class="d-none d-md-table-cell"></td>
                                    <td class="d-none d-md-table-cell"></td>

                                </tr>
                                <tr>
                                    <td>Diagnose</td>
                                    <td><a href="?lid=<?php echo $location_id;?>&kid=sum-condition-mii"><?php echo parseResponse(getResponse($location_id, "sum-condition-mii", $hDB), $kdskey); ?></a></td>
                                    <td class="d-none d-md-table-cell"></td>
                                    <td class="d-none d-md-table-cell"></td> 
                                    <td class="d-none d-md-table-cell"></td>
                                    <td class="d-none d-md-table-cell"></td>

                                </tr> 
                                <tr>
                                    <td>Labor</td>
                                    <td><a href="?lid=<?php echo $location_id;?>&kid=sum-diagnosticreport-mii"><?php echo parseResponse(getResponse($location_id, "sum-diagnosticreport-mii", $hDB), $kdskey); ?></a></td>
                                    <td><a href="?lid=<?php echo $location_id;?>&kid=sum-observation-lab-mii"><?php echo parseResponse(getResponse($location_id, "sum-observation-lab-mii", $hDB), $kdskey); ?></a></td>
                                    <td><a href="?lid=<?php echo $location_id;?>&kid=sum-servicerequest-mii"><?php echo parseResponse(getResponse($location_id, "sum-servicerequest-mii", $hDB), $kdskey); ?></a></td>
                                    <td><a href="?lid=<?php echo $location_id;?>&kid=sum-specimen-all"><?php echo parseResponse(getResponse($location_id, "sum-specimen-all", $hDB), $kdskey); ?></a></td>
                                    <td class="d-none d-md-table-cell"></td>
                                </tr>
                                <tr>
                                    <td>Prozedur<br></td>
                                    <td><a href="?lid=<?php echo $location_id;?>&kid=sum-procedure-mii"><?php echo parseResponse(getResponse($location_id, "sum-procedure-mii", $hDB), $kdskey); ?></a></td>
                                    <td class="d-none d-md-table-cell"></td>
                                    <td class="d-none d-md-table-cell"></td>
                                    <td class="d-none d-md-table-cell"></td>
                                    <td class="d-none d-md-table-cell"></td> 
                                </tr>
                                <tr>
                                    <td>Medikation</td>
                                    <td><a href="?lid=<?php echo $location_id;?>&kid=sum-medication-administration-mii"><?php echo parseResponse(getResponse($location_id, "sum-medication-administration-mii", $hDB), $kdskey); ?></a></td>
                                    <td><a href="?lid=<?php echo $location_id;?>&kid=sum-medication-statement-mii"><?php echo parseResponse(getResponse($location_id, "sum-medication-statement-mii", $hDB), $kdskey); ?></a></td>
                                    <td class="d-none d-md-table-cell"></td>
                                    <td class="d-none d-md-table-cell"></td>
                                    <td class="d-none d-md-table-cell"></td>
                                </tr>
                                <tr>
                                    <td>Consent</td>
                                    <td><a href="?lid=<?php echo $location_id;?>&kid=sum-consent-mii"><?php echo parseResponse(getResponse($location_id, "sum-consent-mii", $hDB), $kdskey); ?></a></td>
                                    <td class="d-none d-md-table-cell"></td>
                                    <td class="d-none d-md-table-cell"></td>
                                    <td class="d-none d-md-table-cell"></td>
                                    <td class="d-none d-md-table-cell"></td>

                                </tr>
                                <tr>
                                    <td>Bioproben</td>
                                    <td><a href="?lid=<?php echo $location_id;?>&kid=sum-specimen-mii-biobank"><?php echo parseResponse(getResponse($location_id, "sum-specimen-mii-biobank", $hDB), $kdskey); ?></a></td>
                                    <td class="d-none d-md-table-cell"></td>
                                    <td class="d-none d-md-table-cell"></td>
                                    <td class="d-none d-md-table-cell"></td>
                                    <td class="d-none d-md-table-cell"></td>

                                </tr>
                                <tr>
                                    <td>Intensive Care Unit</td>
                                    <td class="d-none d-md-table-cell"></td>
                                    <td class="d-none d-md-table-cell"></td>
                                    <td class="d-none d-md-table-cell"></td>
                                    <td class="d-none d-md-table-cell"></td>
                                    <td class="d-none d-md-table-cell"></td>

                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="card flex-fill w-100">
                        <div class="card-header">

                            <h5 class="card-title mb-0">KDS Module <?php echo $kdskey;?></h5>

<?php
$dStartDate = "2021-01-01";
$name = $kdskey;
$data = getData($location_id, $name, $hDB);

// print_r($data);

$startDate = mktime(0, 0, 0, 8, 1, 2022);  // 01.08.2022
if (($data[0][0]/1000) > $startDate) {
  $year = date("Y", $data[0][0]/1000);
  for($y = $year; $y >= 2022; $y--) {
    $month = date("m", $data[0][0]/1000);
    if ($year == 2022) {
      $endMonth = 8;
    } else {
      $endMonth = 1;
    }
    for($m = $month; $m >= $endMonth; $m--) {
      if ($y == $year and $m == $month) {
        $day = date("d", $data[0][0]/1000) - 1;
      } else {
        $day = date("t", mktime(0, 0, 0, $m, 1, $y));
      }
      for($d = $day; $d >= 1; $d--) {
        $dataBefore = array(mktime(0, 0, 0, $m, $d, $y) *1000, 0);
        $countNewData = array_unshift($data, $dataBefore);
      }
    }
  }
}

$dates = [];
$responses = [];
if (!empty($data)) {
    // Daten mit einer For-Schleife ausgeben
    foreach ($data as $datapoint) {
    	
    	// wie lautet das 1. gefundene Datum für den SO?
    	
    	// if (Datum < $dStartDate) 
    	// --> ARRAY mit Datumswerten befüllen
    	// --> response array mit 0 füllen
    
        $dates[] = date("d.m.Y", $datapoint[0] / 1000);
        
        $responses[] = $datapoint[1];
    }
} else {
    echo "Keine Datensätze gefunden";
}
?>

<canvas id="myChart" width="400" height="200"></canvas>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
<script>
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($dates); ?>,
        datasets: [{
            label: 'Response Value',
            data: <?php echo json_encode($responses); ?>,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});
</script>

<?php
//$location_id = 1;
$name = "sum-patient-mii";
$data = getData($location_id, $name, $hDB);
if (!empty($data)) {
    // Daten mit einer For-Schleife ausgeben
    foreach ($data as $datapoint) {
      //  echo "Datetime: " . $datapoint[0] . ", Response: " . $datapoint[1]."<br>";
    }
} else {
    echo "Keine Datensätze gefunden";
}

?>                            

                        </div>
                    </div>

                    <div class="row removable">


                        <div class="col-12 col-lg-12">

                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Erschlossene Jahre<br></h5>
                                    <h6 class="card-subtitle text-muted">Anzahl Patientendaten pro Jahr<br></h6>
                                </div>
                                <div class="card-body">

                                    
								<div style="width: 100%;">
									<canvas id="myChartYearly"></canvas>
								</div>
	<script>
		var chartDataYearly = <?php echo getYearlyChartData($_GET['lid'], $hDB); ?>;
		var ctx = document.getElementById('myChartYearly').getContext('2d');
		var myChartYearly = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: chartDataYearly.map(function(item) { return item[0]; }),
				datasets: [{
					label: 'Werte',
					data: chartDataYearly.map(function(item) { return item[1]; }),
					backgroundColor: 'rgba(54, 162, 235, 0.2)',
					borderColor: 'rgba(54, 162, 235, 1)',
					borderWidth: 1
				}]
			},
			options: {
				scales: {
					y: {
						beginAtZero: true
					}
				}
			}
		});
	</script>                                    
                                    
                               
                                    
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <!-- Footer -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row text-muted">
                        <div class="col-6 text-start">
                            <p class="mb-0">
                                FDPG <a class="text-muted" href="https://adminkit.io/" target="_blank"><strong>DASH-X</strong></a>
                            </p>
                        </div>
                        <div class="col-6 text-end">
                            <ul class="list-inline">
                                <li class="list-inline-item"> <a class="text-muted" href="#" target="_blank">Slack</a> </li>
                                <li class="list-inline-item"> <a class="text-muted" href="https://support.tmf-ev.de/index.php?a=add&category=1" target="_blank">Support</a> </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="https://demo-basic.adminkit.io/js/app.js"></script>
    <script>
        if(document.getElementById("chartjs-dashboard-line")){
        var ctx = document.getElementById("chartjs-dashboard-line").getContext("2d");
        		var gradient = ctx.createLinearGradient(0, 0, 0, 225);
        		gradient.addColorStop(0, "rgba(215, 227, 244, 1)");
        		gradient.addColorStop(1, "rgba(215, 227, 244, 0)");
        		// Line chart
        		new Chart(document.getElementById("chartjs-dashboard-line"), {
        			type: "line",
        			data: {
        				labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        				datasets: [{
        					label: "Sales ($)",
        					fill: true,
        					backgroundColor: gradient,
        					borderColor: window.theme.primary,
        					data: [
        						2115,
        						1562,
        						1584,
        						1892,
        						1587,
        						1923,
        						2566,
        						2448,
        						2805,
        						3438,
        						2917,
        						3327
        					]
        				}]
        			},
        			options: {
        				maintainAspectRatio: false,
        				legend: {
        					display: false
        				},
        				tooltips: {
        					intersect: false
        				},
        				hover: {
        					intersect: true
        				},
        				plugins: {
        					filler: {
        						propagate: false
        					}
        				},
        				scales: {
        					xAxes: [{
        						reverse: true,
        						gridLines: {
        							color: "rgba(0,0,0,0.0)"
        						}
        					}],
        					yAxes: [{
        						ticks: {
        							stepSize: 1000
        						},
        						display: true,
        						borderDash: [3, 3],
        						gridLines: {
        							color: "rgba(0,0,0,0.0)"
        						}
        					}]
        				}
        			}
        		});
          }
        
        
        if(document.getElementById("chartjs-dashboard-pie")){
          new Chart(document.getElementById("chartjs-dashboard-pie"), {
        			type: "pie",
        			data: {
        				labels: ["Chrome", "Firefox", "IE"],
        				datasets: [{
        					data: [4306, 3801, 1689],
        					backgroundColor: [
        						window.theme.primary,
        						window.theme.warning,
        						window.theme.danger
        					],
        					borderWidth: 5
        				}]
        			},
        			options: {
        				responsive: !window.MSInputMethodContext,
        				maintainAspectRatio: false,
        				legend: {
        					display: false
        				},
        				cutoutPercentage: 75
        			}
        		});
          }
        
        
          if(document.getElementById("chartjs-dashboard-bar")){
        		// Bar chart
        		new Chart(document.getElementById("chartjs-dashboard-bar"), {
        			type: "bar",
        			data: {
        				labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        				datasets: [{
        					label: "This year",
        					backgroundColor: window.theme.primary,
        					borderColor: window.theme.primary,
        					hoverBackgroundColor: window.theme.primary,
        					hoverBorderColor: window.theme.primary,
        					data: [54, 67, 41, 55, 62, 45, 55, 73, 60, 76, 48, 79],
        					barPercentage: .75,
        					categoryPercentage: .5
        				}]
        			},
        			options: {
        				maintainAspectRatio: false,
        				legend: {
        					display: false
        				},
        				scales: {
        					yAxes: [{
        						gridLines: {
        							display: false
        						},
        						stacked: false,
        						ticks: {
        							stepSize: 20
        						}
        					}],
        					xAxes: [{
        						stacked: false,
        						gridLines: {
        							color: "transparent"
        						}
        					}]
        				}
        			}
        		});
          }
        
        
          if(document.getElementById("chartjs-dashboard-bar-dark")){
        			// Bar chart
        			new Chart(document.getElementById("chartjs-dashboard-bar-dark"), {
        				type: "bar",
        				data: {
        					labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        					datasets: [{
        						label: "This year",
        						backgroundColor: window.theme.primary,
        						borderColor: window.theme.primary,
        						hoverBackgroundColor: window.theme.primary,
        						hoverBorderColor: window.theme.primary,
        						data: [54, 67, 41, 55, 62, 45, 55, 73, 60, 76, 48, 79],
        						barPercentage: .75,
        						categoryPercentage: .5
        					}]
        				},
        				options: {
        					maintainAspectRatio: false,
        					legend: {
        						display: false
        					},
        					scales: {
        						yAxes: [{
        							gridLines: {
        								display: false
        							},
        							stacked: false,
        							ticks: {
        								stepSize: 20
        							}
        						}],
        						xAxes: [{
        							stacked: false,
        							gridLines: {
        								color: "transparent"
        							}
        						}]
        					}
        				}
        			});
          }
        
        
          if(document.getElementById("chartjs-line")){
            new Chart(document.getElementById("chartjs-line"), {
        				type: "line",
        				data: {
        					labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        					datasets: [{
        						label: "Sales ($)",
        						fill: true,
        						backgroundColor: "transparent",
        						borderColor: window.theme.primary,
        						data: [2115, 1562, 1584, 1892, 1487, 2223, 2966, 2448, 2905, 3838, 2917, 3327]
        					}, {
        						label: "Orders",
        						fill: true,
        						backgroundColor: "transparent",
        						borderColor: "#adb5bd",
        						borderDash: [4, 4],
        						data: [958, 724, 629, 883, 915, 1214, 1476, 1212, 1554, 2128, 1466, 1827]
        					}]
        				},
        				options: {
        					maintainAspectRatio: false,
        					legend: {
        						display: false
        					},
        					tooltips: {
        						intersect: false
        					},
        					hover: {
        						intersect: true
        					},
        					plugins: {
        						filler: {
        							propagate: false
        						}
        					},
        					scales: {
        						xAxes: [{
        							reverse: true,
        							gridLines: {
        								color: "rgba(0,0,0,0.05)"
        							}
        						}],
        						yAxes: [{
        							ticks: {
        								stepSize: 500
        							},
        							display: true,
        							borderDash: [5, 5],
        							gridLines: {
        								color: "rgba(0,0,0,0)",
        								fontColor: "#fff"
        							}
        						}]
        					}
        				}
        			});
            }
        
            if(document.getElementById("chartjs-bar")){
              new Chart(document.getElementById("chartjs-bar"), {
              				type: "bar",
              				data: {
              					labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
              					datasets: [{
              						label: "Last year",
              						backgroundColor: window.theme.primary,
              						borderColor: window.theme.primary,
              						hoverBackgroundColor: window.theme.primary,
              						hoverBorderColor: window.theme.primary,
              						data: [54, 67, 41, 55, 62, 45, 55, 73, 60, 76, 48, 79],
              						barPercentage: .75,
              						categoryPercentage: .5
              					}, {
              						label: "This year",
              						backgroundColor: "#dee2e6",
              						borderColor: "#dee2e6",
              						hoverBackgroundColor: "#dee2e6",
              						hoverBorderColor: "#dee2e6",
              						data: [69, 66, 24, 48, 52, 51, 44, 53, 62, 79, 51, 68],
              						barPercentage: .75,
              						categoryPercentage: .5
              					}]
              				},
              				options: {
              					maintainAspectRatio: false,
              					legend: {
              						display: false
              					},
              					scales: {
              						yAxes: [{
              							gridLines: {
              								display: false
              							},
              							stacked: false,
              							ticks: {
              								stepSize: 20
              							}
              						}],
              						xAxes: [{
              							stacked: false,
              							gridLines: {
              								color: "transparent"
              							}
              						}]
              					}
              				}
              			});
                  }
    </script>
    <script src="./assets/js/loopple/loopple.js"></script>
</body>

<?php
}
// show the last-drop-details in separate view
else if(isset($_GET["location_id"]) && isset($_GET["showLastDrop"])) {
?>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>DIZ DASH-X</title>
        <!-- <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script> -->

        <link rel="stylesheet" href="./assets/css/bootstrap.min.css"/>
        <link rel="stylesheet" href="./assets/css/style-dev.css"/>
        <link rel="stylesheet" href="./login_files/style.css">
        <link rel="stylesheet" href="./assets/css/style-csr.css">

        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap5.min.css">

        <script src="./assets/js/jquery.min.js"></script>
        <script src="./assets/js/popper.min.js"></script>
        <!-- <script src="./assets/js/bootstrap.min.js"></script> -->

        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

        <script>
            $(document).ready(function () {
                $('#list_profiles_location_detail').DataTable({
                    scrollX: '400px',
                    scrollCollapse: true
                });
            });
        </script>
    </head>
    <body>

    <?php

    echo showMainMenu();

    ?>

    <div class="card-header">
        <h5 class="card-title">Datentabelle des Standortes <strong><?php
              $query = "SELECT loc_display FROM tmfev114_locations_all WHERE id_connected=" . $_GET["location_id"];
              $result = mysqli_query($hDB, $query);
              $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
              echo $rows[0]["loc_display"];
            ?></strong><br></h5>
    </div>
    <div class="card-body">
        <div class="auth-box" style="overflow-y: auto;">
            <?php
                create_location_drop($_GET["location_id"], $hDB);
            ?>
        </div>
    </div>
    </body>



<?php
} else {
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>DIZ DASH-X</title>
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.statically.io/gh/Loopple/loopple-public-assets/main/asteria-dashboard/css/nucleo-icons.css">
    <link rel="stylesheet" href="./assets-x2/css/theme.css">
    <link rel="stylesheet" href="./assets-x2/css/loopple/loopple.css">

    <script>
    
	function startTimer(timestamp, targetElement) {
	  setInterval(function() {
	    const now = new Date();
	    const timeDiff = now - new Date(timestamp);
	    const days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
	    const hours = Math.floor((timeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
	    const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
	    const seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);
    
	    const dayString = days === 1 ? 'day' : 'days';

		const secondsString = seconds < 10 ? `0${seconds}` : seconds;

	targetElement.innerHTML = `
	<div style="display: flex; align-items: center; flex-wrap: wrap; width: 100%; font-size: 11px;">
	  <div style="width: 50%; flex-shrink: 0; white-space: nowrap; flex-wrap: wrap; font-weight: bold;">LAST DELIVERY:</div>
	  <div style="width: 50%; white-space: nowrap;">${days} ${dayString}, ${hours}:${minutes}:${secondsString} ago</div>
	</div><!--
	<div style="display: flex; align-items: center; flex-wrap: wrap; width: 100%; font-size: 11px;">
	  <div style="width: 50%; flex-shrink: 0; white-space: nowrap; flex-wrap: wrap; font-weight: bold;">TIMESTAMP:</div>
	  <div style="width: 50%; white-space: nowrap;">${timestamp}</div
	</div>>-->
	`;

  }, 1000);
}

	</script>
	
	<style>
	.gray-background {
	  background-color: #f1f1f1;
	}

	.orange-background {
	  background-color: #f5b041;
	}

	.lightblue-background {
	  background-color: #87cefa;
	}

	.lightgreen-background {
	  background-color: #90ee90;
	}

	.green-background {
	  background-color: #2ecc71;
	}
	</style>
</head>
<body class="null">

    <div class="main-content" id="panel">
<?php

echo showMainMenu();

?>  
        <div class="container-fluid pt-3">
           
            
<?php
if (isset($_GET['tab'])) {


$query = "SELECT p.fhir_profile, l.name_short, MAX(s.date_delivery) AS max_date_delivery, s.response 
          FROM tmfev114_fhirprofiles p 
          JOIN tmfev114_sync_details s ON p.fhir_profile = s.name 
          JOIN tmfev114_locations l ON l.id = s.location_id 
          WHERE s.name IN (
              'sum-patient-mii',
              'sum-patient-pseudonymisiert-mii',
              'sum-researchsubject-mii',
              'sum-todesursache-mii',
              'sum-vitalstatus-mii',
              'sum-encounter-abteilung-mii',
              'sum-encounter-einrichtung-mii',
              'sum-encounter-versorgung-mii',
              'sum-condition-mii',
              'sum-diagnosticreport-mii',
              'sum-observation-lab-mii',
              'sum-servicerequest-mii',
              'sum-specimen-all',
              'sum-procedure-mii',
              'sum-medication-administration-mii',
              'sum-medication-statement-mii',
              'sum-consent-mii',
              'sum-specimen-mii-biobank'
          ) AND s.date_delivery >= NOW() - INTERVAL 2 DAY AND s.date_delivery <= NOW() - INTERVAL 1 DAY
          GROUP BY p.fhir_profile, l.name_short";

$result = mysqli_query($hDB, $query);

$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

echo "<table id='sort-table' style='style: margin-top: 40px; margin-bottom: 40px;'><thead><tr><th class='sort' data-sort='0'>FHIR Profile</th><th class='sort' data-sort='Summe'>Summe</th>";

// header
$locations = array();
foreach ($rows as $row) {
    if (!in_array($row['name_short'], $locations)) {
        $locations[] = $row['name_short'];
        echo "<th class='sort' data-sort='{$row['name_short']}'>{$row['name_short']}</th>";
    }
}

echo "</tr></thead><tbody>";

$fhir_profiles = array_unique(array_column($rows, 'fhir_profile'));


foreach ($fhir_profiles as $fhir_profile) {
  
  $echovariable1='';
  $echovariable2='';
  $echovariable3='';
    $echovariable1="<tr><td>{$fhir_profile}</td>";
    $cell_summe=0;
    foreach ($locations as $location) {
        $cell_data = '';
        foreach ($rows as $row) {
            if ($row['fhir_profile'] === $fhir_profile && $row['name_short'] === $location) {
                $cell_data = $row['response'];
                break;
            }
        }
        $cell_summe+=$cell_data;
        $background_class = '';
        if ($cell_data == 0) {
            $background_class = 'gray-background';
        } elseif ($cell_data > 0 && $cell_data <= 100) {
            $background_class = 'orange-background';
        } elseif ($cell_data > 100 && $cell_data <= 10000) {
            $background_class = 'lightblue-background';
        } elseif ($cell_data > 10000 && $cell_data <= 1000000) {
            $background_class = 'lightgreen-background';
        } elseif ($cell_data > 1000000) {
            $background_class = 'green-background';
        }
        $echovariable2=$echovariable2."<td data-sort='{$location}' class='{$background_class}'>{$cell_data}</td>";
    }

 
    $echovariable3="<td data-sort='' class='gray-background'>{$cell_summe}</td>";
    echo $echovariable1.$echovariable3.$echovariable2."</tr>";
}

echo "</tbody></table>";

} else {
?>
            <div class="row removable">
<?php

$name_short_array = getNameShortArray($trend_start_date, date("Y-m-d"), $iTrendDays, $hDB);

for ($i = 0; $i < count($name_short_array); $i++) {
  echo $name_short_array[$i];
}

?>                  
                
            </div>

<?php
} // end if - tab abfrage
?>            
          
        </div>
        <!-- Footer -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row text-muted">
                        <div class="col-6 text-start">
                            <p class="mb-0">
                                FDPG <a class="text-muted" href="https://adminkit.io/" target="_blank"><strong>DASH-X</strong></a>
                            </p>
                        </div>
                        <div class="col-6 text-end">
                            <ul class="list-inline">
                                <li class="list-inline-item"> <a class="text-muted" href="#" target="_blank">Slack</a> </li>
                                <li class="list-inline-item"> <a class="text-muted" href="https://support.tmf-ev.de/index.php?a=add&category=1" target="_blank">Support</a> </li>
                            </ul>
                        </div>
                    </div> 
                </div>
            </footer>
    </div>
<script>
const sortTable = document.getElementById('sort-table');

function sortTableByLocation(table, column, asc) {
  var rows = Array.from(table.querySelectorAll('tbody tr'));
  rows.sort(function(a, b) {
    var aLocation = a.querySelector('td[data-sort="' + column + '"]').textContent.trim();
    var bLocation = b.querySelector('td[data-sort="' + column + '"]').textContent.trim();
    if (asc) {
      return aLocation.localeCompare(bLocation);
    } else {
      return bLocation.localeCompare(aLocation);
    }
  });
  for (var i = 0; i < rows.length; i++) {
    table.querySelector('tbody').appendChild(rows[i]);
  }
}

var table = document.getElementById('sort-table');
var ths = table.querySelectorAll('thead th');
ths.forEach(function(th) {
  th.addEventListener('click', function() {
    var column = this.getAttribute('data-sort');
    var asc = this.classList.contains('active') ? !this.classList.contains('asc') : true;
    ths.forEach(function(other) {
      if (other !== th) {
        other.classList.remove('active');
        other.classList.remove('asc');
        other.classList.remove('desc');
      }
    });
    if (asc) {
      sortTableByLocation(table, column, true);
      this.classList.remove('desc');
      this.classList.add('asc');
    } else {
      sortTableByLocation(table, column, false);
      this.classList.remove('asc');
      this.classList.add('desc');
    }
    this.classList.add('active');
  });
});

</script>
    <script src="https://loopple.s3.eu-west-3.amazonaws.com/asteria-dashboard/js/core/bootstrap.min.js"></script>
    <script src="https://rawcdn.githack.com/Loopple/loopple-public-assets/f5029eba0dafd952ecdbf8fbbdd0aa9ae0d0abc1/asteria-dashboard/js/plugins/chartjs.min.js"></script>
    <script src="https://rawcdn.githack.com/Loopple/loopple-public-assets/1f72f0b962eae981381ec8ccac9cd18d65d3bbe6/asteria-dashboard/js/plugins/apexcharts.js"></script>
    <script src="https://rawcdn.githack.com/Loopple/loopple-public-assets/f5029eba0dafd952ecdbf8fbbdd0aa9ae0d0abc1/asteria-dashboard/js/plugins/swiper.min.js"></script>
    <script src="https://loopple.s3.eu-west-3.amazonaws.com/asteria-dashboard/js/asteria-dashboard.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function(event) {
        
        if(document.querySelector('.chart-pie')){
        var ctx = document.querySelectorAll(".chart-pie");
        
          ctx.forEach(function(ctx) {
            ctx.innerHTML = "";
        
            var chartPie = new ApexCharts(ctx, {
              chart: {
                 width: 380,
                 type: 'donut',
               },
               dataLabels: {
                 enabled: false
               },
               plotOptions: {
                  pie: {
                    customScale: 1,
                    expandOnClick: false,
                    donut: {
                      size: "80%",
                    }
                  },
                },
              legend: {
                  position: "right",
                  verticalAlign: "center",
                  containerMargin: {
                    left: 35,
                    right: 60
                  }
                },
               series: [66, 55, 13, 33],
               labels: ['Asia', 'USA', 'China', 'Africa'],
               colors: ['#00ab5599', '#00ab55', '#00ab5535', '#00ab5550'],
               donut: {
                 size: "100%"
               },
               responsive: [
                  {
                     breakpoint: 1550,
                     options: {
                       chart: {
                          width: 340,
                       },
                       legend: {
                           position: "bottom",
                           verticalAlign: "bottom",
                           containerMargin: {
                             left: 'auto',
                             right: 'auto'
                           }
                         },
                     }
                  },
                  {
                     breakpoint: 1450,
                     options: {
                       chart: {
                          width: 300,
                       },
                     }
                  }
                ]
            });
            chartPie.render();
          });
        }
        
        if(document.querySelector('.chart-line')){
        var ctx2 = document.querySelectorAll(".chart-line");
          ctx2.forEach(function(ctx2) {
            new Chart(ctx2, {
                type: "line",
                data: {
                  labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                  datasets: [{
                      label: "Black Friday",
                      tension: 0.4,
                      borderWidth: 0,
                      pointRadius: 0,
                      borderColor: "#00ab55",
                      borderWidth: 3,
                      backgroundColor: "transparent",
                      data: [20, 60, 20, 50, 90, 220, 440, 380, 500],
                      maxBarThickness: 6
                    },
                    {
                      label: "Autumn Sale",
                      tension: 0.4,
                      borderWidth: 0,
                      pointRadius: 0,
                      borderColor: "#212b36",
                      borderWidth: 3,
                      backgroundColor: "transparent",
                      data: [30, 90, 40, 140, 290, 290, 240, 270, 230],
                      maxBarThickness: 6
                    },
                  ],
                },
                options: {
                  responsive: true,
                  maintainAspectRatio: false,
                  legend: {
                    display: false,
                  },
                  tooltips: {
                    enabled: true,
                    mode: "index",
                    intersect: false,
                  },
                  scales: {
                    yAxes: [{
                      gridLines: {
                        borderDash: [2],
                        borderDashOffset: [2],
                        color: '#dee2e6',
                        zeroLineColor: '#dee2e6',
                        zeroLineWidth: 1,
                        zeroLineBorderDash: [2],
                        drawBorder: false,
                      },
                      ticks: {
                        suggestedMin: 0,
                        suggestedMax: 500,
                        beginAtZero: true,
                        padding: 10,
                        fontSize: 11,
                        fontColor: '#adb5bd',
                        lineHeight: 3,
                        fontStyle: 'normal',
                        fontFamily: "Public Sans",
                      },
                    }, ],
                    xAxes: [{
                      gridLines: {
                        zeroLineColor: 'rgba(0,0,0,0)',
                        display: false,
                      },
                      ticks: {
                        padding: 10,
                        fontSize: 11,
                        fontColor: '#adb5bd',
                        lineHeight: 3,
                        fontStyle: 'normal',
                        fontFamily: "Public Sans",
                      },
                    }, ],
                  },
                },
              });
          });
        
        }
        
        if(document.querySelector(".chart-line-1")){
        var ctx1 = document.querySelectorAll('.chart-line-1');
        
        ctx1.forEach(function(ctx1) {
            var gradientStroke1 = ctx1.getContext("2d").createLinearGradient(0, 230, 0, 50);
        
            gradientStroke1.addColorStop(1, 'rgba(7,43,174,0.4)');
            gradientStroke1.addColorStop(0, 'rgba(7,43,174,0.4)'); //purple colors
        
        
              new Chart(ctx1, {
                type: "line",
                data: {
                  labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                  datasets: [{
                    label: "Visitors",
                    tension: 0.5,
                    borderWidth: 0,
                    pointRadius: 0,
                    borderColor: "#072bae",
                    borderWidth: 4,
                    backgroundColor: gradientStroke1,
                    data: [50, 70, 60, 60, 80, 65, 90, 80, 100],
                    maxBarThickness: 6,
                    fill: true
                  }],
                },
                options: {
                  responsive: true,
                  maintainAspectRatio: true,
                  legend: {
                    display: false,
                  },
                  scales: {
                    yAxes: [{
                      gridLines: {
                        zeroLineColor: 'rgba(0,0,0,0)',
                        display: false
                      },
                      ticks: {
                        display: false,
                      },
                    }, ],
                    xAxes: [{
                      gridLines: {
                        zeroLineColor: 'rgba(0,0,0,0)',
                        display: false,
                      },
                      ticks: {
                        display: false
                      },
                    }, ],
                  },
                },
              });
          });
        }
        
        
        if(document.querySelector(".chart-line-2")){ 
        
        var ctx2 = document.querySelectorAll('.chart-line-2');
        
          ctx2.forEach(function(ctx2) {
            var gradientStroke2 = ctx2.getContext("2d").createLinearGradient(0, 230, 0, 50);
        
            gradientStroke2.addColorStop(1, 'rgba(31,143,31,0.4)');
            gradientStroke2.addColorStop(0, 'rgba(31,143,31,0.4)'); //purple colors
        
            new Chart(ctx2, {
              type: "line",
              data: {
                labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                  label: "Income",
                  tension: 0.5,
                  borderWidth: 0,
                  pointRadius: 0,
                  borderColor: "#1f8f14",
                  borderWidth: 4,
                  backgroundColor: gradientStroke2,
                  data: [60, 80, 75, 90, 67, 100, 90, 110, 120],
                  maxBarThickness: 6,
                  fill: true
                }],
              },
              options: {
                responsive: true,
                maintainAspectRatio: true,
                legend: {
                  display: false,
                },
                scales: {
                  yAxes: [{
                    gridLines: {
                      zeroLineColor: 'rgba(0,0,0,0)',
                      display: false
                    },
                    ticks: {
                      display: false,
                    },
                  }, ],
                  xAxes: [{
                    gridLines: {
                      zeroLineColor: 'rgba(0,0,0,0)',
                      display: false,
                    },
                    ticks: {
                      display: false
                    },
                  }, ],
                },
              },
            });
          });
        }
        
        if(document.querySelector(".chart-bar-stacked")){ 
        
        var ctx3 = document.querySelectorAll('.chart-bar-stacked');
        
          ctx3.forEach(function(ctx3) {
        const data = {
          labels: [
            "2015",
            "2016",
            "2017",
            "2018",
            "2019",
            "2020"
          ],
          datasets: [
            {
              label: "Long",
              backgroundColor: "#0dcaf0",
        
              data: [
                9000,
                5000,
                5240,
                3520,
                2510,
                3652
              ]
            },
            {
              label: "Short",
              backgroundColor: "#5e72e4",
              data: [
                3000,
                4000,
                6000,
                3500,
                3600,
                8060
              ]
            },
            {
              label: "Spreading",
              backgroundColor: "#20c997",
              data: [
                6000,
                7200,
                6500,
                4600,
                3600,
                9200
              ]
            }
          ]
        };
        
        const options = {
          scales: {
            yAxes: [
               { 
                   stacked: true,  
                   ticks: { fontSize: 14, lineHeight: 3, fontColor: "#adb5bd" }, 
                   gridLines: { display: false }
             
                }],
            xAxes: [
              {
                stacked: true,
                ticks: {  fontSize: 14, lineHeight: 3, fontColor: "#adb5bd" }
              }
            ]
          }
        };
        
        const chart = new Chart(ctx3, {
          // The type of chart we want to create
          type: "bar",
          // The data for our dataset
          data: data,
          // Configuration options go here
          options: options
        }); 
        
        });
        }
        
        
        if(document.querySelector(".chart-sales")){
        var ctx4 = document.querySelectorAll('.chart-sales');
        
        ctx4.forEach(function(ctx4) {
         new Chart(ctx4, {
                                    type: "bar",
                                    data: {
                                      labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                                      datasets: [{
                                        label: "Sales",
                                        tension: 0.4,
                                        borderWidth: 0,
                                        pointRadius: 0,
                                        backgroundColor: "#02a352",
                                        data: [450, 200, 100, 220, 500, 100, 400, 230, 500],
                                        maxBarThickness: 6
                                      }, ],
                                    },
                                    options: {
                                      responsive: true,
                                      maintainAspectRatio: false,
                                      legend: {
                                        display: false,
                                      },
                                      tooltips: {
                                        enabled: true,
                                        mode: "index",
                                        intersect: false,
                                      },
                                      scales: {
                                        yAxes: [{
                                          gridLines: {
                                             
                                            display: false,
                                          },
                                          ticks: {
                                            suggestedMin: 0,
                                            suggestedMax: 500,
                                            beginAtZero: true,
                                            padding: 0,
                                            fontSize: 14,
                                            lineHeight: 3,
                                            fontColor: "#adb5bd"
                                          },
                                        }, ],
                                        xAxes: [{
         gridLines: {
                        offsetGridLines: false
                    },
                                          ticks: {
                                            padding: 20,
                                            fontColor: "#adb5bd"
                                          },
                                        }, ],
                                      },
                                    },
                                  }); 
        });
        
        }
        
        
        if(document.querySelector(".chart-interest")){
        var ctx5 = document.querySelectorAll('.chart-interest');
        
        ctx5.forEach(function(ctx5) {
        new Chart(ctx5, {
          type: 'pie',
          data: {
            labels: ['OK', 'WARNING', 'CRITICAL', 'UNKNOWN'],
            datasets: [{
              label: '# of Tomatoes',
              data: [12, 19, 3, 5],
              backgroundColor: [
                'rgba(32, 201, 151, 0.5)',
                'rgba(111, 66, 193, 0.5)',
                'rgba(13, 202, 240, 0.5)',
                'rgba(251, 207, 51, 0.5)'
              ],
              borderColor: [
                'rgba(32, 201, 151, 0.5)',
                'rgba(111, 66, 193, 0.5)',
                'rgba(13, 202, 240, 0.5)',
                 'rgba(251, 207, 51, 0.5)'
              ],
              borderWidth: 1
            }]
          },
          options: {
           	//cutoutPercentage: 70,
            responsive: true,
        legend: {
                           position: "bottom",
                         },
        
          }
        }); 
        });
        }
        
        
        if(document.querySelector(".chart-social")){
        var ctx6 = document.querySelectorAll('.chart-social');
        
        ctx6.forEach(function(ctx6) {
        
        var data = {
            datasets: [{
                data: [
                    10,
                    60,
                    30,
                    20,
                    50
                ],
                backgroundColor: [
                    "#000000",
                    "#3B5998",
                    "#a6b1b7",
                    "#1da1f2",
                    "#bd081c"
                ],
                label: 'My dataset', // for legend
            }],
            labels: [
                "Blog",
                "Facebook",
                "Instagram",
                "Twitter",
                "Pinterest"
            ],
        };
        new Chart(ctx6, {
            data: data,
            type: 'polarArea',
            options: {
              legend: {
                display: false,
              },
              scale: {
                display: true
              }
            }
        }); 
        });
        }
        
        
        if(document.querySelector("#swiperHeader")){
          popupGallery = new Swiper('#swiperHeader', {
        
              grabCursor: true,
              keyboard: {
                enabled: true,
              },
              navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
              },
              breakpoints: {
                640: {
                  slidesPerView: 1,
                  spaceBetween: 20,
                },
                768: {
                  slidesPerView: 1.8,
                  spaceBetween: 20,
                },
                1024: {
                  slidesPerView: 4.5,
                  spaceBetween: 20,
                },
              }
            }); 
        }
        
        });
    </script>
    <script src="./assets-x2/js/loopple/loopple.js"></script>
</body>
<?php
}
?>


</html>