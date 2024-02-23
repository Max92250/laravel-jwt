<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail,
            'password' => '$2y$10$zgUZO4SdLN.Emw96gtGcmOxYKLD1Z6e8qoz8JpXErHyR...m3KGW', // password
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'department' => $this->faker->word, // Adjust the type of data based on your needs
            'phone_number' => $this->faker->phoneNumber,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
          
           
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
