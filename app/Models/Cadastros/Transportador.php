<?php

namespace App\Models\Cadastros;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Util\Util;

class Transportador extends Model
{
    protected $table = 'Transportador'
    ;

    public $timestamps = false;

    protected $fillable = [
        'id_cadastro',
        'nome_site',
        'transp_ecommerce',
        'cotacao_online',
        'dia_pgto_fat',
        'coleta_dom',
        'hora_col_dom',
        'coleta_seg',
        'hora_col_seg',
        'coleta_ter',
        'hora_col_ter',
        'coleta_qua',
        'hora_col_qua',
        'coleta_qui',
        'hora_col_qui',
        'coleta_sex',
        'hora_col_sex',
        'coleta_sab',
        'hora_col_sab', 
        'situacao'
    ];

    static function index($top = 100){
        $transportadores = Transportador::join('Cadastro', 'Transportador.id_cadastro', '=', 'Cadastro.id_cadastro')
                                        ->take($top)
                                        ->lock('WITH(NOLOCK)')
                                        ->selectRaw("Cadastro.id_cadastro, 
                                          Cadastro.razao_social,
                                          Cadastro.fantasia  ,
                                          Transportador.nome_site, 
                                          Transportador.transp_ecommerce, 
                                          Transportador.cotacao_online, 
                                          Transportador.dia_pgto_fat")
                            ->orderBy('fantasia', 'asc')
                            ->get();
        return $transportadores;
    }

    static function getById($id){
        $clientes = Transportador::where('id_cadastro', $id)
                           ->lock('WITH(NOLOCK)')
                           ->first();

        return $clientes;
    }

    static function store($id_cadastro, $transportador)
    {
        $response = false;

        $response = Transportador::create([
            'id_cadastro'      => $id_cadastro                   ,
            'nome_site'        => $transportador->nomeSite       ,
            'transp_ecommerce' => $transportador->transpEcommerce,
            'cotacao_online'   => $transportador->cotacaoOnline  ,
            'dia_pgto_fat'     => $transportador->diaPgtoFat     ,
            'coleta_dom'       => $transportador->coletaDom      ,
            'hora_col_dom'     => $transportador->horaColDom     ,
            'coleta_seg'       => $transportador->coletaSeg      ,
            'hora_col_seg'     => $transportador->horaColSeg     ,
            'coleta_ter'       => $transportador->coletaTer      ,
            'hora_col_ter'     => $transportador->horaColTer     ,
            'coleta_qua'       => $transportador->coletaQua      ,
            'hora_col_qua'     => $transportador->horaColQua     ,
            'coleta_qui'       => $transportador->coletaQui      ,
            'hora_col_qui'     => $transportador->horaColQui     ,
            'coleta_sex'       => $transportador->coletaSex      ,
            'hora_col_sex'     => $transportador->horaColSex     ,
            'coleta_sab'       => $transportador->coletaSab      ,
            'hora_col_sab'     => $transportador->horaColSab     ,
            'situacao'         => 'A'
        ]);

        return $response;
    }

    static function updateItem($id_cadastro, $transportador){
        $update = false;
            
        $update = Transportador::where('id_cadastro', $id_cadastro)->update([
            'nome_site'        => $transportador->nomeSite       ,
            'transp_ecommerce' => $transportador->transpEcommerce,
            'cotacao_online'   => $transportador->cotacaoOnline  ,
            'dia_pgto_fat'     => $transportador->diaPgtoFat     ,
            'coleta_dom'       => $transportador->coletaDom      ,
            'hora_col_dom'     => $transportador->horaColDom     ,
            'coleta_seg'       => $transportador->coletaSeg      ,
            'hora_col_seg'     => $transportador->horaColSeg     ,
            'coleta_ter'       => $transportador->coletaTer      ,
            'hora_col_ter'     => $transportador->horaColTer     ,
            'coleta_qua'       => $transportador->coletaQua      ,
            'hora_col_qua'     => $transportador->horaColQua     ,
            'coleta_qui'       => $transportador->coletaQui      ,
            'hora_col_qui'     => $transportador->horaColQui     ,
            'coleta_sex'       => $transportador->coletaSex      ,
            'hora_col_sex'     => $transportador->horaColSex     ,
            'coleta_sab'       => $transportador->coletaSab      ,
            'hora_col_sab'     => $transportador->horaColSab     ,
            'situacao'         => $transportador->situacao
        ]);

        return $update;
    }

    static function deleteItem($id_cadastro){
        try {
            $delete = Transportador::where('id_cadastro', $id_cadastro)->delete();
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
