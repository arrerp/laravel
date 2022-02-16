<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cadastros\CadastroController;


Route::group(['middleware', 'auth:sanctum'], function() {
// Cadastro - Cadastros 
    Route::get('cadastros/cadastro/', [CadastroController::class, 'index']);
    Route::post('cadastros/cadastro/', [CadastroController::class, 'store']);
    Route::delete('cadastros/cadastro/{id}', [CadastroController::class, 'delete']);
    Route::put('cadastros/cadastro/{id}', [CadastroController::class, 'update']);
    Route::get('cadastros/cadastro/id/{id}', [CadastroController::class, 'getById']);
    Route::get('cadastros/cadastro/cnpjCpf/{cnpjCpf}', [CadastroController::class, 'existsCnpjCpf']);
    Route::get('cadastros/cadastro/getPageInfo', [CadastroController::class, 'getPageInfo']);
});


