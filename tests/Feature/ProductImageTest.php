<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProductImageTest extends TestCase
{
    use WithFaker;

    public function test_images()
    {

        $imageFiles = [
            UploadedFile::fake()->image('image1.jpg'),
            UploadedFile::fake()->image('image2.png'),
        ];

        $response = $this->postJson("/api/products/images", [
            'images' => $imageFiles,
            'product_id' => 1,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
            ]);

        $product = Product::find(1);

        $images = $product->images;

        foreach ($images as $image) {
            $this->assertDatabaseHas('images', [
                'id' => $image->id,
                'product_id' => $product->id,
                'image_path' => $image->image_path,
            ]);

            $this->assertFileExists(public_path("images/{$image->image_path}"));

            $response->assertJsonStructure([
                'status',
                'product_id',
            ]);
    
        }

    }

}
