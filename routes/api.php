<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UniformeController;
use App\Http\Controllers\UniformeFotoController;

Route::middleware('api')->group(function () {
    // Rutas para Uniformes
    Route::get('/uniformes', [UniformeController::class, 'index']);
    Route::get('/uniformes/{id}', [UniformeController::class, 'show']);
    Route::post('/uniformes', [UniformeController::class, 'store']);
    Route::put('/uniformes/{id}', [UniformeController::class, 'update']);
    Route::delete('/uniformes/{id}', [UniformeController::class, 'destroy']);

    // Ruta para eliminar fotos individuales
    Route::delete('/fotos/{id}', [UniformeFotoController::class, 'destroy'])->name('fotos.destroy');
});
