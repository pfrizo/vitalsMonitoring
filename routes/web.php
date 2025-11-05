<?php

use App\Http\Controllers\Api\MonitoringDataController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MonitoringController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
     ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');

    Route::resource('patients', PatientController::class);
    Route::resource('devices', DeviceController::class);    

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/monitoring/latest-data', [MonitoringDataController::class, 'getLatestData'])->name('monitoring.latest_data');
    Route::get('/monitoring/critical-alerts', [MonitoringDataController::class, 'getCriticalAlerts'])->name('monitoring.critical_alerts');
});

require __DIR__.'/auth.php';
