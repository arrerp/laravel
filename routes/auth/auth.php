<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;

Route::middleware('auth:sanctum')->get('/auth/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth/register', [RegisterController::class, 'store'])->name('password.reset');
Route::post('/auth/login', [LoginController::class, 'index']);
Route::post('/auth/logout', [LogoutController::class, 'index']);






