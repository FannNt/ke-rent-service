<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminPanelController;

Route::get('/', function(){
    return "Kerent API";
});

Route::get('/admin', [AdminPanelController::class, 'index']);
Route::post('/admin/login', [AdminPanelController::class, 'login'])->name('admin.login');

Route::middleware(['jwt.verify', 'role:admin'])->group(function () {
    Route::get('/home', [AdminPanelController::class, 'showHome'])->name('admin.page');
    Route::get('/users', [AdminPanelController::class, 'getUsers'])->name('users');
    Route::get('/users/search', [AdminPanelController::class, 'searchUsers'])->name('users.search');
    Route::post('/users/ban/{id}', [AdminPanelController::class, 'banUser'])->name('users.ban');
});