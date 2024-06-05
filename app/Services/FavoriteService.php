<?php

namespace App\Services;

use App\Exceptions\CommonException;
use App\Models\Favorite;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class FavoriteService
{
    public function add(array $favoriteData): ?Collection
    {
        $favoriteData['user_id'] = Auth::user()->id;

        $checkIfNotExists = Favorite::where('user_id', $favoriteData['user_id'])->where('coffee_id', $favoriteData['coffee_id'])->first();

        if (! $checkIfNotExists) {
            $favorite = Favorite::create($favoriteData);

            if (! $favorite) {
                throw new CommonException('Could not add coffee to favorites.', 500, $favoriteData);
            }
        }

        $favorites = Favorite::with(['coffee' => function ($query) {
            return $query->where('is_active', 1);
        }])->where('user_id', $favoriteData['user_id'])->get();

        // dd($favorites);

        return $favorites;
    }

    public function delete(array $favoriteData): ?Collection
    {
        $favoriteData['user_id'] = Auth::user()->id;

        Favorite::where('user_id', $favoriteData['user_id'])->where('coffee_id', $favoriteData['coffee_id'])->delete();

        $favorites = Favorite::with(['coffee' => function ($query) {
            return $query->where('is_active', 1);
        }])->where('user_id', $favoriteData['user_id'])->get();

        return $favorites;
    }
}
