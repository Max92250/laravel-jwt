<?php

use App\Http\Controllers\web\AuthenticationController;
use App\Http\Controllers\web\CategoryController;
use App\Http\Controllers\web\CustomerController;
use App\Http\Controllers\web\ImageController;
use App\Http\Controllers\web\ProductController;
use App\Http\Controllers\web\ProfileController;
use App\Http\Controllers\web\SizeController;
use App\Http\Controllers\web\UserController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/login', [AuthenticationController::class, 'loginform'])->name('loginform');
Route::post('/login', [AuthenticationController::class, 'login'])->name('login');
Route::get('/logout', [AuthenticationController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [CustomerController::class, 'show'])->name('dashboard');
    Route::get('/profiles', [ProfileController::class, 'show'])->name('profile');

    Route::middleware('role:admin')->group(function () {

        // Customer Routes
        Route::post('/customers', [CustomerController::class, 'create'])->name('customers.store');

        Route::get('/search', [CustomerController::class, 'search'])->name('admin.customers.search');
        Route::put('/customers/{id}/update', [CustomerController::class, 'update'])->name('customer.update');

        // User Routes
        Route::post('/users', [UserController::class, 'create'])->name('Users.Store');
        Route::get('/users', [UserController::class, 'show'])->name('users.details');
        Route::get('/customers/{customerId}/users', [UserController::class, 'customer_users'])->name('user.dashboard');
        Route::put('/users/{id}/update', [UserController::class, 'update'])->name('users.update');

    });

    Route::middleware('role:user')->group(function () {

        // Category Routes
        Route::get('/category', [CategoryController::class, 'show'])->name('categories');
        Route::post('/category', [CategoryController::class, 'create'])->name('categories.create');
        Route::put('/category/{id}/update', [CategoryController::class, 'update'])->name('categories.updates');

        // Size Routes
        Route::get('/sizes', [SizeController::class, 'show_Sizes'])->name('sizes');
        Route::post('/size', [SizeController::class, 'store'])->name('sizes.store');
        Route::put('/sizes/{id}/update', [SizeController::class, 'update'])->name('sizes.update');
        Route::get('/sizes/{id}/subsizes', [SizeController::class, 'show_SubSizes'])->name('sizes.subsizes');

        // Product Routes
        Route::get('/products', [ProductController::class, 'create'])->name('products.create.form');
        Route::get('/products/{product_id}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{productId}/update', [ProductController::class, 'update'])->name('products.update');
        Route::post('/products/create', [ProductController::class, 'stores'])->name('products.create.items');
        Route::get('/Search', [ProductController::class, 'search'])->name('products.by.search');

        // Image Routes
        Route::get('/products/images/{product_id}', [ImageController::class, 'show'])->name('showimages');
        Route::put('/products/images/{id}/soft-delete', [ImageController::class, 'softdelete'])->name('images.soft-delete');
        Route::get('/category/products/image/{product_id}', [ImageController::class, 'store'])->name('imagecreate');
        Route::post('category/products/images', [ImageController::class, 'create'])->name('products.createWithImages');
        Route::get('/images/{id}', [ImageController::class, 'delete'])->name('images.delete');

    });

});
