<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Frontend\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
});

Route::middleware('auth')->post('logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('products', AdminProductController::class);

    Route::post('products/{product}/images', [AdminProductController::class, 'uploadImage'])->name('products.images.store');
    Route::delete('products/{product}/images/{image}', [AdminProductController::class, 'deleteImage'])->name('products.images.destroy');
    Route::post('products/{product}/reorder-images', [AdminProductController::class, 'reorder'])->name('products.reorder');
    Route::patch('products/{product}/toggle', [AdminProductController::class, 'toggleActive'])->name('products.toggle');

    Route::resource('gallery', AdminGalleryController::class);
    Route::post('gallery-reorder', [AdminGalleryController::class, 'reorder'])->name('gallery.reorder');
});
