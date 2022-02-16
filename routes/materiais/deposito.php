<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Materiais\DepositoController;


Route::group(['middleware', 'auth:sanctum'], function() {
    // Materiais - Depósito 
    Route::get('/materiais/deposito/', [DepositoController::class, 'index']);
    Route::post('/materiais/deposito/', [DepositoController::class, 'store']);
    Route::put('/materiais/deposito/{id}', [DepositoController::class, 'update']);
    Route::delete('/materiais/deposito/{id}', [DepositoController::class, 'delete']);
    Route::get('/materiais/deposito/id/{id}', [DepositoController::class, 'getById']);
    Route::get('/materiais/deposito/Empresa/{id}', [DepositoController::class, 'getByIdEmp']);
    Route::get('/materiais/deposito/getPageInfo', [DepositoController::class, 'getPageInfo']);
});


