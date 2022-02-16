<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Materiais\TransformaProdutoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware', 'auth:sanctum'], function() {
    Route::get('/materiais/transformaProduto/pageInfo', [TransformaProdutoController::class, 'getPageInfo']);
    Route::get('/materiais/transformaProduto/itens/{id}', [TransformaProdutoController::class, 'getItensByMovimento']);
    Route::post('/materiais/transformaProduto/sugereLote', [TransformaProdutoController::class, 'sugereLote']);
    Route::get('/materiais/transformaProduto', [TransformaProdutoController::class, 'index']);
    Route::get('/materiais/transformaProduto/id/{id}', [TransformaProdutoController::class, 'getById']);    
    Route::post('/materiais/transformaProduto', [TransformaProdutoController::class, 'store']);
    Route::delete('/materiais/transformaProduto/{id}', [TransformaProdutoController::class, 'destroy']);
    Route::put('/materiais/transformaProduto/{id}', [TransformaProdutoController::class, 'update']);    
});


