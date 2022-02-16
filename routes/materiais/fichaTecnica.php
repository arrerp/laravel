<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Materiais\ProdutoFichaTecnicaController;


Route::group(['middleware', 'auth:sanctum'], function() {
    // Materiais - Ficha Técnica
    Route::get('/materiais/fichaTecnica/', [ProdutoFichaTecnicaController::class, 'index']);
    Route::post('/materiais/fichaTecnica/', [ProdutoFichaTecnicaController::class, 'store']);
    Route::delete('/materiais/fichaTecnica/{id}', [ProdutoFichaTecnicaController::class, 'delete']);
    Route::put('/materiais/fichaTecnica/{id}', [ProdutoFichaTecnicaController::class, 'update']);
});


