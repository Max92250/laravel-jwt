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

class ProductUpdateTest extends TestCase
{
    use WithFaker;
   
    public function testUpdateProductWithItemsAndImages()
    {
        $user = User::factory()->create();

        $token = JWTAuth::fromUser($user);

        $product = Product::factory()->create();
        $item = Item::factory(['product_id' => $product->id])->create();

       
        $newName = $this->faker->word;
        $newDescription = $this->faker->sentence;

        $newItemsData = [
            ['id' => $item->id, 'price' => 20.99, 'size' => 'L', 'color' => 'Red'],
            ['price' => 15.50, 'size' => 'M', 'color' => 'Blue', 'sku' => 'ABC123'],
           
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('PUT', '/api/products/' . $product->id . '/update-items', [
            'name' => $newName,
            'description' => $newDescription,
            'items' => $newItemsData,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Product updated successfully',
            ]);

        $this->assertEquals($newName, $product->refresh()->name);

        $this->assertEquals($newDescription, $product->refresh()->description);


        $this->assertCount(2, $product->items);

        $updatedItem = $product->items->first();

        $this->assertEquals($newItemsData[0]['price'], $updatedItem->price);

        $this->assertEquals($newItemsData[0]['size'], $updatedItem->size);
        
        $secondUpdatedItem = $product->items->last();
        $this->assertEquals($newItemsData[1]['price'], $secondUpdatedItem->price);

        $this->assertEquals($newItemsData[1]['size'], $secondUpdatedItem->size);

        $this->assertEquals($newItemsData[1]['color'], $secondUpdatedItem->color);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => $newName,
            'description' => $newDescription,
        ]);

        $this->assertDatabaseHas('items', [
            'id' => $newItemsData[0]['id'], 
            'price' => $newItemsData[0]['price'],
            'size' => $newItemsData[0]['size'],
        ]);
        
        $this->assertDatabaseHas('items', [
            'price' => $newItemsData[1]['price'],
            'size' => $newItemsData[1]['size'],
            'color' => $newItemsData[1]['color'],
        ]);

        $responseArray = $response->json();
        $this->assertArrayHasKey('status', $responseArray);
        $this->assertArrayHasKey('message', $responseArray);
    }
}
