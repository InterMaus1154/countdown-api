<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiTokenController;
use App\Http\Middleware\ValidateApiTokenMiddleware;
use App\Http\Controllers\ApiTimerController;

Route::post('/validate', [ApiTokenController::class, 'validate'])->middleware('throttle:3,1');

Route::group(['prefix' => 'timers', 'middleware' => ValidateApiTokenMiddleware::class, 'controller' => ApiTimerController::class], function() {

    Route::get('/', 'index');
    Route::get('/{uuid}', 'forUuid');
});
