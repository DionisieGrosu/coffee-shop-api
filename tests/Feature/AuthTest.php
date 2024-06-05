<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
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
    }

    public function test_login_authontication_return_sucess_response(): void
    {
        $authResponse = $this->postJson(route('api.login'), [
            'email' => 'test@gmail.com',
            'password' => '123456',
            'device_name' => 'ios',
        ])->assertStatus(201);

        $this->assertArrayHasKey('token', $authResponse->json());
    }

    public function test_login_authontication_return_error(): void
    {
        $authResponse = $this->postJson(route('api.login'), [
            'email' => 'test@gmail.com',
            'password' => '123',
            'device_name' => 'ios',
        ])->assertStatus(401);

        $this->assertArrayHasKey('errors', $authResponse->json());
    }
}
