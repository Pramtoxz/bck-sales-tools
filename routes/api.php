<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\TargetSalesController;
use App\Http\Controllers\Api\StockController;
use App\Http\Controllers\Api\IndentController;
use App\Http\Controllers\Api\TargetProspekController;
use App\Http\Controllers\Api\ProspekController;
use App\Http\Controllers\Api\ActualSpkController;
use App\Http\Controllers\Api\PerformanceController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ActualSalesController;

RateLimiter::for('auth', function (Request $request) {
    return Limit::perMinute(5)->by($request->ip());
});

RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
});

RateLimiter::for('write', function (Request $request) {
    return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
});

Route::post('/auth/login', [AuthController::class, 'login'])->middleware('throttle:auth');

Route::middleware(['auth:api', 'throttle:api'])->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/logout-all', [AuthController::class, 'logoutAll']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::get('/auth/devices', [AuthController::class, 'devices']);
    
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/target-sales', [TargetSalesController::class, 'index']);
    Route::get('/stock', [StockController::class, 'index']);
    Route::get('/indent', [IndentController::class, 'index']);
    Route::get('/target-prospek', [TargetProspekController::class, 'index']);
    Route::get('/prospek', [ProspekController::class, 'index']);
    Route::get('/prospek/{id}', [ProspekController::class, 'show']);
    Route::get('/actual-spk', [ActualSpkController::class, 'index']);
    Route::get('/actual-sales', [ActualSalesController::class, 'index']);
    Route::get('/performance', [PerformanceController::class, 'index']);
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update'])->middleware('throttle:write');
});

Route::middleware(['auth:api', 'throttle:write'])->group(function () {
    Route::post('/prospek', [ProspekController::class, 'store']);
    Route::put('/prospek/{id}', [ProspekController::class, 'update']);
    Route::delete('/prospek/{id}', [ProspekController::class, 'destroy']);
    Route::post('/profile/photo', [ProfileController::class, 'uploadPhoto']);
});

Route::get('/health', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'API is running',
        'timestamp' => now()->toDateTimeString()
    ]);
});
