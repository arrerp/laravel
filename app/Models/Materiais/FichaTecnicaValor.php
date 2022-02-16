<?php

namespace App\Models\Materiais;

use Illuminate\Database\Eloquent\Model;

class FichaTecnicaValor extends Model
{
    protected $table = 'FichaTecnicaValor';

    public $timestamps = false;

    protected $fillable = [
        'id_fichaTecnica', 
        'valor',
        'situacao'
    ];

    static function index($id_fichaTecnica){
        $fichaValores = FichaTecnicaValor::where('id_fichaTecnica', $id_fichaTecnica)
                                         ->lock('WITH(NOLOCK)')
                                         ->get();

        return $fichaValores;
    }

    static function getWhere($fichaValor){
        $fichaValores = FichaTecnicaValor::where('fichaTecnica', $fichaValor->id_fichaTecnica)
                                         ->where('valor', $fichaValor->valor)
                                         ->lock('WITH(NOLOCK)')
                                         ->get();

        return $fichaValores;
    }

    static function store($fichaValor)
    {
        $response = false;

        if(!FichaTecnicaValor::existsItem($fichaValor)){
            $response = FichaTecnicaValor::insertGetId([
                'id_fichaTecnica' => $fichaValor->id_fichaTecnica, 
                'valor' => $fichaValor->valor,
                'situacao' => 'A'
            ]);
        }

        return $response;
    }

    static function existsItem($fichaValor)
    {
        return FichaTecnicaValor::where('id_fichaTecnica', $fichaValor->id_fichaTecnica)
                                ->where('valor', $fichaValor->valor)
                                ->exists();
    }


    static function updateItem($id_fichaValor, $fichaValor){
        $update = false;

        if(!FichaTecnicaValor::existsItem($fichaValor)){
            $update = FichaTecnicaValor::where('id_fichaValor', $id_fichaValor)->update([
                'valor' => $fichaValor->valor
            ]);
        }

        return $update;
    }

    static function updateSituacao($id){
        $update = false;
            
        $update = FichaTecnicaValor::where('id_fichaValor', $id)
                         ->update([
                            'situacao' => 'X'
                         ]);

        return $update;
    }      

    static function deleteItem($id_fichaValor){
        try {
        $delete = FichaTecnicaValor::where('id_fichaValor', $id_fichaValor)->delete();
        } catch(\Illuminate\Database\QueryException $ex){ 
            $delete = $ex; 
        }
        return $delete;
    }
}
