<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiTokenController;

Route::post('/validate', [ApiTokenController::class, 'validate'])->middleware('throttle:3,1');
