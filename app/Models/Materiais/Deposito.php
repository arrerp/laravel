<?php

namespace App\Models\Materiais;

use Illuminate\Database\Eloquent\Model;
use App\Util\Util;

class Deposito extends Model
{
    protected $table = 'Deposito';

    public $timestamps = false;

    protected $fillable = [
        'id_empresa',
        'descricao',
        'dep_proprio',
        'id_parceiro',
        'situacao',
        'integra_saldo',
        'integra_custo',
        'dt_registro',
        'integra_saldoErp',
    ];

    static function index($top = 1000){
        $depositos = Deposito::join('Empresa' , 'deposito.id_empresa' , '=', 'Empresa.id_empresa')
                             ->join('Cadastro', 'Cadastro.id_cadastro', '=', 'Empresa.id_empresa')
                             ->take($top)
                             ->lock('WITH(NOLOCK)')
                             ->selectRaw("deposito.id_deposito, 
                                          cadastro.fantasia   , 
                                          Deposito.descricao  , 
                                          Deposito.id_empresa ,
                                          CASE WHEN dep_proprio       = 'S' THEN 'Sim' ELSE 'Não' END as dep_proprio, 
                                          CASE WHEN integra_saldo     = 'S' THEN 'Sim' ELSE 'Não' END as integra_saldo, 
                                          CASE WHEN integra_custo     = 'S' THEN 'Sim' ELSE 'Não' END as integra_custo, 
                                          CASE WHEN deposito.situacao = 'A' THEN 'Ativo' ELSE 'Inativo' END as situacao,
                                          CASE WHEN integra_saldoErp  = 'S' THEN 'Sim' ELSE 'Não' END as integra_saldo_erp,
                                          (SELECT Cadastro.fantasia 
                                             FROM Cadastro WITH(NOLOCK)
                                            WHERE Cadastro.id_cadastro = Deposito.id_parceiro) as id_parceiro")
                             ->get();
        return $depositos;
    }

    static function getWhere($deposito){
        $depositos = Deposito::where('descricao', $deposito)->lock('WITH(NOLOCK)')->get();

        return $depositos;
    }

    static function getById($id){
        $depositos = Deposito::where('id_deposito', $id)
                             ->lock('WITH(NOLOCK)')
                             ->first();

        return $depositos;
    }

    static function getByIdEmp($id){
        $depositos = Deposito::where('id_empresa', $id)
                             ->lock('WITH(NOLOCK)')
                             ->get();

        return $depositos;
    }    

    static function store($deposito)
    {
        $response = false;

        $response = Deposito::insertGetId([
            'descricao'     => $deposito->descricao   , 
            'dep_proprio'   => $deposito->depProprio  ,
            'id_empresa'    => $deposito->idEmpresa   ,
            'id_parceiro'   => $deposito->idParceiro  ,
            'situacao'      => 'A'                    ,
            'integra_saldo' => $deposito->integraSaldo,
            'integra_custo' => $deposito->integraCusto,
            'dt_registro'   => Now('America/Fortaleza'), 
            'integra_saldoErp' => $deposito->integraSaldoErp 
        ]);

        return $response;
    }

    static function existsItem($deposito)
    {
        return Deposito::where('descricao', $deposito)->exists();
    }

    static function updateItem($id_deposito, $deposito){
        $update = false;
            
        $update = Deposito::where('id_deposito', $id_deposito)->update([
            'descricao'     => $deposito->descricao   , 
            'id_empresa'    => $deposito->idEmpresa   ,
            'dep_proprio'   => $deposito->depProprio  ,
            'id_parceiro'   => $deposito->idParceiro  ,
            'situacao'      => $deposito->situacao    ,
            'integra_saldo' => $deposito->integraSaldo,
            'integra_custo' => $deposito->integraCusto,
            'dt_registro'   => Now('America/Fortaleza'), 
            'integra_saldoErp' => $deposito->integraSaldoErp 
        ]);

        return $update;
    }

    static function updateSituacao($id){
        $update = false;
            
        $update = Deposito::where('id_deposito', $id)->update([
            'situacao' => 'X'
        ]);

        return $update;
    }

    static function deleteItem($id_deposito){
        try {
        $delete = Deposito::where('id_deposito', $id_deposito)->delete();
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
