<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Materiais\ProdutoController;

Route::group(['middleware', 'auth:sanctum'], function() {
    // Materiais - Produto 
    routesERP();
    routesEcommerce();
});


function routesERP() {
    Route::get('/materiais/produto/', [ProdutoController::class, 'index']);
    Route::get('/materiais/produto/images/{id}', [ProdutoController::class, 'getImagesById']);
    Route::post('/materiais/produto/', [ProdutoController::class, 'store']);
    Route::post('/materiais/produto/patch', [ProdutoController::class, 'update']);
    Route::post('/materiais/produto/images', [ProdutoController::class, 'storeImages']);
    Route::get('/materiais/produto/id/{id}', [ProdutoController::class, 'getbyId']);
    Route::get('/materiais/produto/sku/{sku}', [ProdutoController::class, 'getbySku']);
    Route::get('/materiais/produto/getPageInfo', [ProdutoController::class, 'getPageInfo']);
    Route::get('/materiais/produto/buscaProduto/{produto}', [ProdutoController::class, 'buscaProduto']);
}

function routesEcommerce() {
    Route::get('/products/{id}', [ProdutoController::class, 'getProdutoSite']);
}