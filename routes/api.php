<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function() {
    Route::prefix('auth')->group(function() {
        Route::post('login', [AuthController::class, 'login'])->name('api.v1.auth.login');
        Route::middleware('auth:api')->post('me', [AuthController::class, 'me'])->name('api.v1.auth.me');
    });

    Route::post('user', [UserController::class, 'store'])->name('api.v1.user.store');

    Route::get('music', [MusicController::class, 'index'])->name('api.v1.music.index');
    Route::get('music/{id}', [MusicController::class, 'show'])->name('api.v1.music.show');

    Route::middleware('auth:api')->group(function() {
        Route::post('music', [MusicController::class, 'store'])->name('api.v1.music.store');
    });
});
