<?php

use App\Http\Controllers\API\V1\UserController;
use App\Http\Controllers\API\V1\ConfigController;
use App\Http\Controllers\API\V1\DashboardController;
use App\Http\Controllers\API\V1\DeviceController;
use App\Http\Controllers\API\V1\ESPController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('V1')->group(function () {
    Route::get('userProfile/{email}', [UserController::class, 'userProfile']);
    Route::post('createUser', [UserController::class, 'createUser']);

    Route::post('registerDevice', [DeviceController::class, 'registerDevice']);
    Route::patch('editDevice', [DeviceController::class, 'editDevice']);
    Route::post('tess', [DeviceController::class, 'tss']);

    Route::get('getConfig/{serial_number}', [DashboardController::class, 'getConfig']);
    Route::get('chartData/{virdi_type}/{device_id}/{periode?}', [DashboardController::class, 'chartData'])->name('chart.get');
    Route::get('avgData/{virdi_type}/{device_id}', [DashboardController::class, 'avgData']);
    Route::get('dataStream/{virdi_type}/{device_id}', [DashboardController::class, 'dataStream']);

    Route::get('fetchSchedule/{device_id}', [DashboardController::class, 'fetch_schedule']);
    Route::delete('deleteSchedule/{id}', [DashboardController::class, 'delete_schedule']);

    Route::get('ts/{device_id}', [DashboardController::class, 'feed']);
    Route::get('tsss/{device_id}', [DashboardController::class, 'update_weekly']);


    Route::get('tes', [ESPController::class, 'tes']);
    Route::post('Esp/Upload/{virdi_type}/{device_id}', [ESPController::class, 'store']);
    Route::post('Esp/Upload/Recover/{virdi_type}/{device_id}', [ESPController::class, 'storeRecover']);
    Route::get('Esp/getConfig/{virdi_type}/{device_id}', [ESPController::class, 'getConfig']);
    Route::get('Esp/getSchedule/{device_id}', [ESPController::class, 'getSchedule']);
    Route::post('Esp/feedSuccess/{mode}/{device_id}', [ESPController::class, 'feedSuccess']);
});
