<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;
use App\Models\User;
class ValidateProductCreateTest extends TestCase
{

    use WithFaker;
    use DatabaseTransactions;

    public function testValidateProductData()
    {


        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $token = JWTAuth::fromUser($user);

        // Missing 'name'
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/products/create', [
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

        //missing description

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);

            $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->postJson('/api/products/create', [
            'name' => 'rrr',
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

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['description']);

        //missing items
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/products/create', [
            'name' => 'rrr',
            'description' => 'its good',
            'items' => [
                ['size' => 'XL', 'color' => 'Red'],

            ],

            'images' => [
                [
                    'image_1' => UploadedFile::fake()->create('image1.png'),
                    'image_2' => UploadedFile::fake()->create('image2.jpg'),
                ],
            ],
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['items.0.price']);



        // Missing image_1 in one image set
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/products/create', [
        'name' => 'Missing Image_1',
        'description' => 'Description',
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
                'image_2' => UploadedFile::fake()->create('image2.jpg'),
            ],
        ],
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'images.0.image_1',
        ]);

    // Missing image_2 in one image set
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->postJson('/api/products/create', [
        'name' => 'Missing Image_2',
        'description' => 'Description',
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
            ],
        ],
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'images.0.image_2',
        ]);

    // Missing both image_1 and image_2 in one image set
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->postJson('/api/products/create', [
        'name' => 'Missing Both Images',
        'description' => 'Description',
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
                // No image_1 or image_2 provided
            ],
        ],
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'images.0.image_1',
            'images.0.image_2',
        ]);

        //invalid image file
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/products/create', [
            'name' => 'Invalid Image Type',
            'description' => 'Description',
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
                    'image_1' => UploadedFile::fake()->create('image1.pdf'),
                    'image_2' => UploadedFile::fake()->create('image2.txt'),
                ],
            ],
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'images.0.image_1',
                'images.0.image_2',
            ]);

            $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->postJson('/api/products/create', [
            'name' => 'Valid Product',
            'description' => 'Valid description',
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

        $response->assertStatus(201)
            ->assertJson(['status' => 'success', 'product_id' => true]);

    }
}
