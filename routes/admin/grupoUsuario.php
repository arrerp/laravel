<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\GrupoUsuarioController;


Route::group(['middleware', 'auth:sanctum'], function() {
    // Materiais - Depósito 
    Route::get('/adm/grupoUsuario/', [GrupoUsuarioController::class, 'index']);
    Route::post('/adm/grupoUsuario/', [GrupoUsuarioController::class, 'store']);
    Route::put('/adm/grupoUsuario/{id}', [GrupoUsuarioController::class, 'update']);
    Route::delete('/adm/grupoUsuario/{id}', [GrupoUsuarioController::class, 'delete']);
    Route::get('/adm/grupoUsuario/id/{id}', [GrupoUsuarioController::class, 'getById']);
});


