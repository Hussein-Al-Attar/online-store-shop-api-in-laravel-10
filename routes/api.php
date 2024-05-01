<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\PostController;
use App\Http\Controllers\api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/post')->controller(PostController::class)->group(function (){
    Route::get('/','index');
    Route::get('/{id}','show');
    Route::post('/','store');
    Route::match(['put','patch'],'/{id}','update');
    Route::delete('/{id}','destroy');
});
Route::prefix('/user')->controller(UserController::class)->group(function (){
    Route::get('/','index');
    Route::get('/{id}','show');
    Route::post('/','store');
    Route::match(['put','patch'],'/{id}','update');
    Route::delete('/{id}','destroy');
});
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});
