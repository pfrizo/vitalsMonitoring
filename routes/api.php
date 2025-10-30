<?php

// routes/api.php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VitalSignController;

Route::post('/vitals', [VitalSignController::class, 'store']);