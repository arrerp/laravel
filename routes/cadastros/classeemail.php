<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cadastros\ClasseEmailController;


Route::group(['middleware', 'auth:sanctum'], function() {
// Cadastro - Cadastros
    Route::get('cadastros/classes/', [ClasseEmailController::class, 'index']);
    Route::post('cadastros/classes/', [ClasseEmailController::class, 'store']);
    Route::delete('cadastros/classes/{id}', [ClasseEmailController::class, 'delete']);
    Route::put('cadastros/classes/{id}', [ClasseEmailController::class, 'update']);
    // Route::get('cadastros/cadastro/id/{id}', [CadastroController::class, 'getById']);
    // Route::get('cadastros/cadastro/cnpjCpf/{cnpjCpf}', [CadastroController::class, 'existsCnpjCpf']);
    Route::get('cadastros/classes/getPageInfo', [ClasseEmailController::class, 'getPageInfo']);
});


