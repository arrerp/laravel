<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cadastros\EnderecoController;


Route::group(['middleware', 'auth:sanctum'], function() {
// Cadastro - Endereço 
    Route::get('/cadastros/endereco/{id}', [EnderecoController::class, 'index']);
    Route::post('/cadastros/endereco/{id}', [EnderecoController::class, 'store']);
    Route::post('/cadastros/endereco/padrao/{id}', [EnderecoController::class, 'endPadrao']);
    Route::put('/cadastros/endereco/{id}', [EnderecoController::class, 'update']);
    Route::delete('/cadastros/endereco/{id}', [EnderecoController::class, 'delete']);
    Route::get('/cadastros/endereco/id/{id}', [EnderecoController::class, 'getById']);
    Route::get('/cadastros/endereco/page/getPageInfo', [EnderecoController::class, 'getPageInfo']);
});


