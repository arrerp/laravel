<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Logistica\TabelaFreteController;

Route::group(['middleware', 'auth:sanctum'], function() {
    Route::get('/logistica/tabelaFrete/idArquivo/{id}', [TabelaFreteController::class, 'index']);
    Route::post('/logistica/tabelaFrete/', [TabelaFreteController::class, 'store']);
    Route::get('/logistica/tabelaFrete/id/{id}', [TabelaFreteController::class, 'getById']);
    Route::delete('/logistica/tabelaFrete/{id}', [TabelaFreteController::class, 'delete']);
    Route::put('/logistica/tabelaFrete/{id}', [TabelaFreteController::class, 'update']);
    Route::get('/logistica/tabelaFrete/getPageInfo', [TabelaFreteController::class, 'getPageInfo']);
});


