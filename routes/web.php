<?php

use Illuminate\Support\Facades\Route;



Auth::routes();

Route::get('/{any}', [App\Http\Controllers\HomeController::class, 'index'])
    ->where('any', "^(?!api).*$")
    ->middleware('auth') // responsible for authenticating the routes using sessions 
    // default guard is web 
    ->name('home');

// json api specifies standards for api creation.
// https://jsonapi.org/