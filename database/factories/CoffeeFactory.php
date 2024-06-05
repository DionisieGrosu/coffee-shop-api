<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coffee>
 */
class CoffeeFactory extends Factory
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
            'description' => $this->faker->text(),
            'sorder' => $this->faker->randomElement([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], 1, true),
            'img' => env('APP_ENV') != 'testing' ? 'coffees/'.$this->faker->image(storage_path('app/public/coffees/'), 640, 480, false) : null,
            'topics' => $this->faker->word(),
            'is_active' => $this->faker->randomElement([0, 1]),
            'category_id' => Category::inRandomOrder()->first()->id,
        ];
    }
}
