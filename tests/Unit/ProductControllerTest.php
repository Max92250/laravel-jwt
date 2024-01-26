<?php

namespace Tests\Unit;
use App\Models\User;
use App\Models\Image;
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

    public function testCreateProductWithItemsAndImages()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $token = JWTAuth::fromUser($user);

        $itemAndImageData = [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'items' => [
                [
                    'price' => 134,
                    'size' => 'M',
                    'color' => 'Blue',
                    'sku' => 'ffvdf4556',
                ],
            ],
            'images' => [
                [
                    'image_1' => UploadedFile::fake()->image('image1.jpg'),
                    'image_2' => UploadedFile::fake()->image('image2.jpg'),
                ],
            ],
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/products/create', $itemAndImageData);

        $response->assertStatus(201)->assertJson([
            'status' => 'success',
            'product_id' => $response->json('product_id'),
        ]);

        $product = Product::where('name', $itemAndImageData['name'])->first();

        $this->assertProductAttributes($product, $itemAndImageData['name'], $itemAndImageData['description']);

        $this->assertItemCount($product, 1);
        
        $this->assertItemCount($product, 1);

        $this->assertItemAttributes($product, $itemAndImageData['items'][0]);

        $this->assertImageCount($product, 1);

        $this->assertImageAttributes($product, $itemAndImageData['images'][0]);
    }

    protected function assertProductAttributes($product, $name, $description)
    {
        $this->assertNotNull($product);
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

    protected function assertImageCount($product, $expectedCount)
    {
        $this->assertCount($expectedCount, $product->images);
    }

    protected function assertImageAttributes($product, $imageData)
    {
        $updatedImages = $product->images->first();
        $image1Filename = basename(parse_url(asset($updatedImages->image_1), PHP_URL_PATH));
        $image2Filename = basename(parse_url(asset($updatedImages->image_2), PHP_URL_PATH));

        $this->assertDatabaseHas('images', [
            'product_id' => $product->id,
            'image_1' => $image1Filename,
            'image_2' => $image2Filename,
        ]);
   
        $this->assertIsArray($imageData);
        $this->assertArrayHasKey('image_1', $imageData);
        $this->assertArrayHasKey('image_2', $imageData);
       

    }

 /*   public function testValidateProductData()
    {
        $response = $this->postJson('/api/products/create', [
            'name' => 'rrr',
            'description' => 'its good',
            'items' => [
                [
                    'price' => 4555,
                    'size' => '6.i inch',
                    'color' => 'red',
                    'sku' => 'ffvdf4556',
                ],
            ],
            'images' => [
                [
                    'image_1' => UploadedFile::fake()->create('image1.png'),
                    'image_2' => UploadedFile::fake()->create('image2.jpg'),
                ],
            ],
        ]);

        if ($response->status() == 201) {
            $response->assertJson([
                'status' => 'success',
                'product_id' => $response->json('product_id'),
            ]);
        } else {
            $response->assertStatus(422)
                ->assertJson([
                    'status' => 'error',
                    'message' => 'The given data was invalid.',
                ])
                ->assertJsonValidationErrors([
                    'name' => 'The name field is required.',
                    'description' => 'The description field is required.',
                    'items.0.price' => 'The items.0.price field is required.',
                    'items.0.size' => 'The items.0.size field is required.',
                    'items.0.color' => 'The items.0.color field is required.',
                    'images.0.image_1' => 'The images.0.image_1 must be an image.',
                    'images.0.image_2' => 'The images.0.image_2 must be an image.',
                ]);
        }
    }*/

}
