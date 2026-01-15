<?php

use Illuminate\Support\Facades\Route;

// SPA Route - catch all routes and serve the Vue app
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
