<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/user/create', [UserController::class,'create']);
Route::post('/user/login', [UserController::class,'login']);
Route::get('/user', function (Request $request) {
    return \App\Classes\ApiResponse::sendResponse(User::all(),'',);
});

Route::middleware('jwt.auth')->group( function () {
    //users
    Route::get('/me', [UserController::class,'me']);
    Route::patch('/user/update/{user}',[UserController::class,'update']);
});
