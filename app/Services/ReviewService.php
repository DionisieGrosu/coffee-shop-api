<?php

namespace App\Services;

use App\Exceptions\CommonException;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewService
{
    public function create(array $reviewData): Review
    {
        $reviewData['user_id'] = Auth::user()->id;

        $checkIfNootExists = Review::where('user_id', $reviewData['user_id'])->where('coffee_id', $reviewData['coffee_id'])->first();

        $newReview = $checkIfNootExists;
        if (! $checkIfNootExists) {
            $newReview = Review::create($reviewData);

            if (! $newReview) {
                throw new CommonException('Could not create new review', 500, $reviewData);
            }
        }

        return $newReview;

    }
}
