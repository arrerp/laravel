<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Materiais\PrecoProdutoController;

Route::group(['middleware', 'auth:sanctum'], function() {
    // Materiais - Preço Produto - Preços Item
    Route::get('/materiais/precoProdutos/{id}', [PrecoProdutoController::class, 'index']);
    Route::get('/materiais/precoProduto/id/{id}', [PrecoProdutoController::class, 'getById']);
    Route::get('/materiais/precoProduto/{idTab}/getByTabId', [PrecoProdutoController::class, 'getByTabId']);
    Route::post('/materiais/precoProduto/{id}', [PrecoProdutoController::class, 'store']);
    Route::delete('/materiais/precoProduto/{id}', [PrecoProdutoController::class, 'delete']);
    Route::put('/materiais/precoProduto/{id}', [PrecoProdutoController::class, 'update']);    
    Route::get('/materiais/precoProduto/{idTab}/getPageInfo', [PrecoProdutoController::class, 'getPageInfo']);
});


