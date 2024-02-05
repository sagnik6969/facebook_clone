<?php

use Illuminate\Support\Facades\Route;



Auth::routes();

Route::get('/{any}', [App\Http\Controllers\HomeController::class, 'index'])
    ->where('any', '.*')
    ->middleware('auth')
    ->name('home');
