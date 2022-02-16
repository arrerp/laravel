<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Materiais\ProdutoComposicaoController;


Route::group(['middleware', 'auth:sanctum'], function() {
    // Materiais - Depósito 
    Route::get('/materiais/produtoComposicao/id/{id}', [ProdutoComposicaoController::class, 'getById']);
});


