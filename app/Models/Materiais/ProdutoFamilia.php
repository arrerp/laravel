<?php

namespace App\Models\Materiais;

use Illuminate\Database\Eloquent\Model;

class ProdutoFamilia extends Model
{
    protected $table = 'ProdutoFamilia';

    public $timestamps = false;

    protected $fillable = [
        'familia'
    ];

    static function index($top = 1000){
        $familias = ProdutoFamilia::take($top)->lock('WITH(NOLOCK)')->get();

        return $familias;
    }

    static function getWhere($familia){
        $familias = ProdutoFamilia::where('familia', $familia)->lock('WITH(NOLOCK)')->get();

        return $familias;
    }

    static function store($familia)
    {
        $response = false;

        if(!ProdutoFamilia::existsItem($familia)){
            $response = ProdutoFamilia::insertGetId([
                'familia' => $familia
            ]);
        }

        return $response;
    }

    static function existsItem($familia)
    {
        return ProdutoFamilia::where('familia', $familia)->exists();
    }

    static function updateItem($id_familia, $familia){
        $update = false;

        if(!ProdutoFamilia::existsItem($id_familia)){
            $update = ProdutoFamilia::where('id_familia', $id_familia)->update([
                'familia' => $familia
            ]);
        }

        return $update;
    }

    static function deleteItem($id_familia){
        try {
            $delete = ProdutoFamilia::where('id_familia', $id_familia)->delete();
            } catch(\Illuminate\Database\QueryException $ex){ 
                $delete = $ex; 
            }
        return $delete;
    }
}
