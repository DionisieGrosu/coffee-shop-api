<?php

namespace Database\Seeders;

use App\Models\Coffee;
use App\Models\Size;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Size::factory()->has(Coffee::factory()->count(10))->count(10)->create();
    }
}
