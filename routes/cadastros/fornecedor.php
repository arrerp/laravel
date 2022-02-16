<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cadastros\FornecedorController;


Route::group(['middleware', 'auth:sanctum'], function() {
// Cadastro - Cliente
    Route::get('/cadastros/fornecedor/{id}', [FornecedorController::class, 'getById']);
    Route::post('/cadastros/fornecedor/{id}', [FornecedorController::class, 'store']);
    //Route::put('/cadastros/fornecedor/{id}', [FornecedorController::class, 'update']);
    //Route::delete('/cadastros/fornecedor/{id}', [FornecedorController::class, 'delete']);
});


