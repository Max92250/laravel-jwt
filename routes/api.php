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
    Route::get('/products/all', [ProductController::class, 'getAllProducts']);
    Route::post('/products/create', [ProductController::class, 'createProductWithItemsAndImages']);
    Route::put('/update-product/{productId}', [ProductController::class, 'updateEntity']);
    Route::delete('/hard-delete-product/{productId}', [ProductController::class, 'hardDeleteProduct']);

    Route::get('/logout', [LogoutController::class, 'logout']);

});
Route::post('password/reset', [PasswordResetController::class, 'forgot']);
Route::post('password/reset/{token}', [PasswordResetController::class, 'reset']);
