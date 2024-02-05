<?php

use Illuminate\Support\Facades\Route;



Auth::routes();

Route::get('/{any}', [App\Http\Controllers\HomeController::class, 'index'])
    ->where('any', '.*')
    ->middleware('auth') // responsible for authenticating the routes using passport 
    //\Laravel\Passport\Http\Middleware\CreateFreshApiToken::class => defined in kernel.php => tells laravel to use passport

    ->name('home');

// json api specifies standards for api creation.
// https://jsonapi.org/