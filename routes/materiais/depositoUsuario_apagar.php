<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Materiais\DepositoUsuarioController;


Route::group(['middleware', 'auth:sanctum'], function() {
    // Materiais - Depósito x Usuário
    Route::get('/materiais/depositoUsuario/{idDep}', [DepositoUsuarioController::class, 'index']);
    Route::post('/materiais/depositoUsuario/{idDep}', [DepositoUsuarioController::class, 'store']);
    Route::put('/materiais/depositoUsuario/{idDep}/{idUsu}', [DepositoUsuarioController::class, 'update']);
    Route::delete('/materiais/depositoUsuario/{idDep}/{idUsu}', [DepositoUsuarioController::class, 'delete']);
    //Route::get('/materiais/depositoUsuario/id/{id}', [DepositoUsuarioController::class, 'getById']);
    Route::get('/materiais/depositoUsuario/{idDep}/getUsers', [DepositoUsuarioController::class, 'getUsers']);
    Route::get('/materiais/depositoUsuario/{idDep}/{idUsuario}/getUser', [DepositoUsuarioController::class, 'getUser']);
});

