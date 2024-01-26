<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

//Route::get('/product/{id}', [ProductController::class, 'edit'])->name('edit');
//Route::post('/products/{productId}/reviews', [ProductController::class, 'postReview'])->name('review');

Route::delete('/hard-delete-product/{productId}', [ProductController::class, 'hardDeleteProduct']);

Route::group(['middleware' => ['jwt.verify']], function () {

    Route::put('/update-product/{productId}', [ProductController::class, 'updateEntity'])->name('update.product');
    Route::get('/products/all', [ProductController::class, 'getAllProducts']);
    Route::post('/products/create', [ProductController::class, 'createProductWithItemsAndImages']);

    Route::get('/products/{productId}', [ProductController::class, 'getProductById']);

    Route::get('/logout', [LogoutController::class, 'logout']);

});
Route::put('/products/{productId}/deactivate/{itemId}', [ProductController::class, 'deactivateItem']);
Route::post('password/reset', [PasswordResetController::class, 'forgot']);
Route::post('password/reset/{token}', [PasswordResetController::class, 'reset']);
