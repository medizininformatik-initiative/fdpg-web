<?php

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// live-database
const DB_SERVER	= "5.35.240.133";
const DB_USER	= "tmfev114_web";
const DB_PASS	= "114Fdpg!";
const DB_NAME	= "tmfev114_web";
const DB_PREFIX	= "tmfev114_";

// dev-database
const DB_SERVER_dev	= "5.35.240.133";
const DB_USER_dev	= "tmfev114_web_dev";
const DB_PASS_dev	= "114Fdpg!";
const DB_NAME_dev	= "tmfev114_web_dev";
const DB_PREFIX_dev	= "tmfev114_";

const EMAIL_SENDER = "pre-register@forschen-fuer-gesundheit.de";
const EMAIL_SENDER_NAME = "forschen-fuer-gesundheit.de";
const EMAIL_PASSWORD = "NS8MgCKUwAW7w5f4SDT8";

const DICKESB_SEED = "5946110485";

const MAINTAIN = 0;

if (MAINTAIN==1) {
	header( 'Location: http://www.forschen-fuer-gesundheit.de/start?m=1');
} // end if

const start_report_date = "2022-08-29 00:00:00";
const end_report_date = null; // use current date if null
?>