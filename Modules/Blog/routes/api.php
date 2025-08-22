<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\Http\Controllers\CategoryController;
use Modules\Blog\Http\Controllers\PostController;

// Public routes (no authentication required)
Route::prefix('v1')->group(function () {
    // Public reading routes
    Route::get('/categories', [CategoryController::class, 'index'])->name('category.index');
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('category.show');
    Route::get('/posts', [PostController::class, 'index'])->name('post.index');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('post.show');

    // Protected routes (all authenticated users can create posts)
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/posts', [PostController::class, 'store'])->name('post.store');
        Route::post('/posts/{post}/update', [PostController::class, 'update'])->name('post.update');
        Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('post.destroy');
    });

    // Protected routes (only super admin can modify categories)
    Route::middleware(['auth:sanctum', 'super.admin'])->group(function () {
        Route::post('/categories', [CategoryController::class, 'store'])->name('category.store');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('category.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');
    });
});
