<?php

namespace Tests\Feature;
use App\Models\Product;
use App\Models\User;
use App\Models\Item;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Image;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    
     public function testHardDeleteProductWithItemsAndImages()
     {

        $user = User::factory()->create();

        $token = JWTAuth::fromUser($user);

         
         $productsWithItemsAndImages = Product::with(['items', 'images'])->get();
 
    
         $this->assertNotEmpty($productsWithItemsAndImages);
 
         $product = $productsWithItemsAndImages->first();
 
         $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->Json('DELETE', "/api/products/{$product->id}/delete");

         $response->assertStatus(200)
             ->assertJson([
                 'status' => 'success',
                 'message' => 'Product and associated items/images deleted successfully',
             ]);
 
      
         $this->assertDatabaseMissing('products', ['id' => $product->id]);
 
         foreach ($product->items as $item) {
             $this->assertDatabaseMissing('items', ['id' => $item->id]);
         }
      
         foreach ($product->images as $image) {
             $this->assertDatabaseMissing('images', ['id' => $image->id]);
         }

     }
}
