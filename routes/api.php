<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UniformeController;

Route::middleware('api')->group(function () {
    Route::get('/uniformes', [UniformeController::class, 'index']);
    Route::get('/uniformes/{id}', [UniformeController::class, 'show']);
    Route::post('/uniformes', [UniformeController::class, 'store']);
    Route::put('/uniformes/{id}', [UniformeController::class, 'update']);
    Route::delete('/uniformes/{id}', [UniformeController::class, 'destroy']);
});