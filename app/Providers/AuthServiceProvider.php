<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Product;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();

       /* Gate::define('edit-product', function (User $user, Product $product) {
            return $user->id === $product->created_by;
        });

        Gate::define('view-product-images', function ( User $user,Product $product) {
            // Check if the product is created by the authenticated user
            return $product->created_by === $user->id;
        });

        Gate::define('view-profile', function ( User $user) {
            return $user->isAdmin() || $user->isUser(); // Example condition for viewing profile
        });*/
    
   
    }
}
