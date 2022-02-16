<?php

namespace App\Models\Financeiro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Util\Util;

class Serie extends Model
{
    protected $table = 'Serie';

    public $timestamps = false;

    protected $fillable = [
        'id_empresa',
        'v_serie',
        'id_tpSerie',
        'descricao',
        'doc_seq', 
        'id_modelo'
    ];


    static function index($top = 10000){
        $serie = Serie::join('Empresa'  , 'Serie.id_empresa'  , '=', 'Empresa.id_empresa')
                      ->join('Cadastro' , 'Empresa.id_empresa', '=', 'Cadastro.id_cadastro')
                      ->join('TipoSerie', 'Serie.id_tpSerie'  , '=', 'TipoSerie.id_tpSerie')
                      ->join('ModeloSerie', 'Serie.id_modelo'  , '=', 'ModeloSerie.id_modelo')
                      ->take($top)
                      ->lock('WITH(NOLOCK)')
                      ->selectRaw("RTRIM(Empresa.id_empresa) + ' - ' + RTRIM(Cadastro.razao_social) 'Empresa', 
                                   Empresa.id_empresa ,
                                   Serie.v_serie      , 
                                   TipoSerie.descricao,
                                   ModeloSerie.descricao modelo,
                                   Serie.doc_seq")
                      ->get();
        return $serie;
    }

    static function getById($id_empresa, $v_serie){
        $serie = Serie::where('id_empresa', $id_empresa)
                      ->where('v_serie', $v_serie)
                      ->lock('WITH(NOLOCK)')
                      ->first();

        return $serie;
    }

    static function store($serie)
    {
        $response = false;

        $response = Serie::create([
            'id_empresa' => $serie->idEmpresa,
            'v_serie'    => $serie->vSerie,
            'id_tpSerie' => $serie->idTpSerie,
            'id_modelo'  => $serie->idModelo,
            'descricao'  => $serie->descricao,
            'doc_seq'    => $serie->docSeq
        ]);

        return $response;
    }

    static function updateItem($id_empresa, $v_serie, $serie){
        $update = false;
            
        $update = Serie::where('id_empresa', $id_empresa)
                       ->where('v_serie', $v_serie)
                       ->update(['id_tpSerie' => $serie->idTpSerie,
                                 'id_modelo'  => $serie->idModelo ,
                                 'descricao'  => $serie->descricao,
                                 'doc_seq'    => $serie->docSeq
        ]);

        return $update;
    }

    static function deleteItem($id_empresa, $v_serie){
        try {
            $delete = Serie::where('id_empresa', $id_empresa)
                            ->where('v_serie', $v_serie) 
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
