<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CoffeeResource;
use App\Models\Coffee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CoffeeController extends Controller
{
    public function list(Request $request): JsonResponse
    {
        $category = $request->query('category');
        $sorder = $request->query('sorder');

        if ($category) {
            return response()->json(['success' => true, 'data' => CoffeeResource::collection(Coffee::active()->where('category_id', $category)->orderBy('sorder', $sorder == 'desc' ? 'desc' : 'asc')->get())]);
        } else {
            return response()->json(['success' => true, 'data' => CoffeeResource::collection(Coffee::active()->orderBy('sorder', $sorder == 'desc' ? 'desc' : 'asc')->get())]);
        }
    }

    public function view(Coffee $coffee): JsonResponse
    {
        return response()->json(['success' => true, 'data' => new CoffeeResource($coffee)]);
    }
}
