<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IncidentController;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('jwt');
    Route::post('logout', [AuthController::class, 'logout'])->middleware('jwt');
    Route::get('me', [AuthController::class, 'me'])->middleware('jwt');
});

Route::middleware('jwt')->group(function () {
    Route::apiResource('incidents', IncidentController::class);
});

