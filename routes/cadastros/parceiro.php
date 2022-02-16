<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cadastros\ParceiroController;

Route::group(['middleware', 'auth:sanctum'], function() {
// Cadastro - Parceiro 
    //Route::get('/cadastros/parceiro/{id}', [ParceiroController::class, 'index']);
    Route::get('/cadastros/parceiro/{id}', [ParceiroController::class, 'getById']);
    Route::post('/cadastros/parceiro/{id}', [ParceiroController::class, 'store']);
    Route::delete('/cadastros/parceiro/{id}', [ParceiroController::class, 'delete']);
    Route::put('/cadastros/parceiro/{id}', [ParceiroController::class, 'update']);
});


