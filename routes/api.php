<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
    
// Auth routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Blog routes
 Route::middleware('auth:sanctum')->group(function () {
    Route::post('/blogs', [BlogController::class, 'store']);
    Route::get('/blogs', [BlogController::class, 'index']);
    Route::post('/blogs/{id}', [BlogController::class, 'update']);
    Route::delete('/blogs/{id}', [BlogController::class, 'destroy']);

    // like and unlike blog
    Route::post('/blogs/{id}/like', [BlogController::class, 'like']);
    Route::post('/blogs/{id}/unlike', [BlogController::class, 'unlike']);

    // get latest blog
    Route::get('/blogs/latest', [BlogController::class, 'latest']);
}); 

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
