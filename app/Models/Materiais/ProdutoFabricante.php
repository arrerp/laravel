<?php

namespace App\Models\Materiais;

use Illuminate\Database\Eloquent\Model;

class ProdutoFabricante extends Model
{

    protected $table = 'ProdutoFabricante';

    public $timestamps = false;

    protected $fillable = [
        'fabricante'
    ];

    static function index($top = 1000){
        $fabricantes = ProdutoFabricante::take($top)
                                        ->lock('WITH(NOLOCK)')
                                        ->get();

        return $fabricantes;
    }

    static function getWhere($fabricante){
        $fabricantes = ProdutoFabricante::where('fabricante', $fabricante)
                                        ->lock('WITH(NOLOCK)')
                                        ->get();

        return $fabricantes;
    }

    
    static function getById($id){
        $fabricantes = ProdutoFabricante::where('id_fabricante', $id)
                                        ->lock('WITH(NOLOCK)')
                                        ->first();

        return $fabricantes;
    }

    static function store($fabricante)
    {
        $response = false;

        if(!ProdutoFabricante::existsItem($fabricante)){
            $response = ProdutoFabricante::insertGetId([
                'fabricante' => $fabricante
            ]);
        }

        return $response;
    }

    static function existsItem($fabricante)
    {
        return ProdutoFabricante::where('fabricante', $fabricante)
                                ->exists();
    }


    static function updateItem($id_fabricante, $fabricante){
        $update = false;

        if(!ProdutoFabricante::existsItem($id_fabricante)){
            $update = ProdutoFabricante::where('id_fabricante', $id_fabricante)
                                       ->update([
                                           'fabricante' => $fabricante
                                       ]);
        }

        return $update;
    }

    static function deleteItem($id_fabricante){
        try {
            $delete = ProdutoFabricante::where('id_fabricante', $id_fabricante)->delete();
        } catch(\Illuminate\Database\QueryException $ex){ 
            $delete = $ex; 
        }
        
        return $delete;
    }

  
}
