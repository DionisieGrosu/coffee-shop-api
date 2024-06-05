<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        User::create([
            'name' => 'test',
            'email' => 'test@gmail.com',
            'password' => Hash::make('123456'),
        ]);
        $user = User::where('email', 'test@gmail.com')->first();

        Sanctum::actingAs($user);
    }

    /**
     * Test if can view all categories.
     */
    public function test_can_view_all_categories(): void
    {
        $response = $this->get(route('api.category.list'))->assertOk()->json();

        $this->assertArrayHasKey('data', $response);
    }

    /**
     * Test if can view all categories.
     */
    public function test_can_view_specific_categorys(): void
    {
        $response = $this->get(route('api.category.list'))->assertOk()->json();

        $this->assertArrayHasKey('data', $response);
    }
}
