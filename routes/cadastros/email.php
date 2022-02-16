<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cadastros\ClienteEmailController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware', 'auth:sanctum'], function() {
// Cadastro - Pessoal 
    Route::get('/cadastros/email/{id}', [ClienteEmailController::class, 'getById']);
    Route::post('/cadastros/email/{id}', [ClienteEmailController::class, 'store']);
    Route::put('/cadastros/email/{id}', [ClienteEmailController::class, 'update']);
    Route::delete('/cadastros/email/{id}', [ClienteEmailController::class, 'delete']);
});


