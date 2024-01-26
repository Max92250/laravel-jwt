<?php

namespace Tests\Unit;
use App\Models\User;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;



class ValidateProductCreateTest extends TestCase

{


    use WithFaker;
    use DatabaseTransactions;
    
    public function testValidateProductData()
    {
        // Missing 'name'
        $response = $this->postJson('/api/products/create', [
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

        $response = $this->postJson('/api/products/create', [
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
            $response = $this->postJson('/api/products/create', [
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
            
        
                $response = $this->postJson('/api/products/create', [
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
