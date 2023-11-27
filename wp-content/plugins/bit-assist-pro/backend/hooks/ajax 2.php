<?php

use BitApps\AssistPro\Core\Http\Router\Route;
use BitApps\AssistPro\HTTP\Controllers\WidgetController;

if (!\defined('ABSPATH')) {
    exit;
}

Route::group(function () {
    Route::post('/widgets/{widget}/update', [WidgetController::class, 'update']);
    Route::post('/widgets/{widgetId}/changeActive', [WidgetController::class, 'changeActive']);
})->middleware('nonce:admin', 'isPro');
