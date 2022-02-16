<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Materiais\DepositoUsuariosController;


Route::group(['middleware', 'auth:sanctum'], function() {
    // Materiais - Depósito x Usuário
    Route::get('/materiais/depositoUsuarios/{idDep}', [DepositoUsuariosController::class, 'index']);
    Route::post('/materiais/depositoUsuarios/{idDep}', [DepositoUsuariosController::class, 'store']);
    Route::put('/materiais/depositoUsuarios/{idDep}/{idUsu}', [DepositoUsuariosController::class, 'updateItem']);
    Route::delete('/materiais/depositoUsuarios/{idDep}/{idUsu}', [DepositoUsuariosController::class, 'delete']);
    //Route::get('/materiais/depositoUsuarios/{idDep}/{idUsu}', [CidadeController::class, 'getPageInfo']);
    Route::get('/materiais/depositoUsuarios/id/{idDep}/{idUsu}', [DepositoUsuariosController::class, 'getById']);
    Route::get('/materiais/depositoUsuarios/getUsers/{idDep}', [DepositoUsuariosController::class, 'getUsers']);
});
