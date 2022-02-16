<?php

namespace App\Models\Financeiro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Util\Util;

class Portador extends Model
{
    protected $table = 'Portador';

    public $timestamps = false;

    protected $fillable = [
        'descricao',
        'pr_desconto',
        'vl_desconto',
        'situacao'
    ];

    static function index($top = 10000){
        $serie = Portador::take($top)
                      ->lock('WITH(NOLOCK)')
                      ->select(['id_portador', 
                                'descricao'  , 
                                'pr_desconto',
                                'vl_desconto',
                                'situacao'
                      ])
                      ->get();
        return $serie;
    }

    static function getById($id_portador){
        $portador = Portador::where('id_portador', $id_portador)
                         ->lock('WITH(NOLOCK)')
                         ->first();

        return $portador;
    }

    static function store($portador)
    {
        $response = false;

        $response = Portador::create([
            'descricao'   => $portador->descricao,
            'pr_desconto' => $portador->prDesconto,
            'vl_desconto' => $portador->vlDesconto,
            'situacao'    => $portador->situacao,
        ]);

        return $response;
    }

    static function updateItem($id_portador, $portador){
        $update = false;
            
        $update = Portador::where('id_portador', $id_portador)
                          ->update(['descricao'   => $portador->descricao,
                                    'pr_desconto' => $portador->prDesconto,
                                    'vl_desconto' => $portador->vlDesconto,
                                    'situacao'    => $portador->situacao,
        ]);

        return $update;
    }

    static function deleteItem($id_portador){
        try {
            $delete = Portador::where('id_portador', $id_portador)
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
