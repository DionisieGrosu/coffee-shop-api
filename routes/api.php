<?php

use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CoffeeController;
use App\Http\Controllers\Api\V1\FavoriteController;
use App\Http\Controllers\Api\V1\ReviewController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::middleware('auth:sanctum')->group(function () {

        Route::prefix('user')->group(function () {
            Route::get('view', [UserController::class, 'view'])->name('api.user.view');
            Route::put('update', [UserController::class, 'update'])->name('api.user.update');
            Route::post('avatar/update', [UserController::class, 'updateAvatar'])->name('api.user.avatar.update');
            Route::put('password/update', [UserController::class, 'updatePassword'])->name('api.user.password.update');
        });

        Route::prefix('category')->group(function () {
            Route::get('/', [CategoryController::class, 'list'])->name('api.category.list');
            Route::get('/{category}', [CategoryController::class, 'view'])->name('api.category.view');

        });

        Route::prefix('coffee')->group(function () {
            Route::get('/', [CoffeeController::class, 'list'])->name('api.coffee.list');
            Route::get('/{coffee}', [CoffeeController::class, 'view'])->name('api.coffee.view');

        });

        Route::prefix('cart')->group(function () {
            Route::get('view', [CartController::class, 'view'])->name('api.cart.view');
            Route::get('products', [CartController::class, 'products'])->name('api.cart.products');
            Route::post('add', [CartController::class, 'add'])->name('api.cart.add');
            Route::put('update-qty', [CartController::class, 'updateQty'])->name('api.cart.updateQty');
            Route::post('delete', [CartController::class, 'delete'])->name('api.cart.delete');
            Route::post('order', [CartController::class, 'order'])->name('api.cart.order');
            Route::delete('clear', [CartController::class, 'clear'])->name('api.cart.clear');
        });

        Route::prefix('review')->group(function () {
            Route::post('create', ReviewController::class)->name('api.review.create');
        });

        Route::prefix('favorite')->group(function () {
            Route::get('list', [FavoriteController::class, 'list'])->name('api.favorite.list');
            Route::get('list/products', [FavoriteController::class, 'listProducts'])->name('api.favorite.list.products');
            Route::post('add', [FavoriteController::class, 'add'])->name('api.favorite.add');
            Route::post('delete', [FavoriteController::class, 'delete'])->name('api.favorite.delete');
        });

        Route::get('signout', [UserController::class, 'signout'])->name('api.signout');

    });

    Route::post('authonticate', [UserController::class, 'login'])->name('api.login');
    Route::post('register', [UserController::class, 'register'])->name('api.register');

});
