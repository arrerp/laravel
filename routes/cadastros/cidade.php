<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cadastros\CidadeController;


Route::group(['middleware', 'auth:sanctum'], function() {
// Cadastro - Cidade 
    Route::get('/cadastros/cidade/', [CidadeController::class, 'index']);
    Route::post('/cadastros/cidade/', [CidadeController::class, 'store']);
    Route::delete('/cadastros/cidade/{id}', [CidadeController::class, 'delete']);
    Route::put('/cadastros/cidade/{id}', [CidadeController::class, 'update']);
    Route::get('/cadastros/cidade/id/{id}', [CidadeController::class, 'getById']);
    Route::get('/cadastros/cidade/getPageInfo', [CidadeController::class, 'getPageInfo']);
    Route::get('/cadastros/cidade/ibge/{ibge}', [CidadeController::class, 'getCidadeByIbge']);
});


