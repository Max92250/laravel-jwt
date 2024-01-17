<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\ProductController;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('/fetch-products', [ProductController::class, 'getProducts']);
    Route::get('/fetch-products/{id}', [ProductController::class, 'getProductById']);
    Route::post('/products', [ProductController::class, 'insertProduct']);
    Route::post('/items', [ProductController::class, 'insertItem']);
    Route::post('/upload-image', [ProductController::class, 'uploadImage']);

    Route::get('/users', [UserController::class, 'allUsers']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::get('/logout', [LogoutController::class, 'logout']);

});
Route::post('password/reset', [PasswordResetController::class, 'forgot']);
Route::post('password/reset/{token}', [PasswordResetController::class, 'reset']);