<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\FuncaoUsuarioController;


Route::group(['middleware', 'auth:sanctum'], function() {
    // Materiais - Depósito 
    Route::get('/adm/funcaoUsuario/', [FuncaoUsuarioController::class, 'index']);
    Route::post('/adm/funcaoUsuario/', [FuncaoUsuarioController::class, 'store']);
    Route::put('/adm/funcaoUsuario/{id}', [FuncaoUsuarioController::class, 'update']);
    Route::delete('/adm/funcaoUsuario/{id}', [FuncaoUsuarioController::class, 'delete']);
    // Route::get('/adm/grupoUsuario/id/{id}', [GrupoUsuarioController::class, 'getById']);
});


