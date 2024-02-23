<?php
use Tests\TestCase;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProductImageTest extends TestCase
{
    use WithFaker;
    use DatabaseTransactions;

    public function test_createimages()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $imageFiles = [
            UploadedFile::fake()->image('image1.jpg'),
            UploadedFile::fake()->image('image2.png'),
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson("/api/products/images", [
            'images' => $imageFiles,
            'product_id' => 3,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
            ]);

        $this->validateImages($response, 3, $imageFiles);
    }

    public function test_invalid_image_format()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $invalidImage = UploadedFile::fake()->create('invalid.pdf', 500);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson("/api/products/images", [
            'images' => [$invalidImage],
            'product_id' => 3,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'images.0' => 'The images.0 must be an image.',
            ]);
    }

    public function test_no_images_given()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson("/api/products/images", [
            'product_id' => 3,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'images' => 'The images field is required.',
            ]);
    }

   

    protected function validateImages($response, $productId, $imageFiles)
    {
        $product = Product::find($productId);
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
