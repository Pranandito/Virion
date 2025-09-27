<?php

use App\Http\Controllers\API\V1\DashboardController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');


Route::redirect('/', '/login');

Route::get('/beranda', [BerandaController::class, 'create'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/monitoring/{serial_number}', [MonitoringController::class, 'create'])->middleware(['auth', 'verified'])->name('monitoring');

Route::get('/monitoring/Humida/{serial_number}', [MonitoringController::class, 'create'])->middleware(['auth', 'verified'])->name('monitoring.Humida');
Route::get('/monitoring/Siram/{serial_number}', [MonitoringController::class, 'create'])->middleware(['auth', 'verified'])->name('monitoring.Siram');
Route::get('/monitoring/Feed/{serial_number}', [MonitoringController::class, 'createFeed'])->middleware(['auth', 'verified'])->name('monitoring.Feed');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/reset-pass', function () {
    $user = User::where('email', 'didi@gmail.com')->first();
    $user->password = Hash::make('didididi');
    $user->save();

    return "Password berhasil direset!";
});

require __DIR__ . '/auth.php';
