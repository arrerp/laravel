<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Materiais\TipoMovimentoController;

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
    Route::get('/materiais/tipoMovimento', [TipoMovimentoController::class, 'index']);
    Route::get('/materiais/tipoMovimento/id/{id}', [TipoMovimentoController::class, 'getById']);    
    Route::post('/materiais/tipoMovimento', [TipoMovimentoController::class, 'store']);
    Route::delete('/materiais/tipoMovimento/{id}', [TipoMovimentoController::class, 'delete']);
    Route::put('/materiais/tipoMovimento/{id}', [TipoMovimentoController::class, 'update']);    
});