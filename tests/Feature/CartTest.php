<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Coffee;
use App\Models\Size;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    private $cartService;

    public function setUp(): void
    {
        parent::setUp();

        $this->cartService = new CartService();

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
     * Test that user can add to cart.
     */
    public function test_can_add_to_cart(): void
    {
        $coffee = Coffee::inRandomOrder()->first();
        $sizes = $coffee->sizes()->limit(1)->get();

        $this->assertNotNull($coffee);
        $this->assertNotNull($sizes);

        $size = $sizes[0];
        // dd($size);
        $response = $this->postJson(route('api.cart.add'), [
            'coffee_id' => $coffee->id,
            'size_id' => $size->id,
            'qt' => rand(1, 9),
        ])->assertStatus(201);

        $this->assertArrayHasKey('data', $response->json());

    }

    /**
     * Test if user can update qty of item in the cart
     */
    public function test_can_update_qty_of_cart_item(): void
    {
        $coffee = Coffee::inRandomOrder()->first();
        $sizes = $coffee->sizes()->limit(1)->get();

        $this->assertNotNull($coffee);
        $this->assertNotNull($sizes);

        $size = $sizes[0];

        $this->assertNotNull($size);
        // dd($size);

        $this->assertAuthenticated();
        Cart::create([
            'coffee_id' => $coffee->id,
            'size_id' => $size->id,
            'user_id' => Auth::user()->id,
            'qt' => rand(1, 9),
        ]);

        $this->assertDatabaseHas('carts', ['coffee_id' => $coffee->id, 'size_id' => $size->id]);
        $cartItem = Cart::all()->random();

        $this->assertNotNull($cartItem);
        $response = $this->putJson(route('api.cart.updateQty'), [
            'coffee_id' => $cartItem->coffee_id,
            'size_id' => $cartItem->size_id,
            'qt' => 10,
        ])->assertOk();

        $this->assertDatabaseHas('carts', ['qt' => 10]);

        $this->assertArrayHasKey('data', $response->json());

    }

    /**
     * Test if total calculating is right
     */
    public function test_total_of_cart(): void
    {
        $coffee = Coffee::inRandomOrder()->first();
        $sizes = $coffee->sizes()->limit(1)->get();

        $this->assertNotNull($coffee);
        $this->assertNotNull($sizes);

        $size = $sizes[0];

        $this->assertNotNull($size);
        $this->assertNotNull($size->price);
        $price = $size->price;

        Cart::create([
            'coffee_id' => $coffee->id,
            'size_id' => $size->id,
            'user_id' => Auth::user()->id,
            'qt' => 2,
        ]);

        $total = $this->cartService->total();
        $this->assertEquals($total, $price * 2);
    }

    public function test_user_can_make_order(): void
    {
        $coffee = Coffee::inRandomOrder()->first();
        $sizes = $coffee->sizes()->limit(1)->get();

        $this->assertNotNull($coffee);
        $this->assertNotNull($sizes);

        $size = $sizes[0];

        $this->assertNotNull($size);
        $this->assertNotNull($size->price);
        $price = $size->price;

        Cart::create([
            'coffee_id' => $coffee->id,
            'size_id' => $size->id,
            'user_id' => Auth::user()->id,
            'qt' => 2,
        ]);

        $name = fake()->sentence();
        $email = fake()->email();
        $phone = fake()->phoneNumber();
        $address = fake()->address();
        $this->postJson(route('api.cart.order'), [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
        ])->assertStatus(201);

        $this->assertDatabaseHas('orders', [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
        ]);

        $this->assertDatabaseHas('order_coffees', [
            'coffee_id' => $coffee->id,
            'size_id' => $size->id,
            'price' => $price,
            'qt' => 2,
        ]);

    }
}
