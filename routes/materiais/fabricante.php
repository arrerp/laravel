<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Materiais\FabricantesController;


Route::group(['middleware', 'auth:sanctum'], function() {
    // Materiais - Fabricantes
    Route::get('/materiais/fabricante/', [FabricantesController::class, 'index']);
    Route::post('/materiais/fabricante/', [FabricantesController::class, 'store']);
    Route::delete('/materiais/fabricante/{id}', [FabricantesController::class, 'delete']);
    Route::put('/materiais/fabricante/{id}', [FabricantesController::class, 'update']);
});


