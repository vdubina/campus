<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CmsController;
use App\Http\Controllers\Api\HomeCmsController;
use App\Http\Controllers\Api\StudentDashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function (): void {
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function (): void {
        Route::get('/me', [AuthController::class, 'me']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::middleware('auth:api')->prefix('student')->group(function (): void {
    Route::get('/dashboard', StudentDashboardController::class);
});

Route::get('/cms/pages/{slug}', CmsController::class);
Route::get('/cms/home', HomeCmsController::class);
