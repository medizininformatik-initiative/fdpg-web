<?php

use BitApps\Assist\Core\Http\Router\Route;
use BitApps\Assist\HTTP\Controllers\ChannelController;
use BitApps\Assist\HTTP\Controllers\DownloadController;
use BitApps\Assist\HTTP\Controllers\ResponseController;
use BitApps\Assist\HTTP\Controllers\WidgetChannelController;
use BitApps\Assist\HTTP\Controllers\WidgetController;

if (!\defined('ABSPATH')) {
    exit;
}

// if (!headers_sent()) {
//     header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
//     header('Access-Control-Allow-Credentials: true');
//     header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
//     header('Access-Control-Allow-Origin: *');
//     if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
//         status_header(200);
//         exit;
//     }
// }

// noAuth()->

Route::group(function () {
    Route::get('widgets', [WidgetController::class, 'index']);
    Route::get('widgets/{widget}', [WidgetController::class, 'show']);
    Route::post('widgets', [WidgetController::class, 'store']);
    Route::post('widgets/{widget}/update', [WidgetController::class, 'update']);
    Route::post('widgets/{widget}/destroy', [WidgetController::class, 'destroy']);
    Route::post('widgets/{widget}/changeStatus', [WidgetController::class, 'changeStatus']);
    Route::get('copyWidget/{widget}', [WidgetController::class, 'copy']);

    Route::get('channels', [ChannelController::class, 'index']);
    Route::get('channels/{channel}', [ChannelController::class, 'show']);

    Route::get('widgets/{widgetId}/widgetChannels', [WidgetChannelController::class, 'index']);
    Route::get('widgetChannels/{widgetChannel}', [WidgetChannelController::class, 'show']);
    Route::post('widgetChannels', [WidgetChannelController::class, 'store']);
    Route::post('widgetChannels/{widgetChannel}/update', [WidgetChannelController::class, 'update']);
    Route::post('widgetChannels/{widgetChannel}/destroy', [WidgetChannelController::class, 'destroy']);
    Route::post('widgetChannels/updateSequence', [WidgetChannelController::class, 'updateSequence']);
    Route::get('copyWidgetChannel/{widgetChannel}', [WidgetChannelController::class, 'copy']);

    Route::get('responses/{widgetChannelId}/othersData', [ResponseController::class, 'othersData']);
    Route::get('responses/{widgetChannelId}/{page}/{limit}', [ResponseController::class, 'index']);
    Route::post('responses', [ResponseController::class, 'store']);
    Route::post('responsesDelete', [ResponseController::class, 'destroy']);

    Route::get('downloadResponseFile', [DownloadController::class, 'downloadResponseFile']);
})->middleware('nonce');
