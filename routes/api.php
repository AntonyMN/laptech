<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SettingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

$apiRoutes = function () {
    // Admin Login (No registration, only admin access)
    Route::post('/login', [AuthController::class, 'login']);

    // Protected Admin Routes
    Route::middleware('auth:sanctum')->group(function () {
        // User & Profile
        Route::get('/user', [AuthController::class, 'user']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
        Route::post('/logout', [AuthController::class, 'logout']);

        // Dashboard Analytics & Alerts
        Route::get('/dashboard', [DashboardController::class, 'index']);

        // Products CRUD & Archive
        Route::get('/products', [ProductController::class, 'index']);
        Route::get('/products/{id}', [ProductController::class, 'show']);
        Route::post('/products', [ProductController::class, 'store']);
        Route::post('/products/{id}', [ProductController::class, 'update']); // Supports multipart image update
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);
        Route::post('/products/{id}/archive', [ProductController::class, 'archive']);
        Route::post('/products/{id}/restore', [ProductController::class, 'restore']);

        // Product Categories
        Route::get('/categories', [ProductController::class, 'categories']);
        Route::post('/categories', [ProductController::class, 'storeCategory']);

        // Orders Management
        Route::get('/orders/stats', [OrderController::class, 'stats']);
        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/{id}', [OrderController::class, 'show']);
        Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus']);

        // Services CRUD & Categories
        Route::get('/service-categories', [ServiceController::class, 'categories']);
        Route::post('/service-categories', [ServiceController::class, 'storeCategory']);
        Route::get('/services', [ServiceController::class, 'index']);
        Route::get('/services/{id}', [ServiceController::class, 'show']);
        Route::post('/services', [ServiceController::class, 'store']);
        Route::put('/services/{id}', [ServiceController::class, 'update']);
        Route::delete('/services/{id}', [ServiceController::class, 'destroy']);

        // Settings & System
        Route::get('/settings', [SettingController::class, 'index']);
        Route::post('/cache/clear', [SettingController::class, 'clearCache']);
    });
};

Route::prefix('v1')->group($apiRoutes);
Route::group([], $apiRoutes);
