<?php

// database/factories/ItemFactory.php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'price' => $this->faker->randomFloat(2, 1, 100),
            'size' => $this->faker->randomElement(['S', 'M', 'L']),
            'color' => $this->faker->colorName,
            'sku' => 'SKU_' . now()->timestamp . $this->faker->randomNumber(5)
       
        ];
    }
}
