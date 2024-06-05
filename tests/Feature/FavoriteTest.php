<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Coffee;
use App\Models\Favorite;
use App\Models\Size;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FavoriteTest extends TestCase
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

    /**
     *  Test if user can add coffee to favorite
     */
    public function test_user_can_add_coffee_to_favorite(): void
    {
        $coffee = Coffee::active()->inRandomOrder()->first();

        $this->assertNotNull($coffee);
        $coffee_id = $coffee->id;
        $user_id = Auth::user()->id;

        $this->postJson(route('api.favorite.add'), [
            'coffee_id' => $coffee_id,
        ])->assertStatus(201);

        $this->assertDatabaseHas('favorites', ['user_id' => $user_id, 'coffee_id' => $coffee_id]);
    }

    /**
     *  Test if user can delete coffee from favorite
     */
    public function test_user_can_delete_coffee_from_favorite(): void
    {
        $coffee = Coffee::active()->inRandomOrder()->first();

        $this->assertNotNull($coffee);
        $coffee_id = $coffee->id;
        $user_id = Auth::user()->id;

        $favorite = Favorite::create(['coffee_id' => $coffee_id, 'user_id' => $user_id]);

        $this->assertNotNull($favorite);
        $this->assertNotFalse($favorite);

        $this->postJson(route('api.favorite.delete'), [
            'coffee_id' => $coffee_id,
        ])->assertOk();

        $this->assertDatabaseMissing('favorites', ['user_id' => $user_id, 'coffee_id' => $coffee_id]);
    }

    /**
     *  Test if user can retrive favorites
     */
    public function test_user_can_retrive_favorites(): void
    {
        $coffee = Coffee::active()->inRandomOrder()->first();

        $this->assertNotNull($coffee);
        $coffee_id = $coffee->id;
        $user_id = Auth::user()->id;

        $favorite = Favorite::create(['coffee_id' => $coffee_id, 'user_id' => $user_id]);

        $this->assertNotNull($favorite);
        $this->assertNotFalse($favorite);

        $response = $this->getJson(route('api.favorite.list'))->assertOk();

        $this->assertArrayHasKey('data', $response->json());
    }
}
