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
    Route::get('/timers/new', [TimerController::class, 'create'])->name('timers.create');
    Route::post('/timers', [TimerController::class, 'store'])->name('timers.store');
    Route::delete('/timers/{id}', [TimerController::class, 'delete'])->name('timers.delete');
    Route::get('/timers/{id}/edit', [TimerController::class, 'edit'])->name('timers.edit');
    Route::put('/timers/{id}', [TimerController::class, 'update'])->name('timers.update');

});


