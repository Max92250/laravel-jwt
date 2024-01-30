<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Models\Item;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProductUpdateValidationTest extends TestCase
{
    use WithFaker;

    

    public function testUpdateProductImageFormatAndItemsValidation()
    {
        
        
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $token = JWTAuth::fromUser($user);

        $product = Product::factory()->create();
        $item = Item::factory(['product_id' => $product->id])->create();
      

        $newItemData = [
            'id' => $item->id,
            'price' => 'invalid_price', // Invalid price format
            'size' => 'M',
        ];

      

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('PUT', '/api/products/' . $product->id . '/update-items', [
            'name' => $product['name'],
            'description' => $product['description'],
            'items' => [$newItemData],
          
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'items.0.price', 
              
            ]);


            $invalidItemData = [
                'id' => $item->id,
                'price' => 555, //
                'size' => 'M',
            ];
    
            $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->json('PUT', '/api/products/' . $product->id . '/update-items',  [
                'name' => '123', // Numeric name (invalid)
                'description' => $product['description'],
                'items' => [$invalidItemData],
             
            ]);
    
            $response->assertStatus(422)
                ->assertJsonValidationErrors([
                    'name', // Check for name 
                ]);
            
                // Valid data
        $validItemData = [
            'id' => $item->id,
            'price' => 19.99,
            'size' => 'M',
        ];

        $validImage1 = UploadedFile::fake()->image('new_image1.jpg');
        $validImage2 = UploadedFile::fake()->image('new_image2.jpg');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('PUT', '/api/products/' . $product->id . '/update-items',  [
            'name' => 'Valid Name',
            'description' => $product['description'],
            'items' => [$validItemData],
           
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Product updated successfully',
            ]);
    

       
    }
}
