<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cadastros\TransportadorController;


Route::group(['middleware', 'auth:sanctum'], function() {
// Cadastro - Cliente 
    Route::get('cadastros/transportador/id/{id}', [TransportadorController::class, 'getById']);
    Route::post('cadastros/transportador/{id}', [TransportadorController::class, 'store']);
    Route::put('cadastros/transportador/{id}', [TransportadorController::class, 'update']);
    Route::delete('cadastros/transportador/{id}', [TransportadorController::class, 'delete']);
    Route::get('cadastros/transportador/getPageInfo', [TransportadorController::class, 'getPageInfo']);
});


