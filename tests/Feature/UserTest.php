<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
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

    public function test_user_can_signout(): void
    {
        $this->get(route('api.signout'))->assertOk();
    }

    public function test_user_can_update_data(): void
    {
        $this->putJson(route('api.user.update'), [
            'name' => 'test updated',
            'email' => 'test_updated@gmail.com',
            'phone' => '1234567',
            'address' => 'Test address',
        ])->assertOk();

        $this->assertDatabaseHas('users', [
            'name' => 'test updated',
            'email' => 'test_updated@gmail.com',
            'phone' => '1234567',
            'address' => 'Test address',
        ]);
    }

    public function test_user_can_update_password(): void
    {
        $this->putJson(route('api.user.password.update'), [
            'old_password' => '123456',
            'password' => 'Dengo.Dionisie1996',
            'password_confirmation' => 'Dengo.Dionisie1996',
        ])->assertOk();

        $authResponse = $this->postJson(route('api.login'), [
            'email' => 'test_updated@gmail.com',
            'password' => 'Dengo.Dionisie1996',
            'device_name' => 'ios',
        ])->assertStatus(401);

        $this->assertArrayHasKey('errors', $authResponse->json());
    }

    public function test_user_can_update_avatar(): void
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->image('avatar.jpg');
        $this->postJson(route('api.user.avatar.update'), [
            'avatar' => $file,
        ])->assertOk();

        Storage::disk('public')->assertExists('/users/'.$file->hashName());
        Storage::disk('public')->delete('/users/'.$file->hashName());

    }
}
