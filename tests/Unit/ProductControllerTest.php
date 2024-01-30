<?php

namespace Tests\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\ProductController;
use App\Models\Product;
use App\Models\Item;
use App\Models\Image;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\File;
use App\Services\ProductService; 
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use WithFaker;

    public function testCreateItems()
    {

        $controller = new ProductController();
        $productName = $this->faker->word;
        $productDescription = $this->faker->sentence;
        $itemData = [
            [
                'price' => 19.99,
                'size' => 'M',
                'color' => 'Red',
                'sku' => 'SKU123',
            ],

        ];

        $response = $controller->createProductWithItems($this->createRequest([
            'name' => $productName,
            'description' => $productDescription,
            'items' => $itemData,
        ]));

        $product = Product::where('name', $productName)->first();

        $this->assertNotNull($product);
        $this->assertEquals($productName, $product->name);
        $this->assertEquals($productDescription, $product->description);

        foreach ($itemData as $item) {
            $this->assertDatabaseHas('items', [
                'product_id' => $product->id,
                'price' => $item['price'],
                'size' => $item['size'],
                'color' => $item['color'],
                'sku' => $item['sku'],
            ]);
        }
    }
  
    public function testCreateImages()
    {
        
        $controller = new ProductController();
        
        $productId=7;

        $images = [
            UploadedFile::fake()->image('image1.jpg'),
            UploadedFile::fake()->image('image2.jpg'),
        ];

        $request = $this->createImageRequest($productId, $images);

     
        $response = $controller->createProductWithImages($request);

 
        
 
        $product = Product::find(7);

        $images = $product->images;

        foreach ($images as $image) {
            $this->assertDatabaseHas('images', [
                'id' => $image->id,
                'product_id' => $product->id,
                'image_path' => $image->image_path,
            ]);

            $this->assertFileExists(public_path("images/{$image->image_path}"));

          
    
        }

      
    }

    private function createImageRequest($productId, $images)
    {
        $imageRequest = [
            'product_id' => $productId,
            'images' => $images,
        ];

        return new \Illuminate\Http\Request($imageRequest);
    }


public function testUpdateEntity()
{
    
    $product = Product::factory()->create();
    $item = Item::factory()->create(['product_id' => $product->id]);

    $requestData = [
        'name' => 'Updated Product Name',
        'description' => 'Updated Product Description',
        'items' => [
            [
                'id' => $item->id,
                'price' => 29.99,
                'size' => 'L',
                'color' => 'Blue',
            ],
            [
                'price' => 19.99,
                'size' => 'M',
                'color' => 'Red',
          
            ],
        ],
    ];

    
    $controller = new ProductController();
    $response = $controller->updateEntity($this->createRequest($requestData), $product->id);

    
    $this->assertDatabaseHas('products', [
        'id' => $product->id,
        'name' => 'Updated Product Name',
        'description' => 'Updated Product Description',
    ]);

    $this->assertDatabaseHas('items', [
        'id' => $item->id,
        'price' => 29.99,
        'size' => 'L',
        'color' => 'Blue',
    ]);

    $this->assertDatabaseHas('items', [
        'product_id' => $product->id,
        'price' => 19.99,
        'size' => 'M',
        'color' => 'Red',
        'sku' => 'SKU123',
    ]);

  
}

private function createRequest($data)
{
    return new Request($data);
}

public function testGetProducts()
{
    
    $products = Product::factory(3)->create();

    foreach ($products as $product) {
        Item::factory()->create(['product_id' => $product->id, 'status' => 'active']);

        Image::factory()->create(['product_id' => $product->id]);
    }


    $inactiveProduct = Product::factory()->create();

    Item::factory()->create(['product_id' => $inactiveProduct->id, 'status' => 'inactive']);

    Image::factory()->create(['product_id' => $inactiveProduct->id]);

    
   $controller = new ProductController();

   
   $result = $controller->getAllProducts();
  
   $this->assertIsArray($result);

   $this->assertArrayHasKey('products', $result);
   
}
public function testHardDeleteProduct()
{
    
    $product = Product::factory()->create();

    
    $controller = new ProductController();


    $response = $controller->hardDeleteProduct($product->id);

    
    $this->assertEquals(['status' => 'success', 'message' => 'Product and associated items/images deleted successfully'], $response->getData(true));

    $this->assertDatabaseMissing('products', ['id' => $product->id]);

}
public function testImageUpdate()
{
    
    $product = Product::factory()->create();

    $item = Item::factory(3)->create(['product_id' => $product->id]);
    $images = Image::factory(3)->create(['product_id' => $product->id]);

    
    $newImage1 = UploadedFile::fake()->image('new_image1.jpg');
    $newImage2 = UploadedFile::fake()->image('new_image2.jpg');

    
    $request = new Request([
        'product_id' => $product->id,
    ]);
    $request->files->add([
        'images' => [$newImage1, $newImage2],
    ]);

    
    $controller = new ProductController();

    
    $response = $controller->updateImages($request, $product->id);

    
    $this->assertEquals(['status' => 'success', 'product_id' => $product->id], $response->getData(true));


    foreach ([$newImage1, $newImage2] as $newImage) {
        $expectedImagePath = now()->timestamp . '_' . $newImage->getClientOriginalName();
        $this->assertDatabaseHas('images', [
            'product_id' => $product->id,
            'image_path' => $expectedImagePath,
        ]);
        $this->assertFileExists(public_path("images/{$expectedImagePath}"));
    }
}

}
