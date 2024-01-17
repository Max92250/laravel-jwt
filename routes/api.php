<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('/fetch-products', [ProductController::class, 'getProducts']);
    Route::get('/fetch-products/{id}', [ProductController::class, 'getProductById']);
    Route::post('/products', [ProductController::class, 'insertProduct']);
    Route::post('/items', [ProductController::class, 'insertItem']);
    Route::post('/upload-image', [ProductController::class, 'uploadImage']);
    Route::put('/products/{id}/update', [ProductController::class, 'updateProductName']);
    Route::put('/products/{productId}/items/{itemId}/update', [ProductController::class, 'updateItemPrice']);
    Route::patch('/products/{productId}/items/{itemId}/update-size', [ProductController::class, 'updateItemSize']);
Route::patch('/products/{productId}/items/{itemId}/update-color', [ProductController::class, 'updateItemColor']);
    Route::get('/logout', [LogoutController::class, 'logout']);

});
Route::post('password/reset', [PasswordResetController::class, 'forgot']);
Route::post('password/reset/{token}', [PasswordResetController::class, 'reset']);
