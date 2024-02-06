<?php
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('')->group(function () {

    // Public routes
    Route::post('/users/register', [RegisterController::class, 'register'])->name('users.register');
    Route::post('/users/login', [LoginController::class, 'login'])->name('users.login');
    Route::post('password/reset', [PasswordResetController::class, 'forgot'])->name('password.reset.forgot');
    Route::post('password/reset/{token}', [PasswordResetController::class, 'reset'])->name('password.reset');

    // Protected routes with JWT middleware
    Route::group(['middleware' => ['jwt.verify']], function () {

        // GET Routes
        Route::get('/products', [ProductController::class, 'getAllProducts'])->name('products.all');
        Route::get('/products/{productId}', [ProductController::class, 'getProductById'])->name('products.get-by-id');
        Route::get('/category/{categoryId}/products', [ProductController::class, 'getProductsByCategory']);
        Route::get('/products/{productName}/category', [ProductController::class, 'getCategoriesByProductName']);
        Route::get('/items/by-size/{sizeId}', [ProductController::class, 'getBySizeId']);

        // POST Routes
        Route::post('/products/items', [ProductController::class, 'createProductWithItems'])->name('products.create-with-items');
        Route::post('products/category', [ProductController::class, 'createCategory']);
        Route::post('/products/images', [ProductController::class, 'createProductWithImages'])->name('images.create');
        Route::post('/sizes', [ProductController::class, 'store']);
      
        // PUT Routes
        Route::put('/products/{productId}/update-items', [ProductController::class, 'updateEntity'])->name('products.update');
        Route::put('/products/{productId}/update-images', [ProductController::class, 'updateImages'])->name('products.update-image');
        Route::put('/products/{productId}/items/{itemId}/deactivate', [ProductController::class, 'deactivateItem'])->name('products.items.deactivate');

        // DELETE Route
        Route::delete('/products/{productId}/delete', [ProductController::class, 'hardDeleteProduct'])->name('products.delete');

        // Logout Route
        Route::get('/logout', [LogoutController::class, 'logout'])->name('users.logout');
    });
});
