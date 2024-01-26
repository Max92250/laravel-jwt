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
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

      
        $token = JWTAuth::fromUser($user);

        $product = Product::factory()->create();
        $item = Item::factory(['product_id' => $product->id])->create();
        $image = Image::factory(['product_id' => $product->id])->create();

        $newItemData = [
            'id' => $item->id,
            'price' => 19.99,
            'size' => 'M',
        ];

        $newImage1 = UploadedFile::fake()->image('new_image1.jpg');
        $newImage2 = UploadedFile::fake()->image('new_image2.jpg');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putjson('/api/update-product/' . $product->id, [
            'name' => $product['name'],
            'description' => $product['description'],
            'items' => [$newItemData],
            'image_ids' => $image->id,
            'image_1' => $newImage1,
            'image_2' => $newImage2,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Product updated successfully',
            ]);

        $product->refresh();
        $this->assertEquals($product['name'], $product->name);
        $this->assertEquals($product['description'], $product->description);

        $this->assertCount(1, $product->items);
        $updatedItem = $product->items->first();
        $this->assertEquals($newItemData['price'], $updatedItem->price);
        $this->assertEquals($newItemData['size'], $updatedItem->size);

        $this->assertCount(1, $product->images);
        $updatedImages = $product->images->first();
        $this->assertNotNull($updatedImages->image_1);
        $this->assertNotNull($updatedImages->image_2);

       
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => $product['name'],
            'description' => $product['description'],
        ]);

        $this->assertDatabaseHas('items', [
            'id' => $newItemData['id'],
            'price' => $newItemData['price'],
            'size' => $newItemData['size'],
        ]);

        $this->assertDatabaseHas('images', [
            'id' => $image->id,
            'image_1' => basename(parse_url(asset($updatedImages->image_1), PHP_URL_PATH)),
            'image_2' => basename(parse_url(asset($updatedImages->image_2), PHP_URL_PATH)),
        ]);

        $responseArray = $response->json();
        $this->assertArrayHasKey('status', $responseArray);
        $this->assertArrayHasKey('message', $responseArray);
    }
}
