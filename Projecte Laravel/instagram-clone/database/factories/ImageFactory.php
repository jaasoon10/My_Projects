<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'     => 1, // se sobreescribirá en el seeder
            'image_path'  => 'images/img' . fake()->numberBetween(1, 10) . '.jpg',
            'description' => fake()->sentence(),
        ];
    }
}
