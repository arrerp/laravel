<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UsuarioController;


Route::group(['middleware', 'auth:sanctum'], function() {
    // Admin - Usuario 
    Route::get('/adm/usuario/', [UsuarioController::class, 'index']);
    Route::post('/adm/usuario/', [UsuarioController::class, 'store']);
    Route::put('/adm/usuario/{id}', [UsuarioController::class, 'update']);
    Route::delete('/adm/usuario/{id}', [UsuarioController::class, 'delete']);
    Route::post('/adm/usuario/images', [UsuarioController::class, 'storePhoto']);
    Route::get('/adm/usuario/getPageInfo', [UsuarioController::class, 'getPageInfo']);
    Route::get('/adm/usuario/id/{id}', [UsuarioController::class, 'getById']);
});


