<?php

if (!defined('ABSPATH')) {
    exit;
}

use BitCode\FI\Actions\CompanyHub\CompanyHubController;
use BitCode\FI\Core\Util\Route;


Route::post('company_hub_authentication', [CompanyHubController::class, 'authentication']);
Route::post('company_hub_fetch_all_fields', [CompanyHubController::class, 'getAllFields']);
Route::post('company_hub_fetch_all_companies', [CompanyHubController::class, 'getAllCompanies']);