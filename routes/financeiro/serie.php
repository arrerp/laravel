<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Financeiro\SerieController;

Route::group(['middleware', 'auth:sanctum'], function() {
    Route::get('/financeiro/serie/', [SerieController::class, 'index']);
    Route::post('/financeiro/serie/', [SerieController::class, 'store']);
    Route::get('/financeiro/serie/{Emp}/{Serie}', [SerieController::class, 'getById']);
    Route::delete('/financeiro/serie/{Emp}/{Serie}', [SerieController::class, 'delete']);
    Route::put('/financeiro/serie/{emp}/{serie}', [SerieController::class, 'update']);
    Route::get('/financeiro/serie/getPageInfo', [SerieController::class, 'getPageInfo']);
});


