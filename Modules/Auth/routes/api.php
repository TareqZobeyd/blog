<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;

// Public routes (no authentication required)
Route::post('/register', [AuthController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (require authentication)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});
