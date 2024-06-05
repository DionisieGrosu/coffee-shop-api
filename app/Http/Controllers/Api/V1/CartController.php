<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\CommonException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CartAddRequest;
use App\Http\Requests\CartDeleteRequest;
use App\Http\Requests\CartUpdateQtyRequest;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\CartProductsResource;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;

class CartController extends Controller
{
    private CartService $cartService;

    public function __construct(CartService $service)
    {
        $this->cartService = $service;
    }

    public function add(CartAddRequest $request): JsonResponse
    {
        $cart = $this->cartService->add($request->validated());

        $total = Number::format($this->cartService->total(), 2, 2);
        $count = $this->cartService->count();

        return response()->json(['success' => true, 'data' => CartResource::collection($cart), 'total' => $total, 'count' => $count], 201);
    }

    public function updateQty(CartUpdateQtyRequest $request): JsonResponse
    {
        $cart = $this->cartService->updateQty($request->validated());

        $total = Number::format($this->cartService->total(), 2, 2);
        $count = $this->cartService->count();

        return response()->json(['success' => true, 'data' => CartResource::collection($cart), 'total' => $total, 'count' => $count]);
    }

    public function delete(CartDeleteRequest $request): JsonResponse
    {
        $cart = $this->cartService->delete($request->validated());

        $total = Number::format($this->cartService->total(), 2, 2);
        $count = $this->cartService->count();

        return response()->json(['success' => true, 'data' => CartResource::collection($cart), 'total' => $total, 'count' => $count]);
    }

    public function clear(): JsonResponse
    {
        $cleared = $this->cartService->clear();
        if (! $cleared) {
            throw new CommonException('Could not clear cart', 500);
        }

        $total = Number::format($this->cartService->total(), 2, 2);
        $count = $this->cartService->count();

        return response()->json(['success' => true, 'total' => $total, 'count' => $count]);
    }

    public function view(): JsonResponse
    {
        $user_id = Auth::user()->id;

        $total = Number::format($this->cartService->total(), 2, 2);
        $count = $this->cartService->count();

        return response()->json(['success' => true, 'data' => CartResource::collection(Cart::where('user_id', $user_id)->get()), 'total' => $total, 'count' => $count]);
    }

    public function products(): JsonResponse
    {
        $user_id = Auth::user()->id;

        $total = Number::format($this->cartService->total(), 2, 2);
        $count = $this->cartService->count();

        return response()->json(['success' => true, 'data' => CartProductsResource::collection(Cart::where('user_id', $user_id)->get()), 'total' => $total, 'count' => $count]);
    }

    public function order(OrderRequest $request): JsonResponse
    {
        $order = $this->cartService->order($request->validated());
        if (! $order) {
            throw new CommonException('Could not create order', 500, $request->validated());
        }

        return response()->json(['success' => true, 'message' => 'Your order is proccessing! Soon customer will contact you.', 'data' => $order], 201);
    }
}
