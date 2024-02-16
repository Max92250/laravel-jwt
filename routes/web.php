<?php
use App\Http\Controllers\web\CustomerController;
use App\Http\Controllers\web\ProductController;
use App\Http\Controllers\web\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [UserController::class, 'loginform'])->name('loginform');
Route::post('/login', [UserController::class, 'login'])->name('login');

Route::get('/logout', [UserController::class, 'logout'])->name('logout');
Route::group(['middleware' => ['auth:sanctum', 'role:admin']], function () {

    Route::get('/profiles', [ProductController::class, 'profile'])->name('profile.show');

    Route::post('/admin/customers', [CustomerController::class, 'CustomerCreate'])->name('customers.store');
    Route::get('/admin/dashboard/customer', [CustomerController::class, 'Customerdetails'])->name('Admin.dashboard');
    Route::post('/admin/users-store', [UserController::class, 'UserCreate'])->name('Users.Store');
    Route::get('/admin/customer/users', [UserController::class, 'userdetails'])->name('users.details');
    Route::get('/admin/customers/{customerId}/users', [UserController::class, 'CustomerUser'])->name('user.dashboard');
    Route::get('/admin/customers', [CustomerController::class, 'getCustomersBySearch'])->name('admin.customers.search');
    Route::put('/customers/{id}/update', [CustomerController::class, 'update'])->name('customer.update');



});

Route::group(['middleware' => ['auth:sanctum', 'role:user']], function () {
    Route::get('/profile', [ProductController::class, 'profile'])->name('profile.show');

    Route::get('/category', [ProductController::class, 'Category'])->name('categories');
    Route::post('/createcategory', [ProductController::class, 'createCategory'])->name('categories.create');
    Route::put('/category/{id}/update',[ ProductController::class,'updateCategory'])->name('categories.updates');

    Route::get('/sizes', [ProductController::class, 'Sizes'])->name('sizes');
    Route::post('/sizecreate', [ProductController::class, 'store'])->name('sizes.store');
    Route::put('/sizes/{id}/update', [ProductController::class, 'sizeupdate'])->name('sizes.update');
    Route::get('/products', [ProductController::class, 'getAllProducts'])->name('product.dashboard');
    Route::get('/products/create', [ProductController::class, 'ProductCreate'])->name('products.create.form');
    Route::get('/products/{product_id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{productId}/update', [ProductController::class, 'updateproduct'])->name('products.update');
    Route::get('/products/images/{product_id}', [ProductController::class, 'showimages'])->name('showimages');
    Route::put('/images/{id}/soft-delete', [ProductController::class, 'softdelete'])->name('images.soft-delete');
    Route::get('/category/products/image/{product_id}', [ProductController::class, 'createimages'])->name('imagecreate');
    Route::post('category/products/images', [ProductController::class, 'createProductWithImages'])->name('products.createWithImages');
    Route::post('/category/products/items', [ProductController::class, 'createProductWithItem'])->name('products.create.items');
    Route::get('/sizes/{id}/subsizes', [ProductController::class, 'showSubSizes'])->name('sizes.subsizes');

    Route::get('/images/{id}', [ProductController::class, 'delete'])->name('images.delete');
    Route::get('/products/search', [ProductController::class, 'getProductsBySearch'])->name('products.by.search');
});
