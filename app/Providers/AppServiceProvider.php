<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Coffee;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (! $this->app->environment('production')) {
            $this->app->register('App\Providers\FakerServiceProvider');
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::bind('category', function ($value) {
            return Category::active()->findOrFail($value);

        });

        Route::bind('coffee', function ($value) {
            return Coffee::active()->findOrFail($value);

        });

    }
}
