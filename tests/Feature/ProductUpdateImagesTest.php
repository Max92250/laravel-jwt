<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Models\Item;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProductUpdateImagesTest extends TestCase
{
    use WithFaker;

    public function testUpdateImages()
    {

        $product = Product::factory()->create();
        $item = Item::factory(['product_id' => $product->id])->create();

        $existingImages = Image::factory(3)->create(['product_id' => $product->id]);

        $newImages = $this->createFakeImages(2);

        $response = $this->json('PUT', "/api/products/{$product->id}/update-images", [
            'images' => $newImages,
            'product_id' => $product->id,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'product_id' => $product->id,
            ]);

        foreach ($newImages as $newImage) {
            $this->assertDatabaseHas('images', [
                'image_path' => now()->timestamp . '_' . basename($newImage->getClientOriginalName()),
            ]);
            $this->assertFileExists(public_path("images/" . now()->timestamp . '_' . basename($newImage->getClientOriginalName())));

        }

    }

    private function createFakeImages($count)
    {
        $imagePaths = [];

        for ($i = 0; $i < $count; $i++) {
            $fakeImage = UploadedFile::fake()->image('fake_image_' . $i . '.jpg');
            $imagePaths[] = $fakeImage;
        }

        return $imagePaths;
    }
}
