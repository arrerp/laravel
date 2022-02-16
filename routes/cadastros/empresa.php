<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cadastros\EmpresaController;


Route::group(['middleware', 'auth:sanctum'], function() {
// Cadastro - Empresa 
    Route::get('/cadastros/empresa/{id}', [EmpresaController::class, 'getById']);
    Route::post('/cadastros/empresa/{id}', [EmpresaController::class, 'store']);
    Route::put('/cadastros/empresa/{id}', [EmpresaController::class, 'update']);
    Route::delete('/cadastros/empresa/{id}', [EmpresaController::class, 'delete']);
});


