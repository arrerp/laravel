<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cadastros\PessoalController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware', 'auth:sanctum'], function() {
// Cadastro - Pessoal 
    Route::get('/cadastros/pessoal/{id}', [PessoalController::class, 'getById']);
    Route::post('/cadastros/pessoal/{id}', [PessoalController::class, 'store']);
    Route::put('/cadastros/pessoal/{id}', [PessoalController::class, 'update']);
    Route::delete('/cadastros/pessoal/{id}', [PessoalController::class, 'delete']);
});


