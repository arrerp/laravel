<?php

namespace App\Models\Materiais;

use Illuminate\Database\Eloquent\Model;
use App\Util\Util;

class ProdutoCategoria extends Model
{
    public $timestamps = false;

    protected $table = 'ProdutoCategoria';

    protected $fillable = [
        'categoria',
        'id_categoriaPai',
        'dt_registro',
        'situacao'
    ];

    static function index($top = 1000){
        $categorias = ProdutoCategoria::leftJoin('ProdutoCategoria AS CategoriaPai', 'ProdutoCategoria.id_categoriaPai', '=', 'CategoriaPai.id_categoria')
                                        ->take($top)
                                        ->lock('WITH(NOLOCK)')
                                        ->selectRaw("ProdutoCategoria.id_categoria,
                                                     ProdutoCategoria.categoria,
                                                     CASE WHEN ProdutoCategoria.situacao = 'A' THEN 'Ativo' ELSE 'Inativo' END situacao,
                                                     ProdutoCategoria.dt_registro,
                                                     CategoriaPai.categoria as categoria_pai")
                                      ->get();

        return $categorias;
    }

    static function getAtivos($top = 1000){
        $categorias = ProdutoCategoria::where('ProdutoCategoria.situacao', 'A')
                                      ->leftJoin('ProdutoCategoria AS CategoriaPai', 'ProdutoCategoria.id_categoriaPai', '=', 'CategoriaPai.id_categoria')
                                      ->select([
                                          'ProdutoCategoria.id_categoria',
                                          'ProdutoCategoria.categoria',
                                          'ProdutoCategoria.situacao',
                                          'ProdutoCategoria.dt_registro',
                                          'ProdutoCategoria.id_categoriaPai',
                                          'CategoriaPai.categoria as categoria_pai',
                                      ])
                                      ->take($top)
                                      ->lock('WITH(NOLOCK)')
                                      ->orderBy('ProdutoCategoria.id_categoriaPai')
                                      ->get();

        return $categorias;
    }

    static function getWhere($categoria){
        $categorias = ProdutoCategoria::where('categoria', $categoria)->lock('WITH(NOLOCK)')->get();

        return $categorias;
    }

    static function getWhereNot($id){
        $categorias = ProdutoCategoria::where('id_categoria', '<>', $id)->lock('WITH(NOLOCK)')->get();

        return $categorias;
    }

    static function getById($id){
        $categoria = ProdutoCategoria::where('id_categoria', $id)
                                     ->lock('WITH(NOLOCK)')
                                     ->first();

        return $categoria;
    }

    static function store($request)
    {
        $response = false;

        if(!ProdutoCategoria::existsItem($request->categoria)){
            $response = ProdutoCategoria::insertGetId([
                'categoria' => $request->categoria,
                'id_categoriaPai' => $request->idCategoriaPai,
                'situacao' => $request->situacao,
                'dt_registro' => Now('America/Fortaleza'),
            ]);
        }

        return $response;
    }

    static function existsItem($categoria)
    {
        return ProdutoCategoria::where('categoria', $categoria)->exists();
    }


    static function updateItem($id_categoria, $request){
        $update = false;

        if(!ProdutoCategoria::existsItem($id_categoria)){
            $update = ProdutoCategoria::where('id_categoria', $id_categoria)->update([
                'categoria' => $request->categoria,
                'situacao' => $request->situacao,
                'id_categoriaPai' => $request->idCategoriaPai,
            ]);
        }

        return $update;
    }

    static function updateSituacao($id){
        $update = false;
            
        $update = ProdutoCategoria::where('id_categoria', $id)->update([
            'situacao' => 'X'
        ]);

        return $update;
    }      

    static function deleteItem($id){
        try {
            $delete = ProdutoCategoria::where('id_categoria', $id)->delete();
            } catch(\Illuminate\Database\QueryException $ex){ 
                $delete = $ex; 
            }
        return $delete;
    } 

    static function getTableInfo(){
        $model = new self();            

        return Util::getTableInfo($model);
    }      
}
