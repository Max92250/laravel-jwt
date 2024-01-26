<?php
// database/factories/ImageFactory.php

namespace Database\Factories;

use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;

class ImageFactory extends Factory
{
    protected $model = Image::class;

    public function definition()
    {
        return [
            'image_1' => 'images/' . $this->faker->image('public/images', 400, 300, null, false),
            'image_2' => 'images/' . $this->faker->image('public/images', 400, 300, null, false),
            // other fields...
        ];
    }
}
