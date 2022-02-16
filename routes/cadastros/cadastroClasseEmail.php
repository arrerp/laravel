<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cadastros\CadastroClasseEmailController;


Route::group(['middleware', 'auth:sanctum'], function() {
// Cadastro - Cadastros
    Route::get('cadastros/cadastroclasseemail/{id}', [CadastroClasseEmailController::class, 'index']);
    Route::post('cadastros/cadastroclasseemail/', [CadastroClasseEmailController::class, 'store']);
    Route::delete('cadastros/cadastroclasseemail/{id}', [CadastroClasseEmailController::class, 'delete']);
   // Route::put('cadastros/classes/{id}', [ClasseEmailController::class, 'update']);
    // Route::get('cadastros/cadastro/id/{id}', [CadastroController::class, 'getById']);
    // Route::get('cadastros/cadastro/cnpjCpf/{cnpjCpf}', [CadastroController::class, 'existsCnpjCpf']);
    //Route::get('cadastros/classes/getPageInfo', [ClasseEmailController::class, 'getPageInfo']);
});


