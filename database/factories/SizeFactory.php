<?php

namespace Database\Factories;

use App\Models\Coffee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Size>
 */
class SizeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 0, 1000),
            'sorder' => rand(1, $this->count - 1),
            'coffee_id' => Coffee::inRandomOrder()->first()->id,
        ];
    }
}
