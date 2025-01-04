<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function() {
    Route::prefix('auth')->group(function() {
        Route::post('login', [AuthController::class, 'login']);
        Route::middleware('auth:api')->post('me', [AuthController::class, 'me']);
    });

    Route::post('user', [UserController::class, 'store']);

    Route::middleware('auth:api')->group(function() {
        Route::apiResource('music', MusicController::class);
    });
});
