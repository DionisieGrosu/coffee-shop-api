<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\CommonException;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddReviewRequest;
use App\Http\Resources\CoffeeResource;
use App\Models\Coffee;
use App\Services\ReviewService;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    private $reviewService;

    public function __construct(ReviewService $service)
    {
        $this->reviewService = $service;
    }

    public function __invoke(AddReviewRequest $request): JsonResponse
    {

        $this->reviewService->create($request->validated());
        $coffee = Coffee::active()->where('id', $request->coffee_id)->first();

        if (! $coffee) {
            throw new CommonException('Could not found coffee by id', 404, $$request->validated());
        }

        return response()->json(['success' => true, 'message' => 'Your review was created', 'data' => new CoffeeResource($coffee)], 201);
    }
}
