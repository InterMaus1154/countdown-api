<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Controllers\TimerController;

Route::group(['prefix' => 'auth', 'controller' => AuthController::class], function() {

    Route::get('/login', 'loginView')->name('auth.login.view');
    Route::post('/login', 'login')->name('auth.login');

    Route::middleware(AuthMiddleware::class)->post('/logout', 'logout')->name('auth.logout');
});

Route::middleware(AuthMiddleware::class)->group(function() {
    Route::get('/', [TimerController::class, 'index'])->name('timers.index');

});


