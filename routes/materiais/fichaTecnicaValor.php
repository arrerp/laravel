<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Materiais\FichaTecnicaValorController;


Route::group(['middleware', 'auth:sanctum'], function() {
    // Materiais - Valores Ficha Técnica
    Route::get('/materiais/fichaTecnicaValor/{id}', [FichaTecnicaValorController::class, 'index']);
    Route::post('/materiais/fichaTecnicaValor/', [FichaTecnicaValorController::class, 'store']);
    Route::delete('/materiais/fichaTecnicaValor/{id}', [FichaTecnicaValorController::class, 'delete']);
    Route::put('/materiais/fichaTecnicaValor/{id}', [FichaTecnicaValorController::class, 'update']);
});


