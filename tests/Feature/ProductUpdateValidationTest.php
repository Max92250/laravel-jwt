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
        $image = Image::factory(['product_id' => $product->id])->create();

        $newItemData = [
            'id' => $item->id,
            'price' => 'invalid_price', // Invalid price format
            'size' => 'M',
        ];

        $newImage1 = UploadedFile::fake()->create('invalid_image.txt'); // Invalid image format
        $newImage2 = UploadedFile::fake()->image('new_image2.jpg');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson('/api/update-product/' . $product->id, [
            'name' => $product['name'],
            'description' => $product['description'],
            'items' => [$newItemData],
            'image_ids' => $image->id,
            'image_1' => $newImage1,
            'image_2' => $newImage2,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'items.0.price', 
                'image_1', 
            ]);


            $invalidItemData = [
                'id' => $item->id,
                'price' => 555, // Invalid price format
                'size' => 'M',
            ];
    
            $newImage1 = UploadedFile::fake()->image('new_image1.jpg');
            $newImage2 = UploadedFile::fake()->image('new_image2.jpg');
    
            $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->putJson('/api/update-product/' . $product->id, [
                'name' => '123', // Numeric name (invalid)
                'description' => $product['description'],
                'items' => [$invalidItemData],
                'image_ids' => $image->id,
                'image_1' => $newImage1,
                'image_2' => $newImage2,
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
        ])->putJson('/api/update-product/' . $product->id, [
            'name' => 'Valid Name',
            'description' => $product['description'],
            'items' => [$validItemData],
            'image_ids' => $image->id,
            'image_1' => $validImage1,
            'image_2' => $validImage2,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Product updated successfully',
            ]);
    

       
    }
}
