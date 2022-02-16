<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customs\DynamicController;


Route::group(['middleware', 'auth:sanctum'], function() {
    Route::get('/customs/dynamic/routes/{model}', [DynamicController::class, 'getRoutes']);
});

