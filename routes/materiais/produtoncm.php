<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Materiais\ProdutoNCMController;


Route::group(['middleware', 'auth:sanctum'], function() {
    // Materiais - ProdutoNCM
    Route::get('/materiais/produtoncm/', [ProdutoNCMController::class, 'index']);
    Route::post('/materiais/produtoncm/', [ProdutoNCMController::class, 'store']);
    Route::delete('/materiais/produtoncm/{id}', [ProdutoNCMController::class, 'delete']);
    Route::put('/materiais/produtoncm/{id}', [ProdutoNCMController::class, 'update']);

  
});


