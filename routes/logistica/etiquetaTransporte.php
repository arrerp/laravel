<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Logistica\EtiquetaTransporteController;

Route::group(['middleware', 'auth:sanctum'], function() {
    Route::get('/logistica/etiquetaTransporte/', [EtiquetaTransporteController::class, 'index']);
    Route::post('/logistica/etiquetaTransporte/', [EtiquetaTransporteController::class, 'store']);
    Route::get('/logistica/etiquetaTransporte/id/{id}', [EtiquetaTransporteController::class, 'getById']);
    Route::delete('/logistica/etiquetaTransporte/{id}', [EtiquetaTransporteController::class, 'delete']);
    Route::put('/logistica/etiquetaTransporte/{id}', [EtiquetaTransporteController::class, 'update']);
    Route::get('/logistica/etiquetaTransporte/getPageInfo', [EtiquetaTransporteController::class, 'getPageInfo']);
});


