<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Coffee;
use App\Models\Size;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        Category::factory()->has(Coffee::factory()->has(Size::factory()->count(10))->count(10))->count(10)->create();
        User::create([
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => Hash::make('123456'),
        ]);
        $user = User::where('email', 'test@gmail.com')->first();

        Sanctum::actingAs($user);
    }

    public function test_user_can_send_review(): void
    {
        $coffee = Coffee::active()->inRandomOrder()->first();

        $this->assertNotNull($coffee);
        $this->postJson(route('api.review.create'), [
            'rate' => 5,
            'coffee_id' => $coffee->id,
        ])->assertStatus(201);

        $this->assertDatabaseHas('reviews', ['coffee_id' => $coffee->id, 'rate' => 5]);
    }
}
