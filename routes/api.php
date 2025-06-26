<?php

use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Feed\FeedController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Authentication Routes
Route::post('register', [AuthenticationController::class, 'register']);
Route::post('login', [AuthenticationController::class, 'login']);
Route::middleware('auth:sanctum')->post('logout', [AuthenticationController::class, 'logout']);
Route::middleware('auth:sanctum')->get('getInfo', [AuthenticationController::class, 'getInfo']);

// Feed Routes (Requires Auth)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/feeds', [FeedController::class, 'index']);
    Route::post('/feed/store', [FeedController::class, 'store']);
    Route::post('/feed/like/{feed_id}', [FeedController::class, 'likePost']);
    Route::post('/feed/comment/{feed_id}', [FeedController::class, 'comment']);
    Route::get('/feed/comments/{feed_id}', [FeedController::class, 'getComments']);
    Route::get('notifications', [FeedController::class, 'getNotifications']);
});

// Public Feed Route
Route::get('/feeds/search', [FeedController::class, 'search']);