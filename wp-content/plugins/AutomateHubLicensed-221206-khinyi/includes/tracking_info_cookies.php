<?php

$posted_data['entryUrl']         = (isset($_COOKIE['_entryUrl']) ? sanitize_url($_COOKIE['_entryUrl']) : "");
$posted_data['referrerUrl']      = (isset($_COOKIE['_referringURL']) ? sanitize_url($_COOKIE['_referringURL']) : "");
$posted_data['refAffiliateCode'] = (isset($_COOKIE['refAffiliateCode']) ? sanitize_text_field( $_COOKIE['refAffiliateCode']) : "");
