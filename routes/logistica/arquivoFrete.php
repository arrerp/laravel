<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Logistica\ArquivoFreteController;

Route::group(['middleware', 'auth:sanctum'], function() {
    Route::get('/logistica/arquivoFrete/', [ArquivoFreteController::class, 'index']);
    Route::post('/logistica/arquivoFrete/', [ArquivoFreteController::class, 'store']);
    Route::get('/logistica/arquivoFrete/id/{id}', [ArquivoFreteController::class, 'getById']);
    Route::delete('/logistica/arquivoFrete/{id}', [ArquivoFreteController::class, 'delete']);
    Route::get('/logistica/arquivoFrete/getPageInfo', [ArquivoFreteController::class, 'getPageInfo']);
});


