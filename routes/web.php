<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return redirect('/admin');
})->name('login');
