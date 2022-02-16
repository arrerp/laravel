<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Venda\CondicaoVendaController;

Route::group(['middleware', 'auth:sanctum'], function() {
    Route::get('/venda/condicaoVenda/', [CondicaoVendaController::class, 'index']);
    Route::post('/venda/condicaoVenda/', [CondicaoVendaController::class, 'store']);
    Route::get('/venda/condicaoVenda/id/{id}', [CondicaoVendaController::class, 'getById']);
    Route::delete('/venda/condicaoVenda/{id}', [CondicaoVendaController::class, 'delete']);
    Route::put('/venda/condicaoVenda/{id}', [CondicaoVendaController::class, 'update']);
    Route::get('/venda/condicaoVenda/getPageInfo', [CondicaoVendaController::class, 'getPageInfo']);
});
