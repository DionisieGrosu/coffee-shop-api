<?php

namespace App\Services;

use App\Exceptions\CommonException;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderCoffee;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class CartService
{
    public function add(array $cartData): ?Collection
    {
        $cartData['user_id'] = Auth::user()->id;

        $checkIfNotExists = Cart::where('coffee_id', $cartData['coffee_id'])->where('size_id', $cartData['size_id'])->where('user_id', $cartData['user_id'])->first();
        $cart = $checkIfNotExists;
        if (! $checkIfNotExists) {
            $cart = Cart::create($cartData);
        }

        if (! $cart->exists()) {
            throw new CommonException('Could not add coffee to cart', 500, $cartData);
        }

        $cartList = Cart::where('user_id', $cartData['user_id'])->get();

        return $cartList;
    }

    public function updateQty(array $cartData): ?Collection
    {
        $cartData['user_id'] = Auth::user()->id;
        $checkIfNotExists = Cart::where('coffee_id', $cartData['coffee_id'])->where('size_id', $cartData['size_id'])->where('user_id', $cartData['user_id'])->first();

        if ($checkIfNotExists) {
            if ($cartData['qt'] > 0) {
                $checkIfNotExists->qt = $cartData['qt'];
                $checkIfNotExists->save();
            } else {
                $checkIfNotExists->delete();
            }
        }

        if (! $checkIfNotExists) {
            throw new CommonException('Could not update cart qty', 500, $cartData);
        }

        $cartList = Cart::where('user_id', $cartData['user_id'])->get();

        return $cartList;
    }

    public function total(): ?float
    {
        $cartData['user_id'] = Auth::user()->id;
        $checkIfNotExists = Cart::where('user_id', $cartData['user_id'])->with('size')->get();
        $sum = 0;
        if ($checkIfNotExists) {
            foreach ($checkIfNotExists as $cartItem) {
                $sum += $cartItem->size->price * $cartItem->qt;
            }
        }

        return $sum;
    }

    public function count(): ?int
    {
        $cartData['user_id'] = Auth::user()->id;
        $count = Cart::where('user_id', $cartData['user_id'])->get()->count();

        return $count;
    }

    public function delete(array $cartData): ?Collection
    {
        $cartData['user_id'] = Auth::user()->id;
        $checkIfNotExists = Cart::where('coffee_id', $cartData['coffee_id'])->where('size_id', $cartData['size_id'])->where('user_id', $cartData['user_id'])->first();
        $deleted = true;
        if ($checkIfNotExists) {
            $deleted = $checkIfNotExists->delete();
        }

        if (! $deleted) {
            throw new CommonException('Could not delete cart item', 500, $cartData);
        }

        $cartList = Cart::where('user_id', $cartData['user_id'])->get();

        return $cartList;
    }

    public function clear(): bool
    {
        $user_id = Auth::user()->id;
        $deleted = Cart::where('user_id', $user_id)->delete();

        return $deleted;
    }

    public function order(array $orderData): ?Order
    {
        $orderData['user_id'] = Auth::user()->id;
        $orderData['order_id'] = rand(1, 10000000);
        $orderData['total_price'] = $this->total();

        $cart = Cart::with('size')->where('user_id', $orderData['user_id'])->get();

        if (! $cart->isNotEmpty()) {
            throw new CommonException('Cart is empty', 500, $orderData);
        }

        $order = Order::create($orderData);

        if (! $order) {
            throw new CommonException('Order was not created', 500, $orderData);
        }

        $orderItems = [];

        foreach ($cart as $cart_item) {
            $tmpArray = [
                'order_id' => $order->id,
                'coffee_id' => $cart_item->coffee_id,
                'size_id' => $cart_item->size_id,
                'price' => $cart_item->size->price,
                'qt' => $cart_item->qt,
            ];
            $orderItems[] = $tmpArray;
        }

        $orderCoffee = OrderCoffee::insert($orderItems);

        if (! $orderCoffee) {
            throw new CommonException('Order items was not created', 500, $orderData);
        }

        $this->clear();

        return $order;
    }
}
