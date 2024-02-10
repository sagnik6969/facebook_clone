<?php

use App\Http\Controllers\AuthUserController;
use App\Http\Controllers\FriendRequestController;
use App\Http\Controllers\FriendRequestResponseController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostLikeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->group(function () {


    Route::get('auth-user', [AuthUserController::class, 'show']);

    Route::apiResource('users', UserController::class);
    Route::apiResource('posts', PostController::class);
    Route::apiResource('users.posts', UserPostController::class);
    Route::apiResource('friend-request', FriendRequestController::class);
    Route::apiResource('friend-request-response', FriendRequestResponseController::class);
    Route::apiResource('/posts/{post}/like', PostLikeController::class);

});

