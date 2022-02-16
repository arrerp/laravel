<?php

namespace App\Models\Materiais;

use Illuminate\Database\Eloquent\Model;

class ProdutoCategoriaItem extends Model
{
    public $timestamps = false;

    protected $table = 'ProdutoCategoriaItem';

    protected $fillable = [
        'id_produto',
        'id_categoria'
    ];

    static function index($idProduto){
        $categorias = ProdutoCategoriaItem::where('ProdutoCategoriaItem.id_produto', $idProduto)
                                          ->join('ProdutoCategoria', 'ProdutoCategoriaItem.id_categoria', '=', 'ProdutoCategoria.id_categoria')
                                          ->select([
                                              'ProdutoCategoria.id_categoria',
                                              'ProdutoCategoria.categoria',
                                              'ProdutoCategoria.situacao',
                                              'ProdutoCategoria.dt_registro',
                                          ])
                                          ->lock('WITH(NOLOCK)')
                                          ->get();

        return $categorias;
    }

    static function store($request)
    {
        $response = false;

        if(!ProdutoCategoriaItem::existsItem($request)){
            $response = ProdutoCategoriaItem::insertGetId([
                'id_produto' => $request->idProduto,
                'id_categoria' => $request->idCategoria
            ]);
        }

        return $response;
    }

    static function deleteItem($idProdCategoria){
        
        $delete = ProdutoCategoriaItem::where('id_prodCategoria', $idProdCategoria)->delete();

        return $delete;
    }

    static function deleteAllItem($idProduto){
        
        $delete = ProdutoCategoriaItem::where('id_produto', $idProduto)->delete();

        return $delete;
    }

    static function existsItem($request)
    {
        return ProdutoCategoriaItem::where('id_produto', $request->idProduto)
                                   ->where('id_categoria', $request->idCategoria)
                                   ->exists();
    }
}
