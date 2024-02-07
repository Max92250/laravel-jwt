<?php

use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;


    Route::post('/category', [WebController::class, 'createCategory'])->name('categories.create');
    Route::get('/products', [WebController::class, 'getAllProducts'])->name('products.index');
    Route::get('/products/imagecreate', [WebController::class, 'createimages']);
    Route::get('/categories', [WebController::class, 'showCategories']);
    Route::post('/products/items', [WebController::class,'createProductWithItem'])->name('products.create.items');
    Route::get('/products/create', [WebController::class, 'showCreateForm'])->name('products.create.form');
    Route::post('/products/create-with-images', [WebController::class, 'createProductWithImages'])->name('products.createWithImages');
    Route::get('/products/category/{categoryid}', [WebController::class, 'getProductsByCategory'])->name('products.by.category');
    Route::get('/products/search', [WebController::class, 'getProductsBySearch'])->name('products.by.search');
    Route::get('/products/details/{product_id}', [WebController::class, 'productdetails'])->name('productdetails');
    Route::put('/products/{productId}/update', [WebController::class, 'updateEntity'])->name('products.update');
    Route::put('/products/{productId}/update-images', [WebController::class, 'updateImages'])->name('api.products.updateImages');
    Route::delete('/products/{productId}/hard-delete', [WebController::class, 'hardDeleteProduct'])->name('products.hardDelete');

