<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Requests\UpdateAvatarRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $token = $this->userService->login($request->validated());
        if ($token) {
            return response()->json(['success' => true, 'token' => $token], 201);
        } else {
            return response()->json(['success' => false, 'message' => __('auth.token_error')], 401);
        }

    }

    public function register(RegistrationRequest $request): JsonResponse
    {
        $user = $this->userService->register($request->validated());

        return response()->json(['success' => true], 201);
    }

    public function signout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['success' => true], 200);
    }

    public function view(): JsonResponse
    {
        if (! Auth::user()) {
            throw new AuthenticationException(
                'Unauthenticated.'
            );
        }

        $user = User::where('id', Auth::user()->id)->first();

        if (! $user) {
            throw new AuthenticationException(
                'Unauthenticated.'
            );
        }

        return response()->json(['success' => true, 'data' => new UserResource($user)]);
    }

    public function update(UpdateUserRequest $request): JsonResponse
    {
        if (! Auth::user()) {
            throw new AuthenticationException(
                'Unauthenticated.'
            );
        }

        $user = $this->userService->update($request->validated());

        return response()->json(['success' => true, 'data' => new UserResource($user)]);
    }

    public function updateAvatar(UpdateAvatarRequest $request): JsonResponse
    {
        if (! Auth::user()) {
            throw new AuthenticationException(
                'Unauthenticated.'
            );
        }

        $user = $this->userService->updateAvatar($request->file('avatar'));

        return response()->json(['success' => true, 'data' => new UserResource($user)]);
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        if (! Auth::user()) {
            throw new AuthenticationException(
                'Unauthenticated.'
            );
        }

        $user = $this->userService->updatePassword($request->validated());

        return response()->json(['success' => true, 'data' => new UserResource($user)]);
    }
}
