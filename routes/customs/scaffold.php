<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customs\ScaffoldController;


Route::group(['middleware', 'auth:sanctum'], function() {
    Route::get('/customs/scaffold/tables', [ScaffoldController::class, 'getTables']);
    Route::get('/customs/scaffold/routes/{model}', [ScaffoldController::class, 'getRoutes']);
    Route::get('/customs/scaffold/columns/{table}', [ScaffoldController::class, 'getTableColumns']);
    Route::post('/customs/scaffold/createAndImport', [ScaffoldController::class, 'createAndImport']);
    Route::post('/customs/scaffold/relationships', [ScaffoldController::class, 'viewConstruct']);
    Route::post('/customs/scaffold/exec', [ScaffoldController::class, 'execQuery']);
});

