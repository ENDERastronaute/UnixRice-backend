<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;

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

Route::get('/user/add', [UserController::class, 'addDiscord']);
Route::get('/user/{id}', [UserController::class, 'get']);
Route::get('/users', [UserController::class, 'getAll']);
Route::post('/user', [UserController::class, 'store']);

Route::get('/post/{id}', [PostController::class,'get']);
Route::get('/posts/{channel}', [PostController::class, 'getAll']);
Route::post('/post', [PostController::class, 'store']);
Route::put('/post', [PostController::class,'update']);
Route::delete('/post', [PostController::class,'delete']);