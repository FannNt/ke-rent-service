<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController; 

Route::post('/user/create', [UserController::class,'create']);
Route::post('/user/login', [UserController::class,'login']);

Route::middleware('jwt.auth')->group( function () {
    //users
    Route::get('/me', [UserController::class,'me']);
    Route::patch('/user/update/{user}',[UserController::class,'update']);
    Route::get('/{user}/product',[ProductController::class, 'findUserProduct']);

    //product
    Route::get('/product', [ProductController::class,'index']);
    Route::get('/product/{id}', [ProductController::class, 'findById']);
    Route::post('/product/create', [ProductController::class, 'create']);
    Route::patch('/product/update/{id}', [ProductController::class, 'update']);
    Route::delete('/product/delete/{id}', [ProductController::class,'delete']);

    // transaction
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::post('/transactions', [TransactionController::class, 'create']);
    Route::get('/transactions/{id}', [TransactionController::class, 'findById']);
    Route::put('/transactions/{id}', [TransactionController::class, 'update']);
    Route::delete('/transactions/{id}', [TransactionController::class, 'delete']);
    Route::get('/user/{userId}/transactions', [TransactionController::class, 'getByUserId']);

    // payment
    Route::post('/payment', [PaymentController::class, 'create']);
    Route::put('/payment/{id}', [PaymentController::class, 'update']);
    Route::get('/payment/{id}', [PaymentController::class, 'findById']);
});
