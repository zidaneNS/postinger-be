<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware("auth:sanctum");

Route::apiResource('posts', PostController::class);
Route::get('/myposts', [PostController::class, 'myposts']);

Route::post('/auth/login', [AuthController::class, 'login'])->name("login");
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware("auth:sanctum");