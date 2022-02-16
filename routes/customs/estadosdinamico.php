<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customs\EstadosDinamicoController;


Route::group(['middleware', 'auth:sanctum'], function() {
    Route::get('/customs/estadosdinamico', [EstadosDinamicoController::class, 'index']);
    Route::get('/customs/estadosdinamico/columns', [EstadosDinamicoController::class, 'getColumns']);
    Route::get('/customs/estadosdinamico/id/{id}', [EstadosDinamicoController::class, 'getById']);   
    Route::post('/customs/estadosdinamico', [EstadosDinamicoController::class, 'store']);
    Route::put('/customs/estadosdinamico/{id}', [EstadosDinamicoController::class, 'updateItem']);   
    Route::delete('/customs/estadosdinamico/{id}', [EstadosDinamicoController::class, 'destroy']);
});

