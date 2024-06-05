<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\FavoriteRequest;
use App\Http\Resources\FavoriteProductsResource;
use App\Http\Resources\FavoriteResource;
use App\Models\Favorite;
use App\Services\FavoriteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    private $favoriteService;

    public function __construct(FavoriteService $service)
    {
        $this->favoriteService = $service;
    }

    public function add(FavoriteRequest $request): JsonResponse
    {
        $favorites = $this->favoriteService->add($request->validated());

        return response()->json(['success' => true, 'data' => FavoriteResource::collection($favorites)], 201);
    }

    public function delete(FavoriteRequest $request): JsonResponse
    {
        $favorites = $this->favoriteService->delete($request->validated());

        return response()->json(['success' => true, 'data' => FavoriteResource::collection($favorites)]);
    }

    public function list(): JsonResponse
    {
        $user_id = Auth::user()->id;
        $favorites = Favorite::where('user_id', $user_id)->get();

        return response()->json(['success' => true, 'data' => FavoriteResource::collection($favorites)]);
    }

    public function listProducts(): JsonResponse
    {
        $user_id = Auth::user()->id;
        $favorites = Favorite::where('user_id', $user_id)->get();

        return response()->json(['success' => true, 'data' => FavoriteProductsResource::collection($favorites)]);
    }
}
