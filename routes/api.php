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
Route::delete('/user/{id}', [UserController::class, 'destroy']);
Route::patch('/user/{id}', [UserController::class, 'update']);
Route::get('/users', [UserController::class, 'getAll']);
Route::post('/user', [UserController::class, 'store']);
ROUTE::GET('/login', [UserController::class, 'login']);

Route::post('/post/{id}/vote', [PostController::class, 'vote']);
Route::get('/post/{id}', [PostController::class,'get']);
Route::get('/posts/trending', [PostController::class, 'getTrending']);
Route::get('/posts/{channel}', [PostController::class, 'getAll']);
Route::post('/post', [PostController::class, 'store']);
Route::patch('/post/{id}', [PostController::class,'update']);
Route::delete('/post/{id}', [PostController::class,'destroy']);