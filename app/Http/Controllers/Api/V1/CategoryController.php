<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function list(Request $request): JsonResponse
    {
        $sorder = $request->query('sorder');
        if ($sorder == 'desc') {
            return response()->json(['success' => true, 'data' => CategoryResource::collection(Category::active()->orderBy('sorder', 'desc')->get())]);
        } else {
            return response()->json(['success' => true, 'data' => CategoryResource::collection(Category::active()->orderBy('sorder', 'asc')->get())]);
        }

    }

    public function view(Category $category): JsonResponse
    {
        return response()->json(['success' => true, 'data' => new CategoryResource($category)]);
    }
}
