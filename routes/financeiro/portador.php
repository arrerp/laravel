<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Financeiro\PortadorController;

Route::group(['middleware', 'auth:sanctum'], function() {
    Route::get('/financeiro/portador/', [PortadorController::class, 'index']);
    Route::post('/financeiro/portador/', [PortadorController::class, 'store']);
    Route::get('/financeiro/portador/id/{id}', [PortadorController::class, 'getById']);
    Route::delete('/financeiro/portador/{id}', [PortadorController::class, 'delete']);
    Route::put('/financeiro/portador/{id}', [PortadorController::class, 'update']);
    Route::get('/financeiro/portador/getPageInfo', [PortadorController::class, 'getPageInfo']);
});
