<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Materiais\MovimentoEstoqueController;

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
    Route::get('/materiais/movimentoEstoque/pageInfo', [MovimentoEstoqueController::class, 'getPageInfo']);
    Route::get('/materiais/movimentoEstoque/itens/{id}', [MovimentoEstoqueController::class, 'getItensByMovimento']);
    Route::post('/materiais/movimentoEstoque/sugereLote', [MovimentoEstoqueController::class, 'sugereLote']);
    Route::get('/materiais/movimentoEstoque/getLotes/{id}', [MovimentoEstoqueController::class, 'getLotesByMovimento']);
    Route::get('/materiais/movimentoEstoque', [MovimentoEstoqueController::class, 'index']);
    Route::get('/materiais/movimentoEstoque/id/{id}', [MovimentoEstoqueController::class, 'getById']);    
    Route::post('/materiais/movimentoEstoque', [MovimentoEstoqueController::class, 'store']);
    Route::delete('/materiais/movimentoEstoque/{id}', [MovimentoEstoqueController::class, 'destroy']);
    Route::put('/materiais/movimentoEstoque/{id}', [MovimentoEstoqueController::class, 'update']);    
});


