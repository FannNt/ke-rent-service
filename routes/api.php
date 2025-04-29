<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/user/create', [UserController::class,'create']);
Route::post('/user/login', [UserController::class,'login']);

Route::middleware('jwt.auth')->group( function () {
    //users
    Route::get('/me', [UserController::class,'me']);
    Route::patch('/user/update/{user}',[UserController::class,'update']);
    Route::get('/{user}/product',[ProductController::class, 'findUserProduct']);
    Route::post('/user/logout',[UserController::class,'logout']);

    //product
    Route::get('/product', [ProductController::class,'index']);
    Route::get('/product/{id}', [ProductController::class, 'findById']);
    Route::post('/product/create', [ProductController::class, 'create']);
    Route::patch('/product/update/{id}', [ProductController::class, 'update']);
    Route::delete('/product/delete/{id}', [ProductController::class,'delete']);

});
