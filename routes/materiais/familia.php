<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Materiais\ProdutoFamiliaController;

Route::group(['middleware', 'auth:sanctum'], function() {
    // Materiais - Familia
    Route::get('/materiais/familia/', [ProdutoFamiliaController::class, 'index']);
    Route::post('/materiais/familia/', [ProdutoFamiliaController::class, 'store']);
    Route::delete('/materiais/familia/{id}', [ProdutoFamiliaController::class, 'delete']);
    Route::put('/materiais/familia/{id}', [ProdutoFamiliaController::class, 'update']);
});


