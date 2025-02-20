<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return \App\Classes\ApiResponse::sendResponse(\App\Models\User::all(),'',);
});
