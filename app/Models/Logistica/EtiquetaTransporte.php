<?php

namespace App\Models\Logistica;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Util\Util;

class EtiquetaTransporte extends Model
{
    protected $table = 'EtiquetaTransporte'
    ;

    public $timestamps = false;

    protected $fillable = [
        'descricao',
        'html',
        'situacao'
    ];

    static function index($top = 1000){
        $etiquetaTransporte = EtiquetaTransporte::take($top)->lock('WITH(NOLOCK)')->get();

        return $etiquetaTransporte;
    }


    static function getById($id){
        $etiquetaTransporte = EtiquetaTransporte::where('id_etiqueta', $id)
                           ->lock('WITH(NOLOCK)')
                           ->first();

        return $etiquetaTransporte;
    }

    static function store($etiquetaTransporte)
    {
        $response = false;

        $response = EtiquetaTransporte::create([
            'descricao'  => $etiquetaTransporte->descricao,
            'html'       => $etiquetaTransporte->html,
            'situacao'   => $etiquetaTransporte->situacao
        ]);

        return $response;
    }

    static function updateItem($id_etiqueta, $etiquetaTransporte){
        $update = false;
            
        $update = EtiquetaTransporte::where('id_etiqueta', $id_etiqueta)->update([
            'descricao'  => $etiquetaTransporte->descricao,
            'html'       => $etiquetaTransporte->html,
            'situacao'   => $etiquetaTransporte->situacao
        ]);

        return $update;
    }

    static function deleteItem($id_cadastro){
        try {
            $delete = EtiquetaTransporte::where('id_etiqueta', $id_etiqueta)->delete();
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
