<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cadastros\ClienteController;


Route::group(['middleware', 'auth:sanctum'], function() {
// Cadastro - Cliente 
    Route::get('/cadastros/cliente/{id}', [ClienteController::class, 'getById']);
    Route::post('/cadastros/cliente/{id}', [ClienteController::class, 'store']);
    Route::put('/cadastros/cliente/{id}', [ClienteController::class, 'update']);
    Route::delete('/cadastros/cliente/{id}', [ClienteController::class, 'delete']);
});


