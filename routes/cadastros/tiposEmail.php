<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cadastros\TiposEmailController;

Route::group(['middleware', 'auth:sanctum'], function() {
    // Materiais - Familia
    Route::get('/cadastros/tiposEmail/', [TiposEmailController::class, 'index']);
    Route::post('/cadastros/tiposEmail/', [TiposEmailController::class, 'store']);
    Route::delete('/cadastros/tiposEmail/{id}', [TiposEmailController::class, 'delete']);
    Route::put('/cadastros/tiposEmail/{id}', [TiposEmailController::class, 'update']);
});


