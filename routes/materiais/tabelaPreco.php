<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Materiais\TabelaPrecoController;

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
    // Materiais - Tabela Preços
    Route::get('/materiais/tabelaPreco/{id}', [TabelaPrecoController::class, 'index']);
    Route::get('/materiais/tabelaPreco/id/{id}', [TabelaPrecoController::class, 'getById']);    
    Route::post('/materiais/tabelaPreco/{id}', [TabelaPrecoController::class, 'store']);
    Route::delete('/materiais/tabelaPreco/{id}', [TabelaPrecoController::class, 'delete']);
    Route::put('/materiais/tabelaPreco/{id}', [TabelaPrecoController::class, 'update']);    
    Route::get('/materiais/tabelaPreco/empresa/getPageInfo', [TabelaPrecoController::class, 'getPageInfo']);
});


