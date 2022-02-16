<?php

namespace App\Models\Venda;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Util\Util;

class CondicaoVenda extends Model
{
    protected $table = 'CondicaoVenda';

    public $timestamps = false;

    protected $fillable = [
        'descricao',
        'tp_prazo',
        'geraTitulo',
        'qt_parcelas',
        'dia1',
        'dia2',
        'dia3',
        'dia4',
        'dia5',
        'dia6',
        'dia7',
        'dia8',
        'dia9',
        'dia10',
        'dia11',
        'dia12',
        'dia13',
        'dia14',
        'dia15',
        'dia16',
        'dia17',
        'dia18',
        'dia19',
        'dia20',
        'dia21',
        'dia22',
        'dia23',
        'dia24',
        'tp_fator',
        'situacao',
        'icone'
    ];


    static function index($top = 10000){
        $condicaoVenda = CondicaoVenda::take($top)
                              ->lock('WITH(NOLOCK)')
                              ->select(['id_condicaoVenda', 'descricao', 'tp_prazo', 'geraTitulo', 'qt_parcelas', 'dia1',
                                        'dia2' , 'dia3' , 'dia4' , 'dia5' , 'dia6' , 'dia7' , 'dia8' , 'dia9' , 'dia10', 'dia11',
                                        'dia12', 'dia13', 'dia14', 'dia15', 'dia16', 'dia17', 'dia18', 'dia19', 'dia20', 'dia21',
                                        'dia22', 'dia23', 'dia24', 'tp_fator', 'situacao', 'icone'])
                              ->get();
        return $condicaoVenda;
    }

    static function getById($id_condicaoVenda){
        $condicaoVenda = CondicaoVenda::where('id_condicaoVenda', $id_condicaoVenda)
                      ->lock('WITH(NOLOCK)')
                      ->first();

        return $condicaoVenda;
    }

    static function store($condicaoVenda)
    {
        $response = false;

        $response = CondicaoVenda::create($condicaoVenda);

        return $response;
    }

    static function updateItem($id_condicaoVenda, $condicaoVenda){
        $update = false;
            
        $update = CondicaoVenda::where('id_condicaoVenda', $id_condicaoVenda)
                        ->update($condicaoVenda);

        return $update;
    }

    static function deleteItem($id_condicaoVenda){
        try {
            $delete = CondicaoVenda::where('id_condicaoVenda', $id_condicaoVenda)
                                    ->delete();
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
