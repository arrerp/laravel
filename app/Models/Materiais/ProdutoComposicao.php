<?php

namespace App\Models\Materiais;

use Illuminate\Database\Eloquent\Model;

class ProdutoComposicao extends Model
{
    public $timestamps = false;

    protected $table = 'ProdutoComposicao';

    protected $fillable = [
        'id_produtoOri',
        'id_produtoDes',
        'qt_aplicada',
    ];

    static function index($top = 1000){
        $composicoes = ProdutoComposicao::join('Produto AS ProdutoOri', 'ProdutoComposicao.id_produtoOri', '=', 'ProdutoOri.id_produto')
                                       ->join('Produto AS ProdutoDes', 'ProdutoComposicao.id_produtoDes', '=', 'ProdutoDes.id_produto')
                                       ->select([
                                          'ProdutoOri.id_produto idProdOrigem', 
                                          'ProdutoOri.SKU        skuOrigem', 
                                          'ProdutoOri.descricao  descOrigem',
                                          'ProdutoDes.id_produto idProdDestino', 
                                          'ProdutoDes.SKU        skuDestino', 
                                          'ProdutoDes.descricao  descDestino',
                                          'ProdutoComposicao.qt_aplicada'
                                      ])
                                      ->take($top)
                                      ->lock('WITH(NOLOCK)')
                                      ->get();

        return $composicoes;
    }

    static function getById($idProdutoOri){
        $composicao = ProdutoComposicao::where('id_produtoOri', $idProdutoOri)
                                       ->join('Produto', 'ProdutoComposicao.id_produtoDes', 'Produto.id_produto')
                                       ->lock('WITH(NOLOCK)')
                                       ->get();

        return $composicao;
    }

    static function store($request)
    {
        $response = false;

        if(!ProdutoComposicao::existsItem($request->idProdutoOri, $request->idProdutoDes)){
            $response = ProdutoComposicao::insertGetId([
                'id_produtoOri' => $request->idProdutoOri,
                'id_produtoDes' => $request->idProdutoDes,
                'qt_aplicada'   => $request->qtAplicada
            ]);
        }

        return $response;
    }

    static function existsItem($idProdutoOri, $idProdutoDes)
    {
        return ProdutoComposicao::where('id_produtoOri', $idProdutoOri)
                                ->where('id_produtoDes', $idProdutoDes)
                                ->exists();
    }


    static function deleteItem($idProdutoOri, $idProdutoDes){
        $delete = ProdutoComposicao::where('id_produtoOri', $idProdutoOri)
                                    ->where('id_produtoDes', $idProdutoDes)
                                    ->delete();

        return $delete;
    }

    static function deleteAllItem($idProdutoOri){
        $delete = ProdutoComposicao::where('id_produtoOri', $idProdutoOri)
                                   ->delete();

        return $delete;
    }
}
