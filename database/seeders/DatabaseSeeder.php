
<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Product;
use App\Models\Item;
use App\Models\Image;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $token = JWTAuth::fromUser($user);

      
        $product = Product::factory()->create();
        $item = Item::factory(['product_id' => $product->id])->create();
        $image = Image::factory(['product_id' => $product->id])->create();
    }
}
