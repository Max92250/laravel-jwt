<?php

namespace Tests\Feature;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
class ProductControllerTest extends TestCase
{
    use WithFaker;
    use DatabaseTransactions;
    
    
    public function testCreateProductWithItems()
    {
        $user = User::factory()->create();

        $token = JWTAuth::fromUser($user);

        $itemData = [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'items' => [
                [
                    'sku' => 'SKU001',
                    'price' => 134,
                    'size' => 'M',
                    'color' => 'Blue',
               
                ],
            ],
    
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/products/items', $itemData);

        $response->assertStatus(201)->assertJson([
            'status' => 'success',
            'product_id' => $response->json('product_id'),
        ]);

        $product = Product::where('name', $itemData['name'])->first();

        $this->assertProductAttributes($product, $itemData['name'], $itemData['description']);

        $this->assertItemCount($product, 1);
        
     
        $this->assertItemAttributes($product, $itemData['items'][0]);

       
    }

    protected function assertProductAttributes($product, $name, $description)
    {
        $this->assertEquals($name, $product->name);
        $this->assertEquals($description, $product->description);

        $this->assertDatabaseHas('products', [
            'name' => $name,
            'description' => $description,
        ]);
    }

    protected function assertItemCount($product, $expectedCount)
    {
        $this->assertCount($expectedCount, $product->items);
    }

    protected function assertItemAttributes($product, $itemData)
    {
        $item = $product->items->first();
        $this->assertEquals($itemData['price'], $item->price);
        $this->assertEquals($itemData['size'], $item->size);
        $this->assertEquals($itemData['color'], $item->color);

        $this->assertDatabaseHas('items', [
            'product_id' => $product->id,
            'price' => $itemData['price'],
            'size' => $itemData['size'],
            'color' => $itemData['color'],
        ]);

        $this->assertArrayHasKey('price', $itemData);
        $this->assertArrayHasKey('size', $itemData);
        $this->assertArrayHasKey('color', $itemData);

    }

}