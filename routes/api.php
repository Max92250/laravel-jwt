<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

//Route::get('/product/{id}', [ProductController::class, 'edit'])->name('edit');
//Route::post('/products/{productId}/reviews', [ProductController::class, 'postReview'])->name('review');
Route::post('/users/register', [RegisterController::class, 'register'])->name('users.register');
Route::post('/users/login', [LoginController::class, 'login'])->name('users.login');

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('/products', [ProductController::class, 'getAllProducts'])->name('products.all');
    Route::put('/products/{productId}/update-images', [ProductController::class, 'updateImages'])->name('products.update-image');
    Route::post('/products/items', [ProductController::class, 'createProductWithItems'])->name('products.create-with-items');
    Route::delete('/products/{productId}/delete', [ProductController::class, 'hardDeleteProduct'])->name('products.delete');

    Route::post('/products/images', [ProductController::class, 'createProductWithImages'])->name('images.create');

    Route::put('/products/{productId}/update-items', [ProductController::class, 'updateEntity'])->name('products.update');

    Route::get('/products/{productId}', [ProductController::class, 'getProductById'])->name('products.get-by-id');
    Route::get('/logout', [LogoutController::class, 'logout'])->name('users.logout');
});

Route::put('/products/{productId}/items/{itemId}/deactivate', [ProductController::class, 'deactivateItem'])->name('products.items.deactivate');
Route::post('password/reset', [PasswordResetController::class, 'forgot'])->name('password.reset.forgot');
Route::post('password/reset/{token}', [PasswordResetController::class, 'reset'])->name('password.reset');
