<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\UserController;

Route::get(
    '/',

    function () {
        return view('welcome');
    }
);

Route::prefix('V1')->group(function () {
    Route::get('semua', [UserController::class, 'semua']);
});
