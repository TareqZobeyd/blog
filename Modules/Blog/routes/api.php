<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\Http\Controllers\CategoryController;

// Public routes (no authentication required)
Route::prefix('v1')->group(function () {
    // Public reading routes
    Route::get('/categories', [CategoryController::class, 'index'])->name('category.index');
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('category.show');

    // Protected routes (only super admin can modify)
    Route::middleware(['auth:sanctum', 'super.admin'])->group(function () {
        Route::post('/categories', [CategoryController::class, 'store'])->name('category.store');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('category.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');
    });
});
