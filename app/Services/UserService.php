<?php

namespace App\Services;

use App\Exceptions\ServerErrorException;
use App\Exceptions\UserValidationException;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService
{
    public function login(array $userData): string|null|UserValidationException
    {
        $user = User::where('email', $userData['email'])->first();

        if (! $user || ! Hash::check($userData['password'], $user->password)) {
            throw UserValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        $token = $user->createToken($userData['device_name'])->plainTextToken;

        return $token;
    }

    public function register(array $userData): ?string
    {
        $user = User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'phone' => $userData['phone'],
            'address' => $userData['address'],
            'password' => Hash::make($userData['password']),
        ]);

        if (! $user) {
            throw new ServerErrorException(__('general.registration_faild'));
        }

        return $user;

    }

    public function update(array $userData): ?User
    {
        $user = User::where('id', Auth::user()->id)->first();
        $user->name = $userData['name'];
        $user->email = $userData['email'];
        $user->phone = $userData['phone'];
        $user->address = $userData['address'];

        $user->save();

        return $user;
    }

    public function updateAvatar(UploadedFile $avatar): ?User
    {
        $user = User::where('id', Auth::user()->id)->first();
        // $path = $avatar->store('users');
        $path = Storage::disk('public')->putFile('users', $avatar);

        $user->avatar = $path;
        $user->save();

        return $user;
    }

    public function updatePassword(array $userData): ?User
    {
        $user = User::where('id', Auth::user()->id)->first();

        $check_password = Hash::check($userData['old_password'], $user->password);
        if (! $check_password) {
            throw new AuthenticationException(
                'Unauthenticated.'
            );
        }

        $user->password = Hash::make($userData['password']);
        $user->save();

        return $user;
    }
}
