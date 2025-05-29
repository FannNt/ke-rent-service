<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function(){
    return "Kerent API";
});

Route::get('/login', function(){
    return view("login");
})->name('login');
