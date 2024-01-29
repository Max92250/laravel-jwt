<?php
// database/factories/ImageFactory.php

namespace Database\Factories;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory
{
    protected $model = Image::class;

    public function definition()
    {
        $fakeImage = $this->faker->image(public_path('images'), 400, 300, null, false);

        return [
            'product_id' => Product::factory(),
            'image_path' =>  basename($fakeImage),
            'created_at' => now(),
            'updated_at' => now(),
        ];
        
    }
}
