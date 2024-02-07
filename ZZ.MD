<?php

namespace Tests\Unit;

use App\Models\Image;
use App\Models\Item;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    public function testCreateProductWithItemsAndImages()
    {


        $productName = $this->faker->word;
        $productDescription = $this->faker->sentence;

        $itemData = [
            'price' => 10.99,
            'size' => 'M',
            'color' => 'Blue',
            'sku' => 'ffvdf4556',
        ];
        $imageData = [

            'image_1' => UploadedFile::fake()->image('image1.jpg'),
            'image_2' => UploadedFile::fake()->image('image2.jpg'),

        ];
        $this->validateProductData($productName, $productDescription, [$itemData], [$imageData]);
     
        
        $response = $this->postJson('/api/products/create', [
            'name' => $productName,
            'description' => $productDescription,
            'items' => [$itemData],
            'images' => [$imageData],
        ]);

       

        $product = Product::where('name', $productName)->first();

        $this->assertProductItemsAndImages($product, $productName, $productDescription, $itemData, $imageData);
    }

    protected function assertProductItemsAndImages($product, $name, $description, $itemData, $imageData)
    {
        $this->assertNotNull($product);
        $this->assertEquals($name, $product->name);
        $this->assertEquals($description, $product->description);

        $this->assertCount(1, $product->items);

        $item = $product->items->first();
        $this->assertEquals($itemData['price'], $item->price);
        $this->assertEquals($itemData['size'], $item->size);
        $this->assertEquals($itemData['color'], $item->color);

        $this->assertCount(1, $product->images);

        $updatedImages = $product->images->first();
        $image1Path = public_path('images/' . basename(parse_url(asset($updatedImages->image_1), PHP_URL_PATH)));
        $image2Path = public_path('images/' . basename(parse_url(asset($updatedImages->image_2), PHP_URL_PATH)));

        $this->assertFileExists($image1Path);
        $this->assertFileExists($image2Path);

    }
    public function testUpdateProductWithItemsAndImages()
    {

        $product = Product::create([
            'name' => 'Fake Product',
            'description' => 'Fake Description',
        ]);

        $item = Item::create([
            'product_id' => $product->id,
            'price' => 9.99,
            'size' => 'M',
            'color' => 'Blue',
            'sku' => 'fake_sku',
        ]);

        $image = Image::create([
            'product_id' => $product->id,
            'image_1' => 'images/fake_image1.jpg',
            'image_2' => 'images/fake_image2.jpg',
        ]);

        $newName = $this->faker->word;
        $newDescription = $this->faker->sentence;

        $newItemData = [
            'id' => $item->id,
            'price' => 15.99,
            'size' => 'L',
            'color' => 'Red',
            'sku' => 'fiubvhfib344',
        ];

        $newImage1 = UploadedFile::fake()->image('new_image1.jpg');
        $newImage2 = UploadedFile::fake()->image('new_image2.jpg');

        $response = $this->postJson("/api/update-product/{$product->id}", [
            'name' => $newName,
            'description' => $newDescription,
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

        $this->assertEquals($newName, $product->name);
        $this->assertEquals($newDescription, $product->description);

        $this->assertCount(1, $product->items);
        $updatedItem = $product->items->first();
        $this->assertEquals($newItemData['price'], $updatedItem->price);
        $this->assertEquals($newItemData['size'], $updatedItem->size);
        $this->assertEquals($newItemData['color'], $updatedItem->color);

        $this->assertCount(1, $product->images);
        $updatedImages = $product->images->first();

        $image1Path = public_path('images/' . basename(parse_url(asset($updatedImages->image_1), PHP_URL_PATH)));
        $image2Path = public_path('images/' . basename(parse_url(asset($updatedImages->image_2), PHP_URL_PATH)));

        $this->assertFileExists($image1Path);
        $this->assertFileExists($image2Path);
    }
    
    public function testGetAllProducts()
{
    $response = $this->get('/api/products/all');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'products' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'items' => [
                        '*' => [
                            'id',
                            'price',
                            'size',
                            'color',
                            'sku',  
                            'created_at',
                            'updated_at',
                        ],
                    ],
                    'images' => [
                        '*' => [
                            'id',
                            'product_id',
                            'image_1',
                            'image_2',
                            'created_at',
                            'updated_at',
                        ],
                    ],
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);


}
protected function validateProductData($name, $description, $items, $images, $responseCode)
{
    $response = $this->postJson('/api/products/create', [
        'name' => $name,
        'description' => $description,
        'items' => $items,
        'images' => $images,
    ]);


    $response->assertStatus($responseCode)
        ->assertJson([
            'status' => 'error',
            'message' => 'The given data was invalid.',
           
        ]);
     

}

    
   
}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Select Category</h1>
    @if($categories->isEmpty())
        <p>No categories found.</p>
    @else
     <form id="categoryForm" action="{{ route('products.by.category', ['categoryid' => ':categoryid']) }}" method="GET">
 
        
            <select id="categorySelect" name="category">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
     
        <button type="submit" id="submitButton">Submit</button>
    </form>
    @endif

    <script>
        document.getElementById('categoryForm').addEventListener('submit', function(event) {
            var selectedOption = document.getElementById('categorySelect').value;
            var actionUrl = "{{ route('products.by.category', ':categoryid') }}".replace(':categoryid', selectedOption);
            this.setAttribute('action', actionUrl);
        });
    </script>
</body>
</html>