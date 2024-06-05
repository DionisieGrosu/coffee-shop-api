<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Coffee;
use Illuminate\Database\Seeder;

class CoffeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Coffee::factory()->has(Category::factory()->count(10))->count(10)->create();
    }
}
