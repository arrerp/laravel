<?php

namespace App\Models\Logistica;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Util\Util;

class TipoTransporte extends Model
{
    protected $table = 'TipoTransporte'
    ;

    public $timestamps = false;

    protected $fillable = [
        'id_transportador',
        'descricao',
        'id_etiqueta',
        'valido_ecommerce',
        'valido_erp', 
        'situacao',
    ];


    static function index($top = 100){
        $tipoTransporte = TipoTransporte::join('Cadastro', 'TipoTransporte.id_transportador', '=', 'Cadastro.id_cadastro')
                                        ->join('EtiquetaTransporte', 'TipoTransporte.id_etiqueta', '=', 'EtiquetaTransporte.id_etiqueta')
                                        ->take($top)
                                        ->lock('WITH(NOLOCK)')
                                        ->selectRaw("TipoTransporte.id_tipoTransp, 
                                          Cadastro.razao_social,
                                          Cadastro.fantasia  ,
                                          TipoTransporte.descricao descTransp, 
                                          TipoTransporte.valido_ecommerce, 
                                          TipoTransporte.valido_erp, 
                                          TipoTransporte.situacao, 
                                          EtiquetaTransporte.descricao descEtiqueta, 
                                          RTRIM(LTRIM(Cadastro.fantasia)) + ' - ' + RTRIM(LTRIM(TipoTransporte.descricao)) descFull ")
                            ->orderBy('TipoTransporte.id_tipoTransp', 'asc')
                            ->get();
        return $tipoTransporte;
    }

    static function getById($id){
        $tipoTransporte = TipoTransporte::where('id_tipoTransp', $id)
                           ->lock('WITH(NOLOCK)')
                           ->first();

        return $tipoTransporte;
    }

    static function store($tipoTransporte)
    {
        $response = false;

        $response = TipoTransporte::create([
            'id_transportador'  => $tipoTransporte->idTransportador,
            'descricao'         => $tipoTransporte->descricao,
            'id_etiqueta'       => $tipoTransporte->idEtiqueta,
            'valido_ecommerce'  => $tipoTransporte->validoEcommerce,
            'valido_erp'        => $tipoTransporte->validoErp,
            'situacao'          => $tipoTransporte->situacao
        ]);

        return $response;
    }

    static function updateItem($id_tipoTransp, $tipoTransporte){
        $update = false;
            
        $update = TipoTransporte::where('id_tipoTransp', $id_tipoTransp)->update([
            'descricao'         => $tipoTransporte->descricao,
            'id_etiqueta'       => $tipoTransporte->idEtiqueta,
            'valido_ecommerce'  => $tipoTransporte->validoEcommerce,
            'valido_erp'        => $tipoTransporte->validoErp,
            'situacao'          => $tipoTransporte->situacao
        ]);

        return $update;
    }

    static function deleteItem($id_tipoTransp){
        try {
            $delete = TipoTransporte::where('id_tipoTransp', $id_tipoTransp)->delete();
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
