<?php

namespace App\Models\Materiais;

use Illuminate\Database\Eloquent\Model;

class ProdutoFichaTecnica extends Model
{
    protected $table = 'ProdutoFichaTecnica';

    public $timestamps = false;

    protected $fillable = [
        'fichaTecnica', 
        'dt_alteracao'
    ];

    static function index($top = 1000){
        $fichatecnicas = ProdutoFichaTecnica::take($top)->lock('WITH(NOLOCK)')->get();

        return $fichatecnicas;
    }

    static function getWhere($fichatecnica){
        $fichatecnicas = ProdutoFichaTecnica::where('fichaTecnica', $fichatecnica)->lock('WITH(NOLOCK)')->get();

        return $fichatecnicas;
    }

    static function getAtivos(){
        $fichatecnicas = ProdutoFichaTecnica::where('situacao', 'A')
                                            ->join('FichaTecnicaValor', 'FichaTecnicaValor.id_fichaTecnica', '=',  'ProdutoFichaTecnica.id_fichaTecnica')
                                            ->lock('WITH(NOLOCK)')
                                            ->get();

        return $fichatecnicas;
    }

    static function store($fichatecnica)
    {
        $response = false;

        if(!ProdutoFichaTecnica::existsItem($fichatecnica)){
            $response = ProdutoFichaTecnica::insertGetId([
                'fichaTecnica' => $fichatecnica, 
                'dt_alteracao' => Now('America/Fortaleza')
            ]);
        }

        return $response;
    }

    static function updateItem($id_fichaTecnica, $fichatecnica){
        $update = false;

        if(!ProdutoFichaTecnica::existsItem($fichatecnica)){
            $update = ProdutoFichaTecnica::where('id_fichaTecnica', $id_fichaTecnica)->update([
                'fichatecnica' => $fichatecnica, 
                'dt_alteracao' => Now('America/Fortaleza')
            ]);
        }

        return $update;
    }

    static function deleteItem($id_fichaTecnica){
        
        $delete = ProdutoFichaTecnica::where('id_fichaTecnica', $id_fichaTecnica)->delete();

        return $delete;
    }

    static function existsItem($fichatecnica)
    {
        return ProdutoFichaTecnica::where('fichaTecnica', $fichatecnica)->exists();
    }
}
