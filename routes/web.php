<?php

use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
 */

Route::get('/', function () {
    return view('welcome');
});


Route::post('/products/create', [WebController::class, 'createProductWithItem'])->name('products.create');

Route::post('/products/create-with-images', [WebController::class, 'createProductWithImages'])->name('products.createWithImages');

Route::put('/products/{productId}/update', [WebController::class, 'updateEntity'])->name('products.update');
Route::put('/products/{productId}/update-images', [WebController::class, 'updateImages'])->name('api.products.updateImages')