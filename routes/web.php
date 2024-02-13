<?php

use App\Http\Controllers\web\AuthController;
use App\Http\Controllers\web\WebController;
use App\Http\Controllers\web\AdminController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('loginform');
Route::group(['middleware' => ['login']], function () {
    Route::get('/category', [WebController::class, 'Category'])->name('categories');
    Route::post('/Admin/customers', [AdminController::class, 'CustomerCreate'])->name('customers.store');
    Route::get('/admin/users-store', [AdminController::class, 'User'])->name('Admin.users');
    Route::get('/admin/dashboard/customer', [AdminController::class, 'dashboard'])->name('Admin.dashboard');
    Route::post('/admin/users-store', [AdminController::class, 'UserCreate'])->name('Users.Store');
    Route::get('/sizes', [WebController::class, 'Sizes'])->name('sizes');
    Route::get('/admin/customers/{customerId}/users', [AdminController::class, 'UserDashboard'])->name('user.dashboard');

    Route::get('/Sizes', [WebController::class, 'sizelisting'])->name('Sizes');
    Route::get('/products', [WebController::class, 'getAllProducts'])->name('products.index');
    Route::get('/products/images/{product_id}', [WebController::class, 'showimages'])->name('showimages');
    Route::get('/category/products/image/{product_id}', [WebController::class, 'createimages'])->name('imagecreate');
    Route::get('/sizes/{id}/subsizes',[WebController::class, 'showSubSizes'])->name('sizes.subsizes');

    // Route::get('/categories', [WebController::class, 'showCategories']);
    Route::post('/category/products/items', [WebController::class, 'createProductWithItem'])->name('products.create.items');
    Route::get('/category/products/create', [WebController::class, 'showCreateForm'])->name('products.create.form');
    Route::post('category/products/images', [WebController::class, 'createProductWithImages'])->name('products.createWithImages');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/admin/customer/users', [AdminController::class, 'userdetails'])->name('users.details');
    Route::get('/profile', [WebController::class, 'profile'])->name('profile.show');
    Route::get('products/{product_id}/edit', [WebController::class, 'productedit'])->name('products.edit');

    // Route::get('/products/category/{categoryid}', [WebController::class, 'getProductsByCategory'])->name('products.by.category');
     Route::get('/products/search', [WebController::class, 'getProductsBySearch'])->name('products.by.search');
    //  Route::get('/products/details/{product_id}', [WebController::class, 'productdetails'])->name('productdetails');
     Route::put('/products/{productId}/update', [WebController::class, 'updateEntity'])->name('products.update');
    //  Route::put('/products/{productId}/update-images', [WebController::class, 'updateImages'])->name('api.products.updateImages');
    // Route::delete('/products/{productId}/hard-delete', [WebController::class, 'hardDeleteProduct'])->name('products.hardDelete');
    Route::get('/images/{id}', [WebController::class, 'delete'])->name('images.delete');
});
