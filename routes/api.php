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
    Route::patch('addOwner', [DeviceController::class, 'addOwner']);
    Route::patch('editDevice', [DeviceController::class, 'editDevice']);
    Route::get('tess', [DeviceController::class, 'statusCheck']);

    Route::patch('editMode', [ConfigController::class, 'editMode']);
    Route::patch('editThreshold', [ConfigController::class, 'editThreshold']);

    Route::get('getConfig/{serial_number}', [DashboardController::class, 'getConfig']);
    Route::get('dashboardData/{virdi_type}/{device_id}/{periode?}', [DashboardController::class, 'dashboardData']);
    Route::get('avgData/{virdi_type}/{device_id}', [DashboardController::class, 'avgData']);
    Route::get('dataStream/{virdi_type}/{device_id}', [DashboardController::class, 'dataStream']);

    Route::get('tes', [ESPController::class, 'tes']);
    Route::post('Esp/Upload/{virdi_type}/{device_id}', [ESPController::class, 'store']);
    Route::get('Esp/getConfig/{virdi_type}/{device_id}', [ESPController::class, 'getConfig']);
});
