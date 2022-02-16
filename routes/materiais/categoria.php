<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Materiais\ProdutoCategoriaController;


Route::group(['middleware', 'auth:sanctum'], function() {
    Route::get('/materiais/categoria/', [ProdutoCategoriaController::class, 'index']);
    Route::post('/materiais/categoria/', [ProdutoCategoriaController::class, 'store']);
    Route::get('/materiais/categoria/{id}', [ProdutoCategoriaController::class, 'getById']);
    Route::delete('/materiais/categoria/{id}', [ProdutoCategoriaController::class, 'delete']);
    Route::put('/materiais/categoria/{id}', [ProdutoCategoriaController::class, 'update']);
    Route::post('/materiais/categoria/getPageInfo', [ProdutoCategoriaController::class, 'getPageInfo']);
});


