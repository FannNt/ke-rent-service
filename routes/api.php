<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PaymentController;

Route::post('/user/create', [UserController::class,'create']);
Route::post('/user/login', [UserController::class,'login']);
Route::post('/user/phoneLogin',[UserController::class,'loginWithNumber']);
Route::post('/midtrans/callback', [MidtransController::class,'callback']);

Route::middleware('jwt.verify')->group( function () {

    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [UserController::class,'show']);
        Route::get('/transactions', [TransactionController::class, 'index']);

        //terms
        Route::post('/terms/add', [AdminController::class,'addTerms']);
        Route::patch('/terms/{id}/edit',[AdminController::class, 'editTerms']);
        Route::delete('/terms/{id}/remove', [AdminController::class, 'removeTerms']);
    });

    //users
    Route::post('/user/upload', [UserController::class, 'uploadKtp']);
    Route::get('/me', [UserController::class,'me']);
    Route::patch('/user/update/{user}',[UserController::class,'update']);
    Route::post('/user/logout',[UserController::class,'logout']);

    //product
    Route::get('/product', [ProductController::class,'index']);
    Route::get('/product/me',[ProductController::class, 'findUserProduct']);
    Route::get('/product/{id}', [ProductController::class, 'findById']);
    Route::get('/product/user/{userId}',[ProductController::class,'findProductByUserId']);
    Route::post('/product/create', [ProductController::class, 'create']);
    Route::patch('/product/update/{id}', [ProductController::class, 'update']);
    Route::delete('/product/delete/{id}', [ProductController::class,'delete']);

    // transaction
    Route::post('/transactions', [TransactionController::class, 'create']);
    Route::get('/transactions/{id}', [TransactionController::class, 'findById']);
    Route::post('/transaction/approve/{id}', [TransactionController::class, 'acceptTransaction']);
    Route::post('/transaction/reject/{id}', [TransactionController::class, 'rejectTransaction']);
    Route::get('/user/transactions', [TransactionController::class, 'transactionHistory']);
    Route::post('/transaction/{id}', [TransactionController::class,'addBill']);
    Route::post('/transaction/{id}/rating', [TransactionController::class,'rating']);

    // payment
    Route::post('/payment/{id}', [PaymentController::class, 'pay']);
//    Route::put('/payment/{id}', [PaymentController::class, 'update']);
//    Route::get('/payment/{id}', [PaymentController::class, 'findById']);

    //chat
    Route::post('/chat', [ChatController::class, 'create']);
    Route::get('/chat', [ChatController::class, 'show']);
    Route::post('/chat/{chat}/messages', [ChatController::class,'sendMessage']);
    Route::get('/chat/{chat}/messages', [ChatController::class, 'getMessages']);

    //notification
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notification/{id}/read',[NotificationController::class,'readNotification']);
    Route::get('/notification/count',[NotificationController::class, 'count']);
});
