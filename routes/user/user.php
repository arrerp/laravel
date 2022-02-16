<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;


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
    Route::post('/user/update', [UserController::class, 'update']);
    Route::get('/user/{id}', [UserController::class, 'getById']);
    Route::post('/user/image', [UserController::class, 'storeImage']);
});
