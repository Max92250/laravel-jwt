<?php

use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;

Route::get('/products', [WebController::class, 'getAllProducts'])->name('products.index');
Route::post('/products/create', [WebController::class, 'createProductWithItem'])->name('products.create');
Route::post('/products/create-with-images', [WebController::class, 'createProductWithImages'])->name('products.createWithImages');
Route::put('/products/{productId}/update', [WebController::class, 'updateEntity'])->name('products.update');
Route::put('/products/{productId}/update-images', [WebController::class, 'updateImages'])->name('api.products.updateImages');
Route::delete('/products/{productId}/hard-delete', [WebController::class, 'hardDeleteProduct'])
    ->name('products.hardDelete');
