<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Logistica\TipoTransporteController;

Route::group(['middleware', 'auth:sanctum'], function() {
    Route::get('/logistica/tipoTransporte/', [TipoTransporteController::class, 'index']);
    Route::post('/logistica/tipoTransporte/', [TipoTransporteController::class, 'store']);
    Route::get('/logistica/tipoTransporte/id/{id}', [TipoTransporteController::class, 'getById']);
    Route::delete('/logistica/tipoTransporte/{id}', [TipoTransporteController::class, 'delete']);
    Route::put('/logistica/tipoTransporte/{id}', [TipoTransporteController::class, 'update']);
    Route::get('/logistica/tipoTransporte/getPageInfo', [TipoTransporteController::class, 'getPageInfo']);
});


